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
    $player->setSteps($row['steps'][0]);
    $player->setJumps($row['jumps'][0]);
    $player->setHolds($row['holds'][0]);
    $player->setMines($row['mines'][0]);
    $player->setTrips($row['trips'][0]);
    $player->setRolls($row['rolls'][0]);
    $player->setLifts($row['lifts'][0]);
    $player->setFakes($row['fakes'][0]);
    $edit->PPE_Edit_Players[] = $player;

    if ($style === "routine")
    {
      $player = new PPE_Edit_Player();
      $player->setPlayer(2);
      $player->setSteps($row['steps'][1]);
      $player->setJumps($row['jumps'][1]);
      $player->setHolds($row['holds'][1]);
      $player->setMines($row['mines'][1]);
      $player->setTrips($row['trips'][1]);
      $player->setRolls($row['rolls'][1]);
      $player->setLifts($row['lifts'][1]);
      $player->setFakes($row['fakes'][1]);
      $edit->PPE_Edit_Players[] = $player;
    }

    $edit->save();
    return $edit->id;
  }
  
  public function updateEdit($id, $row, $player = 1)
  {
    $this->createQuery('a')->update()
      ->set('diff', $row['diff'])
      ->where('id = ?', $id)->execute();
    
    $pT = Doctrine::getTable('PPE_Edit_Player');
    $pT->updateEdit($id, 1, $row);
    if ($row['style'] === "pump-routine")
    {
      $pT->updateEdit($id, 2, $row);
    }
    return true;
  }
  
  // Get the user's edits...including problem ones.
  public function getSVGEdits($id)
  {
    return $this->createQuery('a')
      ->select('style, title, diff, s.abbr sabbr, s.name sname')
      ->innerJoin('a.PPE_Song_Song s')
      ->where('a.user_id = ?', $id)
      ->orderBy('a.id')
      ->execute();
  }
  
  public function getNonProblemEdits()
  {
    return $this->createQuery('a')
      ->select('id, b.name uname, style, title, diff, c.name sname')
      ->innerJoin('a.PPE_User_User b')
      ->innerJoin('a.PPE_Song_Song c')
      ->where('a.is_problem = ?', false)
      ->orderBy('b.lc_name, c.lc_name, title, style')
      ->execute();
  }

  public function getIDByUpload($row)
  {
    return $this->createQuery('a')->select('id')
      ->where('title = ?', $row['title'])
      ->andWhere('style = ?', substr($row['style'], 5))
      ->andWhere('user_id = ?', $row['uid'])->fetchOne()->id;
  }

  public function getEditsBySong($songid)
  {
    $cols = 'diff, y.steps ysteps, y.jumps yjumps, y.holds yholds, y.mines ymines';
    $cols .= ', y.trips ytrips, y.rolls yrolls, y.fakes yfakes, y.lifts ylifts';
    $cols .= ', m.steps msteps, m.jumps mjumps, m.holds mholds, m.mines mmines';
    $cols .= ', m.trips mtrips, m.rolls mrolls, m.fakes mfakes, m.lifts mlifts';
    $cols .= ', user_id, b.name uname, title, style, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_User_User b')
      ->innerJoin('a.PPE_Edit_Players y WITH y.player = 1')
      ->leftJoin('a.PPE_Edit_Players m WITH m.player = 2') // routine check.
      ->where('song_id = ?', $songid)
      ->andWhere('a.is_problem = ?', 0)
      ->orderBy('b.lc_name, a.title')
      ->execute();
      
  }
  
  public function getEditsByUser($userid)
  {
    $cols = 'diff, y.steps ysteps, y.jumps yjumps, y.holds yholds, y.mines ymines';
    $cols .= ', y.trips ytrips, y.rolls yrolls, y.fakes yfakes, y.lifts ylifts';
    $cols .= ', m.steps msteps, m.jumps mjumps, m.holds mholds, m.mines mmines';
    $cols .= ', m.trips mtrips, m.rolls mrolls, m.fakes mfakes, m.lifts mlifts';
    $cols .= ', song_id, b.name sname, title, style, num_votes, tot_votes';
    return $this->createQuery('a')
      ->select($cols)
      ->innerJoin('a.PPE_Song_Song b')
      ->innerJoin('a.PPE_Edit_Players y WITH y.player = 1')
      ->leftJoin('a.PPE_Edit_Players m WITH m.player = 2') // routine check.
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
