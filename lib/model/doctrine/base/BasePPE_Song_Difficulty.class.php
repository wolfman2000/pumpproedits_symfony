<?php

/**
 * BasePPE_Song_Difficulty
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $song_id
 * @property integer $diff_id
 * @property PPE_Song_Song $PPE_Song_Song
 * @property PPE_Game_Difficulty $PPE_Game_Difficulty
 * 
 * @method integer             getSongId()              Returns the current record's "song_id" value
 * @method integer             getDiffId()              Returns the current record's "diff_id" value
 * @method PPE_Song_Song       getPPESongSong()         Returns the current record's "PPE_Song_Song" value
 * @method PPE_Game_Difficulty getPPEGameDifficulty()   Returns the current record's "PPE_Game_Difficulty" value
 * @method PPE_Song_Difficulty setSongId()              Sets the current record's "song_id" value
 * @method PPE_Song_Difficulty setDiffId()              Sets the current record's "diff_id" value
 * @method PPE_Song_Difficulty setPPESongSong()         Sets the current record's "PPE_Song_Song" value
 * @method PPE_Song_Difficulty setPPEGameDifficulty()   Sets the current record's "PPE_Game_Difficulty" value
 * 
 * @package    pumpproedits
 * @subpackage model
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BasePPE_Song_Difficulty extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('p_p_e__song__difficulty');
        $this->hasColumn('song_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('diff_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             ));


        $this->index('songs_in_game_index', array(
             'fields' => 
             array(
              0 => 'song_id',
              1 => 'diff_id',
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
        $this->hasOne('PPE_Song_Song', array(
             'local' => 'song_id',
             'foreign' => 'id',
             'foreignKeyName' => 'song_diff_fk'));

        $this->hasOne('PPE_Game_Difficulty', array(
             'local' => 'diff_id',
             'foreign' => 'id',
             'foreignKeyName' => 'game_diff_fk'));
    }
}