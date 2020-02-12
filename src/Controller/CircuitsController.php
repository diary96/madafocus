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
        'datatable' => '11.1',
        'hotels' => "11.1",
        'specify' => '11.1',
        'roomTypeSelect2' => '11.1',
        'serviceSelect2' => '11.1'
    ];

    public function index(){
        $this->set('rsto_circuits_datatable_url', Router::url('/circuits/datatable'));
    }

    public function hotels(){
        $this->jsonOnly();
        $params = $this->request->getData();
        $params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('view_hotels');
        $params['column'] = 'name';
        $response = $this->loadComponent('Select2', $params)->get();
        $this->setJSONResponse($response);
    }

    public function specify(){

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
    public function initialize() {
        parent::initialize();

        // Init datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTripMere');
    }
}
