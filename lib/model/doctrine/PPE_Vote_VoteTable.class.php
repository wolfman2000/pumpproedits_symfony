<?php

class PPE_Vote_VoteTable extends Doctrine_Table
{
  public function getVotesByID($editid)
  {
    $cols = 'user_id, u.name, rating, reason';
    return $this->createQuery('a')->select($cols)
      ->innerJoin('a.PPE_User_User u')->where('edit_id = ?', $editid)
      ->andWhere('is_problem = ?', false)
      ->orderBy('created_at')->execute();
  }
  
  public function getRatingByID($id)
  {
    return $this->createQuery('a')->select('rating, is_problem')
      ->where('id = ?', $id)->fetchOne();
  }
}
