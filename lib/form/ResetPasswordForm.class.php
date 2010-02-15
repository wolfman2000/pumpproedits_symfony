<?php

class ResetPasswordForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $max_un = sfConfig::get('app_max_oregano_length');
    $min_un = sfConfig::get('app_min_oregano_length');
    $min_pw = sfConfig::get('app_min_password_length');
    
    $unreq['maxlength'] = $max_un;
    $pieces['confirm'] = new sfWidgetFormInput(array('label' => 'Confirmation Code'), $unreq);
    $pieces['password'] = new sfWidgetFormInputPassword(array('label' => 'New Password'));
    $pieces['passdual'] = new sfWidgetFormInputPassword(array('label' => 'Confirm Password'));
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

    unset($tmp1['max_length']);
    unset($tmp1['pattern']);
    $tmp1['min_length'] = $min_pw;
    $tmp2['min_length'] = "Your password must be at least $min_pw characters.";
    $tmp2['required'] = "You must supply a password.";

    $val['password'] = new sfValidatorString($tmp1, $tmp2);
    
    $tmp2['required'] = "You must supply a confirmation password.";

    $val['passdual'] = new sfValidatorString($tmp1, $tmp2);

    $this->setValidators($val);
    
    $tmp1 = array();
    $tmp2 = array();
    $tmp2['invalid'] = 'Both passwords must be the same.';
    $val = new sfValidatorSchemaCompare('password', '==', 'passdual', $tmp1, $tmp2);
    $this->validatorSchema->setPostValidator($val);
  }
}
