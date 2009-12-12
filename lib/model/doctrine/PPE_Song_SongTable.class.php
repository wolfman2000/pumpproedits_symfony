<?php

class PPE_Song_SongTable extends Doctrine_Table
{
  public function getBaseEdits()
  {
    $a = $this->createQuery('a')->select('name, id, abbr')->orderBy('name');
    return $a->execute();
  }
}
