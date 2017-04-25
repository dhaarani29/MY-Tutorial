<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Controller provider for ItembankAdmin
 */


namespace QuizPlat\Admin\ItembankAdmin;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : ItembankAdminControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class ItembankAdminControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

	// Write all your routing path along with proper http methods.        
        $controllers->delete("api/itembanks/{id}", 'itembankadmin.controller:deleteItemBank');
        
        $controllers->post("api/itembanks", 'itembankadmin.controller:createItemBank');
        
        $controllers->put("api/itembanks/{id}", 'itembankadmin.controller:updateItemBank');
        
        return $controllers;
    }

}
