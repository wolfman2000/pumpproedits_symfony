<?php

class PPE_User_UserTable extends Doctrine_Table
{
  public function getIDByEmail($email)
  {
    $q = $this->createQuery('a')->select('id')->where('email = ?', $email);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
  
  public function getIDByUser($user)
  {
    $q = $this->createQuery('a')->select('id')->where('name = ?', $user);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
}
