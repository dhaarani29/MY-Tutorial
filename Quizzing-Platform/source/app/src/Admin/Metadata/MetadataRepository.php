<?php

/**
 * MetadataRepository - It's the repository class file to handle the metadata tag module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Metadata;

//Entity Namespace
use QuizzingPlatform\Entity\CmnMetadata;
use QuizzingPlatform\Entity\CmnMetadataType;
use QuizzingPlatform\Entity\CmnMetadataDatatype;
use QuizzingPlatform\Entity\CmnMetadataValues;
use QuizzingPlatform\Entity\CmnMetadataHierarchyValues;
use QuizzingPlatform\Entity\CmnInstitutionMetadata;
//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\CmnResourceMetadata;
use QuizzingPlatform\Entity\PrdProduct;

class MetadataRepository implements RepositoryInterface {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->hierarchyids = array();
        $this->hierarchySubChildIds = array();
        $this->hierarchyParentNodes = array();
        $this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));
        $this->hierarchySubTopicIds = array();
    }

    /**
     * @param : No parameters
     * @Desc : Repository method to Get the Metadata Tag Types(Free Text , Lookup , Hierachy)
     * @Return : array, Returns all the metadata types.
     */
    public function getMetadataTypes() {
        try {

            // Fetch metadata types stored in cache
            $metadataTypes = $this->app['cache']->fetch('metadataTypes');
            $metadataTypes = json_decode($metadataTypes);

            if (!empty($metadataTypes)) {

                // return from cache.
                return $metadataTypes;
            } else {

                // If its not set in cache, fetch from DB and set to cache and then return the metadata types.
                $qb = $this->em->createQueryBuilder();
                $qb->select('cmt.metadataTypeId as tagTypeId', 'cmt.metadataTypeName as tagType', 'cmt.description', 'cmt.createdBy')
                        ->from('QuizzingPlatform\Entity\CmnMetadataType', 'cmt')
                        ->orderBy('cmt.displayOrder', 'ASC');
                $query = $qb->getQuery();
                $metadataTypes = $query->getArrayResult();

                if (empty($metadataTypes)) {

                    //Return false if metadata types doesn't exists.
                    return false;
                } else {

                    // Store metadata types to cache and then return.
                    $this->app['cache']->store('metadataTypes', json_encode($metadataTypes));
                    return $metadataTypes;
                }
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata types listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to Get the Metadata data Types (String , Numeric , Datetime).
     * @Return : array, Returns as the metadata data types.
     */
    public function getMetadataDataTypes() {
        try {

            // Fetch metadata datatypes stored in cache
            $metadataDataTypes = $this->app['cache']->fetch('metadataDataTypes');
            $metadataDataTypes = json_decode($metadataDataTypes);

            if (!empty($metadataDataTypes)) {

                //Return from cache.
                return $metadataDataTypes;
            } else {

                // If its not set in cache, fetch from DB and set to cache and then return the metadata datatypes.
                $qb = $this->em->createQueryBuilder();
                $qb->select('cmdt.metadataDatatypeId as dataTypeId', 'cmdt.metadataDatatypeName as dataType', 'cmdt.createdBy')
                        ->from('QuizzingPlatform\Entity\CmnMetadataDatatype', 'cmdt')
                        ->orderBy('cmdt.displayOrder', 'ASC');
                $query = $qb->getQuery();
                $metadataDataTypes = $query->getArrayResult();

                if (empty($metadataDataTypes)) {
                    //Return false if metadata data types doesn't exists.
                    return false;
                } else {
                    // Store metadata datatypes to cache and then return.
                    $this->app['cache']->store('metadataDataTypes', json_encode($metadataDataTypes));
                    return $metadataDataTypes;
                }
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata datatypes listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc : Function to store metadata values.
     * @param type $metaData
     * @param type $cmnMetadata
     */
    public function createMetadataValues($metaData, $cmnMetadata, $userId) {

        //Insertion for multiple Records
        foreach ($metaData as $metadataValue) {
            $cmnMetadataValue = new CmnMetadataValues(); //Creating the object for MetadataValues
            $cmnMetadataValue->setMetadata($cmnMetadata); //Setting the MetadataId(Foreign Key value references to Cmn_metadata table id)
            $cmnMetadataValue->setSequence($metadataValue['sequence']); //Setting the Lookup Sequence
            $cmnMetadataValue->setValue($metadataValue['value']); //setting the Lookup Value
            $cmnMetadataValue->setCreatedBy($userId); //Created by(user id)
            $cmnMetadataValue->setCreatedDate($this->dateTime); //Created Date(current Date)
            $cmnMetadataValue->setModifiedBy($userId); //modified By(user id)
            $cmnMetadataValue->setModifiedDate($this->dateTime); //Modified Date(current Date)
            $this->em->persist($cmnMetadataValue); //Manage the Inserting Value 
        }
        //Execution of insertion Query
        $this->em->flush();

        return $cmnMetadataValue->getId();
    }

    /**
     * @Desc : function to update metadata values. While updating, user may add new value or delete existing value or they can only update the value.
     * @param type $metaData
     * @param type $cmnMetadata
     */
    public function updateMetadataValues($metaData, $cmnMetadata, $userId) {

        //Insertion for multiple Records
        foreach ($metaData as $metadataValue) {

            // If nodeStatus is created, then create new lookup value. 
            //Note :  Not using as above function will take complete metadata values in loop. But this is single Lookup value creation.
            if ($metadataValue['nodeStatus'] == "created") {
                $cmnMetadataValue = new CmnMetadataValues(); //Creating the object for MetadataValues
                $cmnMetadataValue->setMetadata($cmnMetadata); //Setting the MetadataId(Foreign Key value references to Cmn_metadata table id)
                $cmnMetadataValue->setSequence($metadataValue['sequence']); //Setting the Lookup Sequence
                $cmnMetadataValue->setValue($metadataValue['value']); //setting the Lookup Value
                $cmnMetadataValue->setCreatedBy($userId); //Created by(user id)
                $cmnMetadataValue->setCreatedDate($this->dateTime); //Created Date(current Date)
                $cmnMetadataValue->setModifiedBy($userId); //modified By(user id)
                $cmnMetadataValue->setModifiedDate($this->dateTime); //Modified Date(current Date)
                $this->em->persist($cmnMetadataValue); //Manage the Inserting Value 
                $this->em->flush();
            } else if ($metadataValue['nodeStatus'] == "updated") {

                // If nodeStatus is updated, then update the existing lookup value.
                $cmnMetadataValue = $this->em->getReference('QuizzingPlatform\Entity\CmnMetadataValues', $metadataValue['id']);
                $cmnMetadataValue->setSequence($metadataValue['sequence']); //Setting the Lookup Sequence
                $cmnMetadataValue->setValue($metadataValue['value']); //setting the Lookup Value
                $cmnMetadataValue->setModifiedBy($userId); //modified By(user id)
                $cmnMetadataValue->setModifiedDate($this->dateTime); //Modified Date(current Date)
                $this->em->flush();
            } else if ($metadataValue['nodeStatus'] == "deleted") {

                // If nodeStatus is deleted, then soft delete the lookup value.
                $cmnMetadataValue = $this->em->getReference('QuizzingPlatform\Entity\CmnMetadataValues', $metadataValue['id']);
                $cmnMetadataValue->setStatus($this->app['cache']->fetch('DELETED'));
                $this->em->flush();
            }
        }
    }

    /**
     * @Desc Used to store individual metadata hierarchy values.
     * @param type $metadataValue
     * @param type $cmnMetadata
     * @param type $parentId
     * @param type $userId
     * @return type integer
     */
    public function createIndividualHierarchy($metadataValue, $cmnMetadata, $parentId, $userId) {

        //Create Object for Hierachy table
        $CmnMetadataHierarchyValues = new CmnMetadataHierarchyValues();
        $CmnMetadataHierarchyValues->setMetadata($cmnMetadata); //Setting the Metadata Id(Foreign Key value references to Cmn_metadata table id)
        $CmnMetadataHierarchyValues->setParentId($parentId); //Setting the Parent Id
        $CmnMetadataHierarchyValues->setValue($metadataValue['value']); //Setting the Hierchy Value
        $CmnMetadataHierarchyValues->setDescription($metadataValue['description']); //Setting the Hierachy Dscription
        $CmnMetadataHierarchyValues->setCreatedBy($userId); //Created By(userId)
        $CmnMetadataHierarchyValues->setCreatedDate($this->dateTime); //Created Date(current Date)
        $CmnMetadataHierarchyValues->setModifiedBy($userId); //modified By(user id)
        $CmnMetadataHierarchyValues->setModifiedDate($this->dateTime); //Modified Date(current Date)
        $this->em->persist($CmnMetadataHierarchyValues); //Manage the Inserting Value
        //Execution of insertion Query
        $this->em->flush();

        if ($CmnMetadataHierarchyValues->getId()) {
            return $CmnMetadataHierarchyValues->getId();
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $silverChairMetadataId
     * @param type $levelName
     * @param type $parentName
     * @return boolean
     */
    public function checkTaxonomyDuplicate($silverChairMetadataId, $levelName, $parentName) {

        $qb = $this->em->createQueryBuilder();
        if ($parentName == "") {
            $qb->select('cmhv.id')
                    ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                    ->where('cmhv.metadata= :metadataId')
                    ->andWhere('cmhv.parentId= :parentId')
                    ->andWhere('cmhv.status= :status')
                    ->setParameter('metadataId', $silverChairMetadataId)
                    ->setParameter('parentId', 0)
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
        } else {
            $qb->select('cmhv.id')
                    ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                    ->join('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhva', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmhva.id=cmhv.parentId')
                    ->where('cmhv.metadata= :metadataId')
                    ->andWhere('cmhva.value= :parentValue')
                    ->andWhere('cmhv.value= :value')
                    ->andWhere('cmhv.status= :status')
                    ->setParameter('metadataId', $silverChairMetadataId)
                    ->setParameter('parentValue', $parentName)
                    ->setParameter('value', $levelName)
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
        }

        $taxonomy = $qb->getQuery()->getArrayResult();

        // If metadata not exists, then proceed to create new metadata.
        if (empty($taxonomyExists)) {
            return $taxonomy;
        } else {
            return false;
        }
    }

    /**
     * @Desc Update the individual metadata hierarchy values.
     * @param type $level
     * @param type $cmnMetadata
     * @param type $userId
     */
    public function updateIndividualHierarchy($metadataValue, $userId) {

        //Create Object for Hierachy table
        $CmnMetadataHierarchyValues = $this->em->getReference('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', $metadataValue['id']);
        $CmnMetadataHierarchyValues->setValue($metadataValue['value']); //Setting the Hierchy Value
        $CmnMetadataHierarchyValues->setDescription($metadataValue['description']); //Setting the Hierachy Dscription
        $CmnMetadataHierarchyValues->setModifiedBy($userId); //modified By(user id)
        $CmnMetadataHierarchyValues->setModifiedDate($this->dateTime); //Modified Date(current Date)
        $this->em->flush();

        if ($CmnMetadataHierarchyValues->getId()) {
            return $CmnMetadataHierarchyValues->getId();
        } else {
            return false;
        }
    }

    /**
     * @Desc : Delete individual metadata hierarchy node
     * @param type $metadataId
     * @param type $deleteId
     */
    public function deleteIndividualHierarchy($metadataId, $deleteId) {

        // Get all the child nodes of the node id which is passed. 
        $hierarchyIdsToDelete = self::getHierarchyNodes($metadataId, $deleteId);

        foreach ($hierarchyIdsToDelete as $deleteIds) {

            // soft delete the metadatahierarchy with status 0
            $CmnMetadataHierarchyValues = $this->em->getReference('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', $deleteIds);
            $CmnMetadataHierarchyValues->setStatus($this->app['cache']->fetch('DELETED'));
            $this->em->flush();
        }
    }

    /**
     * 
     * @param type $institutions
     * @param type $cmnMetadata
     * @param type $userId
     */
    public function createInstitutionMetadata($institutions, $cmnMetadata, $userId) {

        $institionsList = explode(',', $institutions);

        //Insertion for multiple Records 
        foreach ($institionsList as $list) {

            //Fetching the foreign key value(Institution id) from cmn_institution_metadata table
            $institutionId = $this->em->getRepository('QuizzingPlatform\Entity\CmnInstitutions')->findOneById(array('id' => $list));

            $CmnInstitutionMetadata = new CmnInstitutionMetadata(); //Creating the object for institution metadata.
            $CmnInstitutionMetadata->setInstitution($institutionId); //Setting the institutionId(Foreign Key value references to Cmn_institutions table id)
            $CmnInstitutionMetadata->setMetadata($cmnMetadata); //Setting the metadataId(Foreign Key value references to Cmn_metadata table metadata_id)
            $CmnInstitutionMetadata->setCreatedBy($userId); //Created by(user id)
            $CmnInstitutionMetadata->setCreatedDate($this->dateTime); //Created Date(current Date)
            $CmnInstitutionMetadata->setModifiedBy($userId); //modified By(user id)
            $CmnInstitutionMetadata->setModifiedDate($this->dateTime); //Modified Date(current Date)
            $this->em->persist($CmnInstitutionMetadata); //Manage the Inserting Value 
        }

        //Execution of insertion Query
        $this->em->flush();

        return $CmnInstitutionMetadata->getId();
    }

    /**
     * @param : Metadata request input as defined above.
     * @desc : Controller method to create the metadata tag in Free text, Lookup,Hierarchy method.
     * @return : id, Returns last created metadata tag.
     */
    public function create($metaData) {
        try {

            //Check for duplicate metadata.
            $qb = $this->em->createQueryBuilder();
            $qb->select('cm.metadataId')
                    ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                    ->where('cm.metadataName= :metadataName')
                    ->andWhere('cm.status= :status')
                    ->setParameter('metadataName', $metaData['tagName'])
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            $metadataExists = $qb->getQuery()->getArrayResult();

            // If metadata not exists, then proceed to create new metadata.
            if (empty($metadataExists)) {

                //Fetching the foreign key value(Metadata type id) from cmn_metadata_type table
                $metadataTypeId = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadataType')->findOneByMetadataTypeId(array('metadataTypeId' => $metaData['tagTypeId']));

                //Fetching the foreign key value(Metadata data type id) from cmn_metadata_datatype table
                $metadataDatatypeId = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadataDatatype')->findOneByMetadataDatatypeId(array('metadataDatatypeId' => $metaData['dataTypeId']));

                /**
                 * Insertion for cmn_metadata table
                 */
                //Creating the object for Cmn_metadata
                $cmnMetadata = new CmnMetadata();
                $cmnMetadata->setMetadataDatatype($metadataDatatypeId); //Setting Metadata Datatype(String , Numeric , DateTime)
                $cmnMetadata->setMetadataType($metadataTypeId); //Setting the Metadatatype(Free text , Lookup , hierachy)
                $cmnMetadata->setMetadataName($metaData['tagName']); //Setting the tagName
                $cmnMetadata->setDescription($metaData['description']); //Setting the tagDescription
                $cmnMetadata->setDisplayLabel($metaData['displayLabel']); //Setting the Display label
                $cmnMetadata->setMandatory($metaData['mandatory']); //Setting the Mandatory Field
                $cmnMetadata->setMultiSelect($metaData['multiselect']); //Setting the Multiselect
                $cmnMetadata->setEffectiveDate($this->dateTime); //Effective Date
                $cmnMetadata->setCreatedBy($metaData['userId']); //Created By (user id )
                $cmnMetadata->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                $cmnMetadata->setModifiedBy($metaData['userId']); //Modified By(userid)
                $cmnMetadata->setModifiedDate($this->dateTime); //Modified date(current Date)
                $cmnMetadata->setStatus($this->app['cache']->fetch('ACTIVE')); //set this to active status
                $this->em->persist($cmnMetadata); //Inserting the Above Field Values to Cmn_metadata table
                $this->em->flush();

                $metadataId = $cmnMetadata->getMetadataId();

                if ($metadataId) {

                    $tagType = $metaData['tagTypeId'];
                    $institutions = $metaData['institutions'];
                    $userId = $metaData['userId'];
                    $metadataValues = $metaData['metadataValues'];


                    // Check if institutions are mapped with metadata
                    if ($institutions != "") {

                        // Call function createInstitutionMetadata to store instituional metadata
                        $storeInstitutions = self::createInstitutionMetadata($institutions, $cmnMetadata, $userId);

                        // If failed to store metadata institution details write to log.
                        if (!$storeInstitutions) {
                            $this->app['log']->writeLog("Failed to store Institutions for metadata : " . $metadataId);
                        }
                    }

                    // If metadata values are passed, then insert them to approapriate tables based on the tag type.
                    if (!empty($metadataValues)) {

                        //  If tag type is LOOKUP, then store to metadata values table.
                        if ($tagType == $this->app['cache']->fetch('LOOKUP')) {

                            $metadatValue = self::createMetadataValues($metadataValues, $cmnMetadata, $userId); // call function createMetadataValues to store metadata values
                        }

                        //  If tag type is HIERARCHY, then store to metadata hierarchy table.
                        elseif ($tagType == $this->app['cache']->fetch('HIERARCHY')) {

                            $metadatValue = $this->app['metadata.service']->createMetadataHierarchyValues($metadataValues, $cmnMetadata, $userId, $metadataId); // call function createMetadataHierarchyValues to store metadata hierarchy values
                        }

                        // If any error occurs while inserting lookup/hierarchy values store the errors in to logs.
                        if (!$metadatValue) {
                            $this->app['log']->writeLog("Failed to store metadata values/hierarchy : " . $metadataId);
                        }
                    }

                    $this->app['log']->writeLog("Successfully created Metadata Tag : " . $metadataId);

                    // Return newly created metadata tag id
                    return $metadataId;
                } else {
                    return false;
                }
            } else {

                // If metadata exists, then return exists keyword.
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : type $id.
     * @Desc : Fetching the metadata details based on the Id
     * @Return : array. contains all the metadata details.
     */
    // public function find($id) {
    //     try {
    //         // Fetch details from metadata table along with metadata type and metadata data type.
    //         $qb = $this->em->createQueryBuilder();
    //         $query = $qb->select('cm.metadataId as id', 'cm.metadataName as tagName', 'cm.description', 'cm.displayLabel', 'cm.mandatory', 'cm.multiSelect as multiselect', 'cm.status as status', 'cm.createdBy', 'cmt.metadataTypeId as tagTypeId ', 'cmt.metadataTypeName as tagType', 'cmdt.metadataDatatypeId as dataTypeId', 'cmdt.metadataDatatypeName as dataType', 'crm.sequence as resourceAssociated')
    //                 ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
    //                 ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
    //                 ->join('QuizzingPlatform\Entity\CmnMetadataDataType', 'cmdt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmdt.metadataDatatypeId=cm.metadataDatatype')
    //                 ->leftJoin('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.metadata=cm.metadataId')
    //                 ->where('cm.metadataId = :metadataId')
    //                 ->andWhere('cm.status = :status')
    //                 ->setParameter('metadataId', $id)
    //                 ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
    //         $metaDataValues = $qb->getQuery()->getArrayResult();
    //         // If metadata tag exists then continue
    //         if (!empty($metaDataValues)) {
    //             $metadataTypeId = $metaDataValues[0]['tagTypeId'];
    //             $metaDataValues[0]['resourceAssociated'] = (empty($metaDataValues[0]['resourceAssociated'])) ? false : true;
    //             // Declare result array to return
    //             $metadataReturnValues = array();
    //             // Assign common metada value to return array.
    //             $metadataReturnValues = $metaDataValues;
    //             //Check if instituions are mapped
    //             $qb = $this->em->createQueryBuilder();
    //             $instQuery = $qb->select('ci.id', 'ci.institutionName')
    //                     ->from('QuizzingPlatform\Entity\CmnInstitutionMetadata', 'cim')
    //                     ->join('QuizzingPlatform\Entity\CmnInstitutions', 'ci', \Doctrine\ORM\Query\Expr\Join::WITH, 'ci.id=cim.institution')
    //                     ->where('cim.metadata = :metadataId')
    //                     ->setParameter('metadataId', $id);
    //             $institutionDetails = $qb->getQuery()->getArrayResult();
    //             if (!empty($institutionDetails)) {
    //                 // assign institution details to return array.
    //                 $metadataReturnValues[0]['institutions'] = $institutionDetails;
    //             }
    //             //Lookup type : If metadata type is Lookup then fetch lookup details.
    //             if ($metadataTypeId == $this->app['cache']->fetch('LOOKUP')) {
    //                 // Get lookup values
    //                 $metadataLookupValues = self::getLookupValues($id);
    //                 if (!empty($metadataLookupValues)) {
    //                     // assign Lookvalues to return array.
    //                     $metadataReturnValues[0]['metadataValues'] = $metadataLookupValues;
    //                 }
    //             }
    //             //Hierachical type : If metadata type is Hierarchy then fetch hierarchy details.
    //             elseif ($metadataTypeId == $this->app['cache']->fetch('HIERARCHY')) {
    //                 // Get Hierarchy values
    //                 $metadataHierachyValue = self::getHierarchyValues($id,$parentId);
    //                 if (!empty($metadataHierachyValue)) {
    //                     // Assign Hierarchy values to return array.-
    //                     $metadataReturnValues[0]['metadataValues'] = $metadataHierachyValue;
    //                 }
    //             }
    //             // Return metadata values 
    //             return $metadataReturnValues[0];
    //         } else {
    //             //Tag Not Found
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         //Add exceptions to logger.
    //         $msg = 'Metadata GetbyId Exception  => ' . $e->getMessage();
    //         $this->app['log']->writeLog($msg);
    //         return false;
    //     }
    // }

    public function find($id, $parentId = NULL) {

        try {

            // Fetch details from metadata table along with metadata type and metadata data type.
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('cm.metadataId as id', 'cm.metadataName as tagName', 'cm.description', 'cm.displayLabel', 'cm.mandatory', 'cm.multiSelect as multiselect', 'cm.status as status', 'cm.createdBy', 'cmt.metadataTypeId as tagTypeId ', 'cmt.metadataTypeName as tagType', 'cmdt.metadataDatatypeId as dataTypeId', 'cmdt.metadataDatatypeName as dataType', 'crm.sequence as resourceAssociated')
                    ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                    ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
                    ->join('QuizzingPlatform\Entity\CmnMetadataDataType', 'cmdt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmdt.metadataDatatypeId=cm.metadataDatatype')
                    ->leftJoin('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.metadata=cm.metadataId')
                    ->where('cm.metadataId = :metadataId')
                    ->andWhere('cm.status = :status')
                    ->setParameter('metadataId', $id)
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            $metaDataValues = $qb->getQuery()->getArrayResult();

            // If metadata tag exists then continue
            if (!empty($metaDataValues)) {

                $metadataTypeId = $metaDataValues[0]['tagTypeId'];
                $metaDataValues[0]['resourceAssociated'] = (empty($metaDataValues[0]['resourceAssociated'])) ? false : true;

                // Declare result array to return
                $metadataReturnValues = array();

                // Assign common metada value to return array.
                $metadataReturnValues = $metaDataValues;

                //Check if instituions are mapped
                $qb = $this->em->createQueryBuilder();
                $instQuery = $qb->select('ci.id', 'ci.institutionName')
                        ->from('QuizzingPlatform\Entity\CmnInstitutionMetadata', 'cim')
                        ->join('QuizzingPlatform\Entity\CmnInstitutions', 'ci', \Doctrine\ORM\Query\Expr\Join::WITH, 'ci.id=cim.institution')
                        ->where('cim.metadata = :metadataId')
                        ->setParameter('metadataId', $id);
                $institutionDetails = $qb->getQuery()->getArrayResult();

                if (!empty($institutionDetails)) {

                    // assign institution details to return array.
                    $metadataReturnValues[0]['institutions'] = $institutionDetails;
                }


                //Lookup type : If metadata type is Lookup then fetch lookup details.
                if ($metadataTypeId == $this->app['cache']->fetch('LOOKUP')) {

                    // Get lookup values
                    $metadataLookupValues = self::getLookupValues($id);

                    if (!empty($metadataLookupValues)) {
                        // assign Lookvalues to return array.
                        $metadataReturnValues[0]['metadataValues'] = $metadataLookupValues;
                    }
                }
                //Hierachical type : If metadata type is Hierarchy then fetch hierarchy details.
                elseif ($metadataTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                    // Get Hierarchy values
                    $metadataHierachyValue = self::getHierarchyValues($id, $parentId);

                    if (!empty($metadataHierachyValue)) {

                        // Assign Hierarchy values to return array.-
                        $metadataReturnValues[0]['metadataValues'] = $metadataHierachyValue;
                    }
                }

                // Return metadata values 
                return $metadataReturnValues[0];
            } else {

                //Tag Not Found
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata GetbyId Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * 
     * @param type $id
     * @return type array
     */
    public function getLookupValues($id) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('cmv.id', 'cmv.sequence', 'cmv.value')
                ->from('QuizzingPlatform\Entity\CmnMetadataValues', 'cmv')
                ->orderBy('cmv.sequence', 'ASC')
                ->where('cmv.metadata= :metadataId')
                ->andWhere('cmv.status= :status')
                ->setParameter('metadataId', $id)
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

        $metadataLookupValues = $qb->getQuery()->getArrayResult();

        foreach ($metadataLookupValues as $key => $value) {
            $metadataLookupValues[$key]['nodeStatus'] = "selected";
        }

        return $metadataLookupValues;
    }

    /**
     * 
     * @param type $id
     * @return type array
     */
    public function getHierarchyValues($id, $parentId = NULL) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('cmhv.id', 'cmhv.parentId', 'cmhv.description', 'cmhv.value')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->orderBy('cmhv.id', 'ASC')
                ->where('cmhv.metadata= :metadataId')
                ->andWhere('cmhv.status= :status')
                ->setParameter('metadataId', $id)
                ->andWhere('cmhv.parentId= :parentId')
                ->setParameter('parentId', $parentId)
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

        $metadataHierachyValue = $qb->getQuery()->getArrayResult();

        //code to check immediate level children are having further childs or not
        foreach ($metadataHierachyValue as $key => $value) {

            $qb = $this->em->createQueryBuilder();
            $qb->select('count(cmhv.id)')
                    ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                    ->where('cmhv.metadata= :metadataId')
                    ->andWhere('cmhv.status= :status')
                    ->setParameter('metadataId', $id)
                    ->andWhere('cmhv.parentId= :parentId')
                    ->setParameter('parentId', $value['id'])
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            $metadataHierachyChildValue = $qb->getQuery()->getArrayResult();
            if (($metadataHierachyChildValue[0][1] == '0')) {
                $metadataHierachyChildValue[0][1] = [];
            }
            $metadataHierachyValue[$key]['children'] = $metadataHierachyChildValue[0][1];
            $metadataHierachyValue[$key]['nodeStatus'] = 'selected';
        }
        //print_R($metadataHierachyValue); 
        //die;
        // Get tree structure values from createHierarchy() and pass $parentid = 0
        //$treeMetadataHierachyValue = $this->app['metadata.service']->createHierarchy($metadataHierachyValue, $parentId);

        return $metadataHierachyValue;
    }

    /**
     * @Desc Get total metadata tags count
     * @param type $tagName
     * @param type $description
     * @param type $tagTypeId
     */
    public function getMetadataCount($tagName = NULL, $description = NULL, $tagTypeId = NULL) {


        // Get Total of all the metadata tags based on the applied filters.
        $qb = $this->em->createQueryBuilder();
        $totalQuery = $qb->select('COUNT(cm.metadataId) as total')
                ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
                ->join('QuizzingPlatform\Entity\CmnMetadataDataType', 'cmdt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmdt.metadataDatatypeId=cm.metadataDatatype')
                ->where('cm.status = :status') // Retrieve only active metadata's
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

        // If tagName filter is passed, then add in to where condition.
        if ($tagName != "") {
            $totalQuery->andWhere($qb->expr()->like('cm.metadataName', ':metadataName'))
                    ->setParameter('metadataName', '%' . $tagName . '%');
        }

        // If description filter is passed, then add in to where condition.
        if ($description != "") {
            $totalQuery->andWhere($qb->expr()->like('cm.description', ':description'))
                    ->setParameter('description', '%' . $description . '%');
        }

        // If tagType filter is passed, then add in to where condition.
        if ($tagTypeId != "") {
            $totalQuery->andWhere('cmt.metadataTypeId = :tagType')
                    ->setParameter('tagType', $tagTypeId);
        }

        // Get the results
        $totalMetaData = $qb->getQuery()->getSingleScalarResult();

        return $totalMetaData;
    }

    /**
     * @Desc : Fetching all metadata details with pagination.
     * @param type $metadataRequest
     * @return list of metadata
     */
    public function getMetadata($metadataRequest) {
        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $tagName = $description = $tagTypeId = "";

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
            $metadataReturnValues = array();

            // Check if request is not null.
            if (!empty($metadataRequest)) {

                foreach ($metadataRequest as $key => $mrequest) {

                    // get values for $tagName,$description,$tagTypeId,$perPage  
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

            // Get Total of all the metadata tags based on the applied filters. 
            $totalMetaData = self::getMetadataCount($tagName, $description, $tagTypeId);

            $metadataReturnValues['total'] = $totalMetaData; // Total metadata count for pagination.
            $metadataReturnValues['data'] = array();

            // Check if count is greater than 0
            if ($totalMetaData > 0) {

                // Fetch All the metadata tags based on the applied filters. Retrieve only active metadata's
                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('cm.metadataId as id', 'cm.metadataName as tagName', 'cm.description as description', 'cm.displayLabel', 'cm.mandatory as mandatory', 'cm.multiSelect as multiselect', 'cm.status as status', 'cm.createdBy', 'cmt.metadataTypeId as tagTypeId', 'cmt.metadataTypeName as tagType', 'cmdt.metadataDatatypeId as dataTypeId', 'cmdt.metadataDatatypeName as dataType')
                        ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                        ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
                        ->join('QuizzingPlatform\Entity\CmnMetadataDataType', 'cmdt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmdt.metadataDatatypeId=cm.metadataDatatype')
                        ->where('cm.status = :status')
                        ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

                // If tagName filter is passed, then add in to where condition.
                if ($tagName != "") {
                    $query->andWhere($qb->expr()->like('cm.metadataName', ':metadataName'))
                            ->setParameter('metadataName', '%' . $tagName . '%');
                }

                // If description filter is passed, then add in to where condition.
                if ($description != "") {
                    $query->andWhere($qb->expr()->like('cm.description', ':description'))
                            ->setParameter('description', '%' . $description . '%');
                }

                // If tagType filter is passed, then add in to where condition.
                if ($tagTypeId != "") {
                    $query->andWhere('cmt.metadataTypeId = :tagType')
                            ->setParameter('tagType', $tagTypeId);
                }

                // Add limits and sorting to query.
                $query->setFirstResult($offset)
                        ->setMaxResults($perPage)
                        ->orderBy($sortField, $sortType);


                // Get the results
                $metaDataValues = $qb->getQuery()->getArrayResult(); //->getSQL(); //
                $metadataReturnValues['data'] = $metaDataValues;
            }

            //Return the result array.
            return $metadataReturnValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @param : type Request, has all the metadata details..
     * @Desc : Update the metadata tag details based on the Id
     * @Return :  last updated id.
     */
    public function update($metadataValues, $updateMetadata) {
        try {

            $metadataId = $metadataValues['id'];

            //Check for duplicate metadata.
            $qb = $this->em->createQueryBuilder();
            $qb->select('cm.metadataId')
                    ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                    ->where('cm.metadataName= :metadataName')
                    ->setParameter('metadataName', $updateMetadata['tagName'])
                    ->andWhere('cm.metadataId != :metadataId')
                    ->setParameter('metadataId', $metadataId)
                    ->andWhere('cm.status= :status')
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            $metadataExists = $qb->getQuery()->getArrayResult();

            if (empty($metadataExists)) {

                // Update cmn_metadata table
                $cmnMetadata = $this->em->getReference('QuizzingPlatform\Entity\CmnMetadata', $metadataId);
                $cmnMetadata->setMetadataName($updateMetadata['tagName']); //Setting the tagName
                $cmnMetadata->setDescription($updateMetadata['description']); //Setting the tagDescription
                $cmnMetadata->setDisplayLabel($updateMetadata['displayLabel']); //Setting the Display label
                $cmnMetadata->setMandatory($updateMetadata['mandatory']); //Setting the Mandatory Field
                $cmnMetadata->setMultiSelect($updateMetadata['multiselect']); //Setting the Multiselect
                $cmnMetadata->setModifiedBy($updateMetadata['userId']); //Modified By(userid)
                $cmnMetadata->setModifiedDate($this->dateTime); //Modified date(current Date)
                $this->em->flush();

                $metadataId = $cmnMetadata->getMetadataId();
                if ($metadataId) {

                    $tagType = $updateMetadata['tagTypeId'];
                    $institutions = $updateMetadata['institutions'];
                    $userId = $updateMetadata['userId'];
                    $metadataValues = $updateMetadata['metadataValues'];

                    // check if institutions are mapped with metadata
                    if ($institutions != "") {

                        // Delete all institution and metadata tag association and recreate.
                        $qb = $this->em->createQueryBuilder();
                        $query = $qb->delete('QuizzingPlatform\Entity\CmnInstitutionMetadata', 'cim')
                                        ->where('cim.metadata= :metadataId')
                                        ->setParameter('metadataId', $metadataId)
                                        ->getQuery()->execute();

                        // Re-create metadata institution association. call function createInstitutionMetadata to store instituional metadata
                        $storeInstitutions = self::createInstitutionMetadata($institutions, $cmnMetadata, $userId);
                        if (!$storeInstitutions) {
                            $this->app['log']->writeLog("Failed to store Institutions for metadata : " . $metadataId);
                        }
                    }

                    if (!empty($metadataValues)) {

                        // Update LOOKUP values
                        if ($tagType == $this->app['cache']->fetch('LOOKUP')) {

                            // Update the Lookup Values. Call function updateMetadataValues to update the metadata values
                            $metadatValue = self::updateMetadataValues($metadataValues, $cmnMetadata, $userId);
                        }

                        // Update HIERARCHY values.
                        elseif ($tagType == $this->app['cache']->fetch('HIERARCHY')) {

                            // Update Hierarchy 
                            $metadatValue = $this->app['metadata.service']->createMetadataHierarchyValues($metadataValues, $cmnMetadata, $userId, $metadataId); // call function createMetadataHierarchyValues to update metadata hierarchy values
                        }

                        // If any error occurs while updating then store them to logs.
                        if (!$metadatValue) {
                            $this->app['log']->writeLog("Failed to store metadata values/hierarchy : " . $metadataId);
                        }
                    }

                    $this->app['log']->writeLog("Successfully updated the metadata tag details : " . $metadataId);
                    return true;
                } else {

                    $this->app['log']->writeLog("Failed to update the metadata tag details : " . $metadataId);
                    return false;
                }
            } else {

                // If different metadata exists with same tag name then return exists .
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata Updation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : type $id.
     * @Desc : Soft delete the metadata tag based on the Id
     * @Return :  boolean|int
     */
    public function delete($id) {

        try {
            //Check whether the Metadata Associated with any other resources or not
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('cm.metadataId', 'crm.resourceId')
                    ->from('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm')
                    ->join('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.metadata=cm.metadataId')
                    ->join('QuizzingPlatform\Entity\CmnResourceType', 'crt', \Doctrine\ORM\Query\Expr\Join::WITH, 'crt.resourceTypeId=crm.resourceType')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.itemId=crm.resourceId')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItemBank', 'qib', \Doctrine\ORM\Query\Expr\Join::WITH, 'qib.itemBankId=crm.resourceId')
                    ->leftJoin('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.testId=crm.resourceId')
                    ->where('cm.metadataId= :metadataId')
                    ->andWhere(
                            $qb->expr()->orX(
                                    ($qb->expr()->andX('qi.isDeleted IN (:itemDeleted ) ', 'crt.resourceTypeId =:itemresourceType')), ($qb->expr()->andX('qib.isDeleted IN (:itemBankDeleted ) ', 'crt.resourceTypeId =:itembankresourceType')), ($qb->expr()->andX('qt.isDeleted IN (:testDeleted ) ', 'crt.resourceTypeId =:testresourceType'))))
                    ->setParameter('metadataId', $id)
                    ->setParameter('itemDeleted', $this->statusArr)
                    ->setParameter('itemresourceType', $this->app['cache']->fetch('item'))
                    ->setParameter('itemBankDeleted', $this->statusArr)
                    ->setParameter('itembankresourceType', $this->app['cache']->fetch('itembanks'))
                    ->setParameter('testDeleted', $this->statusArr)
                    ->setParameter('testresourceType', $this->app['cache']->fetch('tests'));

            $resourceMetadataId = $qb->getQuery()->getArrayResult();

            //If Its is not associated with anyother resources, soft delete the metadata.
            if (empty($resourceMetadataId)) {

                // soft delete the metadata with status 0
                $cmnMetadata = $this->em->getReference('QuizzingPlatform\Entity\CmnMetadata', $id);
                $cmnMetadata->setStatus($this->app['cache']->fetch('DELETED'));
                $this->em->flush();

                $this->app['log']->writeLog("Successfully soft deleted the metadata tag : " . $id);
                return true;
            } else {

                $this->app['log']->writeLog("Failed to soft delete the metadata tag : " . $id);
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Metadata Deletion Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @Desc : This function used to get details of multiple metadatas if ids are sent or to fetch mandatory metadata tags
     * @param type $ids
     * @return boolean
     */
    public function getMetadataDetails($ids = NULL) {
        try {

            // Fetch mandatory metadata table along with metadata values.
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('cm.metadataId as id', 'cm.metadataName as tagName', 'cm.description', 'cm.displayLabel', 'cm.mandatory', 'cm.multiSelect as multiselect', 'cm.status as status', 'cm.createdBy', 'cmt.metadataTypeId as tagTypeId ', 'cmt.metadataTypeName as tagType', 'cmdt.metadataDatatypeId as dataTypeId', 'cmdt.metadataDatatypeName as dataType')
                    ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                    ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
                    ->join('QuizzingPlatform\Entity\CmnMetadataDataType', 'cmdt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmdt.metadataDatatypeId=cm.metadataDatatype')
                    ->where('cm.status = :status')
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            if ($ids) {
                $query->andWhere('cm.metadataId IN (:metadataId)')
                        ->setParameter('metadataId', $ids);
            } else {
                $query->andWhere('cm.mandatory = :mandatory')
                        ->setParameter('mandatory', 1);
            }

            $metaDataValues = $qb->getQuery()->getArrayResult();

            // If metadata tag exists then continue
            // Declare result array to return
            $metadataReturnValues = $metaDataValues;
            if (!empty($metaDataValues)) {
                foreach ($metaDataValues as $key => $metadata) {

                    $metadataTypeId = $metadata['tagTypeId'];
                    $id = $metadata['id'];

                    // Assign common metada value to return array.
                    $metadataReturnValues[$key] = $metadata;
                    $metadataReturnValues[$key]["prepopulate"] = true;

                    //Lookup type : If metadata type is Lookup then fetch lookup details.
                    if ($metadataTypeId == $this->app['cache']->fetch('LOOKUP')) {

                        // Get lookup values
                        $metadataLookupValues = self::getLookupValues($id);

                        if (!empty($metadataLookupValues)) {

                            // assign Lookvalues to return array.
                            $metadataReturnValues[$key]['metadataValues'] = $metadataLookupValues;
                        }
                    }
                    //Hierachical type : If metadata type is Hierarchy then fetch hierarchy details.
                    elseif ($metadataTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                        // Get Hierarchy values
                        $metadataHierachyValue = self::getHierarchyValues($id, 0);

                        if (!empty($metadataHierachyValue)) {

                            // Assign Hierarchy values to return array.-
                            $metadataReturnValues[$key]['metadataValues'] = $metadataHierachyValue;
                        }
                    }
                }
            }
            // Return metadata values 
            return $metadataReturnValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Mandatory metadata fetching error Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : No parameters
     * @Desc : Get all the institutions list
     * @return array with list of institutions.
     */
    public function getInstitutions() {

        try {

            //Get all the institutions stored in WK database.
            $qb = $this->em->createQueryBuilder();
            $qb->select('ci.id', 'ci.institutionName')
                    ->from('QuizzingPlatform\Entity\CmnInstitutions', 'ci')
                    ->orderBy('ci.id', 'ASC');
            $query = $qb->getQuery();
            $institionsList = $query->getArrayResult();

            if (!empty($institionsList)) {
                return $institionsList;
            } else {
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Institions list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    public function getMetadataType($id) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('cm.metadataId as id', 'cmt.metadataTypeId as tagTypeId')
                ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
                ->where('cm.status = :status')
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                ->andWhere('cm.metadataId = :metadataId')
                ->setParameter('metadataId', $id);

        $metaDataType = $qb->getQuery()->getArrayResult();

        return $metaDataType[0]['tagTypeId'];
    }

    /**
     * @Desc : Associate metadata with resources like item/itembank/tests
     * @param type $resourceType item/itembank/test
     * @param type $metadataAssociation 
     * @param type $resourceId
     * @param type $userId
     * @return boolean
     */
    public function storeMetadataResourceAssociation($resourceType, $metadataAssociation, $resourceId, $userId) {

        try {
            //Get reference for resource type
            $resourceType = $this->em->getRepository('QuizzingPlatform\Entity\CmnResourceType')->findOneByResourceTypeId(array('resourceTypeId' => $resourceType));

            foreach ($metadataAssociation as $key => $association) {
                $metadataValue = array();
                //Get reference for metadata
                $metadata = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadata')->findOneByMetadataId(array('metadataId' => $key));
                $tagTypeId = self::getMetadataType($key);

                if ($tagTypeId == $this->app['cache']->fetch('LOOKUP')) {

                    $metadataValue = $association;
                } else if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY') && is_array($association)) {
                    //when hierarchy values comes as array, extract only id and save in DB
                    {
                        //print_r($association);
                        if (empty($association['id']) && empty($association[0]['id'])) {
                            //$value = implode(",", $association);
                            $metadataValue = $association;
                        } else {
                            if (empty($association['id'])) {

                                foreach ($association as $collectNodes) {
                                    array_push($metadataValue, $collectNodes['id']);
                                }
                                //$value = implode(",", $value);
                            } else {
                                $metadataValue = $association['id'];
                            }
                        }
                        //print_r($metadataValue);die;
                    }
                } else {
                    $metadataValue[] = $association;
                }

                //echo "%%%%".$value;
                foreach ($metadataValue as $value) {
                    $cmnResourceMetadata = new CmnResourceMetadata();
                    $cmnResourceMetadata->setResourceType($resourceType); //Set resource type id
                    $cmnResourceMetadata->setResourceId($resourceId); //Set resource id
                    $cmnResourceMetadata->setMetadata($metadata); //Set metadata 
                    $cmnResourceMetadata->setValue($value); // Set answer value
                    $cmnResourceMetadata->setCreatedBy($userId); //Created By (user id )
                    $cmnResourceMetadata->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $cmnResourceMetadata->setModifiedBy($userId); //Modified By(userid)
                    $cmnResourceMetadata->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($cmnResourceMetadata); //Inserting the Above Field Values to QtiItemRemediationLinks table 
                }
            }
            $this->em->flush();

            return true;
        } catch (Exception $e) {

            //Add exceptions to logger''.
            $msg = 'Metadata Resource association Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * 
     * @param type $metadataId
     * @return type
     */
    public function getHierarchyDetails($metadataId) {

        // Get all the hierarchy nodes of the metadata
        $qb = $this->em->createQueryBuilder();
        $qb->select('cmhv.id', 'cmhv.parentId', 'cmhv.description', 'cmhv.value')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->orderBy('cmhv.id', 'ASC')
                ->where('cmhv.metadata= :metadataId')
                ->setParameter('metadataId', $metadataId);

        $metadataHierachyValue = $qb->getQuery()->getArrayResult();

        return $metadataHierachyValue;
    }

    /**
     * @Desc get all child details for the given node.
     * @param type $key
     * @param type $association
     * @return type
     */
    public function getHierarchyNodes($metadataId, $parentId) {

        $metadataHierachyValue = self::getHierarchyDetails($metadataId);

        $hierarchyChilds = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $parentId);

        // Add actual parent assocaited to hierarchy ids
        array_push($hierarchyChilds, $parentId);

        return $hierarchyChilds;
    }

    /**
     * @Desc : Used to get hierarchy data ids recursively
     * @param type $item
     * @param type $key
     */
    public function getHierarchyIds($item, $key) {
        if ($key == "id") {
            $this->hierarchyids[] = $item;
        }
    }

    /**
     * @Desc : Get resource metadata association details
     * @param type $resourceType
     * @param type $resourceId
     */
    public function getMetadataResourceAssociation($resourceType, $resourceId) {

        try {
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('cm.metadataId', 'crm.value', 'cmt.metadataTypeId as tagTypeId')
                    ->from('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm')
                    ->join('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=crm.metadata')
                    ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmt.metadataTypeId=cm.metadataType')
                    ->join('QuizzingPlatform\Entity\CmnResourceType', 'crt', \Doctrine\ORM\Query\Expr\Join::WITH, 'crt.resourceTypeId=crm.resourceType')
                    ->where('crm.resourceId = :resourceId')
                    ->andWhere('crt.resourceType = :resourceType')
                    ->setParameter('resourceId', $resourceId)
                    ->setParameter('resourceType', $resourceType);
            $metaDataValues = $qb->getQuery()->getArrayResult();

            $metadataAssociation = array();
            $metadataIds = array();
            foreach ($metaDataValues as $key => $association) {
                $metadataId = $association['metadataId'];

                if ($association['tagTypeId'] == $this->app['cache']->fetch('LOOKUP')) {
                    $metadataValue = $association['value'];
                    $metadataAssociation[$metadataId][] = $metadataValue;
                } else if ($association['tagTypeId'] == $this->app['cache']->fetch('HIERARCHY')) {
                    $metadataValue = self::getHierarcyNodeDetails($association['value'], $metadataId);
                    $metadataAssociation[$metadataId][] = $metadataValue;
                } else {
                    $metadataValue = $association['value'];
                    $metadataAssociation[$metadataId] = $metadataValue;
                }

                // Get all metadata tags associated
                $metadataIds[] = $metadataId;

                // Assign all association details to return array.
                //  $metadataAssociation[$metadataId][] = $metadataValue;
            }

            // Assign metadataids array
            $metadataAssociation['metadataIds'] = array_unique($metadataIds);

            // print_r($metadataValue);die;
            return $metadataAssociation;
        } catch (Exception $e) {

            //Add exceptions to logger''.
            $msg = 'Metadata Resource association Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the MetadataValue path ( root / subject / topic / subtopic )
     * @param type $metadataValueId
     * @param type $clientMetadataId
     * @return type
     */
    public function getMetadataValuePath($metadataValueId, $metadataId) {

        //Pass the taxonomy id
        $qb = $this->em->createQueryBuilder();
        $qb->select('cmhv.id', 'cmhv.parentId', 'cmhv.value')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->leftJoin('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=cmhv.metadata')
                ->where('cmhv.metadata=:metadata')
                ->andWhere('cmhv.id=:id')
                ->setParameter('metadata', $metadataId)
                ->setParameter('id', $metadataValueId);
        $parentId = $qb->getQuery()->getArrayResult();

        $this->hierarchyParentNodes[] = $parentId[0]['value'];

        //recursively pass the id to function to get next parent values
        if ($parentId[0]['parentId'] != '0') {
            self::getMetadataValuePath($parentId[0]['parentId'], $metadataId);
        }

        //Get the taxonomy path
        $taxonomyPath = implode($this->app['config']['tagDelimiter'], array_reverse($this->hierarchyParentNodes));
        return $taxonomyPath;
    }

    /**
     * 
     * @param type $nodeId
     * @return type
     */
    public function getHierarcyNodeDetails($nodeId, $metadataId) {

//        $nodeArray = explode(',', $nodeId);
//        $metadataHierachyNodeArray = array();
//        if (count($nodeArray) >= 1) {
//            foreach ($nodeArray as $node) {
//                $qb = $this->em->createQueryBuilder();
//                $qb->select('cmhv.id', 'cmhv.value', 'cmhv.description')
//                        ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
//                        ->where('cmhv.id= :nodeId')
//                        ->setParameter('nodeId', $node);
//
//                $metadataHierachyNodeDetails = $qb->getQuery()->getArrayResult();
//
//                if (!empty($metadataHierachyNodeDetails)) {
//                    $metadataHierachyNodeDetails[0]['value'] = self::getMetadataValuePath($node, $metadataId);
//                    $this->hierarchyParentNodes = array();
//                    array_push($metadataHierachyNodeArray, $metadataHierachyNodeDetails[0]);
//                }
//            }
//            return $metadataHierachyNodeArray;
//        } else {
//
//            $qb = $this->em->createQueryBuilder();
//            $qb->select('cmhv.id', 'cmhv.value', 'cmhv.description')
//                    ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
//                    ->where('cmhv.id= :nodeId')
//                    ->setParameter('nodeId', $nodeId);
//
//            $metadataHierachyNodeDetails = $qb->getQuery()->getArrayResult();
//
//            if (!empty($metadataHierachyNodeDetails)) {
//                $metadataHierachyNodeDetails[0]['value'] = self::getMetadataValuePath($nodeId, $metadataId);
//                $this->hierarchyParentNodes = array();
//                return $metadataHierachyNodeDetails[0];
//            }
//        }
        $qb = $this->em->createQueryBuilder();
        $qb->select('cmhv.id', 'cmhv.value', 'cmhv.description')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->where('cmhv.id= :nodeId')
                ->setParameter('nodeId', $nodeId);

        $metadataHierachyNodeDetails = $qb->getQuery()->getArrayResult();

        if (!empty($metadataHierachyNodeDetails)) {
            $metadataHierachyNodeDetails[0]['value'] = self::getMetadataValuePath($nodeId, $metadataId);
            $this->hierarchyParentNodes = array();
            return $metadataHierachyNodeDetails[0];
        }
    }

    /**
     * delete the metadata resource association
     * @param type $resourceType
     * @param type $resourceId
     * @param type $userId
     */
    public function deleteMetadataResourceAssociation($resourceType, $resourceId) {

        //Get reference for resource type
        $resourceType = $this->em->getRepository('QuizzingPlatform\Entity\CmnResourceType')->findOneByResourceTypeId(array('resourceTypeId' => $resourceType));
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm')
                        ->where('crm.resourceType= :resourceType')
                        ->andWhere('crm.resourceId= :resourceId')
                        ->setParameter('resourceType', $resourceType)
                        ->setParameter('resourceId', $resourceId)
                        ->getQuery()->execute();
    }

    /**
     * 
     * @param type $metadataId
     * @return type
     */
    public function getTaxanomyProductAssocation($metadataId) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pta.productId', 'pta.parentProductId', 'pta.metadataValueId', 'pta.metadataId')
                ->from('QuizzingPlatform\Entity\ProductTaxonomyAssoc', 'pta')
                ->where('pta.metadataId= :metadataId')
                ->setParameter('metadataId', $metadataId);
        //echo $qb->getQuery()->getSQL();
        $associatedTaxonomy = $qb->getQuery()->getArrayResult();
        return $associatedTaxonomy;
    }

    /**
     * Get the next level/child level of metadata based on current metadata param
     * @param int $id
     * @param int $clientId
     * @param array $params
     * @return type array
     */
    public function getChildValues($id, $userId, $params) {
        $parentId = $params['metadataValueId'];
        $searchFilter = $params['name'];
        $filterType = $params['searchType'];
        $sortBy = $params['sortBy'];

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('cmhv.id as id', 'cmhv.value as name', 'cmhv.description as description', 'CAST(CASE WHEN COUNT(cmhv1.id) > 0 THEN 1 ELSE 0 END AS boolean) as hasChild', 'MAX(qt.testId) as quizId')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->leftJoin('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv1', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmhv1.parentId=cmhv.id')
                ->leftJoin('QuizzingPlatform\Entity\QtiTestMetadata', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.metadataValue = cmhv.id AND qtt.createdBy =:userId')
                ->leftJoin('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testId=qt.testId 
                    AND qt.generalTest=' . $this->app['cache']->fetch('ACTIVE')
                        . 'AND qt.isDeleted=' . $this->app['cache']->fetch('ACTIVE'))
                ->leftjoin('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qtt.createdBy')
                ->where('cmhv.metadata= :metadataId')
                ->andWhere('cmhv.parentId= :parentId')
                ->andWhere('cmhv.status= :status')
                ->setParameter('metadataId', $id)
                ->setParameter('parentId', $parentId)
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('userId', $userId)
                ->groupBy('cmhv.id');

        //Adding search conditions         
        if (isset($searchFilter)) {
            $serachFilterCondition = $qb->expr()->andx();

            if ($filterType == "startswith")//cecks starting letter
                $serachFilterCondition->add($qb->expr()->like('cmhv.value', $qb->expr()->literal($searchFilter . '%')));
            else if ($filterType == "equals")//search exactly
                $serachFilterCondition->add($qb->expr()->like('cmhv.value', $qb->expr()->literal($searchFilter)));
            else//searches inthe content
                $serachFilterCondition->add($qb->expr()->like('cmhv.value', $qb->expr()->literal('%' . $searchFilter . '%')));

            $query->andWhere($serachFilterCondition);
        }

        //Adding sorting options         
        if (isset($sortBy)) {
            $sort = CommonHelper::getSortingDetails($sortBy);
            $query->orderBy($sort['field'], $sort['type']);
        } else
            $query->orderBy('name', 'ASC');

        $metadataHierachyValue = $qb->getQuery()->getArrayResult();

        return $metadataHierachyValue;
    }

    /**
     * Fetches the level one/subject details of metadatafrom db
     * @param type $metadataId
     * @param type $metaValueIds
     */
    public function getSubjectDetails($productIds, $metadataId, $userId) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('cmhv.id  as id', 'cmhv.value as name', 'cmhv.description as description', 'MAX(qt.testId) as quizId')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->join('QuizzingPlatform\Entity\PrdProductMetadata', 'ppm', \Doctrine\ORM\Query\Expr\Join::WITH, 'ppm.metadataValue=cmhv.id')
                ->leftJoin('QuizzingPlatform\Entity\QtiTestMetadata', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.metadataValue = cmhv.id AND qtt.createdBy =:userId')
                ->leftJoin('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testId=qt.testId 
                    AND qt.generalTest=' . $this->app['cache']->fetch('ACTIVE')
                        . 'AND qt.isDeleted=' . $this->app['cache']->fetch('ACTIVE'))
                ->leftjoin('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qtt.createdBy')
                ->where('cmhv.status= :status')
                ->andWhere('ppm.product IN(:productIds)')
                ->andWhere('ppm.metadata =:metadataId')
                ->setParameter('metadataId', $metadataId)
                ->setParameter('productIds', $productIds)
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('userId', $userId)
                ->groupBy('id')
                ->orderBy('name', 'ASC');
        //echo $qb->getQuery()->getSql();die;
        $subjectDetails = $qb->getQuery()->getArrayResult();

        return $subjectDetails;
    }

    /**
     * Get the actual metadata id for client used in the backend
     * @param type $clientId
     * @param type $randomMetadataId
     */
    public function getclientMetadataId($clientId, $randomMetadataId) {
        $metadataDetails = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneBy(array('clientId' => $clientId, 'randomClientMetadataId' => $randomMetadataId));
        if ($metadataDetails)
            return $metadataDetails->getMetadataId();
        else
            return false;
    }

    /*
     * @Desc Get foreign key reference
     * @param type $resourceType
     * @param type $resourceId
     * @param type $userId
     */

    public function getMetadataReference($metadataId) {
        //Get reference for metadata
        $CmnMetadata = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadata')->findOneByMetadataId(array('metadataId' => $metadataId));
        return $CmnMetadata;
    }

    /**
     * Get taxonomy name
     * @param type $taxonomyId
     * @return type
     */
    public function getTaxonomyName($taxonomyId, $clientMetadataId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('cmhv.value')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->join('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=cmhv.metadata')
                ->where('cmhv.id= :id')
                ->andWhere('cmhv.metadata=:metadataId')
                ->setParameter('id', $taxonomyId)
                ->setParameter('metadataId', $clientMetadataId);
        $taxonomyName = $qb->getQuery()->getArrayResult();
        return $taxonomyName;
    }

    /**
     * Get client products
     * @param type $clientId
     * @return type
     */
    public function getClientProducts($clientId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('pp.productId', 'pp.parentProductId', 'pp.name')
                ->from('QuizzingPlatform\Entity\PrdProduct', 'pp')
                ->where('pp.client = :clientId')
                ->setParameter('clientId', $clientId);
        $productList = $qb->getQuery()->getArrayResult();

        return $productList;
    }

    /**
     * @Desc : Get metadata sublevel child ids and merge with parent ids
     * @param type $parentIds
     * @return type
     */
    public function getMetadataSubLevelIds($parentIds) {

        $metadataIds = array();
        foreach ($parentIds as $parentId) {

            // Get the recursive child ids for a given parentid
            $childIds = self::getRecursiveChildIds($parentId);
        }
        // Merge both parentids and child ids
        $metadataIds = array_merge($parentIds, $childIds);

        //Return the metadata ids.
        return $metadataIds;
    }

    /**
     * @Desc  Get the recursive child ids for a given parentid
     * @param type $parentId
     * @return type
     */
    public function getRecursiveChildIds($parentId) {

        //Get the child id's for a given parentid
        $qb = $this->em->createQueryBuilder();
        $qb->select('cmhv.id')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->where('cmhv.parentId= :parentId')
                ->setParameter('parentId', $parentId);
        $childIds = $qb->getQuery()->getArrayResult();

        // For each child id get thier child ids by calling self function recursively
        foreach ($childIds as $childId) {

            $child = $childId['id'];
            // Assign all the child ids to global array
            $this->hierarchySubChildIds[] = $child;

            if ($child) {
                // call the function recursively to get child ids.
                self::getRecursiveChildIds($child);
            }
        }

        //Return all the sub level child ids
        return $this->hierarchySubChildIds;
    }

    /**
     * @Desc  Get the list of snomed terms for provided copnceptIds
     * @param type $conceptId
     * @return type
     */
    public function getSnomedTerms($conceptIds) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('sc.id as conceptid', 'sd.typeid', 'sd.term')
                ->from('QuizzingPlatform\Entity\SnomedDescription', 'sd')
                ->join('QuizzingPlatform\Entity\SnomedConcept', 'sc', \Doctrine\ORM\Query\Expr\Join::WITH, 'sc.id=sd.conceptid AND sc.active=1');
        $query->where('sd.conceptid IN(:conceptids)')
                ->andWhere('sd.active=1')
                ->setParameter('conceptids', $conceptIds);
        $termList = $qb->getQuery()->getArrayResult();
        return $termList;
    }

    /**
     * @Desc  Get the list of taxonomy mapped to a metadata
     * @param array $metadataId
     * @return array
     */
    public function getMetadataTaxonomyMapping($metadataId) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('DISTINCT(sc.id) as id', 'cmhv.id as metadataId', 'cmhv.value as metadataValue')
                ->from('QuizzingPlatform\Entity\CmnMetadataTaxonomyMapping', 'cmtm')
                ->join('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmhv.id=cmtm.sourceTaxonomy')
                ->join('QuizzingPlatform\Entity\CmnTaxonomyTypeMaster', 'cttm1', \Doctrine\ORM\Query\Expr\Join::WITH, 'cttm1.taxonomyTypeId=cmtm.sourceTaxonomyType')
                ->join('QuizzingPlatform\Entity\CmnTaxonomyTypeMaster', 'cttm2', \Doctrine\ORM\Query\Expr\Join::WITH, 'cttm2.taxonomyTypeId=cmtm.destinationTaxonomyType')
                ->join('QuizzingPlatform\Entity\SnomedConcept', 'sc', \Doctrine\ORM\Query\Expr\Join::WITH, 'sc.id=cmtm.destinationTaxonomy')
                ->where('cmtm.sourceTaxonomy IN(:metadataId)')
                ->setParameter('metadataId', $metadataId);
        //echo $qb->getQuery()->getSQL();die;
        $termList = $qb->getQuery()->getResult();
        return $termList;
    }

    /**
     * @Desc  Get the list of child concepts from nomedRelationship table
     * @param int $conceptIds
     * @return array
     */
    public function getChildConceptList($conceptIds) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('sc1.id')
                ->from('QuizzingPlatform\Entity\SnomedRelationship', 'sr')
                ->join('QuizzingPlatform\Entity\SnomedConcept', 'sc', \Doctrine\ORM\Query\Expr\Join::WITH, 'sc.id=sr.sourceid AND sc.active=1')
                ->join('QuizzingPlatform\Entity\SnomedConcept', 'sc1', \Doctrine\ORM\Query\Expr\Join::WITH, 'sc1.id=sr.destinationid AND sc.active=1')
                ->where('sr.sourceid IN(:conceptids)')
                ->andWhere('sr.active=1')
                ->andWhere('sr.typeid=:typeId')
                ->andWhere('sr.moduleid=:snomedModuleId')
                ->setParameter('snomedModuleId', $this->app['config']['snomedModuleId'])
                ->setParameter('typeId', $this->app['config']['snomedRelationTypeId'])
                ->setParameter('conceptids', $conceptIds);

        $conceptList = $qb->getQuery()->getArrayResult();
        $conceptIdArr = array_column($conceptList, 'id');
        return $conceptIdArr;
    }

    /**
     * Get metadata values and node path
     * @return type
     */
    public function getMetadataValues() {

        //Get all metadata Details (FREE Text , LOOK up and HIERACHY)
        $qb = $this->em->createQueryBuilder();
        $qb->select('cm.metadataId', 'IDENTITY(cm.metadataType) as metadataTypeId', 'cmt.metadataTypeName', 'cm.status')
                ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataType=cmt.metadataTypeId')
                ->where('cm.status=:status')
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
        $allMetadataDetails = $qb->getQuery()->getArrayResult();

        //Based on the tagType Get the details from corresponding tables
        $metadataDetailsArray = array();
        foreach ($allMetadataDetails as $key => $metadataDetails) {
            $metadataTagTypeId = $metadataDetails['metadataTypeId'];
            $metadataId = $metadataDetails['metadataId'];

            /*
             * Free Text
             */
            if ($metadataTagTypeId == $this->app['cache']->fetch('FREE_TEXT')) {
                $qb = $this->em->createQueryBuilder();
                $qb->select('cm.metadataId', 'cmt.metadataTypeName as metadataType', 'cm.status')
                        ->from('QuizzingPlatform\Entity\CmnMetadata', 'cm')
                        ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataType=cmt.metadataTypeId')
                        ->where('cm.metadataId=:metadataId')
                        ->setParameter('metadataId', $metadataId);
                $metadataDetails = $qb->getQuery()->getArrayResult();

                foreach ($metadataDetails as $key => $value) {
                    $metadataDetails[$key]['taxonomyId'] = '';
                    $metadataDetails[$key]['taxonomyPath'] = '';
                    $metadataDetails[$key]['taxonomyName'] = '';
                    $metadataDetails[$key]['metadataType'] = strtoupper($value['metadataType']);
                }
            }
            /*
             * LookUp
             */ elseif ($metadataTagTypeId == $this->app['cache']->fetch('LOOKUP')) {
                $qb = $this->em->createQueryBuilder();
                $qb->select('cmv.id as taxonomyId', 'cmv.value as taxonomyName', 'cm.metadataId', 'cmt.metadataTypeName as metadataType', 'cmv.status')
                        ->from('QuizzingPlatform\Entity\CmnMetadataValues', 'cmv')
                        ->join('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=cmv.metadata')
                        ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataType=cmt.metadataTypeId')
                        ->where('cmv.metadata=:metadata')
                        ->setParameter('metadata', $metadataId);
                $metadataDetails = $qb->getQuery()->getArrayResult();

                foreach ($metadataDetails as $key => $value) {
                    $metadataDetails[$key]['status'] = $value['status'];
                    $metadataDetails[$key]['taxonomyPath'] = $value['taxonomyName'];
                    $metadataDetails[$key]['metadataType'] = strtoupper($value['metadataType']);
                }
            }
            /*
             * Hierachy
             */ elseif ($metadataTagTypeId == $this->app['cache']->fetch('HIERARCHY')) {
                $qb = $this->em->createQueryBuilder();
                $qb->select('cmhv.id as taxonomyId', 'cmhv.value as taxonomyName', 'cm.metadataId', 'cmt.metadataTypeName as metadataType', 'cmhv.status')
                        ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                        ->join('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=cmhv.metadata')
                        ->join('QuizzingPlatform\Entity\CmnMetadataType', 'cmt', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataType=cmt.metadataTypeId')
                        ->where('cmhv.metadata=:metadata')
                        ->setParameter('metadata', $metadataId);
                $metadataDetails = $qb->getQuery()->getArrayResult();

                foreach ($metadataDetails as $key => $value) {
                    $nodePath = self::getMetadataValuePath($value['taxonomyId'], $metadataId);
                    $metadataDetails[$key]['taxonomyPath'] = $nodePath;
                    $metadataDetails[$key]['metadataType'] = strtoupper($value['metadataType']);
                    $this->hierarchyParentNodes = array();
                }
            }
            array_push($metadataDetailsArray, $metadataDetails);
        }

        return $metadataDetailsArray;
    }

    /**
     * Get metadataTypeId By metadata typeName
     * @param type $metadataTagType
     * @return type
     */
    public function getMetadataTypeIdByName($metadataTagType) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('cmt.metadataTypeId as metadataTypeId')
                ->from('QuizzingPlatform\Entity\CmnMetadataType', 'cmt')
                ->where('cmt.metadataTypeName=:metadataTypeName')
                ->setParameter('metadataTypeName', $metadataTagType);
        $metadataTypeId = $qb->getQuery()->getArrayResult();

        return $metadataTypeId[0]['metadataTypeId'];
    }

    /**
     * @Desc Get subject and topics list along with thier test progress details for the given product ids.
     * @param type $productIds
     */
    public function getTaxonomyWithProgress($productIds, $userId) {

        $taxonomyFinalResult = array();

        // Get all the subjects which are mapped to the products
        $subjectQuery = 'CALL PRODUCT_TAXONOMY(?)';
        $subjectStmt = $this->app['pdo']->prepare($subjectQuery);
        $subjectStmt->bindParam(1, $productIds, \PDO::PARAM_STR);
        $subjectStmt->execute();
        $subjects = $subjectStmt->fetchAll();

        $subjectStmt->closeCursor();

        if (!empty($subjects)) {

            // Foreach subject get the progress details and thier child topic details. 
            foreach ($subjects as $key => $subject) {

                $subjectId = $subject['id'];

                // Get taxonomy details with test instance progress details 
                $taxonomyFinalResult['subjects'][$key] = self::getTaxonomyProgressDetails($subjectId, $userId);

                //Get topics list under the subjects
                $topicsListQuery = 'CALL SUBJECT_TOPIC_LIST(?)';
                $topicsListStmt = $this->app['pdo']->prepare($topicsListQuery);
                $topicsListStmt->bindParam(1, $subjectId, \PDO::PARAM_STR);
                $topicsListStmt->execute();
                $topicsList = $topicsListStmt->fetchAll();

                $topicsListStmt->closeCursor();

                if (!empty($topicsList)) {

                    foreach ($topicsList as $topic) {

                        $topicId = $topic['id'];

                        // Get taxonomy details with test instance progress details 
                        $taxonomyFinalResult['subjects'][$key]['topics'][] = self::getTaxonomyProgressDetails($topicId, $userId);
                    }
                }
            }
        }

        //Return final list of taxonomy details with progress details
        return $taxonomyFinalResult;
    }

    /**
     * @Desc Get taxonomy details with test instance progress details 
     * @param type $taxonomyId
     */
    public function getTaxonomyProgressDetails($taxonomyId, $userId) {

        $taxonomyResult = array();
        $this->hierarchySubTopicIds = array();

        // get all topics/subtopics list recurssively 
        $childIds = self::getRecursiveTopicIds($taxonomyId);

        if (!empty($childIds)) {

            // Add parent id to childs list
            $childIds[] = $taxonomyId;
            $taxonomyLists = implode(',', $childIds);
        } else {
            $taxonomyLists = $taxonomyId;
        }

        // Get subject/topic (taxonomy) level progress details
        $taxonomyProgressQuery = 'CALL SUBJECT_TOPIC_PROGRESS(?,?,?)';
        $taxonomyProgressStmt = $this->app['pdo']->prepare($taxonomyProgressQuery);
        $taxonomyProgressStmt->bindParam(1, $taxonomyId, \PDO::PARAM_STR);
        $taxonomyProgressStmt->bindParam(2, $taxonomyLists, \PDO::PARAM_STR);
        $taxonomyProgressStmt->bindParam(3, $userId, \PDO::PARAM_STR);
        $taxonomyProgressStmt->execute();
        $taxonomyProgress = $taxonomyProgressStmt->fetch();

        $taxonomyProgressStmt->closeCursor();

        // Store all the information to array.
        $taxonomyResult['id'] = (int) $taxonomyProgress['metadataValueId'];
        $taxonomyResult['name'] = $taxonomyProgress['metadataValueName'];
        $taxonomyResult['description'] = ($taxonomyProgress['description']) ? $taxonomyProgress['description'] : NULL;
        $taxonomyResult['numberOfMetadataQuestions'] = ($taxonomyProgress['numberOfMetadataQuestions']) ? (int) $taxonomyProgress['numberOfMetadataQuestions'] : NULL;
        $taxonomyResult['hasChild'] = (!empty($childIds)) ? true : false;
        $taxonomyResult['testId'] = ($taxonomyProgress['testId']) ? (int) $taxonomyProgress['testId'] : NULL;
        $taxonomyResult['testProgress']['totalTestQuestions'] = ($taxonomyProgress['totalTestQuestions']) ? (int) $taxonomyProgress['totalTestQuestions'] : NULL;
        $taxonomyResult['testProgress']['totalCorrectAnswers'] = ($taxonomyProgress['totalCorrectAnswers']) ? (int) $taxonomyProgress['totalCorrectAnswers'] : NULL;
        $taxonomyResult['testProgress']['totaWrongAnswers'] = ($taxonomyProgress['totaWrongAnswers']) ? (int) $taxonomyProgress['totaWrongAnswers'] : NULL;
        $taxonomyResult['testProgress']['totalUnAttempted'] = ($taxonomyProgress['totalUnAttempted']) ? (int) $taxonomyProgress['totalUnAttempted'] : NULL;

        return $taxonomyResult;
    }

    /**
     * @Desc  Get the recursive child ids for a given parentid
     * @param type $parentId
     * @return type
     */
    public function getRecursiveTopicIds($parentId) {

        $topicsListQuery = 'CALL SUBJECT_TOPIC_LIST(?)';
        $topicsListStmt = $this->app['pdo']->prepare($topicsListQuery);
        $topicsListStmt->bindParam(1, $parentId, \PDO::PARAM_STR);
        $topicsListStmt->execute();
        $childIds = $topicsListStmt->fetchAll();

        $topicsListStmt->closeCursor();

        // For each child id get thier child ids by calling self function recursively
        foreach ($childIds as $childId) {
//
            $child = $childId['id'];
            // Assign all the child ids to global array
            $this->hierarchySubTopicIds[] = $child;

            if ($child) {
                // call the function recursively to get child ids.
                self::getRecursiveTopicIds($child);
            }
        }

        //Return all the sub level child ids
        return $this->hierarchySubTopicIds;
    }

    public function getAllProducts($clientId) {
        try {

            $qb = $this->em->createQueryBuilder();
            $qb->select('pp.productId as id', 'pp.name', 'pp.description', 'pp.parentProductId')
                    ->from('QuizzingPlatform\Entity\PrdProduct', 'pp')
                    ->Where('pp.client = :clientId')
                    ->setParameter('clientId', $clientId);
            $query = $qb->getQuery();
            $allProducts = $query->getArrayResult();
            return $allProducts;
        } catch (Exception $ex) {
            //Add exceptions to logger''.
            $msg = 'Product for given clientid not found Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

}
