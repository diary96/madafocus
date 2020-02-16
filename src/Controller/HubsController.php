<?php

namespace App\Controller;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

/**
 * Description of HubsController
 *
 * @author RSMandimby
 */
class HubsController extends AppController {

    protected $actionsPrivileges = [
        'add' => '6.2',
        'delete' => '6.2',
        'datatable' => '6.1',
        'index' => '6.1',
        'select2' => '6.2',
        'typeSelect2' => '6.2',
        'update' => '6.2',
        'validateName' => '6.2'
    ];
    
    /**
     * Hub table
     * @var \Cake\ORM\Table 
     */
    protected $hubs;
    
    public function add(){
        $this->jsonOnly();
        $_hub = $this->hubs->newEntity($this->request->getData());
        if(is_object($_hub)){
            $this->setJSONResponse([
                'success' => is_object($this->hubs->save($_hub)),
                'row' => $_hub
            ]);
        } else {
            $this->raise404('Incomplete data');
        }
    }
    
    public function delete(){
        $this->jsonOnly();
        $_hub = $this->hubs->get($this->request->getData('id_hub'));
        if(is_object($_hub)){
            $this->setJSONResponse($this->hubs->delete($_hub));
        } else {
            $this->raise404(sprintf("ID %s doesn't exists!"));
        }
    }
    
    public function index(){
        $this->set('rsto_hub_add_url', Router::url('/hubs/add'));;
        $this->set('rsto_hub_edit_url', Router::url('/hubs/update'));
        $this->set('rsto_hub_delete_url', Router::url('/hubs/delete'));
        $this->set('rsto_hub_datatable_url', Router::url('/hubs/datatable'));
        $this->set('rsto_hub_type_select2_url', Router::url('/hubs/type_select2'));
        $this->set('rsto_hub_place_select2_url', Router::url('/hubs/select2'));
        $this->set('rsto_hub_name_validation_url', Router::url('/hubs/validate_name'));
        // Places URL
        $this->set('rsto_places_add_url', Router::url('/places/add'));
        $this->set('rsto_place_parent_select2_url', Router::url('/places/select2'));
        $this->set('rsto_place_name_validation_url', Router::url('/places/validate_name'));
    }

    public function initialize() {
        parent::initialize();
        
        // Initialize datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewHubs');
        $this->datatableAdditionalColumns = [
            ['data' => 'id_place'],
            ['data' => 'id_type']
        ];
        
        // Initialize select2
        $this->select2Table = TableRegistry::getTableLocator()->get('ViewPlaces');
        $this->select2Column = 'name';
        
        // Initialize hub table
        $this->hubs = TableRegistry::getTableLocator()->get('Hubs');
    }
    
    public function typeSelect2(){
        $this->jsonOnly();
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $_params['column'] = 'option';
        $_params['filters'] = [['id_select' => 8]];
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }
    
    public function update(){
        $this->jsonOnly();
        $_hub = $this->hubs->get($this->request->getQuery('id_hub'));
        $_hub->name = $this->request->getData('name');
        $_hub->place = $this->request->getData('place');
        $_hub->type = $this->request->getData('type');
        if(is_object($_hub)){
            $this->setJSONResponse([
                'success' => $this->hubs->save($_hub) !== null,
                'row' => $_hub
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }
    
    public function validateName(){
        $this->jsonOnly();
        $_id_place = $this->request->getQuery('id_place', 'null');
        if($_id_place === 'null'){
            $this->setJSONResponse(false);
            return;
        }
        $_name = trim($this->request->getData('value'));
        $_query = $this->datatableTable->find();
        $_query->where(['name' => $_name]);
        $_query->where(['id_place' => $_id_place]);
        $this->setJSONResponse($_query->count() === 0 && strlen($_name) > 0);
    }
    

}
