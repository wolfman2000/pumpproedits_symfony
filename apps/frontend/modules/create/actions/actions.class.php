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
}
