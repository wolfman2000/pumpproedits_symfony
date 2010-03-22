<?php

class PPE_Edit_EditTable extends Doctrine_Table
{
  // Insert data based on EditParser::get_stats
  public function addEdit($row)
  {
    $style = substr($row['style'], 5);
    $edit = new PPE_Edit_Edit();
    $edit->setSongID($row['id']);
    $edit->setUserID($row['uid']);
    $edit->setTitle($row['title']);
    $edit->setStyle($style);
    $edit->setDiff($row['diff']);

    $player = new PPE_Edit_Player();
    $player->setPlayer(1);
    $player->setSteps($row[0]['steps']);
    $player->setJumps($row[0]['jumps']);
    $player->setHolds($row[0]['holds']);
    $player->setMines($row[0]['mines']);
    $player->setTrips($row[0]['trips']);
    $player->setRolls($row[0]['rolls']);
    $player->setLifts($row[0]['lifts']);
    $player->setFakes($row[0]['fakes']);
    $edit->PPE_Edit_Players[] = $player;

    if ($style === "routine")
    {
      $player = new PPE_Edit_Player();
      $player->setPlayer(2);
      $player->setSteps($row[1]['steps']);
      $player->setJumps($row[1]['jumps']);
      $player->setHolds($row[1]['holds']);
      $player->setMines($row[1]['mines']);
      $player->setTrips($row[1]['trips']);
      $player->setRolls($row[1]['rolls']);
      $player->setLifts($row[1]['lifts']);
      $player->setFakes($row[1]['fakes']);
      $edit->PPE_Edit_Players[] = $player;
    }

    $edit->save();
    return $edit->id;
  }
  
  public function getNonProblemEdits()
  {
    return $this->createQuery('a')
      ->select('id, b.name uname, is_single, title, diff, c.name sname')
      ->innerJoin('a.PPE_User_User b')
      ->innerJoin('a.PPE_Song_Song c')
      ->where('a.is_problem = ?', false)
      ->orderBy('b.lc_name, c.lc_name, title, is_single')
      ->execute();
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
    $cols = 'diff, y.steps ysteps, y.jumps yjumps, y.holds yholds, y.mines ymines';
    $cols .= ', y.trips ytrips, y.rolls yrolls, y.fakes yfakes, y.lifts ylifts';
    $cols .= ', user_id, b.name uname, title, style, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_User_User b')
      ->innerJoin('a.PPE_Edit_Players y')
      ->where('song_id = ?', $songid)
      ->andWhere('a.is_problem = ?', 0)
      ->andWhere('y.player = ?', 1)
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
