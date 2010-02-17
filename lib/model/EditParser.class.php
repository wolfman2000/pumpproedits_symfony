<?php

class EditParser
{
  protected function gen_measure($cols, $step = false)
  {
    $line = str_repeat("0", $cols) . "\r\n";
    $measure = str_repeat($line, 4);
    if ($step)
    {
      $measure = substr_replace($measure, "1", 2, 1);
    }
  }

  protected function gen_edit_file($kind, $name, $abbr, $measures)
  {
    $fname = sprintf("base_%s_%s.edit", $abbr, $kind);
    $eol = "\r\n";
    $loc = sfConfig::get('sf_data_dir') . '/base_edits';
    $fh = fopen($loc . '/' . $fname, 'w');

    /* File is opened: now write the headers. */

    fwrite($fh, sprintf("#SONG:%s%s#NOTES:%s", $name, $eol, $eol));
    fwrite($fh, sprintf("     pump-%s:%s", $kind, $eol));
    fwrite($fh, sprintf("     NameEditHere:%s", $eol));
    fwrite($fh, sprintf("     Edit:%s     10:%s     ", $eol, $eol));
    fwrite($fh, sprintf("0, 0, 0, 0, 0, %d, 0, 0, 0, 0, 0, ", $measures - 1));
    fwrite($fh, sprintf("0, 0, 0, 0, 0, %d, 0, 0, 0, 0, 0%s%s", $measures - 1, $eol, $eol));

    $cols = ($kind === "double" ? 10 : 5);

    fwrite($fh, $this->gen_measure($cols));

    for ($i = 2; $i <= $measures; $i++)
    {
      fwrite($fh, sprintf(",  // measure %s%s", $i, $eol));
      fwrite($fh, $this->gen_measure($cols, true));
    }

    fwrite($fh, sprintf(";%s", $eol));
    fclose($fh);
    return true;
  }

  public function generate_base($songid)
  {
    $base = Doctrine::getTable('PPE_Song_Song')->getSongRow($songid);
    $this->gen_edit_file('single', $base->getName(), $base->getAbbr(), $base->getMeasures());
    $this->gen_edit_file('double', $base->getName(), $base->getAbbr(), $base->getMeasures());

  }

 /**
  * Pass a file handle, get the note data.
  * Return the notes themselves ONLY when asked.
  *
  * This code uses alternative control syntax a lot to keep indentation low.
  */
  public function get_stats($fh, $inc_notes = false, $strict_song = true)
  {
    $res = array(); # Return variables go in here.
    $steps = $jumps = $holds = $mines = $trips = $rolls = $lifts = $fakes = 0;
    $steps_on = array();
    $holds_on = array();
    $actve_on = array();
    $notes = array();
    $state = $diff = $cols = $measure = $songid = 0;
    $title = $song = $style = "";
    $base = Doctrine::getTable('PPE_Song_Song');

    $numl = 0;
    while(!feof($fh)):

    $numl++;
    $line = rtrim(fgets($fh));

    switch ($state):

    case 0: /* Initial state: verify first line and song title.*/
    {
      $pos = strpos($line, "#SONG:", 0);
      if ($pos !== 0)
      {
        $s = 'The first line must contain "#SONG:" in it.';
        throw new sfParseException($s);
      }
      $pos = strpos($line, ";");
      if ($pos === false)
      {
        $s = "This line needs a semicolon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      $song = substr($line, 6, $pos - strlen($line));
      if ($strict_song)
      {
        $songid = $base->getIDBySong($song);
        if (!$songid)
        {
          $s = "This song is not found in the database: %s. ";
          $s .= "Make sure you spelt it right.";
          throw new sfParseException(sprintf($s, $song));
        }
      }
      else
      {
        $songid = -1;
      }
      $state = 1; # The song exists. We can move on.
      break;
    }
    case 1: /* Verify NOTES tag is present next. */
    {
      if ($line === "" or strpos($line, "//", 0) === 0) { continue; }
      if (strpos($line, "#NOTES:", 0) !== 0)
      {
        $s = "The #NOTES: tag must be on line 2.";
        throw new sfParseException($s);
      }
      $state = 2;
      break;
    }
    case 2: /* Confirm this is pump-single or pump-double. */
    {
      $line = ltrim($line);
      $pos = strpos($line, ":", 0);
      if ($pos === false)
      {
        $s = "This line needs a colon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      $style = substr($line, 0, $pos - strlen($line));
      if (!in_array($style, array("pump-single", "pump-double")))
      {
        $s = "The style %s is invalid. Use pump-single or pump-double.";
        throw new sfParseException(sprintf($s, $style));
      }
      $state = 3;
      break;
    }
    case 3: /* Get the title of the edit. No blank names Dread. ☻ */
    {
      $line = ltrim($line);
      $pos = strpos($line, ":", 0);
      if ($pos === false)
      {
        $s = "This line needs a colon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      if ($pos === 0)
      {
        $s = "Blank edit names are no longer allowed.";
        throw new sfParseException($s);
      }
      $title = substr($line, 0, $pos - strlen($line));
      $maxlen = sfConfig::get('app_max_edit_name_length');
      $titlen = strlen($title);
      if ($titlen > $maxlen)
      {
        $s = 'The edit titled "%s" is %d characters too long.';
        throw new sfParseException(sprintf($s, $title, $titlen));
      }
      $state = 4;
      break;
    }
    case 4: /* Ensure the "Edit:" line is in place. */
    {
      $line = ltrim($line);
      if ($line !== "Edit:")
      {
        $s = 'The edit must have "Edit:" on a new line after the title.';
        throw new sfParseException($s);
      }
      $state = 5;
      break;
    }
    case 5: /* Get the difficulty level of the edit. */
    {
      $line = ltrim($line);
      $pos = strpos($line, ":", 0);
      if ($pos === false)
      {
        $s = "This line needs a colon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }

      $diff = (int) $line;
      if ($diff != $line) /* Unsure of !== here. */
      {
        $s = "The difficulty must be a positive integer. You gave: %d ";
        throw new sfParseException(sprintf($s, $line));
      }
      $mindiff = sfConfig::get('app_min_difficulty_rating');
      $maxdiff = sfConfig::get('app_max_difficulty_rating');
      if (!($mindiff <= $diff and $diff <= $maxdiff))
      {
        $s = "The difficulty rating %d must be between %d and %d.";
        throw new sfParseException(sprintf($s, $diff, $mindiff, $maxdiff));
      }
      $state = 6;
      break;
    }
    case 6: /* Radar line: use this time to prep other variables. */
    {
      $cols = ($style === "pump-single" ? 5 : 10);

      for ($dummy = 0; $dummy < $cols; $dummy++)
      {
        $holds_on[] = 0;
        $actve_on[] = 0;
        $steps_on[] = 0;
      }
      $notes[] = array();
      $state = 7;
      break;
    }
    case 7: /* Finally at step content. Read until ; is first. */
    {
      $line = ltrim($line);
      if (substr($line, 0, 1) === ",") /* New measure upcoming. */
      {
        $measure++;
        $notes[] = array();
      }
      elseif (substr($line, 0, 1) === ";") /* Should be EOF */
      {
        $state = 8;
      }
      elseif (!($line === "" or strpos($line, "//", 0) === 0)) // Parse.
      {
        $steps_per_row = 0;
        $row = substr($line, 0, $cols);
        $notes[$measure][] = $row;

        for ($i = 0; $i < $cols; $i++)
        {
          $steps_on[$i] = 0; // Reset previous info.
          $char = substr($row, $i, 1);
          switch ($char):

          case "0": // Empty space
          {
            break;
          }
          case "1": // Tap note
          {
            $holds_on[$i] = 0;
            $steps_on[$i] = 1;
            $steps_per_row++;
            break;
          }
          case "2": // Start of hold note
          {
            $holds_on[$i] = 1;
            $steps_on[$i] = 1;
            $steps_per_row++;
            $holds++;
            break;
          }
          case "3": // End of hold/roll note
          {
            $holds_on[$i] = 0;
            $steps_on[$i] = 1; // Triple check. (Why did I have this?)
            break;
          }
          case "4": // Start of roll note
          {
            $holds_on[$i] = 1;
            $steps_on[$i] = 1;
            $steps_per_row++;
            $rolls++;
            break;
          }
          case "M": // Mine
          {
            $holds_on[$i] = 0;
            $mines++;
            break;
          }
          case "L": // Lift note (not fully implemented)
          {
            $holds_on[$i] = 0;
            $lifts++;
            break;
          }
          case "F": // Fake note (will be in future builds)
          {
            $holds_on[$i] = 0;
            $fakes;
            break;
          }
          default: // Invalid data found.
          {
            $n = "0, 1, 2, 3, 4, M, L, F";
            $s = "Line %d has an invalid note %s. Stick with %s.";
            throw new sfParseException(sprintf($s, $numl, $char, $n));
          }
          endswitch;

        }
        for ($i = 0; $i < $cols; $i++)
        {
          $actve_on[$i] = ($holds_on[$i] === 1 or $steps_on[$i] === 1 ? 1 : 0);
        }
        if ($steps_per_row > 0 and array_sum($actve_on) >= 3) { $trips++; }
        if ($steps_per_row >= 2) { $jumps++; }
        if ($steps_per_row) { $steps++; }
      }
      break;
    }

    case 8: /* Ensure no non-comment data is after the end. */
    {
      $line = ltrim($line);
      if (!($line === "" or strpos($line, "//", 0) === 0))
      {
        $s = "Remove all data on and after line %d of your file.";
        throw new sfParseException(sprintf($s, $numl));
      }
      break;
    }
    endswitch;
    endwhile;
    $res['id'] = $songid;
    $res['song'] = $song;
    $res['diff'] = $diff;
    $res['cols'] = $cols;
    $res['style'] = $style;
    $res['title'] = $title;
    $res['steps'] = $steps;
    $res['jumps'] = $jumps;
    $res['holds'] = $holds;
    $res['mines'] = $mines;
    $res['trips'] = $trips;
    $res['rolls'] = $rolls;
    $res['lifts'] = $lifts;
    $res['fakes'] = $fakes;
    if ($inc_notes) { $res['notes'] = $notes; }
    return $res;
  }
}
