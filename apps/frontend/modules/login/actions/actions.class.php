<?php

/**
 * login actions.
 *
 * @package    pumpproedits
 * @subpackage login
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class loginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new LoginForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new LoginForm();
    $this->form->bind($request->getParameter('validate'));
    if ($this->form->isValid())
    {
      $condT = Doctrine::getTable('PPE_User_Condiment');
      $roleT = Doctrine::getTable('PPE_User_Role');
      $user = $this->form->getValue('username');
      $pass = $this->form->getValue('password');
      $id = $condT->checkUser($user, $pass);
      
      if (!$id)
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = array("Make sure you put in the username and password correctly.");
        return sfView::ERROR;      
      }
      elseif ($roleT->getIsUserBanned($id))
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = array("You are not allowed to contribute to the website.");
        $this->noshow = 1;
        return sfView::ERROR; 
      }
      else
      {
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
