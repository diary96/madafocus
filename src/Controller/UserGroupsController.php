<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Http\Exception\NotFoundException;
use App\Model\Table\GroupsTable;
use Cake\ORM\TableRegistry;
use App\Controller\Component\DatatableComponent;
/**
 * Description of UserGroupsController
 *
 * @author Sitraka
 */
class UserGroupsController extends AppController {

    const PRIVILEGES = [
        "labels" => [
            "1" => "User groups",
            "2" => "Users",
            "3" => "Taxonomy",
            "4" => "Places",
            "5" => "Hotels",
            "6" => "Hubs",
            "7" => "Parks",
            "8" => "Directory",
            "9" => "Carriers",
            "10" => "Serivces",
            "11" => "Circuits",
            "12" => "Booking"
        ],
        "privileges" => [
            "1.1"   => "Consult groups list and privileges",
            "1.2"   => "Manage group and privileges",
            "2.1"   => "Consult user list",
            "2.2"   => "Manage users",
            "3.1"   => "Consult dropdowns list",
            "3.2"   => "Manage dropdowns",
            "4.1"   => "Consult places list",
            "4.2"   => "Manage places",
            "5.1"   => "Consult the list of hotels",
            "5.2"   => "Manage hotels, room, transfers and cost prices",
            "5.3"   => "Manage selling prices",
            "6.1"   => "Consult hubs list",
            "6.2"   => "Manage hubs",
            "7.1"   => "Consult parks list",
            "7.2"   => "Manage parks",
            "7.3"   => "Manage park selling prices",
            "8.1"   => "Consult directory",
            "8.2"   => "Manage directory",
            "9.1"   => "Consult carriers list",
            "9.2"   => "Manage carriers",
            "9.3"   => "Manage carriers selling prices",
            "10.1"  => "Consult service list",
            "10.2"  => "Manage services",
            "10.3"  => "Manage services selling prices",
            "11.1"  => "Consult circtuis list",
            "11.2"  => "Manage circuits",
            "12.1"  => "Consult booking"
        ]
    ];
    
    protected $actionsPrivileges = [
        'add' => '1.2', 
        'delete' => '1.2', 
        'validateName' => '1.2',
        'index' => '1.1', 
        'update' => '1.2', 
        'datatable' => '1.1'
    ];

    /**
     * Modèle de la table groups
     * @var GroupsTable
     */
    public $groups;
    
    /**
     * Composant qui gère les datatables
     * @var DatatableComponent
     */
    public $datatable;

    public function add() {
        $this->jsonOnly();

        // Enregistrement
        $_new_group = $this->groups->newEntity();
        $_new_group->name = trim($this->request->getData('name'));
        $_new_group->privileges = implode(';', array_keys($this->request->getData('privileges')));
        $_response = [
            'success' => $this->groups->save($_new_group) != false,
            'row' => $_new_group
        ];

        $this->setJSONResponse($_response);
    }

    public function delete() {
        $this->jsonOnly();
        $_id = intval($this->request->getData('id_group'));
        $_group = $this->groups->get($_id);
        if(!is_object($_group)){
            $this->raise404();
        }
        $_response = $this->groups->delete($_group) != false;
        $this->setJSONResponse($_response);
    }

    /**
     * Page de gestion de groupes d'utilisateurs
     * @privilege 1.1 pour lecture seule et 1.2 pour les modifications
     */
    public function index() {
        $this->set('x_csrf_token', $this->request->getParam('_csrfToken'));
        $this->set('rsto_privileges', UserGroupsController::PRIVILEGES);
        $this->set('rsto_privileges_url', Router::url('/usergroups/get?type=privilege'));
        $this->set('rsto_add_user_group_url', Router::url('/usergroups/add'));
        $this->set('rsto_edit_user_group_url', Router::url('/usergroups/update'));
        $this->set('rsto_delete_user_group_url', Router::url('/usergroups/delete'));
        $this->set('rsto_get_user_group_url', Router::url('/usergroups/get?type=group'));
        $this->set('rsto_name_validation_url', Router::url('/usergroups/validatename'));
        $this->set('rsto_groups_datatable_url', Router::url('/usergroups/datatable'));
    }

    public function initialize() {
        $this->pageInfos = [
            'title' => __('Groups'),
            'subtitle' => __("Manage groups here.")
        ];

        $this->groups = TableRegistry::getTableLocator()->get('Groups');
        $this->datatableTable = $this->groups;
        $this->datatableAdditionalColumns = [
            ['data' => 'privileges']
        ];
        parent::initialize();
    }

    public function update() {
        $this->jsonOnly();
        $_id = intval($this->request->getQuery('id_group'));
        $_group = $this->groups->get($_id);
        if(!is_object($_group)){
            $this->raise404();
        }
        $_group->name = trim($this->request->getData('name'));
        $_group->privileges = implode(';', array_keys($this->request->getData('privileges')));
        $_response = [
            'success' => $this->groups->save($_group) != false,
            'row' => $_group
        ];

        $this->setJSONResponse($_response);
    }

    public function validateName() {
        $this->jsonOnly();
        $_name = $this->request->getData('value');
        if ($_name !== null) {
            // Le nom du groupe doit étre unique et contenir au moins une lettre
            $this->setJSONResponse($this->groups->exists($_name) == false  && strlen(trim($_name)) > 0);
            return;
        }
        $this->raise404();
    }

}
