<?php

class ChartGeneratorForm extends sfForm
{
  public function configure()
  {
    parent::configure();
    
    $size = sfConfig::get('app_max_edit_file_size');
    $editT = Doctrine::getTable('PPE_Edit_Edit');
    $rows = $editT->getNonProblemEdits();
    
    $choices = array();
    $choices[0] = 'Select an edit.';
    
    $possible[] = 0;
    
    $oname = "無"; // Start with no match.
    
    foreach ($rows as $r):
      $nname = $r->uname;
      if ($oname !== $nname)
      {
        $choices[$nname] = array();
        $oname = $nname;
      }
      $choices[$oname][$r->id] = "$r->sname → $r->title (" . (ucfirst(substr($r->style, 0, 1))) . "$r->diff)";
      $possible[] = $r->id;
    endforeach;
    
    $pieces['edits'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Choose an edit'), array('size' => 20));

    $tmp1['required'] = false;
    $tmp1['choices'] = $possible;
    $val['edits'] = new sfValidatorChoice($tmp1, array());
    
    $pieces['file'] = new sfWidgetFormInputFile(array('label' => '…or provide your own.'));
    
    $choices = array('classic' => 'classic', 'rhythm' => 'rhythm');
    $pieces['kind'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Noteskin'));
    $this->setDefault('kind', 'classic');
    
    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['kind'] = new sfValidatorChoice($tmp1, array('required' => 'A noteskin must be chosen.'));
    
    $choices = array(0 => 'blue', 1 => 'red');
    $pieces['red4'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => '4th Note Color'));
    $this->setDefault('red4', 0);

    $tmp1['required'] = false;
    $tmp1['choices'] = array_keys($choices);
    $val['red4'] = new sfValidatorChoice($tmp1, array());

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
    
    unset($tmp1);
    $tmp1['max_size'] = $size;
    $tmp1['path'] = sfConfig::get('sf_upload_dir');
    $tmp1['required'] = false;
    $messages['max_size'] = sprintf("The edit must be less than %d bytes!", $size);
    $val['file'] = new sfValidatorFile($tmp1, $messages);
    
    $this->setValidators($val);
    
    $one = new sfValidatorCallback(array('callback' => array($this, 'ensureOne')));
    $rhy = new sfValidatorCallback(array('callback' => array($this, 'ensureRhythm')));
    
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(array($one, $rhy)));
  }
  
  public function ensureOne($validator, $values)
  {
    if ($values['edits'] xor $values['file'])
    {
      return $values;
    }
    throw new sfValidatorError($validator, "Select either an author's edit or your own file.");
  }
  
  public function ensureRhythm($validator, $values)
  {
    if ($values['kind'] === 'classic')
    {
      return $values;
    }
    if ($values['kind'] === 'rhythm')
    {
      if ($values['red4'] == 0 or $values['red4'] == 1)
      {
        return $values;
      }
      else
      {
            throw new sfValidatorError($validator, "If using Rhythm, you must select the quarter note color.");
      }
    }
    return $values;
  }
}
