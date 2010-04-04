<?php

class PPE_Song_SongTable extends Doctrine_Table
{
  public function getIDBySong($song)
  {
    return $this->createQuery('a')->select('id')
      ->where('lc_name = ?', strtolower($song))
      ->fetchOne()->id;
  }
  public function getSongByID($id)
  {
    return $this->createQuery('a')->select('name')->where('id = ?', $id)
      ->fetchOne()->name;
  }
  public function getSongRow($songid)
  {
    return $this->createQuery('a')
      ->where('id = ?', $songid)->fetchOne();
  }
  
  public function getSongByEditID($eid)
  {
    return $this->createQuery('a')->select('name')
      ->innerJoin('a.PPE_Edit_Edits e')
      ->where('e.id = ?', $eid)->fetchOne();
  }
  
  public function getSongs()
  {
    return $this->createQuery('a')->select('id, name')
      ->where('is_problem = ?', false)
      ->orderBy('lc_name')->execute();
  }
  
  public function getSongsWithGame()
  {
    return $this->createQuery('a')
      ->select('name, g.song_id sid, MIN(g.game_id) gid')
      ->innerJoin('a.PPE_Song_Games g')
      ->where('is_problem = ?', false)
      ->groupBy('name, sid')
      ->orderBy('gid, name')
      ->execute();
  }
  
  public function getBaseEditsExecute()
  {
    return $this->getBaseEdits()->execute();
  }
  public function getBaseEdits()
  {
    return $this->createQuery('a')->select('name, id, abbr')->orderBy('lc_name');
  }
  
  public function getSongsWithEdits()
  {
    return $this->createQuery('a')->select('name core, num_edits')
      ->where('is_problem = ?', false)
      ->andWhere('num_edits > ?', 0)
      ->orderBy('lc_name')
      ->execute();
  }
}
