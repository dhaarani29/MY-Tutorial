<?php

/**
 * LoginServiceProvider - It's a model service provider file to handle the Login module services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

// Declare namespaces
namespace QuizzingPlatform\Admin\Login;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

class LoginServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        // Controller service registry.
        $app['login.controller'] = function() use ($app) { 
            return new LoginController($app);
        };        
        
         // Login service registry.
        $app['login.service'] = function() use ($app) { 
            return new LoginService($app);
        };        
     
    }

   
}
