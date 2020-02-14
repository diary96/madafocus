<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

/**
 * Description of CarriersController
 *
 * @author RSMandimby
 */
class CarriersController extends AppController{
    protected $actionsPrivileges = [
        'add' => '9.2',
        'addDriver' => '9.2',
        'addVehicle' => '9.2',
        'addVehiclePrice' => '9.2',
        'addVehicleSellingPrice'=> '9.3',
        'currencySelect2'=> '9.3',
        'datatable' => '9.1',
        'delete' => '9.2',
        'deleteDriver' => '9.2',
        'deleteVehicle' => '9.2',
        'deleteVehiclePrice' => '9.2',
        'deleteVehicleSellingPrice' => '9.3',
        'driverDatatable' => '9.1',
        'index' => '9.1',
        'titleSelect2' => '9.2',
        'update' => '9.2',
        'updateDriver' => '9.2',
        'updateVehicle' => '9.2',
        'updateVehiclePrice' => '9.2',
        'updateVehicleSellingPrice' => '9.3',
        'vehicleDatatable' => '9.1',
        'vehicleBrandSelect2' => '9.2',
        'vehiclePriceDatatable' => '9.1',
        'vehicleSellingPriceDatatable' => '9.1',
        'vehicleTypeSelect2' => '9.2'
    ];
    
    protected $pageInfos = [
        'title' => 'Carriers',
        'subtitle' => 'Manage carriers here'
    ];
    
    /**
     * Carrier's table
     * @var Cake\ORM\Table
     */
    protected $carriers;
    
    /**
     * Directory's table
     * @type Cake\ORM\Table
     */
    protected $directories;
    
    /**
     * Contact information's table
     * @var Cake\ORM\Table
     */
    protected $directoryContactInformations;
    
    /**
     * Carrier's vehicles table
     * @var \Cake\ORM\Table 
     */
    protected $vehicles;
    
    /**
     * Carrier vehicle prices
     * @var \Cake\ORM\Table
     */
    protected $vehiclePrices;
    
    /**
     * Carrier vehicle selling prices table
     * @var \Cake\ORM\Table 
     */
    protected $vehicleSellingPrices;
    
    public function add(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Carrier');
        $_carrier = $_component->add($this->request->getData());
        if(is_object($_carrier)){
            $_carrier ->half_cost_price = $this->RSTO->RemoveThousandSeparator($_carrier->half_cost_price);
            $_carrier ->full_cost_price = $this->RSTO->RemoveThousandSeparator($_carrier->full_cost_price);
            $this->setJSONResponse([
                'success' => is_object($_carrier),
                'row' => $_carrier
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function addDriver(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Carrier');
        $_driver = $_component->addDriver($this->request->getData());
        if(is_object($_driver)){
            $this->setJSONResponse([
                'success' => 'true',
                'row' => $_driver
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function addVehicle(){
        $this->jsonOnly();
        $_vehicle = $this->vehicles->newEntity($this->request->getData());
        if(is_object($_vehicle)){
            $_vehicle->seat_count = $this->RSTO->RemoveThousandSeparator($_vehicle->seat_count);
            $this->setJSONResponse([
                'success' => $this->vehicles->save($_vehicle) !== null,
                'row' => $_vehicle
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function addVehiclePrice(){
        $this->jsonOnly();
        $_price = $this->vehiclePrices->newEntity($this->request->getData());
        if(is_object($_price)){
            $this->setJSONResponse([
                'success' => $this->vehiclePrices->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function addVehicleSellingPrice(){
        $this->jsonOnly();
        $_price = $this->vehicleSellingPrices->newEntity($this->request->getData());
        if(is_object($_price)){
            $this->setJSONResponse([
                'success' => $this->vehicleSellingPrices->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function currencySelect2(){
        $this->jsonOnly();
        $_id_carrier_vehicle_price = $this->request->getQuery('id_carrier_vehicle_price');
        $_exclude = $this->vehicleSellingPrices->find()->where(['carrier_vehicle_price' => $_id_carrier_vehicle_price])->select(['currency'])->extract('currency')->toArray();
        $this->setJSONResponse($this->loadComponent('select2', $this->request->getData())->getCurrencies($_exclude));
    }
    
    public function delete(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Carrier');
        $_id_carrier = $this->request->getData('id_carrier');
        $this->setJSONResponse($_component->delete($_id_carrier));
    }
    
    public function deleteDriver(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Carrier');
        $_id_carrier_driver = $this->request->getData('id_carrier_driver');
        $this->setJSONResponse($_component->deleteDriver($_id_carrier_driver));
    }
    
    public function deleteVehicle(){
        $this->jsonOnly();
        $_vehicle = $this->vehicles->get($this->request->getData('id_carrier_vehicle'));
        if(is_object($_vehicle)){
            $this->setJSONResponse($this->vehicles->delete($_vehicle));
        } else {
            $this->raise404();
        }
    }
    
    public function deleteVehiclePrice(){
        $this->jsonOnly();
        $_price = $this->vehiclePrices->get($this->request->getData('id_carrier_vehicle_price'));
        if(is_object($_price)){
            $this->setJSONResponse($this->vehiclePrices->delete($_price));
        } else {
            $this->raise404();
        }
    }
    
    public function deleteVehicleSellingPrice(){
        $this->jsonOnly();
        $_price = $this->vehicleSellingPrices->get($this->request->getData('id_carrier_vehicle_selling_price'));
        if(is_object($_price)){
            $this->setJSONResponse($this->vehicleSellingPrices->delete($_price));
        } else {
            $this->raise404();
        }
    }
    
    public function driverDatatable(){
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_columnModel = [
            'name' => '',
            'data' => '',
            'searchable' => false,
            'search' => [
                'value' => '',
                'regex' => ''
            ]
        ];
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewCarrierDrivers');
        foreach([['data' => 'id_carrier'], ['data' => 'id_title']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['filters'] = ['id_carrier' => $this->request->getQuery('id_carrier')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function index(){
        $this->set('rsto_carriers_datatable_url', Router::url('/carriers/datatable'));
        $this->set('rsto_carriers_title_select2', Router::url('/carriers/title_select2'));
        $this->set('rsto_carriers_add_url', Router::url('/carriers/add'));
        $this->set('rsto_carriers_edit_url', Router::url('/carriers/update'));
        $this->set('rsto_carriers_delete_url', Router::url('/carriers/delete'));
        $this->set('rsto_carriers_validate_fullname_url', Router::url('/directory/validate_fullname'));
        // Vehicle prices
        $this->set('rsto_carriers_vehicle_price_datatable_url', Router::url('/carriers/vehicle_price_datatable'));
        $this->set('rsto_carriers_vehicle_price_vehicle_type_select2_url', Router::url('/carriers/vehicle_type_select2'));
        $this->set('rsto_carriers_vehicle_price_add_url', Router::url('/carriers/add_vehicle_price'));
        $this->set('rsto_carriers_vehicle_price_edit_url', Router::url('/carriers/update_vehicle_price'));
        $this->set('rsto_carriers_vehicle_price_delete_url', Router::url('/carriers/delete_vehicle_price'));
        // Vehicle selling prices
        $this->set('rsto_carriers_vehicle_selling_price_datatable_url', Router::url('/carriers/vehicle_selling_price_datatable'));
        $this->set('rsto_carriers_vehicle_selling_price_currency_select2_url', Router::url('/carriers/currency_select2'));
        $this->set('rsto_carriers_vehicle_selling_price_add_url', Router::url('/carriers/add_vehicle_selling_price'));
        $this->set('rsto_carriers_vehicle_selling_price_edit_url', Router::url('/carriers/update_vehicle_selling_price'));
        $this->set('rsto_carriers_vehicle_selling_price_delete_url', Router::url('/carriers/delete_vehicle_selling_price'));
        // Vehicles
        $this->set('rsto_carriers_vehicle_datatable_url', Router::url('/carriers/vehicle_datatable'));
        $this->set('rsto_carriers_vehicle_type_select2_url', Router::url('/carriers/vehicle_type_select2'));
        $this->set('rsto_carriers_vehicle_brand_select2_url', Router::url('/carriers/vehicle_brand_select2'));
        $this->set('rsto_carriers_vehicle_add_url', Router::url('/carriers/add_vehicle'));
        $this->set('rsto_carriers_vehicle_edit_url', Router::url('/carriers/update_vehicle'));
        $this->set('rsto_carriers_vehicle_delete_url', Router::url('/carriers/delete_vehicle'));
        // Drivers
        $this->set('rsto_carriers_driver_datatable_url', Router::url('/carriers/driver_datatable'));
        $this->set('rsto_carriers_driver_title_select2_url', Router::url('/carriers/title_select2'));
        $this->set('rsto_carriers_driver_add_url', Router::url('/carriers/add_driver'));
        $this->set('rsto_carriers_driver_edit_url', Router::url('/carriers/update_driver'));
        $this->set('rsto_carriers_driver_delete_url', Router::url('/carriers/delete_driver'));
    }
    
    public function initialize() {
        parent::initialize();
        
        // Init tables
        $this->carriers = TableRegistry::getTableLocator()->get('Carriers');
        $this->vehicles = TableRegistry::getTableLocator()->get('CarrierVehicles');
        $this->directories = TableRegistry::getTableLocator()->get('Directories');
        $this->directoryContactInformations = TableRegistry::getTableLocator()->get('DirectoryContactInformations');
        $this->vehiclePrices = TableRegistry::getTableLocator()->get('CarrierVehiclePrices');
        $this->vehicleSellingPrices = TableRegistry::getTableLocator()->get('CarrierVehicleSellingPrices');
        
        // Init datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewCarriers');
        $this->datatableAdditionalColumns = [
            ['data' => 'id_title']
        ];
    }
    
    public function vehiclePriceDatatable(){
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_columnModel = [
            'name' => '',
            'data' => '',
            'searchable' => false,
            'search' => [
                'value' => '',
                'regex' => ''
            ]
        ];
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewCarrierVehiclePrices');
        foreach([['data' => 'id_carrier'], ['data' => 'id_type']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$_prices){
            foreach($_prices as $_price){
                $_price->full_cost_price = $this->RSTO->FormatNumber($_price->full_cost_price);
                $_price->half_cost_price = $this->RSTO->FormatNumber($_price->half_cost_price);
            }
            return $_prices;
        };
        $_params['filters'] = ['id_carrier' => $this->request->getQuery('id_carrier')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function vehicleSellingPriceDatatable(){
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_columnModel = [
            'name' => '',
            'data' => '',
            'searchable' => false,
            'search' => [
                'value' => '',
                'regex' => ''
            ]
        ];
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewCarrierVehicleSellingPrices');
        foreach([['data' => 'id_carrier_vehicle_price'], ['data' => 'id_currency']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$_prices){
            foreach($_prices as $_price){
                $_price->full_price = $this->RSTO->FormatNumber($_price->full_price);
                $_price->half_price = $this->RSTO->FormatNumber($_price->half_price);
            }
            return $_prices;
        };
        $_params['filters'] = ['id_carrier_vehicle_price' => $this->request->getQuery('id_carrier_vehicle_price')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function vehicleBrandSelect2(){
        $this->jsonOnly();
        $this->setJSONResponse($this->loadComponent('select2', $this->request->getData())->getVehicleBrands());
    }
    
    public function vehicleTypeSelect2(){
        $this->jsonOnly();
        $_id_carrier = $this->request->getQuery('id_carrier');
        $_exclude = $this->vehiclePrices->find()->where(['carrier' => $_id_carrier])->select(['type'])->extract('type')->toArray();
        $this->setJSONResponse($this->loadComponent('select2', $this->request->getData())->getVehicleTypes($_exclude));
    }
    
    public function titleSelect2(){
        $this->jsonOnly();
        $this->setJSONResponse($this->loadComponent('Select2', $this->request->getData())->getTitles());
    }
    
    public function update(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Carrier');
        $_id_carrier = $this->request->getQuery('id_carrier');
        $_data = $this->request->getData();
        $_carrier = $_component->update($_id_carrier, $_data);
        $this->setJSONResponse([
            'success' => !is_null($_carrier != null),
            'row' => $_carrier
        ]);
    }
    
    public function updateDriver(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Carrier');
        $_id_carrier_driver = $this->request->getQuery('id_carrier_driver');
        $_data = $this->request->getData();
        $_driver = $_component->updateDriver($_id_carrier_driver, $_data);
        $this->setJSONResponse([
            'success' => !is_null($_driver != null),
            'row' => $_driver
        ]);
    }
    
    public function updateVehicle(){
        $this->jsonOnly();
        $_vehicle = $this->vehicles->get($this->request->getQuery('id_carrier_vehicle'));
        if(is_object($_vehicle)){
            $_vehicle->type = $this->request->getData('type');
            $_vehicle->brand = $this->request->getData('brand');
            $_vehicle->seat_count = $this->RSTO->RemoveThousandSeparator($this->request->getData('seat_count'));
            $_vehicle->vehicle_registration = $this->request->getData('vehicle_registration');
            $this->setJSONResponse([
                'success' => $this->vehicles->save($_vehicle) !== null,
                'row' => $_vehicle
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function updateVehiclePrice(){
        $this->jsonOnly();
        $_price = $this->vehiclePrices->get($this->request->getQuery('id_carrier_vehicle_price'));
        if(is_object($_price)){
            $_price->type = $this->request->getData('type');
            $_price->full_cost_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('full_cost_price'));
            $_price->half_cost_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('half_cost_price'));
            $this->setJSONResponse([
                'success' => $this->vehiclePrices->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function updateVehicleSellingPrice(){
        $this->jsonOnly();
        $_price = $this->vehicleSellingPrices->get($this->request->getQuery('id_carrier_vehicle_selling_price'));
        if(is_object($_price)){
            $_price->currency = $this->request->getData('currency');
            $_price->full_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('full_price'));
            $_price->half_price = $this->RSTO->RemoveThousandSeparator($this->request->getData('half_price'));
            $this->setJSONResponse([
                'success' => $this->vehicleSellingPrices->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function vehicleDatatable(){
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_columnModel = [
            'name' => '',
            'data' => '',
            'searchable' => false,
            'search' => [
                'value' => '',
                'regex' => ''
            ]
        ];
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewCarrierVehicles');
        foreach([['data' => 'id_carrier'], ['data' => 'id_type'], ['data' => 'id_brand']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['filters'] = ['id_carrier' => $this->request->getQuery('id_carrier')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
}
