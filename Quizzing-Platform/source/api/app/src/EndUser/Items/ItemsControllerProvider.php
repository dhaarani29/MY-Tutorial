<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Design of "Stubs Module" using silex framework.
 */


namespace QuizPlat\EndUser\Items;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : StubsControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class ItemsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

		// Write all your routing path along with proper http methods.        
        $controllers->get("/api/items/", 'items.controller:getItems');
        $controllers->get("/api/items/{id}", 'items.controller:findItemById');

       

        return $controllers;
    }

}
