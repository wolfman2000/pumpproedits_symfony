<?php

class ValidateEditForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

    $this->setWidgets(array('file' => new sfWidgetFormInputFile()));
    $validate['max_size'] = $size;
    $validate['path'] = sfConfig::get('sf_upload_dir');
    $messages['required'] = 'You must submit an edit file!';
    $messages['max_size'] = sprintf("The edit must be less than %d bytes!", $size);
    $vfile = new sfValidatorFile($validate, $messages);
    $val['file'] = $vfile;
    $this->setValidators($val);
    $this->widgetSchema->setNameFormat('validate[%s]');
  }
}
