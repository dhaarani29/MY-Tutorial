<?php

/**
 * TestsRepository - It's the model class file to handle the Tests/Quiz module database operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Tests;

//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\QtiTest;
use QuizzingPlatform\Entity\QtiTestItems;
use QuizzingPlatform\Entity\QtiTestItemBanks;
use QuizzingPlatform\Entity\QtiTestType;
use QuizzingPlatform\Entity\QtiItem;
use QuizzingPlatform\Entity\QtiItemBank;
use QuizzingPlatform\Entity\QtiTestTarget;
use QuizzingPlatform\Entity\QtiTestTargetType;
use QuizzingPlatform\Entity\QtiTestLimit;
use QuizzingPlatform\Entity\QtiUserTest;
use QuizzingPlatform\Entity\QtiTestStatus;
use QuizzingPlatform\Entity\QtiUserTestItems;
use QuizzingPlatform\Entity\OrgUserProfile;
use QuizzingPlatform\Entity\QtiTestMetadata;
use QuizzingPlatform\Entity\CmnClient;
use QuizzingPlatform\Entity\CmnMetadataHierarchyValues;
use QuizzingPlatform\Entity\QtiUserTestItemProgress;
use QuizzingPlatform\Entity\QtiUserTestItemResponses;
use QuizzingPlatform\Entity\QtiTestLatestVersion;
use Symfony\Component\Security\Acl\Exception\Exception;

class TestsRepository implements RepositoryInterface {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->module = 'tests';
        $this->dateTime = $app['config']['dbDate'];
        $this->effectiveDateTo = $app['config']['effectiveDateTo'];
        $this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));
    }

    /**
     * Create the test/Quiz details
     * @param type $testsData
     * @return boolean
     */
    public function create($testsData, $requestFrom = NULL, $userId = NULL, $clientId = NULL) {

        try {

            //Input Request For test Creation

            $userId = $userId; // User id

            $title = isset($testsData['title']) ? $testsData['title'] : '';

            $label = isset($testsData['label']) ? $testsData['label'] : NULL;

            $testMode = isset($testsData['testMode']) ? $testsData['testMode'] : '0';

            $noOfQuestions = isset($testsData['noofQuestions']) ? $testsData['noofQuestions'] : '0';

            $enableFeedback = isset($testsData['enableFeedback']) ? $testsData['enableFeedback'] : '0';

            $navigationType = isset($testsData['navigationType']) ? $testsData['navigationType'] : '1';

            $quizTime = isset($testsData['quizTime']) ? $testsData['quizTime'] : '0';

            $itemTimeLimit = isset($testsData['questionTime']) ? $testsData['questionTime'] : '0';

            $overrideTimeLimit = isset($testsData['overrideTimeLimit']) ? $testsData['overrideTimeLimit'] : '0';

            $chooseQuestion = isset($testsData['chooseQuestion']) ? $testsData['chooseQuestion'] : '0';

            $randomizeItem = isset($testsData['randomizeQuestion']) ? $testsData['randomizeQuestion'] : '0';

            $randomizeAnswer = isset($testsData['randomizeAnswer']) ? $testsData['randomizeAnswer'] : '0';

            $testFeedback = isset($testsData['testFeedback']) ? $testsData['testFeedback'] : NULL;

            $testItemList = $testsData['associatedItems'];

            $parentTestId = isset($testsData['baseQuizId']) ? $testsData['baseQuizId'] : '0';

            $testItembankList = $testsData['testItemBanks'];

            $clientMetadataId = isset($testsData['id']) ? $testsData['id'] : NULL;

            $version = $this->app['cache']->fetch('version');
            //Get the previous testId
            $qb = $this->em->createQueryBuilder();
            $qb->select('MAX(qt.testId)')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt');
            $lastTest = $qb->getQuery()->getArrayResult();
            $lastTestId = isset($lastTest[0][1]) ? $lastTest[0][1] : '0';

            $clientIdObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByClientId(array('clientId' => $clientId));

            /*
             * Admin Quiz or Test Creation
             */
            if ($requestFrom) {

                //check Duplicate test name
                $qb = $this->em->createQueryBuilder();
                $qb->select('qt.testId')
                        ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                        ->where('qt.title =:title')
                        ->andWhere('qt.isDeleted =:isDeleted')
                        ->setParameter('title', $title)
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
                $testExist = $qb->getQuery()->getArrayResult();

                //If its not duplicate
                if (empty($testExist)) {
                    /*
                     * Insert into qti_test table
                     */
                    $qtiTest = new QtiTest();

                    //foreign key id of test type from qti_test_type table
                    $testType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => $this->app['cache']->fetch('Admin')));


                    $qtiTest->setTestId($lastTestId + 1);
                    $qtiTest->setTestType($testType);
                    $qtiTest->setTitle($title);
                    $qtiTest->setLabel($label);
                    $qtiTest->setVersion($version);
                    $qtiTest->setClient($clientIdObj);
                    $qtiTest->setTestMode($testMode);
                    $qtiTest->setNoOfQuestions($noOfQuestions);
                    $qtiTest->setEnableFeedback($enableFeedback);
                    $qtiTest->setNavigationType($navigationType);
                    $qtiTest->setTimeLimit($quizTime);
                    $qtiTest->setGeneralTest($this->app['cache']->fetch('generalQuizId'));
                    $qtiTest->setItemTimeLimit($itemTimeLimit);
                    $qtiTest->setOverrideTimeLimit($overrideTimeLimit);
                    $qtiTest->setChooseQuestion($chooseQuestion);
                    $qtiTest->setRandomizeItem($randomizeItem);
                    $qtiTest->setRandomizeAnswer($randomizeAnswer);
                    $qtiTest->setTestFeedback($testFeedback);
                    $qtiTest->setEffectiveDateFrom($this->dateTime);
                    $qtiTest->setEffectiveDateTo($this->effectiveDateTo);
                    $qtiTest->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                    $qtiTest->setCreatedBy($userId);
                    $qtiTest->setCreatedDate($this->dateTime);
                    $qtiTest->setModifiedBy($userId);
                    $qtiTest->setModifiedDate($this->dateTime);
                    $this->em->persist($qtiTest); //Inserting the Above Field Values to QtiTest table
                    $this->em->flush();

                    // Assign newly created test id to variable.
                    $testId = $qtiTest->getTestId();
                    $testPkId = $qtiTest->getId();
                    /*
                     * Insert into latest version table
                     */
                    $qtiTestLatestVersion = new QtiTestLatestVersion();
                    $qtiTestLatestVersion->setTestId($testId);
                    $qtiTestLatestVersion->setVersion($version);
                    $this->em->persist($qtiTestLatestVersion);
                    $this->em->flush();

                    /*
                     * Insert into qti_test_items
                     */


                    if (!empty($testItemList)) {
                        $testItems = $testItemList;
                        //Associated muliple items to test/Quiz
                        foreach ($testItems as $item) {
                            $itemId = $item['itemId'];
                            $version = $item['version'];
                            if (isset($version)) {
                                $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version);
                                $itemPkId = $itemPkDetails['id'];
                            } else {
                                $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
                                $itemPkId = $latestItemDetails['id'];
                            }
                            self::createItemsAssociation($itemPkId, $testPkId, $userId);
                        }
                        $this->em->flush();
                    }


                    /*
                     * Insert into qti_test_itembanks
                     */


                    if (!empty($testItembankList)) {
                        $testItembanks = explode(',', $testItembankList);
                        //Associated muliple items to test/Quiz
                        foreach ($testItembanks as $itembankId) {
                            self::createItemBanksAssociation($itembankId, $testPkId, $userId);
                        }
                        $this->em->flush();
                    }

                    /**
                     *  Store metadata association to the question.
                     */
                    $metadataAssociation = $testsData['metadataAssoc'];

                    if (!empty($metadataAssociation)) {

                        $metadataAssoc = $this->app['metadata.repository']->storeMetadataResourceAssociation($this->app['cache']->fetch('tests'), $metadataAssociation, $testPkId, $userId);
                        if (!$metadataAssoc) {
                            $this->app['log']->writeLog("Failed to store question metadata association for item : " . $itemId);
                        }
                    }

                    return $testId;
                } else {
                    return $this->app['cache']->fetch('exists');
                }
            }

            /*
             * End User Test Creation
             */ else {

                //Get the metadataId
                $metadataId = $this->app['metadata.repository']->getclientMetadataId($clientId, $clientMetadataId);

                //metadataId validation
                if (empty($metadataId)) {
                    return false;
                }

                //Check the quiz is custom or general
                $metadataValue = $testsData['metadataAssoc'];
                $quizType = $testsData['quizType'];
                $metadataValueArray = explode(',', $metadataValue);

                //Validating the metadatavalue
                foreach ($metadataValueArray as $key => $metadataValueId) {

                    //foreign key id of test type from qti_test_type table
                    $taxonomyObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadataHierarchyValues')->findOneBy(array('id' => $metadataValueId, 'metadata' => $metadataId));
                    if (empty($taxonomyObj)) {
                        return false;
                    }
                }

                if (!empty($quizType) && $quizType == $this->app['cache']->fetch('customQuiz')) {
                    //Custom Quiz = 2
                    $generalTest = $this->app['cache']->fetch('customQuizId');
                } else {
                    //General Quiz = 1
                    $generalTest = $this->app['cache']->fetch('generalQuizId');
                }

                if ($parentTestId == '0') {
                    //check Duplicate test name
                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('qt.testId')
                            ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                            ->where('qt.title =:title')
                            ->andWhere('qt.isDeleted =:isDeleted')
                            ->andWhere('qt.generalTest =:generalTest')
                            ->andWhere('qt.client=:client')
                            ->andWhere('qt.createdBy=:createdBy')
                            ->setParameter('title', $title)
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('generalTest', $generalTest)
                            ->setParameter('client', $clientId)
                            ->setParameter('createdBy', $userId);

                    $testExist = $qb->getQuery()->getArrayResult();
                }

                if (empty($testExist)) {

                    /*
                     * Insert into qti_test table
                     */
                    $qtiTest = new QtiTest();

                    //Calculating the quiz time = question time limit * no of question
                    $quizTime = isset($testsData['quizTime']) ? $testsData['quizTime'] : (($testsData['questionTime'] * $testsData['noofQuestions']) / 60);

                    //foreign key id of test type from qti_test_type table
                    $testType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => $this->app['cache']->fetch('EndUser')));
                    $qtiTest->setTestId($lastTestId + 1);
                    $qtiTest->setTestType($testType);
                    $qtiTest->setTitle($title);
                    $qtiTest->setLabel($label);
                    $qtiTest->setVersion($version);
                    $qtiTest->setClient($clientIdObj);
                    $qtiTest->setGeneralTest($generalTest);
                    $qtiTest->setTestMode($testMode); //1-Testmode 0-Review mode
                    $qtiTest->setNoOfQuestions($noOfQuestions);
                    $qtiTest->setEnableFeedback($enableFeedback);
                    $qtiTest->setNavigationType($navigationType);
                    $qtiTest->setTimeLimit($quizTime);
                    $qtiTest->setItemTimeLimit($itemTimeLimit);
                    $qtiTest->setOverrideTimeLimit($overrideTimeLimit);
                    $qtiTest->setChooseQuestion($chooseQuestion);
                    $qtiTest->setRandomizeItem(true); // For silver chair always considering randomize questions
                    $qtiTest->setRandomizeAnswer($randomizeAnswer);
                    $qtiTest->setTestFeedback($testFeedback);
                    $qtiTest->setEffectiveDateFrom($this->dateTime);
                    $qtiTest->setEffectiveDateTo($this->effectiveDateTo);
                    $qtiTest->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                    $qtiTest->setCreatedBy($userId);
                    $qtiTest->setCreatedDate($this->dateTime);
                    $qtiTest->setModifiedBy($userId);
                    $qtiTest->setModifiedDate($this->dateTime);
                    $this->em->persist($qtiTest); //Inserting the Above Field Values to QtiTest table
                    $this->em->flush();
                    // Assign newly created test id to variable.
                    $testId = $qtiTest->getTestId();
                    $testTitle = $qtiTest->getTitle();

                    /*
                     * Insert into latest version table
                     */
                    $qtiTestLatestVersion = new QtiTestLatestVersion();
                    $qtiTestLatestVersion->setTestId($testId);
                    $qtiTestLatestVersion->setVersion($version);
                    $this->em->persist($qtiTestLatestVersion);
                    $this->em->flush();

                    /*
                     * Topic/subject association
                     */

                    if (!empty($metadataValueArray) && $parentTestId == '0') {
                        foreach ($metadataValueArray as $key => $metadataValueId) {

                            //foreign key id of test type from qti_test_type table
                            $taxonomyObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadataHierarchyValues')->findOneById(array('id' => $metadataValueId));
                            $qtiTestTaxonomyAssoc = new QtiTestMetadata();
                            $qtiTestTaxonomyAssoc->setTestId($testId); //TestId
                            $qtiTestTaxonomyAssoc->setMetadataValue($taxonomyObj); //taxonomy id
                            $metadataObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadata')->findOneByMetadataId(array('metadataId' => $metadataId));
                            $qtiTestTaxonomyAssoc->setMetadata($metadataObj);
                            $qtiTestTaxonomyAssoc->setCreatedBy($userId);
                            $qtiTestTaxonomyAssoc->setCreatedDate($this->dateTime);
                            $qtiTestTaxonomyAssoc->setModifiedBy($userId);
                            $qtiTestTaxonomyAssoc->setModifiedDate($this->dateTime);
                            $this->em->persist($qtiTestTaxonomyAssoc);
                        }

                        $this->em->flush();
                    }

                    /*
                     * Store target type
                     */
                    $newQuestions = $testsData['newQuestions'];
                    $gotWrong = $testsData['gotWrong'];
                    $targetTypeName = array('newQuestions' => $newQuestions, 'gotWrong' => $gotWrong);
                    if (!empty($targetTypeName)) {

                        foreach ($targetTypeName as $key => $target) {

                            //Insert into qti_test_target table
                            $qtiTestTarget = new QtiTestTarget();
                            $qtiTest = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneByTestId(array('testId' => $testId));
                            $qtiTestTarget->setTestPk($qtiTest);
                            $testTargetType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestTargetType')->findOneByTestTargetTypeName(array('testTargetTypeName' => $key));
                            $qtiTestTarget->setTestTargetType($testTargetType);
                            $qtiTestTarget->setStatus($target);
                            $qtiTestTarget->setCreatedBy($userId);
                            $qtiTestTarget->setCreatedDate($this->dateTime);
                            $qtiTestTarget->setModifiedBy($userId);
                            $qtiTestTarget->setModifiedDate($this->dateTime);
                            $this->em->persist($qtiTestTarget); //Inserting the Above Field Values to QtiTest table
                        }

                        $this->em->flush();
                    }
                    $quizResponse = array('id' => $testId, 'name' => $testTitle, 'metadataAssoc' => implode(',', $metadataValueArray));
                    return $quizResponse;
                } else {
                    return $this->app['cache']->fetch('exists');
                }
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test creation Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Update the test details
     * @param type $testsData
     * @param type $testId
     * @return boolean
     */
    public function update($testsData, $testId, $accessToken = '', $requestFrom = '', $userId = '') {

        try {

            //Check the duplicate Test Name
            $qb = $this->em->createQueryBuilder();
            $qb->select('qt.testId')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->where('qt.title =:title')
                    ->andWhere('qt.testId != :testId')
                    ->andWhere('qt.isDeleted =:isDeleted')
                    ->setParameter('title', $testsData['title'])
                    ->setParameter('testId', $testId)
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
            $testExist = $qb->getQuery()->getArrayResult();

            $title = isset($testsData['title']) ? $testsData['title'] : '';

            $label = isset($testsData['label']) ? $testsData['label'] : NULL;

            $clientMetadataId = isset($testsData['id']) ? $testsData['id'] : NULL;

            $lastTestDetails = self::getLastClientpkidByTestId($testId);

            $testMode = isset($testsData['testMode']) ? $testsData['testMode'] : '0';

            $clientId = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByClientId(array('clientId' => $lastTestDetails[0]['clientId']));

            if (empty($clientId)) {
                //$clientId = $this->app['login.service']->getClientId($accessToken);
                return false;
            }

            if (empty($requestFrom)) {

                //Calculating the quiz time = question time limit * no of question
                $quizTime = isset($testsData['quizTime']) ? $testsData['quizTime'] : (($testsData['questionTime'] * $testsData['testTarget']['noofQuestions']) / 60);

                //foreign key id of test type from qti_test_type table
                $testType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => $lastTestDetails[0]['testType']));

                //Get the metadataId
                $metadataId = $this->app['metadata.repository']->getclientMetadataId($clientId->getClientId(), $clientMetadataId);

                $metadataValue = $testsData['metadataAssoc'];

                $metadataValueArray = explode(',', $metadataValue);

                //Validating the metadatavalue
                foreach ($metadataValueArray as $key => $metadataValueId) {

                    //foreign key id of test type from qti_test_type table
                    $taxonomyObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnMetadataHierarchyValues')->findOneBy(array('id' => $metadataValueId, 'metadata' => $metadataId));
                    if (empty($taxonomyObj)) {
                        return false;
                    }
                }
            } else {

                $quizTime = isset($testsData['quizTime']) ? $testsData['quizTime'] : '0';

                //foreign key id of test type from qti_test_type table
                $testType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => isset($testsData['testType']) ? $testsData['testType'] : $lastTestDetails[0]['testType']));
            }

            $noOfQuestions = isset($testsData['noofQuestions']) ? $testsData['noofQuestions'] : '0';

            $enableFeedback = isset($testsData['enableFeedback']) ? $testsData['enableFeedback'] : '0';

            $navigationType = isset($testsData['navigationType']) ? $testsData['navigationType'] : '1';

            $itemTimeLimit = isset($testsData['questionTime']) ? $testsData['questionTime'] : '0';

            $overrideTimeLimit = isset($testsData['overrideTimeLimit']) ? $testsData['overrideTimeLimit'] : '0';

            $chooseQuestion = isset($testsData['chooseQuestion']) ? $testsData['chooseQuestion'] : '0';

            $randomizeItem = isset($testsData['randomizeQuestion']) ? $testsData['randomizeQuestion'] : '0';

            $randomizeAnswer = isset($testsData['randomizeAnswer']) ? $testsData['randomizeAnswer'] : '0';

            $testFeedback = isset($testsData['testFeedback']) ? $testsData['testFeedback'] : NULL;

            //Admin test update
            if ($requestFrom) {
                //If its not duplicate
                if (empty($testExist)) {

                    /*
                     * Update qti_test table
                     */

                    //get latest version and increament it 
                    $latestTestDataArray = self::getLatestTestId($testId);

                    $version = $latestTestDataArray['version'] + 1;

                    //fetch the forign key value of test type from qti_test_type table
                    $qtiTestType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => $testType));
                    $qtiTest = new QtiTest();
                    $qtiTest->setTestType($qtiTestType);
                    $qtiTest->setTestId($testId);
                    $qtiTest->setTitle($title);
                    $qtiTest->setClient($clientId);
                    $qtiTest->setGeneralTest($lastTestDetails[0]['generalTest']);
                    $qtiTest->setVersion($version);
                    $qtiTest->setLabel($label);
                    $qtiTest->setTestMode($testMode);
                    $qtiTest->setNoOfQuestions($noOfQuestions);
                    $qtiTest->setEnableFeedback($enableFeedback);
                    $qtiTest->setNavigationType($navigationType);
                    $qtiTest->setTimeLimit($quizTime);
                    $qtiTest->setItemTimeLimit($itemTimeLimit);
                    $qtiTest->setOverrideTimeLimit($overrideTimeLimit);
                    $qtiTest->setChooseQuestion($chooseQuestion);
                    $qtiTest->setRandomizeItem($randomizeItem);
                    $qtiTest->setRandomizeAnswer($randomizeAnswer);
                    $qtiTest->setTestFeedback($testFeedback);
                    $qtiTest->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                    $qtiTest->setEffectiveDateFrom($this->dateTime);
                    $qtiTest->setEffectiveDateTo($this->dateTime);
                    $qtiTest->setCreatedBy($lastTestDetails[0]['createdBy']);
                    $qtiTest->setCreatedDate($this->dateTime);
                    $qtiTest->setModifiedBy($userId);
                    $qtiTest->setModifiedDate($this->dateTime);
                    $this->em->persist($qtiTest);
                    $this->em->flush();
                    $testPkId = $qtiTest->getId();

                    /*
                     * Store target type
                     */
                    $targetType = $testsData['testTarget'];

                    if (!empty($targetType)) {

                        $targetTypeId = $targetType['type'];

                        //Insert into qti_test_target table
                        $qtiTestTarget = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestTarget')->findOneByTest(array('test' => $testId));
                        $qtiTest = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneByTestId(array('testId' => $testId));
                        $qtiTestTarget->setTest($qtiTest);
                        $testTargetType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestTargetType')->findOneByTestTargetTypeId(array('testTargetTypeId' => $targetTypeId));
                        $qtiTestTarget->setTestTargetType($testTargetType);
                        $qtiTestTarget->setNoOfQuestions($targetType['noOfQuestions']);
                        $qtiTestTarget->setModifiedBy($userId);
                        $qtiTestTarget->setModifiedDate($this->dateTime);
                        $this->em->persist($qtiTestTarget); //Inserting the Above Field Values to QtiTest table
                        $this->em->flush();
                    }

                    /*
                     * Store the test limit
                     */
                    $testLimit = $testsData['testLimits'];
                    if (!empty($testLimit)) {

                        //Insert into qti_test_limit table
                        $qtiTestLimit = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestLimit')->findOneByTest(array('test' => $testId));
                        $qtiTest = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneByTestId(array('testId' => $testId));
                        $qtiTestLimit->setTest($qtiTest);
                        $qtiTestLimit->setMinTime(isset($testLimit['minTime']) ? $testLimit['minTime'] : '0');
                        $qtiTestLimit->setMaxTime(isset($testLimit['maxTime']) ? $testLimit['maxTime'] : '0');
                        $qtiTestLimit->setAllowLateSubmission(isset($testLimit['allowLateSubmission'])) ? $testLimit['allowLateSubmission'] : '0';
                        $qtiTestLimit->setModifiedBy($userId);
                        $qtiTestLimit->setModifiedDate($this->dateTime);
                        $this->em->persist($qtiTestLimit); //Inserting the Above Field Values to QtiTest table
                        $this->em->flush();
                    }

                    /*
                     * Update the latest version
                     */
                    $qtiTestLatest = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestLatestVersion')->findOneByTestId(array('testId' => $testId));
                    $qtiTestLatest->setVersion($version);
                    $this->em->flush();

                    /*
                     * Update the qti_test_items table
                     */

                    $assocItem = $testsData['associatedItems']; //Associated items
                    //For item association
                    if (!empty($assocItem)) {

                        $qb = $this->em->createQueryBuilder();
                        $qb->update('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                                ->set('qti.isDeleted', $this->app['cache']->fetch('DELETED'))
                                ->where('qti.testPk =:testPk')
                                ->setParameter('testPk', $testPkId)
                                ->getQuery()->execute();
                        $assocItemData = $assocItem;

                        foreach ($assocItemData as $item) {
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
                            $query = $qb->select('qti.id')
                                    ->from('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                                    ->where('qti.itemPk= :itemPk')
                                    ->setParameter('itemPk', $itemPkId)
                                    ->andWhere('qti.testPk=:testPk')
                                    ->setParameter('testPk', $testPkId);
                            $testAssocExist = $query->getQuery()->getArrayResult();

                            //If item is not associated ,then associate to test
                            if (empty($testAssocExist)) {

                                //Insert into qti_test_items table
                                self::createItemsAssociation($itemId, $testPkId, $userId);
                            }
                            //If its associated , change the status is active
                            else {

                                $qb = $this->em->createQueryBuilder();
                                $query = $qb->select('qti.id')
                                        ->from('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                                        ->where('qti.itemPk= :itemPk')
                                        ->setParameter('itemPk', $itemPkId)
                                        ->andWhere('qti.testPk=:testPk')
                                        ->setParameter('testPk', $testPkId);
                                $item = $qb->getQuery()->getArrayResult();

                                $qtiTestItems = $this->em->getReference('QuizzingPlatform\Entity\QtiTestItems', $item[0]['id']);
                                $qtiTestItems->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                            }
                        }$this->em->flush();
                    }
                    //For dissociation
//                        if (!empty($dissocItem)) {
//                            //Convert to array
//                            $disassocItemData = explode(',', $dissocItem);
//
//                            foreach ($disassocItemData as $itemId) {
//                                //Make the status to deleted
//                                self::deleteItemsAssociation($itemId, $testPkId);
//                            }
//                            $this->em->flush();
//                        }
                    //    }

                    /*
                     * Update qti_test_item_banks table
                     */

                    $assocItemBanks = $testsData['associatedItemBanks']; //Associated item banks
                    //$dissocItemBanks = $testsData['dissociatedItemBanks']; //Dissociated item banks
                    // if (!empty($assocItemBanks) || !empty($dissocItemBanks)) {
                    //For itemBank association
                    if (!empty($assocItemBanks)) {
                        //convert to array
                        $assocItemBanksData = explode(',', $assocItemBanks);

                        foreach ($assocItemBanksData as $itemBankId) {

                            $qb = $this->em->createQueryBuilder();
                            $query = $qb->select('qtib.id')
                                    ->from('QuizzingPlatform\Entity\QtiTestItemBanks', 'qtib')
                                    ->where('qtib.itemBank= :itemBank')
                                    ->setParameter('itemBank', $itemBankId)
                                    ->andWhere('qtib.testPk=:testPk')
                                    ->setParameter('testPk', $testPkId);
                            $testAssocExist = $query->getQuery()->getArrayResult();

                            //If item collection is not associated ,then associate to test
                            if (empty($testAssocExist)) {

                                //Insert into qti_test_item_banks table
                                self::createItemBanksAssociation($itemBankId, $testPkId, $userId);
                            }
                            //If its associated , change the status is active
                            else {

                                $qb = $this->em->createQueryBuilder();
                                $query = $qb->select('qtib.id')
                                        ->from('QuizzingPlatform\Entity\QtiTestItemBanks', 'qtib')
                                        ->where('qtib.itemBank= :itemBank')
                                        ->setParameter('itemBank', $itemBankId)
                                        ->andWhere('qtib.testPk=:testPk')
                                        ->setParameter('testPk', $testPkId);
                                $itemBank = $qb->getQuery()->getArrayResult();

                                $qtiTestItems = $this->em->getReference('QuizzingPlatform\Entity\QtiTestItemBanks', $itemBank[0]['id']);
                                $qtiTestItems->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                            }
                        }$this->em->flush();
                    }

                    //For dissociation
//                        if (!empty($dissocItemBanks)) {
//                            //Convert into array
//                            $disassocItemBankData = explode(',', $dissocItemBanks);
//
//                            foreach ($disassocItemBankData as $itemBankId) {
//                                //Make the status to deleted
//                                self::deleteItemBanksAssociation($itemBankId, $testPkId);
//                            }
//                            $this->em->flush();
//                        }
                    //   }
                    /**
                     *  Store metadata association to the item collection.
                     */
                    $metadataAssociation = $testsData['metadataAssoc'];

                    if (!empty($metadataAssociation)) {
                        //Delete the old record and recreate it.
                        // $this->app['metadata.repository']->deleteMetadataResourceAssociation($this->app['cache']->fetch('tests'), $testId);

                        $metadataAssoc = $this->app['metadata.repository']->storeMetadataResourceAssociation($this->app['cache']->fetch('tests'), $metadataAssociation, $testPkId, $userId);
                        if (!$metadataAssoc) {
                            $this->app['log']->writeLog("Failed to store question metadata association for item : " . $testId);
                        }
                    }
                    return $testPkId;
                }
                //If its Duplicate            
                else {
                    return $this->app['cache']->fetch('exists');
                }
            }
            /*
             * End User Test Updation
             */ elseif (empty($requestFrom)) {

                if (empty($testExist)) {

                    $passedCLientPkId = $this->app['login.service']->getClientId($accessToken);

                    if (($passedCLientPkId != $lastTestDetails[0]['clientId']) || ($userId != $lastTestDetails[0]['createdBy'])) { //validating it is from right portal (third party client)
                        return false;
                    }

                    /*
                     * Update qti_test table
                     */
                    $qb = $this->em->createQueryBuilder();
                    $qb->select('IDENTITY(qtta.metadataValue) as topicId')
                            ->from('QuizzingPlatform\Entity\QtiTestMetadata', 'qtta')
                            ->where('qtta.testId=:testId')
                            ->setParameter('testId', $testId);
                    $topicDetailsArray = $qb->getQuery()->getArrayResult();
                    $topicDetails = array();
                    foreach ($topicDetailsArray as $topicDetailsRecord) {

                        array_push($topicDetails, $topicDetailsRecord['topicId']);
                    }
                    $metadataPassed = explode(',', $testsData['metadataAssoc']);

                    $res1 = array_diff($topicDetails, $metadataPassed);
                    $res2 = array_diff($metadataPassed, $topicDetails);

                    if (!empty($res1) || !empty($res2)) {
                        return "Metadata not matching";
                    }


                    //fetch the forign key value of test type from qti_test_type table
                    $qtiTestType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => $testType));

                    //get latest version and increament it 
                    $latestTestDataArray = self::getLatestTestId($testId);

                    $latestTestData = $latestTestDataArray['version'] + 1;

                    $quizType = $testsData['quizType'];
                    if (!empty($quizType) && $quizType == $this->app['cache']->fetch('customQuiz')) {
                        //Custom Quiz = 2
                        $generalTest = $this->app['cache']->fetch('customQuizId');
                    } else {
                        //General Quiz = 1
                        $generalTest = $this->app['cache']->fetch('generalQuizId');
                    }

                    $qtiTest = new QtiTest();
                    //Calculating the quiz time = question time limit * no of question
                    $quizTime = isset($testsData['quizTime']) ? $testsData['quizTime'] : (($testsData['questionTime'] * $testsData['noofQuestions']) / 60);

                    //foreign key id of test type from qti_test_type table
                    $testType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestType')->findOneByTestTypeId(array('testTypeId' => $this->app['cache']->fetch('EndUser')));

                    $qtiTest->setTestType($testType);
                    $qtiTest->setTitle($title);
                    $qtiTest->setLabel($label);
                    $qtiTest->setVersion($latestTestData);
                    $qtiTest->setTestId($testId);
                    $qtiTest->setClient($clientId);
                    $qtiTest->setGeneralTest($generalTest);
                    $qtiTest->setTestMode($testMode); //1-Testmode 0-Review mode
                    $qtiTest->setNoOfQuestions($noOfQuestions);
                    $qtiTest->setEnableFeedback($enableFeedback);
                    $qtiTest->setNavigationType($navigationType);
                    $qtiTest->setTimeLimit($quizTime);
                    $qtiTest->setItemTimeLimit($itemTimeLimit);
                    $qtiTest->setOverrideTimeLimit($overrideTimeLimit);
                    $qtiTest->setChooseQuestion($chooseQuestion);
                    $qtiTest->setRandomizeItem($randomizeItem);
                    $qtiTest->setRandomizeAnswer($randomizeAnswer);
                    $qtiTest->setTestFeedback($testFeedback);
                    $qtiTest->setEffectiveDateFrom($this->dateTime);
                    $qtiTest->setEffectiveDateTo($this->effectiveDateTo);
                    $qtiTest->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                    $qtiTest->setCreatedBy($lastTestDetails[0]['createdBy']);
                    $qtiTest->setCreatedDate($this->dateTime);
                    $qtiTest->setModifiedBy($userId);
                    $qtiTest->setModifiedDate($this->dateTime);

                    $this->em->persist($qtiTest); //Inserting the Above Field Values to QtiTest table
                    $this->em->flush();
                    $id = $qtiTest->getId();
                    $testTitle = $qtiTest->getTitle();


                    $qtiTestLatest = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestLatestVersion')->findOneByTestId(array('testId' => $testId));
                    $qtiTestLatest->setVersion($latestTestData);
                    $this->em->flush();

                    /*
                     * Store target type
                     */
                    $newQuestions = $testsData['newQuestions'];
                    $gotWrong = $testsData['gotWrong'];
                    $targetTypeName = array('newQuestions' => $newQuestions, 'gotWrong' => $gotWrong);
                    if (!empty($targetTypeName)) {

                        foreach ($targetTypeName as $key => $target) {

                            //Insert into qti_test_target table
                            $qtiTestTarget = new QtiTestTarget();
                            //$qtiTest = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneByTestId(array('testId' => $id));
                            $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $id);
                            $qtiTestTarget->setTestPk($qtiTest);

                            $testTargetType = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestTargetType')->findOneByTestTargetTypeName(array('testTargetTypeName' => $key));
                            $qtiTestTarget->setTestTargetType($testTargetType);

                            $qtiTestTarget->setStatus($target);
                            $qtiTestTarget->setCreatedBy($userId);
                            $qtiTestTarget->setCreatedDate($this->dateTime);
                            $qtiTestTarget->setModifiedBy($userId);
                            $qtiTestTarget->setModifiedDate($this->dateTime);
                            $this->em->persist($qtiTestTarget); //Inserting the Above Field Values to QtiTest table
                        }

                        $this->em->flush();
                    }
                    $quizResponse = array('id' => (int) $testId, 'name' => $testTitle, 'metadataAssoc' => implode(',', $topicDetails));
                    return $quizResponse;
                } else {
                    return $this->app['cache']->fetch('exists');
                }
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Item collection creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the test details by testid
     * @param type $testId
     * @return type
     */
    public function find($testId, $requestFrom = '', $userId = '', $version = '') {

        try {
            if ($requestFrom) {
                //Get the test details from qti_test table
                $qb = $this->em->createQueryBuilder();
                $latestTestData = self::getLatestTestId($testId);
                $query = $qb->select('qt.id', 'qt.version', 'IDENTITY(qt.testType) as testType', 'oc.clientId as clientId', 'qt.title', 'qt.label', 'qt.testMode', 'qt.navigationType', 'qt.timeLimit as quizTime', 'qt.itemTimeLimit as questionTime', 'qt.overrideTimeLimit', 'qt.chooseQuestion', 'qt.randomizeItem as randomizeQuestion', 'qt.randomizeAnswer')
                        ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                        ->Join('QuizzingPlatform\Entity\QtiTestType', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testTypeId=qt.testType')
                        ->Join('QuizzingPlatform\Entity\CmnClient', 'oc', \Doctrine\ORM\Query\Expr\Join::WITH, 'oc.clientId=qt.client')
                        ->where('qt.isDeleted=:isDeleted')
                        ->andWhere('qt.testId=:testId')
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                        ->setParameter('testId', $testId);
                if (empty($version)) {
                    $query->andWhere('qt.version=:version')
                            ->setParameter('version', $latestTestData['version']);
                } else {
                    $query->andWhere('qt.version=:version')
                            ->setParameter('version', $version);
                }
                $testDetailsArray = $qb->getQuery()->getArrayResult();
                $testDetails = $testDetailsArray[0];
                if (!empty($version)) {
                    $testPkId = $testDetails['id'];
                } else {
                    $testPkId = $latestTestData['id'];
                }
                //Get the Associated metadata values
                if (!empty($testDetails)) {

                    //Fetch Metadata association details
                    $metadataAssoc = $this->app['metadata.repository']->getMetadataResourceAssociation($this->module, $testPkId);

                    if (!empty($metadataAssoc['metadataIds'])) {

                        // Get concatenated metadatas to get all the details of the selected metadatas
                        $metadataIds = $metadataAssoc['metadataIds'];

                        // Delete metadataIds from return array.
                        unset($metadataAssoc['metadataIds']);

                        // assign metadata association details to return array.
                        $testDetails['metadataAssoc'] = $metadataAssoc;

                        if (!empty($metadataIds)) {

                            //Get complete metadata details which are all associated.
                            $metadatasDetails = $this->app['metadata.repository']->getMetadataDetails($metadataIds);

                            // assign complete details of metadata's association details to return array.
                            $testDetails['selectedMetaDetails'] = $metadatasDetails;
                        }
                    }


                    //Get the test associated Item details
                    $qb = $this->em->createQueryBuilder();
                    $qb->select('qi.itemId as itemId', 'qi.label', 'qi.version')
                            ->from('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                            ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qti.itemPk')
                            ->Where('qti.isDeleted IN (:isDeleted)')
                            ->andWhere('qi.isDeleted IN ( :isDeleted )')
                            ->andWhere('qti.testPk=:testPk')
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('isDeleted', $this->statusArr)
                            ->setParameter('testPk', $testPkId);

                    $testItemsArray = $qb->getQuery()->getArrayResult();

                    $testDetails['testItems'] = $testItemsArray;

                    //Get the test associated itembank details
                    $qb = $this->em->createQueryBuilder();
                    $qb->select('IDENTITY(qtib.itemBank) as itemBankId', 'qib.itemBankName')
                            ->from('QuizzingPlatform\Entity\QtiTestItemBanks', 'qtib')
                            ->join('QuizzingPlatform\Entity\QtiItemBank', 'qib', \Doctrine\ORM\Query\Expr\Join::WITH, 'qib.itemBankId=qtib.itemBank')
                            ->Where('qtib.isDeleted IN (:isDeleted)')
                            ->andWhere('qib.isDeleted IN (:isDeleted)')
                            ->andWhere('qtib.testPk=:testPk')
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('isDeleted', $this->statusArr)
                            ->setParameter('testPk', $testPkId);
                    $testItemsBankArray = $qb->getQuery()->getArrayResult();

                    $testDetails['testItemBanks'] = $testItemsBankArray;

                    //Get the version list of the testId
                    $testDetails['versionsList'] = self::getAlltestVersions($testId);

                    //Return test details
                    return $testDetails;
                }
            } else if (empty($requestFrom)) {
                $qb = $this->em->createQueryBuilder();
                //get the latest test entry
                $latestTestData = self::getLatestTestId($testId);

                //get test details

                $query = $qb->select('qt.id', 'oc.clientId as clientId', 'qt.testId', 'qt.title', 'qt.generalTest', 'qt.navigationType', 'qt.testMode', 'qt.noOfQuestions', 'qt.itemTimeLimit', 'qt.timeLimit', 'qt.randomizeItem as randomizeQuestion')//'qttt.testTargetTypeName', 'qtt.status'
                        ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                        ->Join('QuizzingPlatform\Entity\CmnClient', 'oc', \Doctrine\ORM\Query\Expr\Join::WITH, 'oc.clientId=qt.client')
                        //->leftJoin('QuizzingPlatform\Entity\QtiTestTarget', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qtt.testPk')
                        //->leftJoin('QuizzingPlatform\Entity\QtiTestTargetType', 'qttt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qttt.testTargetTypeId=qtt.testTargetType')
                        //->join('QuizzingPlatform\Entity\QtiTestTaxonomyAssoc', 'qtta', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtta.test=qt.testId')
                        ->where('qt.testId=:testId')
                        //  ->andWhere('qt.version=:version')
                        ->andWhere('qt.isDeleted=:isDeleted')
                        ->setParameter('testId', $testId)
                        //  ->setParameter('version', $latestTestData['version'])
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

                if (empty($version)) {
                    $query->andWhere('qt.version=:version')
                            ->setParameter('version', $latestTestData['version']);
                } else {
                    $query->andWhere('qt.version=:version')
                            ->setParameter('version', $version);
                }


                $testDetailsArray = $qb->getQuery()->getArrayResult();

                if (!empty($testDetailsArray)) {

                    $testDetails['id'] = $testDetailsArray[0]['testId'];
                    $testDetails['name'] = $testDetailsArray[0]['title'];
                    $testDetails['questionTime'] = $testDetailsArray[0]['itemTimeLimit'];
                    $testDetails['noOfQuestions'] = $testDetailsArray[0]['noOfQuestions'];
                    $testDetails['navigationType'] = $testDetailsArray[0]['navigationType'];
                    $testDetails['quizTime'] = $testDetailsArray[0]['timeLimit'];
                    $testDetails['randomizeQuestion'] = $testDetailsArray[0]['randomizeQuestion'];
                    $testDetails['testMode'] = $testDetailsArray[0]['testMode'];
                    if ($testDetailsArray[0]['generalTest'] != $this->app['cache']->fetch('generalQuizId')) {
                        $testDetails['quizType'] = $this->app['cache']->fetch('customQuiz');
                    } else {
                        $testDetails['quizType'] = "";
                    }

                    $qb = $this->em->createQueryBuilder();
                    //query to get test target details depending on test pk id
                    $qb->select('qttt.testTargetTypeName', 'qtt.status')
                            ->from('QuizzingPlatform\Entity\QtiTestTarget', 'qtt')
                            ->leftJoin('QuizzingPlatform\Entity\QtiTestTargetType', 'qttt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qttt.testTargetTypeId=qtt.testTargetType')
                            ->where('qtt.testPk=:testId')
                            ->setParameter('testId', $testDetailsArray[0]['id']);
                    $testTargetDetailsArray = $qb->getQuery()->getArrayResult();

                    foreach ($testTargetDetailsArray as $testDetailsRecord) {

                        $testDetailsRecord['status'] = $testDetailsRecord['status'] == 1 ? true : false;
                        $testDetails[$testDetailsRecord['testTargetTypeName']] = $testDetailsRecord['status'];
                    }

                    //query to get metadata details of the test
                    $qb = $this->em->createQueryBuilder();
                    $qb->select('IDENTITY(qtta.metadataValue) as topicId', 'IDENTITY(qtta.metadata) as clientMetadataId')
                            ->from('QuizzingPlatform\Entity\QtiTestMetadata', 'qtta')
                            ->where('qtta.testId=:testId')
                            ->setParameter('testId', $testId);
                    $topicDetailsArray = $qb->getQuery()->getArrayResult();
                    $topicDetails = array();
                    $topicNames = array();

                    foreach ($topicDetailsArray as $topicDetailsRecord) {

                        array_push($topicDetails, $topicDetailsRecord['topicId']);
                        $getMatadataTopicNames = $this->app['metadata.repository']->getTaxonomyName($topicDetailsRecord['topicId'], $topicDetailsRecord['clientMetadataId']);
                        array_push($topicNames, $getMatadataTopicNames[0]['value']);
                    }
                    $testDetails['metadataAssoc'] = implode(',', $topicDetails);
                    $testDetails['metadataTitle'] = implode(',', $topicNames);
                    return $testDetails;
                }
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Delete the Quiz
     * @param type $testId
     * @return boolean
     */
    public function delete($testId, $userId = '', $requestFrom = '', $isDeleteAll = '', $version = '') {
        try {

            //get all the instances of the particular testId
            $qb = $this->em->createQueryBuilder();
            $qb->select('qut.id')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.testPk=qt.id')
                    ->where('qt.testId IN (:testId)')
                    ->setParameter('testId', $testId);
            $testAssoc = $qb->getQuery()->getArrayResult();

            /*
             * ADMIN QUIZ DELETE
             */
            if (!empty($requestFrom)) {

                //If the quiz is not started / Used
                if (empty($testAssoc)) {
                    //Delete All versions
                    if ($isDeleteAll == 'true') {

                        //Get all the version and delete the versions 
                        $allversion = self::getAllVersion($testId);

                        foreach ($allversion as $testPkId) {

                            $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testPkId);
                            //Delete the base quiz
                            $qtiTest->setIsDeleted($this->app['cache']->fetch('DELETED'));
                        }
                    }
                    //Delete particular version
                    else {
                        $testPkId = $this->app['itemcollection.repository']->getTestPkByVersion($testId, $version);
                        $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testPkId);
                        //Delete the base quiz
                        $qtiTest->setIsDeleted($this->app['cache']->fetch('DELETED'));
                    }
                    $this->em->flush();
                    $qb = $this->em->createQueryBuilder()
                            ->select('MAX(qt.version) as version')
                            ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                            ->where('qt.isDeleted = :isDeleted') // Retrieve only active items
                            ->andWhere('qt.testId = :testId') // Retrieve only parent=0 items
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('testId', $testId);

                    $previousVersionItem = $qb->getQuery()->getArrayResult();

                    if (!empty($previousVersionItem)) {

                        $version = $previousVersionItem[0]['version'];
                        $qtiItemLatestVersion = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestLatestVersion')->findOneByTestId(array('testId' => $testId));
                        $qtiItemLatestVersion->setVersion(isset($version) ? $version : 0);
                        $this->em->flush();
                    }
                    $this->app['log']->writeLog("Successfully soft deleted the test : " . $testId);

                    return true;
                }

                //Quiz started / used
                elseif (!empty($testAssoc)) {
                    //InActive all the version
                    if ($isDeleteAll == 'true') {
                        //Get all the version and delete the versions 
                        $allversion = self::getAllVersion($testId);

                        foreach ($allversion as $testPkId) {

                            $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testPkId);
                            //Delete the base quiz
                            $qtiTest->setIsDeleted($this->app['cache']->fetch('INACTIVE'));
                        }
                    }
                    //Inactive particular version
                    else {
                        $testPkId = $this->app['itemcollection.repository']->getTestPkByVersion($testId, $version);
                        $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testPkId);
                        //Delete the base quiz
                        $qtiTest->setIsDeleted($this->app['cache']->fetch('INACTIVE'));
                    }
                    //Delete the instance
                    foreach ($testAssoc as $instanceId) {

                        $qtiUserTest = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTest', $instanceId);
                        $qtiUserTest->setIsDeleted($this->app['cache']->fetch('DELETED'));
                    }

                    $this->em->flush();

                    $this->app['log']->writeLog("Successfully soft deleted the test: " . $testId);

                    return true;
                }
            }
            /*
             * END USER QUIZ DELETE
             */ else {

                $testDetails = self::getLatestTestId($testId);

                //Get the object of the testId
                $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testDetails['id']);

                if (!empty($testAssoc)) {

                    //End user can delete  the quiz only which they created
                    if ($userId == $qtiTest->getCreatedBy()) {

                        //Get all the version and delete the versions 
                        $allversion = self::getAllVersion($testId);

                        foreach ($allversion as $testPkId) {

                            $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testPkId);
                            //Delete the base quiz
                            $qtiTest->setIsDeleted($this->app['cache']->fetch('DELETED'));
                        }
                        //Delete the instance
                        foreach ($testAssoc as $instanceId) {

                            $qtiUserTest = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTest', $instanceId);
                            $qtiUserTest->setIsDeleted($this->app['cache']->fetch('DELETED'));
                        }

                        $this->em->flush();

                        $this->app['log']->writeLog("Successfully soft deleted the test : " . $testId);

                        return true;
                    } else {

                        $this->app['log']->writeLog("Failed to soft deleted the test : " . $testId);
                        return false;
                    }
                }
                //If not delete only base quiz
                else {

                    //End user can delete  the quiz only which they created
                    if ($userId == $qtiTest->getCreatedBy()) {

                        //Get all the version and delete the versions 
                        $allversion = self::getAllVersion($testId);

                        foreach ($allversion as $testPkId) {

                            $qtiTest = $this->em->getReference('QuizzingPlatform\Entity\QtiTest', $testPkId);
                            //Delete the base quiz
                            $qtiTest->setIsDeleted($this->app['cache']->fetch('DELETED'));
                        }

                        $this->em->flush();

                        $this->app['log']->writeLog("Successfully soft deleted the test : " . $testId);

                        return true;
                    } else {

                        $this->app['log']->writeLog("Failed to soft deleted the test : " . $testId);
                        return false;
                    }
                }
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Delete Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Delete the Quiz instance
     * @param type $instanceId
     * @return boolean
     */
    public function deleteInstance($instanceId, $userId = '', $requestFrom = '') {
        try {

            //Get the object of the testId
            $qtiTestInstance = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTest', $instanceId);
            /*
             * END USER QUIZ DELETE
             */
            if ($userId == $qtiTestInstance->getCreatedBy()) {

                //Delete the base quiz
                $qtiTestInstance->setIsDeleted($this->app['cache']->fetch('DELETED'));

                $this->em->flush();

                $this->app['log']->writeLog("Successfully soft deleted the test : " . $testId);

                return true;
            } else {

                $this->app['log']->writeLog("Failed to soft deleted the test : " . $testId);
                return false;
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Delete Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Listing the test details
     * @param type $testRequest
     * @param type $metadataRequest
     * @return type
     */
    public function getTestList($testRequest, $metadataRequest, $requestFrom = '', $userId = '') {
        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $title = $label = $quizType = $clientName = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

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
            if (!empty($testRequest)) {

                foreach ($testRequest as $key => $mrequest) {

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
            $totalItemcollection = self::getTestCount($title, $label, $metadataRequest, $userId, $quizType, $clientName, $requestFrom, $clientId);
            $testDetailsValues['total'] = ($totalItemcollection) ? ((int) $totalItemcollection) : 0; // Total Tests count for pagination.
            $testDetailsValues['data'] = array();

            // Check if count is greater than 0
            if ($totalItemcollection > 0) {

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
                $query = $qb->select('qt.title as title', 'qt.label as label', 'qt.testId as quizId', 'qtt.testTypeName as testTypeName', 'oac.clientName as clientName')
                        ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                        ->join('QuizzingPlatform\Entity\QtiTestType', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testTypeId=qt.testType')
                        ->join('QuizzingPlatform\Entity\CmnClient', 'oac', \Doctrine\ORM\Query\Expr\Join::WITH, 'oac.clientId=qt.client')
                        ->where('qt.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                        ->andWhere($qb->expr()->in('qt.id', $subQuery));

                //For End user Quiz listing , listing only the quiz which is createdby the login user
                if (empty($requestFrom)) {

                    //If the quiz type is cutom , listing only custom quiz details

                    if ($quizType == $this->app['cache']->fetch('customQuiz') && $userId) {

                        $query->andWhere('qt.generalTest=:generalTest')
                                ->andWhere('qtt.testTypeId=:testTypeId')
                                ->andWhere('qt.createdBy=:createdBy')
                                ->andWhere('qt.client=:client')
                                ->setParameter('generalTest', $this->app['cache']->fetch('customQuizId'))
                                ->setParameter('testTypeId', $this->app['cache']->fetch('EndUser'))
                                ->setParameter('createdBy', $userId)
                                ->setParameter('client', $clientId);
                    }
                    //Listing the general quiz
                    elseif (empty($quizType) && $userId) {
                        $query->andWhere('qt.generalTest=:generalTest')
                                ->andWhere('qtt.testTypeId=:testTypeId')
                                ->andWhere('qt.createdBy=:createdBy')
                                ->andWhere('qt.client=:client')
                                ->setParameter('generalTest', $this->app['cache']->fetch('generalQuizId'))
                                ->setParameter('testTypeId', $this->app['cache']->fetch('EndUser'))
                                ->setParameter('createdBy', $userId)
                                ->setParameter('client', $clientId);
                    } else {
                        return false;
                    }
                }

                //Metadata Filter
                if (!empty($metadataRequest)) {
                    $query->leftJoin('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.resourceId=qt.id')
                            ->leftJoin('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=crm.metadata');
                }

                // If title filter is passed, then add in to where condition.
                if ($title != "") {
                    $query->andWhere($qb->expr()->like('qt.title', ':title'))
                            ->setParameter('title', '%' . $title . '%');
                }

                // If label filter is passed , then add in to where condition
                if ($label != "") {
                    $query->andWhere($qb->expr()->like('qt.label', ':label'))
                            ->setParameter('label', '%' . $label . '%');
                }

                // If title filter is passed, then add in to where condition.
                if ($clientName != "") {
                    $query->andWhere($qb->expr()->like('oac.clientName', ':clientName'))
                            ->setParameter('clientName', '%' . $clientName . '%');
                }

                // If any metadata filters are passed. 
                if (!empty($metadataRequest)) {

                    $andX = $qb->expr()->orX();

                    foreach ($metadataRequest as $key => $association) {

                        // Check metadata type for the metadata passed
                        $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                        // Added below code to convert to array for hierarchy data as it has check in IN condition.
                        if (is_numeric($association)) {


                            //Check if associated metadata is of type hierarchy
                            if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                                // Get all the parent ids of the node passed
                                $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);
                                $parentNodes = $childNodes = array();
                                if ($this->app['cache']->fetch('parentNodes') == "YES") {
                                    // Get all the parent ids of the node passed
                                    $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $association);
                                }

                                if ($this->app['cache']->fetch('childNodes') == "YES") {
                                    //Get all the child nodes of the passed node.
                                    $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $association);
                                }
                                $association = array_unique(array_merge($parentNodes, $childNodes));
                            }
                        }

                        // Its common for both hierarchy and lookup
                        if (is_array($association)) {

                            $condition = $qb->expr()->andx();

                            $or = $qb->expr()->orX();

                            // Check for metadata Id
                            $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                            $or->add($qb->expr()->in('crm.value', $association));

                            //Check for all the metadata values
//                            foreach ($association as $value) { 
//                                $or->add($qb->expr()->in('crm.value', $value));
//                            }

                            $condition->add($or);
                        } else {

                            // Its for free text metadata.
                            $condition = $qb->expr()->andx();
                            $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                            $condition->add($qb->expr()->like('crm.value', $qb->expr()->literal('%' . $association . '%')));
                        }

                        $andX->add($condition);
                    }

                    $query->andWhere($andX);
                    $query->andWhere('crm.resourceType=:resourceTypeId')
                            ->setParameter('resourceTypeId', $this->app['cache']->fetch('tests'));
                }

                // Add limits and sorting to query.
                $query->setFirstResult($offset)
                        ->setMaxResults($perPage)
                        ->orderBy($sortField, $sortType)
                        ->groupBy('qt.testId');

                // Get the results
                $testDetails = $qb->getQuery()->getArrayResult(); //getSQL(); 

                if ($userId && empty($requestFrom)) {
                    //Listing the custom quiz details
                    foreach ($testDetails as $keys => $value) {
                        //Getting the most recent quiz instance id
                        $instanceDetails = self::getTestProgressBar($value['quizId'], $userId);
                        //Assign the instanceid
                        foreach ($instanceDetails as $values) {
                            $testDetails[$keys]['instanceId'] = $values['userTestId'];
                        }
                        //Remove the unwanted details from the array
                        unset($testDetails[$keys]['testTypeName'], $testDetails[$keys]['label'], $testDetails[$keys]['clientName']);
                    }
                }
                $testDetailsValues['data'] = $testDetails;
            }
            return $testDetailsValues;
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the count of tests based on the filters
     * @param type $title
     * @param type $label
     * @param type $metadataRequest
     * @return type
     */
    public function getTestCount($title = NULL, $label = NULL, $metadataRequest = NULL, $userId = NULL, $quizType = NULL, $clientName = NULL, $requestFrom = NULL, $clientId = NULL) {
        try {

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
            $query = $qb->select('COUNT(DISTINCT(qt.testId) as total')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->join('QuizzingPlatform\Entity\QtiTestType', 'qtt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtt.testTypeId=qt.testType')
                    ->join('QuizzingPlatform\Entity\CmnClient', 'oac', \Doctrine\ORM\Query\Expr\Join::WITH, 'oac.clientId=qt.client')
                    ->where('qt.isDeleted = :isDeleted')
                    ->andWhere($qb->expr()->in('qt.id', $subQuery))
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

            //For End user Quiz listing , listing only the quiz which is createdby the login user
            if (empty($requestFrom)) {


                //If the quiz type is cutom , listing only custom quiz details

                if ($quizType == $this->app['cache']->fetch('customQuiz') && $userId) {

                    $query->andWhere('qt.generalTest=:generalTest')
                            ->andWhere('qtt.testTypeId=:testTypeId')
                            ->andWhere('qt.createdBy=:createdBy')
                            ->andWhere('qt.client=:client')
                            ->setParameter('generalTest', $this->app['cache']->fetch('customQuizId'))
                            ->setParameter('testTypeId', $this->app['cache']->fetch('EndUser'))
                            ->setParameter('createdBy', $userId)
                            ->setParameter('client', $clientId);
                }
                //Listing the general quiz
                elseif ((empty($quizType)) && $userId) {
                    $query->andWhere('qt.generalTest=:generalTest')
                            ->andWhere('qtt.testTypeId=:testTypeId')
                            ->andWhere('qt.createdBy=:createdBy')
                            ->andWhere('qt.client=:client')
                            ->setParameter('generalTest', $this->app['cache']->fetch('generalQuizId'))
                            ->setParameter('testTypeId', $this->app['cache']->fetch('EndUser'))
                            ->setParameter('createdBy', $userId)
                            ->setParameter('client', $clientId);
                }
            }


            //Metadata Filter
            if (!empty($metadataRequest)) {
                $query->leftJoin('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.resourceId=qt.id')
                        ->leftJoin('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=crm.metadata');
            }

            // If title filter is passed, then add in to where condition.
            if ($title != "") {
                $query->andWhere($qb->expr()->like('qt.title', ':title'))
                        ->setParameter('title', '%' . $title . '%');
            }

            // If label filter is passed , then add in to where condition
            if ($label != "") {
                $query->andWhere($qb->expr()->like('qt.label', ':label'))
                        ->setParameter('label', '%' . $label . '%');
            }

            // If title filter is passed, then add in to where condition.
            if ($clientName != "") {
                $query->andWhere($qb->expr()->like('oac.clientName', ':clientName'))
                        ->setParameter('clientName', '%' . $clientName . '%');
            }

            // If any metadata filters are passed. 
            if (!empty($metadataRequest)) {

                $andX = $qb->expr()->orX();

                foreach ($metadataRequest as $key => $association) {

                    // Check metadata type for the metadata passed
                    $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                    // Added below code to convert to array for hierarchy data as it has check in IN condition.
                    if (is_numeric($association)) {


                        //Check if associated metadata is of type hierarchy
                        if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                            // Get all the parent ids of the node passed
                            $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);
                            $parentNodes = $childNodes = array();
                            if ($this->app['cache']->fetch('parentNodes') == "YES") {
                                // Get all the parent ids of the node passed
                                $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $association);
                            }

                            if ($this->app['cache']->fetch('childNodes') == "YES") {
                                //Get all the child nodes of the passed node.
                                $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $association);
                            }
                            $association = array_unique(array_merge($parentNodes, $childNodes));
                        }
                    }

                    // Its common for both hierarchy and lookup
                    if (is_array($association)) {

                        $condition = $qb->expr()->andx();

                        $or = $qb->expr()->orX();

                        // Check for metadata Id
                        $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                        $or->add($qb->expr()->in('crm.value', $association));

                        //Check for all the metadata values
//                        foreach ($association as $value) {
//                            $or->add($qb->expr()->in('crm.value', $value));
//                        }

                        $condition->add($or);
                    } else {

                        // Its for free text metadata.
                        $condition = $qb->expr()->andx();
                        $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                        $condition->add($qb->expr()->like('crm.value', $qb->expr()->literal('%' . $association . '%')));
                    }

                    $andX->add($condition);
                }

                $query->andWhere($andX);
                $query->andWhere('crm.resourceType=:resourceTypeId')
                        ->setParameter('resourceTypeId', $this->app['cache']->fetch('tests'));
            }

            $totalTests = $qb->getQuery()->getSingleScalarResult();
            return $totalTests;
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Create Item Association
     * @param type $itemId
     * @param type $testId
     * @param type $userId
     */
    public function createItemsAssociation($itemPkId, $testPkId, $userId) {

        $qtiTestItems = new QtiTestItems();
        //Get Item repository
        $itemPk = $this->em->getRepository('QuizzingPlatform\Entity\QtiItem')->findOneById(array('id' => $itemPkId));
        $qtiTestItems->setItemPk($itemPk);
        //Get Test Repository
        $test = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneById(array('id' => $testPkId));
        $qtiTestItems->setTestPk($test);
        $qtiTestItems->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
        $qtiTestItems->setCreatedBy($userId);
        $qtiTestItems->setCreatedDate($this->dateTime);
        $qtiTestItems->setModifiedBy($userId);
        $qtiTestItems->setModifiedDate($this->dateTime);
        $this->em->persist($qtiTestItems);
    }

    /**
     * Create Item bank Association
     * @param type $itemBankId
     * @param type $testId
     * @param type $userId
     */
    public function createItemBanksAssociation($itemBankId, $testPkId, $userId) {

        $qtiTestItems = new QtiTestItemBanks();
        //Get ItemBank repository
        $itemTypeId = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemBank')->findOneByItemBankId(array('itemBankId' => $itemBankId));
        $qtiTestItems->setItemBank($itemTypeId);
        //Get Test repository
        $test = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneById(array('id' => $testPkId));
        $qtiTestItems->setTestPk($test);
        $qtiTestItems->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
        $qtiTestItems->setCreatedBy($userId);
        $qtiTestItems->setCreatedDate($this->dateTime);
        $qtiTestItems->setModifiedBy($userId);
        $qtiTestItems->setModifiedDate($this->dateTime);
        $this->em->persist($qtiTestItems);
    }

    /**
     * @Desc Soft Delete the existing item association .
     * @param type $itemId
     * @param type $itemCollectionId
     */
    public function deleteItemsAssociation($itemId = '', $testPkId = '') {

        if ($itemId && $testPkId) {

            // Delete the item Association 
            $qb = $this->em->createQueryBuilder();
            $qb->select('qti.id')
                    ->from('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                    ->where('qti.testPk=:testPk')
                    ->andwhere('qti.itemPk=:itemPk')
                    ->setParameter('testPk', $testPkId)
                    ->setParameter('itemPk', $itemId);
            $id = $qb->getQuery()->getArrayResult();
            //Mark it as Deleted
            $qtiTestItems = $this->em->getReference('QuizzingPlatform\Entity\QtiTestItems', $id[0]['id']);
            $qtiTestItems->setIsDeleted($this->app['cache']->fetch('DELETED'));
            $this->em->flush();
        }
    }

    /**
     * Delete the existing item bank association
     * @param type $itemBankId
     * @param type $testId
     */
    public function deleteItemBanksAssociation($itemBankId = '', $testPkId = '') {

        if ($itemBankId && $testPkId) {
            // Delete the item bank Association .
            $qb = $this->em->createQueryBuilder();
            $qb->select('qtib.id')
                    ->from('QuizzingPlatform\Entity\QtiTestItemBanks', 'qtib')
                    ->where('qtib.testPk=:testPk')
                    ->andwhere('qtib.itemBank=:itemBank')
                    ->setParameter('testPk', $testPkId)
                    ->setParameter('itemBank', $itemBankId);
            $id = $qb->getQuery()->getArrayResult();
            //Mark it as Deleted
            $qtiTestItems = $this->em->getReference('QuizzingPlatform\Entity\QtiTestItemBanks', $id[0]['id']);
            $qtiTestItems->setIsDeleted($this->app['cache']->fetch('DELETED'));
            $this->em->flush();
        }
    }

    /**
     * Get the details of user test based on the testid and client id
     * @param $testId
     * @param $userId
     * @return mixed
     */
    public function getTestProgress($testId, $userId = NULL, $accessToken = NULL, $summary = NULL, $instanceId = NULL) {

        try {
            //Fetching the topics covered
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('IDENTITY(qut.testPk) as quizId', 'qt.noOfQuestions', 'qt.version', 'qut.totalQuestions', 'qut.id as instanceId', 'qts.testStatusName as status', 'qut.totalTimeSpent', 'qut.bookmark', 'qut.totalCorrect', 'qut.totalIncorrect', 'qut.totalUnattempted', 'qut.grade', 'qut.testStart as testStartDate', 'qut.testCompletedDate')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qut.testPk')
                    ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qut.user')
                    ->join('QuizzingPlatform\Entity\OrgUserType', 'out', \Doctrine\ORM\Query\Expr\Join::WITH, 'out.userTypeId=oup.userType')
                    ->join('QuizzingPlatform\Entity\QtiTestStatus', 'qts', \Doctrine\ORM\Query\Expr\Join::WITH, 'qts.testStatusId=qut.testStatus')
                    ->where('oup.userType=:userType')
                    ->andWhere('qut.isDeleted=:isDeleted')
                    ->andWhere('oup.status=:status')
                    ->andWhere('qut.user=:userId')
                    ->setParameter('userType', $this->app['cache']->fetch('EUP'))
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('userId', $userId)
                    ->andWhere('qt.testId=:testId')
                    ->setParameter('testId', $testId);
            if (!empty($instanceId)) { //to fetch details when instance id is passed
                $query->andWhere('qut.id=:testInstanceId')
                        ->setParameter('testInstanceId', $instanceId);
            } else { //to fetch all instance and test details when test id alone is passed
            }
            $testProgress = $qb->getQuery()->getArrayResult();
            $testDashboard['id'] = $testId;
            if (!empty($testProgress)) {
                if (empty($instanceId)) { //to fetch details when instance id is passed
                    //Get the item progress of particular test
                    foreach ($testProgress as $key => $value) {
                        $instanceId = $value['instanceId'];

                        $testInstanceProgress[$key]['id'] = $value['instanceId'];
                        $testInstanceProgress[$key]['status'] = $value['status'];
                        $testInstanceProgress[$key]['totalTimeSpent'] = $value['totalTimeSpent'];
                        $testInstanceProgress[$key]['testStartDate'] = $value['testStartDate'];
                        $testInstanceProgress[$key]['testCompletionDate'] = $value['testCompletedDate'];
                        //call function to generate launchurl
                        $testInstanceProgress[$key]['launchURL'] = self::generateLaunchURL($testId, $value['instanceId'], $value['version']);

                        if ($summary == 'true') {

                            $testInstanceProgress[$key]['testProgress']['totalTestQuestions'] = $value['totalQuestions'];
                            $testInstanceProgress[$key]['testProgress']['totalCorrectAnswers'] = $value['totalCorrect'];
                            $testInstanceProgress[$key]['testProgress']['totaWrongAnswers'] = $value['totalIncorrect'];
                            $testInstanceProgress[$key]['testProgress']['totalUnAttempted'] = $value['totalUnattempted'];
                        }
                    }

                    $testDashboard['instances'] = $testInstanceProgress;
                } else {

                    $testDashboard['launchURL'] = self::generateLaunchURL($testId, $testProgress[0]['instanceId'], $testProgress[0]['version']);
                    $testDashboard['id'] = $testProgress[0]['instanceId'];
                    $testDashboard['numberOfQuestions'] = $testProgress[0]['noOfQuestions'];
                    if ($summary == 'true') {
                        $testDashboard['testProgress']['totalTestQuestions'] = $testProgress[0]['totalQuestions'];
                        $testDashboard['testProgress']['totalCorrectAnswers'] = $testProgress[0]['totalCorrect'];
                        $testDashboard['testProgress']['totaWrongAnswers'] = $testProgress[0]['totalIncorrect'];
                        $testDashboard['testProgress']['totalUnAttempted'] = $testProgress[0]['totalUnattempted'];
                    }
                }
                return $testDashboard;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Progress Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the test details by test id got through taxonomy id
     * @param type $testId
     * @return type
     */
    public function findTaxonomyExist($topicId, $clientMetadataId) {

        try {

            //Get the test details from qti_test table
            $qb = $this->em->createQueryBuilder();

            $qb->select('MAX(qtm.testId) as testId')
                    ->from('QuizzingPlatform\Entity\QtiTestMetadata', 'qtm')
                    ->Join('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtm.testId=qt.testId')
                    ->where('qtm.metadataValue=:metadataValueId')
                    ->andWhere('qt.generalTest=:generalTest')
                    ->andWhere('qtm.metadata=:metadata')
                    ->setParameter('metadataValueId', $topicId)
                    ->setParameter('generalTest', $this->app['cache']->fetch('generalQuizId'))
                    ->setParameter('metadata', $clientMetadataId);

            $testDetailsArray = $qb->getQuery()->getArrayResult();

            //Get the Associated metadata values
            if (!empty($testDetailsArray)) {

                return $testDetailsArray[0]['testId'];
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Progress Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get test progress bar
     * @param type $testId
     * @param type $userId
     * @return boolean
     */
    public function getTestProgressBar($testId, $userId = NULL) {
        try {

            //Get the test details of particular test id
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('qut.id as userTestId', 'qut.totalCorrect', 'qut.totalIncorrect', 'qut.totalQuestions', 'qt.id as testPkId', 'qut.totalUnattempted', 'qt.testId as testId')
                    ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                    ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qut.user')
                    ->join('QuizzingPlatform\Entity\OrgUserType', 'out', \Doctrine\ORM\Query\Expr\Join::WITH, 'out.userTypeId=oup.userType')
                    ->leftJoin('QuizzingPlatform\Entity\QtiTestStatus', 'qts', \Doctrine\ORM\Query\Expr\Join::WITH, 'qts.testStatusId=qut.testStatus')
                    ->leftJoin('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qut.testPk')
                    ->where('oup.userType=:userType')
                    ->andWhere('qt.isDeleted=:isDeleted')
                    ->andWhere('oup.status=:status')
                    ->andWhere('qut.user=:userId')
                    ->andWhere('qt.testId=:testId')
                    ->andWhere('qut.isDeleted=:isDeleted')
                    ->setParameter('userType', $this->app['cache']->fetch('EUP'))
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('userId', $userId)
                    ->setParameter('testId', $testId)
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

            //fetching the testdetails of most recent quiz instance
            $query->orderBy('qut.id', 'DESC')
                    ->setFirstResult(0)
                    ->setMaxResults(1);

            //Fetching the no of questions for the testinstance or basetest
            $testProgress = $qb->getQuery()->getArrayResult();

            return $testProgress;
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Progress Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc Create test instance for a user
     * @param type $testPkId
     * @param type $userId
     * @return type
     */
    public function createTestInstanceForUser($testPkId, $userId, $totalQuestions) {

        //Get user object
        $userObj = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfile')->findOneByUserId(array('userId' => $userId));

        //Get test object
        $testPkObj = $this->em->getRepository('QuizzingPlatform\Entity\QtiTest')->findOneById(array('id' => $testPkId));

        //Get test status
        $qtiTestStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestStatus')->findOneByTestStatusId(array('testStatusId' => $this->app['cache']->fetch('Notstarted')));

        // Add user to quiz mapping. Create a quiz instance
        $qtiUserTest = new QtiUserTest();
        $qtiUserTest->setUser($userObj);
        $qtiUserTest->setTestPk($testPkObj);
        $qtiUserTest->setTestStatus($qtiTestStatus);
        $qtiUserTest->setTotalQuestions($totalQuestions);
        $qtiUserTest->setCreatedBy($userId);
        $qtiUserTest->setCreatedDate($this->dateTime);
        $qtiUserTest->setModifiedBy($userId);
        $qtiUserTest->setModifiedDate($this->dateTime);
        $this->em->persist($qtiUserTest);
        $this->em->flush();
        // Quiz instance ID.
        $userTesInstancetId = $qtiUserTest->getId();

        return $userTesInstancetId;
    }

    /**
     * @Desc Get latest quiz id 
     * @param type $testId
     * @return type
     */
    public function getLatestTestId($testId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('qt.id', 'qt.testId', 'qt.version')
                ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                ->join('QuizzingPlatform\Entity\QtiTestLatestVersion', 'qtlv', \Doctrine\ORM\Query\Expr\Join::WITH, 'qtlv.testId=qt.testId AND qtlv.version = qt.version')
                ->where('qt.testId = :testId')
                ->andWhere('qt.isDeleted = :isDeleted') // Retrieve only active items
                ->setParameter('testId', $testId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $testData = $qb->getQuery()->getArrayResult();
        return $testData[0];
    }

    /**
     * @Desc Store questions list for the instances
     * @param type $testItemsFinalList
     * @param type $userTesInstancetId
     * @param type $userId
     */
    public function storeTestItemsDetails($testItemsFinalList, $userTesInstancetId, $userId) {

        if (!empty($testItemsFinalList)) {

            $userTestObj = $this->em->getRepository('QuizzingPlatform\Entity\QtiUserTest')->findOneById(array('id' => $userTesInstancetId));
            $sequence = 0;

            foreach ($testItemsFinalList as $itemId) {

                //get latest version item id details of the parent
                $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
                $itemPkId = $latestItemDetails['id'];

                $itemObj = $this->em->getRepository('QuizzingPlatform\Entity\QtiItem')->findOneById(array('id' => $itemPkId));
                $version = $itemObj->getVersion();

                // Store the question sequence.
                $sequence++;

                // Store questions of a quiz instance
                $qtiUserTestItems = new QtiUserTestItems();
                $qtiUserTestItems->setItemPk($itemObj);
                $qtiUserTestItems->setUserTest($userTestObj);
                $qtiUserTestItems->setVersion($version);
                $qtiUserTestItems->setSequence($sequence);
                $qtiUserTestItems->setCreatedBy($userId);
                $qtiUserTestItems->setCreatedDate($this->dateTime);
                $qtiUserTestItems->setModifiedBy($userId);
                $qtiUserTestItems->setModifiedDate($this->dateTime);
                $this->em->persist($qtiUserTestItems);
            }
            $this->em->flush();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @Desc First get the questions associated with the quiz for this user and then exlude them while selecting the questions list.
     * @param type $metadataValue
     * @return type
     */
    public function getNewQuestionsForMetadataValue($metadataValue, $noOfQuestions, $userId, $randomizeQuestion) {

        // Get all the quiz mapped questions list for the provided taxonomy and the client user
        $quizItemPkIds = self::getMetadataValueTestsItemsList($metadataValue, $userId);

        // Exclude the quiz mapped questions and fetch only which are not part of quiz for that taxonomy.
        $newItemsList = self::getQuestionsForMetadataValue($metadataValue, $noOfQuestions, $quizItemPkIds, $randomizeQuestion);

        // Return items list
        if (!empty($newItemsList)) {

            $newItemsUnique = array_unique($newItemsList);

            //$itemIds = $this->app['items.repository']->getItemIdForPkIds($newItemsUnique);

            return $newItemsUnique;
        } else {
            return false;
        }
    }

    /**
     * @Desc : If taxonomy is passed then get the list of wrong answered questions from the previous quiz by that user
     * @param type $metadataValue
     * @param type $noOfQuestions
     * @param type $userId
     * @return type
     */
    public function getGotWrongQuestionsForMetadataValue($metadataValue, $noOfQuestions, $userId, $randomizeQuestion) {

        // Always get the recent attempt details 
        // Use case : first attempt answered wrong, then 2nd attempt answered correctly then it should not come in these, as recent attmept is correct.
        $subQuery = $this->em->createQueryBuilder()
                ->select('MAX(aquti.id)')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'aquti')
                ->groupBy('aquti.itemPk')
                ->getDQL();

        // If taxonomy is passed then get the list of wrong answered questions from the previous quiz by that user
        $qb = $this->em->createQueryBuilder();

        $query = $qb->select('distinct(qi.id) as id', 'qi.itemId', 'qi.parent')
                ->from('QuizzingPlatform\Entity\QtiTestMetadata', 'qtm')
                ->join('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.testId=qtm.testId')
                ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.testPk=qt.id')
                ->join('QuizzingPlatform\Entity\QtiUserTestItems', 'quti', \Doctrine\ORM\Query\Expr\Join::WITH, 'quti.userTest=qut.id')
                ->leftJoin('QuizzingPlatform\Entity\QtiUserTestItemProgress', 'qutip', \Doctrine\ORM\Query\Expr\Join::WITH, 'qutip.userTestItem=quti.id')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->where($qb->expr()->in('quti.id', $subQuery))
                ->andWhere('qtm.metadataValue IN (:metadataValue)')
                ->andWhere('qt.isDeleted = :isDeleted') // Retrieve only active tests
                ->andWhere('qi.isDeleted = :isDeletedItem') // Retrieve only active items
                ->andWhere('qut.user = :userId') // resource type should be item
                ->andWhere('qutip.correct = :correct') // check for wrong answers condition
                ->setParameter('metadataValue', $metadataValue)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('isDeletedItem', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('userId', $userId)
                ->setParameter('correct', $this->app['config']['incorrect']); // check for wrong answers condition 


        if ($noOfQuestions) {
            $query->setFirstResult(0)
                    ->setMaxResults($noOfQuestions);
        }

        $quizItemIds = $qb->getQuery()->getArrayResult();

        $itemIds = $childPkIds = $parentItemPkIds = $childItemIds = $uniqueItemIds = array();
        $itemsCount = $finalItemsCount = 0;

        // Check if  child questions are wrong,then we need to retake the complete medical case question.
        // Separate other type of questions and medical case/clinical symptom child questions based on the parent flag.
        foreach ($quizItemIds as $items) {
            if ($items['parent'] == 0) {
                $itemIds[] = $items['itemId'];
            } else {
                $childPkIds[] = $items['id'];
            }
        }

        // For each other type items get the unique itemids and the count.
        if (!empty($itemIds)) {

            $uniqueItemIds = array_unique($itemIds);
            $itemsCount = count($uniqueItemIds);

            //If randomize questions flag is set, then we have to randomize other than medical/clinical symptom questions.
            if ($randomizeQuestion) {
                shuffle($uniqueItemIds);
            }
        }

        // If other type items count is same as the expected items count then return them. 
        if ($itemsCount == $noOfQuestions) {

            return $uniqueItemIds;
        } else {


            // Medical /clinical case questions are incorrect then 
            // Retake the whole question once again if any one the question failed in medical case /clinical symptom.  

            if (!empty($childPkIds)) {

                foreach ($childPkIds as $child) {

                    // get the parent ids of the childIds passed. 
                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('MAX(qia.id) as parentItemPkId')
                            ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                            ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                            ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                            ->where('qi.id = :relation')
                            ->setParameter('relation', $child);
                    $parentItems = $qb->getQuery()->getArrayResult();

                    $parentItemPkIds[] = $parentItems[0]['parentItemPkId'];
                }

                // Get unique parent keys. 
                $uniquParentItemPkIds = array_unique($parentItemPkIds);

                // For all parents get thier complete list of childs. 

                if (!empty($uniquParentItemPkIds)) {

                    $finalItemsCount += $itemsCount;

                    foreach ($uniquParentItemPkIds as $parentItem) {

                        // Holds invidual parent childs 
                        $parentChildItemIds = array();

                        $qb = $this->em->createQueryBuilder();
                        $query = $qb->select('qi.id', 'qi.itemId')
                                ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                                ->where('qia.id = :parentItem')
                                ->setParameter('parentItem', $parentItem);
                        $allChildItems = $qb->getQuery()->getArrayResult();
                        foreach ($allChildItems as $child) {
                            $parentChildItemIds[] = $child['itemId'];
                        }

                        $parentChildItemIdsCount = count($parentChildItemIds);

                        //Add child question count to the itemcount list and check its matching number of questions 
                        $finalItemsCount += $parentChildItemIdsCount;

                        // check if calculated number of questions are matching required number of quesions then add them.
                        if ($finalItemsCount <= $noOfQuestions) {
                            $childItemIds[] = $parentChildItemIds;
                        } else {
                            break;
                        }
                    }
                }

                // If child items are there then add them to $uniqueItemIds list to return
                if (!empty($childItemIds)) {

                    foreach ($childItemIds as $childItems) {

                        foreach ($childItems as $childItem) {

                            $uniqueItemIds[] = $childItem;
                        }
                    }
                }
            }

            // Return the final wrong answer questions.
            return $uniqueItemIds;
        }
    }

    /**
     * @Desc Get all the quiz  associated questions list for the requested user and requested taxonomy.
     * @param type $metadataValue
     * @param type $userId
     */
    public function getMetadataValueTestsItemsList($metadataValue, $userId) {

        // Get all the quizzes for the taxonomy and then get all the qustions under the quizzes.
        $qb = $this->em->createQueryBuilder();
        $qb->select('distinct(qi.id) as id', 'qi.itemId', 'qi.parent')
                ->from('QuizzingPlatform\Entity\QtiTestMetadata', 'qtm')
                ->join('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.testId=qtm.testId')
                ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.testPk=qt.id')
                ->join('QuizzingPlatform\Entity\QtiUserTestItems', 'quti', \Doctrine\ORM\Query\Expr\Join::WITH, 'quti.userTest=qut.id')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->where('qtm.metadataValue IN (:metadataValue)')
                ->andWhere('qt.isDeleted = :isDeleted') // Retrieve only active tests
                ->andWhere('qut.user = :userId') // resource type should be item
                ->setParameter('metadataValue', $metadataValue)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('userId', $userId);

        // This array will have both medical case/clinical symptoms child questions and other types of questions
        $quizItemIds = $qb->getQuery()->getArrayResult();

        $itemIds = $childPkIds = $parentItemPkIds = $parentItemsIds = array();


        // Separate other type of questions and medical case/clinical symptom child questions based on the parent flag.
        foreach ($quizItemIds as $items) {
            if ($items['parent'] == 0) {
                $itemIds[] = $items['itemId'];
            } else {
                $childPkIds[] = $items['id'];
            }
        }

        // If any tests taken with medical case/clinical symptom questions then child ids will be there. 
        // For those child ids get the parent ids

        if (!empty($childPkIds)) {

            foreach ($childPkIds as $child) {

                // get the parent ids of the childIds passed. 
                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('MAX(qia.id) as parentItemPkId')
                        ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                        ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                        ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                        ->where('qi.id = :relation')
                        ->setParameter('relation', $child);
                $parentItems = $qb->getQuery()->getArrayResult();

                $parentItemPkIds[] = $parentItems[0]['parentItemPkId'];
            }

            // Get unique parent keys. 
            $uniquParentItemPkIds = array_unique($parentItemPkIds);

            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('qi.id', 'qi.itemId')
                    ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                    ->where('qi.id IN (:ids)')
                    ->setParameter('ids', $uniquParentItemPkIds);
            $parentItemDetails = $qb->getQuery()->getArrayResult();

            foreach ($parentItemDetails as $parent) {
                $parentItemsIds[] = $parent['itemId'];
            }
        }

        if (!empty($itemIds) && !empty($parentItemsIds))
        // Finally merge medical/clinical symptom parentIds with other type ids.
            $finalItemIds = array_merge($itemIds, $parentItemsIds);
        else if (!empty($itemIds))
            $finalItemIds = $itemIds;
        else if (!empty($parentItemsIds))
            $finalItemIds = $parentItemsIds;

        if (!empty($finalItemIds)) {

            $finalItems = array_unique($finalItemIds);

            // Return the final quiz associated questions list.
            return $finalItems;
        } else {
            return false;
        }
    }

    /**
     * @Desc Get the list of questions for the taxonomy based on the conditions passed.
     * @param type $metadataValue
     * @return type
     */
    public function getQuestionsForMetadataValue($metadataValue, $noOfQuestions = NULL, $excludeQuestions = NULL, $randomizeQuestion = NULL) {


        //Fetch taxonomy question association details 
        try {



            // Get noOf Questions for the matched taxonomy.
//            $subQuery = $this->app['items.repository']->getSubQueryForHighestVersion();
//
//            $qb = $this->em->createQueryBuilder();
//            $query = $qb->select('DISTINCT(qi.itemId) as itemId', 'qti.itemTypeId')
//                    ->from('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm')
//                    ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.itemId=crm.resourceId')
//                    ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
//                    ->join('QuizzingPlatform\Entity\CmnResourceType', 'crt', \Doctrine\ORM\Query\Expr\Join::WITH, 'crt.resourceId=crm.resourceType')
//                    ->join('QuizzingPlatform\Entity\QtiItemType', 'qti', \Doctrine\ORM\Query\Expr\Join::WITH, 'qti.itemTypeId=qi.itemType')
//                    ->where($qb->expr()->in('qi.itemId', $subQuery))
//                    ->andWhere('crm.value IN (:value)') // taxonomy should be in vlaue column
//                    ->andWhere('crt.resourceId = :resourceId') // resource type should be item
//                    ->andWhere('qi.isDeleted = :isDeleted') // Retrieve only active items
//                    ->andWhere('qi.parent = :parent') // Retrieve only parent=0 items
//                    ->andWhere('qs.statusName = :status')
//                    ->setParameter('value', $metadataValue)
//                    ->setParameter('resourceId', $resourceType)
//                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
//                    ->setParameter('parent', 0)
//                    ->setParameter('status', 'Published');
//
//            if ($excludeQuestions) {
//                $query->andWhere($qb->expr()->notin('qi.itemId', $excludeQuestions));
//            }
//
//            if ($noOfQuestions) {
//                $query->setFirstResult(0)
//                        ->setMaxResults($noOfQuestions);
//            }
//            $associatedItemIds = $qb->getQuery()->getArrayResult(); 


            $resourceType = $this->app['cache']->fetch('item');
            $implodedMetadata = implode(',', $metadataValue);
            $query = "SELECT DISTINCT (qia.id) AS id, qia.itemId, qia.item_type_id as itemTypeId
                        FROM cmn_resource_metadata crm 
                        INNER JOIN (SELECT MAX(qi.id) AS id, qi.item_id as itemId, qi.status_id,qi.item_type_id 
                                                FROM qti_item qi 
                                                INNER JOIN qti_status qs ON (qs.status_id = qi.status_id) 
                                                INNER JOIN qti_item_type qit ON (qit.item_type_id = qi.item_type_id) 
                                                WHERE qi.is_deleted = '" . $this->app['cache']->fetch('ACTIVE') . "' 
                                                    AND qi.parent = 0 AND qs.status_name = 'Published'
                                                GROUP BY qi.item_id) as qia ON (qia.id = crm.resource_id) 
                        INNER JOIN cmn_resource_type qrt ON (qrt.resource_type_id = crm.resource_type_id) 
                        WHERE crm.value IN ($implodedMetadata) AND qrt.resource_type_id = $resourceType ";

            if ($excludeQuestions) {

                $implodedExcludeQuestions = implode(',', $excludeQuestions);

                $query .= " AND qia.itemId NOT IN ($implodedExcludeQuestions)";
            }

            $query .= " ORDER BY RAND() ";

            if ($noOfQuestions) {

                $query .= "LIMIT 0 , $noOfQuestions";
            }

            $associatedItemIds = $this->app['db']->fetchAll($query);

            if (!empty($associatedItemIds)) {

                $finalItemsList = array(); // Holds Final list of items computed
                $medicalClinicalTypeItems = array(); // Holds medical/clinical parent questions ids
                $otherTypeItems = array(); // Holds other type of questions ids
                $differenceQuestionsList = array();
                $newQuestionCount = 0; // New question count flag used to compute the difference count.
                // Categorize medical and clinical symptom questions and other type of questins if any. 
                foreach ($associatedItemIds as $key => $items) {

                    if (($items['itemTypeId'] == $this->app['cache']->fetch('MEDICAL_CASE')) || ($items['itemTypeId'] == $this->app['cache']->fetch('CLINICAL_SYMPTOMS') )) {
                        // Take medical and clinical symptom questions to one array
                        $medicalClinicalTypeItems[$key]['id'] = $items['id'];
                        $medicalClinicalTypeItems[$key]['itemId'] = $items['itemId'];
                    } else {
                        // take other type of questions to another array.
                        $otherTypeItems[] = $items['itemId'];
                    }
                }

                // Check if any medical and clinical symptom questions are there.
                if (!empty($medicalClinicalTypeItems)) {

                    // Then for each question get the child questions
                    foreach ($medicalClinicalTypeItems as $parentItemPkId) {

                        $mcDiffrenceCount = 0; // Take difference count in this variable.
                        // $newQuestionCount will sum up all the questins and stores. Check wether it is less than required questions.
                        if ($newQuestionCount < $noOfQuestions) {
                            $mcDiffrenceCount = $noOfQuestions - $newQuestionCount;
                        }

                        // If still not met the target count, then again request for next question medical/clinical question for the child questions.
                        if ($mcDiffrenceCount > 0) {
                            // Get all the child questions list for the given parent id
                            $medicalClinicalItems = self::getMedicalClinicalQuestions($parentItemPkId['id']);

                            // Check if any medical case child items exists then add them to $newQuestionCount.
                            if (!empty($medicalClinicalItems)) {
                                // Take the questions count
                                $medicalClinicalItemsCount = count($medicalClinicalItems);

                                // These 2 question types should always come as a group, 
                                // hence if its having more number of questions than the target count, then we should not consider this parent question
                                if ($medicalClinicalItemsCount <= $mcDiffrenceCount) {
                                    // Add questions count to $newQuestionCount
                                    $newQuestionCount += $medicalClinicalItemsCount;

                                    // Finally add all the child items to final items list
                                    foreach ($medicalClinicalItems as $item) {
                                        $finalItemsList[] = $item;
                                    }
                                }
                            }

                            // Add to exclude list to not to consider for next self call if required.
                            $excludeQuestions[] = $parentItemPkId['itemId'];
                        }
                    }
                }

                // If other type items are there continue
                if (!empty($otherTypeItems)) {

                    // Once after getting all the medical/clinical symptom questions if any check for $newQuestionCount still not equal to $noOfQuestions
                    if ($newQuestionCount < $noOfQuestions) {
                        $diffrenceCount = $noOfQuestions - $newQuestionCount;

                        // For the difference count get the questions from other type itgetems
                        $differenceOtherTypeItems = array_slice($otherTypeItems, 0, $diffrenceCount);
                        $newQuestionCount += count($differenceOtherTypeItems);

                        //print_r($differenceOtherTypeItems);

                        if (!empty($differenceOtherTypeItems)) {

                            //Still check if any less items are there than the target item then recall the same function to get the items by excluding the items
                            // If randomize questions flag is set, then we have to randomize other than medical/clinical symptom questions.
                            if ($randomizeQuestion) {
                                shuffle($differenceOtherTypeItems);
                            }

                            // Finally add other type questions to final items list. 
                            foreach ($differenceOtherTypeItems as $item) {
                                $finalItemsList[] = $item;
                                $excludeQuestions[] = $item;
                            }
                        }
                    }
                }

                // again check, still if new questions count is less than number of questions required then recall the self function by giving exclude items list.
                if ($newQuestionCount < $noOfQuestions) {

                    $diffrenceCount = $noOfQuestions - $newQuestionCount;

                    $differenceQuestionsList = self::getQuestionsForMetadataValue($metadataValue, $diffrenceCount, $excludeQuestions, $randomizeQuestion);


                    if (!empty($differenceQuestionsList)) {

                        // Finally add other type questions to final items list. 
                        foreach ($differenceQuestionsList as $item) {
                            $finalItemsList[] = $item;
                        }
                    }
                }

                // Return final list of items
                return $finalItemsList;
            } else {
                return false;
            }

            // return $itemIds;
        } catch (Exception $e) {

            //Add exceptions to logger
            $msg = 'Metadata Resource association Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc : Get medical/clinical symptom child questions for the given parent id nad for number of questions
     * @param type $parentItemPkId
     * @param type $diffrenceCount
     * @return type
     */
    public function getMedicalClinicalQuestions($parentItemPkId) {

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.id', 'qi.itemId', 'qir.sequence as childOrder')
                ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
                ->where('qi.isDeleted = :isDeleted') // Retrieve only active items
                ->andWhere('qia.id = :parent') // Retrieve assocaited items
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('parent', $parentItemPkId)
                ->orderBy('qir.sequence', 'ASC'); // Always consider based on the sequence 

        $childItems = $qb->getQuery()->getArrayResult();

        $childItemsIds = array();

        if (!empty($childItems)) {
            foreach ($childItems as $child) {

                $childItemsIds[] = $child['itemId'];
            }
        }

        // Return the child items list.
        return $childItemsIds;
    }

    /**
     * Get the itemcount based on the taxonomy id and clientMetadataId
     * @param type $topicId
     * @param type $clientMetadataId
     * @return type
     */
    public function getItemCountByTaxonomyId($subLevelIds, $clientMetadataId) {

        //Get the count of count questions assoiciated to taxonomy
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(crm.resourceId) as itemCount')
                ->from('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm')
                ->join('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=crm.metadata')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=crm.resourceId')
                ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
                ->where('crm.metadata= :metadata')
                ->andWhere($qb->expr()->in('crm.value', ':value'))
                ->andWhere('crm.resourceType=:resourceType')
                ->andWhere('qi.isDeleted=:isDeleted')
                ->andWhere('qs.statusName=:statusName')
                ->setParameter('metadata', $clientMetadataId)
                ->setParameter('value', $subLevelIds)
                ->setParameter('resourceType', $this->app['cache']->fetch('item'))
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('statusName', 'Published');

        $testItems = $qb->getQuery()->getArrayResult();
        //return count of item
        return $testItems;
    }

    /**
     * Get list of questions for the given quiz instance
     * @param type $instanceId
     * @param type $summary
     * @param type $testRequest
     * @return type
     */
    public function getTestInstanceItemsList($instanceId, $summary = NULL, $testRequest = NULL) {

        if (!empty($summary)) {

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Check if request is not null.
            if (!empty($testRequest)) {

                foreach ($testRequest as $key => $mrequest) {

                    // get values for $bankName,$description,$perPage  
                    $$key = $mrequest;
                }
            }
            // Logic found in CommonHelper class to get the offset value.
            $offset = CommonHelper::getOffset($page, $perPage);
        }

        // Get the questions list for given quiz instance id
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('DISTINCT(qi.id) as id', 'qi.itemId', 'qi.version', 'quti.sequence')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.id=quti.userTest')
                ->where('qut.id=:userTest')
                ->andWhere('qut.isDeleted = :isDeleted') // Retrieve only active items
                ->setParameter('userTest', $instanceId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        if (!empty($summary)) {
            $query->setFirstResult($offset)
                    ->setMaxResults($perPage)
                    ->orderBy('quti.sequence', 'ASC');
        }

        $instanceItemsList = $qb->getQuery()->getArrayResult();

        // Get the questions details

        $itemsDetails = array();
        $quizEngine = true; //Since refering the question getbyid method, we dont need to version details for quiz hence excluding
        // After storing quiz to question mapping get the question details and return the details.
        if (!empty($instanceItemsList)) {

            foreach ($instanceItemsList as $items) {
                $itemId = $items['itemId'];
                $sequence = $items['sequence'];
                $itemVersion = $items['version'];
                if (empty($summary)) {
                    // Get each quesion details.
                    $iterationData = $this->app['items.repository']->find($itemId, $quizEngine, NULL, $itemVersion);
                } else {
                    // Get each quesion details.
                    $iterationData = $this->app['items.repository']->find($itemId, $quizEngine, NULL, $itemVersion);
                    $iterationData['sequence'] = $sequence;
                }
                array_push($itemsDetails, $iterationData);
            }
        }

        // Return the question details
        return $itemsDetails;
    }

    /**
     * To get  test instance details
     */
    public function findInstance($instanceId, $requestFrom, $userId) {
        try {

            //Fetching the topics covered

            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('IDENTITY(qut.testPk) as quizId', 'qt.noOfQuestions', 'qut.id as instanceId', 'qts.testStatusName as status', 'qut.totalTimeSpent', 'qut.bookmark', 'qut.totalCorrect', 'qut.totalIncorrect', 'qut.totalUnattempted', 'qut.grade', 'qut.testStart as testStartDate', 'qut.testCompletedDate')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qut.testPk')
                    ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=qut.user')
                    ->join('QuizzingPlatform\Entity\OrgUserType', 'out', \Doctrine\ORM\Query\Expr\Join::WITH, 'out.userTypeId=oup.userType')
                    ->join('QuizzingPlatform\Entity\QtiTestStatus', 'qts', \Doctrine\ORM\Query\Expr\Join::WITH, 'qts.testStatusId=qut.testStatus')
                    ->where('oup.userType=:userType')
                    ->andWhere('qut.isDeleted=:isDeleted')
                    ->andWhere('oup.status=:status')
                    ->andWhere('qut.id=:testInstanceId')
                    ->andWhere('qut.user=:userId')
                    ->setParameter('userType', $this->app['cache']->fetch('EUP'))
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('testInstanceId', $instanceId)
                    ->setParameter('userId', $userId);

            $testProgress = $qb->getQuery()->getArrayResult();

            //print_R($testProgress);die;

            if (!empty($testProgress)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Progress Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get all the version of test
     * @param type $testId
     * @return type
     */
    public function getAllVersion($testId) {


        $qb = $this->em->createQueryBuilder();
        $qb->select('qt.id')
                ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                ->where('qt.testId=:testId')
                ->setParameter('testId', $testId);
        $allVersion = $qb->getQuery()->getArrayResult();
        return $allVersion;
    }

    /**
     * Get the clientid 
     * @param type $testId
     */
    public function getLastClientpkidByTestId($testId) {


        $latestTestDataArray = self::getLatestTestId($testId);
        $pkId = $latestTestDataArray['id'];
        $qb = $this->em->createQueryBuilder();
        $qb->select('IDENTITY(qt.client) as clientId', 'IDENTITY(qt.testType) as testType', 'qt.generalTest as generalTest', 'qt.createdBy as createdBy')
                ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                ->where('qt.testId=:testId')
                ->andWhere('qt.id=:id')
                ->setParameter('testId', $testId)
                ->setParameter('id', $pkId);
        $lastTestDetails = $qb->getQuery()->getArrayResult();

        return $lastTestDetails;
    }

    /**
     * @Desc get quiz instance details
     * @param type $instanceId
     * @return type
     */
    public function getInstanceDetails($instanceId, $userId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('qut.id', 'qut.bookmark')
                ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                ->where('qut.id=:id')
                ->andWhere('qut.user=:userId')
                ->andWhere('qut.isDeleted=:isDeleted')
                ->setParameter('id', $instanceId)
                ->setParameter('userId', $userId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
        $instanceDetails = $qb->getQuery()->getArrayResult();

        return $instanceDetails;
    }

    public function checkValidInstanceItem($instanceId, $itemId, $version) {

        if (isset($version)) {
            $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version, 1);
            $itemPkId = $itemPkDetails['id'];
        } else {
            $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
            $itemPkId = $latestItemDetails['id'];
        }

        $instanceItem = self::checkInstanceItemExists($instanceId, $itemPkId);

        return $instanceItem;
    }

    /**
     * @Desc Check wether question associated with the requested instance or not.
     * @param type $instanceId
     * @param type $itemId
     * @return type
     */
    public function checkInstanceItemExists($instanceId, $itemPkId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('quti.id')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->where('quti.itemPk=:item')
                ->andWhere('quti.userTest=:userTest')
                ->setParameter('item', $itemPkId)
                ->setParameter('userTest', $instanceId);
        $instanceItem = $qb->getQuery()->getArrayResult();

        return $instanceItem;
    }

    /**
     * @Desc Get quiz instance  question details
     * @param type $instanceId
     * @param type $itemId
     */
    public function getTestInstanceItemDetails($instanceId, $itemId, $version) {

        // Get test details based on instanceId
        $testId = self::getTestBasedOnInstance($instanceId);

        // Get quiz instance quesion details.
        $itemsDetails = $this->app['items.repository']->find($itemId, true, $testId, $version);

        //Get current sequence of the item and assign it to sequence element.
        $currentSequence = self::getItemSequence($instanceId, $itemId, $version);
        $itemsDetails['sequence'] = $currentSequence;

        //get the next question to load
        $itemsDetails['nextItemId'] = self::getNextItem($instanceId, $currentSequence);

        // get the previous question to load.
        $itemsDetails['previousItemId'] = self::getPreviousItem($instanceId, $currentSequence);

        // Get previously attempted details for that instance question.
        $userAttemptDetails = self::getUserAttemptDetails($instanceId, $itemId, $version);
        $itemsDetails['userAttemptDetails'] = $userAttemptDetails;

        //Return item details
        return $itemsDetails;
    }

    /**
     * @Desc Get instance question present sequence number
     * @param type $instanceId
     * @param type $itemId
     * @return type
     */
    public function getItemSequence($instanceId, $itemId, $version = NULL) {

        if (isset($version)) {
            $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version, 1);
            $itemPkId = $itemPkDetails['id'];
        } else {
            $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
            $itemPkId = $latestItemDetails['id'];
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('quti.sequence')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->where('quti.itemPk=:item')
                ->andWhere('quti.userTest=:userTest')
                ->setParameter('item', $itemPkId)
                ->setParameter('userTest', $instanceId);
        $instanceItemSequence = $qb->getQuery()->getArrayResult();

        return $instanceItemSequence[0]['sequence'];
    }

    /**
     * @Desc Get the previous question loaded. 
     * @param type $instanceId
     * @param type $currentSequence
     * @return type
     */
    public function getPreviousItem($instanceId, $currentSequence) {
        // Get previous sequence number using currentsequence and then fetch the sequence questionid
        $previousSequence = --$currentSequence;

        $qb = $this->em->createQueryBuilder();
        $qb->select('qi.itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->where('quti.sequence=:sequence')
                ->andWhere('quti.userTest=:userTest')
                ->setParameter('sequence', $previousSequence)
                ->setParameter('userTest', $instanceId);
        $instanceItem = $qb->getQuery()->getArrayResult();
        $previousItem = $instanceItem[0];
        if (!empty($previousItem)) {
            return $previousItem;
        } else {
            return NULL;
        }
    }

    /**
     * @Desc Get the next question to load. 
     * @param type $instanceId
     * @param type $currentSequence
     * @return type
     */
    public function getNextItem($instanceId, $currentSequence) {
        // Get next sequence number using currentsequence and then fetch the sequence questionid
        $nextSequence = ++$currentSequence;

        $qb = $this->em->createQueryBuilder();
        $qb->select('qi.itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->where('quti.sequence=:sequence')
                ->andWhere('quti.userTest=:userTest')
                ->setParameter('sequence', $nextSequence)
                ->setParameter('userTest', $instanceId);
        $instanceItem = $qb->getQuery()->getArrayResult();

        $nextItem = $instanceItem[0];
        if (!empty($nextItem)) {
            return $nextItem;
        } else {
            return NULL;
        }
    }

    /**
     * @Desc : Get the quiz mode based on instanceid
     * @param type $instanceId
     * @return type
     */
    public function getTestBasedOnInstance($instanceId) {

        //Get the quiz mode based on instanceid
        $qb = $this->em->createQueryBuilder();
        $qb->select('qt.testId')
                ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                ->join('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qut.testPk')
                ->where('qut.id=:id')
                ->setParameter('id', $instanceId);
        $instanceItem = $qb->getQuery()->getArrayResult();

        return $instanceItem[0]['testId'];
    }

    /**
     * @Desc Gets user attempted details for the given isntance and itemid
     * @param type $instanceId
     * @param type $itemId
     * @return type
     */
    public function getUserAttemptDetails($instanceId, $itemId, $version = NULL) {

        if (isset($version)) {
            $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version, 1);
            $itemPkId = $itemPkDetails['id'];
        } else {
            $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
            $itemPkId = $latestItemDetails['id'];
        }

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qutip.id as progressId', 'qutir.value', 'qutip.timeSpent', 'qutip.correct')
                ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                ->join('QuizzingPlatform\Entity\QtiUserTestItems', 'quti', \Doctrine\ORM\Query\Expr\Join::WITH, 'quti.userTest=qut.id')
                ->leftJoin('QuizzingPlatform\Entity\QtiUserTestItemProgress', 'qutip', \Doctrine\ORM\Query\Expr\Join::WITH, 'qutip.userTestItem=quti.id')
                ->leftJoin('QuizzingPlatform\Entity\QtiUserTestItemResponses', 'qutir', \Doctrine\ORM\Query\Expr\Join::WITH, 'qutir.userTestItemProgress=qutip.id')
                ->where('qut.id =:id')
                ->andWhere('quti.itemPk =:item')
                ->setParameter('id', $instanceId)
                ->setParameter('item', $itemPkId);
        $attemptDetails = $qb->getQuery()->getArrayResult();

        // Get all user answered answers 
        $attemptValue = array();
        $attemptValue['userAnswer'] = array();

        // User attempted choice ids.
        if (!empty($attemptDetails)) {
            foreach ($attemptDetails as $value) {
                if (!empty($value['value']))
                    $attemptValue['userAnswer'][] = (int) $value['value'];
            }

            // Assign time spent for the question.
            $attemptValue['timeSpent'] = $attemptDetails[0]['timeSpent'];
            $attemptValue['correct'] = $attemptDetails[0]['correct'];
        }

        return $attemptValue;
    }

    /**
     * Get the test result items details in summary page
     * @param type $instanceId
     * @param type $testId
     * @param type $userId
     */
    public function getTestResultItems($instanceId, $testId, $userId, $testRequest) {
        try {

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Check if request is not null.
            if (!empty($testRequest)) {

                foreach ($testRequest as $key => $mrequest) {

                    // get values for $bankName,$description,$perPage  
                    $$key = $mrequest;
                }
            }
            // Logic found in CommonHelper class to get the offset value.
            $offset = CommonHelper::getOffset($page, $perPage);

            $qb = $this->em->createQueryBuilder();
            //Get the base quiz Details
            $qb->select('qt.title as title', 'qt.itemTimeLimit as questionTime', 'qt.timeLimit as quizTime', 'qut.totalTimeSpent as totalTimeSpent', 'IDENTITY(qut.testStatus) as testStatusId', 'qts.testStatusName as testStatus', 'qut.totalCorrect', 'qut.totalUnattempted', 'qut.totalIncorrect', 'qut.totalQuestions', 'qut.testStart as testStart', 'qut.testLastAttempted as testLastAttempted', 'qut.testCompletedDate as testCompletedDate')
                    ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                    ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.testPk=qt.id')
                    ->join('QuizzingPlatform\Entity\QtiTestStatus', 'qts', \Doctrine\ORM\Query\Expr\Join::WITH, 'qts.testStatusId=qut.testStatus')
                    ->where('qt.testId=:testId')
                    ->andWhere('qut.id=:id')
                    ->andWhere('qut.user=:user')
                    ->andWhere('qt.isDeleted=:isDeleted')
                    ->andWhere('qut.isDeleted=:isDeletedtest')
                    ->setParameter('testId', $testId)
                    ->setParameter('id', $instanceId)
                    ->setParameter('user', $userId)
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('isDeletedtest', $this->app['cache']->fetch('ACTIVE'));
            $testResult = $qb->getQuery()->getArrayResult();

            //Datetime foramt
            $testResult[0]['testLastAttempted'] = isset($testResult[0]['testLastAttempted']) ? date_format($testResult[0]['testLastAttempted'], 'Y-m-d H:i:s') : '';
            $testResult[0]['testCompletedDate'] = isset($testResult[0]['testCompletedDate']) ? date_format($testResult[0]['testCompletedDate'], 'Y-m-d H:i:s') : '';
            $testResult[0]['testStart'] = isset($testResult[0]['testStart']) ? date_format($testResult[0]['testStart'], 'Y-m-d H:i:s') : '';
            $testResult[0]['testStatusId'] = isset($testResult[0]['testStatusId']) ? ((int) $testResult[0]['testStatusId']) : '';


            $testResponse = array();
            if (!empty($testResult)) {

                //Get the test Items / Questions details
                $testItems = self::getTestInstanceItemsList($instanceId, $summary = 1, $testRequest);

                //Forming the array for each question response
                foreach ($testItems as $key => $items) {

                    //Remove unwanted details
                    unset($testItems[$key]['timeDependant'], $testItems[$key]['identifier'], $testItems[$key]['language'], $testItems[$key]['timeLimit'], $testItems[$key]['adaptive'], $testItems[$key]['toolName'], $testItems[$key]['toolVersion']);

                    //Get the userattempt details
                    $userResponse = self::getUserAttemptDetails($instanceId, $items['id'], $items['version']);
                    $testItems[$key]['userResponse'] = $userResponse['userAnswer'];
                    $testItems[$key]['questionTimeSpent'] = $userResponse['timeSpent'];
                    $testItems[$key]['isCorrect'] = $userResponse['correct'];
                }
            }

            //Forming the main array
            $testResult[0]['totalAnswered'] = $testResult[0]['totalCorrect'] + $testResult[0]['totalIncorrect'];
            $testResponse['details'] = $testResult[0];
            $testResponse['details']['itemResponse']['questionData'] = $testItems;
            return $testResponse;
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Retrieve Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the answer by choice id
     * @param type $itemId
     * @param type $choiceId
     */
    public function getAnswerBychoiceId($itemId, $choiceId) {

        $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
        $itemPkId = $latestItemDetails['id'];

        $qb = $this->em->createQueryBuilder();
        $qb->select('qisc.label')
                ->from('QuizzingPlatform\Entity\QtiItemSimpleChoice', 'qisc')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qisc.itemPk')
                ->where('qisc.itemPk=:item')
                ->andWhere('qisc.id=:id')
                ->setParameter('item', $itemPkId)
                ->setParameter('id', $choiceId);
        $choice = $qb->getQuery()->getArrayResult();
        return $choice[0];
    }

    /**
     * submit the test answer
     * @param type $instanceId
     * @param type $itemId
     * @param type $userId
     * @param type $answerRequest
     * @return boolean
     */
    public function submitTestAnswers($instanceId, $itemId, $version, $userId, $answerRequest) {

        try {

            if (isset($version)) {
                $itemPkDetails = $this->app['items.repository']->getItemPkByVersion($itemId, $version, 1);
                $itemPkId = $itemPkDetails['id'];
            } else {
                $latestItemDetails = $this->app['items.repository']->getLatestItemId($itemId);
                $itemPkId = $latestItemDetails['id'];
            }

            /*
             * Get the input request
             */
            $isSubmit = $answerRequest['submit']; // submit the quiz
            $choices = $answerRequest['userAnswer']; // answer id
            $questionTimeSpent = $answerRequest['timeSpent']; // time spent for each question

            /*
             * Get some Basic details for submit answers
             */

            //Get the ItemType based on the ItemId
            $itemType = self::getItemTypeByItemId($itemPkId);


            //Get the primary key value of qti_user_test_items table
            $testItemId = self::checkInstanceItemExists($instanceId, $itemPkId);

            if (!empty($testItemId)) {

                $testItemPkId = $testItemId[0]['id'];

                //Get the previous record of qti_user_test_item_progress table
                $existingProgressRecord = self::getPreviousRecord(NULL, $testItemPkId, NULL);

                /*
                 * Checking the User Answer 
                 */

                if (!empty($choices)) {
                    //Check whether the user answer is correct or not
                    $correctAnswer = self::checkTheCorrectAnswer($itemPkId, $choices);
                } else {
                    $correctAnswer = $this->app['config']['unAttempted'];
                }


                /*
                 * insert into qti_user_test_item_progress
                 */
                if (empty($existingProgressRecord)) {//Insert the record if its empty
                    $qtiUserTestItemProgress = new QtiUserTestItemProgress();

                    $qtiUserTestItems = $this->em->getRepository('QuizzingPlatform\Entity\QtiUserTestItems')->findOneById(array('id' => $testItemPkId));

                    $qtiUserTestItemProgress->setUserTestItem($qtiUserTestItems);
                    if (empty($choices)) {
                        $attemptCount = 0;
                    } else {
                        $attemptCount = $this->app['config']['increment'];
                    }
                    $qtiUserTestItemProgress->setAttemptCount($attemptCount);
                    $qtiUserTestItemProgress->setCorrect($correctAnswer);
                    $qtiUserTestItemProgress->setTimeSpent($questionTimeSpent);
                    $qtiUserTestItemProgress->setCreatedBy($userId);
                    $qtiUserTestItemProgress->setCreatedDate($this->dateTime);
                    $qtiUserTestItemProgress->setModifiedBy($userId);
                    $qtiUserTestItemProgress->setModifiedDate($this->dateTime);
                    $this->em->persist($qtiUserTestItemProgress);
                }
                //update the attempt count and time spent
                else {
                    $qtiUserTestItemProgress = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTestItemProgress', $existingProgressRecord[0]['id']);
                    $qtiUserTestItemProgress->setAttemptCount($existingProgressRecord[0]['attemptCount'] + $this->app['config']['increment']);
                    $qtiUserTestItemProgress->setTimeSpent($questionTimeSpent);
                    $qtiUserTestItemProgress->setCorrect($correctAnswer);
                }
                $this->em->flush();

                /*
                 * insert into qti_user_test_item_responses
                 */

                //Get the existing record of qti_user_test_item_progress table
                $existingResponseRecord = self::getPreviousRecord(NULL, NULL, $qtiUserTestItemProgress->getId());
                //Attempted question
                if (!empty($choices)) {

                    foreach ($choices as $choiceId) {
                        //Single choice Question Types (All except choice muliple) 
                        if ($itemType != $this->app['cache']->fetch('CHOICE_MULTIPLE')) {
                            //update the existing entry
                            if (!empty($existingResponseRecord)) {
                                foreach ($existingResponseRecord as $response) {
                                    $qtiUserTestItemResponses = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTestItemResponses', $response['id']);
                                    $qtiUserTestItemResponses->setValue($choiceId);
                                    $qtiUserTestItemResponses->setModifiedBy($userId);
                                    $qtiUserTestItemResponses->setModifiedDate($this->dateTime);
                                }
                            }
                            //Insert new record
                            else {
                                $qtiUserTestItemResponses = new QtiUserTestItemResponses();
                                $qtiUserTestItemResponses->setUserTestItemProgress($qtiUserTestItemProgress);
                                $qtiUserTestItemResponses->setValue($choiceId);
                                $qtiUserTestItemResponses->setCreatedBy($userId);
                                $qtiUserTestItemResponses->setCreatedDate($this->dateTime);
                                $qtiUserTestItemResponses->setModifiedBy($userId);
                                $qtiUserTestItemResponses->setModifiedDate($this->dateTime);
                                $this->em->persist($qtiUserTestItemResponses);
                            }
                        }
                        //Multiple choice Question Types (Choice muliple)
                        else {
                            if (!empty($existingResponseRecord)) {
                                //Delete the existing record
                                foreach ($existingResponseRecord as $exist) {
                                    $qb = $this->em->createQueryBuilder();
                                    $qb->delete('QuizzingPlatform\Entity\QtiUserTestItemResponses', 'qutir')
                                            ->where('qutir.id= :id')
                                            ->setParameter('id', $exist['id'])
                                            ->getQuery()->execute();
                                }
                            }
                            //Insert new record
                            $qtiUserTestItemResponses = new QtiUserTestItemResponses();
                            $qtiUserTestItemResponses->setUserTestItemProgress($qtiUserTestItemProgress);
                            $qtiUserTestItemResponses->setValue($choiceId);
                            $qtiUserTestItemResponses->setCreatedBy($userId);
                            $qtiUserTestItemResponses->setCreatedDate($this->dateTime);
                            $qtiUserTestItemResponses->setModifiedBy($userId);
                            $qtiUserTestItemResponses->setModifiedDate($this->dateTime);
                            $this->em->persist($qtiUserTestItemResponses);
                        }
                    }
                    $this->em->flush();
                }
                //Unattempted question
                else {
                    //Delete the existing record
                    foreach ($existingResponseRecord as $exist) {
                        $qb = $this->em->createQueryBuilder();
                        $qb->delete('QuizzingPlatform\Entity\QtiUserTestItemResponses', 'qutir')
                                ->where('qutir.id= :id')
                                ->setParameter('id', $exist['id'])
                                ->getQuery()->execute();
                    }
                }

                /*
                 * Get the total answer increment count
                 */
                $totalCount = self::getTotalAnswerCount($instanceId);

                //Assign the array values to variable
                $totalCorrect = isset($totalCount['correctAnswer']) ? $totalCount['correctAnswer'] : 0;
                $totalIncorrect = isset($totalCount['inCorrectAnswer']) ? $totalCount['inCorrectAnswer'] : 0;
                $totalUnattempted = isset($totalCount['unAttempted']) ? $totalCount['unAttempted'] : 0;
                $totalTimeSpent = isset($totalCount['timeSpent']) ? $totalCount['timeSpent'] : 0;


                /*
                 * update the values to qti_user_test
                 */
                $qtiUserTest = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTest', $instanceId);
                if (!empty($qtiUserTest)) {

                    if ($isSubmit) {//Final submit
                        $qtiTestStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestStatus')->findOneByTestStatusId(array('testStatusId' => $this->app['cache']->fetch('Completed')));
                        $qtiUserTest->setTestStatus($qtiTestStatus); //completed status
                        $qtiUserTest->setTestCompletedDate($this->dateTime); //completed datetime
                    }
                    $qtiUserTest->setTotalCorrect($totalCorrect); //total correct answer
                    $qtiUserTest->setTotalIncorrect($totalIncorrect); //total wrong answer
                    $qtiUserTest->setTotalUnattempted($totalUnattempted); //total unattempted answer
                    $qtiUserTest->setTotalTimeSpent($totalTimeSpent); //total time spent
                    $qtiUserTest->setBookmark($itemPkId); //bookmark question
                    $qtiUserTest->setTestLastAttempted($this->dateTime); //last attempted
                    $this->em->flush($qtiUserTest);
                }
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Test Submit Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the Previous record for test submit answers
     * @param type $instanceId
     * @param type $itemId
     * @param type $progressId
     * @return type
     */
    public function getPreviousRecord($instanceId = NULL, $testItemId = NULL, $progressId = NULL) {

        //Previous record from qti_user_test
        if ($instanceId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('qut.id', 'qut.totalTimeSpent', 'qut.totalCorrect', 'qut.totalIncorrect', 'qut.totalUnattempted')
                    ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                    ->where('qut.id=:id')
                    ->andWhere('qut.isDeleted=:isDeleted')
                    ->setParameter('id', $instanceId)
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

            $existingRecord = $qb->getQuery()->getArrayResult();
        }
        //Previous record from qti_user_test_item_progress
        elseif ($testItemId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('qutip.id', 'qutip.attemptCount', 'qutip.correct', 'qutip.timeSpent')
                    ->from('QuizzingPlatform\Entity\QtiUserTestItemProgress', 'qutip')
                    ->where('qutip.userTestItem=:userTestItem')
                    ->setParameter('userTestItem', $testItemId);

            $existingRecord = $qb->getQuery()->getArrayResult();
        }
        //Previous record from qti_user_test_item_responses
        elseif ($progressId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('qutir.id', 'qutir.value')
                    ->from('QuizzingPlatform\Entity\QtiUserTestItemResponses', 'qutir')
                    ->where('qutir.userTestItemProgress=:userTestItemProgress')
                    ->setParameter('userTestItemProgress', $progressId);

            $existingRecord = $qb->getQuery()->getArrayResult();
        }
        return $existingRecord;
    }

    /**
     * Check the correct answer
     * @param type $itemPkId
     * @param type $choices
     */
    public function checkTheCorrectAnswer($itemPkId, $choices) {

        $choiceArray = array();
        //Forming the user answer choices to array for comparing with actutal correct answer
        foreach ($choices as $key => $value) {
            $choiceArray[$key]['id'] = $value;
        }
        //Fetch the correct answer of the item
        $qb = $this->em->createQueryBuilder();
        $qb->select('qisc.id')
                ->from('QuizzingPlatform\Entity\QtiItemSimpleChoice', 'qisc')
                ->where('qisc.itemPk=:item')
                ->andWhere('qisc.correct=:correct')
                ->setParameter('item', $itemPkId)
                ->setParameter('correct', $this->app['config']['correct']);
        $answers = $qb->getQuery()->getArrayResult();

        sort($choiceArray);
        sort($answers);

        //Check the user answer and actual answer
        if ($answers == $choiceArray) {
            //If user answer is correct
            $isCorrect = $this->app['config']['correct'];
        } else {
            //user answer is wrong
            $isCorrect = $this->app['config']['incorrect'];
        }

        return $isCorrect;
    }

    /**
     * Get the total answer count
     * @return type
     */
    public function getTotalAnswerCount($instanceId) {

        $totalCount['totalAnswered'] = 0;
        $totalCount['correctAnswer'] = 0;
        $totalCount['inCorrectAnswer'] = 0;
        $totalCount['unAttempted'] = 0;
        //Fetch the total answer count based on the correct / incorrect /unattempted
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(qutip.correct) as totalCount', 'qutip.correct', 'SUM(qutip.timeSpent) as timeSpent')
                ->from('QuizzingPlatform\Entity\QtiUserTestItemProgress', 'qutip')
                ->leftJoin('QuizzingPlatform\Entity\QtiUserTestItems', 'quti', \Doctrine\ORM\Query\Expr\Join::WITH, 'quti.id=qutip.userTestItem')
                ->leftJoin('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.id=quti.userTest')
                ->where('quti.userTest=:userTest')
                ->setParameter('userTest', $instanceId)
                ->groupBy('qutip.correct');
        $totalAnswerCount = $qb->getQuery()->getArrayResult();

        //Get the total noof questions
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(quti.itemPk) as noOfQuestions')
                ->from('QuizzingPlatform\Entity\QtiUserTestItems', 'quti')
                ->join('QuizzingPlatform\Entity\QtiUserTest', 'qut', \Doctrine\ORM\Query\Expr\Join::WITH, 'qut.id=quti.userTest')
                ->where('quti.userTest=:userTest')
                ->setParameter('userTest', $instanceId);
        $totalTestQuestions = $qb->getQuery()->getArrayResult();

        //Assign the total count to array
        foreach ($totalAnswerCount as $total) {

            //Total time spent
            $totalCount['timeSpent'] += $total['timeSpent'];

            //total Correct answer
            if ($total['correct'] == $this->app['config']['correct']) {
                $totalCount['correctAnswer'] = $total['totalCount'];
            }
            //Total incorrect answer
            elseif ($total['correct'] == $this->app['config']['incorrect']) {
                $totalCount['inCorrectAnswer'] = $total['totalCount'];
            }
            //Total unAttempted answer
            $totalCount['totalAnswered'] = $totalCount['correctAnswer'] + $totalCount['inCorrectAnswer'];
            $totalCount['unAttempted'] = $totalTestQuestions[0]['noOfQuestions'] - ($totalCount['totalAnswered']);
        }
        return $totalCount;
    }

    /**
     * 
     * @param type $itemPkId
     * @return type
     */
    public function getItemTypeByItemId($itemPkId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('IDENTITY(qi.itemType) as itemType')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->where('qi.id=:id')
                ->setParameter('id', $itemPkId);

        $itemType = $qb->getQuery()->getArrayResult();
        return $itemType[0]['itemType'];
    }

    /**
     * @Desc Get first question/bookmarked question to load.
     * @param type $instanceId
     * @return type
     */
    public function getFirstQuestionToLoad($instanceId) {

        // Check if any question is bookmarked.
        $bookmarkedItem = self::getLatestBookmarkItemId($instanceId);

        // If bookmarked question is available then return bookmarked item id.
        if ($bookmarkedItem) {

            return $bookmarkedItem;
        } else {

            // Otherwise get first sequence question for the instance
            $itemDetails = self::getSequenceItemId($instanceId, 1);


            // Update instance start time and status.
            self::updateInstanceStart($instanceId);

            return $itemDetails;
        }
    }

    /**
     * @Desc Check for test instance status
     * @param type $instanceId
     * @return type
     */
    public function getInstanceStatus($instanceId) {

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qts.testStatusId')
                ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                ->leftJoin('QuizzingPlatform\Entity\QtiTestStatus', 'qts', \Doctrine\ORM\Query\Expr\Join::WITH, 'qts.testStatusId=qut.testStatus')
                ->where('qut.id = :instanceId')
                ->setParameter('instanceId', $instanceId);

        $instanceDetails = $qb->getQuery()->getArrayResult();

        $instanceStatus = $instanceDetails[0]['testStatusId'];

        return $instanceStatus;
    }

    /**
     * @Desc Get next question from bookamrked id
     * @param type $instanceId
     * @return type
     */
    public function getLatestBookmarkItemId($instanceId) {

        // Get the bookmark Id
        $qtiUserTest = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTest', $instanceId);

        $bookmarkedItem = $qtiUserTest->getBookmark();

        // Get the sequence of the the bookmarked questin Id 

        if ($bookmarkedItem) {

            $qb = $this->em->createQueryBuilder();
            $qb->select('quti.sequence')
                    ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                    ->join('QuizzingPlatform\Entity\QtiUserTestItems', 'quti', \Doctrine\ORM\Query\Expr\Join::WITH, 'quti.userTest=qut.id')
                    ->where('qut.id = :instanceId')
                    ->andWhere('quti.itemPk = :itemId') // Retrieve only active items
                    ->setParameter('instanceId', $instanceId)
                    ->setParameter('itemId', $bookmarkedItem);

            $itemDetails = $qb->getQuery()->getArrayResult();
            $bookmarkedItemSequence = $itemDetails[0]['sequence'];

            //Increment the sequence
            $nextSequence = ++$bookmarkedItemSequence;

            // get the next sequence question for the instance
            $itemDetails = self::getSequenceItemId($instanceId, $nextSequence);

            return $itemDetails;
        } else {
            return false;
        }
    }

    /**
     * @Desc Get question id for sequence sent
     * @param type $instanceId
     * @return type
     */
    public function getSequenceItemId($instanceId, $sequence) {

        // Get item id for the given instance and sequence
        $qb = $this->em->createQueryBuilder();
        $qb->select('qi.itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiUserTest', 'qut')
                ->join('QuizzingPlatform\Entity\QtiUserTestItems', 'quti', \Doctrine\ORM\Query\Expr\Join::WITH, 'quti.userTest=qut.id')
                ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=quti.itemPk')
                ->where('qut.id = :instanceId')
                ->andWhere('quti.sequence = :sequence') // Retrieve only active items
                ->setParameter('instanceId', $instanceId)
                ->setParameter('sequence', $sequence);

        $itemDetails = $qb->getQuery()->getArrayResult();
        return $itemDetails[0];
    }

    /**
     * @Desc update test instance start date and status
     * @param type $instanceId
     */
    public function updateInstanceStart($instanceId) {

        $qtiTestStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiTestStatus')->findOneByTestStatusId(array('testStatusId' => $this->app['cache']->fetch('Inprogress')));

        $qtiUserTest = $this->em->getReference('QuizzingPlatform\Entity\QtiUserTest', $instanceId);
        $qtiUserTest->setTestStatus($qtiTestStatus); //start test instance status
        $qtiUserTest->setTestStart($this->dateTime); //test instance start datetime
        $this->em->flush();

        return true;
    }

    /*
     * By srilakshmi R
     * to get test engine url from passed instance and test id
     */

    public function generateLaunchURL($testId, $instanceId, $version) {
        return $this->app['cache']->fetch('host') . "enduser/engine/" . $testId . "/" . $version . "/" . $instanceId; // . "?token=" . $accessToken;
    }

    /**
     * Get all the versions of the testid
     * @param type $testId
     * @return type
     */
    public function getAlltestVersions($testId) {
        // Fetch all versions of the table for the given Identifier.
        $qb = $this->em->createQueryBuilder();
        $qb->select('qt.id as id', 'qt.version')
                ->from('QuizzingPlatform\Entity\QtiTest', 'qt')
                ->where('qt.testId = :testId')
                ->andWhere('qt.isDeleted = :isDeleted')
                ->setParameter('testId', $testId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->orderBy('qt.version', 'DESC');
        $testVersions = $qb->getQuery()->getArrayResult();

        return $testVersions;
    }

}
