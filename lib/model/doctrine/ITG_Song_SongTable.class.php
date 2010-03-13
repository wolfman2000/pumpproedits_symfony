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
  
  public function getBaseEdits()
  {
    return $this->createQuery('a')->select('name, id, abbr')->orderBy('lc_name');
  }
  
  public function getSongsWithEdits()
  {
    return $this->createQuery('a')->select('a.name core, a.id, COUNT(b.id) AS num_edits')
      ->innerJoin('a.ITG_Edit_Edits b')
      ->orderBy('a.lc_name')
      ->groupBy('a.name, b.song_id')
      ->execute();
  }
}
