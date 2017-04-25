<?php

/*
 * V1.0 Silex Authentication module
 * @Author : Jagadeeshraj V S
 * @Date : 30-08-2016
 * @Puropose : Stub Apis for "Authentication Module" using silex framework.
 */

namespace QuizPlat\EndUser\Authentication;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

/*
 * Classname : AuthenticationServiceProvider
 * Interfaces used : ServiceProviderInterface
 * controller class which extends pimple service provider interface and it registers the classes as a service.
 */

class AuthenticationServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        $app['authentication.controller'] = function() use ($app) {
            return new AuthenticationController($app);
        };

    }

    public function boot(Application $app)
    {

    }
}
