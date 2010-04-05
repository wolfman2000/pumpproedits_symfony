<?php

class ChartOfficialForm extends sfForm
{
  public function configure()
  {
    parent::configure();
    
    $songT = Doctrine::getTable('PPE_Song_Song');
    $rows = $songT->getSongsWithGameAndDiff();
    
    $choices = array();
    $choices[0] = 'Select a song.';
    
    $possible[] = 0;
    
    $oid = "無"; // Start with no match.
    $game = "Tmp";
    foreach ($rows as $r):
      $nid = $r->gid;
      if ($oid !== $nid)
      {
        $game = "Pump it up Pro" . ($nid == 1 ? "" : " 2");
        $choices[$game] = array();
        $oid = $nid;
      }
      $choices[$game][$r->id] = $r->name;
      $possible[] = $r->id;
    endforeach;
    
    $pieces['edits'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Choose a song'), array('size' => 20));

    $tmp1['required'] = false;
    $tmp1['choices'] = $possible;
    $val['edits'] = new sfValidatorChoice($tmp1, array());
    
/*    
    $choices = array();
    
    foreach ($rows as $r):
      $choices[$r->id] = $r->name;
    endforeach;
    
    $pieces['edits'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Choose a song'), array('size' => 20));

    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['edits'] = new sfValidatorChoice($tmp1, array());
*/    
    $choices = array('ez' => 'Easy', 'nr' => 'Normal', 'hr' => 'Hard', 'cz' => 'Crazy',
      'hd' => 'Halfdouble', 'fs' => 'Freestyle', 'nm' => 'Nightmare', 'rt' => 'Routine');
    $pieces['diff'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Difficulty'));
    $this->setDefault('diff', 'cz');
    
    $tmp1['required'] = true;
    $tmp1['choices'] = array_keys($choices);
    $val['diff'] = new sfValidatorChoice($tmp1, array('required' => 'A difficulty must be chosen.'));
    
    $choices = array('classic' => 'classic', 'rhythm' => 'rhythm');
    $pieces['kind'] = new sfWidgetFormChoice(array('choices' => $choices, 'label' => 'Noteskin'));
    $this->setDefault('kind', 'classic');
    
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
    
    
    $this->setValidators($val);
    
    $rhy = new sfValidatorCallback(array('callback' => array($this, 'ensureRhythm')));
    $this->validatorSchema->setPostValidator($rhy);
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
