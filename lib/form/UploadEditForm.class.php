<?php

class UploadEditForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $max_em = sfConfig::get('app_max_email_length');
    
    $pieces['file'] = new sfWidgetFormInputFile();

    $choices = array('me' => "Me", 'piu' => "Andamiro", 'other' => "Someone else");
    $pieces['owner'] = new sfWidgetFormChoice(array('choices' => $choices));

    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

    $size = sfConfig::get('app_max_edit_file_size');
    
    $tmp1['max_size'] = $size;
    $tmp1['path'] = sfConfig::get('sf_upload_dir');
    $tmp2['required'] = 'You must submit an edit file!';
    $tmp2['max_size'] = sprintf("The edit must be less than %d bytes!", $size);

    $val['file'] = new sfValidatorFile($tmp1, $tmp2);

    unset($tmp1);
    unset($tmp2);
    $tmp1['choices'] = array_keys($choices);
    $tmp2['invalid'] = "You did not choose a selection.";

    $val['owner'] = new sfValidatorChoice($tmp1, $tmp2);

    $this->setValidators($val);
    
  }
}
