<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Description of UsersTable
 *
 * @author macbookpro
 */
class UsersTable extends Table{
    
    
    const PENDING_USER = 0;
    const ACTIVE_USER = 1;
    const DISABLED_USER = 2;
    
    public function exists($username){
        $_query = $this->find();
        $_query->where(['username' => $username]);
        return ($_query->count() > 0);
    }
    
    public function initialize(array $config){
        parent::initialize($config);
        $this->setTable('users');
        $this->setPrimaryKey('id_user');
    }
}
