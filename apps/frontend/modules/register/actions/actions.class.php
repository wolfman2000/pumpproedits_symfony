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
    if ($this->getUser()->isAuthenticated())
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
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
    $this->form->bind($request->getParameter('validate'));
    if ($this->form->isValid())
    {
      // Check the things the form can't do through the database.
      $table = Doctrine::getTable('PPE_User_User');
      $data = array();

      /* Check if the email is taken. */
      $email = $this->form->getValue('email');
      if ($table->getIDByEmail($email))
      {
        array_push($data, "The requested email address is already taken.");
      }
      $username = $this->form->getValue('username');
      $id = $table->getIDByUser($username);
      if ($id)
      {
        // Find out WHY the username is taken. Start with banning.
        if (Doctrine::getTable('PPE_User_Role')->getIsUserBanned($id))
        {
          $data = array("You are prohibited from joining again.");
          $this->noshow = 1;
        }
        // Not banned: see if the username is just taken.
        elseif ($table->getConfirmedByID($id))
        {
          array_push($data, "The requested username is already taken.");
        }
        // Not confirmed: ask for a new confirmation.
        else
        {
          array_push($data, "You need to confirm your username. See Account Help.");
        }
      }
      
      if (count($data))
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = $data;
        return sfView::ERROR;
      }
      else
      {
        // Test email sending first.
        $salt = $table->addUser($username, $email, $this->form->getValue('password'));
        $this->getMailer()->send(new RegisterConfirmationMessage($email, $username, $salt));
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
