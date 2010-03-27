<?php

/**
 * create actions.
 *
 * @package    pumpproedits
 * @subpackage create
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class createActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== false)
    {
      $this->getResponse()->setStatusCode(415);
      return sfView::ERROR;
    }
    $this->songs = Doctrine::getTable('PPE_Song_Song')->getSongs();
    $this->getResponse()->setHttpHeader('Content-Type', 'application/xhtml+xml');
  }
  
  /**
   * Get the user's list of edits and other required data
   * for the person to work on it. AJAJ to the rescue!
   */
  public function executeLoadSite(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->getResponse()->setHttpHeader("Content-type", "application/json");
    $ret = array();
    
    $sEdit = Doctrine::getTable('PPE_Edit_Edit')->getSVGEdits($id);
    
    foreach ($sEdit as $s)
    {
      $row = array();
      $row['id'] = $s->id;
      $row['abbr'] = $s->sabbr;
      $row['title'] = $s->title;
      $row['style'] = $s->style;
      $row['diff'] = $s->diff;
      $ret[] = $row;
    }
    
    return $this->renderText(json_encode($ret));
  }
  
  /**
   * Call this on AJAX requests to load the svg
   * properly.
   */
  public function executeAjax(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->getResponse()->setHttpHeader("Content-type", "application/json");
    
    $ret = array();
    
    $sRow = Doctrine::getTable('PPE_Song_Song')->getSongRow($id);
    
    $ret['name'] = $sRow->name;
    $ret['abbr'] = $sRow->abbr;
    $ret['measures'] = $sRow->measures;
    
    $bpms = Doctrine::getTable('PPE_Song_BPM')->getBPMsBySongID($id);
    $bArr = array();
    foreach ($bpms as $b)
    {
      $bArr[] = array('beat' => $b->beat, 'bpm' => $b->bpm);
    }
    $ret['bpms'] = $bArr;
    
    $stps = Doctrine::getTable('PPE_Song_Stop')->getStopsBySongID($id);
    $sArr = array();
    foreach ($stps as $s)
    {
      $sArr[] = array('beat' => $s->beat, 'time' => $s->break . "B");
    }
    $ret['stps'] = $sArr;

    return $this->renderText(json_encode($ret));
  }
  
  public function executeLoadInput(sfWebRequest $request)
  {
    $file = base64_decode($request->getParameter('file'));
    $this->getResponse()->setHttpHeader("Content-type", "application/json");
    $ret = array();
    
    $fp = null;
    $time = date('YmdHis');
    $fn = sfConfig::get('sf_upload_dir') . "/" . $time . ".edit";
    
    try
    {
      $fp = fopen($fn, "w+");
      fwrite($fp, $file);
      fseek($fp, 0);
      
      $tmp = new EditParser();
      
      $st = $tmp->get_stats($fp, array('notes' => 1));
      $ret['id'] = $st['id'];
      $ret['diff'] = $st['diff'];
      $ret['style'] = substr($st['style'], 5);
      $ret['title'] = $st['title'];
      $ret['steps'] = $st['steps'];
      $ret['jumps'] = $st['jumps'];
      $ret['holds'] = $st['holds'];
      $ret['mines'] = $st['mines'];
      $ret['trips'] = $st['trips'];
      $ret['rolls'] = $st['rolls'];
      $ret['lifts'] = $st['lifts'];
      $ret['fakes'] = $st['fakes'];
      $ret['notes'][0] = $st['notes'][0];
      if ($ret['style'] === "routine")
      {
        $ret['notes'][1] = $st['notes'][1];
      }
    }
    catch (sfParseException $e)
    {
      $ret['exception'] = $e->getMessage();
    }
    @unlink($fn);
    return $this->renderText(json_encode($ret));
  }
  
  /**
   * Download the file the user made.
   */
  public function executeDownload(sfWebRequest $request)
  {
    $b64 = $request->getParameter('b64');
    $abbr = $request->getParameter('abbr');
    $style = $request->getParameter('style');
    $diff = $request->getParameter('diff');
    $title = $request->getParameter('title');
    $name = sprintf("svg_%s_%s%d_%s.edit", $abbr, strtoupper(substr($style, 0, 1)), $diff, $title);
    
    $d64 = base64_decode($b64);
    $response = $this->getResponse();
    $response->clearHttpHeaders();
    $response->setHttpHeader('Content-Disposition', 'attachment; filename='.$name);
    $response->setHttpHeader('Content-Length', strlen($d64));
    $response->setHttpHeader('Content-Type', 'application/edit');
    $response->sendHttpHeaders();
    $response->setContent($d64);

    return sfView::NONE;
  }
}
