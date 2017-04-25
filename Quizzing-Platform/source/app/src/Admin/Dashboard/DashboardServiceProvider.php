<?php

/**
 * DashboardServiceProvider - It's a model service provider file to handle the Dashboard module services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Srinivasu M
 */

// Declare namespaces
namespace QuizzingPlatform\Admin\Dashboard;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

class DashboardServiceProvider implements ServiceProviderInterface
{
	
    public function register(Container $app)
    {
        
        // Controller service registry.
        $app['dashboard.controller'] = function() use ($app) { 
            return new DashboardController($app);
        };

        // Repository/Model service registry.
        $app['dashboard.repository'] = function() use ($app) { 
            return new DashboardRepository($app);
        }; 
        
        // Business logic service registry.
        $app['dashboard.service'] = function() use ($app) {
            return new DashboardService($app);
        };
    }

   
}
