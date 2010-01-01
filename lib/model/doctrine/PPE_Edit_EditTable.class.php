<?php

class PPE_Edit_EditTable extends Doctrine_Table
{
  public function getEditsBySong($songid)
  {
    return $this->createQuery('a')
      ->select('diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts')
      ->where('song_id = ?', $songid)
      ->execute();
      
  }
}
