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
  
  public function executeOfficial(sfWebRequest $request)
  {
    $this->users = Doctrine::getTable('PPE_Edit_Edit')->getEditsByUser(2);
  }
  
  public function executeUnknown(sfWebRequest $request)
  {
    $this->users = Doctrine::getTable('PPE_Edit_Edit')->getEditsByUser(95);
  }
  
  // Download the edit
  public function executeDownload(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    if (!(is_numeric($id)))
    {
      $response = $this->getResponse();
      $response->setStatusCode(409);
      $this->id = $request->getParameter('id');
      return sfView::ERROR;
    }
    $id = sprintf("%06d", $id);
    $name = sprintf("edit_%s.edit", $id);
    $gz = $name . '.gz';
    $path = sprintf("%s/data/user_edits/%s", sfConfig::get('sf_root_dir'), $gz);
    $file = gzopen($path, 'r');
    $data = gzread($file, sfConfig::get('app_max_edit_file_size'));
    gzclose($file);
    
    $response = $this->getResponse();
    $response->clearHttpHeaders();
    $response->setHttpHeader('Content-Disposition', 'attachment; filename='.$name);
    $response->setHttpHeader('Content-Length', strlen($data));
    $response->setHttpHeader('Content-Type', 'application/edit');
    $response->sendHttpHeaders();
    $response->setContent($data);

    return sfView::NONE;
  }
}
