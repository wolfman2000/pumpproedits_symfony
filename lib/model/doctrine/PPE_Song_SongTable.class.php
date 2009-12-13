<?php

class PPE_Song_SongTable extends Doctrine_Table
{
  public function getBaseEditsExecute()
  {
    return $this->getBaseEdits()->execute();
  }
  public function getBaseEdits()
  {
    return $this->createQuery('a')->select('name, id, abbr')->orderBy('name');
  }
  public function getSongRow($songid)
  {
    return $this->createQuery('a')->find($songid)->execute();
  }
  public function getSongId($song)
  {
    return $this->createQuery('a')->select('id')->findOneByName($song)->execute();
  }
}
