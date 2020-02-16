<?php

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

final class CarrierComponent extends Component{
    
    private $data;
    
    /**
     * Carrier's table
     * @var Cake\ORM\Table
     */
    protected $carriers;
    
    /**
     * Driver's table
     * @var \Cake\ORM\Table 
     */
    protected $drivers;
    
    /**
     * Directory's table
     * @type \Cake\ORM\Table
     */
    protected $directories;
    
    /**
     * Contact information's table
     * @var \Cake\ORM\Table
     */
    protected $directoryContactInformations;
    
    public function add($data){
        // Step 1: create directory
        $_directory = $this->directories->NewEntity($data);
        if(is_object($_directory)){
            // Attempt to save
            if($this->directories->save($_directory) !== null){
                $_contact_infos = $this->addBookingContactInformation($data, $_directory);
                return $this->addCarrier($_directory, $_contact_infos);
            }
        }
    }
    
    private function addBookingContactInformation($data, $directory){
        $_return = ['email' => null];
        // Step 2: create phone number contact informations
        $_booking_phone_number = $this->directoryContactInformations->NewEntity([
            'directory' => $directory->id_directory,
            'contact_info' => $data['booking_phone_number'],
            'label' => 'Booking',
            'type' => RSTOComponent::PHONE_NUMBER_ID_SELECT_OPTION
        ]);
        $this->directoryContactInformations->save($_booking_phone_number);
        $_return['phone'] = $_booking_phone_number->id_contact_information;
        
        // Step 3: create email address contact informations
        if(array_key_exists('booking_mail_address', $data)){
            $_booking_email_address = $this->directoryContactInformations->NewEntity([
                'directory' => $directory->id_directory,
                'contact_info' => $data['booking_mail_address'],
                'label' => 'Booking',
                'type' => RSTOComponent::EMAIL_ID_SELECT_OPTION
            ]);
            $this->directoryContactInformations->save($_booking_email_address);
            $_return['email'] = $_booking_email_address->id_contact_information;
        }
        return $_return;
    }
    
    private function addCarrier($directory, $contact_infos){
        $_carrier = $this->carriers->NewEntity([
            'directory' => $directory->id_directory,
            'booking_phone_number' => $contact_infos['phone'],
            'booking_mail_address' => $contact_infos['email']
        ]);
        return $this->carriers->save($_carrier);
    }
    
    public function addDriver($data){
        // Step 1: create directory
        $_directory = $this->directories->NewEntity($data);
        if(is_object($_directory)){
            // Attempt to save
            if($this->directories->save($_directory) !== null){
                $_contact_infos = $this->addDriverContactInformation($data, $_directory);
                $_driver = $this->drivers->newEntity([
                    'carrier' => $data['carrier'],
                    'description' => $data['description'],
                    'directory' => $_directory->id_directory,
                    'phone_number' => $_contact_infos['phone'],
                    'email_address' => $_contact_infos['email']
                ]);
                return $this->drivers->save($_driver);
            }
        }
    }
    
    public function addDriverContactInformation($data, $directory){
        $_return = ['email' => null];
        // Step 2: create phone number contact informations
        $_driver_phone_number = $this->directoryContactInformations->NewEntity([
            'directory' => $directory->id_directory,
            'contact_info' => $data['phone_number'],
            'label' => 'Phone number',
            'type' => RSTOComponent::PHONE_NUMBER_ID_SELECT_OPTION
        ]);
        $this->directoryContactInformations->save($_driver_phone_number);
        $_return['phone'] = $_driver_phone_number->id_contact_information;
        
        // Step 3: create email address contact informations
        if(array_key_exists('email_address', $data)){
            $_driver_email_address = $this->directoryContactInformations->NewEntity([
                'directory' => $directory->id_directory,
                'contact_info' => $data['email_address'],
                'label' => 'Email address',
                'type' => RSTOComponent::EMAIL_ID_SELECT_OPTION
            ]);
            $this->directoryContactInformations->save($_driver_email_address);
            $_return['email'] = $_driver_email_address->id_contact_information;
        }
        return $_return;
    }
    
    public function delete($id_carrier){
        $_carrier = $this->carriers->get($id_carrier);
        if(is_object($_carrier)){
            $_return = true;
            $_phone_number = $_carrier->booking_phone_number;
            $_email_address = $_carrier->booking_mail_address;
            $_directory = $_carrier->directory;
            
            // Delete drivers
            $_return |= $this->drivers->deleteAll(['carrier' => $id_carrier]);
            
            // Delete carrier
            $_return &= $this->carriers->delete($_carrier);
            
            // Detele contact info
            $_return |= $this->directoryContactInformations->deleteAll(['id_contact_information' => $_phone_number]);
            $_return |= $this->directoryContactInformations->deleteAll(['id_contact_information' => $_email_address]);
            
            // Delete directory
            $_return |= $this->directories->deleteAll(['id_directory' => $_directory]);
            
            return $_return;
        }
        return false;
    }
    
    public function deleteDriver($id_carrier_driver){
        $_driver = $this->drivers->get($id_carrier_driver);
        if(is_object($_driver)){
            $_return = true;
            $_phone_number = $_driver->phone_number;
            $_email_address = $_driver->email_address;
            $_directory = $_driver->directory;
            
            // Delete carrier
            $_return &= $this->drivers->delete($_driver);
            
            // Detele contact info
            $_return |= $this->directoryContactInformations->deleteAll(['id_contact_information' => $_phone_number]);
            $_return |= $this->directoryContactInformations->deleteAll(['id_contact_information' => $_email_address]);
            
            // Delete directory
            $_return |= $this->directories->deleteAll(['id_directory' => $_directory]);
            
            return $_return;
        }
        return false;
    }
    
    public function initialize(array $config = []) {
        parent::initialize($config);
        
        // Init tables
        $this->carriers = TableRegistry::getTableLocator()->get('Carriers');
        $this->drivers = TableRegistry::getTableLocator()->get('CarrierDrivers');
        $this->directories = TableRegistry::getTableLocator()->get('Directories');
        $this->directoryContactInformations = TableRegistry::getTableLocator()->get('DirectoryContactInformations');
    }
    
    public function update($id_carrier, $data){
        $_carrier = $this->carriers->get($id_carrier);
        if(is_object($_carrier)){
            // Update directory
            $_directory = $this->directories->get($_carrier->directory);
            $_directory->title = $data['title'];
            $_directory->fullname = $data['fullname'];
            $_directory->description = $data['description'];
            $this->directories->save($_directory);
            
            // Update phone number
            $_phone_number = $this->directoryContactInformations->get($_carrier->booking_phone_number);
            $_phone_number->contact_info = $data['booking_phone_number'];
            $this->directoryContactInformations->save($_phone_number);
            
            // Update email address
            $_email_address = $this->directoryContactInformations->get($_carrier->booking_mail_address);
            if(array_key_exists('booking_mail_address', $data)){
                $_email_address->contact_info = $data['booking_mail_address'];
                $this->directoryContactInformations->save($_email_address);
            } else {
                $_carrier->booking_mail_address = null;
                $this->carriers->save($_carrier);
                $this->directoryContactInformations->delete($_email_address);
            }
            return $_carrier;    
        }
        return null;
    }
    
    public function updateDriver($id_carrier_driver, $data){
        $_driver = $this->drivers->get($id_carrier_driver);
        if(is_object($_driver)){
            // Update directory
            $_directory = $this->directories->get($_driver->directory);
            $_directory->title = $data['title'];
            $_directory->fullname = $data['fullname'];
            $_directory->description = $data['description'];
            $this->directories->save($_directory);
            
            // Update phone number
            $_phone_number = $this->directoryContactInformations->get($_driver->phone_number);
            $_phone_number->contact_info = $data['phone_number'];
            $this->directoryContactInformations->save($_phone_number);
            
            // Update email address
            $_email_address = $this->directoryContactInformations->get($_driver->email_address);
            if(array_key_exists('email_address', $data)){
                $_email_address->contact_info = $data['email_address'];
                $this->directoryContactInformations->save($_email_address);
            } else {
                $_driver->mail_address = null;
                $this->drivers->save($_driver);
                $this->directoryContactInformations->delete($_email_address);
            }
            return $_driver;    
        }
        
            
    }
}
