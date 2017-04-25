<?php

/**
 * RolesServiceProvider - Handles the Roles module services.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 * 
 */

namespace QuizzingPlatform\Admin\Roles;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RolesServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['roles.controller'] = function() use ($app) {
            return new RolesController($app);
        };

        // Repository/Model service registry.
        $app['roles.repository'] = function() use ($app) {
            return new RolesRepository($app);
        };

        // Business logic service registry.
        $app['roles.service'] = function() use ($app) {
            return new RolesService($app);
        };
    }

}