<?php

/**
 * PPE_Song_Stop filter form base class.
 *
 * @package    pumpproedits
 * @subpackage filter
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePPE_Song_StopFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'song_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PPE_Song_Song'), 'add_empty' => true)),
      'beat'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'break'      => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'song_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PPE_Song_Song'), 'column' => 'id')),
      'beat'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'break'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ppe_song_stop_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PPE_Song_Stop';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'song_id'    => 'ForeignKey',
      'beat'       => 'Number',
      'break'      => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
