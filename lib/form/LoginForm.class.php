<?php

class LoginForm extends sfForm
{
  public function configure()
  {
    parent::configure();
    
    $pieces['username'] = new sfWidgetFormInput(array(), array());
    $pieces['password'] = new sfWidgetFormInputPassword(array());
    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');
    
    $tmp2['required'] = "Your username is required.";

    $val['username'] = new sfValidatorString(array(), $tmp2);

    $tmp2['required'] = "Your password is required.";

    $val['password'] = new sfValidatorString(array(), $tmp2);

    $this->setValidators($val);
    
  }
}
