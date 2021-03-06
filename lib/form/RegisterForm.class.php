<?php

class RegisterForm extends sfForm
{
  public function configure()
  {
    parent::configure();


    $max_un = sfConfig::get('app_max_username_length');
    $min_un = sfConfig::get('app_min_username_length');
    $min_pw = sfConfig::get('app_min_password_length');
    $max_em = sfConfig::get('app_max_email_length');
    $unreq['maxlength'] = $max_un;
    $pieces['username'] = new sfWidgetFormInput(array(), $unreq);
    $pieces['password'] = new sfWidgetFormInputPassword(array());
    $pieces['passdual'] = new sfWidgetFormInputPassword(array('label' => 'Confirm Password'));
    $unreq['maxlength'] = sfConfig::get('app_max_email_length');
    $pieces['email'] = new sfWidgetFormInput(array(), $unreq);
    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

    $tmp1['max_length'] = $max_em;
    $tmp2['required'] = "You must supply an email address.";
    $tmp2['max_length'] = "Your email address must be at most $max_em characters.";
    $tmp2['invalid'] = "The email address is not in a valid form.";

    $val['email'] = new sfValidatorEmail($tmp1, $tmp2);

    $tmp1['max_length'] = $max_un;
    $tmp1['min_length'] = $min_un;
    $tmp2['max_length'] = "Your username must be at most $max_un characters.";
    $tmp2['min_length'] = "Your username must be at least $min_un characters.";
    $tmp2['required'] = "You must supply a username.";
    $tmp2['invalid'] = null;

    $val['username'] = new sfValidatorString($tmp1, $tmp2);

    $tmp1['max_length'] = null;
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
