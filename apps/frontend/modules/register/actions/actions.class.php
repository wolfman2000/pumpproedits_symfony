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
      $email = $request->getParameter('email');
      if ($table->getIDByEmail($email))
      {
        array_push($data, "The requested email address is already taken.");
      }
      $username = $request->getParameter('username');
      $id = $table->getIDByUser($username);
      // Check if the username is taken.
      if ($id)
      {
        // Find out WHY the username is taken. Start with banning.
        $power = Doctrine::getTable('PPE_User_Power');
        if ($power->getIsUserBanned($id))
        {
          $data = array("You are prohibited from joining again.");
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
      else
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = $data;
        return sfView::ERROR;
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
        $this->getMailer()->send(new RegisterConfirmationMessage($email, $username, '3838'));
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
