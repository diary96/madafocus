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

    const ID_TYPE_VEHICLE = 7;
    const MEAL_TYPE_ID_SELECT = 3;

    protected $trip_mere;
    protected $hotels;
    protected $trip_det;
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
        'select2' => '11.1',
        'datatable' => '11.1',
        'serviceSelect2' => '11.1',
        'add' => '11.2',
        'update' => '11.2',
        'validate' => '11.2',
        'places' => '11.1',
        'carrier' => '11.1',
        'vehicletype' => '11.1',
        'hotel' => '11.1',
        'meal' => '11.1',
        'updatedaily' => '11.1'
    ];

    /**
     * Directory table
     */
    protected $circuits;

    public function hotels(){
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

    public function trip_child() {

    }
    public function places() {
        $this->jsonOnly();
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('Places');
        $_params['column'] = 'name';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function carrier(){
        $this->jsonOnly();
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewCarriers');
        $_params['column'] = 'title_name';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }


    public function vehicletype(){
        $this->jsonOnly();
        $id_carrier = $this->request->getQuery('carrier', 'null');
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('carrier_vehicles');
        $_params['column'] = 'vehicle_registration';
        $_params['filters'] = ['carrier'=> $id_carrier];
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function hotel(){
        $this->jsonOnly();
        $id_place = $this->request->getQuery('place', 'null');
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('Hotels');
        $_params['column'] = 'name';
        $_params['filters'] = ['place' => $id_place];
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function meal(){
        $this->jsonOnly();
        $id_hotel = $this->request->getQuery('hotel', '0');
        if ($id_hotel != '0') {
            $hotel = $this->hotels->get($id_hotel);
            $restaurant = null;
            if (is_object($hotel)) {
                $restaurant = $hotel->have_restaurant;
                if ($restaurant == '0') {
                    $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewSelectOptionsWithoutRestaurant');
                } else {
                    $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewSelectOptions');
                }
                $_params['column'] = 'option';
                $_params['filters'] = ['id_select' => HotelRoomsController::MEAL_TYPE_ID_SELECT ];

                $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
            }
        } else {

            $this->raise404(sprintf("Veuillez selectionner un hotel "));
        }

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
        $this->set('rsto_circuits_add_url', Router::url('/circuits/add'));
        $this->set('rsto_circuits_places_select', Router::url('/circuits/places'));
        $this->set('rsto_circuits_hotel_select', Router::url('/circuits/hotel'));
        $this->set('rsto_circuits_carrier_select', Router::url('/circuits/carrier'));
        $this->set('rsto_circuits_meal_select', Router::url('/circuits/meal'));
        $this->set('rsto_circuits_vehicle_type_select', Router::url('/circuits/vehicletype'));

        $this->set('rsto_circuits_datatable_url', Router::url('/circuits/datatable'));
        $this->set('rsto_circuits_tour_operator_url', Router::url('/circuits/tour_operator'));
        $this->set('rsto_circuit_daily_datable_url', Router::url('/circuitdaily/datatable'));

        $this->set('rsto_circuits_edit_url', Router::url('/circuits/update'));
        $this->set('rsto_circuits_validate_url', Router::url('/circuits/validate'));
        $this->set('rsto_circuits_select2_data_url', Router::url('/circuits/select2'));
        $this->set('rsto_circuit_trip_det_edit_url', Router::url('/circuits/updatedaily'));

    }

    public function update() {
        $this->jsonOnly();

        $_id = $this->request->getQuery('id');
        $_circuit = $this->trip_mere->get($_id);
        //echo $_circuit;
        if (is_object($_circuit)) {
            //$_circuit->id_status = $this->request->getData('ID_STATUS');
            $_circuit->start = $this->request->getData('start');
            $_circuit->duration = $this->request->getData('duration');
            $_circuit->adults = $this->request->getData('adults');
            $_circuit->childrens = $this->request->getData('childrens');
            $_circuit->self_drive = $this->request->getData('self_drive');
            $_circuit->tour_operator = $this->request->getData('tour_operator');

            $this->setJSONResponse([
                'success' => $this->trip_mere->save($_circuit) !== false,
                'row' => $_circuit
            ]);
            return;
        }
        $this->raise404(sprintf("L'id %s n'existe pas", $_id));
    }
    public function updatedaily() {
        $this-> jsonOnly();
        $_id = $this->request->getQuery('id');
        $_trip_det = $this->trip_det->get($_id);
        if ( is_object($_trip_det)) {
            $_trip_det->id_places = $this->request->getData('id_places');
            $_trip_det->carrier= $this->request->getData('carrier');
            $_trip_det->type_vehicule= $this->request->getData('type_vehicule');
            $_trip_det->hotel= $this->request->getData('hotel');
            $_trip_det->id_carrier_vehicle= $this->request->getData('id_carrier_vehicle');

            $this->setJSONResponse([
               'success'=> $this->trip_det->save($_trip_det)  !== false,
                'row'=>$_trip_det
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
        $this->circuits = TableRegistry::getTableLocator()->get('TripMere');
        $this->tripDet = TableRegistry::getTableLocator()->get('TripDet');

        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTripMere');
        $this->trip_mere = TableRegistry::getTableLocator()->get('TripMere');
        $this->hotels = TableRegistry::getTableLocator()->get('Hotels');
        $this->trip_det = TableRegistry::getTableLocator()->get('TripDet');
        $this->datatableAdditionalColumns = [
             ['data' => 'ADULTS'],
             ['data' => 'childrens'],
             ['data' => 'id_tour_operator'],
             ['data' => 'lib_tour_operator'],
             ['data' => 'ID_STATUS'],
             ['data' => 'self_drive']
        ];

        // Init table
        // $this->circuits = TableRegistry::getTableLocator()->get('Directories');
        // $this->contactInfos = TableRegistry::getTableLocator()->get('DirectoryContactInformations');
    }

    public function validate(){
        $this->jsonOnly();
        $_entry = $this->trip_mere->get($this->request->getData('id'));
        $_entry->id_status = 2;
        if (is_object($_entry)) {
            $this->setJSONResponse($this->trip_mere->save($_entry));
        } else {
            $this->raise404('Incomplete data!');
        }
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
