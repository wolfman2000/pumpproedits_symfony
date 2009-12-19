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

  private function validateFailure(sfWebRequest $request, $data = null)
  {
    $this->getResponse()->setStatusCode(409);
    $this->data = $data;
    return sfView::ERROR;
  }

  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new RegisterForm();
    $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
    {
      // Check the things the form can't do through the database.
      $table = Doctrine::getTable('PPE_User_User');
      $data = array();

      /* Check if the email is taken. */
      if ($table->getIDByEmail())
      {
        array_push($data, "The requested email address is already taken.");
      }
      // Check if the username is taken.
      if ($table->getIDByUser())
      {
        // Find out WHY the username is taken.
        array_push($data, "The requested username is already taken.");
      }

      if (count($data))
      {
        $this->validateFailure($this, $data);
      }
    }
    else
    {
      $this->validateFailure($this);
    }
  }
}
