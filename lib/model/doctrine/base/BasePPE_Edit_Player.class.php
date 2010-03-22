<?php

/**
 * BasePPE_Edit_Player
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $edit_id
 * @property integer $player
 * @property integer $steps
 * @property integer $jumps
 * @property integer $holds
 * @property integer $mines
 * @property integer $trips
 * @property integer $rolls
 * @property integer $lifts
 * @property integer $fakes
 * @property PPE_Edit_Edit $PPE_Edit_Edit
 * 
 * @method integer         getEditId()        Returns the current record's "edit_id" value
 * @method integer         getPlayer()        Returns the current record's "player" value
 * @method integer         getSteps()         Returns the current record's "steps" value
 * @method integer         getJumps()         Returns the current record's "jumps" value
 * @method integer         getHolds()         Returns the current record's "holds" value
 * @method integer         getMines()         Returns the current record's "mines" value
 * @method integer         getTrips()         Returns the current record's "trips" value
 * @method integer         getRolls()         Returns the current record's "rolls" value
 * @method integer         getLifts()         Returns the current record's "lifts" value
 * @method integer         getFakes()         Returns the current record's "fakes" value
 * @method PPE_Edit_Edit   getPPEEditEdit()   Returns the current record's "PPE_Edit_Edit" value
 * @method PPE_Edit_Player setEditId()        Sets the current record's "edit_id" value
 * @method PPE_Edit_Player setPlayer()        Sets the current record's "player" value
 * @method PPE_Edit_Player setSteps()         Sets the current record's "steps" value
 * @method PPE_Edit_Player setJumps()         Sets the current record's "jumps" value
 * @method PPE_Edit_Player setHolds()         Sets the current record's "holds" value
 * @method PPE_Edit_Player setMines()         Sets the current record's "mines" value
 * @method PPE_Edit_Player setTrips()         Sets the current record's "trips" value
 * @method PPE_Edit_Player setRolls()         Sets the current record's "rolls" value
 * @method PPE_Edit_Player setLifts()         Sets the current record's "lifts" value
 * @method PPE_Edit_Player setFakes()         Sets the current record's "fakes" value
 * @method PPE_Edit_Player setPPEEditEdit()   Sets the current record's "PPE_Edit_Edit" value
 * 
 * @package    pumpproedits
 * @subpackage model
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
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
             'foreignKeyName' => 'edit_player_fk'));
    }
}