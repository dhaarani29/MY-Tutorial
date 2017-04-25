<?php

namespace QuizzingPlatform\Admin\Login;

use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SecurityJWTServiceProvider;
use QuizzingPlatform\Admin\Login\LoginService;
use QuizzingPlatform\Admin\Login\LoginControllerProvider;
use QuizzingPlatform\Admin\Login\LoginServiceProvider;

class LoginBuilder {

    public static function mountLoginProvider(Application $app) {

        //create security.jwt identifier to register SecurityJWTServiceProvider
        //Specify access token expiration time 
        
        $app['security.jwt'] = [
            'life_time' => $app['cache']->fetch('accessTokenLifeTime'),
            'secret_key' => $app['cache']->fetch('secretTokenSignatureKey'),
        ];

        //Register JWt service provider
        $app->register(new SecurityJWTServiceProvider());

        // Create LoginService objet to use in SecurityServiceProvider
        $app['users'] = function () use ($app) {
            return new LoginService($app);
        };

        // Register SecurityServiceProvider for api path.
        $app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'api' => array(
                    'pattern' => '^/api',
                    'stateless' => true,
                ),
                'default' => array(
                    'pattern' => '^.*$',
                    'anonymous' => true,
                    'users' => $app['users']
                ),
            )
        ));

        //Register login module service and controller providers
        $app->register(new LoginServiceProvider());

        $app->mount('/', new LoginControllerProvider());
    }

}

?>
