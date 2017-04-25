<?php

/**
 * MetadataServiceProvider - Handles the Metadata module services.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * 
 */

namespace QuizzingPlatform\Admin\Metadata;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MetadataServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['metadata.controller'] = function() use ($app) {
            return new MetadataController($app);
        };

        // Repository/Model service registry.
        $app['metadata.repository'] = function() use ($app) {
            return new MetadataRepository($app);
        };

        // Business logic service registry.
        $app['metadata.service'] = function() use ($app) {
            return new MetadataService($app);
        };
    }

}
