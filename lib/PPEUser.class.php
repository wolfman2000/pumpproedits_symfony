<?php

class PPEUser extends sfBasicSecurityUser
{
  public function signIn($credentials, $id)
  {
    $this->setAuthenticated(true);
    $this->addCredentials($credentials);
    $this->setAttribute('id', $id);
    $this->setAttribute('name', Doctrine::getTable('PPE_User_User')
      ->getNameByID($id));
  }

  public function signOut()
  {
    $this->setAuthenticated(false);
    $this->clearCredentials();
    $this->getAttributeHolder()->remove('id');
    $this->getAttributeHolder()->remove('user');
  }
}
