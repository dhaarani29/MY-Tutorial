<?php

namespace QuizzingPlatform\Services;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Provider\MonologServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\LineFormatter;
use Monolog\Logger;
use Monolog\Formatter\LogstashFormatter; //Added by Srinivasu
use Elasticsearch\ClientBuilder;

class QuizPlatformMonologServiceProvider implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $app) {

        //Register Silex Monolog Service Provider
        $app->register(new MonologServiceProvider(), array(
            'monolog.logfile' => $app['config']['logfile'] // Logfile defined in config environment setups.
        ));

        // initialize the logger
        $app['mono.log'] = function($app) {
            return new Logger('log');
        };

        //initiate the Log Service
        $app['log'] = function($app) {
            return new Log($app);
        };

        // ELK configuration settings, Just need to set only the setHosts value pointing to Elasticsearch IP & PORT numbers
        if ($app['elkConfig']['enableElkLogging'] == 'yes') {


            try {
                $app['elk.obj'] = function($app) {

                    return \Elasticsearch\ClientBuilder::create()
                                    //->setHosts(['http://172.16.1.55:9200'])
                                    ->setHosts([$app['elk']['host'] . ':' . $app['elk']['port']]) // changed by shree, value added in config.yml
                                    ->setSSLVerification(false)
                                    ->build();
                };
            } catch (\Elasticsearch\Common\Exceptions\NoNodesAvailableException $e) {
                error_log("Error in connecting to the ELK server..!", 1, "elangovan.n@impelsys.com");
            }
        }

        // Stream Handlers
        $app['mono.log']->pushHandler(new StreamHandler($app['config']['logfile'], $app['config']['logger.level'])); // Logfile,loggerlevel defined in config environment setups.
        //$app['qp.log']->pushHandler(new StreamHandler("/logs/laravel_warning.log", Logger::WARNING));
        //$app['qp.log']->pushHandler(new StreamHandler("/logs/laravel_error.log", Logger::ERROR));
    }

}
