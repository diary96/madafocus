<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use Cake\Routing\Router;

/**
 * Description of ParksController
 *
 * @author RSMandimby
 */
class ParksController extends AppController{
    
    protected $actionsPrivileges = [
        'add' => '7.2',
        'addSellingEntranceFee' => '7.3',
        'currenciesSelect2' => '7.3',
        'datatable' => '7.1',
        'delete' => '7.2',
        'deleteSellingEntranceFee' => '7.3',
        'index' => '7.1',
        'select2' => '7.2',
        'sellingEntranceFeeDatatable' => '7.1',
        'update' => '7.2',
        'updateSellingEntranceFee' => '7.3',
        'validateName' => '7.2'
    ];
    
    /**
     * Parks table
     * @var \Cake\ORM\Table 
     */
    protected $parks;
    
    /**
     * Park selling prices table
     * @var \Cake\ORM\Table
     */
    protected $parkSellingEntranceFees;
    
    public function add(){
        $this->jsonOnly();
        $_park = $this->parks->newEntity($this->request->getData());
        $_park->adult_costing_entrance_fee = $this->RSTO->RemoveThousandSeparator($_park->adult_costing_entrance_fee);
        $_park->children_costing_entrance_fee = $this->RSTO->RemoveThousandSeparator($_park->children_costing_entrance_fee);
        if(is_object($_park)){
            return $this->setJSONResponse([
                'success' => $this->parks->save($_park) != null,
                'row' => $_park
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }
    
    public function addSellingEntranceFee(){
        $this->jsonOnly();
        $_selling_entrance_fee = $this->parkSellingEntranceFees->newEntity($this->request->getData());
        if(is_object($_selling_entrance_fee)){
            $this->setJSONResponse([
                'success' => $this->parkSellingEntranceFees->save($_selling_entrance_fee) !== null,
                'row' => $_selling_entrance_fee
            ]);
        } else {
            $this->raise404('Icomplete data!');
        }
    }
    
    public function currenciesSelect2(){
        $this->jsonOnly();
        $_id_park = $this->request->getQuery('id_park');
        $_exclude = $this->parkSellingEntranceFees->find()->where(['park' => $_id_park])->extract('currency')->toArray();
        $this->setJSONResponse($this->loadComponent('Select2', $this->request->getData())->getCurrencies($_exclude));
    }
    
    public function delete(){
        $this->jsonOnly();
        $_park = $this->parks->get($this->request->getData('id_park'));
        if(is_object($_park)){
            $this->setJSONResponse($this->parks->delete($_park));
        } else {
            $this->raise404(sprintf("ID %s doesn't exist", $this->request->getData('id_park')));
        }
    }
    
    public function deleteSellingEntranceFee(){
        $this->jsonOnly();
        $_price = $this->parkSellingEntranceFees->get($this->request->getData('id_park_selling_entrance_fee'));
        if(is_object($_price)){
            $this->setJSONResponse($this->parkSellingEntranceFees->delete($_price));
        } else {
            $this->raise404($this->raise404(sprintf("ID %s doesn't exist", $this->request->getQuery('id_park_selling_entrance_fee'))));
        }
    }
    
    public function index(){
        $this->set('rsto_park_delete_url', Router::url('/parks/delete'));
        $this->set('rsto_park_datatable_url', Router::url('/parks/datatable'));
        $this->set('rsto_park_add_url', Router::url('/parks/add'));
        $this->set('rsto_park_edit_url', Router::url('/parks/update'));
        $this->set('rsto_park_name_validation_url', Router::url('/parks/validate_name'));
        $this->set('rsto_park_place_select2_url', Router::url('/parks/select2'));
        // Selling price
        $this->set('rsto_park_selling_entrance_fee_datatable_url', Router::url('/parks/selling_entrance_fee_datatable'));
        $this->set('rsto_park_selling_entrance_fee_add_url', Router::url('/parks/add_selling_entrance_fee'));
        $this->set('rsto_park_selling_entrance_fee_edit_url', Router::url('/parks/update_selling_entrance_fee'));
        $this->set('rsto_park_selling_entrance_fee_delete_url', Router::url('/parks/delete_selling_entrance_fee'));
        $this->set('rsto_park_selling_entrance_fee_select2_url', Router::url('/parks/currencies_select2'));
        // Places URL
        $this->set('rsto_places_add_url', Router::url('/places/add'));
        $this->set('rsto_place_parent_select2_url', Router::url('/places/select2'));
        $this->set('rsto_place_name_validation_url', Router::url('/places/validate_name'));
    }
    
    public function initialize() {
        parent::initialize();
        
        // Initialize datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewParks');
        $this->datatableAdditionalColumns = [
            ['data' => 'id_place']
        ];
        $this->datatableCallback = function(&$parks){
            foreach($parks as $_park){
                $_park['adult_costing_entrance_fee'] = $this->RSTO->FormatNumber($_park['adult_costing_entrance_fee']);
                $_park['children_costing_entrance_fee'] = $this->RSTO->FormatNumber($_park['children_costing_entrance_fee']);
            }
            return $parks;
        };
        
        // Initialize select2
        $this->select2Table = TableRegistry::getTableLocator()->get('ViewPlaces');
        $this->select2Column = 'name';
        
        // Initialize parks table
        $this->parks = TableRegistry::getTableLocator()->get('Parks');
        $this->parkSellingEntranceFees = TableRegistry::getTableLocator()->get('ParkSellingEntranceFees');
    }
    
    public function sellingEntranceFeeDatatable(){
        $this->jsonOnly();
        $_columnModel = [
            'name' => '',
            'data' => '',
            'searchable' => false,
            'search' => [
                'value' => '',
                'regex' => ''
            ]
        ];
        
        $_params = $this->request->getData();
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewParkSellingPrices');
        foreach([['data' => 'id_currency']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$_prices){
            foreach($_prices as $_price){
                $_price->adult = $this->RSTO->FormatNumber($_price->adult);
                $_price->children = $this->RSTO->FormatNumber($_price->children);
            }
            return $_prices;
        };
        $_params['filters'] = ['id_park' => $this->request->getQuery('id_park')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function update(){
        $this->jsonOnly();
        $_park = $this->parks->get($this->request->getQuery('id_park'));
        if(is_object($_park)){
            $_park->name = $this->request->getData('name');
            $_park->place = $this->request->getData('place');
            $_park->adult_costing_entrance_fee = $this->RSTO->RemoveThousandSeparator($this->request->getData('adult_costing_entrance_fee'));
            $_park->children_costing_entrance_fee = $this->RSTO->RemoveThousandSeparator($this->request->getData('children_costing_entrance_fee'));
            $this->setJSONResponse([
                'success' => $this->parks->save($_park) !== null,
                'row' => $_park
            ]);
        } else {
            $this->raise404(sprintf("ID %s doesn't exist", $this->request->getQuery('id_park')));
        }
    }
    
    public function updateSellingEntranceFee(){
        $this->jsonOnly();
        $_price = $this->parkSellingEntranceFees->get($this->request->getQuery('id_park_selling_entrance_fee'));
        if(is_object($_price)){
            $_price->currency = $this->request->getData('currency');
            $_price->adult_entrance_fee = $this->RSTO->RemoveThousandSeparator($this->request->getData('adult_entrance_fee'));
            $_price->children_entrance_fee = $this->RSTO->RemoveThousandSeparator($this->request->getData('children_entrance_fee'));
            $this->setJSONResponse([
                'success' => $this->parkSellingEntranceFees->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404($this->raise404(sprintf("ID %s doesn't exist", $this->request->getQuery('id_park_selling_entrance_fee'))));
        }
    }
    
    public function validateName(){
        $this->jsonOnly();
        $_name = trim($this->request->getData('value'));
        $_id_place = $this->request->getQuery('id_place', 'null');
        $_query = $this->parks->find();
        $_query->where(['name' => $_name]);
        if($_id_place !== 'null'){
            $_query->where(['place' => $_id_place]);
        }   
        $this->setJSONResponse($_query->count() === 0 && strlen($_name) > 0);
    }
}
