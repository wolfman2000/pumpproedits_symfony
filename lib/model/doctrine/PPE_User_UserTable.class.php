<?php

class PPE_User_UserTable extends Doctrine_Table
{
  public function getIDByEmail($email)
  {
    return $this->createQuery('a')->select('id')->
      where('email = ?', $email)->execute();
  }
}
