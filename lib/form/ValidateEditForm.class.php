<?php

class ValidateEditForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'file' => new sfWidgetFormInputFile()
    ));
    $this->widgetSchema->setFormFormatterName('list');
  }
}
