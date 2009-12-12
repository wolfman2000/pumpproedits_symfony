<?php

class PPE_Song_SongTable extends Doctrine_Table
{
  public function getBaseEditsExecute()
  {
    return $this->getBaseEdits()->execute();
  }
  public function getBaseEdits()
  {
    return $this->createQuery('a')->select('name, id, abbr');
  }
}
