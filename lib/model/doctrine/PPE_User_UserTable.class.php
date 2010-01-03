<?php

class PPE_User_UserTable extends Doctrine_Table
{
  public function confirmUser($id)
  {
    return $this->createQuery('a')->update()->set('is_confirmed', '?', 1)
      ->where('id = ?', $id)->execute();
  }

  public function addUser($name, $email, $pass)
  {
    $util = new Utilities();
    $salt = $util->genSalt();
    
    $user = new PPE_User_User();
    $user->setName($name);
    $user->setEmail($email);

    $md5 = hash("md5", $pass . $salt);

    $cond = new PPE_User_Condiment();
    $cond->setOregano($md5);
    $cond->setSalt($salt);
    $cond->setPepper(hash("sha256", $pass . $salt));
    
    $user->PPE_User_Condiments[] = $cond;
    
    $role = new PPE_User_Power();
    
    // TODO: Make this a subquery instead of using two queries.
    $role->setRoleID(Doctrine::getTable('PPE_User_Role')->getIDByRole('user')); 
    $user->PPE_User_Powers[] = $role;
    
    $user->save();
    return $md5; // Needed for email confirmation.
  }

  public function getIDByEmail($email)
  {
    $q = $this->createQuery('a')->select('id')->where('email = ?', strtolower($email));
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
  
  public function getIDByUser($user)
  {
    $q = $this->createQuery('a')->select('id')->where('lc_name = ?', strtolower($user));
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['id'];
  }
  
  public function getConfirmedByID($id)
  {
    $q = $this->createQuery('a')->select('is_confirmed')->where('id = ?', $id);
    $q = $q->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    return $q['is_confirmed'];
  }
  
  /* Name and email address */
  public function getAuthByID($id)
  {
    return $this->createQuery('a')->select('name, email')
      ->where('id = ?', $id)
      ->fetchOne(array(), Doctrine::HYDRATE_ARRAY);
  }
  
  public function getNameByID($id)
  {
    $q = $this->createQuery('a')->select('name')->where('id = ?', $id)
      ->fetchOne(array(), Doctrine::HYDRATE_ARRAY);
    return $q['name'];
  }
  
  public function getUserByID($id)
  {
    return $this->getNameByID($id);
  }
  
  public function getUsersWithEdits()
  {
    return $this->createQuery('a')->select('name core, num_edits')
      ->where('num_edits > ?', 0)
      ->andWhere('id != ?', 2)
      ->orderBy('lc_name')->execute();
  }
}
