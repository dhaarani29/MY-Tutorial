<?php

/**
 * ReportsRepository - It's the repository class file to handle the reports tag module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Reports;

//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\CmnClient;
use QuizzingPlatform\Entity\OrgUserProfile;
use QuizzingPlatform\Entity\QtiTest;
use QuizzingPlatform\Entity\QtiTestMetadata;
use QuizzingPlatform\Entity\CmnMetadataHierarchyValues;

class ReportsRepository implements RepositoryInterface {

    protected $em;
    protected $app;
    public $getcount = 0;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->hierarchyids = array();
    }

    public function getUsageData($reportRequest = NULL, $export = NULL) {
        // Page number
        $page = $this->app['cache']->fetch('page');

        // Per page count
        $perPage = $this->app['cache']->fetch('limit');

        // Default sorting type
        $sortType = $this->app['cache']->fetch('sortType');

        // Default sorting field 
        $sortField = "title";

        // Declare a result array to return. 
        $itemCollectionValues = array();

        // Check if request is not null.
        if (!empty($reportRequest)) {

            foreach ($reportRequest as $key => $mrequest) {

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

        // Get Total of all the Tests based on the applied filters. 
        $totalUsageData = self::getUsageDataCount($title, $startDate, $endDate);
        $usageDetailData['total'] = $totalUsageData; // Total Tests count for pagination.
        $usageDetailData['data'] = array();

        // Check if count is greater than 0
        if ($totalUsageData > 0) {

            //Get the latest version of quiz
            $subQuery = $this->em->createQueryBuilder()
                    ->select('MAX(qtta.id)')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qtta')
                    ->where('qtta.isDeleted = :isDeleted') // Retrieve only active items
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->groupBy('qtta.testId')
                    ->getDQL();

            //Fetch the quiz name and quiz desc
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('qt.title as title', 'oup.firstName as firstName', 'oup.lastName as lastName', 'qt.testId as quizId', 'qtt.testTypeName as testTypeName', 'oc.clientName as clientName', "(CASE WHEN qts.description IS NULL THEN 'New' ELSE qts.description END) as testStatus")
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->join('QuizzingPlatform\Entity\QtiTestType', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testTypeId=qt.testType')
                    ->leftjoin('QuizzingPlatform\Entity\CmnClient', 'oc', \Doctrine\ORM\Query\Expr\Join::WITH, 'oc.clientId=qt.client')
                    ->leftjoin('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.testPk=qt.id')
                    ->leftjoin('QuizzingPlatform\Entity\qtiTestStatus', 'qts', \Doctrine\ORM\Query\Expr\Join::WITH, 'qts.testStatusId=qut.testStatus')
                    ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qt.createdBy')
                    ->where('qt.isDeleted = :isDeleted')
                    ->andWhere($qb->expr()->in('qt.id', $subQuery))
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

            // If title filter is passed, then add in to where condition.
            if ($title != "") {
                $query->andWhere($qb->expr()->like('qt.title', ':title'))
                        ->setParameter('title', '%' . $title . '%');
            }

            // If startDate filter is passed , then add in to where condition
            if ($startDate != "") {
                $query->andWhere('qt.createdDate >= :startDate')
                        ->setParameter('startDate', $startDate);
            }

            // If endDate filter is passed , then add in to where condition
            if ($endDate != "") {
                $query->andWhere('qt.createdDate <= :endDate')
                        ->setParameter('endDate', $endDate . " 23:59:59");
            }
            if (empty($export)) {
                // Add limits and sorting to query.
                $query->setFirstResult($offset)
                        ->setMaxResults($perPage)
                        ->orderBy($sortField, $sortType)
                        ->groupBy('qt.testId');
            } else {
                $query->orderBy($sortField, $sortType)
                        ->groupBy('qt.testId');
            }


            // Get the results
            $testDetails = $qb->getQuery()->getArrayResult(); //getSQL(); 
            $usageDetailData['data'] = $testDetails;
            //print_r($testDetails);die;
        }

        return $usageDetailData;
    }

    public function getUsageDataCount($title, $startDate, $endDate) {
        //Get the latest version of quiz
        $subQuery = $this->em->createQueryBuilder()
                ->select('MAX(qtta.id)')
                ->from('QuizzingPlatform\Entity\QtiTest', 'qtta')
                ->where('qtta.isDeleted = :isDeleted') // Retrieve only active items
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->groupBy('qtta.testId')
                ->getDQL();

        //Fetch the quiz name and quiz desc
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select("COUNT(DISTINCT(qt.testId) as total")
                ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                ->join('QuizzingPlatform\Entity\QtiTestType', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testTypeId=qt.testType')
                ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qt.createdBy')
                ->where('qt.isDeleted = :isDeleted')
                ->andWhere($qb->expr()->in('qt.id', $subQuery))
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        // If title filter is passed, then add in to where condition.
        if ($title != "") {
            $query->andWhere($qb->expr()->like('qt.title', ':title'))
                    ->setParameter('title', '%' . $title . '%');
        }

        // If startDate filter is passed , then add in to where condition
        if ($startDate != "") {
            $query->andWhere('qt.createdDate >= :startDate')
                    ->setParameter('startDate', $startDate);
        }

        // If endDate filter is passed , then add in to where condition
        if ($endDate != "") {
            $query->andWhere('qt.createdDate <= :endDate')
                    ->setParameter('endDate', $endDate . " 23:59:59");
        }

        $totalTests = $qb->getQuery()->getSingleScalarResult();
        return $totalTests;
    }

    public function find($id) {
        
    }

    public function create($request) {
        
    }

    public function delete($id) {
        
    }

    public function update($request, $updateRequest) {
        
    }

    public function getClientReports($sortParam = array(), $export = NULL) {
        try {

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "oc.clientName";


            // Check if request is not null.
            if (!empty($sortParam)) {

                foreach ($sortParam as $tagName => $tagValue) {
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


            // GENERIC QUERY
            $genericQuery = 'SELECT cc.client_name AS clientName, tbl1.studentsCount, tbl2.quizCount '
                    . ' FROM `cmn_client` cc '
                    . ' LEFT JOIN '
                    . ' ( SELECT client_id, COUNT(id) AS quizCount FROM qti_test WHERE is_deleted = ' . $this->app['isDeleted']['ACTIVE'] . ' GROUP BY client_id ) AS tbl2 '
                    . ' ON (cc.client_id = tbl2.client_id) '
                    . ' LEFT JOIN '
                    . ' ( SELECT client_id, COUNT(user_id) AS studentsCount FROM org_user_profile WHERE is_deleted = ' . $this->app['isDeleted']['ACTIVE'] . ' GROUP BY client_id ) AS tbl1 '
                    . ' ON (cc.client_id = tbl1.client_id) '
                    . ' WHERE cc.status = ' . $this->app['cache']->fetch('ACTIVE');

            if (isset($clientName) && strlen($clientName) > 0) {
                //GENERIC QUERY
                $genericQuery .= ' AND cc.client_name LIKE ' . "'%" . $clientName . "%'";
            }

            $genericQuery .= " GROUP BY cc.client_id ORDER BY " . $sortField . ' ' . $sortType;
            if (empty($export)) {
                //GENERIC QUERY
                $genericQuery .= ' LIMIT ' . $offset . ',' . $perPage;
            }

            $return = $this->app['db']->fetchAll($genericQuery);

            foreach ($return AS $k => $v) {
                $returnArr[$k]['clientName'] = $v['clientName'];
                $returnArr[$k]['studentsCount'] = (empty($v['studentsCount'])) ? 0 : $v['studentsCount'];
                $returnArr[$k]['quizCount'] = (empty($v['quizCount'])) ? 0 : $v['quizCount'];
            }

            $returnData['total'] = count($returnArr);
            $returnData['data'] = $returnArr;
            return $returnData;
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Get Client report Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    public function getUserQuizzingReports($sortParam, $export = NULL) {
        try {

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "quizCount";

            $topic = ($sortParam['title']) ? $sortParam['title'] : NULL;

            // Check if request is not null.
            if (!empty($sortParam)) {

                foreach ($sortParam as $tagName => $tagValue) {
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

            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('cmhv.value AS title, cmhv.description AS description, count(qtm.id) AS quizCount')
                    ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                    ->leftjoin('QuizzingPlatform\Entity\QtiTestMetadata', 'qtm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cmhv.id = qtm.metadataValue')
                    ->where('cmhv.status = :Status')
                    ->setParameter('Status', $this->app['cache']->fetch('ACTIVE'));

            if (isset($topic) && strlen($topic) > 0) {
                $query->andWhere($qb->expr()->like('cmhv.value', ':title'))
                        ->setParameter('title', '%' . $topic . '%');
            }


            // Add limits and sorting to query.
            if (empty($export)) {
                $query->setFirstResult($offset)
                        ->setMaxResults($perPage);
            }
            $query->orderBy($sortField, $sortType)
                    ->groupby('cmhv.id');

            $studentsQuizzingData = $qb->getQuery()->getArrayResult();

            $returnData['total'] = self::getUserQuizzingReportCount($sortParam);
            $returnData['data'] = $studentsQuizzingData;

            return $returnData;
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Get Students Quizzing report Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    public function getUserQuizzingReportCount($topic = NULL) {


        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('COUNT(cmhv.id) AS total')
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'cmhv')
                ->where('cmhv.status = :Status')
                ->setParameter('Status', $this->app['cache']->fetch('ACTIVE'));

        if (isset($topic['title']) && strlen($topic['title']) > 0) {
            $title = $topic['title'];
            $query->andWhere($qb->expr()->like('cmhv.value', ':title'))->setParameter('title', '%' . $title . '%');
        }

        $totalTests = $qb->getQuery()->getSingleScalarResult();
        return $totalTests;
    }

    //get metadata in row list order
    public function getMetadataReport($reportRequest = NULL, $export = NULL) {
        // Page number
        $page = $this->app['cache']->fetch('page');

        // Per page count
        $perPage = $this->app['cache']->fetch('limit');

        // Default sorting type
        $sortType = $this->app['cache']->fetch('sortType');

        // Default sorting field 
        $sortField = "value";

        // Declare a result array to return. 
        $metadataValues = array();

        // Check if request is not null.
        if (!empty($reportRequest)) {

            foreach ($reportRequest as $key => $mrequest) {

                // get values for $bankName,$description,$perPage  
                $$key = $mrequest;
            }

            // Logic found in CommonHelper class to get the offset value.
            $offset = CommonHelper::getOffset($page, $perPage);
            $pageLimit = $offset + $perPage; //added by srilakshmi for this report to get offset and llimit explicitly as query wont support
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



        $metadataListArray = array();

        $qb = $this->em->createQueryBuilder();
        $qb->select("qm.metadataName", "qtm.id", "IDENTITY(qtm.metadata) as metadata", "qtm.parentId", "qtm.value", "qtm.description")
                ->from('QuizzingPlatform\Entity\CmnMetadataHierarchyValues', 'qtm')
                ->join('QuizzingPlatform\Entity\CmnMetadata', 'qm', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtm.metadata=qm.metadataId')
                ->where('qtm.status = :isDeleted')
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
        //->andWhere('qtm.metadata = 1000');
        // If value filter is passed, then add in to where condition.
        if ($value != "") {
            $qb->andWhere($qb->expr()->like('qtm.value', ':value'))
                    ->setParameter('value', '%' . $value . '%');
        }
        // If
        //  description filter is passed, then add in to where condition.
        if ($description != "") {
            $qb->andWhere($qb->expr()->like('qtm.description', ':description'))
                    ->setParameter('description', '%' . $description . '%');
        }
        $metadataHierachyValue = $qb->getQuery()->getArrayResult();
        $branch = array();
        $listMetadataValue = $this->app['metadata.service']->createMetadataList($metadataHierachyValue, 0, 0, $branch);
        //print_r($listMetadataValue);die;
        $listMetadataValuePerPage = array();
        foreach ($listMetadataValue as $key => $eachTopic) {

            if (($value == '' && $description == '' && $export != 1) || (($value != '' || $description != '') && count($listMetadataValue) > $perPage && $export != 1)) {
                if ($key >= $offset && $key < $pageLimit) {

                    $metadataQuestionCount = $this->app['tests.repository']->getItemCountByTaxonomyId(array($eachTopic['id']), $eachTopic['metadata']);

                    //array_push($listMetadataValuePerPage, $eachTopic);
                    $listMetadataValuePerPage[$key] = $eachTopic;
                    $listMetadataValuePerPage[$key]['noOfQuestion'] = $metadataQuestionCount[0]['itemCount'];
                }
            } else {
                $metadataQuestionCount = $this->app['tests.repository']->getItemCountByTaxonomyId(array($eachTopic['id']), $eachTopic['metadata']);

                //array_push($listMetadataValuePerPage, $eachTopic);
                $listMetadataValuePerPage[$key] = $eachTopic;
                $listMetadataValuePerPage[$key]['noOfQuestion'] = $metadataQuestionCount[0]['itemCount'];
            }
        }
        //print_r($listMetadataValuePerPage);        die;

        $metadataListArray['total'] = count($listMetadataValue);
        $metadataListArray['data'] = $listMetadataValuePerPage;
        return $metadataListArray;
    }

    /**
     * Get most incorrect item details
     * @param type $reportRequest
     */
    public function getIncorrectItems($reportRequest, $export = NULL) {

        $label = '';

        // Page number
        $page = $this->app['cache']->fetch('page');

        // Per page count
        $perPage = $this->app['cache']->fetch('limit');

        // Default sorting type
        $sortType = $this->app['cache']->fetch('sortType');

        // Default sorting field 
        $sortField = "incorrectCount";

        // Check if request is not null.
        if (!empty($reportRequest)) {

            foreach ($reportRequest as $key => $mrequest) {

                // get values for $label,$page,$perPage  
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
        $itemdetails = array();
        $totalItemsCount = self::getIncorrectItemsCount($label);
        if ($totalItemsCount > 0) {
            //Fetch the most incorrect item details
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('IDENTITY(quti.itemPk) as itemId', 'qi.label as label', 'qi.promptText as promptText', 'COUNT(quti.itemPk) as incorrectCount')
                    ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                    ->leftJoin('QuizzingPlatform\Entity\QtiUserTestItemProgress', 'qutip', \Doctrine\ORM\Query\Expr\Join::WITH, 'qutip.userTestItem=quti.id')
                    ->where('qutip.correct=:correct')
                    ->andWhere('qi.isDeleted=:isDeleted')
                    ->setParameter('correct', $this->app['config']['incorrect'])
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

            //Label search filter
            if ($label != '') {
                $query->andWhere($qb->expr()->like('qi.label', ':label'))
                        ->setParameter('label', '%' . $label . '%');
            }

            if (empty($export)) {
                //Pagination and orderBy
                $query->setFirstResult($offset)
                        ->setMaxResults($perPage);
            }
            $query->groupBy('quti.itemPk')
                    ->orderBy($sortField, $sortType);

            $itemdetails = $qb->getQuery()->getArrayResult();
        }
        $itemDetails['total'] = $totalItemsCount;
        $itemDetails['data'] = $itemdetails;

        return $itemDetails;
    }

    /**
     * Get incorrect itemcount
     * @param type $label
     * @return type
     */
    public function getIncorrectItemsCount($label = NULL) {

        //Fetch the most incorrect item details
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('COUNT(qi.id) as total')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->leftJoin('QuizzingPlatform\Entity\QtiUserTestItemProgress', 'qutip', \Doctrine\ORM\Query\Expr\Join::WITH, 'qutip.userTestItem=quti.id')
                ->where('qutip.correct=:correct')
                ->andWhere('qi.isDeleted=:isDeleted')
                ->setParameter('correct', $this->app['config']['incorrect'])
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->groupBy('quti.itemPk');

        //Label search filter
        if ($label != '') {
            $query->andWhere($qb->expr()->like('qi.label', ':label'))
                    ->setParameter('label', '%' . $label . '%');
        }

        $totalCount = $qb->getQuery()->getArrayResult();

        return count($totalCount);
    }

}
