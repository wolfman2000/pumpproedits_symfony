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

    for ($i = 2, $i <= $measures; $i++)
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
  public function get_stats($fh, $inc_notes = false)
  {
    $res = array(); # Return variables go in here.
    $steps = $jumps = $holds = $mines = $trips = $rolls = $lifts = $fakes = 0;
    $steps_on = array();
    $holds_on = arary();
    $actve_on = array();
    $notes = array();
    $state = $diff = $cols = $measure = $songid = 0;
    $title = $song = $style = "";

    $numl = 0;
    while(!feof($fh)):

    $numl++;
    $line = rtrim(fgets($fh));

    switch ($state):

    case 0: /* Initial state: verify first line and song title.*/
    {
      $pos = strpos($line, "#SONG:", 0)
      if $pos !== 0
      {
        # Exception code here.
      }
      break;
    }
    case 1:
    {

    }
    endswitch;
    endwhile;
  }
}
