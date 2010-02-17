<?php

class ChartGeneratorForm extends sfForm
{
  public function configure()
  {
    parent::configure();
    
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
      $choices[$oname][$r->id] = "$r->sname → $r->title (" . ($r->is_single ? "S" : "D") . "$r->diff)";
      $possible[] = $r->id;
    endforeach;
    
    $pieces['edits'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Choose an edit'), array('size' => 20));
    $pieces['file'] = new sfWidgetFormInputFile(array('label' => '…or provide your own.'));
    $pieces['rm_file'] = new myWidgetFormButton(array('label' => '&nbsp;'), array('type' => 'button'));
    
    unset($choices);
    $choices = array('classic', 'rhythm');
    $pieces['kind'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Noteskin', 'expanded' => true));
    
    $r_choices = array(0 => 'blue', 1 => 'red');
    $pieces['red4'] = new sfWidgetFormChoice(array('choices' => $r_choices, 'label' => '4th Note Color'));
    
    $s_choices = array(1, 2, 3, 4, 6, 8);
    $pieces['speed'] = new sfWidgetFormChoice(array('choices' => $s_choices, 'label' => 'Speed Mod'));
    
    

    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');
    
    $size = sfConfig::get('app_max_edit_file_size');
    
    $tmp1['required'] = false;
    $tmp1['choices'] = $possible;
    $val['edits'] = new sfValidatorChoice($tmp1, array());
    
    $tmp1['required'] = "A noteskin must be chosen.";
    $tmp1['choices'] = array_keys($choices);
    $val['kind'] = new sfValidatorChoice($tmp1, array());
    
    $tmp1['required'] = false;
    $tmp1['choices'] = array_keys($r_choices);
    $val['red4'] = new sfValidatorChoice($tmp1, array());
    
    $tmp1['required'] = "A speed mod must be chosen.";
    $tmp1['choices'] = array_keys($s_choices);
    $val['speed'] = new sfValidatorChoice($tmp1, array());
    
    unset($tmp1);
    $tmp1['max_size'] = $size;
    $tmp1['path'] = sfConfig::get('sf_upload_dir');
    $tmp1['required'] = false;
    $messages['max_size'] = sprintf("The edit must be less than %d bytes!", $size);
    $vfile = new sfValidatorFile($tmp1, $messages);
    $val['file'] = $vfile;
    
    
    
    $this->setValidators($val);
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'ensureOne'))));
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'ensureRhtyhm'))));

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
    if ($values['kind'] === 'classic' or ($values['red4'] === 0 or $values['red4'] === 1))
    {
      return $values;
    }
    throw new sfValidatorError($validator, "If using Rhythm, you must select the quarter note color.");
  }
}
