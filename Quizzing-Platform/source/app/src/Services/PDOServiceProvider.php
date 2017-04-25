<?php

namespace QuizzingPlatform\Services;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PDOServiceProvider implements ServiceProviderInterface {

    /**
     * Register the pdo connection
     *
     * @param Container $app
     */
    public function register(Container $app) {

        // Create pdo container
        $app['pdo'] = function ($app) {

            $options = $app['pdo.options'];

            // Assign DB connection information to variables
            $dsn = 'mysql:host=' . $options['host'] . ';dbname=' . $options['dbname'];
            $user = $options['user'];
            $password = $options['password'];

            // Create a new PDO object
            try {
                $dbh = new \PDO($dsn, $user, $password);
                return $dbh;
            } catch (PDOException $e) {
                $app['log']->writeLog('Connection failed: ' . $e->getMessage());
            }
        };
    }

}

?>
