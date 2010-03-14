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
  
  public function getNameByOldEditID($oid)
  {
    return $this->createQuery('a')->select('a.name aname')
      ->innerJoin('a.ITG_Edit_Edits b')
      ->where('b.old_edit_id = ?', $oid)
      ->fetchOne()->aname;
  }
  
  public function getNameByID($id)
  {
    $q = $this->createQuery('a')->select('name')->where('id = ?', $id)
      ->fetchOne(array(), Doctrine::HYDRATE_ARRAY);
    return $q['name'];
  }
  
  public function getUserByID($id)
  {
    return $this->getNameByID($id);
  }
}
