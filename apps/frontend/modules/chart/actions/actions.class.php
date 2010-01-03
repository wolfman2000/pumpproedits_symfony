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
        $notedata = $tmp->get_stats(fopen($path, "r"), 1);
        @unlink($path);
        // The others can be gotten later.
        $p = array('cols' => $notedata['cols']);
        $tmp = new EditCharter($p);
        $xml = $tmp->genChart($notedata);
        
        $response = $this->getResponse();
        $response->clearHttpHeaders();
        $response->setHttpHeader('Content-Type', 'image/svg+xml');
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
  
  public function executeQuick(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $kind = $request->getParameter('kind');
    if (!(is_numeric($id) and ($kind === "classic" or $kind === "rhythm")))
    {
      $response = $this->getResponse();
      $response->setStatusCode(409);
      $this->id = $request->getParameter('id');
      $this->kind = $kind;
      return sfView::ERROR;
    }
    
    $id = sprintf("%06d", $id);
    $root = sfConfig::get('sf_root_dir');
    $name = sprintf("edit_%s.edit", $id);
    $path = sprintf("%s/data/user_edits/%s", $root, $name);
    
    /* File validation takes place here. */
    $tmp = new EditParser();
    try
    {
      $notedata = $tmp->get_stats(fopen($path, "r"), 1);
      $p = array('cols' => $notedata['cols'], 'kind' => $kind);
      $tmp = new EditCharter($p);
      $xml = $tmp->genChart($notedata);
      
      $response = $this->getResponse();
      $response->clearHttpHeaders();
      $response->setHttpHeader('Content-Type', 'image/svg+xml');
      $response->setContent($xml->saveXML());
      return sfView::NONE;
    }
    catch (sfParseException $e)
    {
      $this->data = $e;
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}