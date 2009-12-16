<?php

class RegisterForm extends sfForm
{
  public function configure()
  {
    $max_un = sfConfig::get('app_max_username_length');
    $min_un = sfConfig::get('app_min_username_length');
    $min_pw = sfConfig::get('app_min_password_length');
    $max_em = sfConfig::get('app_max_email_length');
    $unreq['maxlength'] = $max_un;
    $pieces['username'] = new sfWidgetFormInput(array(), $unreq);
    $pieces['password'] = new sfWidgetFormInput(array());
    $pieces['passdual'] = new sfWidgetFormInput(array());
    $unreq['maxlength'] = sfConfig::get('app_max_email_length');
    $pieces['email'] = new sfWidgetFormInput(array(), $unreq);
    $this->setWidgets($pieces);
    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

//    $unerr['required'] = "You must supply an email address.";
//    $val['email'] = new sfValidatorEmail(array(), $unerr);

/*
    $unerr['min_length'] = $min_un;
    $messs['min_length'] = "Your username must be at least $min_un characters.";
    $unerr['max_length'] = $min_un;
    $messs['max_length'] = "Your username must be at most $max_un characters.";
    $messs['required'] = "You must supply a username.";

    $val['username'] = new sfValidatorString($unerr, $messs);

    $unerr['min_length'] = $min_pw;
    $messs['min_length'] = "Your password must be at least $min_pw characters.";
    $unerr['max_length'] = null;
    $messs['required'] = "You must supply a password.";

    $val['password'] = new sfValidatorString($unerr, $messs);

    $messs['required'] = "You must supply a confirmation password.";

    $val['passdual'] = new sfValidatorString($unerr, $messs);
*/
    $this->setValidators(array(
      'email'   => new sfValidatorEmail(array('max_length' => $max_em), array('required' => "You must supply an email address.")),
      'username' => new sfValidatorString(array('max_length' => $max_un, 'min_length' => $min_un),
        array('max_length' => "Your username must be at most $max_un characters.", 'min_length' => "Your username must be at least $min_un characters.")),
    ));

//    $this->setValidators(array($val));
  }
}
