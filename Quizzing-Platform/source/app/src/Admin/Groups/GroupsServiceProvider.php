<?php

/**
 * GroupsServiceProvider - Handles the groups module services.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 * 
 */

namespace QuizzingPlatform\Admin\Groups;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class GroupsServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['groups.controller'] = function() use ($app) {
            return new GroupsController($app);
        };

        // Repository/Model service registry.
        $app['groups.repository'] = function() use ($app) {
            return new GroupsRepository($app);
        };

        // Business logic service registry.
        $app['groups.service'] = function() use ($app) {
            return new GroupsService($app);
        };
    }

}