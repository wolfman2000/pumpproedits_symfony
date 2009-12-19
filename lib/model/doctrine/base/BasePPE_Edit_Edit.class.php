<?php

/**
 * BasePPE_Edit_Edit
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $song_id
 * @property string $title
 * @property boolean $is_single
 * @property integer $diff
 * @property integer $steps
 * @property integer $jumps
 * @property integer $holds
 * @property integer $mines
 * @property integer $trips
 * @property integer $rolls
 * @property integer $lifts
 * @property integer $fakes
 * @property boolean $is_problem
 * @property PPE_User_User $PPE_User_User
 * @property PPE_Song_Song $PPE_Song_Song
 * @property Doctrine_Collection $PPE_Vote_Votes
 * 
 * @method integer             getUserId()         Returns the current record's "user_id" value
 * @method integer             getSongId()         Returns the current record's "song_id" value
 * @method string              getTitle()          Returns the current record's "title" value
 * @method boolean             getIsSingle()       Returns the current record's "is_single" value
 * @method integer             getDiff()           Returns the current record's "diff" value
 * @method integer             getSteps()          Returns the current record's "steps" value
 * @method integer             getJumps()          Returns the current record's "jumps" value
 * @method integer             getHolds()          Returns the current record's "holds" value
 * @method integer             getMines()          Returns the current record's "mines" value
 * @method integer             getTrips()          Returns the current record's "trips" value
 * @method integer             getRolls()          Returns the current record's "rolls" value
 * @method integer             getLifts()          Returns the current record's "lifts" value
 * @method integer             getFakes()          Returns the current record's "fakes" value
 * @method boolean             getIsProblem()      Returns the current record's "is_problem" value
 * @method PPE_User_User       getPPEUserUser()    Returns the current record's "PPE_User_User" value
 * @method PPE_Song_Song       getPPESongSong()    Returns the current record's "PPE_Song_Song" value
 * @method Doctrine_Collection getPPEVoteVotes()   Returns the current record's "PPE_Vote_Votes" collection
 * @method PPE_Edit_Edit       setUserId()         Sets the current record's "user_id" value
 * @method PPE_Edit_Edit       setSongId()         Sets the current record's "song_id" value
 * @method PPE_Edit_Edit       setTitle()          Sets the current record's "title" value
 * @method PPE_Edit_Edit       setIsSingle()       Sets the current record's "is_single" value
 * @method PPE_Edit_Edit       setDiff()           Sets the current record's "diff" value
 * @method PPE_Edit_Edit       setSteps()          Sets the current record's "steps" value
 * @method PPE_Edit_Edit       setJumps()          Sets the current record's "jumps" value
 * @method PPE_Edit_Edit       setHolds()          Sets the current record's "holds" value
 * @method PPE_Edit_Edit       setMines()          Sets the current record's "mines" value
 * @method PPE_Edit_Edit       setTrips()          Sets the current record's "trips" value
 * @method PPE_Edit_Edit       setRolls()          Sets the current record's "rolls" value
 * @method PPE_Edit_Edit       setLifts()          Sets the current record's "lifts" value
 * @method PPE_Edit_Edit       setFakes()          Sets the current record's "fakes" value
 * @method PPE_Edit_Edit       setIsProblem()      Sets the current record's "is_problem" value
 * @method PPE_Edit_Edit       setPPEUserUser()    Sets the current record's "PPE_User_User" value
 * @method PPE_Edit_Edit       setPPESongSong()    Sets the current record's "PPE_Song_Song" value
 * @method PPE_Edit_Edit       setPPEVoteVotes()   Sets the current record's "PPE_Vote_Votes" collection
 * 
 * @package    pumpproedits
 * @subpackage model
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BasePPE_Edit_Edit extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('p_p_e__edit__edit');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('song_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 12, array(
             'type' => 'string',
             'length' => 12,
             'notnull' => true,
             ));
        $this->hasColumn('is_single', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('diff', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 10,
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
        $this->hasColumn('is_problem', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));


        $this->index('author_index', array(
             'fields' => 
             array(
              0 => 'user_id',
              1 => 'song_id',
              2 => 'title',
              3 => 'is_single',
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');

        $this->check('diff > 0');
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
        $this->hasOne('PPE_User_User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignKeyName' => 'edit_user_fk'));

        $this->hasOne('PPE_Song_Song', array(
             'local' => 'song_id',
             'foreign' => 'id',
             'foreignKeyName' => 'edit_song_fk'));

        $this->hasMany('PPE_Vote_Vote as PPE_Vote_Votes', array(
             'local' => 'id',
             'foreign' => 'edit_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}