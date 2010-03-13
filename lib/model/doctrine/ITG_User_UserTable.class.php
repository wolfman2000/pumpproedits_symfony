<?php

class ITG_User_UserTable extends Doctrine_Table
{
  public function getUsersWithEdits()
  {
    return $this->createQuery('a')->select('a.name core, a.id, COUNT(b.id) AS num_edits')
      ->innerJoin('a.ITG_Edit_Edits b')
      ->orderBy('a.lc_name')
      ->groupBy('a.name, b.user_id')
      ->execute();
  }
}
