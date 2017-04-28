<?php

// Initialize the doctrine tools

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

//Autoloading is taken care of by Composer.
require_once "vendor/autoload.php";

//Define the annotation file path , where to create entity files
$path = array(__DIR__ . "/src/Entity/");
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration($path, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

//Define Database configuration details:
$conn = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'doctrine_db',
    'user' => 'root',
    'password' => 'Impelsys1'
);

//obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
