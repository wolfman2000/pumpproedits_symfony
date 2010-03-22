<?php

class PPE_Edit_PlayerTable extends Doctrine_Table
{
  public function updateEdit($id, $player, $row)
  {
    return $this->createQuery('a')->update()
      ->set('diff', $row['diff'])
      ->set('steps', $row['steps'])
      ->set('jumps', $row['jumps'])
      ->set('holds', $row['holds'])
      ->set('mines', $row['mines'])
      ->set('trips', $row['trips'])
      ->set('rolls', $row['rolls'])
      ->set('lifts', $row['lifts'])
      ->set('fakes', $row['fakes'])
      ->where('edit_id = ?', $id)
      ->andWhere('player = ?', $player)->execute();
  }
}
