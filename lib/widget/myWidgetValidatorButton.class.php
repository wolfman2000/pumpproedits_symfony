<?php
class myWidgetValidatorButton extends sfValidatorBase
{
  protected function configure($options = array(), $attributes = array())
  {
  }
  
  public function isEmpty($value = null)
  {
    return true;
  }
}

?>
