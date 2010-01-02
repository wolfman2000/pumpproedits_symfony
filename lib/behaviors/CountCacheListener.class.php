<?php
class CountCacheListener extends Doctrine_Record_Listener
{
  protected $_options;
 
  public function __construct(array $options)
  {
    $this->_options = $options;
  }

  public function preInsert(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();
    
    foreach ($this->_options['relations'] as $relation => $options)
    {
      $table = Doctrine::getTable($options['className']);
      $relation = $table->getRelation($options['foreignAlias']);
      
      if (!(isset($invoker->is_problem) and $invoker->is_problem))
      {
        $table->createQuery()->update()
          ->set($options['columnName'], $options['columnName'].' + 1')
          ->where($relation['local'].' = ?', $invoker->$relation['foreign'])
          ->execute();
      }
    }
  }
  
  public function preUpdate(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();
    $id = $invoker->id;
    $oprob = Doctrine::getTable('PPE_Edit_Edit')->getProblemByID($id);
    
    foreach ($this->_options['relations'] as $relation => $options)
    {
      $table = Doctrine::getTable($options['className']);
      $relation = $table->getRelation($options['foreignAlias']);
      
      
      if (!(isset($invoker->is_problem) and $invoker->is_problem and !$oprob))
      {
        $table->createQuery()->update()
          ->set($options['columnName'], $options['columnName'].' + 1')
          ->where($relation['local'].' = ?', $invoker->$relation['foreign'])
          ->execute();
      }
      elseif (isset($invoker->is_problem) and $invoker->is_problem and !$oprob)
      {
        $table->createQuery()->update()
          ->set($options['columnName'], $options['columnName'].' - 1')
          ->where($relation['local'].' = ?', $invoker->$relation['foreign'])
          ->execute();
      }
    }
  }

  public function postDelete(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();
    foreach ($this->_options['relations'] as $relation => $options)
    {
      $table = Doctrine::getTable($options['className']);
      $relation = $table->getRelation($options['foreignAlias']);
 
      $table
        ->createQuery()
        ->update()
        ->set($options['columnName'], $options['columnName'].' - 1')
        ->where($relation['local'].' = ?', $invoker->$relation['foreign'])
        ->execute();
    }
  }
  public function preDqlDelete(Doctrine_Event $event)
  {
    foreach ($this->_options['relations'] as $relation => $options)
    {
      $table = Doctrine::getTable($options['className']);
      $relation = $table->getRelation($options['foreignAlias']);
 
      $q = clone $event->getQuery();
      $q->select($relation['foreign']);
      $ids = $q->execute(array(), Doctrine::HYDRATE_NONE);
 
      foreach ($ids as $id)
      {
        $id = $id[0];
 
        $table
          ->createQuery()
          ->update()
          ->set($options['columnName'], $options['columnName'].' - 1')
          ->where($relation['local'].' = ?', $id)
          ->execute();
      }
    }
  }
}
