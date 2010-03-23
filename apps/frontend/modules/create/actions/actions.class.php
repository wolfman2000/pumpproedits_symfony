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
    $this->getResponse()->setHttpHeader("Content-type: application/xml");
    $xml = new DomDocument("1.0", "UTF-8");
    $xml->preserveWhiteSpace = false;
    $xml->formatOutput = false;
    
    $song = $xml->createElement('song');
    $xml->appendChild($song);
    
    $sRow = Doctrine::getTable('PPE_Song_Song')->getSongRow($id);
    
    $name = $xml->createElement('name');
    $name->createTextNode($sRow->name);
    $song->appendChild($name);
    
    $meas = $xml->createElement('measures');
    $meas->createTextNode($sRow->measures);
    $song->appendChild($meas);
    
    return $xml;
  }
}
