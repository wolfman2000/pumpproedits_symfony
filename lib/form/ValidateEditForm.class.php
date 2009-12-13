<?php

class ValidateEditForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'file' => new sfWidgetFormInputFile()
    ));
    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);

    $this->widgetSchema->setFormFormatterName('dlist');
  }
}
