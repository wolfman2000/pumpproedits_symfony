<?php

class PPEUser extends sfBasicSecurityUser
{
  public function signIn($credentials, $id)
  {
    $this->setAuthenticated(true);
    $this->addCredentials($credentials);
    $this->setAttribute('id', $id);
    $userT = Doctrine::getTable('PPE_User_User');
    $row = $userT->getAuthByID($id);
    $this->setAttribute('name', $row['name']);
    $this->setAttribute('email', $row['email']);
  }

  public function signOut()
  {
    $this->setAuthenticated(false);
    $this->clearCredentials();
    $this->getAttributeHolder()->remove('id');
    $this->getAttributeHolder()->remove('user');
    $this->getAttributeHolder()->remove('email');
  }
}
