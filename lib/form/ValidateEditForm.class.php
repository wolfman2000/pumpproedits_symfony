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
    $this->setValidators(array(
      'file' => new sfValidatorFile(array(
        'max_size' => sfConfig::get('app_max_edit_file_size'),
        'path' => sfConfig::get('sf_upload_dir'),
      ))
    ));
    $this->widgetSchema->setNameFormat('validate[%s]');
  }
}
