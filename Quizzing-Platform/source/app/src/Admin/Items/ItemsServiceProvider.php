<?php

/**
 * ItemsServiceProvider - It's a model service provider file to handle the Question/Item module services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

// Declare namespaces
namespace QuizzingPlatform\Admin\Items;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

class ItemsServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        // Controller service registry.
        $app['items.controller'] = function() use ($app) { 
            return new ItemsController($app);
        };

        // Repository/Model service registry.
        $app['items.repository'] = function() use ($app) { 
            return new ItemsRepository($app);
        }; 
        
        // Business logic service registry.
        $app['items.service'] = function() use ($app) {
            return new ItemsService($app);
        };
    }

   
}
