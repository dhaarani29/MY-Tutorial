<?php

/*
 * OfflinescriptsService - Handles all offline scripts module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Offlinescripts;

use Silex\Application;

set_time_limit(0);
ini_set('memory_limit', -1);

class OfflinescriptsService {

    protected $app;
    protected $em;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->em = $app['orm.em'];
        $this->taxonomyMapping = array();
        $this->productParentTaxonomy = array();
    }

    /**
     * @Desc : It loads Silver chair client taxonomy details to quizzing platform database. It reads excel document from the specific location and reads it. 
     * @return boolean
     */
    public function readTaxonomy() {

        // Read the silver chair taxonomy files from the upload directory 
        $filePath = __DIR__ . "/../../../web/uploads/silverchair_taxonomy/";
        $fileName = "silver_chair_taxonomy.xlsx";

        // Get file full path
        $absoluteFilePath = $filePath . $fileName;

        //check for file availability  
        if (file_exists($absoluteFilePath)) {

            // Initiate phpexcel object
            $objReader = new \PHPExcel();
            $objPHPExcel = \PHPExcel_IOFactory::load($absoluteFilePath);

            // Get by sheet name 
            $worksheet = $objPHPExcel->getActiveSheet();

            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            //$nrColumns = ord($highestColumn) - 64;

            echo "<br>The worksheet '" . $worksheetTitle . "' has " . $highestColumnIndex . ' columns (A-' . $highestColumn . ') and ' . $highestRow . ' rows.';

            // Excel sheet should have following columns.
            $headers = array("Product Id", "Sub Product Id", "Subject/topic levels");

            //Constant metadataId defined for silver chair client and can be found in oauth_clients table  
            $silverChairMetadataId = 460;
            $userId = 1;
            $failedProductsList = array();
            $i = 0; //to check, how many rows are processed
            // Get metadata reference for the given metadata id.
            $cmnMetadata = $this->app['metadata.repository']->getMetadataReference($silverChairMetadataId);

            //Neglect header and start reading actual data
            //Read row wise data
            for ($row = 2; $row <= $highestRow; ++$row) {


                //Read column wise data
                for ($col = 0; $col < count($headers); ++$col) {

                    //Get cell wise data
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $cellValue = $cell->getValue();

                    // consider column = 0 as product Id
                    if ($col == 0) {
                        $productId = $cellValue ? $cellValue : 0;
                    }
                    // Consider column = 1 as sub product Id
                    else if ($col == 1) {
                        $subProductId = $cellValue ? $cellValue : 0;
                        if ($subProductId != 0) {
                            $productId = $subProductId;
                        }
                    }
                    // taxonomy details (subject/topic) will be in column 2
                    else if ($col == 2) {

                        $subTopicLevel = $cellValue;

                        // If subject/topics details are empty then skip this and continue
                        if ($productId == "" || $productId == NULL || $subTopicLevel == "" || $subTopicLevel == NULL) {

                            if ($subTopicLevel == "" || $subTopicLevel == NULL) {
                                $failedProductsList[] = $productId;
                            }

                            continue;
                        }

                        $i++; // Increment this to check, how many rows are processed
                        // Get subjects and topics, sub topics from the cell in to array.
                        $subTopicsArray = explode('/', $subTopicLevel);
                        $parentId = 0; // by default add parentId as 0 
                        // Store subjects/topics in to metadata table.
                        foreach ($subTopicsArray as $key => $levelName) {

                            $parentName = $subTopicsArray[$key - 1];

                            // Check if the levelname already stored in the database for the specific product id then consider that ID and assign in to parentId
                            $taxonomy = self::checkTaxonomyDuplicate($silverChairMetadataId, $levelName, $parentName);
                            if (!empty($taxonomy)) {
                                $parentId = $taxonomy[0]['id'];
                            } else {
                                // If its not yet stored to database then store it freshly and get the Id and store it to parentId variable.
                                $parentId = self::storeTaxonomy($levelName, $cmnMetadata, $parentId, $userId, $productId, $parentName);
                            }
                        }
                    }
                }
            }

            echo " <br> <br> Total products (rows) processed $i ";
            // After reading all the excel data, store the product/subproduct to subject mapping in the product_taxonomy_assoc table
            $this->app['offlinescripts.repository']->storeProductTaxonomyMapping($this->productParentTaxonomy, $silverChairMetadataId, $userId);

            if (!empty($failedProductsList)) {
                echo "<br><br> Failed products to process : " . implode(',', array_unique($failedProductsList));
            }
        }
        return true;
    }

    /**
     * @Desc check if taxonomy already stored and exists in taxonomyMapping array .
     * @param type $id
     * @param type $levelName
     * @return boolean
     */
    public function checkTaxonomyDuplicate($silverChairMetadataId, $levelName, $parentName) {

        $checkDuplicate = $this->app['metadata.repository']->checkTaxonomyDuplicate($silverChairMetadataId, $levelName, $parentName);
        return $checkDuplicate;
    }

    /**
     * @Desc Store taxonomy for the products and subproducts.
     * @param type $levelName
     * @param type $cmnMetadata
     * @param type $parentId
     * @param type $userId
     * @param type $productId
     * @return type
     */
    public function storeTaxonomy($levelName, $cmnMetadata, $parentId, $userId, $productId = NULL, $parentName = NULL) {

        $metadataValue['value'] = $levelName;

        $id = $this->app['metadata.repository']->createIndividualHierarchy($metadataValue, $cmnMetadata, $parentId, $userId);

        // Categorised the storing of taxonomy to get the subject id when its inserting the subject for the first time.
        if ($parentName == "Health Library") {

            $metadataValueArray = array('metadataValueId' => $id);

            if ($productId != 0)
                $this->productParentTaxonomy[$productId][] = $metadataValueArray;
        }

        return $id;
    }

    /**
     * @Desc Search for concept id/metadata id from solr
     * @param type $conceptId
     * @param type $conceptType
     * @return type
     */
    public function solrSearch($conceptId, $conceptType) {

        $searchResults = $this->app['offlinescripts.repository']->solrSearch($conceptId, $conceptType);

        if (!empty($searchResults)) {
            return $searchResults;
        }
    }

    /**
     * Item solr search
     * @param type $conceptId
     * @param type $conceptType
     * @return type
     */
    public function itemSolrSearch($conceptId, $conceptType = NULL) {

        $searchResults = $this->app['offlinescripts.repository']->itemSolrSearch($conceptId, $conceptType);

        if (!empty($searchResults)) {
            return $searchResults;
        }
    }

    /**
     * @Desc Add/update the record to solr
     * @param type $itemId
     * @return type
     */
    public function solrIndex($itemId) {

        $document = $this->app['offlinescripts.repository']->solrIndex($itemId);
        return $document;
    }

    /**
     * @Desc delete the record from solr
     * @param type $itemId
     * @return type
     */
    public function solrDelete($itemId) {

        $document = $this->app['offlinescripts.repository']->solrDelete($itemId);
        return $document;
    }

    /**
     * solr search for metadata / Taxonomy
     * @param type $taxonomyName
     * @param type $metadataType
     * @param type $metadataId
     * @return type
     */
    public function metadataSolrSearch($taxonomyName, $metadataType, $metadataId) {

        $searchResults = $this->app['offlinescripts.repository']->metadataSolrSearch($taxonomyName, $metadataType, $metadataId);

        if (!empty($searchResults)) {
            return $searchResults;
        }
    }

}
