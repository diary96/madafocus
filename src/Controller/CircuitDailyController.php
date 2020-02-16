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
    protected $tripChildInfo;
    public $actionsPrivileges = [
        'datatable' => '11.1',
    ];

    public function index() {
    }

    public function initialize() {
        parent::initialize();
        $this->datatableTable = TableRegistry::getTableLocator()->get('ViewTripDet');
        $this->datatableAdditionalColumns = [
            ["data"=>"id_places"],
            ["data"=>"id_carrier"],
            ["data"=>"id_hotel"],
            ["data"=>"id_carrier_vehicle"],
            ["data"=>"vehicle_registration"],
            ["data"=>"id_select_option"],
            ["data"=>"meal_plan"],
            ["data"=>"type_vehicule"],
            ["data"=>"type_vehicle_libelle"]
        ];
        $this->datatableFilters = ['id_trips' => 'id_trips'];
        $this->tripChildInfo = TableRegistry::getTableLocator()->get('TripDet');

    }

    public function updateDaily() {
        $this->jsonOnly();
        $_tripChild = $this->tripChildInfo->get($this->request->getQuery('id_directory_contact_information'));
        if (is_object($_tripChild)) {
            $_tripChild->type = $this->request->getData('type');
            $_tripChild->label = $this->request->getData('label');
            $_tripChild->contact_info = $this->request->getData('contact_info');
            return $this->setJSONResponse([
                'success' => $this->contactInfos->save($_tripChild) !== null,
                'row' => $_tripChild
            ]);
        } else {
            $this->raise404(sprintf("ID %s doesn't exist!", $this->request->getQuery('id_directory_contact_information')));
        }
    }

}
