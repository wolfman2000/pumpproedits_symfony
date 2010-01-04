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
    return $this->createQuery('a')->select('id')->where('name = ?', $song)
      ->fetchOne()->id;
  }
  public function getSongByID($id)
  {
    return $this->createQuery('a')->select('name')->where('id = ?', $id)
      ->fetchOne()->name;
  }
  
  public function getSongsWithEdits()
  {
    return $this->createQuery('a')->select('name core, num_edits')
      ->where('is_problem = ?', false)
      ->andWhere('num_edits > ?', 0)
      ->orderBy('lc_name')->execute();
  }
  
  public function getSongByEditID($eid)
  {
    return $this->createQuery('a')->select('name')
      ->innerJoin('a.PPE_Edit_Edits e')
      ->where('e.song_id = ?', $eid)->fetchOne()->name;
  }
}
