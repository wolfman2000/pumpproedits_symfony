<?php
class PPEEvents
{
  public function clearCache(sfEvent $event)
  {
    $cM = sfContext::getInstance()->getViewCacheManager();
    $cM->remove('edits/song');
    $cM->remove('edits/chosenSong?id=' . $event['songid'] . "&page=1");
    $cM->remove('edits/user');
    $cM->remove('edits/chosenUser?id=' . $event['userid'] . "&page=1");
    $cM->remove('edits/official?page=1');
    $cM->remove('create/loadSite?id='  . $event['userid'] . "&page=1");
  }
}
