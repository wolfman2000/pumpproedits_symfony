<?php

/**
 * BasePPE_User_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $email
 * @property boolean $is_confirmed
 * @property Doctrine_Collection $PPE_User_Powers
 * @property Doctrine_Collection $PPE_User_Condiments
 * @property Doctrine_Collection $PPE_Edit_Edits
 * @property Doctrine_Collection $PPE_Vote_Votes
 * 
 * @method string              getName()                Returns the current record's "name" value
 * @method string              getEmail()               Returns the current record's "email" value
 * @method boolean             getIsConfirmed()         Returns the current record's "is_confirmed" value
 * @method Doctrine_Collection getPPEUserPowers()       Returns the current record's "PPE_User_Powers" collection
 * @method Doctrine_Collection getPPEUserCondiments()   Returns the current record's "PPE_User_Condiments" collection
 * @method Doctrine_Collection getPPEEditEdits()        Returns the current record's "PPE_Edit_Edits" collection
 * @method Doctrine_Collection getPPEVoteVotes()        Returns the current record's "PPE_Vote_Votes" collection
 * @method PPE_User_User       setName()                Sets the current record's "name" value
 * @method PPE_User_User       setEmail()               Sets the current record's "email" value
 * @method PPE_User_User       setIsConfirmed()         Sets the current record's "is_confirmed" value
 * @method PPE_User_User       setPPEUserPowers()       Sets the current record's "PPE_User_Powers" collection
 * @method PPE_User_User       setPPEUserCondiments()   Sets the current record's "PPE_User_Condiments" collection
 * @method PPE_User_User       setPPEEditEdits()        Sets the current record's "PPE_Edit_Edits" collection
 * @method PPE_User_User       setPPEVoteVotes()        Sets the current record's "PPE_Vote_Votes" collection
 * 
 * @package    pumpproedits
 * @subpackage model
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BasePPE_User_User extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('p_p_e__user__user');
        $this->hasColumn('name', 'string', 12, array(
             'type' => 'string',
             'length' => 12,
             'notnull' => true,
             ));
        $this->hasColumn('email', 'string', 320, array(
             'type' => 'string',
             'length' => 320,
             'notnull' => true,
             ));
        $this->hasColumn('is_confirmed', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));


        $this->index('user_index', array(
             'fields' => 
             array(
              0 => 'name',
             ),
             'type' => 'unique',
             ));
        $this->index('email_index', array(
             'fields' => 
             array(
              0 => 'email',
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('PPE_User_Power as PPE_User_Powers', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('PPE_User_Condiment as PPE_User_Condiments', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('PPE_Edit_Edit as PPE_Edit_Edits', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('PPE_Vote_Vote as PPE_Vote_Votes', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $lowercase0 = new Lowercase(array(
             'columns' => 
             array(
              'name' => 
              array(
              'columnName' => 'lc_name',
              ),
              'email' => 
              array(
              'columnName' => 'lc_email',
              ),
             ),
             ));
        $this->actAs($timestampable0);
        $this->actAs($lowercase0);
    }
}