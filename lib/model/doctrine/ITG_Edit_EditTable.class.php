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
}
