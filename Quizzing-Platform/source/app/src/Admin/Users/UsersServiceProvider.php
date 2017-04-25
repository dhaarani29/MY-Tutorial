<?php

/**
 * UsersServiceProvider - Handles the Users/Groups/Roles module services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Users;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UsersServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['users.controller'] = function() use ($app) {
            return new UsersController($app);
        };

        // Repository/Model service registry.
        $app['users.repository'] = function() use ($app) {
            return new UsersRepository($app);
        };

        // Business logic service registry.
        $app['users.service'] = function() use ($app) {
            return new UsersService($app);
        };
    }

}
