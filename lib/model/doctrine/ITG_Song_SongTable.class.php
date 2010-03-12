<?php

class ITG_Song_SongTable extends Doctrine_Table
{
  public function getIDBySong($song)
  {
    return $this->createQuery('a')->select('id')->where('name = ?', $song)
      ->fetchOne()->id;
  }
  public function getSongRow($songid)
  {
    return $this->createQuery('a')->find($songid)->execute();
  }
  public function getSongs()
  {
    return $this->createQuery('a')->select('id, name')
      ->where('is_problem = ?', false)
      ->orderBy('lc_name')->execute();
  }
}