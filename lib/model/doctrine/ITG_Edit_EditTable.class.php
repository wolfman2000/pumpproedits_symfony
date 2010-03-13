<?php

class ITG_Edit_EditTable extends Doctrine_Table
{
  public function getEditsByUser($userid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls';
    $cols .= ', song_id, b.name sname, title, is_single, old_edit_id';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.ITG_Song_Song b')
      ->where('user_id = ?', $userid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
  }
  public function getEditsBySong($songid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls';
    $cols .= ', user_id, b.name uname, title, is_single, old_edit_id';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.ITG_User_User b')
      ->where('song_id = ?', $songid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
  }
  
  public function getFileName($eid)
  {
    return $this->createQuery('e')
      ->select('e.is_single style, e.diff d, e.title t, s.abbr sabbr, u.name uname')
      ->innerJoin('e.ITG_Song_Song s')
      ->innerJoin('e.ITG_User_User u')
      ->where('e.old_edit_id = ?', $eid)
      ->fetchOne();
  }
}
