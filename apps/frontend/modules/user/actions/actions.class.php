<?php

/**
 * user actions.
 *
 * @package    pumpproedits
 * @subpackage user
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_user_users = Doctrine::getTable('PPE_User_User')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_user_user = Doctrine::getTable('PPE_User_User')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_user_user);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_User_UserForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_User_UserForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_user_user = Doctrine::getTable('PPE_User_User')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_user does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_User_UserForm($ppe_user_user);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_user_user = Doctrine::getTable('PPE_User_User')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_user does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_User_UserForm($ppe_user_user);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_user_user = Doctrine::getTable('PPE_User_User')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_user does not exist (%s).', $request->getParameter('id')));
    $ppe_user_user->delete();

    $this->redirect('user/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_user_user = $form->save();

      $this->redirect('user/edit?id='.$ppe_user_user->getId());
    }
  }
}
