<?php

class PPE_User_PowerTable extends Doctrine_Table
{
  public function getIsUserBanned($id)
  {
    $query = $this->createQuery('a')->select('ur.role');
    $query = $query->innerJoin('a.PPE_User_Role ur')->whereIn('role', array('banned', 'forbidden'));
    return $query->execute();
  }
}
