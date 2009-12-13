<?php

class ValidateEditForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array('file' => new sfWidgetFormInput() ));
    $this->setWidgets->setFormFormatterName('list');
  }
}
