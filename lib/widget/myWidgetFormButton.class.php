<?php
class myWidgetFormButton extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('type');
    
    // to maintain BC with symfony 1.2
    $this->setOption('type', 'submit');
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return $this->renderContentTag('button', self::escapeOnce($value), array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
  }
}

?>
