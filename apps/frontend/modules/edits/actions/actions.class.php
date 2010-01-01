<?php

/**
 * edits actions.
 *
 * @package    pumpproedits
 * @subpackage edits
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class editsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('edits', 'song');
  }
  
  // Viewing songs
  public function executeSong(sfWebRequest $request)
  {
    $this->songs = Doctrine::getTable('PPE_Song_Song')->getSongsWithEdits();
  }
  
  // View edits of the chosen song
  public function executeChosenSong(sfWebRequest $request)
  {
    $sid = $request->getParameter('id');
    $this->song = Doctrine::getTable('PPE_Song_Song')->getSongByID($sid);
    $this->songs = Doctrine::getTable('PPE_Edit_Edit')->getEditsBySong($sid);
  }
  
  // View edits of the chosen user
  public function executeChosenUser(sfWebRequest $request)
  {
    $uid = $request->getParameter('id');
    $this->user = Doctrine::getTable('PPE_User_User')->getUserByID($uid);
    $this->users = Doctrine::getTable('PPE_Edit_Edit')->getEditsByUser($uid);
  }
  
  public function executeUser(sfWebRequest $request)
  {
    $this->users = Doctrine::getTable('PPE_User_User')->getUsersWithEdits();
  }
  
  // Download the edit
  public function executeDownload(sfWebRequest $request)
  {
  
  }
}
