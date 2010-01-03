<?php
class VoteCache extends Doctrine_Template
{
  protected $_options = array(
    'relations' => array()
  );
 
  public function setTableDefinition()
  {
    foreach ($this->_options['relations'] as $relation => $options)
    {
      // Build base name if one is not given
      if (!isset($options['baseName']))
      {
        $this->_options['relations'][$relation]['baseName'] = Doctrine_Inflector::tableize($relation);
      }
 
      // Add the 2 columns to the related model
      $baseName = $this->_options['relations'][$relation]['baseName'];
      $relatedTable = $this->_table->getRelation($relation)->getTable();
      $this->_options['relations'][$relation]['className'] = $relatedTable->getOption('name');
      $relatedTable->setColumn("num_" . $baseName, 'integer', null, array('default' => 0));
      $relatedTable->setColumn("tot_" . $baseName, 'integer', null, array('default' => 0));

    }
    $this->addListener(new VoteCacheListener($this->_options));
  }
  public function setUp()
  {
  }
}
