<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Design of "Stubs Module" using silex framework.
 */
namespace QuizPlat\EndUser\Items;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

/*
 * Classname : StubsServiceProvider
 * Interfaces used : ServiceProviderInterface
 * controller class which extends pimple service provider interface and it registers the classes as a service.
 */

class ItemsServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        $app['items.controller'] = function() use ($app) {
            return new ItemsController($app);
        };

    }

    public function boot(Application $app)
    {

    }
}
