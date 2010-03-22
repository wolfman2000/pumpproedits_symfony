<?php

class PPE_Edit_PlayerTable extends Doctrine_Table
{
  public function updateEdit($id, $player, $row)
  {
    return $this->createQuery('a')->update()
      ->set('steps', $row['steps'][$player - 1])
      ->set('jumps', $row['jumps'][$player - 1])
      ->set('holds', $row['holds'][$player - 1])
      ->set('mines', $row['mines'][$player - 1])
      ->set('trips', $row['trips'][$player - 1])
      ->set('rolls', $row['rolls'][$player - 1])
      ->set('lifts', $row['lifts'][$player - 1])
      ->set('fakes', $row['fakes'][$player - 1])
      ->where('edit_id = ?', $id)
      ->andWhere('player = ?', $player)->execute();
  }
}
