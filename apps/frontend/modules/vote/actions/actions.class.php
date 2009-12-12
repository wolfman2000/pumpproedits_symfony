<?php

/**
 * vote actions.
 *
 * @package    pumpproedits
 * @subpackage vote
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voteActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ppe_vote_votes = Doctrine::getTable('PPE_Vote_Vote')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->ppe_vote_vote = Doctrine::getTable('PPE_Vote_Vote')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->ppe_vote_vote);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PPE_Vote_VoteForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PPE_Vote_VoteForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ppe_vote_vote = Doctrine::getTable('PPE_Vote_Vote')->find(array($request->getParameter('id'))), sprintf('Object ppe_vote_vote does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Vote_VoteForm($ppe_vote_vote);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($ppe_vote_vote = Doctrine::getTable('PPE_Vote_Vote')->find(array($request->getParameter('id'))), sprintf('Object ppe_vote_vote does not exist (%s).', $request->getParameter('id')));
    $this->form = new PPE_Vote_VoteForm($ppe_vote_vote);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($ppe_vote_vote = Doctrine::getTable('PPE_Vote_Vote')->find(array($request->getParameter('id'))), sprintf('Object ppe_vote_vote does not exist (%s).', $request->getParameter('id')));
    $ppe_vote_vote->delete();

    $this->redirect('vote/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $ppe_vote_vote = $form->save();

      $this->redirect('vote/edit?id='.$ppe_vote_vote->getId());
    }
  }
}
