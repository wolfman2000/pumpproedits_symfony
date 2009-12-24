<?php

class Utilities
{
  public function genSalt($length = 5)
  {
    // random number between 48 and 126 inclusive
    if ($length < 1) { return; }
    $slt = '';
    for ($i = 0; $i < $length; $i++)
    {
      $min = sfConfig::get('app_min_salt_char');
      $max = sfConfig::get('app_max_salt_char');
      $slt .= chr(rand($min, $max));
    }
    return $slt;
  }
}