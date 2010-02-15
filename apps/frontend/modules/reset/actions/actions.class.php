<?php

/**
 * reset actions.
 *
 * @package    pumpproedits
 * @subpackage reset
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class resetActions extends sfActions
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
    $code = $request->getParameter('code');
    $this->form = new ResetPasswordForm(array('confirm' => $code));
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new ResetPasswordForm();
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
