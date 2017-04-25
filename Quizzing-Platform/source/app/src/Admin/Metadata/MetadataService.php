<?php

/*
 * MetadataService - Handles metadata module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Metadata;

use Silex\Application;

class MetadataService {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->hierarchyParentIds = array();
        $this->hierarchyChildIds = array();
    }

    /**
     * @Desc : common function used to create/update/delete metadata hierarchy values for creation and updation.
     * @param type $metaData
     * @param type $cmnMetadata
     * @param type $userId
     * @param type $parentId
     * @return string
     */
    public function createMetadataHierarchyValues($metaData, $cmnMetadata, $userId, $metadataId, $parentId = 0) {

        $returnIds = "";

        foreach ($metaData as $level) {

            if ($level['nodeStatus'] == "created") {
                // If created status sent, then store individual tag and get the parent_id for child tags insertion
                $newParent = $this->app['metadata.repository']->createIndividualHierarchy($level, $cmnMetadata, $parentId, $userId);
            } else if ($level['nodeStatus'] == "updated") {
                //if updated status sent, then update the nodes
                $newParent = $this->app['metadata.repository']->updateIndividualHierarchy($level, $userId);
            } else if ($level['nodeStatus'] == "deleted") {
                //if deleted status sent, then soft delete the nodes along with soft delete its child nodes as well.
                $newParent = $this->app['metadata.repository']->deleteIndividualHierarchy($metadataId, $level['id']);
            } else if ($level['nodeStatus'] == "selected")
                $newParent = $level['id'];

            $returnIds .= $parentId . ",";

            if (is_array($level['children']) && $level['nodeStatus'] != "deleted") {
                if ($newParent) {
                    // recurssive call to create the child metadata tags.
                    $children = self::createMetadataHierarchyValues($level['children'], $cmnMetadata, $userId, $metadataId, $newParent);
                }
            }
        }

        return $returnIds;
    }

    /**
     * @Desc takes array of metadata and forms the hierarchical data
     * @param array $elements
     * @param type $parentId
     * @return array
     */
    public function createHierarchy(array &$elements, $parentId) {

        $branch = array();
        foreach ($elements as $element) {

            if ($element['parentId'] == $parentId) {

                $children = self::createHierarchy($elements, $element['id']);
                //Adding this to maintain the status for each node level for metadata updation.
                $element['nodeStatus'] = 'selected';

                if ($children) {
                    $element['children'] = $children;
                } else {
                    $element['children'] = array();
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    //to get levelsof topics under metadata
    public function createMetadataList(array &$elements, $parentId, $key, array &$branch) {

        foreach ($elements as $keyelement => $element) {

            if ($element['parentId'] == $parentId) {

                $element['level'] = 'level' . $key;
                //$branch[$keyelement]['level'] = 'level' . $key;
                array_push($branch, $element);

                $key++;
                $children = self::createMetadataList($elements, $element['id'], $key, $branch);
                $key--;
            }
        }

        return $branch;
    }

    /**
     * 
     * @param type $arr
     * @param type $id
     * @return type
     */
    public function get_key($arr, $id) {
        foreach ($arr as $key => $val) {
            if ($val['id'] === $id) {
                return $key;
            }
        }
        return null;
    }

    /**
     * 
     * @param type $elements
     * @param type $hierarchyId
     * @return type
     */
    public function getParentNodeIds($elements, $hierarchyId) {

        $key = self::get_key($elements, $hierarchyId);

        $this->hierarchyParentIds[] = $hierarchyId;
        if ($elements[$key]['parentId'] == 0) {
            return $this->hierarchyParentIds;
        } else {

            return self::getParentNodeIds($elements, $elements[$key]['parentId']);
        }
    }

    /**
     * 
     * @param type $elements
     * @param type $parentId
     * @return type
     */
    public function getChildNodesIds($elements, $parentId) {

        foreach ($elements as $element) {

            if ($element['parentId'] == $parentId) {

                $children = self::getChildNodesIds($elements, $element['id']);

                $this->hierarchyChildIds[] = $element['id'];
            }
        }
        return $this->hierarchyChildIds;
    }

    public function getTaxonomyList($userId, $clientId, $randomMetadataId, $requestDetails) {
        $metadataId = $this->app['metadata.repository']->getclientMetadataId($clientId, $randomMetadataId); //Get actual client metadata id using clientId and randomclient metadataId

        if ($metadataId)
            $childDetails = $this->app['metadata.repository']->getChildValues($metadataId, $userId, $requestDetails);
        else
            return array();

        array_walk($childDetails, 'self::getProgressDetails', array("userId" => $userId, "requestFrom" => "topic", "metadataId" => $metadataId));

        $returnArray = array("totalMetadata" => count($childDetails), "metadata" => $childDetails);

        return $returnArray;
    }

    public function getProgressDetails(&$metadata, $key, $data) {
        //Add below details only for the topic api
        if ($data['requestFrom'] == "topic") {
            $metadata['hasChild'] = ($metadata['hasChild'] == 0) ? true : false;
        }

        //Get the sublevel of topics
        $subLevelIds = $this->app['metadata.repository']->getMetadataSubLevelIds(array($metadata['id']));
        //Get the Item count of taxonomy
        $itemCount = $this->app['tests.repository']->getItemCountByTaxonomyId($subLevelIds, $data['metadataId']);
        $metadata['numberOfMetadataQuestions'] = ($itemCount[0]['itemCount']) ? ((int) $itemCount[0]['itemCount']) : null;

        //If latest quiz exist then the get the latest completed instance test progress details
        if (!empty($metadata['quizId'])) {
            $metadata['quizId'] = (int) $metadata['quizId'];
            $testProgressData = $this->app['tests.repository']->getTestProgressBar($metadata['quizId'], $data['userId']);
            if (!empty($testProgressData)) {
                $testProgress = array();
                $testProgress['totalTestQuestions'] = $testProgressData[0]['totalQuestions'];
                $testProgress['totalCorrectAnswers'] = $testProgressData[0]['totalCorrect'];
                $testProgress['totaWrongAnswers'] = $testProgressData[0]['totalIncorrect'];
                $testProgress['totalUnAttempted'] = $testProgressData[0]['totalUnattempted'];
                $metadata['testProgress'] = $testProgress;
            }
        }
    }

    /**
     * @Desc Fetches the subjects details mapped for the product and its sub product
     * @param type $productId 
     * @param type $clientId
     * @return array
     */
    public function getSubjects($productIds, $clientId, $userId, $metadataId) {
        $productArr = array();
        $productList = explode(',', $productIds);

        $clientProductList = $this->app['metadata.repository']->getClientProducts($clientId);
        //Find sub products for each products requested
        foreach ($productList as $key => $productId) {
            $productArr = array_merge($productArr, $this->app['metadata.service']->getSubProducts($clientProductList, $productId));
        }

        $productArr = array_unique($productArr);
        $subjectDetails = $this->app['metadata.repository']->getSubjectDetails($productArr, $metadataId, $userId); //Get level 1 /subject details from productArr

        array_walk($subjectDetails, 'self::getProgressDetails', array("userId" => $userId, "metadataId" => $metadataId));

        $returnArray = array("totalMetadata" => count($subjectDetails), "metadata" => $subjectDetails);
        return $returnArray;
    }

    /**
     * @Desc takes array of metadata and forms the hierarchical data
     * @param array $elements
     * @param type $parentId
     * @return array
     */
    public function createTaxonomyHierarchy(array &$elements, $parentId, $level = 0) {
        $returnIds = "";
        foreach ($elements as $element) {
            if ($element['parentProductId'] == $parentId) {
                $returnIds .= $element['metadataValueId'] . ",";
                $children = self::createTaxonomyHierarchy($elements, $element['productId'], $level + 1);
                if ($children) {
                    $returnIds .= $children . ",";
                }
            } else if ($element['productId'] == $parentId && $level == 0) {
                $returnIds .= $element['metadataValueId'] . ",";
            }
        }
        return trim($returnIds, ',');
    }

    /**
     * @Desc takes array of products for a client and get all subproducts based on passed product id
     * @param array $products
     * @param type $parentId
     * @return array
     */
    public function getSubProducts(array &$products, $parentId, $level = 0) {
        $returnIds = "";
        foreach ($products as $product) {
            if ($product['parentProductId'] == $parentId) {
                $returnIds .= $product['productId'] . ",";
                $children = self::createTaxonomyHierarchy($products, $product['productId'], $level + 1);
                if ($children) {
                    $returnIds .= $children . ",";
                }
            } else if ($product['productId'] == $parentId && $level == 0) {
                $returnIds .= $product['productId'] . ",";
            }
        }
        return explode(',', trim($returnIds, ','));
    }

    /**
     * @Desc Get list of snomed concept desc and terms based on taxonomyId and taxonomyType
     * and call recursively based on $limitLevel
     * @param array $taxonomyId
     * @param string $taxonomyType
     * @param int $limitLevel
     * @param int $currentLevel
     * @return array
     */
    public function getSnomedTerms($taxonomyId, $taxonomyType, $limitLevel = 0, $currentLevel = 0) {
        if ($taxonomyType == $this->app['config']['qbTaxonomyType']) {
            $conceptIdArr = $this->app['metadata.repository']->getMetadataTaxonomyMapping($taxonomyId);
            $conceptIds = array_column($conceptIdArr, 'id');
            $termList = $this->app['metadata.repository']->getSnomedTerms($conceptIds);
            foreach ($conceptIdArr as $taxonomy) {
                $formatedTermList[$taxonomy['metadataId']]['metadataValue'] = $taxonomy['metadataValue'];
                $conceptDetails = $this->getConceptDetails($taxonomy['id'], $termList);

                if ($currentLevel != $limitLevel) {
                    $childList = $this->app['metadata.repository']->getChildConceptList($conceptDetails['conceptid']);
                    if (is_array($childList) && count($childList) > 0) {
                        $childDetails = $this->getSnomedTerms($childList, $this->app['config']['snomedTaxonomyType'], $limitLevel, $currentLevel + 1);
                        if (is_array($childDetails) && count($childDetails) > 0)
                            $conceptDetails['children'] = $childDetails;
                    }
                }
                if (is_array($formatedTermList[$taxonomy['metadataId']]['concepts']))
                    array_push($formatedTermList[$taxonomy['metadataId']]['concepts'], $conceptDetails);
                else
                    $formatedTermList[$taxonomy['metadataId']]['concepts'] = array($conceptDetails);
            }
        }
        else if ($taxonomyType == $this->app['config']['snomedTaxonomyType']) {
            $termList = $this->app['metadata.repository']->getSnomedTerms($taxonomyId);
            if (count($termList) > 0) {
                foreach ($taxonomyId as $id) {
                    $conceptDetails = $this->getConceptDetails($id, $termList);
                    if ($currentLevel != $limitLevel) {
                        $childList = $this->app['metadata.repository']->getChildConceptList($conceptDetails['conceptid']);
                        if (is_array($childList) && count($childList) > 0) {
                            $childDetails = $this->getSnomedTerms($childList, $this->app['config']['snomedTaxonomyType'], $limitLevel, $currentLevel + 1);
                            if (is_array($childDetails) && count($childDetails) > 0)
                                $conceptDetails['children'] = $childDetails;
                        }
                    }
                    $formatedTermList['concepts'][] = $conceptDetails;
                }
            }
        }
        return $formatedTermList;
    }

    /**
     * @Desc Finds and collects the desc and terms for concept from the $termDetails array for the given $conceptId
     * @param int $conceptId
     * @param array $termDetails
     * @return array
     */
    public function getConceptDetails($conceptId, $termDetails) {
        $returnArr = array('terms' => array());
        foreach ($termDetails as $terms) {
            if ($conceptId == $terms['conceptid']) {
                if ($terms['typeid'] == $this->app['config']['snomedSynonymTypeId']) {
                    $returnArr['desc'] = $terms['term'];
                    $returnArr['conceptid'] = $conceptId;
                } else if ($terms['typeid'] == $this->app['config']['snomedDescTypeId']) {
                    array_push($returnArr['terms'], $terms['term']);
                }
            }
        }
        return $returnArr;
    }

    /**
     * Get metadata Types
     * @return type
     */
    public function getMetadataTypes() {
        $listOfMetadataTypes = $this->app['metadata.repository']->getMetadataTypes();
        return $listOfMetadataTypes;
    }

    /**
     * Get metadata Datatypes
     * @return type
     */
    public function getMetadataDataTypes() {
        $listOfMetadataDataTypes = $this->app['metadata.repository']->getMetadataDataTypes();
        return $listOfMetadataDataTypes;
    }

    /**
     * Create metadata 
     * @param type $metadata
     * @return type
     */
    public function create($metadata) {
        $createdId = $this->app['metadata.repository']->create($metadata);
        return $createdId;
    }

    /**
     * Get the metadata details by metadataid
     * @param type $metadataId
     * @param type $metadataParentId
     * @return type
     */
    public function find($metadataId, $metadataParentId = NULL) {
        $metadataTagArray = $this->app['metadata.repository']->find($metadataId, $metadataParentId);
        return $metadataTagArray;
    }

    /**
     * Get all the metadata
     * @param type $metadataRequest
     * @return type
     */
    public function getMetadata($metadataRequest) {
        $metadataTagArray = $this->app['metadata.repository']->getMetadata($metadataRequest);
        return $metadataTagArray;
    }

    /**
     * update the metadata
     * @param type $metdataValue
     * @param type $updateMetadata
     * @return type
     */
    public function update($metdataValue, $updateMetadata) {
        $updated = $this->app['metadata.repository']->update($metdataValue, $updateMetadata);
        return $updated;
    }

    /**
     * To delete the metadata
     * @param type $metadataId
     * @return type
     */
    public function delete($metadataId) {
        $response = $this->app['metadata.repository']->delete($metadataId);
        return $response;
    }

    /**
     * Get metadata Details
     * @return type
     */
    public function getMetadataDetails() {
        $mandatoryMetadata = $this->app['metadata.repository']->getMetadataDetails();
        return $mandatoryMetadata;
    }

    /**
     * Get all the institutions
     * @return type
     */
    public function getInstitutions() {
        $instituionsList = $this->app['metadata.repository']->getInstitutions();
        return $instituionsList;
    }

    /**
     * To get clientMetadataId
     * @param type $clientId
     * @param type $randomMetadataId
     * @return type
     */
    public function getclientMetadataId($clientId, $randomMetadataId) {
        $metadataId = $this->app['metadata.repository']->getclientMetadataId($clientId, $randomMetadataId); //Get actual client metadata id using clientId and randomclient metadataId
        return $metadataId;
    }

    /**
     * solr indexing the metadata
     * @return type
     */
    public function metadataSolrIndex() {
        //Get all the metadata values
        $metadataValueArray = $this->app['metadata.repository']->getMetadataValues();

        //Forming a array to index the values to solr
        foreach ($metadataValueArray as $metadataValue) {
            foreach ($metadataValue as $value) {
                $metadataValueList[] = $value;
            }
        }
        //Total number of records
        $totalMetadataValues = count($metadataValueList);

        //Get the modular value of total metadata with configured value for index
        $moduleValue = $totalMetadataValues % $this->app['config']['metadataIndexCount'];
        $count = 0;

        for ($i = 0; $i < $totalMetadataValues; $i++, $count++) {

            //Assign metadataValues to array
            $metadataValue[$count] = $metadataValueList[$i];

            //if the count of values indexing is same as the value configured in application
            if ($moduleValue == 0) {
                if ($count == $this->app['config']['metadataIndexCount']) {
                    $document = $this->app['offlinescripts.repository']->metadataSolrIndex($metadataValue);
                    $count = 0;
                }
            }
            //If the modular values is there
            else {
                if ($count == $this->app['config']['metadataIndexCount']) {
                    $document = $this->app['offlinescripts.repository']->metadataSolrIndex($metadataValue);
                    $count = 0;
                    $metadataValue = array();
                } elseif ($i == $totalMetadataValues - 1) {
                    $document = $this->app['offlinescripts.repository']->metadataSolrIndex($metadataValue);
                    $count = 0;
                    $metadataValue = array();
                }
            }
        }

        // If its failed to index, then log this metadata in logs.
        if (!$document) {

            //If failed to index the metadata, then add to logs.
            $msg = 'Failed to index the Metadata Values';
            $this->app['log']->writeLog($msg);
            return false;
        }
        return true;
    }

    /**
     * @Desc Get subject and topics list along with thier test progress details for the given product ids.
     * @param type $productIds
     * @return type
     */
    public function getTaxonomyWithProgress($productIds, $userId) {

        //get subject/topics details along with progress details
        $response = $this->app['metadata.repository']->getTaxonomyWithProgress($productIds, $userId);

        return $response;
    }

    /**
     * To get all products for given client
     * @param type $clientId
     * 
     * @return type
     */
    public function getAllProducts($clientId) {

        $productDetails = $this->app['metadata.repository']->getAllProducts($clientId);
        //print_R($productDetails);die;
        $branch = array();
        $productArray = self::getAllProductNodes($productDetails, 0);
        //print_R($productArray);die;
        return $productArray;
    }

    public function getAllProductNodes(array &$elements, $parentId) {
        $branch = array();
        foreach ($elements as $element) {

            if ($element['parentProductId'] == $parentId) {

                $children = self::getAllProductNodes($elements, $element['id']);
                //Adding this to maintain the status for each node level for metadata updation.
                //$element['nodeStatus'] = 'selected';
                unset($element['parentProductId']);
                if ($children) {
                    $element['subProducts'] = $children;
                } else {
                    $element['subProducts'] = array();
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

}

?>