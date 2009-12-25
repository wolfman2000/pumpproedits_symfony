<?php

class ConfirmForm extends sfForm
{
  public function configure($useConfirm = true)
  {
    parent::configure();

    $max_un = sfConfig::get('app_max_oregano_length');
    $min_un = sfConfig::get('app_min_oregano_length');
    
    $unreq['maxlength'] = $max_un;
    $pieces['confirm'] = new sfWidgetFormInput(array('label' => 'Confirmation Code'), $unreq);
    $pieces['password'] = new sfWidgetFormInputPassword(array());
    $unreq['maxlength'] = sfConfig::get('app_max_email_length');
    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

    $tmp1['max_length'] = $max_un;
    $tmp1['min_length'] = $min_un;
    $tmp1['pattern'] = "/([0-9A-Fa-f]){32}/";
    $tmp2['max_length'] = "The confirmation code must be $max_un characters.";
    $tmp2['min_length'] = $tmp2['max_length'];
    
    $tmp2['required'] = "You must supply the confirmation code.";
    $tmp2['invalid'] = "The confirmation code did not match the pattern.";

    $val['confirm'] = new sfValidatorRegex($tmp1, $tmp2);

    $tmp2['required'] = "You must supply a password.";

    $val['password'] = new sfValidatorString(array(), $tmp2);

    $this->setValidators($val);
    
  }
}
