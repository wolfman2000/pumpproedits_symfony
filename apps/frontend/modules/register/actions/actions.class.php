<?php

/**
 * register actions.
 *
 * @package    pumpproedits
 * @subpackage register
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class registerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new RegisterForm();
  }

  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new RegisterForm();
    $this->form->bind($request->getParameter($this->form->getName()));
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
