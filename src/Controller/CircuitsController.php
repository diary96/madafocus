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
    protected $trip_mere;
    protected $pageInfos = [
        'title' => 'Circuits',
        'subtitle' => 'Manage your circuits here'
    ];

    protected $actionsPrivileges = [
        'index' => '11.1',
        'hotels' => "11.1",
        'specify' => '11.1',
        'roomTypeSelect2' => '11.1',
        'datatable' => '11.1',
        'select2' => '11.1',
        'tourOperator' => '11.1',
        'update' => '11.2'
    ];

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

    public function tourOperator(){
        $this->jsonOnly();
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('TourOperators');
        $_params['column'] = 'name';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function index() {
        // // Remote validation
        // $this->set('rsto_circuits_fullname_validation_url', Router::url('/circuits/validate_fullname'));
        // // Directory
        // $this->set('rsto_circuits_add_url', Router::url('/circuits/add'));

        // $this->set('rsto_circuits_delete_url', Router::url('/circuits/delete'));
        // $this->set('_serialize', ['circuits']);
        $this->set('rsto_circuits_edit_url', Router::url('/circuits/update'));
        $this->set('rsto_circuits_select2_data_url', Router::url('/circuits/select2'));
        $this->set('rsto_circuits_datatable_url', Router::url('/circuits/datatable'));
        //$this->set('rsto_circuits_tour_operator_url', Router::url('/circuits/tour_operator'));
        // $this->set('rsto_circuits_title_select2_url', Router::url('/circuits/title_select2'));
        // Directory contact informations
        // $this->set('rsto_circuits_contact_information_datatable_url', Router::url('/circuits/contact_information_datatable'));
        // $this->set('rsto_circuits_contact_information_type_select2_url', Router::url('/circuits/contact_information_type_select2'));
        // $this->set('rsto_circuits_contact_information_add_url', Router::url('/circuits/add_contact_information'));
        // $this->set('rsto_circuits_contact_information_edit_url', Router::url('/circuits/update_contact_information'));
        // $this->set('rsto_circuits_contact_information_delete_url', Router::url('/circuits/delete_contact_information'));
    }
    public function update() {
        $this->jsonOnly();

        $_id = $this->request->getQuery('id');
        $_circuit = $this->trip_mere->get($_id);
        //echo $_circuit;
        if (is_object($_circuit)) {
            //$_circuit->id_status = $this->request->getData('ID_STATUS');
            $_circuit->start = $this->request->getData('start');
            $_circuit->duration = $this->request->getData('length');
            $_circuit->adults = $this->request->getData('adult-count');
            $_circuit->childrens = $this->request->getData('child-count');
            $_circuit->self_drive = $this->request->getData('driving-mode');
            $_circuit->tour_operator = $this->request->getData('type');

            $this->setJSONResponse([
                'success' => $this->trip_mere->save($_circuit) !== false,
                'row' => $_circuit
            ]);
            return;
        }
        $this->raise404(sprintf("L'id %s n'existe pas", $_id));
    }
    public function initialize() {
        parent::initialize();
        $this->select2Table = TableRegistry::getTableLocator()->get('TourOperators');
        $this->select2Column = "name";
        // Init datatable

        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTripMere');
        $this->trip_mere = TableRegistry::getTableLocator()->get('TripMere');
        $this->datatableAdditionalColumns = [
             ['data' => 'ADULTS'],
             ['data' => 'childrens'],
             ['data' => 'id_tour_operator'],
             ['data' => 'lib_tour_operator'],
             ['data' => 'self_drive']
        ];

        // Init table
        // $this->circuits = TableRegistry::getTableLocator()->get('Directories');
        // $this->contactInfos = TableRegistry::getTableLocator()->get('DirectoryContactInformations');
    }
}
