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
    if ($this->getUser()->isAuthenticated())
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
    $code = $request->getParameter('code');
    $this->form = new ConfirmForm(array('confirm' => $code));
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new ConfirmForm();
    $this->form->bind($request->getParameter('validate'));
    
    if ($this->form->isValid())
    {
      $condT = Doctrine::getTable('PPE_User_Condiment');
      $roleT = Doctrine::getTable('PPE_User_Role');
      $oreg = $this->form->getValue('confirm');
      $pass = $this->form->getValue('password');
      $id = $condT->confirmUser($oreg, $pass);
      
      if (!$id)
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = array("Make sure you put in the confirmation code and password correctly.");
        return sfView::ERROR;      
      }
      elseif ($roleT->getIsUserBanned($id))
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = array("You are not allowed to contribute to the website.");
        $this->noshow = 1;
        return sfView::ERROR; 
      }
      else // We're good!
      {
        $userT = Doctrine::getTable('PPE_User_User');
        $userT->confirmUser($id);
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
