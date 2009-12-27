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
    $response = $this->getResponse();
    $response->clearHttpHeaders();
    $response->setContent($xml->saveXML());
    return sfView::NONE;
  }
}
