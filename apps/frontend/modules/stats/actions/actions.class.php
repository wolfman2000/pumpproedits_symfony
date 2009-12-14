<?php

/**
 * stats actions.
 *
 * @package    pumpproedits
 * @subpackage stats
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statsActions extends sfActions
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
        $this->result = $tmp->get_stats(fopen($path, "r"));
      }
      catch (sfParseException $e)
      {
        $this->result = "There was a problem parsing: " . $e->getMessage();
      }
      /* Do this step at the end. */
      @unlink($path);

    }
  }
}
