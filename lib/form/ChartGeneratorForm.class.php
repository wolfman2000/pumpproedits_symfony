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

    $this->setWidgets($pieces);

    $decorator = new myWidgetFormSchemaFormatterDList($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('dlist', $decorator);
    $this->widgetSchema->setFormFormatterName('dlist');
    $this->widgetSchema->setNameFormat('validate[%s]');
    
    $size = sfConfig::get('app_max_edit_file_size');
    
    $tmp1['required'] = false;
    $tmp1['choices'] = $possible;
    $val['edits'] = new sfValidatorChoice($tmp1, array());
    
    unset($tmp1);
    $tmp1['max_size'] = $size;
    $tmp1['path'] = sfConfig::get('sf_upload_dir');
    $tmp1['required'] = false;
    $messages['max_size'] = sprintf("The edit must be less than %d bytes!", $size);
    $vfile = new sfValidatorFile($tmp1, $messages);
    $val['file'] = $vfile;
    $this->setValidators($val);
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'ensureOne'))));

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
