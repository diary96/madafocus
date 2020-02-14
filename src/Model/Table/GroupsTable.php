<?php

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Description of GroupsTable
 *
 * @author Sitraka
 */
class GroupsTable extends Table {
    /**
     * Vérifie si le nom de groupe existe déja
     * @param String $name
     * @return Boolean Vrai si le nom existe
     */
    public function exists($name){
        $_query = $this->find();
        $_query->where(['name' => $name]);
        return $_query->count() > 0;
    }
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('user_groups');
        $this->setPrimaryKey('id_group');
    }
}
