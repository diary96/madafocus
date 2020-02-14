<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

/**
 * Description of PlacesController
 * 
 * @author RSMandimby
 */
final class PlacesController extends AppController{
    
    protected $places;
    
    protected $actionsPrivileges = [
        'add' => "4.2",
        'datatable' => "4.1",
        'delete' => "4.2",
        'index' => "4.1",
        'select2' => "4.1",
        'update' => "4.2",
        'validateName' => "4.2"
    ];
    
    public function add(){
        $this->jsonOnly();
        $_place = $this->places->newEntity($this->request->getData());
        $this->setJSONResponse([
            'success' => $this->places->save($_place) !== null,
            'row' => $_place
        ]);
        
    }
    
    public function delete(){
        $this->jsonOnly();
        $_id = $this->request->getData('id_place');
        $_place = $this->places->get($_id);
        if($_place !== null){
            $this->setJSONResponse($this->places->delete($_place));
            return;
        }
        $this->raise404(sprintf("Place with id_place = %s doesn't exist", $_id));
    }
    
    public function index(){
        $this->set('rsto_places_add_url', Router::url('/places/add'));
        $this->set('rsto_places_edit_url', Router::url('/places/update'));
        $this->set('rsto_places_delete_url', Router::url('/places/delete'));
        $this->set('rsto_places_datatable_url', Router::url('/places/datatable'));
        $this->set('rsto_place_parent_select2_url', Router::url('/places/select2'));
        $this->set('rsto_place_name_validation_url', Router::url('/places/validate_name'));
    }
    
    public function initialize() {
        parent::initialize();
        $this->pageInfos = [
            'title' => __('Places'),
            'subtitle' => __('Manage the places here'),
        ];
        
        // Configuration de la datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewPlaces');
        $this->datatableFilters = ['id_parent' => 'id_parent'];
        $this->datatableAdditionalColumns = [['data' => 'id_parent']];
        
        // Configuration du select 2
        $this->select2Column = 'name';
        $this->select2Table = TableRegistry::getTableLocator()->get('ViewZones');
        $_exclude = $this->request->getQuery('exclude');
        if(!is_null($_exclude)){
            $this->select2Filters = ['exclude' => 'id !='];
        }
        
        // Chargement de la table places
        $this->places = TableRegistry::getTableLocator()->get('Places');
        
        $this->set('rsto_zones', $this->select2Table->query()->select());
    }
    
    public function update(){
        $this->jsonOnly();
        $_place = $this->places->get($this->request->getQuery('id_place'));
        if($_place){
            $_place->parent = $this->request->getData('parent');
            $_place->name = $this->request->getData('name');
            $this->setJSONResponse([
                'success' => $this->places->save($_place) !== null,
                'row' => $_place
            ]);
            return;
        }
        $this->raise404(sprintf("Place with id_place = %s doesn't exist", $this->request->getQuery('id_place')));
    }
    
    public function validateName(){
        $this->jsonOnly();
        $_name = trim($this->request->getData('value'));
        $_parent = $this->request->getQuery('id_parent');
        $_response = false;
        if(strlen($_name) > 0){
            $_query = $this->datatableTable->find()->where(['name' => $_name]);
            $_query->where($_parent !== null && $_parent !== 'null' ? ['id_parent' => $_parent] : function(QueryExpression $exp, Query $q){
                return $exp->isNull('id_parent');
            });
            $_response |= $_query->count() === 0;
        }
        $this->setJSONResponse($_response);
    }
    
}
