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

  }

  public function generate_base($songid)
  {

  }

  public function get_stats($data, $inc_notes = false)
  {

  }
}
