<?php

/**
 * TestsServiceProvider - It's a model service provider file to handle the Tests/Quiz module services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

// Declare namespaces
namespace QuizzingPlatform\Admin\Tests;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

class TestsServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        // Controller service registry.
        $app['tests.controller'] = function() use ($app) { 
            return new TestsController($app);
        };

        // Repository/Model service registry.
        $app['tests.repository'] = function() use ($app) { 
            return new TestsRepository($app);
        }; 
        
        // Business logic service registry.
        $app['tests.service'] = function() use ($app) {
            return new TestsService($app);
        };
    }

   
}
