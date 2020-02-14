<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;

/**
 * Description of HotelRoomsController
 *
 * @author RSMandimby
 */
class HotelRoomsController extends AppController {

    const ROOM_TYPE_ID_SELECT = 2;
    const MEAL_TYPE_ID_SELECT = 3;
    const CURRENCY_ID_SELECT = 1;

    /**
     * Hotel room's table
     * @var \Cake\ORM\Table
     */
    protected $hotel_rooms;

    /**
     * Hotel room selling price's table
     * @var \Cake\ORM\Table
     */
    protected $selling_prices;
    public $actionsPrivileges = [
        'add' => '5.2',
        'addSellingPrice' => '5.3',
        'datatable' => '5.1',
        'delete' => '5.2',
        'deleteSellingPrice' => '5.3',
        'select2' => '5.2',
        'sellingPriceDatatable' => '5.1',
        'sellingPriceCurrencySelect2' => '5.2',
        'typeSelect2' => '5.2',
        'update' => '5.2',
        'updateSellingPrice' => '5.3',
    ];

    public function add() {
        $this->jsonOnly();
        $_data = $this->request->getData();
        $_data['capacity'] = str_replace(',', '', $_data['capacity']);
        $_data['bo_cost_price'] = str_replace(',', '', $_data['bo_cost_price']);
        $_data['du_cost_price'] = str_replace(',', '', $_data['du_cost_price']);
        $_data['hb_cost_price'] = array_key_exists('hb_cost_price', $_data) ? str_replace(',', '', $_data['hb_cost_price']) : null;
        $_data['hb_cost_price'] = array_key_exists('bb_cost_price', $_data) ? str_replace(',', '', $_data['bb_cost_price']) : null;
        $_data['hb_cost_price'] = array_key_exists('fb_cost_price', $_data) ? str_replace(',', '', $_data['fb_cost_price']) : null;
        $_hotel_room = $this->hotel_rooms->newEntity($_data);
        if ($_hotel_room !== null) {
            $this->setJSONResponse([
                'success' => $this->hotel_rooms->save($_hotel_room) !== null,
                'row' => $_hotel_room
            ]);
            return;
        }
        $this->raise404(__('Incomplete data!'));
    }

    public function addSellingPrice() {
        $this->jsonOnly();
        $_data = $this->request->getData();
        $_data['bo'] = $this->RSTO->RemoveThousandSeparator($_data['bo']);
        $_data['du'] = $this->RSTO->RemoveThousandSeparator($_data['du']);
        $_data['bb'] = array_key_exists('bb', $_data) ? $this->RSTO->RemoveThousandSeparator($_data['bb']) : null;
        $_data['hb'] = array_key_exists('hb', $_data) ? $this->RSTO->RemoveThousandSeparator($_data['hb']) : null;
        $_data['fb'] = array_key_exists('fb', $_data) ? $this->RSTO->RemoveThousandSeparator($_data['fb']) : null;
        $_selling_price = $this->selling_prices->newEntity($_data);
        if ($_selling_price != null) {
            $this->setJSONResponse([
                'success' => $this->selling_prices->save($_selling_price) !== null,
                'row' => $_selling_price
            ]);
        }
    }

    public function delete() {
        $this->jsonOnly();
        $_id = $this->request->getData('id_hotel_room');
        $_hotel_room = $this->hotel_rooms->get($_id);
        if (!is_null($_hotel_room)) {
            $this->setJSONResponse($this->hotel_rooms->delete($_hotel_room));
            return;
        }
        $this->raise404('Invalid data!');
    }
    public function deleteSellingPrice(){
        $this->jsonOnly();
        $_id = $this->request->getData('id_hotel_room_selling_price');
        $_selling_price = $this->selling_prices->get($_id);
        if (!is_null($_selling_price)) {
            $this->setJSONResponse($this->selling_prices->delete($_selling_price));
            return;
        }
        $this->raise404('Invalid data!');
    }

    public function initialize() {
        parent::initialize();
        $this->hotel_rooms = TableRegistry::getTableLocator()->get('HotelRooms');
        $this->selling_prices = TableRegistry::getTableLocator()->get('HotelRoomSellingPrices');
        // Configure datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewHotelRooms');
        $this->datatableFilters = ['id_hotel' => 'id_hotel'];
        $this->datatableAdditionalColumns = [
            ['data' => 'id_hotel'],
            ['data' => 'id_type']
        ];
        $this->datatableCallback = function(&$rooms){
            foreach($rooms as $_room){
                $_room->bo_cost_price = $this->RSTO->FormatNumber($_room->bo_cost_price);
                $_room->du_cost_price = $this->RSTO->FormatNumber($_room->du_cost_price);
                $_room->bb_cost_price = $this->RSTO->FormatNumber($_room->bb_cost_price);
                $_room->hb_cost_price = $this->RSTO->FormatNumber($_room->hb_cost_price);
                $_room->fb_cost_price = $this->RSTO->FormatNumber($_room->fb_cost_price);
            }
            return $rooms;
        };
    }

    public function sellingPriceDatatable() {
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
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewHotelRoomSellingPrices');
        $_params['filters'] = ['id_hotel_room' => $this->request->getQuery('id_hotel_room')];
        foreach ([['data' => 'id_hotel_room'], ['data' => 'id_currency']] as $_column) {
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$prices){
            foreach($prices as $_price){
                $_price->bo = $this->RSTO->FormatNumber($_price->bo);
                $_price->du = $this->RSTO->FormatNumber($_price->du);
                $_price->bb = $this->RSTO->FormatNumber($_price->bb);
                $_price->hb = $this->RSTO->FormatNumber($_price->hb);
                $_price->fb = $this->RSTO->FormatNumber($_price->fb);
            }
            return $prices;
        };
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }

    public function sellingPriceCurrencySelect2() {
        $this->jsonOnly();
        $_exp = new QueryExpression();
        $_id_hotel_room = $this->request->getQuery('id_hotel_room');
        $_query = TableRegistry::getTableLocator()->get('ViewHotelRoomSellingPrices')->find();
        $_query->where(['id_hotel_room' => $_id_hotel_room]);
        $_exclude = $_query->select(['id_currency'])->extract('id_currency')->toArray();
        $_params = $this->request->getData();
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $_params['column'] = 'option';
        $_params['filters'] = [['id_select' => self::CURRENCY_ID_SELECT]];
        if (count($_exclude) > 0) {
            array_push($_params['filters'], $_exp->notIn('id', $_exclude));
        }

        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    /**
     * Gives data to jQuery Select2 of room types
     * @return void
     */
    public function typeSelect2() {
        $this->jsonOnly();
        $_params = $this->request->getData();

        // Build exclude array
        $_id_hotel = $this->request->getQuery('id_hotel');
        $_exclude = [];
        $_params['filters'] = [];
        if (!is_null($_id_hotel)) {
            $_query = $this->datatableTable->find();
            $_query->where(['id_hotel' => $_id_hotel]);
            $_exclude = $_query->select(['id_type'])->distinct()->extract('id_type')->toArray();
        }
        // Build query filter
        $_exp = new QueryExpression();
        if (count($_exclude) > 0) {
            array_push($_params['filters'], $_exp->notIn('id', $_exclude));
        }

        $_params['table'] = TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $_params['column'] = 'option';
        array_push($_params['filters'], ['id_select' => self::ROOM_TYPE_ID_SELECT]);
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    public function update() {
        $this->jsonOnly();
        $_id = $this->request->getQuery('id_hotel_room');
        $_hotel_room = $this->hotel_rooms->get($_id);
        if (!is_null($_hotel_room)) {
            $_data = $this->request->getData();
            $_hotel_room->type = $this->request->getData('type');
            $_hotel_room->capacity = str_replace(',', '', $_data['capacity']);
            $_hotel_room->bo_cost_price = str_replace(',', '', $_data['bo_cost_price']);
            $_hotel_room->du_cost_price = str_replace(',', '', $_data['du_cost_price']);
            $_hotel_room->hb_cost_price = array_key_exists('hb_cost_price', $_data) ? str_replace(',', '', $_data['hb_cost_price']) : null;
            $_hotel_room->bb_cost_price = array_key_exists('bb_cost_price', $_data) ? str_replace(',', '', $_data['bb_cost_price']) : null;
            $_hotel_room->fb_cost_price = array_key_exists('fb_cost_price', $_data) ? str_replace(',', '', $_data['fb_cost_price']) : null;
            $this->setJSONResponse([
                'success' => $this->hotel_rooms->save($_hotel_room) !== null,
                'row' => $_hotel_room
            ]);
            return;
        }
        $this->raise404(__('Incomplete data!'));
    }

    public function updateSellingPrice() {
        $this->jsonOnly();
        $_id = $this->request->getQuery('id_hotel_room_selling_price');
        $_selling_price = $this->selling_prices->get($_id);
        if ($_selling_price !== null) {
            $_data = $this->request->getData();
            $_selling_price->currency = $this->request->getData('currency');
            $_selling_price->bo = $this->RSTO->RemoveThousandSeparator($_data['bo']);
            $_selling_price->du = $this->RSTO->RemoveThousandSeparator($_data['du']);
            $_selling_price->bb = array_key_exists('bb', $_data) ? $this->RSTO->RemoveThousandSeparator($_data['bb']) : null;
            $_selling_price->hb = array_key_exists('hb', $_data) ? $this->RSTO->RemoveThousandSeparator($_data['hb']) : null;
            $_selling_price->fb = array_key_exists('fb', $_data) ? $this->RSTO->RemoveThousandSeparator($_data['fb']) : null;
            $this->setJSONResponse([
                'success' => $this->selling_prices->save($_selling_price) !== null,
                'row' => $_selling_price
            ]);
            return;
        }
        $this->raise404(__(sprintf("ID %s doesn't exist!", $_id)));
    }

}
