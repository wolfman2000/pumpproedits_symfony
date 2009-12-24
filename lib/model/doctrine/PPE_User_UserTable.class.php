<?php

class PPE_User_UserTable extends Doctrine_Table
{
  public function addUser($name, $email, $pass)
  {
    $util = new Utilities();
    $salt = $util->genSalt();
    
    $user = new PPE_User_User();
    $user->setName($name);
    $user->setEmail($email);

    $cond = new PPE_User_Condiment();
    $cond->setOregano(hash("md5", $pass . $salt));
    $cond->setSalt($salt);
    $cond->setPepper(hash("sha256", $pass . $salt));
    
    $user->PPE_User_Condiments[] = $cond;
    
    $role = new PPE_User_Power();
    $role->setRoleID(Doctrine::getTable('PPE_User_Role')->getIDByRole('user')); 
    $user->PPE_User_Powers[] = $role;
    
    
    $user->save();
    return $salt; // Needed for email confirmation.
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
}
