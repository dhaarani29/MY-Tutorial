<?php

/**
 * SystemsettingsControllerProvider - It's the controller class file to handle the system settings routing. All the Routings along with Http methods defined here.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * 
 */

// Declare namespaces
namespace QuizzingPlatform\Admin\Systemsettings;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class SystemsettingsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        $controllers = $app['controllers_factory'];
        
        // Get UI configurations.
        $controllers->get('/api/systemconfig', 'systemsettings.controller:getUIConfig'); 
        
        //Get menus list.
        $controllers->get('/api/adminmenu', 'systemsettings.controller:getMenusList'); 
        
        //Clear the cache.
        $controllers->get('/api/clearcache', 'systemsettings.controller:clearCache'); 
               
        // Get Country list.
        $controllers->get('/api/countrylist', 'systemsettings.controller:getCountriesList');
        
        // Get States list.
        $controllers->get('/api/stateslist/{country_id}', 'systemsettings.controller:getStatesList');
        
        // Get Group list.
        $controllers->get('/api/grouplist', 'systemsettings.controller:getGroupsList');
        
        // Get Group list by Id.
        $controllers->get('/api/grouplist/{id}', 'systemsettings.controller:getGroupsListById');
        
        // Get Roles list.
        $controllers->get('/api/roleslist', 'systemsettings.controller:rolesInformation');
        
        // Get Roles list by Id.
        $controllers->get('/api/roleslist/{id}', 'systemsettings.controller:rolesInformationById');
        
        // Get status list 
        $controllers->get('api/status','systemsettings.controller:getAllStatus');
        
        //Get All Usertype
        $controllers->get('api/usertype','systemsettings.controller:getUserType');
        
        //Generate the client_secret_code
        $controllers->get('api/clientsecretcode','systemsettings.controller:generateVendorSecretKey');
        
        //Get the system configuration setting values
        $controllers->get('api/systemconfiguration','systemsettings.controller:getSystemconfiguration');
        
        //PUT system settings 
        $controllers->put('api/systemconfiguration','systemsettings.controller:updateSystemconfiguration');
        
        return $controllers;
    }

}
