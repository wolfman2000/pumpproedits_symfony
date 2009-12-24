<?php

class Lowercase extends Doctrine_Template
{
  protected $_options = array("columns" => array());

  public function setTableDefinition()
  {
    $line = $this->_options['columns'];
    $table = $this->_table;
    foreach ($line as $key => $column)
    {
      if (is_array($column))
      {
        $columnName = $column['columnName'];
      }
      else
      {
        $columnName = "lc_" . $column;
      }
      
      $def = $table->getColumnDefinition($key);
      
      $table->setColumn($columnName, $def['type'], $def['length']);
    }
    
    $this->addListener(new LowercaseListener($this->_options));
  }

  public function setUp()
  {

  }
}
