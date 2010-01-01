<?php

class PPE_Edit_EditTable extends Doctrine_Table
{
  public function getEditsBySong($songid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts';
    $cols .= ', user_id, b.name uname, title, is_single';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_User_User b')
      ->where('song_id = ?', $songid)
      ->orderBy('b.lc_name, a.title')
      ->execute();
      
  }
}
