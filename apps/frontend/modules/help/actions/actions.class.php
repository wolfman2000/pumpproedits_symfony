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
    $this->form = new HelpForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new HelpForm();
    $this->form->bind($request->getParameter('validate'));
    if ($this->form->isValid())
    {
      // Check the things the form can't do through the database.
      $table = Doctrine::getTable('PPE_User_User');
      $data = array();

      /* Check if the email is taken. */
      $email = $this->form->getValue('email');
      $id = $table->getIDByEmail($email);
      if (!$id)
      {
        array_push($data, "There is no one with this email account.");
      }
      // Make sure the user isn't banned.
      elseif (Doctrine::getTable('PPE_User_Role')->getIsUserBanned($id))
      {
        $data = array("You are prohibited from joining again.");
        $this->noshow = 1;
      }
      
      if (count($data))
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = $data;
        return sfView::ERROR;
      }
      $username = $table->getNameByID($id);
      
      if ($this->form->getValue('choice') === "resend")
      {
        $table->confirmUser($id, 0);
        $table = Doctrine::getTable('PPE_User_Condiment');
        $table->updateOregano($id);
        $mailer = new ResendConfirmationMessage($email, $username, $table->getOregano($id));
      }
      else
      {
        $mailer = null;
      }
      
      $this->getMailer()->send($mailer);
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
