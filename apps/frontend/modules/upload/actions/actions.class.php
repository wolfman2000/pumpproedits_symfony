<?php

/**
 * upload actions.
 *
 * @package    pumpproedits
 * @subpackage upload
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class uploadActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if (!$this->getUser()->isAuthenticated())
    {
      $this->forward('login', 'index');
      return;
    }
    $this->form = new UploadEditForm();
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $this->form = new UploadEditForm();
    $this->form->bind($request->getParameter('validate'), $request->getFiles('validate'));
    if ($this->form->isValid())
    {
      $file = $this->form->getValue('file');
      $filename = 'uploaded'.sha1($file->getOriginalName());
      $extension = $file->getExtension($file->getOriginalExtension());
      $path = sfConfig::get('sf_upload_dir').'/'.$filename.$extension;
      $file->save($path);
      
      $owner = $this->form->getValue('owner');
      if ($owner == "me")
      {
        $uid = $this->getUser()->getAttribute('id');
      }
      elseif ($owner == "piu")
      {
        $uid = 2;
      }
      elseif ($owner == "other")
      {
        $uid = 95;
      }
      
      /* File validation takes place here. */
      $tmp = new EditParser();
      try
      {
        $this->page = "parseyay";
        $row = $tmp->get_stats(fopen($path, "r"));
        @unlink($path);
      }
      catch (sfParseException $e)
      {
        $this->page = "parseboo";
        $this->data = array($e);
        $this->getResponse()->setStatusCode(409);
        @unlink($path);
        return sfView::ERROR;
      }
      
      $editT = Doctrine::getTable('PPE_Edit_Edit');
      $row['uid'] = $uid;
      $eid = $editT->getIDByUpload($row);
      
      if (isset($eid) and $owner != "me")
      {
        $this->data = array("You are not allowed to override edits you do not own.");
        $this->getResponse()->setStatusCode(409);
        return sfView::ERROR;
      }
      elseif (isset($eid))
      {
        $editT->updateEdit($eid, $row);
      }
      else
      {
        $eid = $editT->addEdit($row);
      }
      
      $path = sfConfig::get('sf_data_dir').sprintf('/user_edits/edit_%06d.edit', $eid);
      $file->save($path);
      
    }
    else
    {
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
