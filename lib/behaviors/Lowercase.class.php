<?php

class Lowercase extends Doctrine_Template
{
  protected $_columns = array("columns");

  public function setTableDefinition()
  {
    foreach (preg_split("/\[\]\s,~", $this->_options['columns']) as $column)
    {
      $columnName = "lc_" . $column;
      $table = $this->_table->getTable();
      $table->setColumn($columnName, 'string', null);
    }
  }

  public function setUp()
  {

  }
}