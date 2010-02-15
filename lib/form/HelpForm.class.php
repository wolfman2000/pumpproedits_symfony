<?php

class HelpForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $max_em = sfConfig::get('app_max_email_length');

    $pieces['email'] = new sfWidgetFormInput(array(), array('maxlength' => $max_em));
    $choices = array("reset" => "Reset my password.", "resend" => "Resend my confirmation email.");
    $pieces['choice'] = new sfWidgetFormChoice(array('choices' => $choices));

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

    $tmp2['invalid'] = "You did not choose a selection.";
    unset($tmp2['max_length']);
    $tmp1['choices'] = array_keys($choices);
    unset($tmp1['max_length']);

    $val['choice'] = new sfValidatorChoice($tmp1, $tmp2);

    $this->setValidators($val);
    
  }
}
