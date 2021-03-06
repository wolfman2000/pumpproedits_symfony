<?php

/**
 * votes actions.
 *
 * @package    pumpproedits
 * @subpackage votes
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class votesActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $id = $request->getParameter('eid');
    $editT = Doctrine::getTable('PPE_Edit_Edit');
    if (!$editT->confirmExistence($id))
    {
      return $this->forward('votes', 'none');
    }
    
    // Deal with the cache.
    $votes = Doctrine::getTable('PPE_Vote_Vote')->getVotesByID($id);
    
    if (!$votes->count()) # Must have votes here.
    {
      return $this->forward('votes', 'none');
    }
    
    $this->votes = $votes->execute();
    $this->song = Doctrine::getTable('PPE_Song_Song')->getSongByEditID($id);
    
    
  }
  
  # Add a vote here.
  public function executeAdd(sfWebRequest $request)
  {
    $this->form = new VotingForm();
  }
  
  # The vote has been added.
  public function executeAdded(sfWebRequest $request)
  {
  
  }
  
  # You can't contribute a vote to your own edit.
  public function executeSubtract(sfWebRequest $request)
  {
  
  }
  
  public function executeNone(sfWebRequest $request)
  {
    $response = $this->getResponse();
    $response->setStatusCode(404);
    return sfView::ERROR;
  }
}
