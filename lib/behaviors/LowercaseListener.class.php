<?php

class LowercaseListener extends Doctrine_Record_Listener
{
  protected $_options;
  
  public function __construct(array $options)
  {
    $this->_options = $options;
  }
  
  public function preInsert(Doctrine_Event $event)
  {
    $record = $event->getInvoker();
    
    foreach ($this->_options['columns'] as $key => $column)
    {
      if (is_array($column))
      {
        $columnName = $column['columnName'];
        $origCol = $key;
      }
      else
      {
        $columnName = "lc_" . $column;
        $origCol = $column;
      }
      
      $record->$columnName = strtolower($record->$origCol);
    }
  }
  
  public function preUpdate(Doctrine_Event $event)
  {
    $record = $event->getInvoker();
    
    foreach ($this->_options['columns'] as $key => $column)
    {
      if (is_array($column))
      {
        $columnName = $column['columnName'];
        $origCol = $key;
      }
      else
      {
        $columnName = "lc_" . $column;
        $origCol = $column;
      }
      
      $record->$columnName = strtolower($record->$origCol);
    }
  }
}