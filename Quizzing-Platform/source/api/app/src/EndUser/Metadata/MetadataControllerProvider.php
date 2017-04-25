<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Dhaarani S
 * @Date : 30-08-2016
 * @Puropose : Controller provider for Metadata Api
 */


namespace QuizPlat\EndUser\Metadata;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : MetadataControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class MetadataControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

	//Fetch all metadata
	$controllers->get("/api/metadata",'metadata.controller:getMetadata');
       
	//Fetch metadata based on id
	$controllers->get("/api/metadata/{id}",'metadata.controller:getMetadataById');
        
		
        return $controllers;
    }

}
