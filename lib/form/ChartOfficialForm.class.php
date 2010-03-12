<?php

class ChartOfficialForm extends sfForm
{
  public function configure()
  {
    parent::configure();
    
    $songT = Doctrine::getTable('ITG_Song_Song');
    $rows = $songT->getSongs();
    
    $choices = array();
    
    foreach ($rows as $r):
      $choices[$r->id] = $r->name;
    endforeach;
    
    $pieces['edits'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Choose a song'), array('size' => 20));

    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['edits'] = new sfValidatorChoice($tmp1, array());
    
    $choices = array('Single' => 'Single', 'Double' => 'Double');
    $pieces['style'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Style'));
    
    $tmp1['choices'] = array_keys($choices);
    $val['style'] = new sfValidatorChoice($tmp1, array('required' => 'A style must be chosen.'));
    
    $choices = array('b' => 'Beginner', 'e' => 'Easy', 'm' => 'Medium', 'h' => 'Hard', 'x' => 'Expert');
    $pieces['diff'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Difficulty'));
    $this->setDefault('diff', 'h');
    
    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['diff'] = new sfValidatorChoice($tmp1, array('required' => 'A difficulty must be chosen.'));
    
    $choices = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6, 8 => 8);
    $pieces['speed'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Speed Mod'));
    $this->setDefault('speed', 2);

    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['speed'] = new sfValidatorChoice($tmp1, array('required' => "A speed mod must be chosen."));


    $choices = array(4 => 4, 6 => 6, 8 => 8, 12 => 12, 16 => 16);
    $pieces['mpcol'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Measures per column'));
    $this->setDefault('mpcol', 6);

    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['mpcol'] = new sfValidatorChoice($tmp1, array('required' => "You must choose how many measures appear in each column."));
    
    $choices = array('0.5' => 0.5, '0.75' => 0.75, 1 => 1, '1.25' => 1.25, '1.5' => 1.5, '1.75' => 1.75, 2 => 2);
    $pieces['scale'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Scale Factor'));
    $this->setDefault('scale', 1);
    
    $tmp1['choices'] = array_keys($choices);
    $val['scale'] = new sfValidatorChoice($tmp1, array('required' => "You must select a scale factor for the chart."));

    $this->setWidgets($pieces);

    $this->widgetSchema->setNameFormat('validate[%s]');
    
    
    $this->setValidators($val);
  }
}
