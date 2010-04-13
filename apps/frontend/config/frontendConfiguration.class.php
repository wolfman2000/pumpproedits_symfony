<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
    $this->dispatcher->connect('edits.cache_fix', array('PPEEvents', 'clearCache'));
  }
}
