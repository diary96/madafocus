<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\Routing\Router;

/**
 * Ce controlleur permet de gérer les authentifications et les déconnexions
 * @author Sitraka Razafimandimby
 */
class AuthController extends AppController {

    public function authenticate() {
        // Si la requête est une requête post alors on tente l'authentification
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            // On test si l'autentification a réussi
            if ($user) {
                // On crée la session
                $this->Auth->setUser($user);
                // On redirige l'utilisateur vers la page d'origine ou le tableau de bord
                $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error("Le nom d'utilisateur ou mot de passe est éronné!");
            $this->redirect('/auth/login');
        }
    }

    /**
     * Page de connexion de l'utilisateur.
     */
    public function login() {
        $this->set('x_csrf_token', $this->request->getParam('_csrfToken'));
        $this->set('authentication_url', Router::url('/auth/authenticate'));
        $this->viewBuilder()->setLayout("ajax");
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    public function initialize() {
        parent::initialize();
        /**
         * Si aucune athentification n'a été faite alors la méthode Authenticate doit être accessible publiquement pour permettre les authentifications
         * Sinon elle doit être innaccessible pour éviter les doublons
         */
        if (!$this->loggedIn()) {
            $this->Auth->allow('logout');
            $this->Auth->allow('authenticate');
        } else {
            $this->Auth->allow('logout');
        }
    }

}
