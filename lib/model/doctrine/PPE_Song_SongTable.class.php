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
  
  public function getSongsWithGameAndDiff()
  {
    return $this->createQuery('a')
      ->select('name, g.song_id sid, MIN(g.game_id) gid, COUNT(d.diff_id) did')
      ->leftJoin('a.PPE_Song_Games g')
      ->leftJoin('a.PPE_Song_Difficulties d')
      ->where('is_problem = ?', false)
      ->groupBy('name, sid')
      ->having("did > 0")
      ->orderBy('gid, name')
      ->execute();
  }
  
  public function getBaseEditsExecute()
  {
    return $this->getBaseEdits()->execute();
  }
  public function getBaseEdits()
  {
    return $this->createQuery('a')
      ->select('name, id, abbr, g.game_id tmp')
      ->leftJoin('a.PPE_Song_Games g WITH g.game_id > 1')
      ->orderBy('lc_name');
  }
  
  public function getSongsWithEdits()
  {
    return $this->createQuery('a')->select('name core, num_edits')
      ->where('is_problem = ?', false)
      ->andWhere('num_edits > ?', 0)
      ->orderBy('lc_name')
      ->execute();
  }
  
  public function getDifficulties($songid)
  {
    return $this->createQuery('z')
      ->select('z.id, a.diff_id ez, b.diff_id nr, c.diff_id hr, d.diff_id cz, '
        . 'e.diff_id hd, f.diff_id fs, g.diff_id nm, h.diff_id rt')
      ->leftJoin('z.PPE_Song_Difficulties a WITH a.diff_id = 1')
      ->leftJoin('z.PPE_Song_Difficulties b WITH b.diff_id = 2')
      ->leftJoin('z.PPE_Song_Difficulties c WITH c.diff_id = 3')
      ->leftJoin('z.PPE_Song_Difficulties d WITH d.diff_id = 4')
      ->leftJoin('z.PPE_Song_Difficulties e WITH e.diff_id = 5')
      ->leftJoin('z.PPE_Song_Difficulties f WITH f.diff_id = 6')
      ->leftJoin('z.PPE_Song_Difficulties g WITH g.diff_id = 7')
      ->leftJoin('z.PPE_Song_Difficulties h WITH h.diff_id = 8')
      ->where('z.id = ?', $songid)->fetchArray();
  }
}
