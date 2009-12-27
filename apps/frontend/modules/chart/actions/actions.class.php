<?php

/**
 * chart actions.
 *
 * @package    pumpproedits
 * @subpackage chart
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class chartActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new ValidateEditForm();
  }
 /**
  * Executes validate action (form required)
  *
  * @param sfRequest $request A request object
  */
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new ValidateEditForm();
    $this->form->bind($request->getParameter('validate'), $request->getFiles('validate'));
    $errors = array();
    if ($this->form->isValid())
    {
      $file = $this->form->getValue('file');
      $filename = 'uploaded'.sha1($file->getOriginalName());
      $extension = $file->getExtension($file->getOriginalExtension());
      $path = sfConfig::get('sf_upload_dir').'/'.$filename.$extension;
      $file->save($path);

      /* File validation takes place here. */
      $tmp = new EditParser();
      try
      {
        $notedata = $tmp->get_stats(fopen($path, "r"));
        @unlink($path);
        $tmp = new EditCharter(array('cols' => $notedata['cols']));
        $xml = $tmp->genChart($notedata);
        
        $response = $this->getResponse();
        $response->clearHttpHeaders();
        $response->setHttpHeader('Content-Type', 'application/xml');
        $response->setContent($xml->saveXML());
        return sfView::NONE;
      }
      catch (sfParseException $e)
      {
        $this->data = $e;
        @unlink($path);
        $this->getResponse()->setStatusCode(409);
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