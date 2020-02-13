<?php


namespace App\Controller;

use Cake\ORM\TableRegistry;

use Cake\Database\Expression\QueryExpression;


class CircuitDailyController extends AppController {


    /**
     * Hotel room's table
     * @var \Cake\ORM\Table
     */

    /**
     * Hotel room selling price's table
     * @var \Cake\ORM\Table
     */
    public $actionsPrivileges = [
        'datatable' => '11.1',
    ];

    public function index() {
    }

    public function initialize() {
        parent::initialize();
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTripDet');
        $this->datatableFilters = ['ID_TRIPS' => 'id_trips'];

    }

}
