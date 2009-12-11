<?php

/**
 * PPE_Vote_Vote form base class.
 *
 * @method PPE_Vote_Vote getObject() Returns the current form's model object
 *
 * @package    pumpproedits
 * @subpackage form
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Vote_VoteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_User_User'), 'add_empty' => false)),
      'edit_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Edit_Edit'), 'add_empty' => false)),
      'rating'     => new sfWidgetFormInputText(),
      'reason'     => new sfWidgetFormTextarea(),
      'is_problem' => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_User_User'))),
      'edit_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Edit_Edit'))),
      'rating'     => new sfValidatorInteger(),
      'reason'     => new sfValidatorString(array('required' => false)),
      'is_problem' => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PPE_Vote_Vote', 'column' => array('user_id', 'edit_id', 'rating')))
    );

    $this->widgetSchema->setNameFormat('ppe_vote_vote[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Vote_Vote';
  }

}
