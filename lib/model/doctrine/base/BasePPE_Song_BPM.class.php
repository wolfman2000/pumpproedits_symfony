<?php

/**
 * BasePPE_Song_BPM
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $song_id
 * @property decimal $beat
 * @property decimal $bpm
 * @property PPE_Song_Song $PPE_Song_Song
 * 
 * @method integer       getSongId()        Returns the current record's "song_id" value
 * @method decimal       getBeat()          Returns the current record's "beat" value
 * @method decimal       getBpm()           Returns the current record's "bpm" value
 * @method PPE_Song_Song getPPESongSong()   Returns the current record's "PPE_Song_Song" value
 * @method PPE_Song_BPM  setSongId()        Sets the current record's "song_id" value
 * @method PPE_Song_BPM  setBeat()          Sets the current record's "beat" value
 * @method PPE_Song_BPM  setBpm()           Sets the current record's "bpm" value
 * @method PPE_Song_BPM  setPPESongSong()   Sets the current record's "PPE_Song_Song" value
 * 
 * @package    pumpproedits
 * @subpackage model
 * @author     JasonWolfman2000Felds
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BasePPE_Song_BPM extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('p_p_e__song__b_p_m');
        $this->hasColumn('song_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('beat', 'decimal', 8, array(
             'type' => 'decimal',
             'notnull' => true,
             'default' => 0,
             'size' => 8,
             'scale' => 4,
             'length' => 8,
             ));
        $this->hasColumn('bpm', 'decimal', 8, array(
             'type' => 'decimal',
             'notnull' => false,
             'size' => 8,
             'scale' => 4,
             'length' => 8,
             ));


        $this->index('bpm_index', array(
             'fields' => 
             array(
              0 => 'song_id',
              1 => 'beat',
             ),
             'type' => 'unique',
             ));
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');

        $this->check('beat >= 0');
        $this->check('bpm IS NULL OR bpm > 0');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('PPE_Song_Song', array(
             'local' => 'song_id',
             'foreign' => 'id',
             'foreignKeyName' => 'bpm_song_fk'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}