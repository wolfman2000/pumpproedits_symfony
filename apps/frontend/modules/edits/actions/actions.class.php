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
  
  public function executeUser(sfWebRequest $request)
  {
    
  }
  
  // Download the edit
  public function executeDownload(sfWebRequest $request)
  {
  
  }
}
