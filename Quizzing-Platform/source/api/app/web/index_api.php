<?php
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Bangkok");
require_once __DIR__.'/../vendor/autoload.php';
define('BASIC_AUTH_USER', 'quizzing');
define('BASIC_AUTH_PWD', 'quizzing');

// Load all supported components
use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;


// if($_SERVER['PHP_AUTH_USER'] != "testuser" && $_SERVER['PHP_AUTH_PW'] != "password"){
// 	print_r($_SERVER);die;
// 	echo "Wrong Credentials";die;
// }
// Registered error handlers to display the error.
ErrorHandler::register();
ExceptionHandler::register();  

// Intialise the silex application.
$app = new Silex\Application();

// Register routing service provider
$app->register(new RoutingServiceProvider()); 

// Register  service controller provider for controllers use.
$app->register(new ServiceControllerServiceProvider());

// Load Stubs Module supported components
use QuizPlat\EndUser\Authentication\AuthenticationControllerProvider;
use QuizPlat\EndUser\Authentication\AuthenticationServiceProvider;

// Load Quiz Module supported components
use QuizPlat\EndUser\Tests\TestsControllerProvider;
use QuizPlat\EndUser\Tests\TestsServiceProvider;

// // Load Stubs Module supported components
 use QuizPlat\EndUser\Items\ItemsControllerProvider;
 use QuizPlat\EndUser\Items\ItemsServiceProvider;

// Load Stubs Module supported components
use QuizPlat\EndUser\Itembank\ItembankControllerProvider;
use QuizPlat\EndUser\Itembank\ItembankServiceProvider;

// // Load Stubs Module supported components
use QuizPlat\EndUser\Metadata\MetadataControllerProvider;
use QuizPlat\EndUser\Metadata\MetadataServiceProvider;



// // Load Stubs Module supported components
 use QuizPlat\Admin\Items\ItemsAdminControllerProvider;
 use QuizPlat\Admin\Items\ItemsAdminServiceProvider;

// Load Stubs Module supported components
use QuizPlat\Admin\ItembankAdmin\ItembankAdminControllerProvider;
use QuizPlat\Admin\ItembankAdmin\ItembankAdminServiceProvider;

// // Load Stubs Module supported components
use QuizPlat\Admin\Metadata\MetadataAdminControllerProvider;
use QuizPlat\Admin\Metadata\MetadataAdminServiceProvider;


// Register authentication service provider where it registers the services for controllers.
$app->register(new AuthenticationServiceProvider());

// Register stubs service provider where it registers the services for controllers.
$app->register(new ItembankServiceProvider());

// Register quiz service provider where it registers the services for controllers.
$app->register(new TestsServiceProvider());

// Register quiz service provider where it registers the services for controllers.
$app->register(new ItemsServiceProvider());

// Register quiz service provider where it registers the services for controllers.
$app->register(new MetadataServiceProvider());



// Register stubs service provider where it registers the services for controllers.
$app->register(new ItembankAdminServiceProvider());

// Register quiz service provider where it registers the services for controllers.
$app->register(new ItemsAdminServiceProvider());

// Register quiz service provider where it registers the services for controllers.
$app->register(new MetadataAdminServiceProvider());

$app->register(new Basster\Silex\Provider\Swagger\SwaggerProvider(), [
    "swagger.servicePath" => __DIR__ . "/../src/EndUser",
]);


// Start mounting question bank module.
$app->mount('/', new ItembankControllerProvider()); 

// Start mounting authentication module.
$app->mount('/', new AuthenticationControllerProvider()); 

// Start mounting quiz module.
$app->mount('/', new TestsControllerProvider()); 

// Start mounting questions module.
$app->mount('/', new ItemsControllerProvider()); 

// Start mounting metadata module.
$app->mount('/', new MetadataControllerProvider()); 

// Start mounting question bank module.
$app->mount('/', new ItembankAdminControllerProvider()); 

// Start mounting questions module.
$app->mount('/', new ItemsAdminControllerProvider()); 

// Start mounting metadata module.
$app->mount('/', new MetadataAdminControllerProvider()); 

$app->run();

?>


