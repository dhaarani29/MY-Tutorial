<?php

namespace QuizzingPlatform\Services;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Solarium\Core\Client\Client;

/**
 * @DESC Solr provider to initialise the solr client.
 */
class SolrConfigurationProvider implements ServiceProviderInterface {

    /**
     * {@inheritdoc}
     */
    public function register(Container $app) {

        // initialize the solr client
        $app['solr.client'] = function($app) {

            $config = array(
                'endpoint' => array(
                    'localhost' => array(
                        'host' => $app['solr.itemindex']['host'],
                        'port' => $app['solr.itemindex']['port'],
                        'path' => $app['solr.itemindex']['path'],
                    )
                )
            );

            // create a client instance
            return new Client($config);
        };

        // initialize the solr client1 for metadata / Taxonomy Search
        $app['solr.client1'] = function($app) {

            $config = array(
                'endpoint' => array(
                    'localhost' => array(
                        'host' => $app['solr.metadataindex']['host'],
                        'port' => $app['solr.metadataindex']['port'],
                        'path' => $app['solr.metadataindex']['path'],
                    )
                )
            );

            // create a client instance
            return new Client($config);
        };
    }

}
