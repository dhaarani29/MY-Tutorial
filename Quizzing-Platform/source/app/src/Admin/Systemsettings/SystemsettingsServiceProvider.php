<?php

/**
 * SystemsettingsServiceProvider - Class to register system settings services.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * 
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Systemsettings;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SystemsettingsServiceProvider implements ServiceProviderInterface {

    public function register(Container $app) {

        // Controller service registry.
        $app['systemsettings.controller'] = function() use ($app) {
            return new SystemsettingsController($app);
        };

        // Repository/Model service registry.
        $app['systemsettings.repository'] = function() use ($app) {
            return new SystemsettingsRepository($app);
        };

        // Business logic service registry.
        $app['systemsettings.service'] = function() use ($app) {
            return new SystemsettingsService($app);
        };

        // Call setconfig method to set few configurations required and store them in to cache.
        $app['systemsettings.repository']->setConfigs();
    }

}
