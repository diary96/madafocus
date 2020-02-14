<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use App\Model\Table;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\TableRegistry;

/**
 * Description of Select2Component
 *
 * @author macbookpro
 */
class Select2Component extends Component{
    /**
     * Table 
     * @var Table 
     */
    private $table;
    
    /**
     * Tableau des paramètres du datatable
     * @var Array 
     */
    private $params;
    
    private function buildJSON(\Cake\ORM\Query $query){
        $_recordsTotal = $query->count();
        $_page = $this->params['page'];
        $_limit = $this->params['length'];
        $_pagesTotal = intval(round($_recordsTotal / $_limit, 0, PHP_ROUND_HALF_UP));
        
        // Prise en charge de la pagination
        if($_limit > 0){
            $query->limit($_limit)->page($_page);
        }
        
        return [
            'results' => $query,
            'pagination' => [
                'more' => $_page < $_pagesTotal
            ]
        ];
    }
    
    private function buildQuery(){
        $_queryExpression = new QueryExpression();
        $_query = $this->table->query();
        $_primaryKey = is_array($this->table->getPrimaryKey()) ? 'id' : $this->table->getPrimaryKey();
        $_searchValue = $this->params['term'];
        $_column = $this->params['column'];
        // Select
        $_query->select(['id' => $_primaryKey, 'text' => $_column]);
        // Where
        if(strlen(trim($_searchValue)) > 0){
            $_query->where($_queryExpression->like($_column, sprintf('%%%s%%', $_searchValue)));
        }
        
        // Traitement des filtres données par le controller
        if(count($this->params['filters']) > 0){
            $_query->where($_queryExpression->and_($this->params['filters']));
        }
        
        // Order By
        $_query->order([$_column => 'ASC']);
        return $_query;
    }
    
    public function get(){
        return $this->buildJSON($this->buildQuery());
    }
    
    public function initialize(array $config) {
        parent::initialize($config);
        $_defaultConfigs = [
            'table' => false,
            'column' => '',
            'term' => '',
            'q' => '',
            '_type' => 'query',
            'page' => 1,
            'length' => 10,
            'filters' => []
        ];
        
        $this->params = array_merge($_defaultConfigs, $config);
        
        // Chargement de la table
        $this->table = $this->params['table'];
    }
    
    public function getContactInfoTypes($exclude = []){
        return $this->getSelectOptions(RSTOComponent::CONTACT_INFO_TYPE_ID_SELECT, $exclude);
    }
    
    public function getCurrencies($exclude = []){
        return $this->getSelectOptions(RSTOComponent::CURRENCIES_ID_SELECT, $exclude);
    }
    
    public function getVehicleBrands($exclude = []){
        return $this->getSelectOptions(RSTOComponent::VEHICLE_BRAND_ID_SELECT, $exclude);
    }
    
    public function getVehicleTypes($exclude = []){
        return $this->getSelectOptions(RSTOComponent::VEHICLE_TYPE_ID_SELECT, $exclude);
    }
    
    public function getServiceTypes($exclude = []){
        return $this->getSelectOptions(RSTOComponent::SERVICE_TYPE_ID_SELECT, $exclude);
    }
    
    public function getTitles($exclude = []){
        return $this->getSelectOptions(RSTOComponent::TITLES_ID_SELECT, $exclude);
    }
    
    protected function getSelectOptions($id_select, $exclude = []){
        $_exp = new QueryExpression();
        $_filters = [['id_select' => $id_select]];
        if(count($exclude) > 0){
            array_push($_filters, $_exp->notIn('id', $exclude));
        }
        $this->table = TableRegistry::getTableLocator()->get('ViewSelectOptions');
        $this->params['column'] = 'option';
        $this->params['filters'] = $_filters;
        return $this->get();
    }
}
