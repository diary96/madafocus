<?php

namespace App\Controller\Component;
use Cake\Controller\Component;
use App\Model\Table;
use Cake\Database\Expression\QueryExpression;

/**
 * Description of DatatableComponent
 *
 * @author macbookpro
 */
class DatatableComponent extends Component{
    
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
        $_queryExpression = new QueryExpression();
        $_recordsTotal = $query->count();
        
        // Prise en charge de la pagination
        $_limit = $this->params['length'];
        if($_limit > 0){
            $query->limit($_limit)->page(intval(round($this->params['start'] / $_limit, 0, PHP_ROUND_HALF_DOWN) + 1));
        }
        
        $_rows = is_callable($this->params['callback']) ?  $this->params['callback']($query) : $query;
        $_searchValue = $this->params['search']['value'];
        $_searchValueLength = strlen($_searchValue);
        if($_searchValueLength > 0){
            $_rows = $this->formatSearchResults($_rows);
        }   
        
        return [
            'draw' => intval($this->params['draw']),
            'data' => $_rows,
            'recordsTotal' => count($this->params['filters']) > 0 ? $this->table->find()->where($_queryExpression->and_($this->params['filters']))->count() : $this->table->find()->count(),
            'recordsFiltered' => $_recordsTotal
        ];
    }
    
    private function formatSearchResults($rows){
        $_rows = $rows;
        foreach($_rows as $_row){
            foreach($this->params['columns'] as $_column){
                $_columnName = $_column['data'];
                $_searchResult = $_row->$_columnName;
                $_row->$_columnName = $this->formatSearchResult($_searchResult);
            }
        }
        return $_rows;
    }
    
    private function formatSearchResult($value){
        $_searchResult = $value;
        $_searchValue = $this->params['search']['value'];
        $_searchValueLength = strlen($_searchValue);
        $_cursor = stripos($_searchResult, $_searchValue, 0);
        while($_cursor > -1 && $_cursor !== FALSE){
            $_toReplace = substr($_searchResult, $_cursor, $_searchValueLength);
            $_replacement = sprintf("<b>%s</b>", $_toReplace);
            $_partOne = substr($_searchResult, 0, $_cursor);
            $_partTwo = substr($_searchResult, $_cursor + $_searchValueLength);
            $_searchResult = $_partOne . $_replacement . $_partTwo;
            $_cursor += 7 + $_searchValueLength;
            if($_cursor < strlen($_searchResult)){
                $_cursor = stripos($_searchResult, $_searchValue, $_cursor);
                continue;
            }
            break;
            
        }
        return $_searchResult;
    }
    
    private function buildQuery(){
        $_query = $this->table->query();
        
        $_primaryKey = is_array($this->table->getPrimaryKey()) ? 'id' : $this->table->getPrimaryKey();
        $_searchValue = $this->params['search']['value'];
        $_searchFilters = [];
        
        // Tableau des colonnes à sélectionner
        $_selectedColumns = ['id' => $_primaryKey];
        
        foreach($this->params['columns'] as $_column){
            $_columnName = $_column['data'];
            array_push($_selectedColumns, $_columnName);
            array_push($_searchFilters, (new QueryExpression())->like($_columnName, sprintf('%%%s%%', $_searchValue)));
        }
        
        // Recherche globale
        $_query->where((new QueryExpression())->or($_searchFilters));
        
        // Gestion des filtres venant du controlleur
        if(count($this->params['filters']) > 0){
            $_query->where((New QueryExpression())->and($this->params['filters']));
        }
        
        // Remplissage du tableau de tri
        foreach($this->params['order'] as $_order){
            $_columnIndex =$_order['column'];
            $_query->order([$this->params['columns'][$_columnIndex]['data'] => $_order['dir']]);
        }
        
        // Construction de la partie SELECT de la requête
        $_query->select($_selectedColumns);
        return $_query;
    }
    
    public function get(){
        return $this->buildJSON($this->buildQuery());
    }
    
    public function initialize(array $config) {
        parent::initialize($config);
        $_defaultConfigs = [
            'table' => false,
            'draw' => 1,
            'columns' => [],
            'order' => [],
            'start' => 1,
            'length' => -1,
            'search' => [],
            'filters' => [],
            'callback' => null
        ];
        
        $this->params = array_merge($_defaultConfigs, $config);
        // Chargement de la table
        $this->table = $this->params['table'];
    }
}
