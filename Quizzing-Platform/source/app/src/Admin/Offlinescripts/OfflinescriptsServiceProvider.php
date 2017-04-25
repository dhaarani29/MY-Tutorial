<?php

/**
 * OfflinescriptsServiceProvider - It's a model service provider file to handle offline scripts module services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

// Declare namespaces
namespace QuizzingPlatform\Admin\Offlinescripts;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

class OfflinescriptsServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        // Controller service registry.
        $app['offlinescripts.controller'] = function() use ($app) { 
            return new OfflinescriptsController($app);
        };

        // Repository/Model service registry.
        $app['offlinescripts.repository'] = function() use ($app) { 
            return new OfflinescriptsRepository($app);
        }; 
        
        // Business logic service registry.
        $app['offlinescripts.service'] = function() use ($app) {
            return new OfflinescriptsService($app);
        };
    }

   
}
