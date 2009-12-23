<?php

class PPE_User_UserTable extends Doctrine_Table
{
  public function getIDByEmail($email)
  {
    return $this->createQuery('a')->select('id')->
      where('email = ?', $email)->execute();
  }
  
  public function getIDByUser($user)
  {
    return $this->createQuery('a')->select('id')->
      where('name = ?', $user)->execute();
  }
}
