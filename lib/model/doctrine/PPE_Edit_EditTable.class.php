<?php

class PPE_Edit_EditTable extends Doctrine_Table
{
  public function getEditsBySong($songid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts';
    $cols .= ', user_id';
    return $this->createQuery('a')
      ->select($cols)
      ->where('song_id = ?', $songid)
      ->execute();
      
  }
}
