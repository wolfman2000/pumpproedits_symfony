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

  public function setPassword($id, $pass)
  {
    $pepper = hash("sha256", $pass . $this->getSalt($id));
    return $this->createQuery('a')->update()->set('pepper', '?', $pepper)
      ->where('id = ?', $id)->execute();
  }
  
  public function setPepper($id, $pass)
  {
    return $this->setPassword($id, $pass);
  }

  public function getIDByOregano($oregano)
  {
    return $this->createQuery('a')->select('id')
      ->where('oregano = ?', $oregano)->fetchOne()->id;
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
      ->innerJoin('a.PPE_User_User b')->where('b.lc_name = ?', strtolower($name))
      ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    
    return ($q ? $this->checkPassword($q['salt'], $pass) : false);
  }
  
  public function getSalt($id)
  {
    return $this->createQuery('a')->select('salt')
      ->where('id = ?', $id)->fetchOne()->salt;
  }
  
  public function updateOregano($id)
  {
    $value = hash("md5", date("YmdHis") . $this->getSalt($id));
    return $this->createQuery('a')->update()->set('oregano', '?', $value)
      ->where('id = ?', $id)->execute();
  }
  
  public function getOregano($id)
  {
    return $this->createQuery('a')->select('oregano')
      ->where('id = ?', $id)->fetchOne()->oregano;
  }
}
