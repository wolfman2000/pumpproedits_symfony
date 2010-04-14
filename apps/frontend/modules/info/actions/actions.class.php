<?php

/**
 * info actions.
 *
 * @package    pumpproedits
 * @subpackage info
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class infoActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    return $this->renderPartial('info/info');
  }
}
