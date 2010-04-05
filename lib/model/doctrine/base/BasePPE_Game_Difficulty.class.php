<?php

/**
 * BasePPE_Game_Difficulty
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $diff
 * @property Doctrine_Collection $PPE_Song_Difficulties
 * 
 * @method string              getDiff()                  Returns the current record's "diff" value
 * @method Doctrine_Collection getPPESongDifficulties()   Returns the current record's "PPE_Song_Difficulties" collection
 * @method PPE_Game_Difficulty setDiff()                  Sets the current record's "diff" value
 * @method PPE_Game_Difficulty setPPESongDifficulties()   Sets the current record's "PPE_Song_Difficulties" collection
 * 
 * @package    pumpproedits
 * @subpackage model
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BasePPE_Game_Difficulty extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('p_p_e__game__difficulty');
        $this->hasColumn('diff', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'notnull' => true,
             ));


        $this->index('difficulty_index', array(
             'fields' => 
             array(
              0 => 'diff',
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
        $this->hasMany('PPE_Song_Difficulty as PPE_Song_Difficulties', array(
             'local' => 'id',
             'foreign' => 'diff_id'));
    }
}