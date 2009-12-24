<?php

class PPE_User_UserTable extends Doctrine_Table
{
  public function getIDByEmail($email)
  {
    $q = $this->createQuery('a')->select('id')->where('email = ?', strtolower($email));
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
  
  public function getIDByUser($user)
  {
    $q = $this->createQuery('a')->select('id')->where('lc_name = ?', strtolower($user));
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
  
  public function getConfirmedByID($id)
  {
    $q = $this->createQuery('a')->select('is_confirmed')->where('id = ?', $id);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['is_confirmed'];
  }
}
