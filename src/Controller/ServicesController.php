<?php

namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Database\Expression\QueryExpression;

final class ServicesController extends AppController{
    protected $pageInfos = [
        'title' => 'Services',
        'subtitle' => 'Manage your services here'
    ];
    
    protected $actionsPrivileges = [
        'add' => '10.2',
        'addProvider' => '10.2',
        'addProviderSellingPrice' => '10.3',
        'addSellingPrice' => '10.3',
        'chooseProvider' => '10.2',
        'costPriceDatatable' => '10.3',
        'currencySelect2' => '10.2',
        'delete' => '10.2',
        'deleteProvider' => '10.2',
        'deleteProviderSellingPrice' => '10.3',
        'deleteSellingPrice' => '10.3',
        'index' => '10.1',
        'datatable' => '10.1',
        'placeSelect2' => '10.2',
        'providerDatatable' => '10.2',
        'providerSelect2' => '10.2',
        'providerSellingPriceDatatable' => '10.3',
        'providerSellingPriceCurrencySelect2' => '10.3',
        'sellingPriceDatatable' => '10.1',
        'titleSelect2' => '10.2',
        'typeSelect2' => '10.2',
        'update' => '10.2',
        'updateProvider' => '10.2',
        'updateProviderSellingPrice' => '10.3',
        'updateSellingPrice' => '10.3'
    ];
    
    /**
     * Services table
     * @var \Cake\ORM\Table
     */
    protected $services;
    
    /**
     * Service selling prices table
     * @var \Cake\ORM\Table 
     */
    protected $sellingPrices;
    
    /**
     * Service dependencies table
     * @var \Cake\ORM\Table 
     */
    protected $dependencies;
    
    public function add(){
        $this->jsonOnly();
        $_service = $this->services->newEntity($this->request->getData());
        if(is_object($_service)){
            $_service->from_provider = $_service->from_provider === 'on' ? 1 : 0;
            $_service->adult_cost_price = $_service->from_provider === 1 ? null : $this->RSTO->RemoveThousandSeparator($_service->adult_cost_price);
            $_service->children_cost_price = $_service->from_provider === 1 ? null : ($_service->children_cost_price === '' ? $_service->adult_cost_price : $this->RSTO->RemoveThousandSeparator($_service->children_cost_price));
            $this->setJSONResponse([
                'success' => $this->services->save($_service) !== null,
                'row' => $_service
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function addProvider(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Service');
        $_provider = $_component->add($this->request->getData());
        $this->setJSONResponse([
            'success' => is_object($_provider),
            'row' => $_provider
        ]);
    }
    
    public function addProviderSellingPrice(){
        $this->jsonOnly();
        $_table = TableRegistry::getTableLocator()->get('ProviderSellingPrices');
        $_price = $_table->newEntity($this->request->getData());
        if(is_object($_price)){
            $_price->adult = $this->RSTO->RemoveThousandSeparator($_price->adult);
            $_price->children = $_price->children === '' ? $_price->adult : $this->RSTO->RemoveThousandSeparator($_price->adult);
            $this->setJSONResponse([
                'success' => $_table->save($_price),
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function addSellingPrice(){
        $this->jsonOnly();
        $_price = $this->sellingPrices->newEntity($this->request->getData());
        if(is_object($_price)){
            $_price->price = $this->RSTO->RemoveThousandSeparator($_price->price);
            $this->setJSONResponse([
                'success' => $this->sellingPrices->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function chooseProvider(){
        $this->jsonOnly();
        $_component = $this->loadComponent('Service');
        $_provider = $_component->choose($this->request->getData());
        $this->setJSONResponse([
            'success' => is_object($_provider),
            'row' => $_provider
        ]);
        
    }
    
    public function costPriceDatatable(){
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
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ProviderCostPrices');
        $_params['filters'] = ['service_provider' => $this->request->getQuery('id_service_provider')];
        $_params['callback'] = function (&$prices){
            foreach($prices as $_price){
                $_price->adult = $this->RSTO->formatNumber($_price->adult);
                $_price->children = $this->RSTO->formatNumber($_price->children);
            }
            return $prices;
        };
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function currencySelect2(){
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_exclude = $this->sellingPrices->find()->where(['service' => $this->request->getQuery('id_service')])->extract('currency')->toArray();
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->getCurrencies($_exclude));
    }
    
    public function delete(){
        $this->jsonOnly();
        $_service = $this->services->get($this->request->getData('id_service'));
        if(is_object($_service)){
            // Delete prices
            $this->sellingPrices->deleteAll(['service' => $_service->id_service]);
            
            // Delete dependencies
            $this->dependencies->deleteAll(['dependent' => $_service->id_service]);
            
            $this->setJSONResponse($this->services->delete($_service));
        } else {
            $this->raise404();
        }
    }
    
    public function deleteProvider(){
        $this->jsonOnly();
        $this->setJSONResponse($this->loadComponent('service')->delete($this->request->getData('id_service_provider')));
    }
    
    public function deleteProviderSellingPrice(){
        $this->jsonOnly();
        $_table = TableRegistry::getTableLocator()->get('ProviderSellingPrices');
        $_price = $_table->get($this->request->getData('id_provider_selling_price'));
        if(is_object($_price)){
            $this->setJSONResponse($_table->delete($_price));
        } else {
            $this->raise404();
        }
    }
    
    public function deleteSellingPrice(){
        $this->jsonOnly();
        $_price = $this->sellingPrices->get($this->request->getData('id_service_selling_price'));
        if(is_object($_price)){
            $this->setJSONResponse($this->sellingPrices->delete($_price));
        } else {
            $this->raise404();
        }
    }
    
    public function index(){
        $this->set('rsto_service_datatable_url', Router::url('/services/datatable'));
        $this->set('rsto_service_add_url', Router::url('/services/add'));
        $this->set('rsto_service_place_select2_url', Router::url('/services/placeSelect2'));
        $this->set('rsto_service_type_select2_url', Router::url('/services/typeSelect2'));
        $this->set('rsto_service_edit_url', Router::url('/services/update'));
        $this->set('rsto_service_delete_url', Router::url('/services/delete'));
        // Selling prices
        $this->set('rsto_service_selling_price_datatable_url', Router::url('/services/selling_price_datatable'));
        $this->set('rsto_service_selling_price_currency_select2_url', Router::url('/services/currency_select2'));
        $this->set('rsto_service_selling_price_add_url', Router::url('/services/add_selling_price'));
        $this->set('rsto_service_selling_price_edit_url', Router::url('/services/update_selling_price'));
        $this->set('rsto_service_selling_price_delete_url', Router::url('/services/delete_selling_price'));
        // Providers
        $this->set('rsto_service_provider_datatable_url', Router::url('/services/provider_datatable'));
        $this->set('rsto_service_title_select2_url', Router::url('/services/title_select2'));
        $this->set('rsto_service_provider_add_url', Router::url('/services/add_provider'));
        $this->set('rsto_service_provider_choose_url', Router::url('/services/choose_provider'));
        $this->set('rsto_service_provider_edit_url', Router::url('/services/update_provider'));
        $this->set('rsto_service_provider_delete_url', Router::url('/services/delete_provider'));
        $this->set('rsto_service_provider_select2_url', Router::url('/services/provider_select2'));
        $this->set('rsto_service_provider_selling_price_datatable_url', Router::url('/services/provider_selling_price_datatable'));
        $this->set('rsto_service_provider_selling_price_currency_select2_url', Router::url('/services/provider_selling_price_currency_select2'));
        $this->set('rsto_service_provider_selling_add_url', Router::url('/services/add_provider_selling_price'));
        $this->set('rsto_service_provider_selling_update_url', Router::url('/services/update_provider_selling_price'));
        $this->set('rsto_service_provider_selling_delete_url', Router::url('/services/delete_provider_selling_price'));
    }
    
    public function initialize() {
        parent::initialize();
        
        // Init tables
        $this->services = TableRegistry::getTableLocator()->get('Services');
        $this->sellingPrices = TableRegistry::getTableLocator()->get('ServiceSellingPrices');
        $this->dependencies = TableRegistry::getTableLocator()->get('ServiceDependencies');
        
        // Init datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewServices');
        $this->datatableAdditionalColumns = [
            ['data' => 'id_type'],
            ['data' => 'id_place'],
            ['data' => 'from_provider']
        ];
        $this->datatableCallback = function(&$services){
            foreach($services as $_service){
                $_service->adult_cost_price = $_service->from_provider === 1 ? __('Depends on provider') : $this->RSTO->FormatNumber($_service->adult_cost_price);
                $_service->children_cost_price = $_service->from_provider === 1 ? __('Depends on provider') : ($_service->children_cost_price === null ? $_service->adult_cost_price : $this->RSTO->FormatNumber($_service->children_cost_price));
            }
            return $services;
        };
    }
    
    public function placeSelect2(){
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_params['table'] = TableRegistry::getTableLocator()->get('Places');
        $_params['column'] = 'name';
        $_params['filters'] = [['level' => 1]];
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }
    
    public function providerDatatable(){
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
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewServicesProviders');
        foreach([['data' => 'id_service'], ['data' => 'id_title'], ['data' => 'must_book'], ['data' => 'is_default']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['filters'] = ['id_service' => $this->request->getQuery('id_service')];
        $_params['callback'] = function (&$providers){
            foreach($providers as $_provider){
                $_provider->adult_cost_price = $this->RSTO->formatNumber($_provider->adult_cost_price);
                $_provider->children_cost_price = $_provider->children_cost_price === null ? $_provider->adult_cost_price : $this->RSTO->formatNumber($_provider->children_cost_price);
            }
            return $providers;
        };
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function providerSelect2(){
        $this->jsonOnly();
        // Exclude existing providers 
        $_exclude = TableRegistry::getTableLocator()->get('ServicesProviders')->find();
        $_exclude->where(['service' => $this->request->getQuery('service')]);
        $_exclude = $_exclude->select(['provider'])->extract('provider')->toArray();
        $_exp = new QueryExpression();
        if (count($_exclude) > 0) {
            $_params['filters'] =[$_exp->notIn('id', $_exclude)];
        }
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewProviders');
        $_params['column'] = 'fullname';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }
    
    public function providerSellingPriceDatatable(){
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
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewProviderSellingPrices');
        foreach([['data' => 'id_currency']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$_prices){
            foreach($_prices as $_price){
                $_price->adult = $this->RSTO->FormatNumber($_price->adult);
                $_price->children = $this->RSTO->FormatNumber($_price->children);
            }
            return $_prices;
        };
        $_params['filters'] = ['id_service_provider' => $this->request->getQuery('service_provider')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function sellingPriceDatatable(){
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
        
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewServiceSellingPrices');
        foreach([['data' => 'id_service'], ['data' => 'id_currency']] as $_column){
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }
        $_params['callback'] = function(&$_prices){
            foreach($_prices as $_price){
                $_price->price = $this->RSTO->FormatNumber($_price->price);
            }
            return $_prices;
        };
        $_params['filters'] = ['id_service' => $this->request->getQuery('id_service')];
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }
    
    public function providerSellingPriceCurrencySelect2(){
        $this->jsonOnly();
        // Exclude existing providers 
        $_exclude = TableRegistry::getTableLocator()->get('ProviderSellingPrices')->find();
        $_exclude->where(['service_provider' => $this->request->getQuery('service_provider')]);
        $_exclude = $_exclude->select(['currency'])->extract('currency')->toArray();
        $_exp = new QueryExpression();
        $_params['filters'] = ['id_select' => Component\RSTOComponent::CURRENCIES_ID_SELECT];
        if (count($_exclude) > 0) {
            array_push($_params['filters'], $_exp->notIn('id', $_exclude));
        }
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $_params['column'] = 'option';
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }
    
    public function titleSelect2(){
        $this->jsonOnly();
        $this->setJSONResponse($this->loadComponent('Select2', $this->request->getData())->getTitles());
    }
    
    public function typeSelect2(){
        $this->jsonOnly();
        $this->setJSONResponse($this->loadComponent('select2', $this->request->getData())->getServiceTypes());
    }
    
    public function update(){
        $this->jsonOnly();
        $_service = $this->services->get($this->request->getQuery('id_service'));
        pr($_service);
        if(is_object($_service)){
            $_service->type = $this->request->getData('type');
            $_service->place = $this->request->getData('place');
            $_service->description = $this->request->getData('description');
            $_service->from_provider = $this->request->getData('from_provider') === 'on' ? 1 : 0;
            $_service->adult_cost_price = $_service->from_provider === 1 ? null : $this->RSTO->RemoveThousandSeparator($this->request->getData('adult_cost_price'));
            $_service->children_cost_price = $_service->from_provider === 1 ? null : ($this->request->getData('children_cost_price') === '' ? $_service->adult_cost_price : $this->RSTO->RemoveThousandSeparator($this->request->getData('children_cost_price')));
            $this->setJSONResponse([
                'success' => $this->services->save($_service) !== null,
                'row' => $_service
            ]);
        } else {
            $this->raise404();
        }
    }
    
    public function updateProvider(){
        $this->jsonOnly();
        $_service_provider = $this->loadComponent('Service')->update($this->request->getQuery('id_service_provider'), $this->request->getData());
        $this->setJSONResponse([
            'success' => !is_null($_service_provider),
            'row' => $_service_provider
        ]);
    }
    
    public function updateProviderSellingPrice(){
        $this->jsonOnly();
        $_table = TableRegistry::getTableLocator()->get('ProviderSellingPrices');
        $_price = $_table->get($this->request->getQuery('id_provider_selling_price'));
        if(is_object($_price)){
            $_price->currency = $this->request->getData('currency');
            $_price->adult = $this->RSTO->RemoveThousandSeparator($this->request->getData('adult'));
            $_price->children = $this->request->getData('children') === '' ? $_price->adult : $this->RSTO->RemoveThousandSeparator($this->request->getData('children'));
            $this->setJSONResponse([
                'success' => $_table->save($_price),
                'row' => $_price
            ]);
        }
    }
    
    public function updateSellingPrice(){
        $this->jsonOnly();
        $_price = $this->sellingPrices->get($this->request->getQuery('id_service_selling_price'));
        if(is_object($_price)){
            $_price->price = $this->RSTO->RemoveThousandSeparator($this->request->getData('price'));
            $_price->currency = $this->request->getData('currency');
            $this->setJSONResponse([
                'success' => $this->sellingPrices->save($_price) !== null,
                'row' => $_price
            ]);
        } else {
            $this->raise404();
        }
    }
}
