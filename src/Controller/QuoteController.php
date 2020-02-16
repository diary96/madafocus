<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Description of CircuitsController
 *
 * @author RSMandimby
 */
class QuoteController extends Controller
{
    public $actionsPrivileges = [
      'index' => '12.1'
    ];

    public function index(){
        $this->layout = 'fullwidth';
    }
}
