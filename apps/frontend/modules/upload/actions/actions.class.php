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
    $id = $this->getUser()->getAttribute('id');
    $this->form = new UploadEditForm(array(), array('user' => $id));
  }
  
  public function executeValidate(sfWebRequest $request)
  {
    $submitterID = $this->getUser()->getAttribute('id');
    $this->form = new UploadEditForm(array(), array('user' => $submitterID));
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
        $uid = $submitterID;
      }
      elseif ($owner == "piu")
      {
        $uid = 2;
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
      $canAM = Doctrine::getTable('PPE_User_Power')->canEditAndamiro($submitterID);
      
      // the edit exists in the system, AND it's an andamiro edit.
      // Send them to the Edit Creator.
      if ($eid and $owner != "me")
      {
        $this->data = array("Please use the Edit Creator to make changes to this edit.");
        $this->getResponse()->setStatusCode(409);
        return sfView::ERROR;
      }
      // the edit exists in the system, and it's the user's edit.
      // let them edit it in place.
      elseif ($eid)
      {
        $eid = $eid->id;
        $editT->updateEdit($eid, $row);
        $status = "Updated";
      }
      // the edit does NOT exist in the system. Add it.
      else
      {
        $eid = $editT->addEdit($row);
        $status = "New";
        $this->dispatcher->notify(new sfEvent($this, 'edits.cache_fix',
          array('userid' => $row['uid'], 'songid' => $row['id'])));
      }
      
      $twit = new Twitter();
      $twit->genEditMessage($row['uid'],
        Doctrine::getTable('PPE_User_User')->getNameByID($row['uid']), $status);
      
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
