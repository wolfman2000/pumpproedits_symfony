<?php

/**
 * PPE_Song_BPM form base class.
 *
 * @method PPE_Song_BPM getObject() Returns the current form's model object
 *
 * @package    pumpproedits
 * @subpackage form
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Song_BPMForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'song_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'), 'add_empty' => false)),
      'beat'       => new sfWidgetFormInputText(),
      'bpm'        => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'song_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'))),
      'beat'       => new sfValidatorNumber(array('required' => false)),
      'bpm'        => new sfValidatorNumber(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PPE_Song_BPM', 'column' => array('song_id', 'beat')))
    );

    $this->widgetSchema->setNameFormat('ppe_song_bpm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Song_BPM';
  }

}
