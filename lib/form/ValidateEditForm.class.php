<?php

class ValidateEditForm extends sfForm
{
  public function configure()
  {
    $val = array('label' => 'file');
    $this->widgetSchema['file'] = new sfWidgetFormInputFile($val);
    $val = array('required' => true, 'path' => sfConfig::get('sf_upload_dir'));
    $this->validationSchema['file'] = new sfValidatorFile($val);
#    $this->setWidgets(array('file' => new sfWidgetFormInputFile() ));
#    $this->setWidgets->setFormFormatterName('list');
  }
}
