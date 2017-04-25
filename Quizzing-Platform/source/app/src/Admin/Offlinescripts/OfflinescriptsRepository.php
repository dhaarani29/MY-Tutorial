<?php

/**
 * OfflinescriptsRepository - It's the model class file to handle offline scripts module database operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Offlinescripts;

use Silex\Application;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\PrdProductMetadata;

set_time_limit(0);
ini_set('memory_limit', -1);

class OfflinescriptsRepository {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->effectiveDateTo = $app['config']['effectiveDateTo'];
        $this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));
        $this->graphicOption = $this->app['cache']->fetch('GRAPHIC_OPTION');
    }

    public function storeProductTaxonomyMapping($productParentTaxonomy, $silverChairMetadataId, $userId) {

        $metadataObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadata')->findOneByMetadataId(array('metadataId' => $silverChairMetadataId));

        //Insertion for multiple Records
        foreach ($productParentTaxonomy as $key => $productDetails) {

            $productObj = $this->em->getRepository('QuizzingPlatform\Entity\PrdProduct')->findOneByProductId(array('productId' => $key));

            foreach ($productDetails as $taxonomy) {

                $metadataValueObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadataHierarchyValues')->findOneById(array('id' => $taxonomy['metadataValueId']));

                $prdProductMetadata = new PrdProductMetadata();
                $prdProductMetadata->setProduct($productObj);
                $prdProductMetadata->setMetadataValue($metadataValueObj);
                $prdProductMetadata->setMetadata($metadataObj);
                $prdProductMetadata->setCreatedBy($userId); //Created by(user id)
                $prdProductMetadata->setCreatedDate($this->dateTime); //Created Date(current Date)
                $prdProductMetadata->setModifiedBy($userId); //Modified by(user id)
                $prdProductMetadata->setModifiedDate($this->dateTime); //Modified Date(current Date)
                $this->em->persist($prdProductMetadata); //Manage the Inserting Value
            }
        }

        //Execution of insertion Query
        $this->em->flush();

        return true;
    }

    /**
     * @Desc Search for the keyword in solr
     * @param type $conceptId
     * @return type
     */
    public function solrSearch($conceptId, $conceptType) {

        // Get solr client select handler
        $query = $this->app['solr.client']->createSelect();

        // Multiple metadata/concept ids can be passed to search
        $conceptIds = explode(',', $conceptId);

        $snomedDetails = $this->app['metadata.service']->getSnomedTerms($conceptIds, $conceptType, 1);

        if (!empty($snomedDetails)) {

            foreach ($snomedDetails as $concepts) {

                if ($conceptType == $this->app['config']['qbTaxonomyType']) {

                    $conceptDetails = $concepts['concepts'];
                } else {
                    $conceptDetails = $concepts;
                }

                foreach ($conceptDetails as $snomedConcept) {

                    $snomedConceptIds[] = $snomedConcept['conceptid'];

                    if (!empty($snomedConcept['children'])) {
                        foreach ($snomedConcept['children']['concepts'] as $childrenConcept) {

                            $snomedConceptIds[] = $childrenConcept['conceptid'];
                        }
                    }
                }
            }
            //IF snomed concept ids are passed then form a query string.
            if (!empty($snomedConceptIds)) {

                // Always list the published and active questions
                $queryParameters = "status :Published AND isDeleted: 1 AND (";

                // Then add each snomed id to query string.
                foreach ($snomedConceptIds as $concept) {

                    if ($concept != "") {
                        // Form the query string using OR operator
                        $queryParameters .= "snomedIds:*" . $concept . '* OR ' . "snomedRelation:*" . $concept . '* OR ';
                    }
                }

                // Remove OR from the query string and finally feed this to solr query serach
                $queryString = substr($queryParameters, 0, -3);

                // Then close the bracket, which is added in $queryParameters.
                $queryString .= ")";
            }

            //For the select query instance with number of records and name of the fields to get along with sorting.
            $query->setQuery($queryString);
            $query->setRows(100);
            $query->setFields(array('itemId', 'identifier', 'snomedIds', 'snomedDesc', 'snomedSynonyms', 'snomedChildren', 'itemTypeName', 'label', 'promptText', 'metadataValues'));
            $query->addSort('itemId', $query::SORT_ASC);

            // Select the query
            $resultset = $this->app['solr.client']->select($query);

            $searchResults = array();

            //Add the result set objects in to array.
            foreach ($resultset as $key => $document) {

                $searchResults[$key]['itemId'] = $document->itemId;
                $searchResults[$key]['identifier'] = $document->identifier;
                $searchResults[$key]['snomedIds'] = $document->snomedIds;
                $searchResults[$key]['snomedDesc'] = $document->snomedDesc;
                $searchResults[$key]['snomedSynonyms'] = $document->snomedSynonyms;
                $searchResults[$key]['snomedChildren'] = $document->snomedChildren;
                $searchResults[$key]['itemTypeName'] = $document->itemTypeName;
                $searchResults[$key]['label'] = $document->label;
                $searchResults[$key]['promptText'] = $document->promptText;
                $searchResults[$key]['metadataValues'] = $document->metadataValues;
            }

            // Return the search results
            return $searchResults;
        } else {
            return false;
        }
    }

    /**
     * search the item solr results by conceptid or string
     * @param type $conceptId
     * @param type $conceptType
     * @return type
     */
    public function itemSolrSearch($conceptId, $conceptType = NULL) {

        // Get solr client select handler
        $query = $this->app['solr.client']->createSelect();

        /*
         * If the conceptType is Fulltext or empty , then search full text search
         */
        if ($conceptType == $this->app['config']['fullTextSearch'] || empty($conceptType)) {

            //full text search fields
            $fullTextSearchFields = array("promptText", "label", "identifier", "snomedDesc", "snomedTerms", "snomedRelationDesc");

            //fields ranking
            $fullTextSearchFieldsRanking = array(6, 5, 4, 3, 2, 1);

            //Form the query string
            for ($i = 0; $i < count($fullTextSearchFields); $i++) {

                $queryParameter .= $fullTextSearchFields[$i] . ":*" . $conceptId . "*^" . $fullTextSearchFieldsRanking[$i] . " OR ";
            }

            //Remove the final OR keyword
            $queryParams = substr($queryParameter, 0, -3);

            //Complete query for solr search
            $queryString = "status :Published AND isDeleted: 1 AND (" . $queryParams . ")";
        }
        /*
         * If the conceptType is QB or SNOMED , then search the conceptId
         */ elseif ($conceptType == $this->app['config']['qbTaxonomyType'] || $conceptType == $this->app['config']['snomedTaxonomyType']) {

            // Multiple metadata/concept ids can be passed to search
            $conceptIds = explode(',', $conceptId);

            $snomedDetails = $this->app['metadata.service']->getSnomedTerms($conceptIds, $conceptType, 1);

            if (!empty($snomedDetails)) {

                foreach ($snomedDetails as $concepts) {

                    if ($conceptType == $this->app['config']['qbTaxonomyType']) {

                        $conceptDetails = $concepts['concepts'];
                    } else {
                        $conceptDetails = $concepts;
                    }

                    foreach ($conceptDetails as $snomedConcept) {

                        $snomedConceptIds[] = $snomedConcept['conceptid'];

                        if (!empty($snomedConcept['children'])) {
                            foreach ($snomedConcept['children']['concepts'] as $childrenConcept) {

                                $snomedConceptIds[] = $childrenConcept['conceptid'];
                            }
                        }
                    }
                }
                //IF snomed concept ids are passed then form a query string.
                if (!empty($snomedConceptIds)) {

                    // Always list the published and active questions
                    $queryParameters = "status :Published AND isDeleted: 1 AND (";

                    // Then add each snomed id to query string.
                    foreach ($snomedConceptIds as $concept) {

                        if ($concept != "") {
                            // Form the query string using OR operator
                            $queryParameters .= "snomedIds:*" . $concept . '* OR ' . "snomedRelation:*" . $concept . '* OR ';
                        }
                    }

                    // Remove OR from the query string and finally feed this to solr query serach
                    $queryString = substr($queryParameters, 0, -3);

                    // Then close the bracket, which is added in $queryParameters.
                    $queryString .= ")";
                }
            }
            $query->addSort('itemId', $query::SORT_ASC);
        }

        //For the select query instance with number of records and name of the fields to get.
        $query->setQuery($queryString);
        $query->setRows(100);
        $query->setFields(array('itemId', 'identifier', 'snomedIds', 'snomedDesc', 'snomedSynonyms', 'snomedChildren', 'itemTypeName', 'label', 'promptText', 'metadataValues'));

        // Select the query
        $resultset = $this->app['solr.client']->select($query);

        $searchResults = array();

        //Add the result set objects in to array.
        foreach ($resultset as $key => $document) {

            $searchResults[$key]['itemId'] = $document->itemId;
            $searchResults[$key]['identifier'] = $document->identifier;
            $searchResults[$key]['snomedIds'] = $document->snomedIds;
            $searchResults[$key]['snomedDesc'] = $document->snomedDesc;
            $searchResults[$key]['snomedSynonyms'] = $document->snomedSynonyms;
            $searchResults[$key]['snomedChildren'] = $document->snomedChildren;
            $searchResults[$key]['itemTypeName'] = $document->itemTypeName;
            $searchResults[$key]['label'] = $document->label;
            $searchResults[$key]['promptText'] = $document->promptText;
            $searchResults[$key]['metadataValues'] = $document->metadataValues;
        }

        // Return the search results
        return $searchResults;
    }

    /**
     * @Desc Add the documents to solr
     * @param array $itemId
     * @return boolean
     */
    public function solrIndex($itemId, $action = NULL) {

        //Declare itemids array
        $itemIds = array();

        // Check if itemid is passed
        if ($itemId != "") {
            $itemIds[] = $itemId;
        } else {

            //If itemid is not passed then get all the itemids from the system
            $metadataRequest = array();

            //First get the total items list from exists in the QP project
            $itemsCount = $this->app['items.repository']->getItemsCount();

            // Following logic is written to get the items list page by page.
            $page = 1;
            $perPage = 10;

            // Get the total number pf pages using perpage count
            $totalPages = round($itemsCount / $perPage);

            $exactPages = $totalPages * $perPage;

            // Check wether computed pages are matching the total pages.
            if ($exactPages < $itemsCount) {
                $totalPages++;
            }

            // Then loop the pages and send the page number and perpage to get the itemids.
            for ($page = 1; $page <= $totalPages; $page++) {

                $itemRequest = array('page' => $page, 'perPage' => $perPage);
                $itemsList = $this->app['items.repository']->getItemsList($itemRequest);
                foreach ($itemsList['data'] as $items) {
                    $itemIds[] = $items['id'];
                }
            }
        }

        // Get the solr client create/update handler.
        $update = $this->app['solr.client']->createUpdate();

        //For each question get the question information
        foreach ($itemIds as $itemId) {

            // Declare few variables.
            $version = $metadataIds = $metadataValues = $questionsChoices = $snomedConceptIds = $remediations = $snomedDescTerms = $snomedChildren = $childrenConceptIds = $childrenDesc = '';

            //Get each item details
            $itemData = $this->app['items.repository']->find($itemId);

            // Get all the item versions list
            if (!empty($itemData['versionsList'])) {
                foreach ($itemData['versionsList'] as $versions) {
                    $version .= $versions['version'] . ',';
                }
                $version = rtrim($version, ',');
            }

            // Get all metadata ids and metadata value ids
            if (!empty($itemData['metadataAssoc'])) {
                foreach ($itemData['metadataAssoc'] as $key => $metadataNodes) {

                    $metadataIds .= $key . ',';

                    if (is_array($metadataNodes)) {

                        foreach ($metadataNodes as $metadata) {

                            if (is_array($metadata)) {

                                $metadataValues .= $metadata['id'] . ',';
                            }
                        }
                    }
                }
                //Metadata ids
                $metadataIds = rtrim($metadataIds, ',');
                //Metadata Value ids
                $metadataValues = rtrim($metadataValues, ',');
            }

            //Get question answer choices
            if (!empty($itemData['choiceInteraction'])) {
                foreach ($itemData['choiceInteraction'] as $simpleChoices) {

                    if (is_array($simpleChoices)) {
                        foreach ($simpleChoices as $choices) {
                            $questionsChoices .= $choices['label'] . ',';
                        }
                    }
                }
                $questionsChoices = rtrim($questionsChoices, ',');
            }


            // Get correct/incorrect rationale
            if (!empty($itemData['modelFeedback'])) {
                foreach ($itemData['modelFeedback'] as $feedback) {

                    if (!empty($feedback)) {

                        if ($feedback['outcomeType'] == 1) {
                            $correctRationale = $feedback['feedbackText'];
                        } else {
                            $inCorrectRationale = $feedback['feedbackText'];
                        }
                    }
                }
            }

            //get remidiation links
            if (!empty($itemData['remediationLinks'])) {
                foreach ($itemData['remediationLinks'] as $remediation) {
                    if (!empty($remediation)) {
                        $remediations .= $remediation['linkText1'] . ',';
                    }
                }
                $remediations = rtrim($remediations, ',');
            }

            // get snomed concept id, concept description and concept synonyms 
            if ($metadataValues != "") {

                $metadataValueIds = explode(',', $metadataValues);

                $snomedDetails = $this->app['metadata.service']->getSnomedTerms($metadataValueIds, $this->app['config']['qbTaxonomyType'], 1);

                if (!empty($snomedDetails)) {

                    foreach ($snomedDetails as $concepts) {

                        foreach ($concepts['concepts'] as $snomedConcept) {

                            $snomedConceptIds .= $snomedConcept['conceptid'] . ',';
                            $snomedConceptsArray[] = $snomedConcept['conceptid'];
                            $snomedDesc .= $snomedConcept['desc'] . ',';
                            $snomedTerms .= implode(',', $snomedConcept['terms']) . ',';

                            if (!empty($snomedConcept['children'])) {
                                foreach ($snomedConcept['children']['concepts'] as $childrenConcept) {

                                    $childrenConceptIds .= $childrenConcept['conceptid'] . ',';
                                    $childrenDesc .= $childrenConcept['desc'] . ',';
                                }
                            }
                        }
                    }

                    $snomedConceptIds = rtrim($snomedConceptIds, ',');
                    $snomedDesc = rtrim($snomedDesc, ',');
                    $snomedTerms = rtrim($snomedTerms, ',');
                    $childrenConceptIds = rtrim($childrenConceptIds, ',');
                    $childrenDesc = rtrim($childrenDesc, ',');
                }
            }


            // Create a solr document to store the data
            $doc = $update->createDocument();
            $doc->itemId = $itemId;
            $doc->identifier = $itemData['identifier'];
            $doc->itemTypeName = $itemData['itemTypeName'];

            $doc->status = $itemData['status'];

            $doc->snomedIds = $snomedConceptIds;
            $doc->snomedDesc = $snomedDesc;
            $doc->snomedTerms = $snomedTerms;
            $doc->snomedRelation = $childrenConceptIds;
            $doc->snomedRelationDesc = $childrenDesc;
            $doc->version = $itemData['version'];
            $doc->status = $itemData['status'];
            $doc->isDeleted = $itemData['isDeleted'];
            $doc->label = $itemData['label'];
            $doc->promptText = $itemData['promptText'];
            $doc->metadataIds = $metadataIds;
            $doc->metadataValues = $metadataValues;
            $doc->answerChoices = $questionsChoices;
            $doc->correctRationale = $correctRationale;
            $doc->inCorrectRationale = $inCorrectRationale;
            $doc->remediation = $remediations;

            // add the documents and a commit command to the update query
            $update->addDocuments(array($doc));

            $update->addCommit();

            // this executes the query and returns the result
            $result = $this->app['solr.client']->update($update);

            // Get the status.
            $solrStatus = $result->getStatus();
        }

        if ($solrStatus == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Desc Delete from solr.
     * @param type $itemId
     * @return boolean
     */
    public function solrDelete($itemId) {


        // Get the solr client create/update handler.
        $update = $this->app['solr.client']->createUpdate();

        // Form the query string to delete the record
        $queryParameters .= "itemId:" . $itemId;

        // add the delete query and a commit command to the update query
        $update->addDeleteQuery($queryParameters);
        $update->addCommit();

        // this executes the query and returns the result
        $result = $this->app['solr.client']->update($update);

        // Get the status.
        $solrStatus = $result->getStatus();

        if ($solrStatus == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * solr search of metadata Values
     * @param type $metadataValueList
     * @return boolean
     * @author Dhaarani S
     */
    public function metadataSolrIndex($metadataValueList) {

        // Get the solr client create/update handler.
        $updateSolr = $this->app['solr.client1']->createUpdate();
        $mainDoc = array();

        foreach ($metadataValueList as $key => $metadataValue) {

            // Create a solr document to store the data
            $document = $doc . $key;
            //Create multiple document based on the metadata values array
            $document = $updateSolr->createDocument();
            $document->metadataId = $metadataValue['metadataId'];
            $document->metadataType = $metadataValue['metadataType'];
            $document->taxonomyId = $metadataValue['taxonomyId'];
            $document->taxonomyName = $metadataValue['taxonomyName'];
            $document->taxonomyPath = $metadataValue['taxonomyPath'];
            $document->status = $metadataValue['status'];
            //Adding multiple document at a time in solr
            array_push($mainDoc, $document);
        }

        //add the documents and a commit command to the update query
        $updateSolr->addDocuments($mainDoc);

        $updateSolr->addCommit();

        // this executes the query and returns the result
        $result = $this->app['solr.client1']->update($updateSolr);
        // Get the status.
        $solrStatus = $result->getStatus();

        if ($solrStatus == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Solr search of metadata
     * @param type $taxonomyName
     * @param type $metadataType
     * @param type $clientMetadataId
     * @return type
     * @author Dhaarani S
     */
    public function metadataSolrSearch($taxonomyName, $metadataType, $clientMetadataId) {

        // Get solr client select handler
        $query = $this->app['solr.client1']->createSelect();

        //Search the metadata with status 1
        $queryParameters = ' status:1';

        //Search Taxonomy Name
        if (!empty($taxonomyName)) {
            $queryParameters .= " AND taxonomyName:" . '(*' . str_replace(' ', '\ ', $taxonomyName) . '* OR "' . $taxonomyName . '")';
        }
        //Search Metadata type ( Free / LookUp / Hierachy )
        if (!empty($metadataType)) {
            $queryParameters .= " AND metadataType:" . '(*' . str_replace(' ', '\ ', $metadataType) . '* OR "' . $metadataType . '")';
        }
        //Search MetadataId
        if (!empty($clientMetadataId)) {
            //Get MetadataId By clientMetadataId
//            $metadataId = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByRandomClientMetadataId(array('randomClientMetadataId' => $clientMetadataId));
//            $metadata = $metadataId->getMetadataId();
            $queryParameters .= " AND metadataId:" . $clientMetadataId;
        }

        $query->setQuery($queryParameters);

        //For the select query instance with number of records and name of the fields to get along with sorting.
        $query->setRows(100);

        //Fields required to display
        $query->setFields(array('taxonomyId', 'taxonomyName', 'taxonomyPath', 'metadataId', 'metadataType', 'status'));
        $query->addSort('taxonomyName', $query::SORT_ASC);
        $searchResult = array();
        $result = array();
        // Select the query
        $metadataResult = $this->app['solr.client1']->select($query);
        $searchResult['totalMetadata'] = count($metadataResult);
        foreach ($metadataResult as $key => $resultSet) {
            $result[$key]['metadataId'] = $resultSet->metadataId;
            $result[$key]['metadataType'] = $resultSet->metadataType;
            $result[$key]['status'] = ($resultSet->status == 1) ? 'Active' : 'Inactive';
            $result[$key]['taxonomyId'] = $resultSet->taxonomyId;
            $result[$key]['taxonomyName'] = $resultSet->taxonomyName;
            $result[$key]['taxonomyPath'] = $resultSet->taxonomyPath;
        }
        $searchResult['metadata'] = $result;
        return $searchResult;
    }

}
