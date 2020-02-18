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
        'validate' => '12 .2'
    ];

    public function index() {
        $this->set('rsto_booking_datatable_url', Router::url('/booking/datatable'));
        $this->set('rsto_booking_validate_url', Router::url('/booking/validate'));
    }

    public function initialize() {
        parent::initialize();
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTicket');
        $this->booking = TableRegistry::getTableLocator()->get('Ticket');
    }

    public function validate() {
        $this->jsonOnly();
        $id = $this->request->getData('id');
        $_entry = $this->booking->get($id);
        $_entry->id_status = 2;
        if (is_object($_entry)) {
            $this->setJSONResponse($this->booking->save($_entry));
        } else {
            $this->raise404('Incomplete data!');
        }
    }
}
