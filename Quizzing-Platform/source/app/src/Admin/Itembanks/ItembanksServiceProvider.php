<?php

/**
 * ItembanksServiceProvider - Handles the Itembanks module services.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 * 
 */

namespace QuizzingPlatform\Admin\Itembanks;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ItembanksServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['itemcollection.controller'] = function() use ($app) {
            return new ItembanksController($app);
        };

        // Repository/Model service registry.
        $app['itemcollection.repository'] = function() use ($app) {
            return new ItembanksRepository($app);
        };

        // Business logic service registry.
        $app['itemcollection.service'] = function() use ($app) {
            return new ItembanksService($app);
        };
    }

}
