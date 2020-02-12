<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;

final class Select2Controller extends AppController{
    protected $actionsPrivileges = [
        
    ];
    
    private $selects = [
        'currencies' => 1,
        'room-types' => 2,
        'meal-plans' => 3,
        'contact-types' => 4,
        'service-types' => 5,
        'vehicle-brands' => 6,
        'vehicle-types' => 7,
        'hub-types' => 8
    ];
    
    /**
     * Select options table
     * @var \Cake\ORM\Table
     */
    private $selectOptions;
    
    public function currencies(){
        
    }
    
    public function select2($select){
        if(array_key_exists($select, $this->selects)){
            $this->jsonOnly();
            $_exclude = explode(';', $this->request->getQuery('exclude'));
            
            $_params['table'] = TableRegistry::getTableLocator()->get('ViewSelectOptions');
            $_params['column'] = 'option';
            $_params['filters'] = [['id_select' => $id_select]];
            $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
        } else {
            $this->raise404();
        }
    }

    public function initialize() {
        parent::initialize();
        $this->selectOptions = TableRegistry::getTableLocator()->get('ViewSelectOptions');
    }
}
