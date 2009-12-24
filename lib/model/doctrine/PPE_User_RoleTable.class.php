<?php

class PPE_User_RoleTable extends Doctrine_Table
{
  public function getIDByRole($role)
  {
    $q = $this->createQuery('a')->select('id')->where('role = ?', $role);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }

  public function getIsUserBanned($id)
  {
    $query = $this->createQuery('a')->select('a.role');
    $query = $query->innerJoin('a.PPE_User_Powers b')->where('b.user_id = ?', $id)->andWhereIn('a.role', array('banned', 'forbidden'));
    return $query->count(); // Return the number of rows.
  }
}
