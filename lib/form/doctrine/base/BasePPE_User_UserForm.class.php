<?php

/**
 * PPE_User_User form base class.
 *
 * @method PPE_User_User getObject() Returns the current form's model object
 *
 * @package    pumpproedits
 * @subpackage form
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_User_UserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'name'         => new sfWidgetFormInputText(),
      'email'        => new sfWidgetFormTextarea(),
      'is_confirmed' => new sfWidgetFormInputCheckbox(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'lc_name'      => new sfWidgetFormInputText(),
      'lc_email'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'         => new sfValidatorString(array('max_length' => 12)),
      'email'        => new sfValidatorString(array('max_length' => 320)),
      'is_confirmed' => new sfValidatorBoolean(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'lc_name'      => new sfValidatorString(array('max_length' => 12, 'required' => false)),
      'lc_email'     => new sfValidatorString(array('max_length' => 320, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'PPE_User_User', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'PPE_User_User', 'column' => array('email'))),
      ))
    );

    $this->widgetSchema->setNameFormat('ppe_user_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_User_User';
  }

}
