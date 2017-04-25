<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Dhaarani S
 * @Date : 30-08-2016
 * @Puropose : Admin Service provider  for metadata Api
 */

namespace QuizPlat\Admin\Metadata;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

/*
 * Classname : MetadataServiceProvider
 * Interfaces used : ServiceProviderInterface
 * controller class which extends pimple service provider interface and it registers the classes as a service.
 */

class MetadataAdminServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        $app['metadataadmin.controller'] = function() use ($app) {
            return new MetadataAdminController($app);
        };

    }

    public function boot(Application $app)
    {

    }
}
