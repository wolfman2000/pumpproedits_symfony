<?php

/**
 * stop actions.
 *
 * @package    pumpproedits
 * @subpackage stop
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class stopActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_song_stops = Doctrine::getTable('PPE_Song_Stop')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_song_stop = Doctrine::getTable('PPE_Song_Stop')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_song_stop);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_Song_StopForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_Song_StopForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_song_stop = Doctrine::getTable('PPE_Song_Stop')->find(array($request->getParameter('id'))), sprintf('Object ppe_song_stop does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Song_StopForm($ppe_song_stop);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_song_stop = Doctrine::getTable('PPE_Song_Stop')->find(array($request->getParameter('id'))), sprintf('Object ppe_song_stop does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Song_StopForm($ppe_song_stop);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_song_stop = Doctrine::getTable('PPE_Song_Stop')->find(array($request->getParameter('id'))), sprintf('Object ppe_song_stop does not exist (%s).', $request->getParameter('id')));
    $ppe_song_stop->delete();

    $this->redirect('stop/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_song_stop = $form->save();

      $this->redirect('stop/edit?id='.$ppe_song_stop->getId());
    }
  }
}
