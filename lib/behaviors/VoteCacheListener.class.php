<?php
class VoteCacheListener extends Doctrine_Record_Listener
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
          ->set("num_" . $options['baseName'], "num_" . $options['baseName'].' + 1')
          ->set("tot_" . $options['baseName'], "tot_" . $options['baseName'].' + ' . $invoker->rating)
          ->where($relation['local'].' = ?', $invoker->$relation['foreign'])
          ->execute();
      }
    }
  }
  
  public function preUpdate(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();
    $id = $invoker->id;
    $oprob = Doctrine::getTable('PPE_Vote_Vote')->getRatingByID($id);
    $orate = $oprob->rating;
    $oprob = $oprob->is_problem;
    
    foreach ($this->_options['relations'] as $relation => $options)
    {
      $table = Doctrine::getTable($options['className']);
      $relation = $table->getRelation($options['foreignAlias']);
      
      # If there is no problem now:
      if (!(isset($invoker->is_problem) and $invoker->is_problem))
      {
        $q = $table->createQuery()->update();
      
        if ($oprob) # If it was previously a problem:
        {
          $q = $q->set("num_" . $options['baseName'], "num_" . $options['baseName'].' + 1')
          ->set("tot_" . $options['baseName'], "tot_" . $options['baseName'].' + ' . $invoker->rating);
        }
        else # No problem then either. Update the total vote sum.
        {
          $q = $q->set("tot_" . $options['baseName'], "tot_" . $options['baseName'].' - ' . $orate . ' + ' . $invoker->rating);
        }
        $q->where($relation['local'].' = ?', $invoker->$relation['foreign'])
          ->execute();
      }
      else # There is a problem now.
      {
        if ($oprob) # There was a problem previously as well:
        {
        
        }
        else # There was no issue before.
        {
          $table->createQuery()->update()
          ->set("num_" . $options['baseName'], "num_" . $options['baseName'].' - 1')
          ->set("tot_" . $options['baseName'], "tot_" . $options['baseName'].' - ' . $orate)
          ->where($relation['local'].' = ?', $invoker->$relation['foreign'])
          ->execute();
        }
      }
    }
  }
}
