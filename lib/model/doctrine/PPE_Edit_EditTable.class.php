<?php

class PPE_Edit_EditTable extends Doctrine_Table
{
  public function getEditsBySong($songid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts';
    $cols .= ', user_id, b.name uname, title, is_single, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_User_User b')
      ->where('song_id = ?', $songid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
      
  }
  
  public function getEditsByUser($userid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts';
    $cols .= ', song_id, b.name sname, title, is_single, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_Song_Song b')
      ->where('user_id = ?', $userid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
      
  }
  
  public function getProblemByID($id)
  {
    return $this->createQuery('a')->select('is_problem')
      ->where('id = ?', $id)->fetchOne()->is_problem;
  }
  
  public function confirmExistence($id)
  {
    return $this->createQuery('a')->select('title')
      ->where('id = ?', $id)->count();
  }
}
