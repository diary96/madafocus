<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

/**
 * Cette classe gère les listes déroulantes
 * @author RSMandimby
 * @property SelectGroupComponent $_groups Gestionnaire de groupes
 * @property SelectOptionComponent $_options Gestionnaire d'options
 */
final class SelectComponent extends Component{
    
    /**
     * Table selects
     * @var \Cake\ORM\Table 
     */
    private $_selects;
    
    /**
     * Vue view_selects
     * @var Table 
     */
    private $_vSelects;
    
    public $components = [
        '_groups' => [
            'className' => 'SelectGroup'
        ],
        '_options' => [
            'className' => 'SelectOption'
        ]
    ];
    
    /**
     * Ajoute un nouvel enregistrement dans la table selects
     * Après l'ajout, un nouvel enregistrement de select_option_groups est ajoutè, ce sera le groupe par défaut du select
     * @param array $select : Tableau de données
     * @param boolean $add_default_group : Détermine si le groupe (Aucun) doit être ajouté ou non, TRUE par défaut
     * @return \Cake\ORM\Entity
     */
    public function add($select, $add_default_group = true){
        // Création du select
        $_select = $this->_selects->save($this->_selects->newEntity($select));
        // Création du groupe (Aucun)
        if(!is_null($_select) && $add_default_group){
            $this->_groups->add([
                'name' => '(Aucun)',
                'select' => $_select->id_select
            ]);
        }
        return $_select;
    }
    
    public function addGroup($group){
        return $this->_groups->add($group);
    }
    
    public function addOption($option){
        return $this->_options->add($option);
    }
    
    /**
     * Supprime un select
     * Un select peut être supprimé si il n'a qu'un seul groupe : celui par défaut
     * Lors de la suppression d'un select, ses options sont d'abord supprimées puis ses groupes et enfin le select
     * @param int $id 
     * @return boolean
     */
    public function delete($id){
        $_select = $this->_vSelects->get($id);
        if(!is_null($_select)){
            // Suppression des options
            
            // Suppression des groupes
            
            // Suppression du select
            return $this->_selects->delete($_select);
        }
        return false;
    }
    
    public function deleteOption($id){
        return $this->_options->delete($id);
    }
    
    /**
     * Sélection un select à partir de son id
     * @param int $id : ID du select ou false pour avoir la liste complète
     * @return \Cake\ORM\Entity|array
     */
    public function get($id = false){
        if($id === false){
            return $this->_vSelects->find()->select();
        }
        return $this->_vSelects->get($id);
    }
    
    /**
     * Sélectionne la liste des options d'un select
     * @param int $id id_select
     * @return array
     */
    public function getOptions($id){
        $this->_options->setSelectId($id);
        return $this->_options->get();
    }
    
    public function getOption($id){
        return $this->_options->get($id);
    }
    
    /**
     * Détermine si un groupe existe déjà 
     * @param string $name : Nom du groupe
     * @param int $id_select
     * @return boolean
     */
    public function groupExists($name, $id_select){
        return $this->_groups->exists($name, $id_select);
    }
    
    /**
     * Initialise le composant
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->intializeTableRegitry();
        $this->initializeTables();
    }
    
    /**
     * Tnstancie toutes les tables
     */
    private function initializeTables(){
        $this->_selects = TableRegistry::getTableLocator()->get('Selects');
        $this->_vSelects = TableRegistry::getTableLocator()->get('vSelects');
    }
    
    /**
     * Configure les autotables
     */
    private function intializeTableRegitry(){
        TableRegistry::getTableLocator()->setconfig('Selects', ['table' => 'selects']);
        TableRegistry::getTableLocator()->setconfig('vSelects', ['table' => 'view_selects']);
    }
    
    /**
     * Determine si une option existe déjà pour une liste déroulante donnée
     * @param string $option
     * @param int $select
     * @return boolean 
     */
    public function optionExists($option, $select){
        return $this->_options->exists($option, $select);
    }
    
    /**
     * Mets à jour un select à partir d'un tableau de données
     * @param array $select
     * @return boolean
     */
    public function update($select){
        $_select = $this->_selects->get($select['id_select']);
        if(!is_null($_select)){
            $_select->name = $select['name'];
            $_select->description = $select['description'];
            return $this->_selects->save($_select) !== null;
        }
        return false;
    }
    
    public function updateOption($option){
        return $this->_options->update($option);
    }
    
    public function __get($name) {
        switch ($name){
            case 'groups':
                return $this->_groups;
            case 'options':
                return $this->_options;
            default :
                return parent::__get($name);
        }
    }
}
