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
 * Description of ServiceComponent
 *
 * @author RSMandimby
 */
class ServiceComponent extends Component{
    public $components = [
        'RSTO'
    ];
    
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
    
    /**
     * Providers table
     * @var \Cake\ORM\Table 
     */
    protected $providers;
    
    /**
     * Service providers table
     * @var \Cake\ORM\Table
     */
    protected $serviceProviders;
    
    public function add($data){
        // Step 1: create directory
        $_directory = $this->directories->NewEntity($data);
        if(is_object($_directory)){
            // Attempt to save
            if($this->directories->save($_directory) !== null){
                $_contact_infos = $this->addBookingContactInformation($data, $_directory);
                $_provider = $this->addProvider($data, $_directory, $_contact_infos);
                return $this->addServiceProvider($data, $_provider);
            }
        }
    }
    
    private function addBookingContactInformation($data, $directory){
        $_return = [
            'email' => null,
            'phone' => null
        ];
        
        if(strlen(trim($data['booking_phone_number'])) > 0){
            // Step 2: create phone number contact informations
            $_booking_phone_number = $this->directoryContactInformations->NewEntity([
                'directory' => $directory->id_directory,
                'contact_info' => $data['booking_phone_number'],
                'label' => 'Booking',
                'type' => RSTOComponent::PHONE_NUMBER_ID_SELECT_OPTION
            ]);
            $this->directoryContactInformations->save($_booking_phone_number);
            $_return['phone'] = $_booking_phone_number->id_contact_information;
        }
        
        if(strlen(trim($data['booking_mail_address'])) > 0){
            // Step 3: create email address contact informations
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
    
    private function addProvider($data, $directory, $contact_infos){
        $_provider = $this->providers->NewEntity([
            'directory' => $directory->id_directory,
            'booking_phone_number' => $contact_infos['phone'],
            'booking_email_address' => $contact_infos['email'],
            'must_book' => array_key_exists('must_book', $data) ? 1 : 0
        ]);
        return $this->providers->save($_provider);
    }
    
    private function addServiceProvider($data, $provider){
        $_adult_cost_price = $this->RSTO->RemoveThousandSeparator($data['adult_cost_price']);
        $_service_provider = $this->serviceProviders->newEntity([
            'service' => $data['service'],
            'provider' => $provider->id_provider,
            'is_default' => array_key_exists('is_default', $data) ? 1 : 0,
            'adult_cost_price' => $_adult_cost_price,
            'children_cost_price' => $data['children_cost_price'] === null ? $_adult_cost_price : $this->RSTO->RemoveThousandSeparator($data['children_cost_price'])
        ]);
        return $this->serviceProviders->save($_service_provider);
    }
    
    public function choose($data){
        $_provider = (object)['id_provider' => $data['provider']];
        return $this->addServiceProvider($data, $_provider);
    }
    
    public function delete($id){
        $_service_provder = $this->serviceProviders->get($id);
        $_provider = $this->providers->get($_service_provder->provider);
        $_directory = $this->directories->get($_provider->directory);
        $_phone_number = is_null($_provider->booking_phone_number) ? null : $this->directoryContactInformations->get($_provider->booking_phone_number);
        $_email_address = is_null($_provider->booking_email_address) ? null : $this->directoryContactInformations->get($_provider->booking_email_address);
        
        // Delete service provider
        $this->serviceProviders->delete($_service_provder);
        
        // Delete provider
        $this->providers->delete($_provider);
        
        // Delete contact information
        if(is_object($_phone_number)){
            $this->directoryContactInformations->delete($_phone_number);
        }
        if(is_object($_email_address)){
            $this->directoryContactInformations->delete($_email_address);
        }
        
        // Delete directory
        return $this->directories->delete($_directory);
    }
    
    public function initialize(array $config): void {
        parent::initialize($config);
        
        // Init table
        $this->directories = TableRegistry::getTableLocator()->get('Directories');
        $this->directoryContactInformations = TableRegistry::getTableLocator()->get('DirectoryContactInformations');
        $this->providers = TableRegistry::getTableLocator()->get('Providers');
        $this->serviceProviders = TableRegistry::getTableLocator()->get('ServicesProviders');
    }
    
    public function update($id, $data){
        $_service_provider = $this->serviceProviders->get($id);
        if(is_object($_service_provider)){
            $_service_provider->adult_cost_price = $this->RSTO->RemoveThousandSeparator($data['adult_cost_price']);
            $_service_provider->children_cost_price = $data['adult_cost_price'] === '' ? $_service_provider->adult_cost_price : $this->RSTO->RemoveThousandSeparator($data['children_cost_price']);
            $this->serviceProviders->save($_service_provider);
            
            $_provider = $this->providers->get($_service_provider->provider);
            $_directory = $this->directories->get($_provider->directory);
            
            // Update directory
            $_directory->title = $data['title'];
            $_directory->fullname = $data['fullname'];
            $_directory->description = $data['description'];
            $this->directories->save($_directory);
            
            // Update contact informations
            $this->updateBookingContactInformation($_provider, $data);
            
            return $_provider;
        }
        return null;
    }
    
    private function updateBookingContactInformation($provider, $data){
        $_phone_number = is_null($provider->booking_phone_number) ? null : $this->directoryContactInformations->get($provider->booking_phone_number);
        $_email_address = is_null($provider->booking_email_address) ? null : $this->directoryContactInformations->get($provider->booking_email_address);
        
        if(strlen(trim($data['booking_phone_number'])) > 0){
            $_phone_number = is_null($_phone_number) ? $this->directoryContactInformations->newEntity([
                'directory' => $provider->directory,
                'label' => 'Booking',
                'type' => RSTOComponent::PHONE_NUMBER_ID_SELECT_OPTION
            ]) : $_phone_number;
            $_phone_number->directory = $provider->directory;
            $_phone_number->contact_info = $data['booking_phone_number'];
            $this->directoryContactInformations->save($_phone_number);
        } else if(!is_null($provider->booking_phone_number)){
            // Set directory booking phone number to null
            $provider->booking_phone_number = null;
            $this->providers->save($provider);
            
            // Remove directory contact information if exists
            $this->directoryContactInformations->deleteAll(['id_contact_information' => $_phone_number->id_contact_information]);
        }
        
        if(strlen(trim($data['booking_mail_address'])) > 0){
            $_email_address = is_null($_email_address) ? $this->directoryContactInformations->newEntity([
                'directory' => $provider->directory,
                'label' => 'Booking',
                'type' => RSTOComponent::EMAIL_ID_SELECT_OPTION
            ]) : $_email_address;
            $_email_address->contact_info = $data['booking_mail_address'];
            $this->directoryContactInformations->save($_email_address);
        } else if(!is_null($provider->booking_email_address)) {
            // Set directory booking email address to null
            $provider->booking_email_address = null;
            $this->providers->save($provider);
            
            // Remove directory contact information if exists
            $this->directoryContactInformations->deleteAll(['id_contact_information' => $_email_address->id_contact_information]);
        }
    }
}
