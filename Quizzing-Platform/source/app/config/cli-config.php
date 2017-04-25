<?php

use Silex\Application;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Juuuuuu\Silex\YamlConfigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

require __DIR__ . '/../vendor/autoload.php';

// Option - 1
// require __DIR__.'/../web/index_api.php';
// Option - 2

$app = new Silex\Application();
$app->register(new YamlConfigServiceProvider(__DIR__ . '/../config/config.yml'));
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => $app['database.doctrine']
));

$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../src/Entity"), $isDevMode, null, null, false);

// For yml format 
/* $paths = array($app['orm.em.options']['mappings'][0]['path']);
  $config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode); */

$em = EntityManager::create($app['db.options'], $config);

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
        ));

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em);





