<?php

/**
 * role actions.
 *
 * @package    pumpproedits
 * @subpackage role
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class roleActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_user_roles = Doctrine::getTable('PPE_User_Role')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_user_role = Doctrine::getTable('PPE_User_Role')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_user_role);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_User_RoleForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_User_RoleForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_user_role = Doctrine::getTable('PPE_User_Role')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_role does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_User_RoleForm($ppe_user_role);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_user_role = Doctrine::getTable('PPE_User_Role')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_role does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_User_RoleForm($ppe_user_role);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_user_role = Doctrine::getTable('PPE_User_Role')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_role does not exist (%s).', $request->getParameter('id')));
    $ppe_user_role->delete();

    $this->redirect('role/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_user_role = $form->save();

      $this->redirect('role/edit?id='.$ppe_user_role->getId());
    }
  }
}
