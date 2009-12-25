<?php

/**
 * confirm actions.
 *
 * @package    pumpproedits
 * @subpackage confirm
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class confirmActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new ConfirmForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new ConfirmForm();
    $this->form->bind($request->getParameter('validate'));
    
    if ($this->form->isValid())
    {
    
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
