<?php

class PPE_User_CondimentTable extends Doctrine_Table
{
  private function checkPassword($salt, $pass)
  {
    $pepper = hash("sha256", $pass . $salt);
    $q = $this->createQuery('a')->select('id')->where('pepper = ?', $pepper)
      ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }

  public function confirmUser($oregano, $pass)
  {
    $q = $this->createQuery('a')->select('salt')
      ->where('oregano = ?', $oregano)
      ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    
    return ($q ? $this->checkPassword($q['salt'], $pass) : false);
  }
  
  public function checkUser($name, $pass)
  {
    $q = $this->createQuery('a')->select('salt')
      ->innerJoin('a.PPE_User_User b')->where('b.name = ?', $name)
      ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    
    return ($q ? $this->checkPassword($q['salt'], $pass) : false);
  }
}
