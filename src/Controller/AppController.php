<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Http\Exception\NotFoundException;

/**
 * Application Controller
 * Tous les contrôleurs héritent de cette classe
 */
class AppController extends Controller {

    /**
     * Composant d'authentification et de permissions
     * @var AuthComponent
     */
    protected $Auth;
    /**
     * Composant de base
     * @var Component\RSTOComponent
     */
    protected $RSTO;

    /**
     * Ce tableau liste les actions qui doivent être publiquement accessibles
     * Il faut définir ce paramètre dans la fonction initialize et avant l'appel de parent::initialize()
     * @var Array
     */
    protected $allowedActions = [];

    /**
     * Table utilisée par les datatables
     * Si cette variable est null, une erreur 404 est retournée.
     * @var \Cake\ORM\Table
     */
    protected $datatableTable = null;

    /**
     * Tableau des filtres du dataTable
     * @var array
     */
    protected $datatableFilters = null;

    protected $datatableCallback = null;

    /**
     * Cette variable liste les colonnes supplémentaires à ajouter à la liste des colonnes du datatable
     * Chaque colonne doit suivre le modèle suivant :
     * [
     *   'name' => 'nom de la colonne',
     *   'data' => 'champ de table de la colonne',
     *   'searchable' => true | false,
     *   'search' => [
     *     'value' : '',
     *     'regex' : ''
     *   ]
     * ]
     * @var type
     */
    protected $datatableAdditionalColumns = [];

    /**
     * Table utilisée par les select2
     * Si cette variable est null, une erreur 404 est retournée.
     * @var \Cake\ORM\Table
     */
    protected $select2Table = null;

    /**
     * Tableau des filtres du select2
     * @var array
     */
    protected $select2Filters = null;

    /**
     * Champ de table qui servira d'étiquette pour select2
     * Si cette variable est null, une erreur 404 est retournée.
     * @var \Cake\ORM\Table
     */
    protected $select2Column = null;

    /**
     * Ce tableau définit :
     *      - Le titre de la page
     *      - Le sous-titre de la page
     * @example description $this->page_info = ['title' => ,'Ceci est le titre', 'subtitle' => 'Ceci est le sous-titre'];
     * @var Array
     */
    protected $pageInfos = [];

    /**
     * Liste des privileges necessaires pour l'expoitation de la page
     * @var Array
     */
    protected $actionsPrivileges = [];

    public function beforeRender(Event $event) {
        parent::beforeRender($event);
        // On transfère vers la vue les informations sur la page actuelle
        $this->pageInfos = array_merge(['title' => '', 'subtitle' => ''], $this->pageInfos);
        $this->set('rsto_page_infos', $this->pageInfos);
        $this->set('rsto_logout_url', Router::url('/auth/logout'));
        // Transfert des information utilisateurs
        if ($this->Auth) {
            $_user = $this->Auth->user();
            $this->set('rsto_logged_user_fullname', $_user['lastname']);
            $this->set('rsto_logged_user_groupname', $_user['group_name']);
            $_privileges = explode(';', $_user['privileges']);
            // Create a constant CAN_X_X for each privilege
            foreach(array_keys(UserGroupsController::PRIVILEGES['privileges']) as $_privilege){
                $_privilegeName = sprintf('CAN_%s', str_replace('.', '_', $_privilege));
                if(defined($_privilegeName)){
                    throwException(sprintf('Privilege %s defined manually', $_privilege));
                } else {
                    define($_privilegeName, in_array($_privilege, $_privileges));
                }
            }
        }

        // Transfer de xCSRFToken
        $this->set('x_csrf_token', $this->request->getParam('_csrfToken'));
    }

    public function dashboard() {
    }

    /**
     * Cette action est appelée par les datatables
     * Il faut affecter une table à la propriéte datatableTable pour activer cette action
     */
    final public function datatable() {
        $_columnModel = [
            'name' => '',
            'data' => '',
            'searchable' => false,
            'search' => [
                'value' => '',
                'regex' => ''
            ]
        ];
        if (is_null($this->datatableTable)) {
            $this->raise404();
        }
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_params['table'] = $this->datatableTable;
        $_params['callback'] = $this->datatableCallback;
        $_filters = [];
        // Ajout des filtres demandées par le controller
        if (is_array($this->datatableFilters)) {
            foreach ($this->datatableFilters as $_queryParam => $_field) {
                $_queryParamValue = $this->request->getQuery($_queryParam);
                if (!is_null($_queryParamValue) && $_queryParamValue !== 'all') {
                    array_push($_filters, [$_field => $_queryParamValue]);
                }
            }
        }
        // Ajout des colonnes supplémentaires demandées par le controller
        if (is_array($this->datatableAdditionalColumns)) {
            foreach ($this->datatableAdditionalColumns as $_column){
                array_push($_params['columns'], array_merge($_columnModel, $_column));
            }
        }

        $_params['filters'] = $_filters;
        $this->setJSONResponse($this->loadComponent('Datatable', $_params)->get());
    }

    /**
     * Fonction d'initialisaton
     * @return void
     */
    public function initialize() {
        parent::initialize();

        // Configuration des autotables
        TableRegistry::getTableLocator()->setConfig('UsersView', ['table' => 'view_users']);

        $this->RSTO = $this->loadComponent('RSTO');
        // Composant nécessaire pour traiter les demandes JSON et XML
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        //Chargement du composant responsable de l'authentification et des permissions
        $this->loadComponent("Auth", [
            'authorize' => ['Controller'],
            'loginAction' => [
                'controller' => 'Auth',
                'action' => 'Login'
            ],
            //'authError' => __("Une authentification est requise!"),
            'authenticate' => [
                'Form' => [
                    'userModel' => 'UsersView',
                    'fields' => [
                        'username' => 'username',
                        'password' => 'secret'
                    ]
                ],
            ],
            'storage' => 'session'
        ]);
        // Donne l'autorisation d'accès aux actions publiques
        if (count($this->allowedActions) > 0) {
            $this->Auth->allow($this->allowedActions);
        }

        // Préparation du tableau des privilèges
        $this->userPrivileges = explode(';', $this->Auth->user('privileges'));


        // Envoi des liens du sidebard
        $this->set('rsto_sidebar_urls', [
            'current' => Router::url($this->request->getRequestTarget()),
            'users' => Router::url('/users'),
            'groups' => Router::url('/usergroups'),
            'selects' => Router::url('/selects'),
            'places' => Router::url('/places'),
            'hotels' => Router::url('/hotels'),
            'hubs' => Router::url('/hubs'),
            'parks' => Router::url('/parks'),
            'directory' => Router::url('/directory'),
            'carriers' => Router::url('/carriers'),
            'services' => Router::url('/services'),
            'circuits' => Router::url('/circuits'),
            'booking' => Router::url('/booking')
        ]);
    }

    /**
     * Cette méthode gère les autorisation d'accès, elle n'est pas overridable
     * Il faut compléter le tableau des privilèges $actionsPrivileges pour que cette méthode fonctionne correctement
     * @param Array $user
     * @return boolean
     */
    final public function isAuthorized($user) {
        $_action = $this->request->getParam('action');
        if (array_key_exists($_action, $this->actionsPrivileges)) {
            $_privilege = $this->actionsPrivileges[$_action];
            return strpos($user['privileges'], $_privilege) !== false;
        }
        return $_action == "dashboard" || false;
    }

    /**
     * Cette fonction permet de couper net les requête autres que JSON
     * @throws NotFoundException
     */
    final protected function jsonOnly() {
        // Si la requête n'est pas de type JSON, on envoi un message d'erreur
        if (!$this->request->is('json')) {
            $this->raise404();
        }
    }

    final protected function loggedIn() {
        return !is_null($this->Auth->user('id'));
    }

    /**
     * Cette fonction redirige l'utilisateur vers la page 404
     * @throws NotFoundException
     */
    final protected function raise404($message = 'Oooops') {
        throw new NotFoundException(_(\Cake\Core\Configure::read('debug') === true ? $message : 'Oooops'));
    }

    /**
     * Cette action est appelée par les select2
     * Il faut definir select2Table et select2Column pour activer cette action
     */
    final public function select2() {
        if (is_null($this->select2Table) || is_null($this->select2Column)) {
            $this->raise404('select2Table ou select2Column est null!');
        }
        $this->jsonOnly();
        $_params = $this->request->getData();
        $_params['table'] = $this->select2Table;
        $_params['column'] = $this->select2Column;
        $_filters = [];
        if (is_array($this->select2Filters)) {
            foreach ($this->select2Filters as $_queryParam => $_field) {
                $_queryParamValue = $this->request->getQuery($_queryParam);
                if (!is_null($_queryParamValue)) {
                    array_push($_filters, [$_field => $_queryParamValue]);
                }
            }
        }
        $_params['filters'] = $_filters;
        $this->setJSONResponse($this->loadComponent('Select2', $_params)->get());
    }

    /**
     * Cette fonction tranmet a la vue une variable à sêrializer en JSON
     * @param Object $value La réponse à sêrializer
     */
    final protected function setJSONResponse($value) {
        $this->set('jsonResponse', $value);
        $this->set('_serialize', 'jsonResponse');
    }

}
