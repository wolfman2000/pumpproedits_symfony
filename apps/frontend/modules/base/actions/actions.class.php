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
    $this->page = $request->getParameter('page');
    $this->base = Doctrine::getTable('PPE_Song_Song');
    $this->pager = new sfDoctrinePager('PPE_Song_Song', sfConfig::get('app_base_edits_per_page'));
    $this->pager->setQuery($this->base->getBaseEdits());
    $this->pager->setPage($this->page);
    $this->pager->init();
  }

 /**
  * Executes download action (download the files)
  *
  * @param sfRequest $request A request object
  */
  public function executeDownload(sfWebRequest $request)
  {
    $id = sprintf("%06d", $request->getParameter('id'));
    $type = $request->getParameter('type');
    $name = sprintf("base_%s_%s.edit", $id, ucfirst($type));
    $path = sprintf("%s/data/base_edits/%s", sfConfig::get('sf_root_dir'), $name);
    $file = file_get_contents($path);

    $response = $this->getResponse();
    $response->clearHttpHeaders();
    $response->setHttpHeader('Content-Disposition', 'attachment; filename='.$name);
    $response->setHttpHeader('Content-Length', strlen($file));
    $response->sendHttpHeaders();
    $response->setContent($file);

    return sfView::NONE;
  }
}
