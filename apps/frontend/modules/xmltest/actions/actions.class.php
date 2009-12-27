<?php

/**
 * xmltest actions.
 *
 * @package    pumpproedits
 * @subpackage xmltest
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class xmltestActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $xml = new DomDocument("1.0", "UTF-8");
    $node = $xml->createElement('svg');
    
    $xml->preserveWhiteSpace = false;
    $xml->formatOutput = true;
    $css = $xml->createProcessingInstruction('xml-stylesheet','type="text/css" href="/css/_svg.css"');
    $xml->appendChild($css);
    $xml->appendChild($node);
    
    $node->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    $node->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    $node->setAttribute('version', '1.1');
    
    $use = $xml->createElement('use');
    $use->setAttribute('x', '0');
    $use->setAttribute('y', '0');
    $use->setAttribute('xlink:href', '/svg/arrowdef.svg#CNarrow');
    $use->setAttribute('class', 'note_008');
    $node->appendChild($use);
    
    
    
    $response = $this->getResponse();
    $response->clearHttpHeaders();
    $response->setHttpHeader('Content-Type', 'application/xml');
    $response->setContent($xml->saveXML());
    return sfView::NONE;
  }
}
