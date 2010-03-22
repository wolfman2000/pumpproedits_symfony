<?php

class EditParser
{
  private function gen_measure($cols, $step = false, $routine = false)
  {
    $line = str_repeat("0", $cols) . "\r\n";
    $measure = str_repeat($line, 4);
    if ($step)
    {
      $measure = substr_replace($measure, "1", ($routine ? 7 : 2), 1);
    }
    return $measure;
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

    $cols = $this->getCols($kind);
    
    $allM = $this->gen_measure($cols);
    for ($i = 2; $i <= $measures; $i++)
    {
      $allM .= sprintf(",  // measure %s%s", $i, $eol);
      $allM .= $this->gen_measure($cols, true);
    }
    
    if ($kind === "routine")
    {
      $allM .= sprintf("&%s", $eol, $allM);
      $allM .= $this->gen_measure($cols);
      for ($i = 2; $i <= $measures; $i++)
      {
        $allM .= sprintf(",  // measure %s%s", $i, $eol);
        $allM .= $this->gen_measure($cols, true, true);
      }
    }
    
    $allM .= sprintf(";%s", $eol);
    fwrite($fh, $allM);
    fclose($fh);
    return true;
  }

  public function generate_base($songid)
  {
    $base = Doctrine::getTable('PPE_Song_Song')->getSongRow($songid);
    foreach (array("single", "double", "halfdouble", "routine") as $kind)
    {
      $this->gen_edit_file($kind, $base->getName(), $base->getAbbr(), $base->getMeasures());
    }

  }
  
  protected function getCols($style)
  {
    switch ($style)
    {
      case "pump-single": return 5;
      case "pump-double": return 10;
      case "pump-routine": return 10;
      case "pump-halfdouble": return 6;
      default: return 5; // Lazy right now.
    }
  }
  
  protected function getOfficialStyle($style, $title)
  {
    switch ($style)
    {
      case "pump-single":
      {
        switch ($title)
        {
          case "Beginner": return "Easy";
          case "Easy": return "Normal";
          case "Medium": return "Hard";
          case "Hard": return "Crazy";
        }
      }
      case "pump-double":
      {
        return $title == "Hard" ? "Nightmare" : "Freestyle";
      }
      case "pump-halfdouble": return "Halfdouble";
      case "pump-routine": return "Routine";
      default: return "Undefined"; // Lazy right now.
    }
  }
  
  /**
   * Check if this line is really blank or is a line comment.
   */
  private function checkCommentLine($line)
  {
    $line = ltrim($line);
    if (strlen($line) === 0 or strpos($line, "//") === 0)
    {
      return true;
    }
    return false;
  }
  
  protected function getOfficialAbbr($diff)
  {
    switch ($diff)
    {
      case "Easy": return "ez";
      case "Normal": return "nr";
      case "Hard": return "hr";
      case "Crazy": return "cz";
      case "Halfdouble": return "hd";
      case "Freestyle": return "fs";
      case "Nightmare": return "nm";
      case "Routine": return "rt";
      default: return "xx";
    }
  }

 /**
  * Pass a file handle, get the note data.
  * Return the notes themselves ONLY when asked.
  *
  * This code uses alternative control syntax a lot to keep indentation low.
  */
  public function get_stats($fh, $params = array())
  {
    $res = array(); # Return variables go in here.
    # Make all of these an array to allow for routine steps.
    $steps = array(0 => 0, 1 => 0);
    $jumps = array(0 => 0, 1 => 0);
    $holds = array(0 => 0, 1 => 0);
    $mines = array(0 => 0, 1 => 0);
    $trips = array(0 => 0, 1 => 0);
    $rolls = array(0 => 0, 1 => 0);
    $lifts = array(0 => 0, 1 => 0);
    $fakes = array(0 => 0, 1 => 0);

    $steps_on = array();
    $holds_on = array();
    $actve_on = array();
    $notes = array(0 => array(), 1 => array());
    $state = $diff = $cols = $measure = $songid = 0;
    $title = $song = $style = "";
    $base = Doctrine::getTable('PPE_Song_Song');
    
    if (!array_key_exists('strict_song', $params)) { $params['strict_song'] = true; }
    if (!array_key_exists('strict_edit', $params)) { $params['strict_edit'] = true; }
    if (!array_key_exists('arcade', $params)) { $params['arcade'] = false; }

    $numl = 0;
    while(!feof($fh)):

    $numl++;
    $line = rtrim(fgets($fh));

    switch ($state):

    case 0: /* Initial state: verify first line and song title.*/
    {
      if ($this->checkCommentLine($line)) { break; }
      $key = $params['arcade'] ? "#TITLE:" : "#SONG:";
      $pos = strpos($line, $key, 0);
      if ($pos !== 0)
      {
        $s = "The first line must contain \"$key\" in it.";
        throw new sfParseException($s);
      }
      $pos = strpos($line, ";");
      if ($pos === false)
      {
        $s = "This line needs a semicolon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      $line = rtrim($line, ";");
      
      $song = substr($line, strlen($key));
      
      $slash = strpos($song, "//");
      if ($slash !== false)
      {
        $song = substr($song, 0, $slash);
      }
      
      $slash = strpos($song, "/");
      while ($slash !== false)
      {
      
        $song = substr($song, $slash + 1);
        $slash = strpos($song, "/");
      }
      
      if (strpos($song, ";") !== false)
      {
        $song = substr($song, 0, strpos($song, ";"));
      }
      
      $songid = $base->getIDBySong($song);
      
      if ($params['strict_song'])
      {
        
        if (!$songid)
        {
          $s = "This song is not found in the database: %s. ";
          $s .= "Make sure you spelt it right.";
          throw new sfParseException(sprintf($s, $song));
        }
      }
      else
      {
        if (!$songid) $songid = -1;
      }
      $state = $params['arcade'] ? 10 : 1; # The song exists. We can move on.
      break;
    }
    case 10: /* Idle until we find a notes tag. */
    {
      if (trim($line) === "#NOTES:")
      {
        $state = 2;
      }
      break;
    }
    case 1: /* Verify NOTES tag is present next. */
    {
      if ($this->checkCommentLine($line)) { continue; }
      if (strpos($line, "#NOTES:", 0) !== 0)
      {
        $s = "The #NOTES: tag must be on line 2.";
        throw new sfParseException($s);
      }
      $state = 2;
      break;
    }
    case 2: /* Confirm this is pump-single, double, halfdouble, or routine. */
    {
      if ($this->checkCommentLine($line)) { continue; }
      $line = ltrim($line);
      $pos = strpos($line, ":", 0);
      if ($pos === false)
      {
        $s = "This line needs a colon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      $style = substr($line, 0, $pos - strlen($line));
      if (!in_array($style, array("pump-single", "pump-double", "pump-halfdouble", "pump-routine")))
      {
        if ($params['arcade'])
        {
          $state = 10; // don't deal with extraneous modes.
          break;
        }
        else
        {
          $s = "The style %s is invalid. Use pump-single, double, halfdouble, or routine.";
          throw new sfParseException(sprintf($s, $style));
        }
      }
      $state = 3;
      break;
    }
    case 3: /* Get the title / author of the edit. No blank names Dread. ☻ */
    {
      if ($this->checkCommentLine($line)) { continue; }
      $line = ltrim($line);
      $pos = strpos($line, ":", 0);
      if ($pos === false)
      {
        $s = "This line needs a colon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      
      if ($params['arcade']) // Different rules:
      {
        if ($pos)
        {
          $author = substr($line, 0, $pos - strlen($line));
        }
        else
        {
          $author = 'Someone';
        }
      }
      else
      {
        if ($pos === 0)
        {
          if ($params['strict_edit'])
          {
            $s = "Blank edit names are no longer allowed.";
            throw new sfParseException($s);
          }
          else
          {
            $title = "JDread Law ☻";
          }
        }
        else
        {
          $title = substr($line, 0, $pos - strlen($line));
        }
        $maxlen = sfConfig::get('app_max_edit_name_length');
        $titlen = mb_strlen($title);
        if ($titlen > $maxlen and $params['strict_edit'])
        {
          $s = 'The edit titled "%s" is %d characters too long.';
          throw new sfParseException(sprintf($s, $title, $titlen - $maxlen));
        }
        $author = false;
      }
      $state = 4;
      break;
    }
    case 4: /* Arcade mode: get title here. Otherwise, ensure the "Edit:" line is in place. */
    {
      if ($this->checkCommentLine($line)) { continue; }
      $line = ltrim($line);
      $pos = strpos($line, ":", 0);
      if ($pos === false)
      {
        $s = "This line needs a colon at the end: %s";
        throw new sfParseException(sprintf($s, $line));
      }
      $line = substr($line, 0, $pos - strlen($line));
      if ($params['arcade'])
      {
        $title = $this->getOfficialStyle($style, $line); // set title now.
        if ($params['arcade'] !== $this->getOfficialAbbr($title))
        {
          $state = 10;
          break;
        }
      }
      elseif ($line !== "Edit" and !$params['arcade']) // temp measure.
      {
        $s = 'The edit must have "Edit:" on a new line after the title.';
        throw new sfParseException($s);
      }
      $state = 5;
      break;
    }
    case 5: /* Get the difficulty level of the edit. */
    {
      if ($this->checkCommentLine($line)) { continue; }
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
      if ($this->checkCommentLine($line)) { continue; }
      $cols = $this->getCols($style);

      for ($dummy = 0; $dummy < $cols; $dummy++)
      {
        $holds_on[] = 0;
        $actve_on[] = 0;
        $steps_on[] = 0;
      }
      $notes[] = array();
      $state = 7;
      $side = 0; // routine compatible switch.
      break;
    }
    case 7: /* Finally at step content. Read until ; is first. */
    {
      $line = ltrim($line);
      if (substr($line, 0, 1) === ",") /* New measure upcoming. */
      {
        $measure++;
        $notes[$side][] = array();
      }
      elseif (substr($line, 0, 1) === "&") /* New routine step partner. */
      {
        $side = 1;
        $measure = 0;
      }
      elseif (substr($line, 0, 1) === ";") /* Should be EOF */
      {
        if ($params['arcade']) { break 2; }
        $state = 8;
        
      }
      elseif (!$this->checkCommentLine($line)) // Parse.
      {
        $steps_per_row = 0;
        $row = substr($line, 0, $cols);
        $notes[$side][$measure][] = $row;

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
            $holds[$side]++;
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
            $rolls[$side]++;
            break;
          }
          case "M": // Mine
          {
            $holds_on[$i] = 0;
            $mines[$side]++;
            break;
          }
          case "L": // Lift note (not fully implemented)
          {
            $holds_on[$i] = 0;
            $lifts[$side]++;
            break;
          }
          case "F": // Fake note (will be in future builds)
          {
            $holds_on[$i] = 0;
            $fakes[$side]++;
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
        if ($steps_per_row > 0 and array_sum($actve_on) >= 3) { $trips[$side]++; }
        if ($steps_per_row >= 2) { $jumps[$side]++; }
        if ($steps_per_row) { $steps[$side]++; }
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
    
    if ($params['arcade'] and !count($notes))
    {
      throw new sfParseException("The chosen song / difficulty combination doesn't exist! Please choose another.");
    }
    
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
    $res['author'] = $author;
    if (isset($params['notes']) and $params['notes']) { $res['notes'] = $notes; }
    return $res;
  }
}
