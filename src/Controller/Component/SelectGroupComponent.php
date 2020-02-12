<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

/**
 * Gestionnaire de select_option_group
 * @author RSMandimby
 */
final class SelectGroupComponent extends Component{
    /**
     * Table select_groups
     * @var \Cake\ORM\Table 
     */
    private $_table;
    
    /**
     * Enregistre un nouveau select_group
     * @param array $group
     * @return array
     */
    public function add($group){
        $_group = $this->_table->newEntity($group);
        return $this->_table->save($_group);
    }
    
    public function exists($name, $id_select){
        $_queryExpression = new \Cake\Database\Expression\QueryExpression();
        $_query = $this->_table->find();
        $_query->where($_queryExpression->and_([
            'select' => $id_select,
            'name' => $name
        ]));
        return $_query->select()->count() > 0;
    }
    
    /**
     * Récupère un select_option_group à partir de son id
     * @param int $id
     */
    public function get($id){
        return $this->_table->get($id);
    }
    
    /**
     * Mets à jour un select_group
     * @param array $group
     * @return array
     */
    public function update($group){
        $_group = $this->_table->get($group['id']);
        if(!is_null($_group)){
            $_group->name = $group['name'];
            return $this->_table->save($_group);
        }
        return false;
    }
    
    /**
     * Initialise le composant, cette méthode est appelée lorsque le composant est chargé par un controller
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);
        TableRegistry::getTableLocator()->setConfig('SelectGroups', ['table' => 'select_option_groups']);
        $this->_table = TableRegistry::getTableLocator()->get('SelectGroups');
    }
}
