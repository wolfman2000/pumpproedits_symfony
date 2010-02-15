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
      $condT = Doctrine::getTable('PPE_User_Condiment');
      $roleT = Doctrine::getTable('PPE_User_Role');
      $userT = Doctrine::getTable('PPE_User_User');
      $oreg = $this->form->getValue('confirm');
      $id = $condT->getIDByOregano($oreg);
      $this->data = array();
      
      if (!$id)
      {
        
        array_push($this->data, "Make sure you put in the confirmation code correctly.");
      }
      // I may remove this line later.
      if ($userT->getConfirmedByID($id)) // If confirmed, don't allow reseting.
      {
        array_push($this->data, "You did not request a password reset.");
      }
      if ($roleT->getIsUserBanned($id))
      {
        $this->data = array("You are not allowed to contribute to the website.");
        $this->noshow = 1;
      }
      
      if (count($this->data))
      {
        $this->getResponse()->setStatusCode(409);
        return sfView::ERROR;
      }
      else // We're good!
      {
        $userT->confirmUser($id);
        $pass = $this->form->getValue('password');
        $condT->setPassword($id, $pass);
        $roles = $roleT->getRolesByID($id);
        $this->getUser()->signIn($roles, $id);
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
