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
 * Description of HotelTransfersController
 *
 * @author RSMandimby
 */
class HotelTransfersController extends AppController {
    
    protected $actionsPrivileges = [
        'add' => '5.2',
        'addSellingPrice' => '5.3',
        'datatable' => '5.1',
        'delete' => '5.2',
        'deleteSellingPrice' => '5.3',
        'hubSelect2' => '5.2',
        'sellingPriceDatatable' => '5.1',
        'sellingPriceCurrencySelect2' => '5.3',
        'update' => '5.2',
        'updateSellingPrice' => '5.3'
    ];
    
    /**
     * Hotel transfers table
     * @var \Cake\ORM\Table
     */
    protected $transfers;
    
    /**
     * Hotel transfer selling prices table
     * @var \Cake\ORM\Table
     */
    protected $sellingPrices;
    
    public function add(){
        $this->jsonOnly();
        $_transfer = $this->transfers->newEntity($this->request->getData());
        if(!is_null($_transfer)){
            // Format numbers
            $_transfer->going_cost_price = $this->RSTO->RemoveThousandSeparator($_transfer->going_cost_price);
            $_transfer->coming_cost_price = $this->RSTO->RemoveThousandSeparator($_transfer->coming_cost_price);
            
            return $this->setJSONResponse([
                'success' => $this->transfers->save($_transfer) !== null,
                'row' => $_transfer
            ]);
        } else {
            $this->raise404('Incomplete data');
        }
    }
    
    public function addSellingPrice(){
        $this->jsonOnly();
        $_selling_price = $this->sellingPrices->newEntity($this->request->getData());
        if(!is_null($_selling_price)){
            $_selling_price->going_price = $this->RSTO->RemoveThousandSeparator($_selling_price->going_price);
            $_selling_price->coming_price = $this->RSTO->RemoveThousandSeparator($_selling_price->coming_price);
            $this->setJSONResponse([
                'success' => $this->sellingPrices->save($_selling_price) !== null,
                'row' => $_selling_price
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }
    
    public function sellingPriceCurrencySelect2(){
        $this->jsonOnly();

        // Build exclude array
        $_id_hotel_transfer = $this->request->getQuery('id_hotel_transfer');
        $_exclude = [];
        if (!is_null($_id_hotel_transfer)) {
            $_query = $this->sellingPrices->find();
            $_query->where(['hotel_transfer' => $_id_hotel_transfer]);
            $_exclude = $_query->select(['currency'])->distinct()->extract('currency')->toArray();
        }

        $this->setJSONResponse($this->loadComponent('Select2', $this->request->getData())->getCurrencies($_exclude));
    }
    
    public function delete(){
        $this->jsonOnly();
        $_transfer = $_transfer = $this->transfers->get($this->request->getData('id_hotel_transfer'));
        if(!is_null($_transfer)){
            $this->setJSONResponse($this->transfers->delete($_transfer));
        } else {
            $this->raise404('Incomplete data');
        }
    }
    
    public function deleteSellingPrice(){
        $this->jsonOnly();
        $_selling_price = $this->sellingPrices->get($this->request->getData('id_hotel_transfer_selling_price'));
        if(!is_null($_selling_price)){
            $this->setJSONResponse($this->sellingPrices->delete($_selling_price));
        } else {
            $this->raise404('Incomplete data');
        }
    }
    
    public function hubSelect2(){
        $this->jsonOnly();
        $_params = $this->request->getData();

        // Build exclude array
        $_id_hotel = $this->request->getQuery('id_hotel');
        $_exclude = [];
        $_params['filters'] = [];
        if (!is_null($_id_hotel)) {
            $_query = $this->datatableTable->find();
            $_query->where(['id_hotel' => $_id_hotel]);
            $_exclude = $_query->select(['id_hub'])->distinct()->extract('id_hub')->toArray();
        }
        // Build query filter
        $_exp = new QueryExpression();
        if (count($_exclude) > 0) {
            array_push($_params['filters'], $_exp->notIn('id', $_exclude));
        }

        $_params['table'] = TableRegistry::getTableLocator()->get('ViewHubs');
        $_params['column'] = 'name';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }
    
    public function initialize() {
        parent::initialize();
        
        // Init tables
        $this->transfers = TableRegistry::getTableLocator()->get('HotelTransfers');
        $this->sellingPrices = TableRegistry::getTableLocator()->get('HotelTransferSellingPrices');
        
        // Init datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewHotelTransfers');
        $this->datatableAdditionalColumns = [['data' => 'id_hub']];
        $this->datatableFilters = ['id_hotel' => 'id_hotel'];
        $this->datatableCallback = function(&$transfers){
            foreach($transfers as $_transfer){
                $_transfer->going_cost_price = $this->RSTO->FormatNumber($_transfer->going_cost_price);
                $_transfer->coming_cost_price = $this->RSTO->FormatNumber($_transfer->coming_cost_price);
            }
            return $transfers;
        };
    }
    
    public function sellingPriceDatatable(){
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
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewHotelTransferSellingPrices');
        $_params['filters'] = ['id_hotel_transfer' => $this->request->getQuery('id_hotel_transfer')];
        foreach ([['data' => 'id_hotel_transfer'], ['data' => 'id_currency']] as $_column) {
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$prices){
            foreach($prices as $_price){
                $_price->going_price = $this->RSTO->FormatNumber($_price->going_price);
                $_price->coming_price = $this->RSTO->FormatNumber($_price->coming_price);
            }
            return $prices;
        };
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function update(){
        $this->jsonOnly();
        $_transfer = $this->transfers->get($this->request->getQuery('id_hotel_transfer'));
        if(!is_null($_transfer)){
            $_transfer->hub = $this->request->getData('hub');
            $_transfer->going_cost_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('going_cost_price'));
            $_transfer->coming_cost_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('coming_cost_price'));
            $this->setJSONResponse([
                'success' => $this->transfers->save($_transfer) != null,
                'row' => $_transfer
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }
    
    public function updateSellingPrice(){
        $this->jsonOnly();
        $_selling_price = $this->sellingPrices->get($this->request->getQuery('id_hotel_transfer_selling_price'));
        if(!is_null($_selling_price)){
            $_selling_price->currency = $this->request->getData('currency');
            $_selling_price->going_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('going_price'));
            $_selling_price->coming_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('coming_price'));
            $this->setJSONResponse([
                'success' => $this->sellingPrices->save($_selling_price) !== null,
                'row' => $_selling_price
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }
    
}
