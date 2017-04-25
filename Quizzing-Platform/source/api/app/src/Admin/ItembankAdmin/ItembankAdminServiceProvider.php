<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Design of "Stubs Module" using silex framework.
 */

namespace QuizPlat\Admin\ItembankAdmin;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface; 

/*
 * Classname : ItembankAdminServiceProvider
 * Interfaces used : ServiceProviderInterface
 * controller class which extends pimple service provider interface and it registers the classes as a service.
 */

class ItembankAdminServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        
        $app['itembankadmin.controller'] = function() use ($app) {
            return new ItembankAdminController($app);
        };

    }

    public function boot(Application $app)
    {

    }
}
