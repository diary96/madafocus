<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
/**
 * Description of RSTOComponent
 *
 * @author RSMandimby
 */
class RSTOComponent extends Component{
    
    const THOUSAND_SEPARATOR = ',';
    const DECIMAL_SEPARATOR = '.';
    
    // Directories
    const IMPORT_DIR = '/Applications/MAMP/htdocs/rsmandimby/files/imports/';
    
    // Select options
    const PHONE_NUMBER_ID_SELECT_OPTION = 72;
    const EMAIL_ID_SELECT_OPTION = 71;
    
    // Selects
    const CONTACT_INFO_TYPE_ID_SELECT = 4;
    const CURRENCIES_ID_SELECT = 1;
    const TITLES_ID_SELECT = 9;
    const VEHICLE_BRAND_ID_SELECT = 6;
    const VEHICLE_TYPE_ID_SELECT = 7;
    const SERVICE_TYPE_ID_SELECT = 5;
    
    public function FormatNumber($number, $decimals = 2){
        return $number === null ? '-' : number_format($number, $decimals, self::DECIMAL_SEPARATOR, self::THOUSAND_SEPARATOR);
    }
    
    public function RemoveThousandSeparator($number){
        return str_replace(self::THOUSAND_SEPARATOR, '', $number);
    }
}
