<?php

class PPE_Song_StopTable extends Doctrine_Table
{
  public function getStopsBySongID($id)
  {
    return $this->findBySongId($id);
  }
}
