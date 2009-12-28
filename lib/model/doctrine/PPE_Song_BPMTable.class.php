<?php

class PPE_Song_BPMTable extends Doctrine_Table
{
  public function getBPMsBySongID($id)
  {
    return $this->findBySongId($id);
  }
}
