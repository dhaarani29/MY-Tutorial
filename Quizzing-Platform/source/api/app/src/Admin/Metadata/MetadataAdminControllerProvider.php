<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Dhaarani S
 * @Date : 30-08-2016
 * @Puropose : Admin Controller Provider  for Metadata Api
 */


namespace QuizPlat\Admin\Metadata;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : MetadataControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class MetadataAdminControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

	//create  metadata
	$controllers->post("/api/metadata",'metadataadmin.controller:createMetadata');
        //Update metadata details
	$controllers->put("/api/metadata/{id}",'metadataadmin.controller:updateMetadata');
         //Delete metadata details
	$controllers->delete("/api/metadata/{id}",'metadataadmin.controller:deleteMetadata');
       
	
		
       
        return $controllers;
    }

}
