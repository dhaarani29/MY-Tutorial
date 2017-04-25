<?php

/**
 * MetadataControllerProvider - Handles the metadata module routing. All the Routings along with Http methods defined here.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Metadata;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class MetadataControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {


        $controllers = $app['controllers_factory'];

        //Listing Metadata Types
        $controllers->get('/api/metadatatypes', 'metadata.controller:getMetadataTypes');

        //Listing Metadata Data Types
        $controllers->get('/api/metadatadatatypes', 'metadata.controller:getMetadataDataTypes');

        //Metadata create with post method.
        $controllers->post('/api/metadata', 'metadata.controller:createMetadata');

        //Get the Metadata based on tag Id.
        $controllers->get('/api/metadata/{id}', 'metadata.controller:getMetadataById');

        //Get Metadata list. 
        $controllers->get('/api/metadata', 'metadata.controller:getMetadata');

        //Update Metadata tag with put method.
        $controllers->put('/api/metadata/{id}', 'metadata.controller:updateMetadata');

        //Soft delete Metadata based on tag Id
        $controllers->delete('/api/metadata/{id}', 'metadata.controller:deleteMetadata');

        //Get Mandatory metadata tags
        $controllers->get('/api/mandatorymetadata', 'metadata.controller:getMandatoryMetadata');

        // Get institutions list.
        $controllers->get('/api/institutions', 'metadata.controller:getInstitutions');

        // Get taxanomy.
        $controllers->get('/api/taxonomy', 'metadata.controller:getTaxonomy');

        $controllers->get('/api/products/{productId}/metadata/{metadataId}', 'metadata.controller:getSubjects');

        $controllers->get('/api/snomed/{taxonomyId}/{taxonomyType}', 'metadata.controller:getSnomedTerms');

        //Solr indexing the metadataValues
        $controllers->get('/api/crons/index-metadata', 'metadata.controller:metadataSolrIndex');

        //Solr Search of metadataValues
        $controllers->get('/api/metadata-search', 'metadata.controller:metadataSolrSearch');

        //Get subject/topics and thier progress details in one api.
        $controllers->get('/api/metadata/{productIds}/metadata/{clientMetadataId}', 'metadata.controller:getTaxonomyWithProgress');

        //get all products for authenticated user
        $controllers->get('/api/products', 'metadata.controller:getAllProducts');

        return $controllers;
    }

}
