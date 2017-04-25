<?php

/*
 * TestsService - Tests/Quiz module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Tests;

use Silex\Application;

class TestsService {

    protected $app;
    protected $em;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->em = $app['orm.em'];
    }

    /**
     * Create the test/Quiz
     * @param type $testsData
     * @return type
     */
    public function createTest($testsData, $requestFrom = NULL, $userId, $clientId) {

        if (empty($requestFrom)) {

            $validateResponse = self::validateField($testsData);
        } else {
            $validateResponse = true;
        }
        //validation is success
        if ($validateResponse === true) {
            //Call the create function in repository
            $response = $this->app['tests.repository']->create($testsData, $requestFrom, $userId, $clientId);
            return $response;
        } else {

            return $validateResponse;
        }
    }

    /**
     * Get the test details by testid
     * @param type $testId
     * @return type
     */
    public function getTestById($testId, $requestFrom = NULL, $userId = NULL, $version = NULL) {

        //Call the find method in repository
        $response = $this->app['tests.repository']->find($testId, $requestFrom, $userId, $version);
        return $response;
    }

    /**
     * Delete the test details
     * @param type $testId
     * @return type
     */
    public function deleteTest($testId, $userId = '', $requestFrom = '', $isDeleteAll = '', $version = '') {

        $response = $this->app['tests.repository']->delete($testId, $userId, $requestFrom, $isDeleteAll, $version);
        return $response;
    }

    /**
     * Delete the test instance details
     * @param type $instanceId
     * @return type
     */
    public function deleteTestInstance($instanceId, $userId = '', $requestFrom = '') {
        //Call the delete method in repository
        $response = $this->app['tests.repository']->deleteInstance($instanceId, $userId, $requestFrom);
        return $response;
    }

    /**
     * Listing all the test details
     * @param type $testRequest
     * @param type $metadataRequest
     * @return type
     */
    public function getTest($testRequest, $metadataRequest, $requestFrom = '', $userId = '') {

        //Call the delete method in repository
        $response = $this->app['tests.repository']->getTestList($testRequest, $metadataRequest, $requestFrom, $userId);
        return $response;
    }

    /**
     * Edit the test details
     * @param type $testData
     * @param type $testId
     * @return type
     */
    public function updateTest($testData, $testId, $accessToken, $requestFrom = '', $userId) {

        if (empty($requestFrom)) {
            $validateResponse = self::validateField($testData);
            // $validateResponse = true;
        } else {
            $validateResponse = true;
        }

        //validation is success
        if ($validateResponse === true) {
            //Call the delete method in repository
            $response = $this->app['tests.repository']->update($testData, $testId, $accessToken, $requestFrom, $userId);
            return $response;
        } else {

            return $validateResponse;
        }
    }

    /**
     * Validate the mandatory field
     * @param type $testsData
     * @return boolean|string
     */
    public function validateField($testsData) {

        $title = trim($testsData['title']); //Quiz Name 
        $noOfQuestions = $testsData['noofQuestions'];
        $metadataValue = $testsData['metadataAssoc'];
        $questionTime = $testsData['questionTime'];
        $metadataId = $testsData['id'];
        $clientUserId = trim($testsData['clientUserId']);
        /*
         * 1.Quiz name is mandatory and cannot be blank
         * 2.taxonomy cannot be blank
         * 3.noofoquestions cannot be blank and should not less than 0 and greater than available questions
         * 4.time is not mandatory . If entered seconds is in integer and greater than o
         */

        if (empty($title) || empty($noOfQuestions) || empty($metadataValue)) {

            //All the mandatory fields are empty
            if (empty($title) && empty($noOfQuestions) && empty($metadataValue)) {
                $msg = 'Quiz title , No of Question and Taxonomy  should not be empty';
            }
            //noofquestion and taxonomy is empty
            elseif (!empty($title) && empty($noOfQuestions) && empty($metadataValue)) {
                $msg = 'No of Question and Taxonomy should not be empty';
            }
            //taxonmy is empty
            elseif (!empty($title) && !empty($noOfQuestions) && empty($metadataValue)) {
                $msg = ' Taxonomy should not be empty';
            }
            //title is empty
            elseif (empty($title) && !empty($noOfQuestions) && !empty($metadataValue)) {
                $msg = 'Quiz title should not be empty';
            }
            //no of question is empty
            elseif (!empty($title) && empty($noOfQuestions) && !empty($metadataValue)) {
                $msg = ' No of Question should not be empty';
            }
            //title and noofquestion is empty
            elseif (empty($title) && empty($noOfQuestions) && !empty($metadataValue)) {
                $msg = 'Quiz title and No of Question should not be empty';
            }
            //title and taxonomy is empty
            elseif (empty($title) && !empty($noOfQuestions) && empty($metadataValue)) {
                $msg = 'Quiz title and Taxonomy  should not be empty';
            }
            return $msg;
        } elseif (!empty($noOfQuestions) || strlen($title) > 250 || empty($metadataId) || empty($clientUserId)) {

            //No of questions cannot be less than 0
            if ($noOfQuestions <= 0 || !is_int($noOfQuestions)) {
                $msg = "No of Questions must be a number between 1 and (maxnumber of questions)";
                return $msg;
            }
            //question time should be numeric
            elseif (!is_int($questionTime)) {
                $msg = 'Time must be an number';
                return $msg;
            }
            //question time should not be less than 0
            elseif ($questionTime <= 0) {
                $msg = 'Entry must be greater than 0';
                return $msg;
            } elseif (strlen($title) > 250) {
                $msg = 'Quiz title should be maximum of 250 characters';
                return $msg;
            } elseif (empty($metadataId)) {
                $msg = "MetadataId should not be empty";
                return $msg;
            } elseif (empty($clientUserId)) {
                $msg = "Client user id should not be empty";
                return $msg;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the test progress of each user id
     * @param $testId
     * @return mixed
     */
    public function getTestProgress($testId, $userId, $accessToken, $summary = NULL, $instanceId = NULL) {

        $response = $this->app['tests.repository']->getTestProgress($testId, $userId, $accessToken, $summary, $instanceId);
        return $response;
    }

    /**
     * Get the test details by topicId
     * @param type $topicId
     * @return type
     */
    public function getTestTaxonomyById($topicId, $userId) {

        //Call the find method in repository
        $testId = $this->app['tests.repository']->findTaxonomyExist($topicId);

        $response['topicsCovered'] = $topicId;
        $testProgressFlag = 1;
        $response['quizInstance'] = self::getTestProgress($testId, $userId, $testProgressFlag);
        return $response;
    }

    /**
     * Get progress bar
     * @param type $topicId
     * @param type $userId
     * @return type
     */
    public function getTestProgressBar($topicId, $userId, $metadataId, $clientId) {


        //Get the metadataId based on the random client metadata id
        $clientMetadataId = $this->app['metadata.repository']->getclientMetadataId($clientId, $metadataId);

        //Get the testId for the particular metadatavalue id
        $testId = $this->app['tests.repository']->findTaxonomyExist($topicId, $clientMetadataId);

        //Get the taxonomy name
        $metadataValueName = $this->app['metadata.repository']->getTaxonomyName($topicId, $clientMetadataId);

        $topics = explode(',', $topicId);

        //Get the sublevel of topics
        $subLevelIds = $this->app['metadata.repository']->getMetadataSubLevelIds($topics);

        //Get the Item count of taxonomy
        $itemCount = $this->app['tests.repository']->getItemCountByTaxonomyId($subLevelIds, $clientMetadataId);

        //Forming the array
        $response['id'] = ($topicId) ? ((int) $topicId) : null;
        $response['name'] = $metadataValueName[0]['value'];
        $response['numberOfMetadataQuestions'] = ($itemCount[0]['itemCount']) ? ((int) $itemCount[0]['itemCount']) : null;
        $metadataValueProgress = $this->app['tests.repository']->getTestProgressBar($testId, $userId);
        if (!empty($metadataValueProgress)) {
            $response['testId'] = $metadataValueProgress[0]['testId'];
            $response['instanceId'] = $metadataValueProgress[0]['userTestId'];

            $response['testProgress'] = array();

            $response['testProgress']['totalTestQuestions'] = $metadataValueProgress[0]['totalQuestions'];
            $response['testProgress']['totalCorrectAnswers'] = $metadataValueProgress[0]['totalCorrect'];
            $response['testProgress']['totaWrongAnswers'] = $metadataValueProgress[0]['totalIncorrect'];
            $response['testProgress']['totalUnAttempted'] = $metadataValueProgress[0]['totalUnattempted'];
        }
        return $response;
    }

    /**
     * @Desc : Create the test instance and assign to user and get list of questions and store them to the user_test instance.
     * @param type $testsData
     * @return type
     */
    public function createTestInstance($testId, $userId, $clientId) {

        // If its not a WK admin then get the questions list based on the configurations. 
        if ($clientId != $this->app['cache']->fetch('WK Admin')) {

            //First check if any questions availbale for the selected constraint then only create the quiz instance. 
            // Call the function to get the lists of questions to create a quiz.
            $testItemsFinalList = self::getSetofItemsForQuiz($testId, $userId);
        }


        //If we have questions for the selected metadata then only create instance.
        if (!empty($testItemsFinalList)) {

            $totalQuestions = count($testItemsFinalList);

            // Get the latest version details of quiz
            $latestTestData = $this->app['tests.repository']->getLatestTestId($testId);

            $testPkId = $latestTestData['id'];

            $version = $latestTestData['version'];

            //Create test instance for the user.
            $userTestInstanceId = $this->app['tests.repository']->createTestInstanceForUser($testPkId, $userId, $totalQuestions);

            //Store questions list for the quiz.
            $createdTestItems = $this->app['tests.repository']->storeTestItemsDetails($testItemsFinalList, $userTestInstanceId, $userId);

            if ($userTestInstanceId) {

                // Form the quiz launch URL
                $testURL = $this->app['tests.repository']->generateLaunchURL($testId, $userTestInstanceId, $version);

                // Return URL.
                return $testURL;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @Desc Get set of questions for the given taxonomy for the passed parameters
     * @param type $metadataValue
     * @param type $noOfQuestions
     * @param type $userId
     * @param type $userTestId
     * @param type $newQuestions
     * @param type $gotWrong
     * @return type
     */
    public function getSetofItemsForQuiz($testId, $userId) {

        // Get the quiz details based on the quiz id.
        $testDetails = $this->app['tests.repository']->find($testId, '', $userId);

        //Assign quiz configurations to variables
        $noOfQuestions = $testDetails['noOfQuestions'];
        $newQuestions = $testDetails['newQuestions'] ? $testDetails['newQuestions'] : false;
        $gotWrong = $testDetails['gotWrong'] ? $testDetails['gotWrong'] : false;
        $metadataValueAssoc = $testDetails['metadataAssoc'];
        $metadataValues = explode(',', $metadataValueAssoc);
        $randomizeQuestion = $testDetails['randomizeQuestion'] ? $testDetails['randomizeQuestion'] : false;

        //Get all the taxonomy sub levels 
        $metadataValueIds = $this->app['metadata.repository']->getMetadataSubLevelIds($metadataValues);

        // If both get new questions and got wrong flags are set, first will get all the got wrong questions and then will add new questions for the difference number of questions.
        if (($newQuestions && $gotWrong) || ($gotWrong)) {

            $gotWrongItemsList = $newQuestionsItemsList = array();

            // First get the wronganswered questions list from the quiz
            $gotWrongItemsList = $this->app['tests.repository']->getGotWrongQuestionsForMetadataValue($metadataValueIds, $noOfQuestions, $userId, $randomizeQuestion);

            // Get the count of wrong answered questions.
            $itemsCount = count($gotWrongItemsList);

            // Check if gotwrong answer count is greater than or equal to user requested questions count, return only got wrong answers list.
            if ($itemsCount >= $noOfQuestions) {
                $itemsListFromMetadata = $gotWrongItemsList;
            } else {

                //Get the difference count 
                $diffrenceCount = $noOfQuestions - $itemsCount;

                //For the difference count get the new questions list.
                $newQuestionsItemsList = $this->app['tests.repository']->getNewQuestionsForMetadataValue($metadataValueIds, $diffrenceCount, $userId, $randomizeQuestion);

                if (!empty($gotWrongItemsList) && !empty($newQuestionsItemsList))
                // Then merge both wrong answered items and new items to final list array.
                    $itemsListFromMetadata = array_unique(array_merge($gotWrongItemsList, $newQuestionsItemsList));
                else if (!empty($gotWrongItemsList))
                    $itemsListFromMetadata = $gotWrongItemsList;
                else if (!empty($newQuestionsItemsList))
                    $itemsListFromMetadata = $newQuestionsItemsList;
            }
        } elseif ($newQuestions) {

            //Get the questions based on the newquestions flag
            $itemsListFromMetadata = $this->app['tests.repository']->getNewQuestionsForMetadataValue($metadataValueIds, $noOfQuestions, $userId, $randomizeQuestion);
        } else {

            //Get the set of questions for the given taxonomy 
            $itemsLists = $this->app['tests.repository']->getQuestionsForMetadataValue($metadataValueIds, $noOfQuestions, '', $randomizeQuestion);

            if (!empty($itemsLists)) {

                $itemsListFromMetadata = array_unique($itemsLists);

                //$itemsListFromMetadata = $this->app['items.repository']->getItemIdForPkIds($newItemsUnique);
            }
        }

        return $itemsListFromMetadata;
    }

    /**
     * Get the test instance details by $instanceId
     * @param type $testId
     * @return type
     */
    public function getTestInstanceById($instanceId, $requestFrom = '', $userId = '') {

        //Call the find method in repository
        $response = $this->app['tests.repository']->findInstance($instanceId, $requestFrom, $userId);
        return $response;
    }

    /**
     * @Desc Get list of questions for the given quiz instance
     * @param type $instanceId
     * @return type
     */
    public function checkValidInstance($instanceId, $userId) {

        //Get list of questions for the given quiz instance
        $instanceDetails = $this->app['tests.repository']->getInstanceDetails($instanceId, $userId);

        if (!empty($instanceDetails)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Desc : Check wether question is associated with the requested instance or not.
     * @param type $instanceId
     * @param type $itemId
     * @return boolean
     */
    public function checkValidInstanceItem($instanceId, $itemId, $version) {

        //Get list of questions for the given quiz instance
        $itemExists = $this->app['tests.repository']->checkValidInstanceItem($instanceId, $itemId, $version);

        if ($itemExists) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Desc Get quiz instance question details
     * @param type $instanceId
     * @param type $itemId
     * @return boolean
     */
    public function getInstanceItemDetails($instanceId, $itemId = NULL, $version = NULL) {

        $instanceStatus = $this->app['tests.repository']->getInstanceStatus($instanceId);

        if ($instanceStatus == $this->app['cache']->fetch('Completed')) {

            return $this->app['cache']->fetch('Completed');
        } else {

            // If item_id is null, then get the first question details/bookmarked item details if any bookmark.
            if ($itemId == NULL) {

                // Get first question to load.
                $itemDetails = $this->app['tests.repository']->getFirstQuestionToLoad($instanceId);
                $itemId = $itemDetails['itemId'];
                $version = $itemDetails['version'];
            }

            //Get questions details of the given quiz instance
            $itemDetails = $this->app['tests.repository']->getTestInstanceItemDetails($instanceId, $itemId, $version);

            //Return item details
            if (!empty($itemDetails)) {
                return $itemDetails;
            } else {
                return false;
            }
        }
    }

    /**
     * Get the test result items details in summary page
     * @param type $instanceId
     * @param type $testId
     * @param type $userId
     * @return type
     */
    public function getTestResultItems($instanceId, $testId, $userId, $testRequest) {

        //Call the getTestResultItems method in repository
        $response = $this->app['tests.repository']->getTestResultItems($instanceId, $testId, $userId, $testRequest);
        return $response;
    }

    /**
     * 
     * @param type $instanceId
     * @param type $itemId
     * @param type $userId
     * @param type $answerRequest
     * @return type
     */
    public function submitTestAnswers($instanceId, $itemId, $version, $userId, $answerRequest) {

        //Call the submitTestAnswers method in repository
        $response = $this->app['tests.repository']->submitTestAnswers($instanceId, $itemId, $version, $userId, $answerRequest);
        return $response;
    }

}
