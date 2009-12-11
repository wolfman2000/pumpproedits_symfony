<?php

/**
 * PPE_Edit_Edit filter form base class.
 *
 * @package    pumpproedits
 * @subpackage filter
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Edit_EditFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_User_User'), 'add_empty' => true)),
      'song_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'), 'add_empty' => true)),
      'title'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_single'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'diff'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'steps'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'jumps'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'holds'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mines'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'trips'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rolls'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lifts'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'fakes'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_problem' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PPE_User_User'), 'column' => 'id')),
      'song_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PPE_Song_Song'), 'column' => 'id')),
      'title'      => new sfValidatorPass(array('required' => false)),
      'is_single'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'diff'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'steps'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'jumps'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'holds'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mines'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'trips'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rolls'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lifts'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fakes'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_problem' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ppe_edit_edit_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Edit_Edit';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'user_id'    => 'ForeignKey',
      'song_id'    => 'ForeignKey',
      'title'      => 'Text',
      'is_single'  => 'Boolean',
      'diff'       => 'Number',
      'steps'      => 'Number',
      'jumps'      => 'Number',
      'holds'      => 'Number',
      'mines'      => 'Number',
      'trips'      => 'Number',
      'rolls'      => 'Number',
      'lifts'      => 'Number',
      'fakes'      => 'Number',
      'is_problem' => 'Boolean',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
