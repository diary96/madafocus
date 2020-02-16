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
    protected $view_room_trip_dep_hotel;
    protected $rooms;
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
        'quote' => '11.1',
        'updatedaily' => '11.1',
        'roomHotel' => '11.1',
        'editCircuit' => '11.1',
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
        $_params = $this->request->getData();
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewPlaces');
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
        $id_hotel = $this->request->getQuery('hotel', '0');
        $_params['table'] = \Cake\ORM\TableRegistry::getTableLocator()->get('ViewHotelRooms');
        $_params['column'] = 'type_name';
        $_params['filters'] = ['id_hotel' =>$id_hotel];
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

        $this->set('rsto_circuit_quote_url', Router::url('/circuits/quote'));
        $this->set('rsto_circuit_room_add_url', Router::url('/circuits/room_type_select2'));
        $this->set('rsto_circuit_room_hotel_datatable_url', Router::url('/circuits/room_hotel'));
        $this->set('rsto_circuit_edit_room_hotel', Router::url('/circuits/edit_circuit'));
        $this->set('rsto_circuit_always_use_url', Router::url('/circuitdaily/alwaysdrive'));
    }

    public function quote() {
        $this->jsonOnly();

        // $this->trip_mere = TableRegistry::getTableLocator()->get('TripMere');
        $_id = $this->request->getData('id');
        $trip = $this->trip_mere->find()->where(['id_trips' => $_id])->first();

        $trip_det = TableRegistry::getTableLocator()->get('ViewTripDet');
        // $trip_det = TableRegistry::getTableLocator()->get('TripDet');
        $tripDetData = $trip_det->find()->where(['id_trips' => $_id])->order(['day' => 'ASC'])->toArray();

        $car_price = TableRegistry::getTableLocator()->get('view_carrier_vehicle_selling_prices');
        $_provider_price = TableRegistry::getTableLocator()->get('view_provider_selling_prices');
        $_service_price = TableRegistry::getTableLocator()->get('view_service_selling_prices');
        $_rooms = TableRegistry::getTableLocator()->get('view_room');
        $_services = TableRegistry::getTableLocator()->get('view_services');
        $_specify = TableRegistry::getTableLocator()->get('specify');

        $trip->total = 0;
        foreach ($tripDetData as $det) {
            if($det != null){
                $carPrice = $car_price->find()->where(['id_carrier_vehicle' => $det->id_carrier_vehicle, 'id_currency' => $trip->currency])->first();
                $trip->currency_name = $carPrice['currency_name'];
                $listRoomPrice = $_rooms->find()->where(['id_trip' => $det->id, 'id_currency' => $trip->currency])->toArray();
                // $listRoomPrice = $_rooms->find()->where(['id_trip' => $det->id_trip])->toArray();
                $price = 0;
                foreach ($listRoomPrice as $room) {
                    // BO: 12, BB: 13, HB: 14, FB: 15, DU: 16
                    if($det->id_select_option == 12) $price += $room->bo * $room->nb_room;
                    else if($det->id_select_option == 13) $price += $room->bb * $room->nb_room;
                    else if($det->id_select_option == 14) $price += $room->hb * $room->nb_room;
                    else if($det->id_select_option == 15) $price += $room->fb * $room->nb_room;
                    else if($det->id_select_option == 16) $price += $room->du * $room->nb_room;
                }

                $det->description = array();
                // array_push($det->description, $listRoomPrice[0]->hotel_name, $carPrice->type_name);
                $det->description[] = $listRoomPrice[0]->hotel_name;
                if($carPrice != null) $det->description[] = $carPrice['type_name'];

                $det->prix = array();
                $det->prix[] = $price;
                if($carPrice != null) $det->prix[] = $carPrice->full_price;

                $listeSpecify = $_specify->find()->where(['id_trip' => $det->id])->toArray();
                // $listeSpecify = $_specify->find()->where(['id_trip' => $det->id_trip])->toArray();
                foreach ($listeSpecify as $specify) {
                    $service = $_services->find()->where(['id' => $specify->ID_SPECIFY])->first();
                    $price = 0;
                    if($service->from_provider == 1){
                        $provider = $_provider_price->find()->where(['service' => $service->id, 'id_currency' => $trip->currency])->first();
                        $price = ($specify->ADULT * $provider['adult']) + ($specify->CHILDREN * $provider['children']);
                    }
                    else{
                        $servicePrice = $_service_price->find()->where(['id_service' => $service->id, 'id_currency' => $trip->currency])->first();
                        $price = ($specify->ADULT + $specify->CHILDREN) * $servicePrice['price'];
                    }
                    $det->description[] = $service['type_name'];
                    $det->prix[] = $price;
                }
                foreach ($det->prix as $price) {
                    $trip->total += $price;
                }
            }
        }
        // $this->set('trip', $trip);
        $this->setJSONResponse([
            'success' => true,
            'row' => [
                'trip' => $trip,
                'tripDet' => $tripDetData
            ]
        ]);
        // $this->raise404(sprintf("L'id %s n'existe pas", $_id));
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
            $_circuit->num_vol = $this->request->getData('num_vol');
            $_circuit->arriving_time = $this->request->getData('arriving_time');

            $nb = $this->trip_det->find()->where(['id_trips' => $_id])->count();
            $nbAll = $this->trip_det->find()->count();
            if($nb < $_circuit->duration){
                $last = $this->trip_det->find()->where(['id_trips' => $_id, 'day' => $nb])->first();
                $split = explode("/", $last->date); 
                $date = $split[2].'-'.$split[1].'-'.$split[0];
                for ($i=0; $i < $_circuit->duration - $nb; $i++) { 
                    $new = $this->trip_det->newEntity();
                    $new->id_trips = $last->id_trips;
                    $new->id_trip = ($nbAll+1+$i).'';
                    $new->day = $nb+1+$i;
                    $new->date = date('Y-m-d', strtotime($date. ' + '.($i+1).' days'));
                    $this->trip_det->save($new);
                }
            }
            else if($nb > $_circuit->duration){
                $list = $this->trip_det->find()->where(['id_trips' => $_id])->order(['day' => 'DESC'])->toArray();
                for ($i=0; $i < $nb - $_circuit->duration; $i++) { 
                    // $this->trip_det->query()->delete()->where(['id' => $_id, 'day' => $list[$i]->day])->execute();
                    $this->trip_det->delete($list[$i]);
                }
            }
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
    public function editCircuit() {
        $this-> jsonOnly();
        $data = $this->request->input('json_decode', 'true');
        echo $data[0]['id'];
        // foreach (data as ))
        $this->setJSONResponse([
            'success'=> true,
            'row'=>  $data
        ]);
        return;

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
        $this->view_room_trip_dep_hotel = TableRegistry::getTableLocator()->get('ViewRoomTripDepHotel');
        $this->rooms = TableRegistry::getTableLocator()->get('Room');

        $this->datatableAdditionalColumns = [
             ['data' => 'ADULTS'],
             ['data' => 'childrens'],
             ['data' => 'id_tour_operator'],
             ['data' => 'lib_tour_operator'],
             ['data' => 'ID_STATUS'],
             ['data' => 'num_vol'],
             ['data' => 'arriving_time'],
             ['data' => 'self_drive']
        ];
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
        $to = $this->select2Table->find()->where(['id_tour_operator' => $_entry->tour_operator])->first();
        $_entry->id_trips = $to->name.'-'.$nb;
        $_entry->id_status = '1';
        $valn = $this->circuits->save($_entry);
        $date = date_format($_entry->start,"Y-m-d");
        for ($i=0; $i < intval($_entry->duration); $i++) {
            $det = $this->tripDet->newEntity();
            $nbDet = $this->tripDet->find()->count()+1;
            $det->id_trip = $nbDet+1;
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

    public function roomHotel() {
        $this->jsonOnly();
        $_id_trip = $this->request->getQuery('id_trip', 'null');
        if($_id_trip === 'null'){
            $this->setJSONResponse(false);
            return;
        }
        $_query = $this->view_room_trip_dep_hotel->find()->where(["id_trip" => $_id_trip]);
        $this->setJSONResponse($_query->all() );

    }
}
