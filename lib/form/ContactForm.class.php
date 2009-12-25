<?php

class ContactForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $max_em = sfConfig::get('app_max_email_length');
    $pieces['name'] = new sfWidgetFormInput();
    $unreq['maxlength'] = $max_em;
    $pieces['email'] = new sfWidgetFormInput(array(), $unreq);
    $max_sb = sfConfig::get('app_max_subject_length');
    $unreq['maxlength'] = $max_sb;
    $pieces['subject'] = new sfWidgetFormInput(array(), $unreq);
    $pieces['content'] = new sfWidgetFormTextarea();
    
    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

    $tmp2['required'] = "You must supply a name.";
    
    $val['name'] = new sfValidatorString(array(), $tmp2);

    $tmp1['max_length'] = $max_em;
    $tmp2['required'] = "You must supply an email address.";
    $tmp2['max_length'] = "Your email address must be at most $max_em characters.";
    $tmp2['invalid'] = "The email address is not in a valid form.";

    $val['email'] = new sfValidatorEmail($tmp1, $tmp2);
    
    $tmp2['required'] = "You must supply a subject.";
    $tmp1['max_length'] = $max_sb;
    $tmp2['max_length'] = "The subject can't be more than $max_sb characters.";
    
    $val['subject'] = new sfValidatorString($tmp1, $tmp2);

    $tmp1['max_length'] = null;
    $min_ct = sfConfig::get('app_min_email_length');
    $tmp1['min_length'] = $min_ct;
    $tmp2['min_length'] = "The email is too short. Add actual sentences.";
    $tmp2['required'] = "You must supply the message.";
    
    $val['content'] = new sfValidatorString($tmp1, $tmp2);

    $this->setValidators($val);
    
  }
}
