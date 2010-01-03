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
    if (!Doctrine::getTable('PPE_Edit_Edit')->confirmExistence($id))
    {
      return $this->forward('votes', 'none');
    }
  }
  
  public function executeNone(sfWebRequest $request)
  {
    $response = $this->getResponse();
    $response->setStatusCode(404);
    return sfView::ERROR;
  }
}