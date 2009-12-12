<?php

/**
 * bpm actions.
 *
 * @package    pumpproedits
 * @subpackage bpm
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bpmActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_song_bp_ms = Doctrine::getTable('PPE_Song_BPM')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_song_bpm = Doctrine::getTable('PPE_Song_BPM')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_song_bpm);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_Song_BPMForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_Song_BPMForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_song_bpm = Doctrine::getTable('PPE_Song_BPM')->find(array($request->getParameter('id'))), sprintf('Object ppe_song_bpm does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Song_BPMForm($ppe_song_bpm);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_song_bpm = Doctrine::getTable('PPE_Song_BPM')->find(array($request->getParameter('id'))), sprintf('Object ppe_song_bpm does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Song_BPMForm($ppe_song_bpm);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_song_bpm = Doctrine::getTable('PPE_Song_BPM')->find(array($request->getParameter('id'))), sprintf('Object ppe_song_bpm does not exist (%s).', $request->getParameter('id')));
    $ppe_song_bpm->delete();

    $this->redirect('bpm/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_song_bpm = $form->save();

      $this->redirect('bpm/edit?id='.$ppe_song_bpm->getId());
    }
  }
}
