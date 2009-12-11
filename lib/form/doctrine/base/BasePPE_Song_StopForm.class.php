<?php

/**
 * PPE_Song_Stop form base class.
 *
 * @method PPE_Song_Stop getObject() Returns the current form's model object
 *
 * @package    pumpproedits
 * @subpackage form
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Song_StopForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'song_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'), 'add_empty' => false)),
      'beat'       => new sfWidgetFormInputText(),
      'break'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'song_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'))),
      'beat'       => new sfValidatorNumber(array('required' => false)),
      'break'      => new sfValidatorNumber(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PPE_Song_Stop', 'column' => array('song_id', 'beat')))
    );

    $this->widgetSchema->setNameFormat('ppe_song_stop[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Song_Stop';
  }

}
