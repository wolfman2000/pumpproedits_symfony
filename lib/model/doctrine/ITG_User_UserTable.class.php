<?php

class ITG_User_UserTable extends Doctrine_Table
{
  public function getUsersWithEdits()
  {
    return $this->createQuery('a')->select('name core, num_edits')
      ->where('num_edits > ?', 0)
      ->orderBy('lc_name')->execute();
  }
}
