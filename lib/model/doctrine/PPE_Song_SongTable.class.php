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
  public function getIDBySong($song)
  {
    $q = $this->createQuery('a')->select('id')->where('name = ?', $song);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
  public function getSongByID($id)
  {
    return $this->createQuery('a')->select('name')->where('id = ?', $id)
      ->fetchOne()->name;
  }
}
