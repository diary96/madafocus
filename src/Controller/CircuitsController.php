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
 * Description of CircuitsController
 *
 * @author RSMandimby
 */
class CircuitsController extends AppController{
    protected $pageInfos = [
        'title' => 'Circuits',
        'subtitle' => 'Manage your circuits here'
    ];
    
    protected $actionsPrivileges = [
        'index' => '11.1',
        'hotels' => "11.1",
        'specify' => '11.1',
        'roomTypeSelect2' => '11.1',
        'tourOperator' => '11.1',
        'datatable' => '11.1',
        'add' => '11.2',
        'serviceSelect2' => '11.1'
    ];

    /**
     * Directory table
     * @var \Cake\ORM\Table 
     */
    protected $circuits;
    
    public function hotels(){
        $this->jsonOnly();
        $params = $this->request->getData();
        $params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('view_hotels');
        $params['column'] = 'name';
        $response = $this->loadComponent('Select2', $params)->get();
        $this->setJSONResponse($response);
    }
    
    public function specify(){
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
        // $_post
        $_params = $this->request->getData();
        
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewServices');
        $_params['filters'] = ['id_place' => 101];
        foreach ([['data' => 'id_place'], ['data' => 'description'], ['data' => 'id_type']] as $_column) {
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function roomTypeSelect2(){
        $this->jsonOnly();
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $_params['column'] = 'option';
        $_params['filters'] = ['id_select' => HotelRoomsController::ROOM_TYPE_ID_SELECT];
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }
    
    public function serviceSelect2(){
        $this->jsonOnly();
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewService');
        $_params['column'] = 'type_name';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function tourOperator() {
        $this->jsonOnly();
        $_params['table'] = TableRegistry::getTableLocator()->get('TourOperators');
        $_params['column'] = 'name';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function index() {
        // // Remote validation
        // $this->set('rsto_circuits_fullname_validation_url', Router::url('/circuits/validate_fullname'));
        // // Directory
        $this->set('rsto_circuits_add_url', Router::url('/circuits/add'));
        // $this->set('rsto_circuits_edit_url', Router::url('/circuits/edit'));
        // $this->set('rsto_circuits_delete_url', Router::url('/circuits/delete'));
        // $this->set('_serialize', ['circuits']);
        $this->set('rsto_circuits_datatable_url', Router::url('/circuits/datatable'));
        $this->set('rsto_circuits_tour_operator_url', Router::url('/circuits/tour_operator'));
        // $this->set('rsto_circuits_title_select2_url', Router::url('/circuits/title_select2'));
        // Directory contact informations
        // $this->set('rsto_circuits_contact_information_datatable_url', Router::url('/circuits/contact_information_datatable'));
        // $this->set('rsto_circuits_contact_information_type_select2_url', Router::url('/circuits/contact_information_type_select2'));
        // $this->set('rsto_circuits_contact_information_add_url', Router::url('/circuits/add_contact_information'));
        // $this->set('rsto_circuits_contact_information_edit_url', Router::url('/circuits/update_contact_information'));
        // $this->set('rsto_circuits_contact_information_delete_url', Router::url('/circuits/delete_contact_information'));
    }

    public function initialize() {
        parent::initialize();
        // Init datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTripMere');
        // $this->datatableAdditionalColumns = [
        //     ['data' => 'id_title']
        // ];

        // Init table
        $this->circuits = TableRegistry::getTableLocator()->get('TripMere');
        $this->tripDet = TableRegistry::getTableLocator()->get('TripDet');
    }

    public function add() {
        $this->jsonOnly();
        $_entry = $this->circuits->newEntity($this->request->getData());
        $nb = $this->circuits->find()->count();
        $_entry->id_trips = 'TRP-'.$nb;
        $_entry->id_status = '1';
        $valn = $this->circuits->save($_entry);
        $date = date_format($_entry->start,"Y-m-d");
        for ($i=0; $i < intval($_entry->duration); $i++) { 
            $det = $this->tripDet->newEntity();
            $nbDet = $this->tripDet->find()->count();
            $det->id_trip = 'DET-'.$nbDet;
            $det->id_trips = $_entry->id_trips;
            $det->date = date('Y-m-d', strtotime($date. ' + '.$i.' days'));
            $det->day = $i+1;
            $this->tripDet->save($det);
        }
        if (is_object($_entry)) {
            $this->setJSONResponse([
                'success' => $valn !== null,
                'row' => $_entry
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }
}
