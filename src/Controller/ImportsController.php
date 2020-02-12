<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\Controller\Controller;
use App\Controller\Component\ImportComponent;

/**
 * Import data from CSV files
 * @author RSMandimby
 */
class ImportsController extends Controller{
    
    /**
     * @var ImportComponent 
     */
    private $importer;
    
    public function index(){
        $this->importer->zones();
        $this->importer->places();
        $this->importer->hotels();
        $this->layout = 'ajax';
        $this->render(false);
    }
    
    public function initialize(): void {
        parent::initialize();
        $this->importer = $this->loadComponent('Import');
    }
}
