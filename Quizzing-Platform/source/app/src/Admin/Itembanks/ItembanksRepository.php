<?php

/**
 * ItembanksRepository - It's the repository class file to handle the Itembanks module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Itembanks;

//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\CmnResourceMetadata;
use QuizzingPlatform\Entity\QtiItemBank;
use QuizzingPlatform\Entity\QtiItemBankMembers;
use QuizzingPlatform\Entity\QtiTestItemBanks;
use QuizzingPlatform\Entity\QtiTest;
use QuizzingPlatform\Entity\QtiItemBankUpload;
use QuizzingPlatform\Entity\LogQtiItemProcess;

class ItembanksRepository implements RepositoryInterface {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->module = 'itembanks';
        $this->effectiveDateTo = $app['config']['effectiveDateTo'];
        $this->hierarchyids = array();
        $this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));
    }

    /**
     * Fetching all the item collection based on the search filters(bank name and description,tag fields) 
     * @param type $itemCollectionRequest
     * @return boolean
     */
    public function getItemcollection($itemCollectionRequest, $metadataRequest, $associatedItemCollection = '', $associatedItemId = '', $allItemCollection = '') {

        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $bankName = $description = $status = $active = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "bankName";

            // Declare a result array to return. 
            $itemCollectionValues = array();

            // Check if request is not null.
            if (!empty($itemCollectionRequest)) {

                foreach ($itemCollectionRequest as $key => $mrequest) {

                    // get values for $bankName,$description,$perPage  
                    $$key = $mrequest;
                }

                // Logic found in CommonHelper class to get the offset value.
                $offset = CommonHelper::getOffset($page, $perPage);

                if (isset($sort)) {
                    // If sorting field is passed.
                    // Logic found in CommonHelper class to get the sorting type and sorting field.
                    $sort = CommonHelper::getSortingDetails($sort);

                    // Sorting type ASC/DESC
                    $sortType = $sort['type'];

                    // Sorting field name
                    $sortField = $sort['field'];
                }
            }

            //Get itemPkId
            if ($associatedItemCollection != NULL) {
                if (isset($version)) {
                    $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($associatedItemId, $version);
                    $itemPkId = $itemPkDetails['id'];
                } else {
                    $latestItemDetails = $this->app['items.repository']->getLatestItemId($associatedItemId);
                    $itemPkId = $latestItemDetails['id'];
                }
            }
            // Get Total of all the item collection based on the applied filters. 
            $totalItemcollection = self::getitemCollectionCount($bankName, $description, $status, $active, $metadataRequest, $associatedItemCollection, $itemPkId, $version);

            $itemCollectionValues['total'] = $totalItemcollection[0]['total']; // Total Item collection count for pagination.
            $itemCollectionValues['data'] = array();

            // Check if count is greater than 0
            if ($totalItemcollection > 0) {

                //Fetching the item collection Details
                $query = "SELECT qib.item_bank_id as itemBankId,qib.item_bank_name as bankName,qib.description as description,qs.status_name as statusName ";

                //Common Query for all types(associated , non associated and all collection listing)
                $commonQuery = ' JOIN qti_status qs ON qs.status_id=qib.status_id WHERE 1=1 ';

                //If metadata filter is passed , then add into where condition
                if (!empty($metadataRequest)) {
                    $metadataQuery .= '  LEFT JOIN cmn_resource_metadata crm ON crm.resource_id=qib.item_bank_id'
                            . ' LEFT JOIN cmn_metadata cm ON cm.metadata_id=crm.metadata_id';
                }

                //Listing All the item collection
                if ($associatedItemCollection == NULL) {
                    $query .= ",qibm.itemCount FROM qti_item_bank qib LEFT JOIN "
                            . "(SELECT COUNT(item_pk_id) as itemCount,item_pk_id,item_bank_id "
                            . "FROM qti_item_bank_members WHERE is_deleted=" . $this->app['cache']->fetch('ACTIVE')
                            . " GROUP BY item_bank_id) as qibm "
                            . "ON qibm.item_bank_id = qib.item_bank_id " . $metadataQuery . $commonQuery;
                }

                //Listing only item associated item collection
                elseif ($associatedItemCollection == $this->app['cache']->fetch('ACTIVE')) {

                    $query .= ' FROM qti_item_bank qib LEFT JOIN qti_item_bank_members qibm ON qibm.item_bank_id = qib.item_bank_id ' . $metadataQuery . $commonQuery . ' AND qibm.item_pk_id IN (' . $itemPkId . ') '
                            . ' AND qibm.is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';
                }

                //Listing only item non-associated item collection
                elseif ($associatedItemCollection == $this->app['cache']->fetch('DELETED')) {

                    //Fetching Associated Item collection
                    $subSql = 'SELECT item_bank_id FROM qti_item_bank_members WHERE item_pk_id =' . $itemPkId
                            . ' AND is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';

                    $itemcollectionResult = $this->app['db']->fetchAll($subSql);

                    foreach ($itemcollectionResult as $key => $value) {

                        $itemcollectionArray[] = $value['item_bank_id'];
                    }

                    if (!empty($itemcollectionArray)) {

                        $nonAssociatedItemcollection = implode(',', $itemcollectionArray);

                        //Fetching the item Collection Not in associated item collection
                        $query .= ' FROM qti_item_bank qib ' . $metadataQuery . $commonQuery . ' AND qib.item_bank_id NOT IN (' . $nonAssociatedItemcollection . ')'
                        ;
                    } else {

                        $query .= ' FROM qti_item_bank qib ' . $metadataQuery . $commonQuery;
                    }
                }

                //If metadata filter is passed , then add into where condition
                if (!empty($metadataRequest)) {

                    $i = 0;

                    foreach ($metadataRequest as $key => $metadataValue) {

                        // Check metadata type for the metadata passed
                        $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                        // Added below code to convert to array for hierarchy data as it has check in IN condition.
                        if (is_numeric($metadataValue)) {

                            //Check if associated metadata is of type hierarchy
                            if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                                // Get all the parent ids of the node passed
                                $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                                $parentNodes = $childNodes = array();
                                if ($this->app['cache']->fetch('parentNodes') == "YES") {
                                    // Get all the parent ids of the node passed
                                    $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $metadataValue);
                                }
                                if ($this->app['cache']->fetch('childNodes') == "YES") {
                                    //Get all the child nodes of the passed node.
                                    $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $metadataValue);
                                }
                                $metadataValue = array_unique(array_merge($parentNodes, $childNodes));
                            }
                        }

                        if ($i > 0) {

                            $orCondition = ' OR ';
                        } else {

                            $orCondition = '';
                        }

                        //Both Lookup and Hierachy
                        if (is_array($metadataValue)) {

                            $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value IN (' . implode(',', $metadataValue) . ')';
                        }
                        //Free Text
                        else {

                            $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value LIKE ' . "'%" . $metadataValue . "%'";
                        }
                        $i++;
                    }$query .= ' AND (' . $tagQuery . ') AND crm.resource_type_id=' . $this->app['cache']->fetch('itembanks');
                }


                // If bankname filter is passed, then add in to where condition.
                if ($bankName != "") {
                    $query .= ' AND qib.item_bank_name LIKE ' . "'%" . str_replace("'", "\'", $bankName) . "%'";
                }

                // If description filter is passed, then add in to where condition.
                if ($description != "") {
                    $query .= ' AND qib.description LIKE ' . "'%" . str_replace("'", "\'", $description) . "%'";
                }

                // If published filter is passed , then add into where condition
                if ($status != '') {
                    $query .= ' AND qs.status_name=' . "'" . $status . "'";
                }

                // If Active or inactive filter is passed , then add into where condition
                if ($active != '') {
                    $query .= ' AND qib.is_deleted=' . $active;
                } else {
                    $query .= ' AND qib.is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';
                }

                $query .= ' GROUP BY qib.item_bank_id ';

                $query .= ' ORDER BY ' . $sortField . ' ' . $sortType;

                if (empty($allItemCollection)) {
                    $query .= ' LIMIT ' . $offset . ',' . $perPage;
                }

                $itemCollectionValues['data'] = $this->app['db']->fetchAll($query);
            }

            $itemCollectionReturnValues = $this->app['db']->fetchAll($query);

            //Fetching the top 3 metadata tag Associated to item collection
            foreach ($itemCollectionReturnValues as $key => $itemBank) {

                $metadataTag = "SELECT DISTINCT(cm.metadata_name) FROM cmn_metadata cm "
                        . "JOIN cmn_resource_metadata crm ON crm.metadata_id = cm.metadata_id "
                        . "JOIN cmn_resource_type crt "
                        . "WHERE crm.resource_id=" . $itemBank['itemBankId']
                        . " AND crm.resource_type_id=" . "'" . $this->app['cache']->fetch('itembanks') . "'"
                        . "LIMIT 0," . $this->app['cache']->fetch('tagList');
                //      echo $metadataTag;die;
                $metadataTagResult = $this->app['db']->fetchAll($metadataTag);

                $metadataName = array();

                foreach ($metadataTagResult as $metadataValues) {

                    array_push($metadataName, $metadataValues['metadata_name']);
                }
                $itemCollectionReturnValues[$key]['metadataName'] = implode(',', $metadataName);
            }

            $itemCollectionValues['data'] = $itemCollectionReturnValues;

            //Return the result array.
            return $itemCollectionValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Itemcollection get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * get the itemCollection count based on the filters
     * @param type $bankName
     * @param type $description
     * @return type
     */
    public function getitemCollectionCount($bankName = NULL, $description = NULL, $status = NULL, $active = NULL, $metadataRequest, $associatedItemCollection = NULL, $itemPkId = NULL, $version = NULL) {

        //Fetching the item collection count based on the type
        $query = "SELECT COUNT(DISTINCT(qib.item_bank_id)) as total";

        $commonQuery = ' JOIN qti_status qs ON qs.status_id=qib.status_id WHERE 1=1 ';

        //If metadata filter is passed , then add into where condition
        if (!empty($metadataRequest)) {

            $metadataQuery .= '  LEFT JOIN cmn_resource_metadata crm ON crm.resource_id=qib.item_bank_id'
                    . ' LEFT JOIN cmn_metadata cm ON cm.metadata_id=crm.metadata_id';
        }

        //Count of All item collection
        if ($associatedItemCollection == NULL) {

            $query .= " FROM qti_item_bank qib " . $metadataQuery . $commonQuery;
        }

        //Count of Associated item collection of item
        elseif ($associatedItemCollection == $this->app['cache']->fetch('ACTIVE')) {

            $query .= ' FROM qti_item_bank qib LEFT JOIN qti_item_bank_members qibm ON qibm.item_bank_id = qib.item_bank_id ' . $metadataQuery . $commonQuery . ' AND qibm.item_pk_id IN (' . $itemPkId
                    . ')'
                    . ' AND qibm.is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';
        }

        //Listing only item non-associated item collection
        elseif ($associatedItemCollection == $this->app['cache']->fetch('DELETED')) {

            //Fetching Associated Item collection
            $subSql = 'SELECT item_bank_id FROM qti_item_bank_members WHERE item_pk_id =' . $itemPkId
                    . '  AND is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';

            $itemcollectionResult = $this->app['db']->fetchAll($subSql);

            foreach ($itemcollectionResult as $key => $value) {

                $itemcollectionArray[] = $value['item_bank_id'];
            }
            if (!empty($itemcollectionArray)) {

                $nonAssociatedItemcollection = implode(',', $itemcollectionArray);

                //Fetching the item Collection Not in associated item collection
                $query .= ' FROM qti_item_bank qib ' . $metadataQuery . $commonQuery . ' AND qib.item_bank_id NOT IN (' . $nonAssociatedItemcollection . ')';
            } else {

                $query .= ' FROM qti_item_bank qib ' . $metadataQuery . $commonQuery;
            }
        }

        //If metadata filter is passed , then add into where condition
        if (!empty($metadataRequest)) {

            $i = 0;

            foreach ($metadataRequest as $key => $metadataValue) {

                // Check metadata type for the metadata passed
                $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                // Added below code to convert to array for hierarchy data as it has check in IN condition.
                if (is_numeric($metadataValue)) {

                    //Check if associated metadata is of type hierarchy
                    if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                        // Get all the parent ids of the node passed
                        $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                        $parentNodes = $childNodes = array();

                        if ($this->app['cache']->fetch('parentNodes') == "YES") {
                            // Get all the parent ids of the node passed
                            $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $metadataValue);
                        }

                        if ($this->app['cache']->fetch('childNodes') == "YES") {
                            //Get all the child nodes of the passed node.
                            $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $metadataValue);
                        }
                        $metadataValue = array_unique(array_merge($parentNodes, $childNodes));
                    }
                }

                if ($i > 0) {

                    $orCondition = ' OR ';
                } else {

                    $orCondition = '';
                }

                //Both Lookup and Hierachy
                if (is_array($metadataValue)) {

                    $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value IN (' . implode(',', $metadataValue) . ')';
                }
                //Free Text
                else {

                    $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value LIKE ' . "'%" . $metadataValue . "%'";
                }
                $i++;
            }$query .= ' AND (' . $tagQuery . ') AND crm.resource_type_id=' . $this->app['cache']->fetch('itembanks');
        }

        // If bankname filter is passed, then add in to where condition.
        if ($bankName != "") {
            $query .= ' AND qib.item_bank_name LIKE ' . "'%" . str_replace("'", "\'", $bankName) . "%'";
        }

        // If description filter is passed, then add in to where condition.
        if ($description != "") {
            $query .= ' AND qib.description LIKE ' . "'%" . str_replace("'", "\'", $description) . "%'";
        }

        // If published filter is passed , then add into where condition
        if ($status != '') {
            $query .= ' AND qs.status_name=' . "'" . $status . "'";
        }

        // If Active or inactive filter is passed , then add into where condition
        if ($active != '') {
            $query .= ' AND qib.is_deleted=' . $active;
        } else {
            $query .= ' AND qib.is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';
        }

        // Get the results
        $totalItemcollection = $this->app['db']->fetchAll($query);

        return $totalItemcollection;
    }

    /**
     * Create the Item collection
     * @param type $itemcollectionData
     * @return boolean
     */
    public function create($itemcollectionData) {

        try {

            //Check the duplicate item collection
            $qb = $this->em->createQueryBuilder();
            $qb->select('qib.itemBankId')
                    ->from('QuizzingPlatform\Entity\QtiItemBank', 'qib')
                    ->where('qib.itemBankName =:itemBankName')
                    ->andWhere('qib.isDeleted =:isDeleted')
                    ->setParameter('itemBankName', $itemcollectionData['itemBankName'])
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
            $itemcollectionExist = $qb->getQuery()->getArrayResult();

            if (empty($itemcollectionExist)) {

                //Fetching the foreign key value(status_id) from qti_status table
                $qtiStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiStatus')->findOneByStatusName(array('statusName' => $itemcollectionData['statusName']));

                $userId = $itemcollectionData['userId'];

                //Insert into qti_item_bank
                $qtiItemBank = new QtiItemBank();
                $qtiItemBank->setItemBankName($itemcollectionData['itemBankName']);
                $qtiItemBank->setDescription($itemcollectionData['description']);
                $qtiItemBank->setStatus($qtiStatus);
                $qtiItemBank->setCreatedBy($userId);
                $qtiItemBank->setCreatedDate($this->dateTime);
                $qtiItemBank->setModifiedBy($userId);
                $qtiItemBank->setModifiedDate($this->dateTime);
                $qtiItemBank->setEffectiveDateFrom($this->dateTime); //Question effective date from
                $qtiItemBank->setEffectiveDateTo($this->effectiveDateTo); // Set question to be effective till 3 years from the date of question creation.
                $qtiItemBank->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                $this->em->persist($qtiItemBank); //Inserting the Above Field Values to QtiItem table
                $this->em->flush();

                // Assign newly created item id to variable.
                $itemBankId = $qtiItemBank->getItemBankId();

                /**
                 *  Store metadata association to the question.
                 */
                $metadataAssociation = $itemcollectionData['metadataAssoc'];

                if (!empty($metadataAssociation)) {

                    $metadataAssoc = $this->app['metadata.repository']->storeMetadataResourceAssociation($this->app['cache']->fetch('itembanks'), $metadataAssociation, $itemBankId, $userId);
                    if (!$metadataAssoc) {
                        $this->app['log']->writeLog("Failed to store question metadata association for item : " . $itemBankId);
                    }
                }

                /*
                 * Insert into qti_item_bank_members for association
                 */
                //change the item collection string to array
                if (!empty($itemcollectionData['associated']))
                    $itemData = $itemcollectionData['associated'];
                else
                    $itemData = array();

                foreach ($itemData as $item) {
                    $itemId = $item['itemId'];
                    $version = $item['version'];
                    if (isset($version)) {
                        $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version);
                        $itemPkId = $itemPkDetails['id'];
                    } else {
                        $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
                        $itemPkId = $latestItemDetails['id'];
                    }

                    //Checks the Duplicate Association
                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('qibm.id')
                            ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                            ->where('qibm.itemPk= :itemPk')
                            ->setParameter('itemPk', $itemPkId)
                            ->andWhere('qibm.itemBank=:itemBank')
                            ->setParameter('itemBank', $itemBankId);
                    $itemExist = $query->getQuery()->getArrayResult();

                    //If its not duplicate , Associate to item collection
                    if (empty($itemExist)) {

                        $userId = $itemcollectionData['userId'];

                        //Insert into qti_item_bank_members table
                        self::createAssociation($itemPkId, $itemBankId, $userId);
                    }
                    //Else return error msg
                    else {
                        return $this->app['cache']->fetch('exists');
                    }
                }
                $this->em->flush();

                //return created itembankId
                return $itemBankId;
            } else {
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Item collection creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the item bank by itembank id
     * @param type $itemBankId
     * @return type
     */
    public function find($itemBankId) {

        //get item bank details
        $qb = $this->em->createQueryBuilder();

        $query = $qb->select('qib.itemBankId', 'qib.itemBankName', 'qib.description', 'qs.statusName', 'oug.firstName', 'oug.lastName')
                ->from('QuizzingPlatform\Entity\QtiItemBank', 'qib')
                ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qib.status')
                ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oug', \Doctrine\ORM\Query\Expr\Join::WITH, 'oug.userId=qib.createdBy')
                ->where('qib.itemBankId = :itemBankId')
                ->andWhere('qib.isDeleted IN (:isDeleted)')
                ->setParameter('itemBankId', $itemBankId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $itemCollectionValues = $qb->getQuery()->getArrayResult();

        $itemCollectionReturnValues = $itemCollectionValues[0];

        if (!empty($itemCollectionReturnValues)) {
            //Fetch Metadata association details 
            $metadataAssoc = $this->app['metadata.repository']->getMetadataResourceAssociation($this->module, $itemBankId);

            if (!empty($metadataAssoc['metadataIds'])) {

                // Get concatenated metadatas to get all the details of the selected metadatas
                $metadataIds = $metadataAssoc['metadataIds'];

                // Delete metadataIds from return array. 
                unset($metadataAssoc['metadataIds']);

                // assign metadata association details to return array.
                $itemCollectionReturnValues['metadataAssoc'] = $metadataAssoc;

                if (!empty($metadataIds)) {

                    //Get complete metadata details which are all associated. 
                    $metadatasDetails = $this->app['metadata.repository']->getMetadataDetails($metadataIds);

                    // assign complete details of metadata's association details to return array.
                    $itemCollectionReturnValues['selectedMetaDetails'] = $metadatasDetails;
                }
            }

            //Get item Association details
            $qb = $this->em->createQueryBuilder();

            $query = $qb->select('qi.itemId as itemId', 'qi.label', 'qi.version')
                    ->from('QuizzingPlatform\Entity\QtiItemBank', 'qib')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm', \Doctrine\ORM\Query\Expr\Join::WITH, 'qibm.itemBank=qib.itemBankId')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qibm.itemPk')
                    ->where('qib.itemBankId = :itemBankId')
                    ->andWhere('qib.isDeleted IN (:itembankDeleted)')
                    ->andWhere('qi.isDeleted IN (:itemDeleted)')
                    ->andWhere('qibm.isDeleted IN (:assocDeleted)')
                    ->setParameter('itemBankId', $itemBankId)
                    ->setParameter('itembankDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('itemDeleted', $this->statusArr)
                    ->setParameter('assocDeleted', $this->app['cache']->fetch('ACTIVE'));

            $itemAssociation = $qb->getQuery()->getArrayResult();

            $itemCollectionReturnValues['getItems'] = $itemAssociation;


            return $itemCollectionReturnValues;
        } else {
            return false;
        }
    }

    /**
     * Update the item collection details
     * @param type $itemcollectionData
     * @param type $itemBankId
     * @return boolean
     */
    public function update($itemcollectionData, $itemBankId) {

        try {

            //Check the duplicate item collection
            $qb = $this->em->createQueryBuilder();
            $qb->select('qib.itemBankId')
                    ->from('QuizzingPlatform\Entity\QtiItemBank', 'qib')
                    ->where('qib.itemBankName =:itemBankName')
                    ->andWhere('qib.itemBankId != :itemBankId')
                    ->andWhere('qib.isDeleted =:isDeleted')
                    ->setParameter('itemBankName', $itemcollectionData['itemBankName'])
                    ->setParameter('itemBankId', $itemBankId)
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
            $itemcollectionExist = $qb->getQuery()->getArrayResult();

            if (empty($itemcollectionExist)) {

                //Fetching the foreign key value(status_id) from qti_status table
                $qtiStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiStatus')->findOneByStatusName(array('statusName' => $itemcollectionData['statusName']));

                $userId = $itemcollectionData['userId'];

                //Update the qti_item_bank
                $qtiItemBank = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemBankId);
                $qtiItemBank->setItemBankName($itemcollectionData['itemBankName']);
                $qtiItemBank->setDescription($itemcollectionData['description']);
                $qtiItemBank->setStatus($qtiStatus);
                $qtiItemBank->setModifiedBy($userId);
                $qtiItemBank->setModifiedDate($this->dateTime);
                $this->em->flush();

                /**
                 *  Store metadata association to the item collection.
                 */
                $metadataAssociation = $itemcollectionData['metadataAssoc'];

                if (!empty($metadataAssociation)) {
                    //Delete the old record and recreate it.

                    $this->app['metadata.repository']->deleteMetadataResourceAssociation($this->app['cache']->fetch('itembanks'), $itemBankId);

                    $metadataAssoc = $this->app['metadata.repository']->storeMetadataResourceAssociation($this->app['cache']->fetch('itembanks'), $metadataAssociation, $itemBankId, $userId);
                    if (!$metadataAssoc) {
                        $this->app['log']->writeLog("Failed to store question metadata association for item : " . $itemBankId);
                    }
                }

                /*
                 * Insert into qti_item_bank_members for association
                 */

                //change the item collection string to array
                $assocItem = $itemcollectionData['associated'];
                //For item association
                if (!empty($assocItem)) {
                    $qb = $this->em->createQueryBuilder();
                    $qb->update('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                            ->set('qibm.isDeleted', $this->app['cache']->fetch('DELETED'))
                            ->where('qibm.itemBank =:itemBank')
                            ->setParameter('itemBank', $itemBankId)
                            ->getQuery()->execute();
                    $assocItemData = $assocItem;
                    foreach ($assocItemData as $item) {

                        $userId = $itemcollectionData['userId'];
                        $itemId = $item['itemId'];
                        $version = $item['version'];
                        if (isset($version)) {
                            $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version);
                            $itemPkId = $itemPkDetails['id'];
                        } else {
                            $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
                            $itemPkId = $latestItemDetails['id'];
                        }

                        $qb = $this->em->createQueryBuilder();
                        $query = $qb->select('qibm.id')
                                ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                                ->where('qibm.itemPk= :itemPk')
                                ->setParameter('itemPk', $itemPkId)
                                ->andWhere('qibm.itemBank=:itemBank')
                                ->setParameter('itemBank', $itemBankId);
                        $itemCollectionExist = $query->getQuery()->getArrayResult();

                        //If item collection is not associated ,then associate to item
                        if (empty($itemCollectionExist)) {

                            //Insert into qti_item_bank_members table
                            self::createAssociation($itemPkId, $itemBankId, $userId);
                        }
                        //If its associated , change the status is active
                        else {

                            $qb = $this->em->createQueryBuilder();
                            $qb->select('qibm.id')
                                    ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                                    ->where('qibm.itemBank=:itemBank')
                                    ->andwhere('qibm.itemPk=:itemPk')
                                    ->setParameter('itemBank', $itemBankId)
                                    ->setParameter('itemPk', $itemPkId);
                            $itemBank = $qb->getQuery()->getArrayResult();

                            $qtiItemBankMembers = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBankMembers', $itemBank[0]['id']);
                            $qtiItemBankMembers->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                        }
                    }$this->em->flush();
                }

                //return created itembankId
                return true;
            } else {
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Item collection creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Delete the item collection
     * @param type $itemBankId
     * @return boolean
     */
    public function delete($itemBankId) {

        try {
            //Check whether item is associated to Quiz, if so get associated quiz names.
            $qb = $this->em->createQueryBuilder();
            $qb->select('qib.itemBankId', 'qt.title as testName')
                    ->from('QuizzingPlatform\Entity\QtiTestItemBanks', 'qtib')
                    ->join('QuizzingPlatform\Entity\QtiItemBank', 'qib', \Doctrine\ORM\Query\Expr\Join::WITH, 'qib.itemBankId=qtib.itemBank')
                    ->leftJoin('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qtib.testPk')
                    ->where('qib.itemBankId IN (:itemBankId)')
                    ->setParameter('itemBankId', $itemBankId);
            $testItemAssoc = $qb->getQuery()->getArrayResult();

            //If the itembank associated with quiz change the status to INACTIVE
            if (!empty($testItemAssoc)) {

                $qtiItemBank = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemBankId);
                $qtiItemBank->setIsDeleted($this->app['cache']->fetch('INACTIVE'));

                $this->em->flush();
                $this->app['log']->writeLog("Successfully soft deleted the Itembank : " . $itemBankId);

                return true;
            }
            //If its not associated with Quiz change the status to DELETED
            elseif (empty($testItemAssoc)) {

                $qtiItemBank = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemBankId);
                $qtiItemBank->setIsDeleted($this->app['cache']->fetch('DELETED'));

                $this->em->flush();
                $this->app['log']->writeLog("Successfully soft deleted the Itembank : " . $itemBankId);

                return true;
            } else {

                $this->app['log']->writeLog("Failed to soft delete the Itembank : " . $itemBankId);
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item collection Deletion Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Get the item count
     * @param type $label
     * @param type $metadataRequest
     * @param type $action
     * @param type $itemBankId
     * @return type
     */
    public function getItemsCount($label = NULL, $metadataRequest = array(), $action = NULL, $itemBankId = NULL, $testId = NULL, $identifier = NULL, $assocItems = NULL, $version = NULL) {


        //Fetching the item collection Details
        $query = "SELECT COUNT(qi.id) as total FROM qti_item qi "
                . " JOIN qti_item_type qit ON qit.item_type_id=qi.item_type_id"
                . " JOIN qti_status qs ON qs.status_id=qi.status_id";

        //If metadata filter is passed , then add into where condition
        if (!empty($metadataRequest)) {
            $metadataQuery .= '  LEFT JOIN cmn_resource_metadata crm ON crm.resource_id=qi.id'
                    . ' LEFT JOIN cmn_metadata cm ON cm.metadata_id=crm.metadata_id';
            $query .= $metadataQuery;
        }

        //If the action is view listing only associated items
        if ($action == 'view' || $action == 'publish') {
            if (!empty($itemBankId)) {
                $query .= ' JOIN  qti_item_bank_members qibm ON qibm.item_pk_id=qi.id  WHERE qibm.item_bank_id=' . $itemBankId . ''
                        . ' AND qi.is_deleted IN (' . implode(',', $this->statusArr) . ') AND qi.parent=' . $this->app['cache']->fetch('DELETED')  //Displaying both active and inactive associated items in itembank
                        . ' AND qibm.is_deleted = ' . $this->app['cache']->fetch('ACTIVE');
            } elseif (!empty($testId)) {
                if (empty($version)) {
                    $latestTestData = $this->app['tests.repository']->getLatestTestId($testId);
                    $version = $latestTestData['version'];
                }
                $testPkId = self::getTestPkByVersion($testId, $version);

                $query .= ' JOIN  qti_test_items qti ON qti.item_pk_id=qi.id  WHERE qti.test_pk_id=' . $testPkId . ''
                        . ' AND qi.is_deleted IN (' . implode(',', $this->statusArr) . ') AND qi.parent=' . $this->app['cache']->fetch('DELETED')  //Displaying both active and inactive associated items in test
                        . ' AND qti.is_deleted = ' . $this->app['cache']->fetch('ACTIVE');
            }
        } elseif ($action == 'edit' && !empty($assocItems)) {
            $query .= " WHERE (qi.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . ' OR (qi.is_deleted =' . $this->app['cache']->fetch('INACTIVE') . ' AND qi.id IN (' . $assocItems . ')))'
                    . " AND qi.id IN ( SELECT MAX(qia.id) FROM qti_item qia WHERE (qia.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . ' OR (qia.is_deleted =' . $this->app['cache']->fetch('INACTIVE') . ' AND qia.id IN (' . $assocItems . ')))'
                    . " AND qi.parent=" . $this->app['cache']->fetch('DELETED') . " GROUP BY qia.qti_identifier )";
            $query .= ' AND qs.status_name IN ("published","authoring")';
        } else {
            $query .= " WHERE qi.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . " AND qi.parent=" . $this->app['cache']->fetch('DELETED')
                    . " AND qi.id IN ( SELECT MAX(qia.id) FROM qti_item qia WHERE qia.is_deleted=" . $this->app['cache']->fetch('ACTIVE')
                    . " GROUP BY qia.qti_identifier )";
            $query .= ' AND qs.status_name IN ("published","authoring")';
        } //If metadata filter is passed , then add into where condition
        if (!empty($metadataRequest)) {

            $i = 0;

            foreach ($metadataRequest as $key => $metadataValue) {

                // Added below code to convert to array for hierarchy data as it has check in IN condition.
                if (is_numeric($metadataValue)) {

                    // Check metadata type for the metadata passed
                    $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                    //Check if associated metadata is of type hierarchy
                    if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                        // Get all the parent ids of the node passed
                        $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                        $parentNodes = $childNodes = array();
                        if ($this->app['cache']->fetch('parentNodes') == "YES") {
                            // Get all the parent ids of the node passed
                            $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $metadataValue);
                        }

                        if ($this->app['cache']->fetch('childNodes') == "YES") {
                            //Get all the child nodes of the passed node.
                            $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $metadataValue);
                        }
                        $metadataValue = array_unique(array_merge($parentNodes, $childNodes));
                    }
                }
                if ($i > 0) {

                    $orCondition = ' OR ';
                } else {

                    $orCondition = '';
                }
                if (is_array($metadataValue)) {

                    $value = implode(',', $metadataValue);

                    $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value IN (' . $value . ')';
                } else {

                    $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value LIKE ' . "'%" . $metadataValue . "%'";
                }

                $i++;
            }$query .= ' AND (' . $tagQuery . ')';
        }


        // If question label filter is passed, then add in to where condition.
        if ($label != "") {
            $query .= ' AND qi.label LIKE ' . "'%" . str_replace("'", "\'", $label) . "%'";
        }

        // If question identifier filter is passed, then add in to where condition.
        if ($identifier != "") {
            $query .= ' AND qi.qti_identifier LIKE ' . "'%" . str_replace("'", "\'", $identifier) . "%'";
        }

        $itemCollectionReturnValues = $this->app['db']->fetchAll($query);

        $itemCollectionValues = $itemCollectionReturnValues[0]['total'];

        //Return the result array.
        return $itemCollectionValues;
    }

    /**
     * Fetching item list for association
     * @param type $itemRequest
     * @param type $metadataRequest
     * @return boolean
     */
    public function getItemlist($itemRequest, $metadataRequest) {
        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $label = $action = $itemBankId = $testId = $identifier = $version = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "id";


            // Declare a result array to return. 
            $itemReturnValues = array();

            // Check if request is not null.
            if (!empty($itemRequest)) {

                foreach ($itemRequest as $key => $request) {

                    // get values for $label = $identifier = $itemTypeId = $satus ;
                    $$key = $request;
                }


                // Logic found in CommonHelper class to get the offset value.
                $offset = CommonHelper::getOffset($page, $perPage);

                if (isset($sort)) {
                    // If sorting field is passed.
                    // Logic found in CommonHelper class to get the sorting type and sorting field.
                    $sort = CommonHelper::getSortingDetails($sort);

                    // Sorting type ASC/DESC
                    $sortType = $sort['type'];

                    // Sorting field name
                    $sortField = $sort['field'];
                }
            }  // Fetch the associated items of particular item_bank_id
            $assocItems = '';  //sorting in the subquery
            if ($action == 'edit') {
                $subSort = ($sortType == 'ASC') ? 'DESC' : 'ASC';
                //Fetching the associated item details

                if (!empty($itemBankId)) {
                    $assocQuery = 'SELECT qibm.item_pk_id , qi.version as version,qi.label as label,qi.qti_identifier as identifier,qs.status_name as status FROM qti_item_bank_members qibm'
                            . ' LEFT JOIN qti_item qi ON qi.id = qibm.item_pk_id '
                            . ' LEFT JOIN qti_status qs ON qi.status_id = qs.status_id ' .
                            ' WHERE qibm.item_bank_id = ' . $itemBankId . ' AND qibm.is_deleted=' . $this->app['cache']->fetch('ACTIVE');
                } elseif (!empty($testId)) {
                    if (empty($version)) {
                        $latestTestData = $this->app['tests.repository']->getLatestTestId($testId);
                        $version = $latestTestData['version'];
                    }
                    $testPkId = self::getTestPkByVersion($testId, $version);

                    $assocQuery = 'SELECT qti.item_pk_id , qi.version as version, qi.label as label,qi.qti_identifier as identifier,qs.status_name as status FROM qti_test_items qti'
                            . ' LEFT JOIN qti_item qi ON qi.id = qti.item_pk_id '
                            . ' LEFT JOIN qti_status qs ON qi.status_id = qs.status_id ' .
                            ' WHERE qti.test_pk_id = ' . $testPkId . ' AND qti.is_deleted=' . $this->app['cache']->fetch('ACTIVE');
                    ;
                }
                $assocQuery .= ' ORDER BY ' . $sortField . ' ' . $subSort;

                $assocResult = $this->app['db']->fetchAll($assocQuery);

                foreach ($assocResult as $key => $value) {

                    $assocArray[] = $value['item_pk_id'];
                }if (!empty($assocArray)) {
                    $assocItems = implode(',', $assocArray);
                }
            }
            $totalItems = self::getItemsCount($label, $metadataRequest, $action, $itemBankId, $testId, $identifier, $assocItems, $version);

            // Get Total of all the items based on the applied filters. 
            $itemCollectionValues['total'] = $totalItems;
            $itemCollectionValues['data'] = array();

            if ($totalItems > 0) {

                //Fetching the item collection Details
                $query = "SELECT qi.item_id as id,qi.version as version,qi.label as label,qi.qti_identifier as identifier,qs.status_name as status FROM qti_item qi"
                        . " JOIN qti_item_type qit ON qit.item_type_id=qi.item_type_id"
                        . " JOIN qti_status qs ON qs.status_id=qi.status_id";

                //If metadata filter is passed , then add into where condition
                if (!empty($metadataRequest)) {

                    $metadataQuery .= '  LEFT JOIN cmn_resource_metadata crm ON crm.resource_id=qi.id'
                            . ' LEFT JOIN cmn_metadata cm ON cm.metadata_id=crm.metadata_id';
                    $query .= $metadataQuery;
                }
                //If the action is view, return only associated items
                if ($action == 'view' || $action == 'publish') {
                    if (!empty($itemBankId)) {
                        $query .= ' JOIN  qti_item_bank_members qibm ON qibm.item_pk_id=qi.id  WHERE qibm.item_bank_id=' . $itemBankId . ''
                                . ' AND qi.is_deleted IN (' . implode(',', $this->statusArr) . ') AND qi.parent=' . $this->app['cache']->fetch('DELETED') //Displaying both active and inactive items in itembank
                                . ' AND qibm.is_deleted = ' . $this->app['cache']->fetch('ACTIVE');
                    } elseif (!empty($testId)) {
                        if (empty($version)) {
                            $latestTestData = $this->app['tests.repository']->getLatestTestId($testId);
                            $version = $latestTestData['version'];
                        }
                        $testPkId = self::getTestPkByVersion($testId, $version);
                        $query .= ' JOIN  qti_test_items qti ON qti.item_pk_id=qi.id  WHERE qti.test_pk_id=' . $testPkId . ''
                                . ' AND qi.is_deleted IN (' . implode(',', $this->statusArr) . ') AND qi.parent=' . $this->app['cache']->fetch('DELETED') //Displaying both active and inactive items in test
                                . ' AND qti.is_deleted = ' . $this->app['cache']->fetch('ACTIVE');
                    }
                } elseif ($action == 'edit' && !empty($assocItems)) {
                    $query .= " WHERE (qi.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . ' OR (qi.is_deleted =' . $this->app['cache']->fetch('INACTIVE') . ' AND qi.id IN (' . $assocItems . ')))'
                            . " AND qi.id IN ( SELECT MAX(qia.id) FROM qti_item qia WHERE (qia.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . ' OR (qia.is_deleted =' . $this->app['cache']->fetch('INACTIVE') . ' AND qia.id IN (' . $assocItems . ')))'
                            . " AND qi.parent=" . $this->app['cache']->fetch('DELETED') . " GROUP BY qia.qti_identifier )";
                    $query .= ' AND qs.status_name IN ("published","authoring")';
                } else {
                    $query .= " WHERE qi.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . " AND qi.parent=" . $this->app['cache']->fetch('DELETED')
                            . " AND qi.id IN ( SELECT MAX(qia.id) FROM qti_item qia WHERE qia.is_deleted=" . $this->app['cache']->fetch('ACTIVE')
                            . " GROUP BY qia.qti_identifier )";
                    $query .= ' AND qs.status_name IN ("published","authoring")';
                }

                //If metadata filter is passed , then add into where condition
                if (!empty($metadataRequest)) {

                    $i = 0;

                    foreach ($metadataRequest as $key => $metadataValue) {

                        // Added below code to convert to array for hierarchy data as it has check in IN condition.
                        if (is_numeric($metadataValue)) {

                            // Check metadata type for the metadata passed
                            $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                            //Check if associated metadata is of type hierarchy
                            if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                                // Get all the parent ids of the node passed
                                $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                                $parentNodes = $childNodes = array();
                                if ($this->app['cache']->fetch('parentNodes') == "YES") {
                                    // Get all the parent ids of the node passed
                                    $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $metadataValue);
                                }

                                if ($this->app['cache']->fetch('childNodes') == "YES") {
                                    //Get all the child nodes of the passed node.
                                    $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $metadataValue);
                                }
                                $metadataValue = array_unique(array_merge($parentNodes, $childNodes));
                            }
                        }
                        if ($i > 0) {

                            $orCondition = ' OR ';
                        } else {

                            $orCondition = '';
                        }
                        if (is_array($metadataValue)) {

                            $value = implode(',', $metadataValue);

                            $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value IN (' . $value . ')';
                        } else {

                            $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value LIKE ' . "'%" . $metadataValue . "%'";
                        }

                        $i++;
                    }$query .= ' AND (' . $tagQuery . ')';
                }


                // If question label filter is passed, then add in to where condition.
                if ($label != "") {
                    $query .= ' AND qi.label LIKE ' . "'%" . str_replace("'", "\'", $label) . "%'";
                }

                // If question identifier filter is passed, then add in to where condition.
                if ($identifier != "") {
                    $query .= ' AND qi.qti_identifier LIKE ' . "'%" . str_replace("'", "\'", $identifier) . "%'";
                }



                //If action is create listing all the items
                if ($action == 'create' || $action == 'view' || $action == 'publish') {
                    $query .= ' ORDER BY ' . $sortField . ' ' . $sortType . ' LIMIT ' . $offset . ',' . $perPage;
                }

                //If action is edit , assoicated items in top and non-associated items in bottom
                elseif ($action == 'edit') {
                    if (!empty($assocArray)) {
                        $sortfield = implode(',', $assocArray);

                        $sorttype = 'DESC';
                        $subOrderBy = ' FIELD ( qi.id ,' . $sortfield . ') ' . $sorttype . ' , ' . $sortField . ' ' . $sortType;
                    } else {
                        $subOrderBy = $sortField . ' ' . $sortType;
                    }

                    $query .= ' ORDER BY ' . $subOrderBy . ' LIMIT ' . $offset . ',' . $perPage;
                }

                $itemCollectionReturnValues = $this->app['db']->fetchAll($query);

                $itemCollectionValues['data'] = $itemCollectionReturnValues;
            }
            //Return the result array.
            return $itemCollectionValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Create the item and Item bank association
     * @param type $itemId
     * @param type $itemBankId
     * @param type $userId
     * @return boolean
     */
    public function createAssociation($itemPkId = NULL, $itemBankId = NULL, $userId = NULL) {
        try {

            //Get Item repository
            $itemPk = $this->em->getRepository('QuizzingPlatform\Entity\QtiItem')->findOneById(array('id' => $itemPkId));

            //get itemBank repository
            $itemCollectionId = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemBank')->findOneByitemBankId(array('itemBankId' => $itemBankId));

            //Associate/Dissociate items to item collection
            $qtiItemBankMembers = new QtiItemBankMembers();
            $qtiItemBankMembers->setItemPk($itemPk); //Inserting itemId
            $qtiItemBankMembers->setItemBank($itemCollectionId); //Inserting itembankid
            $qtiItemBankMembers->setCreatedBy($userId); //Inserting createdby
            $qtiItemBankMembers->setCreatedDate($this->dateTime); //Inserting created date
            $qtiItemBankMembers->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
            $qtiItemBankMembers->setModifiedBy($userId); //Inserting modified by
            $qtiItemBankMembers->setModifiedDate($this->dateTime); //Inserting modified date
            $this->em->persist($qtiItemBankMembers); //Inserting the Above Field Values to QtiItemBankMembers table
            $this->em->flush();
        } catch (Exception $e) {
            //Add exceptions to logger.
            $msg = 'Item Association all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Fetching all the item collection based on the search filters(bank name and description,tag fields) and action(create,edit,view)
     * @param type $itemCollectionRequest
     * @return boolean
     */
    public function getItembankList($itemCollectionRequest, $metadataRequest) {

        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $bankName = $description = $action = $testId = $version = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "bankName";

            // Declare a result array to return. 
            $itemCollectionValues = array();

            // Check if request is not null.
            if (!empty($itemCollectionRequest)) {

                foreach ($itemCollectionRequest as $key => $mrequest) {

                    // get values for $bankName,$description,$perPage  
                    $$key = $mrequest;
                }

                // Logic found in CommonHelper class to get the offset value.
                $offset = CommonHelper::getOffset($page, $perPage);

                if (isset($sort)) {
                    // If sorting field is passed.
                    // Logic found in CommonHelper class to get the sorting type and sorting field.
                    $sort = CommonHelper::getSortingDetails($sort);

                    // Sorting type ASC/DESC
                    $sortType = $sort['type'];

                    // Sorting field name
                    $sortField = $sort['field'];
                }
            }$assocItembanks = '';

            if (empty($version)) {
                $latestTestData = $this->app['tests.repository']->getLatestTestId($testId);
                $version = $latestTestData['version'];
            }
            $testPkId = self::getTestPkByVersion($testId, $version);

            //sorting in the subquery
            if ($action == 'edit') {
                $subSort = ($sortType == 'ASC') ? 'DESC' : 'ASC';
                //Fetching the associated item details


                $assocQuery = 'SELECT qtib.item_bank_id , qib.item_bank_name as bankName  FROM qti_test_item_banks qtib'
                        . ' LEFT JOIN qti_item_bank qib ON qib.item_bank_id = qtib.item_bank_id WHERE qtib.test_pk_id = ' . $testPkId .
                        ' AND qtib.is_deleted=' . $this->app['cache']->fetch('ACTIVE');


                $assocQuery .= ' ORDER BY ' . $sortField . ' ' . $subSort;

                $assocResult = $this->app['db']->fetchAll($assocQuery);

                foreach ($assocResult as $key => $value) {

                    $assocArray[] = $value['item_bank_id'];
                }if (!empty($assocArray)) {
                    $assocItembanks = implode(',', $assocArray);
                }
            }
            // Get Total of all the item collection based on the applied filters. 
            $totalItemcollection = self::getItembankListCount($bankName, $description, $metadataRequest, $action, $testId, $assocItembanks, $version);
            $itemCollectionValues['total'] = $totalItemcollection; // Total Item collection count for pagination.
            $itemCollectionValues['data'] = array();

            // Check if count is greater than 0
            if ($totalItemcollection > 0) {

                //Fetching the item collection Details
                $query = "SELECT qib.item_bank_id as itemBankId ,qib.item_bank_name as bankName,qib.description FROM qti_item_bank qib JOIN qti_status qs ON qs.status_id=qib.status_id ";

                //If metadata filter is passed , then add into where condition
                if (!empty($metadataRequest)) {

                    $metadataQuery .= '  LEFT JOIN cmn_resource_metadata crm ON crm.resource_id=qib.item_bank_id'
                            . ' LEFT JOIN cmn_metadata cm ON cm.metadata_id=crm.metadata_id';
                    $query .= $metadataQuery;
                }

                //If the action is view, return only associated item banks
                if ($action == 'view') {

                    $query .= ' JOIN  qti_test_item_banks qtib ON qtib.item_bank_id=qib.item_bank_id  WHERE qtib.test_pk_id=' . $testPkId . ''
                            . ' AND qtib.is_deleted = ' . $this->app['cache']->fetch('ACTIVE')
                            . ' AND qib.is_deleted IN (' . implode(',', $this->statusArr) . ')'; //Display both active and inactive itembanks of test
                } elseif ($action == 'edit' && !empty($assocItembanks)) {
                    //return only active itembanks
                    $query .= " WHERE (qib.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . ' OR (qib.is_deleted = ' . $this->app['cache']->fetch('INACTIVE') . ' AND qib.item_bank_id IN (' . $assocItembanks . ')))';
                } else {
                    $query .= ' WHERE qib.is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';
                }// If bankname filter is passed, then add in to where condition.
                if ($bankName != "") {
                    $query .= ' AND qib.item_bank_name LIKE ' . "'%" . str_replace("'", "\'", $bankName) . "%'";
                }

                // If description filter is passed, then add in to where condition.
                if ($description != "") {
                    $query .= ' AND qib.description LIKE ' . "'%" . str_replace("'", "\'", $description) . "%'";
                }



                //If metadata filter is enable
                if (!empty($metadataRequest)) {

                    $i = 0;

                    foreach ($metadataRequest as $key => $metadataValue) {

                        // Added below code to convert to array for hierarchy data as it has check in IN condition.
                        if (is_numeric($metadataValue)) {

                            // Check metadata type for the metadata passed
                            $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                            //Check if associated metadata is of type hierarchy
                            if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                                // Get all the parent ids of the node passed
                                $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                                $parentNodes = $childNodes = array();

                                if ($this->app['cache']->fetch('parentNodes') == "YES") {
                                    // Get all the parent ids of the node passed
                                    $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $metadataValue);
                                }

                                if ($this->app['cache']->fetch('childNodes') == "YES") {
                                    //Get all the child nodes of the passed node.
                                    $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $metadataValue);
                                }
                                $metadataValue = array_unique(array_merge($parentNodes, $childNodes));
                            }
                        }

                        if ($i > 0) {

                            $orCondition = ' OR ';
                        } else {

                            $orCondition = '';
                        }
                        if (is_array($metadataValue)) {

                            $value = implode(',', $metadataValue);

                            $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value IN (' . $value . ')';
                        } else {

                            $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value LIKE ' . "'%" . $metadataValue . "%'";
                        }

                        $i++;
                    }$query .= ' AND (' . $tagQuery . ')';
                }

                $query .= ' GROUP BY itemBankId';
                //If action is create listing all the items
                if ($action == 'create' || $action == 'view') {
                    $query .= ' ORDER BY ' . $sortField . ' ' . $sortType . ' LIMIT ' . $offset . ',' . $perPage;
                }


                if ($action == 'edit') {
                    if (!empty($assocArray)) {
                        $sortfield = implode(',', $assocArray);

                        $sorttype = 'DESC';
                        $subOrderBy = ' FIELD ( qib.item_bank_id ,' . $sortfield . ') ' . $sorttype . ' , ' . $sortField . ' ' . $sortType;
                    } else {
                        $subOrderBy = $sortField . ' ' . $sortType;
                    }

                    $query .= ' ORDER BY ' . $subOrderBy . ' LIMIT ' . $offset . ',' . $perPage;
                }

                $itemCollectionReturnValues = $this->app['db']->fetchAll($query);


                $itemCollectionValues['data'] = $itemCollectionReturnValues;
            }

            //Return the result array.
            return $itemCollectionValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Itemcollection get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Get Item bank count
     * @param type $bankName
     * @param type $description
     * @param type $metadataRequest
     * @param type $action
     * @param type $testId
     * @return type
     */
    public function getItembankListCount($bankName = NULL, $description = NULL, $metadataRequest = NULL, $action = NULL, $testId = NULL, $assocItembanks = NULL, $version = NULL) {

        //Fetching the item collection Details
        $query = "SELECT COUNT(DISTINCT(qib.item_bank_id)) as total FROM qti_item_bank qib "
                . " JOIN qti_status qs ON qs.status_id=qib.status_id";

        //If metadata filter is passed , then add into where condition
        if (!empty($metadataRequest)) {
            $metadataQuery .= '  LEFT JOIN cmn_resource_metadata crm ON crm.resource_id=qib.item_bank_id'
                    . ' LEFT JOIN cmn_metadata cm ON cm.metadata_id=crm.metadata_id';
            $query .= $metadataQuery;
        }

        //If the action is view listing only associated item banks
        if ($action == 'view') {
            if (empty($version)) {
                $latestTestData = $this->app['tests.repository']->getLatestTestId($testId);
                $version = $latestTestData['version'];
            }
            $testPkId = self::getTestPkByVersion($testId, $version);

            $query .= ' JOIN  qti_test_item_banks qtib ON qtib.item_bank_id=qib.item_bank_id  WHERE qtib.test_pk_id=' . $testPkId . ''
                    . ' AND qib.is_deleted IN (' . implode(',', $this->statusArr) . ')';
        } elseif ($action == 'edit' && !empty($assocItembanks)) {
            //return only active itembanks
            $query .= " WHERE (qib.is_deleted = " . $this->app['cache']->fetch('ACTIVE') . ' OR (qib.is_deleted = ' . $this->app['cache']->fetch('INACTIVE') . ' AND qib.item_bank_id IN (' . $assocItembanks . ')))';
        } else {
            $query .= ' WHERE qib.is_deleted IN (' . $this->app['cache']->fetch('ACTIVE') . ')';
        }

        //If metadata filter is passed , then add into where condition
        if (!empty($metadataRequest)) {

            $i = 0;

            foreach ($metadataRequest as $key => $metadataValue) {

                // Added below code to convert to array for hierarchy data as it has check in IN condition.
                if (is_numeric($metadataValue)) {

                    // Check metadata type for the metadata passed
                    $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                    //Check if associated metadata is of type hierarchy
                    if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                        // Get all the parent ids of the node passed
                        $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                        $parentNodes = $childNodes = array();

                        if ($this->app['cache']->fetch('parentNodes') == "YES") {
                            // Get all the parent ids of the node passed
                            $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $metadataValue);
                        }

                        if ($this->app['cache']->fetch('childNodes') == "YES") {
                            //Get all the child nodes of the passed node.
                            $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $metadataValue);
                        }
                        $metadataValue = array_unique(array_merge($parentNodes, $childNodes));
                    }
                }
                if ($i > 0) {

                    $orCondition = ' OR ';
                } else {

                    $orCondition = '';
                }
                if (is_array($metadataValue)) {

                    $value = implode(',', $metadataValue);

                    $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value IN (' . $value . ')';
                } else {

                    $tagQuery .= $orCondition . ' cm.metadata_id=' . $key . ' AND crm.value LIKE ' . "'%" . $metadataValue . "%'";
                }

                $i++;
            }$query .= ' AND (' . $tagQuery . ')';
        }


        // If bankname filter is passed, then add in to where condition.
        if ($bankName != "") {
            $query .= ' AND qib.item_bank_name LIKE ' . "'%" . str_replace("'", "\'", $bankName) . "%'";
        }

        // If description filter is passed, then add in to where condition.
        if ($description != "") {
            $query .= ' AND qib.description LIKE ' . "'%" . str_replace("'", "\'", $description) . "%'";
        }


        $itemCollectionReturnValues = $this->app['db']->fetchAll($query);

        $itemCollectionValues = $itemCollectionReturnValues[0]['total'];

        //Return the result array.
        return $itemCollectionValues;
    }

    /**
     * Get the testpkid by version and testid
     * @param type $testId
     * @param type $version
     * @return type
     */
    public function getTestPkByVersion($testId, $version) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('qt.id')
                ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                ->where('qt.version=:version')
                ->andWhere('qt.testId=:testId')
                ->andWhere('qt.isDeleted=:isDeleted')
                ->setParameter('version', $version)
                ->setParameter('testId', $testId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
        $testPk = $qb->getQuery()->getArrayResult();
        $testPkId = $testPk[0]['id'];
        return $testPkId;
    }

    /**
     * @Desc Check whether the item collection exists or not
     * @param type $id
     * @param type $inActive
     * @return type
     */
    public function itemCollectionExists($id, $inActive = NULL) {

        // Check wether item exists for the passed item ids.
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.itemBankId as id')
                ->from('QuizzingPlatform\Entity\QtiItemBank', 'qi')
                ->where('qi.itemBankId = :itemBankId')
                ->setParameter('itemBankId', $id)
                ->andWhere('qi.isDeleted = :isDeleted')
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));



        $itemIds = $qb->getQuery()->getArrayResult();

        return $itemIds;
    }

    /**
     * @Desc : Publish the item collection and all associated questions
     * @param type $itemCollectionId
     * @return boolean
     */
    public function publish($itemCollectionId, $itemCollectionPublishRequest) {
        try {

            //Fetching the foreign key value(status_id) from qti_status table
            $qtiStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiStatus')->findOneByStatusName(array('statusName' => $itemCollectionPublishRequest['statusDependedValue']));
            $qtiStatusId = $qtiStatus->getStatusId();


            if ($itemCollectionPublishRequest['publishValue'] == 'all' ) {
                if($itemCollectionPublishRequest['statusDependedValue'] == 'Published')
                {
                // Publish the itembank
                $qtiItemCollection = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemCollectionId);
                $qtiItemCollection->setStatus($qtiStatus);
                $this->em->flush();
                }
                // Publish the item
                $appendQuery = " AND QM.item_pk_id = QI.id";
            } else if ($itemCollectionPublishRequest['publishValue'] == 'selected') {
                // Publish the item
                $appendQuery = " AND QI.item_id in (" . $itemCollectionPublishRequest['itemIds'] . ")";
            }

            // Publish the item
            $query = "UPDATE qti_item QI, qti_item_bank_members QM SET QI.status_id = " . $qtiStatusId . " WHERE QM.item_bank_id = " . $itemCollectionId . $appendQuery;

            $itemCollectionReturnValues = $this->app['db']->executeQuery($query);

            //if all the items are selectred n clicked publish selected item, then itembank should automatically get published
            if ($itemCollectionPublishRequest['publishValue'] == 'selected' && $itemCollectionPublishRequest['statusDependedValue'] == 'Published') {
                $qb = $this->em->createQueryBuilder();
                $qb->select('IDENTITY(qibm.itemPk)')
                        ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                        ->where('qibm.itemBank=:itemBank')
                        ->andwhere('qibm.isDeleted=:isDeleted')
                        ->setParameter('itemBank', $itemCollectionId)
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
                $itemBankMembers = $qb->getQuery()->getArrayResult();

                $qb = $this->em->createQueryBuilder();
                $qb->select('IDENTITY(qibm.itemPk)')
                        ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                        ->Join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qibm.itemPk=qi.id')
                        ->where('qibm.itemBank=:itemBank')
                        ->andwhere('qibm.isDeleted=:isDeleted')
                        ->andwhere('qi.status=:status')
                        ->setParameter('itemBank', $itemCollectionId)
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                        ->setParameter('status', $qtiStatusId);
                $itemBankMembersPublished = $qb->getQuery()->getArrayResult();
                //echo count($itemBankMembers)."^^^^".count($itemBankMembersPublished);die;
                if (count($itemBankMembers) == count($itemBankMembersPublished)) {
                    $qtiItemCollection = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemCollectionId);
                    $qtiItemCollection->setStatus($qtiStatus);
                    $this->em->flush();
                }
            }

            return true;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Publish Item Collection Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /*
     * * @Desc :insert upload bank details in qtiitembank table
     * @param type $itemcollectionData
     * @return boolean
     */

    public function insertUploadDetails($itemcollectionData) {
        try {
            //insert upload details in qtiitembankupload table
            $itemcollectionData['tmpFileName'] = explode('.zip', $itemcollectionData['tmpFileName']);
            $fileName = $itemcollectionData['tmpFileName'][0];
            $processingPath = $this->app['config']['itemcollectionupload'];
            $errMsg = '';
            if (file_exists($processingPath . "/" . $fileName . "/" . $this->app['config']['uploadFileName']) && file_exists($processingPath . "/" . $fileName . "/" . $this->app['config']['xsdFileName'])) {


                $getValidateStatus = $this->app['itemcollection.service']->validateXML($fileName);

                if (is_int($getValidateStatus)) {
                    $status = $this->app['cache']->fetch('ACTIVE');
                } else {
                    $status = $this->app['cache']->fetch('DELETED');
                    $msg = 'Import Item Collection with filename->' . $fileName . ' did not validate the files giving error ->> ' . $getValidateStatus;
                    $this->app['log']->writeLog($msg);
                    $errMsg = strip_tags($getValidateStatus);
                    $statusErr = $this->app['cache']->fetch('exists');
                }
            } else {

                $status = $this->app['cache']->fetch('DELETED');
                $msg = 'Import Item Collection with filename->' . $fileName . ' does not contain one of these file, allQuestions.xml or allQuestionsSXD.xml';
                $this->app['log']->writeLog($msg);
                $errMsg = 'allQuestions.xml / allQuestionsXSD.xml is missing';
                $statusErr = $this->app['cache']->fetch('notexists');
            }

            $qtiItemBankUpload = new QtiItemBankUpload();

            $getBankId = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemcollectionData['itemBankId']);
            $qtiItemBankUpload->setItemBank($getBankId);
            $qtiItemBankUpload->setContentType($itemcollectionData['contentType']);

            $qtiItemBankUpload->setFileName($fileName);
            $qtiItemBankUpload->setCreatedBy($itemcollectionData['userId']);
            $qtiItemBankUpload->setStatus($status);
            if (is_int($getValidateStatus)) {
                $qtiItemBankUpload->setNoOfQuestions($getValidateStatus);
                $qtiItemBankUpload->setQuestionsProcessed($this->app['cache']->fetch('DELETED'));
                $qtiItemBankUpload->setQuestionsFailed($this->app['cache']->fetch('DELETED'));
            }
            $qtiItemBankUpload->setCreatedDate($this->dateTime);

            $this->em->persist($qtiItemBankUpload); //Inserting the Above Field Values to QtiItem table
            $this->em->flush();
            $itemBankUploadId = $qtiItemBankUpload->getId();

            $errorArray = array();

            //to insert any errors while upload
            if ($status != 1 && $errMsg != '' && !empty($statusErr)) {
                array_push($errorArray, $errMsg);
                $errMsg = json_encode($errorArray);
                self::storeItemUploadToLog($itemcollectionData['itemBankId'], $itemBankUploadId, $errMsg);
                return $statusErr;
            }

            return true;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Upload Item Collection Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * To get the upload details
     * @return type
     */
    public function getUploadDetails() {
        $qb = $this->em->createQueryBuilder();
        $qb->select('IDENTITY(qibu.itemBank) as itemBankId', 'qibu.id', 'qibu.fileName', 'qibu.createdBy', 'qibu.status')
                ->from('QuizzingPlatform\Entity\QtiItemBankUpload', 'qibu')
                ->where('qibu.status=:status')
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
        $fileDetails = $qb->getQuery()->getArrayResult();
        return $fileDetails;
    }

    /**
     * To store the uploaded status
     * @param type $uploadStatus
     */
    public function storeUploadStatus($itemBankUploadId, $uploadStatus, $userId) {
        foreach ($uploadStatus as $itemBankId => $uploadCount) {
            $QtiItemBankUpload = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBankUpload', $itemBankUploadId);
            $QtiItemBankUpload->setQuestionsProcessed(isset($uploadCount['success']) ? $uploadCount['success'] : 0);
            $QtiItemBankUpload->setQuestionsFailed(isset($uploadCount['failure']) ? $uploadCount['failure'] : 0);
            $QtiItemBankUpload->setStatus($this->app['cache']->fetch('INACTIVE'));
            $QtiItemBankUpload->setModifiedBy($userId);
            $QtiItemBankUpload->setModifiedDate($this->dateTime);
            $this->em->flush();
        }
        return true;
    }

    /*
     * BySrilakshmi R
     * query to get upload/import/parsing statuus of collection
     */

    public function getImportStatus($itemCollectionId, $getImportDetails) {

        try {
            //print_r($getImportDetails);die;
            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $uploadDate = $processStatus = $processedDate = $totalItem = $itemsCreated = $itemsFailed = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "qibu.createdDate";

            // Declare a result array to return. 
            $itemCollectionImportRequest = array();

            // Check if request is not null.
            if (!empty($getImportDetails)) {

                foreach ($getImportDetails as $tagName => $tagValue) {
                    // get values for $firstName, $lastName, $userEmail, $role, $group
                    $$tagName = $tagValue;
                }

                // Logic found in CommonHelper class to get the offset value.
                $offset = CommonHelper::getOffset($page, $perPage);

                if (isset($sort)) {
                    // If sorting field is passed.
                    // Logic found in CommonHelper class to get the sorting type and sorting field.
                    $sort = CommonHelper::getSortingDetails($sort);

                    // Sorting type ASC/DESC
                    $sortType = $sort['type'];

                    // Sorting field name
                    $sortField = $sort['field'];
                }
            }


            //echo $sortField."$$$".$sortType;die;
            $itemCollectionImportRequest['data'] = array();


            ################# Get Import DATA ################
            // Get user data
            $qb = $this->em->createQueryBuilder();
            $dataQuery = $qb->select('qibu.createdDate as createdDate', 'qibu.status as status', 'lqi.log as logError', 'qibu.modifiedDate as modifiedDate', 'qibu.noOfQuestions as noOfQuestions', 'qibu.questionsProcessed as questionsProcessed', 'qibu.questionsFailed as questionsFailed')
                    ->from('QuizzingPlatform\Entity\QtiItemBankUpload', 'qibu')
                    ->leftJoin('QuizzingPlatform\Entity\LogQtiItemProcess', 'lqi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qibu.id=lqi.itemBankUpload')
                    ->where('qibu.itemBank=:itemBank')
                    ->setParameter('itemBank', $itemCollectionId);
            // Add limits and sorting to query.
            $dataQuery->setFirstResult($offset)
                    ->setMaxResults($perPage)
                    ->orderBy($sortField, $sortType);
            $itemImportValues = $qb->getQuery()->getArrayResult();
            //print_r($itemImportValues);die;
            foreach ($itemImportValues as $statusKey => $statusValue) {
                if ($statusValue['createdDate'] != null || $statusValue['createdDate'] != '')
                    $itemImportValues[$statusKey]['createdDate'] = date_format($statusValue['createdDate'], 'Y-m-d H:i:s');
                if ($statusValue['modifiedDate'] != null || $statusValue['modifiedDate'] != '')
                    $itemImportValues[$statusKey]['modifiedDate'] = date_format($statusValue['modifiedDate'], 'Y-m-d H:i:s');

                $itemImportValues[$statusKey]['logError'] = json_decode($itemImportValues[$statusKey]['logError'], true);
            }

            //print_r($itemImportValues);die;
            $itemCollectionImportRequest['data'] = $itemImportValues;
            $itemCollectionImportRequest['total'] = count($itemImportValues);
            //print_r($itemCollectionImportRequest);die;
            return $itemCollectionImportRequest;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Get all Item  import data Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
        }
    }

    public function storeItemUploadToLog($itemBankId, $itemBankUploadId, $errMsg) {
        $logQtiItemProcess = new LogQtiItemProcess();

        $getBankId = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBank', $itemBankId);
        $logQtiItemProcess->setItemBank($getBankId);
        $getBankUploadId = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBankUpload', $itemBankUploadId);
        $logQtiItemProcess->setItemBankUpload($getBankUploadId);

        $logQtiItemProcess->setLog($errMsg);
        $this->em->persist($logQtiItemProcess); //Inserting the Above Field Values to QtiItem table
        $this->em->flush();
    }

    public function exportItemCollection($itemCollectionId, $exportDir) {

        //Form a file path to store exported file.
        $filePath = $exportDir . '/' . $this->app['config']['uploadFileName'];

        //Get itembank to items association details
        $qb = $this->em->createQueryBuilder();

        $query = $qb->select('qi.itemId as itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiItemBank', 'qib')
                ->leftJoin('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm', \Doctrine\ORM\Query\Expr\Join::WITH, 'qibm.itemBank=qib.itemBankId')
                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qibm.itemPk')
                ->where('qib.itemBankId = :itemBankId')
                ->andWhere('qib.isDeleted IN (:itembankDeleted)')
                ->andWhere('qibm.isDeleted IN (:assocDeleted)')
                ->setParameter('itemBankId', $itemCollectionId)
                ->setParameter('itembankDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('assocDeleted', $this->app['cache']->fetch('ACTIVE'));

        $associatedItems = $qb->getQuery()->getArrayResult();

        if (!empty($associatedItems)) {

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<wk_question_root mode="C">' . "\n\t";
            foreach ($associatedItems as $item) {

                $itemId = $item['itemId'];
                $version = $item['version'];

                $itemDetails = $this->app['items.repository']->find($itemId, NULL, NULL, $version);

                // Form xml for each question.
                $xml .= self::formXMLItem($itemDetails, $exportDir);
            }

            $xml .= '</wk_question_root>' . "\n";

            // Create a XML class and add created sml to the constructor.
            $xmlobj = new \SimpleXMLElement($xml);
            $xmlobj->asXML($filePath);

            //echo $xml;
            die;
        } else {
            return 'noitems';
        }
    }

    /**
     * @DEsc Form a xml question data for each question
     * @param type $itemDetails
     * @param type $exportDir
     * @return string
     */
    public function formXMLItem($itemDetails, $exportDir) {

        //print_r($itemDetails); die;
        // Get itemType short form for wk_question tag
        $itemTypeId = $itemDetails['itemType'];

        if ($itemTypeId == $this->app['cache']->fetch('MULTIPLE_CHOICE')) {
            $qtype = 'MC';
            $question_choice_tag = 'question_multiple_choices';
            $tagtype = 'radioButton';
        } elseif ($itemTypeId == $this->app['cache']->fetch('CHOICE_MULTIPLE')) {
            $qtype = 'CM';
            $question_choice_tag = 'question_choices_multiple';
            $tagtype = 'checkBox';
        } elseif ($itemTypeId == $this->app['cache']->fetch('TRUE_FALSE')) {
            $qtype = 'TF';
            $question_choice_tag = 'question_true_false';
            $tagtype = 'radioButton';
        } elseif ($itemTypeId == $this->app['cache']->fetch('IMAGE_INTEGRATION')) {
            $qtype = 'II';
            $question_choice_tag = 'question_video_questions';
            $tagtype = 'radioButton';
        } elseif ($itemTypeId == $this->app['cache']->fetch('VIDEO_QUESTIONS')) {
            $qtype = 'VQ';
            $question_choice_tag = 'question_image_integration';
            $tagtype = 'radioButton';
        } elseif ($itemTypeId == $this->app['cache']->fetch('GRAPHIC_OPTION')) {
            $qtype = 'GO';
            $question_choice_tag = 'question_graphic_option';
            $tagtype = 'radioButton';
        } elseif ($itemTypeId == $this->app['cache']->fetch('MEDICAL_CASE')) {
            $qtype = 'MEDC';
        } elseif ($itemTypeId == $this->app['cache']->fetch('CLINICAL_SYMPTOMS')) {
            $qtype = 'CS';
        }

        $questionType = $itemDetails['itemTypeName'];
        $questionTitle = $itemDetails['label'];
        $questionText = $itemDetails['promptText'];
        $choiceAnswers = $itemDetails['choiceInteraction']['simpleChoices'];
        $questionScore = $itemDetails['score'];
        $questionDifficulty = $itemDetails['difficulty'];
        $modelFeedback = $itemDetails['modelFeedback'];
        $remediationLinks = $itemDetails['remediationLinks'];
        $metadataAssoc = $itemDetails['metadataAssoc'];
        $assets = $itemDetails['assets'];

        $xml = '<wk_question identificationId="0" qtype="' . $qtype . '" qmode="C">' . "\n\t\t";
        $xml .= '<question_type ucx="C" >"' . $questionType . '"</question_type>' . "\n\t\t";
        $xml .= '<question_title ucx="C">"' . $questionTitle . '"</question_title>' . "\n\t\t";
        $xml .= '<question_text ucx="C">"' . $questionText . '"</question_text>' . "\n\t\t";

        // Form answer text tag and correct answer
        if (!empty($choiceAnswers)) {
            $correctAnswer = '';

            $xml .= '<' . $question_choice_tag . ' qmcmode="C" tagtype="' . $tagtype . '" > ' . "\n\t\t\t";

            foreach ($choiceAnswers as $key => $answer) {

                $xml .= '<question_choice ucx="C" refId="">' . "\n\t\t\t\t";
                $xml .= '<question_answer_text>' . $answer['label'] . '</question_answer_text>' . "\n\t\t\t\t";
                $xml .= '<question_rationale>' . $answer['rationale'] . '</question_rationale>' . "\n\t\t\t";
                $xml .= '</question_choice>' . "\n\t\t";

                // Get the correct answer through index
                if ($answer['correct']) {
                    $correctIndex = $key + 1;
                    $correctAnswer = $correctIndex . ',';
                }
            }
            $xml .= '</' . $question_choice_tag . '>' . "\n\t\t";
        }


        // If question type is Graphic Option then answers are assets
        if ($itemTypeId == $this->app['cache']->fetch('GRAPHIC_OPTION')) {
            if (!empty($assets)) {

                // Create additional field tags for assets
                $xml .= self::formAdditionalAssetTag($assets, $exportDir);
            }
        }

        // Form a correct answer tag
        $correctAnswer = rtrim($correctAnswer, ',');

        $xml .= '<correct_answer>' . $correctAnswer . '</correct_answer>' . "\n\t\t";

        if (($itemTypeId == $this->app['cache']->fetch('VIDEO_QUESTIONS')) || ($itemTypeId == $this->app['cache']->fetch('IMAGE_INTEGRATION'))) {

            if (!empty($assets)) {

                // Create additional field tags for assets
                $xml .= self::formAdditionalAssetTag($assets, $exportDir);
            }
        }

        $xml .= '<question_score ucx="C">' . $questionScore . '</question_score>' . "\n\t\t";
        $xml .= '<question_difficulty ucx="C" >' . $questionDifficulty . '</question_difficulty>' . "\n\t\t";

        // Form a correct rationale and incorrect rationale tags
        if (!empty($modelFeedback)) {
            foreach ($modelFeedback as $rationale) {
                if ($rationale['outcomeType'] == 1) {
                    $xml .= '<question_correct_rationale ucx="C">' . $rationale['feedbackText'] . '</question_correct_rationale>' . "\n\t\t";
                } if ($rationale['outcomeType'] == 2) {
                    $xml .= '<question_incorrect_rationale ucx="C">' . $rationale['feedbackText'] . '</question_incorrect_rationale>' . "\n\t\t";
                }
            }
        }

        // Form a remediation link tags
        if (!empty($remediationLinks)) {

            $xml .= '<question_remediation_link qrlmode="C">' . "\n\t\t\t";

            foreach ($remediationLinks as $key => $remediation) {

                $redLinkId = $key + 1;

                if ($remediation['linkTypeId'] == 1) {
                    $xml .= '<remediation_type ucx = "C" redLinkId = "' . $redLinkId . '" remediation_link_type = "Web Link">' . "\n\t\t\t\t";
                    $xml .= '<remediation_type_text>' . $remediation['linkText1'] . '</remediation_type_text>' . "\n\t\t\t\t";
                    $xml .= '<remediation_type_link>' . $remediation['linkText2'] . '</remediation_type_link>' . "\n\t\t\t\t";
                    $xml .= '<remediation_type_tooltip>' . $remediation['linkText3'] . '</remediation_type_tooltip>' . "\n\t\t\t";
                    $xml .= '</remediation_type>' . "\n\t\t";
                } else if ($remediation['linkTypeId'] == 2) {
                    $xml .= '<remediation_type ucx = "C" redLinkId = "' . $redLinkId . '" remediation_link_type = "Ebook"></remediation_type>' . "\n\t\t";
                } else if ($remediation['linkTypeId'] == 3) {
                    $xml .= '<remediation_type ucx = "C" redLinkId = "' . $redLinkId . '" remediation_link_type = "Text">' . "\n\t\t\t\t";
                    $xml .= '<remediation_type_text>' . $remediation['linkText1'] . '</remediation_type_text>' . "\n\t\t\t";
                    $xml .= '</remediation_type>' . "\n\t\t";
                }
            }

            $xml .= '</question_remediation_link>' . "\n\t\t";
        }

        // Form metadata tags 
        if (!empty($metadataAssoc)) {
            $xml .= '<question_meta_tag qmtmode = "C">
                        <meta_tag ucx = "C" metaTagId = "">
                            <meta_tag_type>Select your metadata tag</meta_tag_type>
                            <meta_tag_value>Click here to enter text.</meta_tag_value>
                        </meta_tag>
                        <meta_tag ucx = "C" metaTagId = "">
                            <meta_tag_type>Select your metadata tag</meta_tag_type>
                            <meta_tag_value>Click here to enter text.</meta_tag_value>
                        </meta_tag>
                        <meta_tag ucx = "C" metaTagId = "">
                            <meta_tag_type>Select your metadata tag</meta_tag_type>
                            <meta_tag_value>Click here to enter text.</meta_tag_value>
                        </meta_tag>
                    </question_meta_tag>' . "\n\t";
        }

        $xml .= '</wk_question>' . "\n\t";
        return $xml;
    }

    /**
     * @desc : for assets create additional tag.
     * @param type $assets
     * @param type $exportDir
     * @return string
     */
    public function formAdditionalAssetTag($assets, $exportDir) {

        $xml = '';

        foreach ($assets as $asset) {

            if ($this->app['cache']->fetch('Image') == $asset['assetTypeId']) {
                $mediaType = 'image';
            } else if ($this->app['cache']->fetch('Audio') == $asset['assetTypeId']) {
                $mediaType = 'audio';
            } else if ($this->app['cache']->fetch('Video') == $asset['assetTypeId']) {
                $mediaType = 'video';
            }

            $fileName = $asset['fileName'];
            $assetName = $asset['assetName'];

            // Copy assets from upload directory to media directory
            $mediaUpload = self::copyAssetToMediaDir($assetName, $fileName, $mediaType, $exportDir);

            if ($mediaUpload) {

                $xml .= '<question_additional_fields uck="C" referencevalue="" mediaType="' . $mediaType . '">' . "\n\t\t\t";
                $xml .= '<question_additional_file_path>media\\' . $fileName . '</question_additional_file_path>' . "\n\t\t";
                $xml .= '</question_additional_fields>' . "\n\t\t";
            }
        }

        return $xml;
    }

    /**
     * @Desc  copy assets from thier directory to media directory of the export.
     * @param type $assetName
     * @param type $fileName
     * @param type $mediaType
     * @param type $exportDir
     * @return boolean
     */
    public function copyAssetToMediaDir($assetName, $fileName, $mediaType, $exportDir) {

        // Using  asset type and get the target directory
        $targetUploadDir = $exportDir . '/media';

        if (!file_exists($targetUploadDir)) {
            mkdir($targetUploadDir, 0777, true);
        }

        $sourceDirFile = $this->app['config']['uploads'] . ucfirst($mediaType) . '/' . $assetName;
        $targetDirFile = $targetUploadDir . '/' . $fileName;

        // Move the files from aset directory to export target directory
        // If file is successfully moved then return the mime type

        if (copy($sourceDirFile, $targetDirFile)) {

            // Get the mime type from actual file uploaded path for the file
            $mimeType = mime_content_type($targetDirFile);

            // Return the mimetype
            return $mimeType;
        } else {

            // If failed to move the file then return false
            return false;
        }
    }

}
