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
    foreach (preg_split("/\[\]\s,~", $this->_options['columns']) as $column)
    {
      $name = $record->getTable()->getFieldName("lc_" . $column);
      $record->name = strtolower($record->getTable()->getFieldName($column));
    }
}
  
  public function preUpdate(Doctrine_Event $event)
  {
  
  }
}