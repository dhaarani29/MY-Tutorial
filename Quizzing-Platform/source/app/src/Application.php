<?php

/**
 * Application - It's a Application class which handles all the registry part, mounting the modules..
 *
 * @ Copyright 2016 Impelsys India Pvt Ltd.
 * @ Author : Shreelakshmi U
 */
// Declare namespace 

namespace QuizzingPlatform;

// Load required silex supported modules
use Silex\Application as SilexApplication;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Juuuuuu\Silex\YamlConfigServiceProvider;
use Moust\Silex\Provider\CacheServiceProvider;
use QuizzingPlatform\Services\QuizPlatformMonologServiceProvider;
use QuizzingPlatform\Services\PDOServiceProvider;
use QuizzingPlatform\Services\SolrConfigurationProvider;
use Igorw\Silex\ConfigServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\PHPExcel;
use QuizzingPlatform\Services\CustomErrorHandler;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
// Include module wise classes
use QuizzingPlatform\Admin\Items\ItemsControllerProvider;
use QuizzingPlatform\Admin\Items\ItemsServiceProvider;
use QuizzingPlatform\Admin\Metadata\MetadataControllerProvider;
use QuizzingPlatform\Admin\Metadata\MetadataServiceProvider;
use QuizzingPlatform\Admin\Systemsettings\SystemsettingsControllerProvider;
use QuizzingPlatform\Admin\Systemsettings\SystemsettingsServiceProvider;
use QuizzingPlatform\Admin\Users\UsersControllerProvider;
use QuizzingPlatform\Admin\Users\UsersServiceProvider;
use QuizzingPlatform\Admin\Roles\RolesControllerProvider;
use QuizzingPlatform\Admin\Roles\RolesServiceProvider;
use QuizzingPlatform\Admin\Groups\GroupsControllerProvider;
use QuizzingPlatform\Admin\Groups\GroupsServiceProvider;
use QuizzingPlatform\Admin\Itembanks\ItembanksServiceProvider;
use QuizzingPlatform\Admin\Itembanks\ItembanksControllerProvider;
use QuizzingPlatform\Admin\Dashboard\DashboardServiceProvider;
use QuizzingPlatform\Admin\Dashboard\DashboardControllerProvider;
use QuizzingPlatform\Admin\Tests\TestsControllerProvider;
use QuizzingPlatform\Admin\Tests\TestsServiceProvider;
use QuizzingPlatform\Admin\Login\LoginBuilder;
use QuizzingPlatform\Admin\Offlinescripts\OfflinescriptsServiceProvider;
use QuizzingPlatform\Admin\Offlinescripts\OfflinescriptsControllerProvider;
use QuizzingPlatform\Admin\Reports\ReportsServiceProvider;
use QuizzingPlatform\Admin\Reports\ReportsControllerProvider;
use Solarium\Core\Client\Client;

// Registered error handlers to display the error.
ErrorHandler::register();
ExceptionHandler::register();

/**
 * Class to register all the service providers and to mount the routers.
 */
class Application extends SilexApplication {

    /**
     * @param : null
     * @Desc : Constructor method.
     * @Return : null
     */
    // public $redisConnect;

    public function __construct() {

        parent::__construct();

        // Register silex providers
        $this->registerProviders($this);

        // Register Quizzing Platform module wise services
        $this->registerModuleServices($this);

        //Check login, if acces token sent, then check for valid access token
        LoginBuilder::mountLoginProvider($this);

        // Register Quizzing Platform module wise controller providers fro routing
        $this->registerRoutes($this);

        //Display the custom errors 
        //new CustomErrorHandler($this);

        /*  $this->after(function (Request $request, Response $response) {
          $response->headers->set('Access-Control-Allow-Origin', '*');
          }); */
    }

    /**
     * @param : Silex application
     * @Desc : Register all the service providers required.
     * @Return : null
     */
    protected function registerProviders(Application $app) {

        // enable/disable the debug mode
        $env = 'dev';
        $app->register(new ConfigServiceProvider(__DIR__ . "/../config/$env.php"));

        // Language file registration,
        $languageName = 'en';
        $app->register(new ConfigServiceProvider(__DIR__ . "/../src/Lang/$languageName.php"));

        // Register Yml config service provider
        $app->register(new YamlConfigServiceProvider(__DIR__ . '/../config/config.yml'));

        // Register Routing service provider
        $app->register(new RoutingServiceProvider());

        // Register service controller provider
        $app->register(new ServiceControllerServiceProvider());

        //Declare to global variables
        $this->redisConnect = $app['redis']['host'];
        $this->redisPort = $app['redis']['port'];

        // Register Redis cache service provider
        $app->register(new CacheServiceProvider(), array(
            'cache.options' => array(
                'driver' => 'redis',
                'redis' => function () {
                    $redis = new \Redis;
                    $redis->connect($this->redisConnect, $this->redisPort);
                    return $redis;
                }
            )
        ));



        // Monolog wrapper class register
        $app->register(new QuizPlatformMonologServiceProvider());

        // Doctrine service provider 
        $app->register(new DoctrineServiceProvider(), array(
            'db.options' => $app['database.doctrine']
        ));

        //Register PDO service provider
        $app->register(new PDOServiceProvider(), array(
            'pdo.options' => $app['database.pdo']
        ));

        // Doctrine ORM service provider
        $app->register(new DoctrineOrmServiceProvider(), array(
            'db.orm.class_path' => __DIR__ . '/../vendor/doctrine/orm/lib',
            'db.orm.proxies_dir' => __DIR__ . '/../var/cache/doctrine/Proxy',
            'db.orm.proxies_namespace' => 'QuizzingPlatform\Proxy',
            'db.orm.auto_generate_proxies' => true,
            'orm.em.options' => array(
                'mappings' => array(
                    // Using actual filesystem paths
                    array(
                        'type' => 'annotation',
                        'namespace' => 'QuizzingPlatform\Entity',
                        'path' => __DIR__ . '/src/Entity',
                        'use_simple_annotation_reader' => false,
                    )
                ),
            ),
            'orm.custom.functions.string' => array(
                'md5' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'cast' => 'Oro\ORM\Query\AST\Functions\Cast',
                'group_concat' => 'Oro\ORM\Query\AST\Functions\String\GroupConcat',
                'concat_ws' => 'Oro\ORM\Query\AST\Functions\String\ConcatWs',
                'replace' => 'Oro\ORM\Query\AST\Functions\String\Replace'
            ),
            'orm.custom.functions.datetime' => array(
                'date' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'time' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'timestamp' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'convert_tz' => 'Oro\ORM\Query\AST\Functions\DateTime\ConvertTz'
            ),
            'orm.custom.functions.numeric' => array(
                'timestampdiff' => 'Oro\ORM\Query\AST\Functions\Numeric\TimestampDiff',
                'dayofyear' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'dayofweek' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'week' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'day' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'hour' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'minute' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'month' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'quarter' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'second' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'year' => 'Oro\ORM\Query\AST\Functions\SimpleFunction',
                'sign' => 'Oro\ORM\Query\AST\Functions\Numeric\Sign',
                'pow' => 'Oro\ORM\Query\AST\Functions\Numeric\Pow',
            )
        ));

        // Swift mailer for mail
        $app->register(new SwiftmailerServiceProvider());

        // Solr wrapper class register
        $app->register(new SolrConfigurationProvider());

        $app->register(new CorsServiceProvider(), [
            "cors.allowOrigin" => "*",
            //"cors.allowHeaders" => "Origin,X-Requested-With,Content-Type,Accept,Authorization",
            "cors.allowMethods" => "GET, POST, OPTIONS, PUT, DELETE",
            "cors.exposeHeaders" => "Authorization",
            "cors.allowCredentials" => true
        ]);

        $app["cors-enabled"]($app);
    }

    /**
     * @Desc : Register module level service providers.
     * @param \QuizzingPlatform\Application $app
     */
    protected function registerModuleServices(Application $app) {

        $app->register(new SystemsettingsServiceProvider());

        $app->register(new MetadataServiceProvider());

        $app->register(new ItemsServiceProvider());

        $app->register(new UsersServiceProvider());

        $app->register(new RolesServiceProvider());

        $app->register(new GroupsServiceProvider());

        $app->register(new ItembanksServiceProvider());


        $app->register(new DashboardServiceProvider());

        $app->register(new TestsServiceProvider());

        $app->register(new OfflinescriptsServiceProvider());

        $app->register(new ReportsServiceProvider());
    }

    /**
     * @Desc : Register module wise controller providers for routing
     * @param \QuizzingPlatform\Application $app
     */
    protected function registerRoutes(Application $app) {

        $app->mount('/', new MetadataControllerProvider());

        $app->mount('/', new ItemsControllerProvider());

        $app->mount('/', new SystemsettingsControllerProvider());

        $app->mount('/', new UsersControllerProvider());

        $app->mount('/', new RolesControllerProvider());

        $app->mount('/', new GroupsControllerProvider());

        $app->mount('/', new ItembanksControllerProvider());

        $app->mount('/', new DashboardControllerProvider());

        $app->mount('/', new TestsControllerProvider());

        $app->mount('/', new OfflinescriptsControllerProvider());

        $app->mount('/', new ReportsControllerProvider());
    }

}
