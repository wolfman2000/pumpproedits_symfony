<?php

/**
 * condiment actions.
 *
 * @package    pumpproedits
 * @subpackage condiment
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class condimentActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_user_condiments = Doctrine::getTable('PPE_User_Condiment')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_user_condiment = Doctrine::getTable('PPE_User_Condiment')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_user_condiment);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_User_CondimentForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_User_CondimentForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_user_condiment = Doctrine::getTable('PPE_User_Condiment')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_condiment does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_User_CondimentForm($ppe_user_condiment);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_user_condiment = Doctrine::getTable('PPE_User_Condiment')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_condiment does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_User_CondimentForm($ppe_user_condiment);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_user_condiment = Doctrine::getTable('PPE_User_Condiment')->find(array($request->getParameter('id'))), sprintf('Object ppe_user_condiment does not exist (%s).', $request->getParameter('id')));
    $ppe_user_condiment->delete();

    $this->redirect('condiment/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_user_condiment = $form->save();

      $this->redirect('condiment/edit?id='.$ppe_user_condiment->getId());
    }
  }
}
