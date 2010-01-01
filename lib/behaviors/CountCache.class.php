<?php
class CountCache extends Doctrine_Template
{
protected $_options = array(
    'relations' => array()
  );
 
  public function setTableDefinition()
  {
    foreach ($this->_options['relations'] as $relation => $options)
    {
      // Build column name if one is not given
      if (!isset($options['columnName']))
      {
        $this->_options['relations'][$relation]['columnName'] = 'num_'.Doctrine_Inflector::tableize($relation);
      }
 
      // Add the column to the related model
      $columnName = $this->_options['relations'][$relation]['columnName'];
      $relatedTable = $this->_table->getRelation($relation)->getTable();
      $this->_options['relations'][$relation]['className'] = $relatedTable->getOption('name');
      $relatedTable->setColumn($columnName, 'integer', null, array('default' => 0));

      #this->addListener(new CountCacheListener($this->_options));
    }
  }
  public function setUp()
  {
  }
}
