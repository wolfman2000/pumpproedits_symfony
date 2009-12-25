<?php

/**
 * contact actions.
 *
 * @package    pumpproedits
 * @subpackage contact
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contactActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new ContactForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new ContactForm();
    $this->form->bind($request->getParameter('validate'));
    if ($this->form->isValid())
    {
      // We can immediately send the email.
      $body = $this->form->getValue('content');
      $subject = $this->form->getValue('subject');
      $name = $this->form->getValue('name');
      $email = $this->form->getValue('email');
      
      try
      {
        $cm = new ContactMessage($email, $name, $subject, $body);
        $this->getMailer()->send($cm);
      }
      catch (Swift_RfcComplianceException $e)
      {
        $this->getResponse()->setStatusCode(409);
        $this->data = array('The mailer is down: please email directly!');
        $this->noshow = 1;
        $this->subj = $subject;
        $this->body = $body;
        return sfView::ERROR;
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
