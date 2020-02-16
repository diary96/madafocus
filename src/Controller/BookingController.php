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
class BookingController extends AppController
{

    protected $booking;


    public $actionsPrivileges = [
        'add' => '12.2',
        'datatable' => '12.1',
        'delete' => '12.2',
        'index' => '12.1',
        'select2' => '12.2',
        'update' => '12.2',
        'validateName' => '12 .2'
    ];

    public function index()
    {
        $this->set('rsto_ticket_datatable_url', Router::url('/booking/datatable'));
    }

    public function initialize()
    {
        parent::initialize();
        $this->datatableTable = TableRegistry::getTableLocator()->get('Ticket');
        /*$this->datatableAdditionalColumns = [
            ['data' => 'id_ticket']
        ];*/
    }
}
