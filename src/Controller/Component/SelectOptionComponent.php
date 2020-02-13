<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;

/**
 * Gère les select_options
 * @author RSMandimby
 */
class SelectOptionComponent extends Component {

    /**
     * Table select_options
     * @var \Cake\ORM\Table 
     */
    private $_table;

    /**
     * View view_select_options
     * @var \Cake\ORM\Table 
     */
    private $_view;

    /**
     * ID du select
     * @var int 
     */
    private $_select_id = false;

    /**
     * Enregistre un nouveau select_option
     * @param array $option
     * @return array
     */
    public function add($option) {
        $_option = $this->_table->newEntity($option);
        return $this->_table->save($_option);
    }
    
    public function delete($id){
        $_option = $this->_table->get($id);
        if(!is_null($_option)){
            return $this->_table->delete($_option);
        }
        return false;
    }

    /**
     * Determine si une option existe déjà pour une liste déroulante donnée
     * @param string | int $option : Soit id_option, dans ce cas $select doit être false. Soit le nom de l'option 
     * @param int | false $select : false si on recherche par id_option sinon id_select
     * @return boolean 
     */
    public function exists($option, $select = false) {
        $_queryExpression = new QueryExpression();
        if ($select !== false) {
            $_query = $this->_view->find()->where($_queryExpression->and_([
                        'option' => $option,
                        'id_select' => $select
            ]));
            return $_query->select()->count() > 0;
        } else {
            return $this->_table->get($option) !== null;
        }
    }

    /**
     * Récupère un select_option_group à partir de son id
     * @param int $id
     * @return \Cake\ORM\Entity|array|null 
     */
    public function get($id = false) {
        if ($id === false && $this->_select_id) {
            $_query = $this->_view->find();
            $_query->where(['id_select' => $this->_select_id]);
            return $_query->select();
        } else if ($id) {
            return $this->_table->get($id);
        }
        return null;
    }

    /**
     * Initialise le composant, cette méthode est appelée lorsque le composant est chargé par un controller
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->_table = TableRegistry::getTableLocator()->get('SelectOptions');
        $this->_view = TableRegistry::getTableLocator()->get('ViewSelectOptions');
    }

    /*
     * Défini le select parent
     * @var int $id id_select
     */
    public function setSelectId($id) {
        $this->_select_id = $id;
    }
    
    public function update($option){
        return $this->_table->save($option) !== null;
    }

}
