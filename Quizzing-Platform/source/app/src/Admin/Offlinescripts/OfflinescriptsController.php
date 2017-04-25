<?php

/**
 * OfflinescriptsController - Handles all offline scripts
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Offlinescripts;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class OfflinescriptsController {

    protected $app;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;

        // HTTP codes
        $this->notFound = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->nocontent = $this->app['cache']->fetch('HTTP_NOCONTENT');
        $this->created = $this->app['cache']->fetch('HTTP_CREATED');
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to read the product to taxonomy mapping and store the relationship and also stores the taxonomy
     * @Return : Returns null
     */
    public function readTaxonomy() {

        $taxonomy = $this->app['offlinescripts.service']->readTaxonomy();

        if (!empty($taxonomy)) {

            // Return the success message. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['READ_TAXONOMY_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc : Controller method to search for the concept ids and relational concept ids in the solr.
     * @param Request $request
     * @return type
     */
    public function solrSearch(Request $request) {

        $conceptId = trim($request->get('conceptId'));

        $conceptType = trim($request->get('conceptType'));

        $searchResults = $this->app['offlinescripts.service']->solrSearch($conceptId, $conceptType);

        if (!empty($searchResults)) {

            // Return the success message. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($searchResults, $this->success);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['READ_SOLR_ERROR']);
            return $response;
        }
    }

    /**
     * search the item results by FULLTEXT/QB/SNOMED
     * @param Request $request
     * @return type
     */
    public function itemSolrSearch(Request $request) {

        //Concept id or fulltext search string
        $conceptId = trim($request->query->get('q'));

        //Concept type (FULLTEXT/QB/SNOMED or empty)
        $conceptType = trim($request->query->get('qMode'));
        
        $searchResults = $this->app['offlinescripts.service']->itemSolrSearch($conceptId, $conceptType);

        if (!empty($searchResults)) {

            // Return the success message. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($searchResults, $this->success);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['READ_SOLR_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc : Controller method to index the question to solr.
     * @param Request $request
     * @return type
     */
    public function solrIndex(Request $request) {

        $itemId = $request->get('itemId');

        $document = $this->app['offlinescripts.service']->solrIndex($itemId);

        if ($document) {

            // Return the success message. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->created);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INDEX_SOLR_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc : Controller method to delete the question from solr.
     * @param Request $request
     * @return type
     */
    public function solrDelete(Request $request) {

        $itemId = $request->get('itemId');

        $document = $this->app['offlinescripts.service']->solrDelete($itemId);

        if ($document) {

            // Return the success message. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['DELETE_SOLR_ERROR']);
            return $response;
        }
    }

}
