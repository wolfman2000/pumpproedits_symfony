<?php

class PPE_User_RoleTable extends Doctrine_Table
{
  public function getIsUserBanned($id)
  {
    $query = $this->createQuery('a')->select('role');
    $query = $query->innerJoin('a.PPE_User_Powers')->whereIn('role', array('banned', 'forbidden'));
    return $query->execute();
  }
}
