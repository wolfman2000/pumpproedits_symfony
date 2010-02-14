<?php

/**
 * help actions.
 *
 * @package    pumpproedits
 * @subpackage help
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class helpActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
    #$this->form = new HelpForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    #$this->form = new HelpForm();
  }
}
