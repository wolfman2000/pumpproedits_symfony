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
    $this->part = 'chart/form';
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
        $response->setHttpHeader('Content-Type', 'application/xhtml+xml');
        sfConfig::set('sf_web_debug', false);
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
  
  public function executeAdvanced(sfWebRequest $request)
  {
    if (!$this->getUser()->isAuthenticated())
    {
      $this->forward('login', 'index');
      return;
    }
    $this->form = new ChartGeneratorForm(array('edits' => 0));
    $this->part = 'chart/chart';
  }
 /**
  * Executes validate action (form required)
  *
  * @param sfRequest $request A request object
  */
  public function executeAdvProcess(sfWebRequest $request)
  {
    $this->form = new ChartGeneratorForm(array('rm_file' => "Nevermind", 'edits' => 0));
    $this->form->bind($request->getParameter('validate'), $request->getFiles('validate'));
    $this->part = 'chart/chart';
    $errors = array();
    if ($this->form->isValid())
    {
      $eid = $this->form->getValue('edits');
      if ($eid > 0)
      {
        $path = sfConfig::get('sf_data_dir').sprintf("/user_edits/edit_%06d.edit", $eid);
        $author = Doctrine::getTable('PPE_User_User')->getUserByEditID($eid);
      }
      else
      {
        $file = $this->form->getValue('file');
        $filename = 'uploaded'.sha1($file->getOriginalName());
        $extension = $file->getExtension($file->getOriginalExtension());
        $path = sfConfig::get('sf_upload_dir').'/'.$filename.$extension;
        $file->save($path);
        $author = "Unknown Author";
      }
      
      /* File validation takes place here. */
      $tmp = new EditParser();
      try
      {
        $p['notes'] = 1;
        $p['strict_song'] = 0;
        $p['strict_edit'] = 0;
        $notedata = $tmp->get_stats(fopen($path, "r"), $p);
        if (isset($file))
        {
          @unlink($path);
        }
        // The others can be gotten later.
        $p = array('cols' => $notedata['cols'], 'kind' => $this->form->getValue('kind'), 
        'red4' => $this->form->getValue('red4'), 'speed_mod' => $this->form->getValue('speed'),
        'mpcol' => $this->form->getValue('mpcol'), 'scale' => $this->form->getValue('scale'));

        $tmp = new EditCharter($p);
        $notedata['author'] = $author;
        $xml = $tmp->genChart($notedata);
        
        $response = $this->getResponse();
        $response->clearHttpHeaders();
        $response->setHttpHeader('Content-Type', 'application/xhtml+xml');
        sfConfig::set('sf_web_debug', false);
        $response->setContent($xml->saveXML());
        return sfView::NONE;
      }
      catch (sfParseException $e)
      {
        $this->data = $e->getMessage();
        if (isset($file))
        {
          @unlink($path);
        }
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
  
  public function executeOfficial(sfWebRequest $request)
  {
    if (!$this->getUser()->isAuthenticated())
    {
      $this->forward('login', 'index');
      return;
    }
    $this->form = new ChartOfficialForm();
  }
  
  /**
   * Get the difficulties available for each song (that I have a chart for).
   */
  public function executeAjajDifficulty(sfWebRequest $request)
  {
    if (!$request->isXmlHttpRequest())
    {
      return sfView::NONE;
    }
    $sid = $request->getParameter('songid');
    $ret = Doctrine::getTable('PPE_Song_Song')->getDifficulties($sid);
    $this->getResponse()->setHttpHeader("Content-type", "application/json");
    return $this->renderText(json_encode($ret[0]));
  }
  
  public function executeOffProcess(sfWebRequest $request)
  {
    $this->form = new ChartOfficialForm();
    $this->form->bind($request->getParameter('validate'));
    if ($this->form->isValid())
    {
      $eid = $this->form->getValue('edits');
      $dif = $this->form->getValue('diff');
      $path = sfConfig::get('sf_data_dir').sprintf("/official/%d.sm", $eid);
      
      if (!file_exists($path))
      {
        $this->data = "This song does not have charts presently. Please choose another song.";
        $this->getResponse()->setStatusCode(409);
        return sfView::ERROR;
      }
      
      /* File validation takes place here. */
      $tmp = new EditParser();
      try
      {
        $p['notes'] = 1;
        $p['strict_song'] = 1;
        $p['arcade'] = $dif;
        $notedata = $tmp->get_stats(fopen($path, "r"), $p);

        $p = array('cols' => $notedata['cols'], 'kind' => $this->form->getValue('kind'), 
        'red4' => $this->form->getValue('red4'), 'speed_mod' => $this->form->getValue('speed'),
        'mpcol' => $this->form->getValue('mpcol'), 'scale' => $this->form->getValue('scale'),
        'arcade' => 1);

        $tmp = new EditCharter($p);
        $xml = $tmp->genChart($notedata);
        
        $response = $this->getResponse();
        $response->clearHttpHeaders();
        $response->setHttpHeader('Content-Type', 'application/xhtml+xml');
        sfConfig::set('sf_web_debug', false);
        $response->setContent($xml->saveXML());
        return sfView::NONE;
      }
      catch (sfParseException $e)
      {
        $this->data = $e->getMessage();
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
    
    if (!file_exists($path))
    {
      $this->data = "The edit file does not exist!";
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }

    /* File validation takes place here. */
    $tmp = new EditParser();
    try
    {
      $notedata = $tmp->get_stats(fopen($path, "r"), array('notes' => 1, 'strict_edit' => 0));
      $p = array('cols' => $notedata['cols'], 'kind' => $kind);
      $tmp = new EditCharter($p);
      $xml = $tmp->genChart($notedata);
      
      $response = $this->getResponse();
      $response->clearHttpHeaders();
      $response->setHttpHeader('Content-Type', 'application/xhtml+xml');
      sfConfig::set('sf_web_debug', false);
      $response->setContent($xml->saveXML());
      return sfView::NONE;
    }
    catch (sfParseException $e)
    {
      echo $e;
      $this->data = $e;
      $this->getResponse()->setStatusCode(409);
      return sfView::ERROR;
    }
  }
}
