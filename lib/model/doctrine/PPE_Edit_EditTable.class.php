<?php

class PPE_Edit_EditTable extends Doctrine_Table
{
  // Insert data based on EditParser::get_stats
  public function addEdit($row)
  {
    $edit = new PPE_Edit_Edit();
    $edit->setSongID($row['id']);
    $edit->setUserID($row['uid']);
    $edit->setTitle($row['title']);
    $edit->setIsSingle($row['style'] == "pump-single" ? 1 : 0);
    $edit->setDiff($row['diff']);
    $edit->setSteps($row['steps']);
    $edit->setJumps($row['jumps']);
    $edit->setHolds($row['holds']);
    $edit->setMines($row['mines']);
    $edit->setTrips($row['trips']);
    $edit->setRolls($row['rolls']);
    $edit->setLifts($row['lifts']);
    $edit->setFakes($row['fakes']);
    
    $edit->save();
    return $edit->id;
  }
  
  // Update data based on EditParser::get_stats
  public function updateEdit($id, $row)
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
      ->where('id = ?', $id)->execute();
  }

  public function getIDByUpload($row)
  {
    return $this->createQuery('a')->select('id')
      ->where('title = ?', $row['title'])
      ->andWhere('is_single = ?', $row['style'] == "pump-single" ? true : false)
      ->andWhere('user_id = ?', $row['uid'])->fetchOne();
  }

  public function getEditsBySong($songid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts';
    $cols .= ', user_id, b.name uname, title, is_single, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_User_User b')
      ->where('song_id = ?', $songid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
      
  }
  
  public function getEditsByUser($userid)
  {
    $cols = 'diff, steps, jumps, holds, mines, trips, rolls, fakes, lifts';
    $cols .= ', song_id, b.name sname, title, is_single, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_Song_Song b')
      ->where('user_id = ?', $userid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
      
  }
  
  public function getProblemByID($id)
  {
    return $this->createQuery('a')->select('is_problem')
      ->where('id = ?', $id)->fetchOne()->is_problem;
  }
  
  public function confirmExistence($id)
  {
    return $this->createQuery('a')->select('title')
      ->where('id = ?', $id)->count();
  }
}
