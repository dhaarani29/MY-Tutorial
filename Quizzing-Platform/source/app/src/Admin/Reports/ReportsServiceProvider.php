<?php

/**
 * ReportsServiceProvider - Handles the Reports module services.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 * 
 */

namespace QuizzingPlatform\Admin\Reports;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ReportsServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['reports.controller'] = function() use ($app) {
            return new ReportsController($app);
        };

        // Repository/Model service registry.
        $app['reports.repository'] = function() use ($app) {
            return new ReportsRepository($app);
        };

        // Business logic service registry.
        $app['reports.service'] = function() use ($app) {
            return new ReportsService($app);
        };
    }

}
