<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Design of "Stubs Module" using silex framework.
 */


namespace QuizPlat\Admin\Items;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : StubsControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class ItemsAdminControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

	// Write all your routing path along with proper http methods.        
        $controllers->post("/api/items/", 'itemsadmin.controller:postItem');
        $controllers->put("/api/items/{id}", 'itemsadmin.controller:updateItem');
        $controllers->delete("/api/items/{id}", 'itemsadmin.controller:deleteItem');
       

        return $controllers;
    }

}
