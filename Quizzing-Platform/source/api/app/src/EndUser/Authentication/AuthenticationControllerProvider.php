<?php

/*
 * V1.0 Silex Authentication module
 * @Author : Jagadeeshraj V S
 * @Date : 30-08-2016
 * @Puropose : Stub Apis for "Authentication Module" using silex framework.
 */


namespace QuizPlat\EndUser\Authentication;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

/*
 * Classname : AuthenticationControllerProvider;
 * Interfaces used : ControllerProviderInterface
 * This uses ControllerProviderInterface's connect method to define all routings for authentication module.
 */

class AuthenticationControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        /** @var \Silex\ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

		// Write all your routing path along with proper http methods.        
        $controllers->get("/api/login", 'authentication.controller:login');
       

        return $controllers;
    }

}
