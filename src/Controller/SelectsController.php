<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Description of SelectsController
 *
 * @author macbookpro
 */
final class SelectsController extends AppController{
    protected $pageInfos = [
        'title' => 'Listes déroulantes',
        'subtitle' => 'Gérer vos liste déroulantes ici'
    ];
    
    protected $actionsPrivileges = [
        'addOption' => '3.2',
        'addGroup' => '3.2',
        'datatable' => '3.1',
        'deleteOption' => '3.2',
        'get' => '3.2',
        'index' => '3.1',
        'select2' => '3.1',
        'update' => '3.2',
        'updateGroup' => '3.2',
        'updateOption' => '3.2',
        'validateGroupName' => '3.2',
        'validateOption' => '3.2'
    ];
    
    /**
     * Gestionnaire des selects
     * @var Component\SelectComponent 
     */
    private $select;
    
    public function addOption(){
        $this->jsonOnly();
        $_data = $this->request->getData();
        $_data['default'] = array_key_exists('default', $_data) ? 1 : 0;
        $_row = $this->select->addOption($_data);
        $this->setJSONResponse([
            'success' => !is_null($_row),
            'row' => !is_null($_row) ? $_row : $_data
        ]);
        
    }
    
    public function addGroup(){
        $this->jsonOnly();
        $_data = $this->request->getData();
        $_row = $this->select->addGroup($_data);
        $this->setJSONResponse([
            'success' => !is_null($_row),
            'row' => !is_null($_row) ? $_row : $_data
        ]);
    }
    
    public function deleteOption(){
        $this->jsonOnly();
        $_id = intval($this->request->getData('id_option'));
        $this->setJSONResponse($this->select->deleteOption($_id));
    }
    
    public function index(){
        // étblissement de la liste des listes déroulantes
        $this->set('rstoSelects', $this->select->get());
        $this->set('rsto_select_datatable_url', Router::url('/selects/datatable'));
        $this->set('rsto_select_group_select2_url', Router::url('/selects/select2'));
        $this->set('rsto_select_option_validation_url', Router::url('/selects/validate_option'));
        $this->set('rsto_select_add_url', Router::url('/selects/add_option'));
        $this->set('rsto_select_edit_url', Router::url('/selects/update_option'));
        $this->set('rsto_select_delete_url', Router::url('/selects/delete_option'));
        $this->set('rsto_select_option_group_name_validation_url', Router::url('/selects/validate_group_name'));
        $this->set('rsto_select_option_group_add_url', Router::url('/selects/add_group'));
    }
    
    public function initialize() {
        parent::initialize();
        $this->select = $this->loadComponent('Select');
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $this->datatableFilters = ['id_select' =>'id_select'];
        $this->datatableAdditionalColumns = [
            ['data' => 'id_select_option_group'],
            ['data' => 'default']
        ];
        $this->select2Table = TableRegistry::getTableLocator()->get('SelectOptionGroups');
        $this->select2Column = 'name';
        $this->select2Filters = ['id_select' => 'select'];
    }
    
    public function updateOption(){
        $this->jsonOnly();
        $_id = $this->request->getQuery('id_option');
        $_data = $this->request->getData();
        $_response = false;
        $_option = $this->select->getOption($_id);
        if(!is_null($_option)){
            $_option->group = intval($_data['group']);
            $_option->option = $_data['option'];
            $_response = $this->select->updateOption($_option);
        }
        $this->setJSONResponse([
            'success' => $_response,
            'row' => $_option
        ]);
    }
    
    public function validateGroupName(){
        //$this->jsonOnly();
        $_response = false;
        $_groupName = trim($this->request->getData('value'));
        $_select = $this->request->getQuery('id_select');
        if(strlen($_groupName) > 0 && !is_null($_select) ){
            $_response |= !$this->select->groupExists($_groupName, $_select);
        }
        $this->setJSONResponse((boolean)$_response);
    }
    
    public function validateOption(){
        $this->jsonOnly();
        $_response = false;
        $_option = trim($this->request->getData('value'));
        $_select = $this->request->getQuery('id_select');
        if(strlen($_option) > 0 && !is_null($_select) ){
            $_response |= !$this->select->optionExists($_option, $_select);
        }
        $this->setJSONResponse((boolean)$_response);
    }
    
}
