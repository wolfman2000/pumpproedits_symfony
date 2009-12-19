<?php

/**
 * PPE_Edit_Edit form base class.
 *
 * @method PPE_Edit_Edit getObject() Returns the current form's model object
 *
 * @package    pumpproedits
 * @subpackage form
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Edit_EditForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_User_User'), 'add_empty' => false)),
      'song_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'), 'add_empty' => false)),
      'title'      => new sfWidgetFormInputText(),
      'is_single'  => new sfWidgetFormInputCheckbox(),
      'diff'       => new sfWidgetFormInputText(),
      'steps'      => new sfWidgetFormInputText(),
      'jumps'      => new sfWidgetFormInputText(),
      'holds'      => new sfWidgetFormInputText(),
      'mines'      => new sfWidgetFormInputText(),
      'trips'      => new sfWidgetFormInputText(),
      'rolls'      => new sfWidgetFormInputText(),
      'lifts'      => new sfWidgetFormInputText(),
      'fakes'      => new sfWidgetFormInputText(),
      'is_problem' => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'deleted_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_User_User'))),
      'song_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'))),
      'title'      => new sfValidatorString(array('max_length' => 12)),
      'is_single'  => new sfValidatorBoolean(array('required' => false)),
      'diff'       => new sfValidatorInteger(array('required' => false)),
      'steps'      => new sfValidatorInteger(),
      'jumps'      => new sfValidatorInteger(),
      'holds'      => new sfValidatorInteger(),
      'mines'      => new sfValidatorInteger(),
      'trips'      => new sfValidatorInteger(),
      'rolls'      => new sfValidatorInteger(),
      'lifts'      => new sfValidatorInteger(array('required' => false)),
      'fakes'      => new sfValidatorInteger(array('required' => false)),
      'is_problem' => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'deleted_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PPE_Edit_Edit', 'column' => array('user_id', 'song_id', 'title', 'is_single')))
    );

    $this->widgetSchema->setNameFormat('ppe_edit_edit[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Edit_Edit';
  }

}
