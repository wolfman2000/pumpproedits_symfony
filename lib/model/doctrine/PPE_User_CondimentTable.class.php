<?php

class PPE_User_CondimentTable extends Doctrine_Table
{
  public function confirmUser($oregano, $pass)
  {
    $q = $this->createQuery('a')->select('salt')->where('oregano = ?', $oregano);
    $row = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    
    if ($row == false) { return false; }
    
    $pepper = hash("sha256", $pass . $row['salt']);
    
    $q = $this->createQuery('a')->select('id')->where('pepper = ?', $pepper);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
    
  }
}
