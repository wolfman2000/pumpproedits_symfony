<?php

class PPEUser extends sfBasicSecurityUser
{
  public function signIn($credentials)
  {
    $this->setAuthenticated(true);
    $this->addCredentials($credentials);
  }

  public function signOut()
  {
    $this->setAuthenticated(false);
    $this->clearCredentials();
  }
}
