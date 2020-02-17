<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Manage hotels, rooms and prices according to it
 * 
 * @author RSMandimby
 */
final class HotelsController extends AppController {

    /**
     * Hotel's table
     * @var \Cake\ORM\Table 
     */
    protected $hotels;
    public $actionsPrivileges = [
        'add' => '5.2',
        'datatable' => '5.1',
        'delete' => '5.2',
        'index' => '5.1',
        'select2' => '5.2',
        'update' => '5.2',
        'validateName' => '5.2'
    ];

    public function add() {
        $this->jsonOnly();
        $_hotel = $this->hotels->newEntity($this->request->getData());
        $_hotel->have_restaurant = $this->request->getData('have_restaurant') === 'on' ? 1 : 0;
        $_hotel->do_transfer = $this->request->getData('do_transfer') === 'on' ? 1 : 0;
        $this->setJSONResponse([
            'success' => $this->hotels->save($_hotel) !== null,
            'row' => $_hotel
        ]);
    }
    
    public function delete(){
        $this->jsonOnly();
        $_id = $this->request->getData('id_hotel');
        $_hotel = $this->hotels->get($_id);
        if(!is_null($_hotel)){
            $this->setJSONResponse($this->hotels->delete($_hotel));
            return;
        }
        $this->raise404();
    }

    public function index() {
        $this->set('rsto_hotel_edit_url', Router::url('/hotels/update'));
        $this->set('rsto_hotel_delete_url', Router::url('/hotels/delete'));
        $this->set('rsto_hotel_datatable_url', Router::url('/hotels/datatable'));
        $this->set('rsto_hotel_add_url', Router::url('/hotels/add'));
        $this->set('rsto_hotel_place_select2_url', Router::url('/hotels/select2'));
        $this->set('rsto_hotel_name_validation_url', Router::url('/hotels/validate_name'));
        // place urls
        $this->set('rsto_places_add_url', Router::url('/places/add'));
        $this->set('rsto_place_parent_select2_url', Router::url('/places/select2'));
        $this->set('rsto_place_name_validation_url', Router::url('/places/validate_name'));
        // Hotel room urls
        $this->set('rsto_hotel_room_datatable_url', Router::url('/hotelrooms/datatable'));
        $this->set('rsto_hotel_room_type_select2_url', Router::url('/hotelrooms/type_select2'));
        $this->set('rsto_hotel_room_add_url', Router::url('/hotelrooms/add'));
        $this->set('rsto_hotel_room_edit_url', Router::url('/hotelrooms/update'));
        $this->set('rsto_hotel_room_deleted_url', Router::url('/hotelrooms/delete'));
        // Hotel room selling price urls
        $this->set('rsto_hotel_room_selling_price_datatable_url', Router::url('/hotelrooms/selling_price_datatable'));
        $this->set('rsto_hotel_room_selling_price_currency_select2_url', Router::url('/hotelrooms/selling_price_currency_select2'));
        $this->set('rsto_hotel_room_selling_add_url', Router::url('/hotelrooms/add_selling_price'));
        $this->set('rsto_hotel_room_selling_edit_url', Router::url('/hotelrooms/update_selling_price'));
        $this->set('rsto_hotel_room_selling_delete_url', Router::url('/hotelrooms/delete_selling_price'));
        // Tranfers urls
        $this->set('rsto_hotel_transfer_datatable_url', Router::url('/hoteltransfers/datatable'));
        $this->set('rsto_hotel_transfer_hub_select2_url', Router::url('/hoteltransfers/hub_select2'));
        $this->set('rsto_hotel_transfer_add_url', Router::url('/hoteltransfers/add'));
        $this->set('rsto_hotel_transfer_edit_url', Router::url('/hoteltransfers/update'));
        $this->set('rsto_hotel_transfer_delete_url', Router::url('/hoteltransfers/delete'));
        // Transfer selling prices
        $this->set('rsto_hotel_transfer_selling_price_datatable_url', Router::url('/hoteltransfers/selling_price_datatable'));
        $this->set('rsto_hotel_transfer_selling_price_currency_select2', Router::url('/hoteltransfers/selling_price_currency_select2'));
        $this->set('rsto_hotel_transfer_selling_price_add_url', Router::url('/hoteltransfers/add_selling_price'));
        $this->set('rsto_hotel_transfer_selling_price_edit_url', Router::url('/hoteltransfers/update_selling_price'));
        $this->set('rsto_hotel_transfer_selling_price_delete_url', Router::url('/hoteltransfers/delete_selling_price'));
    }

    public function initialize() {
        parent::initialize();
        // init tables
        $this->hotels = TableRegistry::getTableLocator()->get('Hotels');

        // datatable config
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewHotels');
        $this->datatableAdditionalColumns = [
            ['data' => 'have_restaurant'],
            ['data' => 'do_transfer'],
            ['data' => 'id_place']
        ];

        // Config hotel's place select2
        $this->select2Table = TableRegistry::getTableLocator()->get('ViewPlaces');
        $this->select2Column = 'name';
    }

    public function update() {
        $this->jsonOnly();
        $_id = intval($this->request->getQuery('id_hotel'));
        $_hotel = $this->hotels->get($_id);
        if (!is_null($_hotel)) {
            $_hotel->ame = $this->request->getData('name');
            $_hotel->place = $this->request->getData('place');
            $_hotel->reservation_email_address = $this->request->getData('reservation_email_address');
            $_hotel->reservation_phone_number = $this->request->getData('reservation_phone_number');
            $_hotel->have_restaurant = $this->request->getData('have_restaurant') === 'on' ? 1 : 0;
            $_hotel->do_transfer = $this->request->getData('do_transfer') === 'on' ? 1 : 0;
            $_hotel->name = $this->request->getData('name');
            $this->setJSONResponse([
                'success' => $this->hotels->save($_hotel) !== null,
                'row' => $_hotel
            ]);
            return;
        }
        $this->raise404();
    }

    public function validateName() {
        $this->jsonOnly();
        $_place = $this->request->getQuery('place');
        $_name = trim($this->request->getData('value', 'null'));
        $_response = false;
        // validation depend on the place 
        if ($_place === 'null') {
            $_response |= true && strlen($_name) > 0;
        } else {
            $_query = $this->datatableTable->find();
            $_query->where(['name' => $_name]);
            $_query->where(['id_place' => $_place]);
            $_response |= ($_query->count() === 0 && strlen($_name) > 0);
        }
        $this->setJSONResponse($_response);
    }

}
