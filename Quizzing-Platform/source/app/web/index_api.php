<?php
error_reporting(E_ERROR);
ini_set('display_errors', 0); 
date_default_timezone_set("US/Central"); 

// Load autoload
require_once __DIR__.'/../vendor/autoload.php';

// Call bootstrap file to start the silex application.
$app = new \QuizzingPlatform\Application();

// If $app_env is set with "test", then retun $app else run the $app.
if ('test' == $app_env)
    return $app;
else
    $app->run();

?>


