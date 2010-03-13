<?php

class ITG_User_UserTable extends Doctrine_Table
{
  public function getUsersWithEdits()
  {
    return $this->createQuery('a')->select('a.name  core, a.id, COUNT(b.id) AS num_edits')
      ->innerJoin('a.ITG_Edit_Edits b')
      ->orderBy('a.lc_name')
      ->groupBy('a.name, b.user_id')
      ->execute();
  
    /*
    return $this->createQuery('a')->select('name core, num_edits')
      ->where('num_edits > ?', 0)
      ->orderBy('lc_name')->execute();
    */
  }
}
