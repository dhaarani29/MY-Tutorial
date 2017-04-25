<?php

/**
 * OfflinescriptsControllerProvider - Class to handle the offline scripts routing.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Offlinescripts;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class OfflinescriptsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        // Router to read product and taxonomy details and store it to quizzing platform
        $controllers->get('/api/silverchairtaxonomy', 'offlinescripts.controller:readTaxonomy');

        ###################################### SOLR ROUTERS ##############################################################
        // Router to add all the questions in the system to solr
        $controllers->post('/api/item/solr-index', 'offlinescripts.controller:solrIndex');

        // Router to add the document to solr for a specific question id passed.
        $controllers->post('/api/item/solr-index/{itemId}', 'offlinescripts.controller:solrIndex');

        // Router to get get search results from solr
        $controllers->get('/api/item/solr-search/{conceptId}/{conceptType}', 'offlinescripts.controller:solrSearch');

        // Router to deelte the document to solr for a specific question id passed.
        $controllers->delete('/api/item/solr-delete/{itemId}', 'offlinescripts.controller:solrDelete');

        // Router to get FULLTEXT/QB/SNOMED search results from item solr
        $controllers->get('/api/item/item-search', 'offlinescripts.controller:itemSolrSearch');


        return $controllers;
    }

}
