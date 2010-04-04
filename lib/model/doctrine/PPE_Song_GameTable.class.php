<?php

class PPE_Song_GameTable extends Doctrine_Table
{
  // Any game after Pro 1 will have routine mode.
  public function getRoutineCompatible($songid)
  {
    return $this->createQuery('a')->select('id')
      ->where('game_id >= ?', 2)
      ->andWhere('song_id = ?', $songid)->count();
  }
}
