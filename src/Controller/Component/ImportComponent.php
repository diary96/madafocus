<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;

/**
 * Description of ImportComponent
 *
 * @author RSMandimby
 */
class ImportComponent extends Component{
    
    private function getFileLines($path){
        $_file = new File($path);
        if($_file->exists()){
            $_file->open();
            $_lines = explode("\n", $_file->read());
            $_file->close();
            return $_lines;
        } else {
            throwException(sprintf("%s does not exists!", $path));
        }
    }
    
    private function getImportDir($file){
        return RSTOComponent::IMPORT_DIR . $file . '.csv';
    }
    
    public function hotels(){
        $_hotels = TableRegistry::getTableLocator()->get('hotels');
        $_count = 0;
        $_lines = $this->getFileLines($this->getImportDir('hotels'));
        foreach($_lines as $_line){
            $_columns = explode(';', $_line);
            if(count($_columns) === 3){
                $_hotel = [
                    'id' => intval($_columns[0]),
                    'place' => intval($_columns[2]),
                    'name' => $_columns[1],
                    'have_restaurant' => 0,
                    'do_transfer' => 0
                ];
                $_count += $_hotels->save($_hotels->newEntity($_hotel)) !== null ? 0 : 1;
            }
        }
        return $_count;
    }
    
    public function places(){
        $_places = TableRegistry::getTableLocator()->get('places');
        $_count = 0;
        $_lines = $this->getFileLines($this->getImportDir('places'));
        foreach($_lines as $_line){
            $_columns = explode(';', $_line);
            if(count($_columns) === 3){
                $_place = [
                    'id' => intval($_columns[0]),
                    'name' => $_columns[1],
                    'parent' => intval($_columns[2]),
                    'level' => 1
                ];
                $_count += $_places->save($_places->newEntity($_place)) !== null ? 1 : 0;
            }
        }
        return $_count;
    }
    
    public function zones(){
        $_zones = TableRegistry::getTableLocator()->get('places');
        $_count = 0;
        $_lines = $this->getFileLines($this->getImportDir('zones'));
        foreach($_lines as $_line){
            $_columns = explode(';', $_line);
            if(count($_columns) === 2){
                $_zone = [
                    'id' => intval($_columns[0]),
                    'name' => $_columns[1],
                    'level' => 0,
                    'parent' => null
                ];
                $_count += $_zones->save($_zones->newEntity($_zone)) !== null ? 1 : 0;
            }
        }
        return $_count;
    }
}
