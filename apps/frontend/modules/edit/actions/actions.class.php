<?php

/**
 * edit actions.
 *
 * @package    pumpproedits
 * @subpackage edit
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class editActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_edit_edits = Doctrine::getTable('PPE_Edit_Edit')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_edit_edit = Doctrine::getTable('PPE_Edit_Edit')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_edit_edit);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_Edit_EditForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_Edit_EditForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_edit_edit = Doctrine::getTable('PPE_Edit_Edit')->find(array($request->getParameter('id'))), sprintf('Object ppe_edit_edit does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Edit_EditForm($ppe_edit_edit);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_edit_edit = Doctrine::getTable('PPE_Edit_Edit')->find(array($request->getParameter('id'))), sprintf('Object ppe_edit_edit does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Edit_EditForm($ppe_edit_edit);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_edit_edit = Doctrine::getTable('PPE_Edit_Edit')->find(array($request->getParameter('id'))), sprintf('Object ppe_edit_edit does not exist (%s).', $request->getParameter('id')));
    $ppe_edit_edit->delete();

    $this->redirect('edit/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_edit_edit = $form->save();

      $this->redirect('edit/edit?id='.$ppe_edit_edit->getId());
    }
  }
}
