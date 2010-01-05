<?php

class VotingForm extends sfForm
{
  public function configure()
  {
    parent::configure();

    $choices = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $pieces['rating'] = new sfWidgetFormChoice(array('choices' => $choices));
    $pieces['reason'] = new sfWidgetFormTextArea();
    
    $def = array('default' => $this->getUser()->getAttribute('id'));
    $pieces['rater'] = new sfWidgetFormInputHidden($def));
    
    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');

    $tmp2['invalid'] = "You did not rate the edit from 0 - 10.";
    $tmp1['choices'] = $choices;

    $val['rating'] = new sfValidatorEmail($tmp1, $tmp2);

    $this->setValidators($val);
    
  }
}
