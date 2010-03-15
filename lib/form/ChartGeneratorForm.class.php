<?php

class ChartGeneratorForm extends sfForm
{
  public function configure()
  {
    parent::configure();
    
    $size = sfConfig::get('app_max_edit_file_size');
    $editT = Doctrine::getTable('ITG_Edit_Edit');
    $rows = $editT->getNonProblemEdits();
    
    $choices = array();
    $choices[0] = 'Select an edit.';
    
    $possible[] = 0;
    
    $oname = "無"; // Start with no match.
    
    foreach ($rows as $r):
      $nname = $r['uname'];
      if ($oname !== $nname)
      {
        $choices[$nname] = array();
        $oname = $nname;
      }
      $title = $r['title'];
      if (strlen($title) > 30) { $title = substr($title, 0, 30) . "…"; }
      $choices[$oname][$r['old_edit_id']] = sprintf("%s → %s (%s%d)", $r['sname'], $title, $r['is_single']? "S" : "D", $r['diff']);
      $possible[] = $r['old_edit_id'];
    endforeach;
    
    $pieces['edits'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Choose an edit'), array('size' => 20));

    $tmp1['required'] = false;
    $tmp1['choices'] = $possible;
    $val['edits'] = new sfValidatorChoice($tmp1, array());
    
    $pieces['file'] = new sfWidgetFormInputFile(array('label' => '…or provide your own.'));
    
    $choices = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6, 8 => 8);
    $pieces['speed'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Speed Mod'));
    $this->setDefault('speed', 2);

    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['speed'] = new sfValidatorChoice($tmp1, array('required' => "A speed mod must be chosen."));


    $choices = array(4 => 4, 6 => 6, 8 => 8, 12 => 12, 16 => 16, 24 => 24, 32 => 32);
    $pieces['mpcol'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Measures per column'));
    $this->setDefault('mpcol', 6);

    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['mpcol'] = new sfValidatorChoice($tmp1, array('required' => "You must choose how many measures appear in each column."));
    
    $choices = array('0.5' => 0.5, '0.75' => 0.75, 1 => 1, '1.25' => 1.25, '1.5' => 1.5,
      '1.75' => 1.75, 2 => 2, '2.5' => 2.5, 3 => 3, '3.5' => 3.5, 4 => 4);
    $pieces['scale'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Scale Factor'));
    $this->setDefault('scale', 1);
    
    $tmp1['choices'] = array_keys($choices);
    $val['scale'] = new sfValidatorChoice($tmp1, array('required' => "You must select a scale factor for the chart."));

    $this->setWidgets($pieces);

    $this->widgetSchema->setNameFormat('validate[%s]');
    
    unset($tmp1);
    $tmp1['max_size'] = $size;
    $tmp1['path'] = sfConfig::get('sf_upload_dir');
    $tmp1['required'] = false;
    $messages['max_size'] = sprintf("The edit must be less than %d bytes!", $size);
    $val['file'] = new sfValidatorFile($tmp1, $messages);
    
    $this->setValidators($val);
    
    $this->validatorSchema->setPostValidator(new
      sfValidatorCallback(array('callback' => array($this, 'ensureOne'))));
  }
  
  public function ensureOne($validator, $values)
  {
    if ($values['edits'] xor $values['file'])
    {
      return $values;
    }
    throw new sfValidatorError($validator, "Select either an author's edit or your own file.");
  }
}
