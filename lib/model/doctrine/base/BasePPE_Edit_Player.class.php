<?php

abstract class BasePPE_Edit_Player extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('p_p_e__edit__player');
        $this->hasColumn('edit_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('player', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('steps', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('jumps', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('holds', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('mines', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('trips', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('rolls', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('lifts', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));
        $this->hasColumn('fakes', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 0,
             ));

        $this->index('player_index', array(
             'fields' =>
             array(
              0 => 'edit_id',
              1 => 'player',
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');

        $this->check('steps > 0');
        $this->check('jumps >= 0');
        $this->check('holds >= 0');
        $this->check('mines >= 0');
        $this->check('trips >= 0');
        $this->check('rolls >= 0');
        $this->check('lifts >= 0');
        $this->check('fakes >= 0');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PPE_Edit_Edit', array(
             'local' => 'edit_id',
             'foreign' => 'id',
             'foreignKeyName' => 'edit_edit_fk'));

    }
}
