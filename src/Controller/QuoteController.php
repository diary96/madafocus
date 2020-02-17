<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Description of CircuitsController
 *
 * @author RSMandimby
 */
class QuoteController extends AppController
{
    public $actionsPrivileges = [
      'index' => '12.1',
      'quote' => '12.1'
    ];

    public function index(){
        $this->layout = 'fullwidth';
        $this->set('rsto_circuit_quote_url', Router::url('/quote/quote'));
    }

    public function quote(){
      // $this->tripMere = TableRegistry::getTableLocator()->get('TripMere');
      // $trip = $this->tripMere->find()->where(['id_trips' => $this->request->getQuery('id')])->first();
      // $this->set('trip', $trip);
      // $this->setJSONResponse([
      //   'success' => true,
      //   'row' => $trip
      // ]);
    }
}
