<?php

/**
 * upload actions.
 *
 * @package    pumpproedits
 * @subpackage upload
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class uploadActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if (!$this->getUser()->isAuthenticated())
    {
      $this->forward('login', 'index');
      return;
    }
    $this->form = new UploadEditForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new UploadEditForm();
    $this->form->bind($request->getParameter('validate'), $request->getFiles('validate'));
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
