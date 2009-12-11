<?php

/**
 * PPE_Song_Song form base class.
 *
 * @method PPE_Song_Song getObject() Returns the current form's model object
 *
 * @package    pumpproedits
 * @subpackage form
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Song_SongForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'abbr'       => new sfWidgetFormInputText(),
      'measures'   => new sfWidgetFormInputText(),
      'is_problem' => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 64)),
      'abbr'       => new sfValidatorPass(),
      'measures'   => new sfValidatorInteger(array('required' => false)),
      'is_problem' => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'PPE_Song_Song', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'PPE_Song_Song', 'column' => array('abbr'))),
      ))
    );

    $this->widgetSchema->setNameFormat('ppe_song_song[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Song_Song';
  }

}
