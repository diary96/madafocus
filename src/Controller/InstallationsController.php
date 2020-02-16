<?php

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Ce controller permer d'installer l'application
 * @author RSMandimby
 */
final class InstallationsController extends Controller{
    
    /**
     * Gestionnaire de listes déroulantes
     * @var Component\SelectComponent 
     */
    private $_selects;
    
    private $_select_rows = [
        [
            'name' => 'Monnaie',
            'description' => "Liste des monnaies utilisées par l'application",
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'Ar',
                        'default' => 1
                    ],
                    [
                        'option' => '€',
                        'default' => 0
                    ],
                    [
                        'option' => '$',
                        'default' => 0
                    ]
                ],
            ]
        ],
        [
            'name' => 'Types de chambre',
            'description' => 'Liste des types de chambres dans un hôtel',
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'Simple',
                        'default' => 0
                    ],
                    [
                        'option' => 'Double - Grand lit',
                        'default' => 0,
                    ],
                    [
                        'option' => 'Double - 02 lits',
                        'default' => 0
                    ],
                    [
                        'option' => 'Studio',
                        'default' => 0,
                    ],
                    [
                        'option' => 'Appartement',
                        'default' => 0
                    ],
                    [
                        'option' => 'Suite',
                        'default' => 0
                    ],
                    [
                        'option' => 'Suite parentale',
                        'default' => 0
                    ],
                    [
                        'option' => 'Suite royale',
                        'default' => 0
                    ]
                ]
            ]   
        ],
        [
            'name' => 'Types de pension',
            'description' => "Liste des types de pension d'un hôtel",
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'BO',
                        'default' => 1
                    ],
                    [
                        'option' => 'BB',
                        'default' => 0
                    ],
                    [
                        'option' => 'HB',
                        'default' => 0
                    ],
                    [
                        'option' => 'FB',
                        'default' => 0
                    ],
                    [
                        'option' => 'DU',
                        'default' => 0
                    ]
                ]
            ]
        ],
        [
            'name' => 'Type de contact',
            'description' => 'Liste des types de contact : personnel, fixe, mobile, domicile, bureau, etc.',
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'Mobile',
                        'default' => 1
                    ],
                    [
                        'option' => 'Domicile',
                        'default' => 0
                    ],
                    [
                        'option' => 'Bureau',
                        'default' => 0
                    ],
                    [
                        'option' => 'Personnel',
                        'default' => 0
                    ],
                    [
                        'option' => 'Professionnel',
                        'default' => 0
                    ]
                ]
            ]
        ],
        [
            'name' => 'Types de service',
            'description' => 'Liste des types de service',
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'Visite de parc',
                        'default' => 0
                    ],
                    [
                        'option' => 'Guidage',
                        'default' => 0
                    ],
                    [
                        'option' => 'Camping',
                        'default' => 0
                    ],
                    [
                        'option' => 'Location de VTT',
                        'default' => 0
                    ],
                    [
                        'option' => 'Location de voiture',
                        'default' => 0
                    ]
                ]
            ]
        ],
        [
            'name' => 'Marques de véhicule',
            'description' => 'Liste des marques de véhicules',
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'Audi',
                        'default' => 0
                    ],
                    [
                        'option' => 'BMW',
                        'default' => 0
                    ],
                    [
                        'option' => 'Citroën',
                        'default' => 0
                    ],
                    [
                        'option' => 'Dacia',
                        'default' => 0
                    ],
                    [
                        'option' => 'Fiat',
                        'default' => 0
                    ],
                    [
                        'option' => 'Ford',
                        'default' => 0
                    ],
                    [
                        'option' => 'Hyundai',
                        'default' => 0,
                    ],
                    [
                        'option' => 'Kia',
                        'default' => 0
                    ],
                    [
                        'option' => 'Lancia',
                        'default' => 0
                    ],
                    [
                        'option' => 'Mazda',
                        'default' => 0
                    ],
                    [
                        'option' => 'Mitsubishi',
                        'default' => 0
                    ],
                    [
                        'option' => 'Mercedes',
                        'default' => 0
                    ],
                    [
                        'option' => 'Nissan',
                        'default' => 0
                    ],
                    [
                        'option' => 'Opel',
                        'default' => 0
                    ],
                    [
                        'option' => 'Peugeot',
                        'default' => 0
                    ],
                    [
                        'option' => 'Škoda',
                        'default' => 0
                    ],
                    [
                        'option' => 'Subaru',
                        'default' => 0
                    ],
                    [
                        'option' => 'Suzuki',
                        'default' => 0
                    ],
                    [
                        'option' => 'Toyota',
                        'default' => 0
                    ],
                    [
                        'option' => 'Volkswagen',
                        'default' => 0
                    ]
                ]
            ]
        ],
        [
            'name' => 'Types de véhicule',
            'description' => 'Liste des types de véhicules',
            'groups' => [
                '(Aucun)' => [
                    [
                        'option' => 'Berline',
                        'default' => 0
                    ],
                    [
                        'option' => 'Break',
                        'default' => 0
                    ],
                    [
                        'option' => 'Citadine',
                        'default' => 0
                    ],
                    [
                        'option' => 'Monospace',
                        'default' => 0
                    ],
                    [
                        'option' => '4x4',
                        'default' => 0
                    ],
                    [
                        'option' => 'Crossover',
                        'default' => 0
                    ],
                    [
                        'option' => 'SUV',
                        'default' => 0
                    ]
                ]
            ]
        ]
    ];
    
    public $actionsPrivileges = [
        'index' => '3.1'
    ];
    
    private function _initializeSelects(){
        $this->_selects = $this->loadComponent('Select');
        foreach($this->_select_rows as $_row){
            $this->_initalizeSelectsGroups($this->_selects->add($_row, false)->id_select, $_row['groups']);
        }
    }
    
    private function _initalizeSelectsGroups($id_select, $groups){
        foreach($groups as $_group_name => $_options){
            $_group = [
                'name' => $_group_name,
                'select' => $id_select
            ];
            $this->_initializeSelectsOptions($this->_selects->groups->add($_group)->id_select_option_group, $_options);
        }
    }
    
    private function _initializeSelectsOptions($id_group, $options){
        foreach($options as $_option){
            $_option['group'] = $id_group;
            $this->_selects->options->add($_option);
        }
    }
    
    public function index(){
        $this->_initializeSelects();
        $this->render('index', 'ajax');
    }
    
    public function initialize() {
        
    }
}
