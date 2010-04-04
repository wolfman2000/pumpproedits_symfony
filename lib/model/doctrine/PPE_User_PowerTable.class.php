<?php

class PPE_User_PowerTable extends Doctrine_Table
{
  // Only allow moderators or higher to edit/create Andamiro charts.
  public function canEditAndamiro($userid)
  {
    return $this->createQuery('a')->select('COUNT(id) AS num')
      ->where('role_id >= ?', 4)
      ->andWhere('user_id = ?', $userid)->fetchOne()->num;
  }
}
