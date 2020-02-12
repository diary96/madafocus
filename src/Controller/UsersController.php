<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use App\Model\Table\UsersTable;

/**
 * Ce contrôleur gère les utilisateurs
 * @author Sitraka
 */
class UsersController extends AppController {

    /**
     * Table des utilisateurs
     * @var \App\Model\Table\UsersTable 
     */
    public $users;
    
    protected $actionsPrivileges = [
        'add' => '2.2',
        'datatable' => '2.1',
        'delete' => '2.2',
        'get' => '2.1',
        'index' => '2.1',
        'update' => '2.2',
        'select2' => '2.1',
        'timezones' => '2.1',
        'validateUsername' => '2.2'
    ];

    /**
     * 
     */
    public function add() {
        $this->jsonOnly();
        $_hasher = \Cake\Auth\PasswordHasherFactory::build('default');
        $_new_user = $this->users->newEntity($this->request->getData());
        $_new_user->status = UsersTable::PENDING_USER;
        $_new_user->secret = $_hasher->hash('1234');
        $_new_user->privileges = "";
        $this->setJSONResponse([
            'success' => $this->users->save($_new_user) != false,
            'row' => $_new_user
        ]);
    }

    public function resetPassword() {
        $_hasher = \Cake\Auth\PasswordHasherFactory::build('default');
        for ($i = 1; $i < 4; $i++) {
            $_user = $this->users->get($i);
            $_user->secret = $_hasher->hash('0000');
            $this->users->save($_user);
        }
        $this->setJSONResponse('okay');
        $this->render('json');
    }

    public function delete() {
        $this->jsonOnly();
        $_id = intval($this->request->getData('id_user'));
        $_user = $this->users->get($_id);
        if (!is_object($_user)) {
            $this->raise404(sprintf("L'id {0} n'existe pas", $_id));
        }
        $_response = $this->users->delete($_user) !== false;
        $this->setJSONResponse($_response);
    }

    public function get() {
        $this->jsonOnly();
        $_id = intval($this->request->getData('id'));
        if ($_id > 0) {
            $_user = $this->datatableTable->find()->where(['id' => $_id])->select()->first();
            // On ne transmet pas : privileges et secret
            $this->setJSONResponse([
                'id_user' => $_user->id,
                'firstname' => $_user->firstname,
                'lastname' => $_user->lastname,
                'gender' => $_user->gender,
                'group' => $_user->group,
                'group_name' => $_user->group_name,
                'phone_number' => $_user->phone_number,
                'email_address' => $_user->email_address,
                'timezone' => $_user->timezone,
                'timezone_name' => \DateTimeZone::listIdentifiers()[$_user->timezone],
                'username' => $_user->username
            ]);
            return;
        }
        $this->raise404(sprintf("L'id {0} n'existe pas!", $_id));
    }

    public function index() {
        $this->set('rsto_users_datatable_data_url', Router::url('/users/datatable'));
        $this->set('rsto_users_select2_data_url', Router::url('/users/select2'));
        $this->set('rsto_timezones_select2_data_url', Router::url('/users/timezones'));
        $this->set('rsto_users_username_validation_url', Router::url('/users/validateUsername'));
        $this->set('rsto_users_add_url', Router::url('/users/add'));
        $this->set('rsto_users_get_url', Router::url('/users/get'));
        $this->set('rsto_users_edit_url', Router::url('/users/update'));
        $this->set('rsto_users_delete_url', Router::url('/users/delete'));
    }

    public function initialize() {
        parent::initialize();
        $this->pageInfos = [
            'title' => __('Utilisateurs'),
            'subtitle' => __('gérez vos utilisateurs ici')
        ];
        // Initialisation du select2
        $this->select2Table = TableRegistry::getTableLocator()->get('Groups');
        $this->select2Column = "name";
        // Chargement de la table des utilisteurs
        $this->users = TableRegistry::getTableLocator()->get('Users');
        $this->datatableTable = TableRegistry::getTableLocator()->get('UsersView');
        $this->datatableAdditionalColumns = [
            ['data' => 'firstname'],
            ['data' => 'lastname'],
            ['data' => 'group'],
            ['data' => 'gender'],
            ['data' => 'timezone']
        ];
        $this->datatableCallback = function(&$query){
            foreach($query as $_user){
                $_user['timezone_name'] = \DateTimeZone::listIdentifiers()[$_user['timezone']];
            }
            return $query;
        };
    }

    public function timezones() {
        $this->jsonOnly();
        $_response = [];
        $_search = strtolower(trim($this->request->getData('term')));
        $_all = [];
        $_doSearch = strlen($_search) > 0;
        foreach (\DateTimeZone::listIdentifiers() as $_id => $_text) {
            $_push = true;
            $_searchInt = intval($_search);
            if ($_doSearch) {
                $_push = strpos(strtolower($_text), $_search) !== false || ($_id === $_searchInt && $_search == sprintf('%s', $_searchInt));
            }
            if ($_push) {
                array_push($_all, [
                    'id' => $_id,
                    'text' => $_text
                ]);
            }
        }

        // Pagination
        $_count = count($_all);
        $_page = intval($this->request->getData('page')) < 1 ? 1 : intval($this->request->getData('page'));
        $_limit = 30;
        $_start = ($_page - 1) * $_limit;

        for ($_i = $_start; ($_i < $_start + $_limit) && ($_i < $_count); $_i++) {
            array_push($_response, $_all[$_i]);
        }

        $this->setJSONResponse([
            'results' => $_response,
            'pagination' => [
                'more' => $_start + $_limit < $_count
            ],
        ]);
    }

    public function update() {
        $this->jsonOnly();
        $_id = intval($this->request->getQuery('id_user'));
        $_user = $this->users->get($_id);
        if (is_object($_user)) {
            $_user->firstname = $this->request->getData('firstname');
            $_user->lastname = $this->request->getData('lastname');
            $_user->email_address = $this->request->getData('email_address');
            $_user->gender = $this->request->getData('gender');
            $_user->group = $this->request->getData('group');
            $_user->username = $this->request->getData('username');
            $_user->phone_number = $this->request->getData('phone_number');
            $_user->timezone = $this->request->getData('timezone');
            $this->setJSONResponse([
                'success' => $this->users->save($_user) !== false,
                'row' => $_user
            ]);
            return;
        }
        $this->raise404(sprintf("L'id %s n'existe pas", $_id));
    }

    public function validateUsername() {
        $this->jsonOnly();
        $_name = $this->request->getData('value');
        if ($_name !== null) {
            $_exists = $this->users->exists(trim($_name));
            $_exists &= strlen(trim($_name)) > 0;
            $this->setJSONResponse(!$_exists);
            return;
        }
        $this->raise404();
    }

}
