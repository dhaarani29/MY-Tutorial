<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Controller provider for Tests
 */


namespace QuizPlat\EndUser\Tests;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : TestsControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for stubs module.
 */

class TestsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

	// Write all your routing path along with proper http methods.        
        $controllers->get("api/tests/{id}/progress/{client_user_id}", 'tests.controller:getTestsProgress');
        
        $controllers->get("api/tests/{id}/progress", 'tests.controller:getAllTestsProgress');

		// Write all your routing path along with proper http methods.        
        $controllers->get("/api/tests", 'tests.controller:getTestList');

        $controllers->get("/api/tests/{id}", 'tests.controller:getTestById');

        $controllers->post("/api/tests", 'tests.controller:addTest');

        $controllers->put("/api/tests/{id}", 'tests.controller:updateTest');

        $controllers->delete("/api/tests/{id}", 'tests.controller:deleteTestById');
        
        return $controllers;
    }

}
