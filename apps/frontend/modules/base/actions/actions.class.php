<?php

/**
 * base actions.
 *
 * @package    pumpproedits
 * @subpackage base
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class baseActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $b = Doctrine::getTable('PPE_Song_Song')->getBaseEdits();
    $this->pager = $b;
  }

 /**
  * Executes download action (download the files)
  *
  * @param sfRequest $request A request object
  */
  public function executeDownload(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $type = $request->getParameter('type');
  }
}
