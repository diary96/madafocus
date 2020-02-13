<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class DirectoryController extends AppController {

    protected $pagesInfos = [
        'title' => 'Directory',
        'subtitle' => 'Manage your contact here'
    ];

    /**
     * Directory table
     * @var \Cake\ORM\Table 
     */
    protected $directory;

    /**
     * Contact infos tables
     * @var \Cake\ORM\Table 
     */
    protected $contactInfos;
    protected $actionsPrivileges = [
        'add' => '8.2',
        'addContactInformation' => '8.2',
        'contactInformationDatatable' => '8.2',
        'contactInformationTypeSelect2' => '8.2',
        'delete' => '8.2',
        'deleteContactInformation' => '8.2',
        'datatable' => '8.1',
        'index' => '8.1',
        'titleSelect2' => '8.2',
        'update' => '8.2',
        'updateContactInformation' => '8.2',
        'validateFullname' => '8.2'
    ];

    public function add() {
        $this->jsonOnly();
        $_entry = $this->directory->newEntity($this->request->getData());
        $_entry->fullname = preg_replace('/\s+/', ' ', trim($_entry->fullname));
        if (is_object($_entry)) {
            $this->setJSONResponse([
                'success' => $this->directory->save($_entry) !== null,
                'row' => $_entry
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }

    public function addContactInformation() {
        $this->jsonOnly();
        $_contact_info = $this->contactInfos->newEntity($this->request->getData());
        if (is_object($_contact_info)) {
            $this->setJSONResponse([
                'success' => $this->contactInfos->save($_contact_info) !== null,
                'row' => $_contact_info
            ]);
        } else {
            $this->raise404('Incomplete data!');
        }
    }

    public function contactInformationDatatable() {
        $this->jsonOnly();
        $_id_directory = $this->request->getQuery('id_directory');
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
        $_params['table'] = TableRegistry::getTableLocator()->get('ViewDirectoryContactInformations');
        $_filters = [];

        if (!is_null($_id_directory)) {
            array_push($_filters, ['id_directory' => $_id_directory]);
        }

        foreach ([['data' => 'id_directory'], ['data' => 'id_type']] as $_column) {
            array_push($_params['columns'], array_merge($_columnModel, $_column));
        }

        $_params['filters'] = $_filters;

        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }

    public function contactInformationTypeSelect2() {
        return $this->setJSONResponse($this->loadComponent('Select2', $this->request->getData())->getContactInfoTypes());
    }

    public function delete() {
        $this->jsonOnly();
        $_entry = $this->directory->get($this->request->getData('id_directory'));
        if (is_object($_entry)) {
            $this->setJSONResponse($this->directory->delete($_entry));
        } else {
            $this->raise404('Incomplete data!');
        }
    }
    
    public function deleteContactInformation() {
        $this->jsonOnly();
        $_contact_information = $this->contactInfos->get($this->request->getData('id_directory_contact_information'));
        if (is_object($_contact_information)) {
            $this->setJSONResponse($this->contactInfos->delete($_contact_information));
        } else {
            $this->raise404('Incomplete data!');
        }
    }

    public function index() {
        // Remote validation
        $this->set('rsto_directory_fullname_validation_url', Router::url('/directory/validate_fullname'));
        // Directory
        $this->set('rsto_directory_add_url', Router::url('/directory/add'));
        $this->set('rsto_directory_edit_url', Router::url('/directory/edit'));
        $this->set('rsto_directory_delete_url', Router::url('/directory/delete'));
        $this->set('rsto_directory_datatable_url', Router::url('/directory/datatable'));
        $this->set('rsto_directory_title_select2_url', Router::url('/directory/title_select2'));
        // Directory contact informations
        $this->set('rsto_directory_contact_information_datatable_url', Router::url('/directory/contact_information_datatable'));
        $this->set('rsto_directory_contact_information_type_select2_url', Router::url('/directory/contact_information_type_select2'));
        $this->set('rsto_directory_contact_information_add_url', Router::url('/directory/add_contact_information'));
        $this->set('rsto_directory_contact_information_edit_url', Router::url('/directory/update_contact_information'));
        $this->set('rsto_directory_contact_information_delete_url', Router::url('/directory/delete_contact_information'));
    }

    public function initialize() {
        parent::initialize();

        // Init datatable
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewDirectories');
        $this->datatableAdditionalColumns = [
            ['data' => 'id_title']
        ];

        // Init table
        $this->directory = TableRegistry::getTableLocator()->get('Directories');
        $this->contactInfos = TableRegistry::getTableLocator()->get('DirectoryContactInformations');
    }

    public function titleSelect2() {
        $this->jsonOnly();
        $this->setJSONResponse($this->loadComponent('Select2', $this->request->getData())->getTitles());
    }

    public function update() {
        $this->jsonOnly();
        $_entry = $this->directory->get($this->request->getQuery('id_directory'));
        if (is_object($_entry)) {
            $_entry->fullname = $this->request->getData('fullname');
            $_entry->title = $this->request->getData('title');
            $_entry->description = $this->request->getData('description');
            return $this->setJSONResponse([
                        'success' => $this->directory->save($_entry) !== null,
                        'row' => $_entry
            ]);
        } else {
            $this->raise404(sprintf("ID %s doesn't exist!", $this->request->getQuery('id_directory')));
        }
    }

    public function updateContactInformation() {
        $this->jsonOnly();
        $_contact_information = $this->contactInfos->get($this->request->getQuery('id_directory_contact_information'));
        if (is_object($_contact_information)) {
            $_contact_information->type = $this->request->getData('type');
            $_contact_information->label = $this->request->getData('label');
            $_contact_information->contact_info = $this->request->getData('contact_info');
            return $this->setJSONResponse([
                        'success' => $this->contactInfos->save($_contact_information) !== null,
                        'row' => $_contact_information
            ]);
        } else {
            $this->raise404(sprintf("ID %s doesn't exist!", $this->request->getQuery('id_directory_contact_information')));
        }
    }

    public function validateFullname(){
        $this->jsonOnly();
        $_fullname = preg_replace('/\s+/', ' ', trim($this->request->getData('value')));
        $_matches = [];
        preg_match("/([A-Za-z]|\s|\'|&#0*39;)+/", $_fullname, $_matches);
        $this->setJSONResponse($this->datatableTable->query()->where(['fullname' => $_fullname])->count() === 0 && strlen($_fullname) > 1 && $_matches[0] === $_fullname);
    }
}
