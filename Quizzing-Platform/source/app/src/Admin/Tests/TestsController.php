<?php

/**
 * TestsController - Handles Tests/Quiz module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Tests;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class TestsController {

    protected $app;

    /*
     * *** Custom Error Codes: ***
     * 4001 :Error Creating Quiz.
     * 4002 :Invalid input request for Quiz create.
     * 4003 :Invalid input request.
     * 4004 :Invalid input request for Quiz progress.
     * 4005 :Invalid input request for Quiz instance delete.
     * 4006 :Invalid input request for Quiz delete.
     * 4007 :Quiz create request is empty.
     * 4008 :Duplicate Quiz Name.
     * 4009 :Quiz Not Found.
     * 4010 :Quiz instance not found.
     * 4011 :Test Not Found.
     * 4012 :Error retrieving test Details.
     * 4013 :Error retrieving test progress.
     * 4014 :Error Deleting the Quiz.
     * 4015 :Error while updating Quiz.
     * 4016 :Error creating quiz instance.
     * 4017 :Error retrieving Questions for Quiz.
     * 4018 :Error Submitting for Quiz.
     * 4019 :Error retreiving Quiz instance question details.
     * 4020 :Question is not associated with the requested Quiz instance.
     * 4021 :Not a valid quiz instance for the user.
     * 4022 :Test instance completed.
     * 1010 :Don't have permission. Please contact WK admin
     */

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "tests";

        // HTTP Codes
        $this->notFound = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->created = $this->app['cache']->fetch('HTTP_CREATED');
        $this->duplicate = $this->app['cache']->fetch('HTTP_DUPLICATE');
        $this->forbidden = $this->app['cache']->fetch('HTTP_FORBIDDEN');
        $this->badrequest = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->nocontent = $this->app['cache']->fetch('HTTP_NOCONTENT');
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function createTest(Request $request) {

        if (!empty($request->getContent())) {

            $testsData = json_decode($request->getContent(), true);

            //Create the quiz instance based on the base quiz id
            $baseQuizId = $request->get('id');

            // Store the request data in to logs. 
            $testsData['info'] = "Request Data : ";
            $this->app['log']->writeLog($testsData);
            $accessToken = $request->headers->get('Authorization');

            if (!empty($baseQuizId)) {
                $testsData['baseQuizId'] = $baseQuizId;
            }

            // Get clientId from access token
            $clientId = $this->app['login.service']->getClientId($accessToken);

            // Check userId is passed for admin
            if ($testsData['userId']) {
                $userId = $testsData['userId'];
            } elseif ($testsData['clientUserId']) {
                // Check if cleintUsrId passed fro end user
                $clientUserId = $testsData['clientUserId'];
                // Get QP userId for the requested clientUserId
                if (!empty($clientUserId))
                    $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
            }

            //based on the requestfrom header , differentiate the admin or EU . For EU requestfrom header is empty
            $requestFrom = $request->headers->get('requestFrom');

            if ($userId) {

                if (empty($requestFrom)) {
                    $hasPermission = $this->app['cache']->fetch('endUserPermission');
                } else {
                    // Check user has permission to create Tests/Quiz.
                    $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');
                }
                if ($hasPermission) {

                    //Create the tests/Quiz based on the Request
                    $createdId = $this->app['tests.service']->createTest($testsData, $requestFrom, $userId, $clientId);

                    if (is_int($createdId)) {

                        //After successful insertion , return newly created quiz ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif (is_array($createdId)) {

                        //After successful insertion , return newly created quiz ID and details for only enduser api.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If test Association exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_QUIZ_ERROR']);
                        return $response;
                    } elseif (is_string($createdId) && $createdId != $this->app['cache']->fetch('exists')) {

                        // If test Association exists then return duplicate  customized error.
                        $logArrValues = array('Code' => 4008, 'Title' => $createdId, 'Description' => $createdId);
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $logArrValues);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['CREATE_QUIZ_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create tests/Quiz then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_QUIZ_REQUEST_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['EMPTY_QUIZ_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * Get Test details by id
     * @param Request $request
     * @return type
     */
    public function getTestById(Request $request) {

        $testId = $request->get('id');

        //$testId is Mandatory
        if ($testId != "") {

            $getTestData = $request->query->all();
            $requestFrom = $request->headers->get('requestFrom');
            $accessToken = $request->headers->get('Authorization');
            $version = $getTestData['version'];
            // Get clientId from access token
            $clientId = $this->app['login.service']->getClientId($accessToken);

            // Check userId is passed for admin
            if ($getTestData['userId']) {
                $userId = $getTestData['userId'];
            } elseif ($getTestData['clientUserId']) {
                // Check if cleintUsrId passed fro end user
                $clientUserId = $getTestData['clientUserId'];
                // Get QP userId for the requested clientUserId
                $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
            }

            if ($userId) {
                if (!empty($requestFrom)) {
                    // Check user has permission to view the test details.
                    $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');
                } else {
                    $hasPermission = $this->app['cache']->fetch('endUserPermission');
                }

                if ($hasPermission) {

                    $testData = $this->app['tests.service']->getTestById($testId, $requestFrom, $userId, $version);
                    //PRINT_R($testData);die;
                    if (!empty($testData)) {

                        // Return Test details.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($testData, $this->success);
                        return $response;
                    } else {

                        // Return following error if Test details doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to view test then return following error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If user doesn't have permission to create tests/Quiz then return permission error. 
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * Delete the test / Quiz Details
     * @param Request $request
     * @return type
     */
    public function deleteTest(Request $request) {

        //Get the query parameter
        $deleteTest = $request->query->all();

        //Get the requestfrom parameter from header
        $requestFrom = $request->headers->get('requestFrom');
        //Get the access token from header
        $accessToken = $request->headers->get('Authorization');

        //Get the testId from url
        $testId = $request->get('id');

        // Get clientId from access token
        $clientId = $this->app['login.service']->getClientId($accessToken);

        // Check userId is passed for admin
        if ($deleteTest['userId']) {
            $userId = $deleteTest['userId'];
        } elseif ($deleteTest['clientUserId']) {
            // Check if cleintUsrId passed fro end user
            $clientUserId = $deleteTest['clientUserId'];
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }
        //Get Version
        $version = $deleteTest['version'];
        //Get is DeleteAll
        $isDeleteAll = $deleteTest['isDeleteAll'];
        //Get the instanceId
        $instanceId = $request->get('instanceId'); //$testId
        if (!empty($instanceId)) {
            $testId = $instanceId;
        }

        if ($testId != "") {

            if ($userId) {

                //based on the requestfrom header , differentiate the admin or EU . For EU requestfrom header is empty
                if (empty($requestFrom)) {
                    $hasPermission = $this->app['cache']->fetch('endUserPermission');
                } else {
                    // Check user has permission to delete test.
                    $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'delete');
                }
                if ($hasPermission) {

                    //check the test exists
                    $testData = $this->app['tests.service']->getTestById($testId);

                    if (!empty($testData)) {
                        // soft delete the test
                        $response = $this->app['tests.service']->deleteTest($testId, $userId, $requestFrom, $isDeleteAll, $version);

                        //If the response is True , Deleted Successfully
                        if ($response === true) {

                            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                            return $response;
                        } elseif (is_string($response)) {

                            // If test Association exists then return duplicate  customized error.
                            $logArrayData = array('Code' => 4008, 'Title' => $response, 'Description' => $response);
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $logArrayData);
                            return $response;
                        } else {

                            // Return following error if any error occurs while deleting the test.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['DELETE_QUIZ_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if item doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if user doen't have permission to delete test.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // Return following error if user doen't have permission to delete test.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_QUIZ_DELETE_REQUEST_ERROR']);
            return $response;
        }
    }

    public function deleteTestInstance(Request $request) {

        //Get the query parameter
        $deleteTest = $request->query->all();

        //Get the requestfrom parameter from header
        $requestFrom = $request->headers->get('requestFrom');

        //Get the access token from header
        $accessToken = $request->headers->get('Authorization');

        //Get the testId from url
        $testId = $request->get('id');

        // Get clientId from access token
        $clientId = $this->app['login.service']->getClientId($accessToken);

        // Check userId is passed for admin
        if ($deleteTest['userId']) {
            $userId = $deleteTest['userId'];
        } elseif ($deleteTest['clientUserId']) {
            // Check if cleintUsrId passed fro end user
            $clientUserId = $deleteTest['clientUserId'];
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }

        //Get the instanceId
        $instanceId = $request->get('instanceId'); //$testId


        if ($instanceId != "") {

            if ($userId) {

                //based on the requestfrom header , differentiate the admin or EU . For EU requestfrom header is empty
                if (empty($requestFrom)) {
                    $hasPermission = $this->app['cache']->fetch('endUserPermission');
                } else {
                    // Check user has permission to delete test.
                    $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'delete');
                }
                if ($hasPermission) {

                    //check the test exists
                    $testData = $this->app['tests.service']->getTestInstanceById($instanceId, $requestFrom, $userId);

                    if (!empty($testData)) {
                        // soft delete the test
                        $response = $this->app['tests.service']->deleteTestInstance($instanceId, $userId, $requestFrom);

                        //If the response is True , Deleted Successfully
                        if ($response === true) {

                            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                            return $response;
                        } elseif (is_string($response)) {

                            // If test Association exists then return duplicate  customized error.
                            $logArray = array('Code' => 4008, 'Title' => $response, 'Description' => $response);
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $logArray);
                            return $response;
                        } else {

                            // Return following error if any error occurs while deleting the test.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['DELETE_QUIZ_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if item doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_INSTANCE_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if user doen't have permission to delete test.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // Return following error if user doen't have permission to delete test.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_QUIZ_INSTANCE_DELETE_ERROR']);
            return $response;
        }
    }

    /**
     * Get All test Details
     * @param Request $request
     * @return type
     */
    public function getTest(Request $request) {

        $testRequest = $request->query->all();

        // Metadata filters will be json encoded requests       
        $metadataRequest = json_decode($testRequest['metadataAssoc'], true);

        //based on the requestfrom header , differentiate the admin or EU . For EU requestfrom header is empty
        $requestFrom = $request->headers->get('requestFrom');

        //Get the access token from header
        $accessToken = $request->headers->get('Authorization');

        //Get ClientId
        $clientId = $this->app['login.service']->getClientId($accessToken);
        $testRequest['clientId'] = $clientId;
        // Check userId is passed for admin
        if ($testRequest['userId']) {

            $userId = $testRequest['userId'];
        } elseif ($testRequest['clientUserId']) {

            // Check if cleintUsrId passed fro end user
            $clientUserId = $testRequest['clientUserId'];
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }

        if ($userId) {
            if (empty($requestFrom)) {
                $hasPermission = $this->app['cache']->fetch('endUserPermission');
            } else {
                // Check user has permission to view item collection details.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');
            }
            if ($hasPermission) {

                $itemCollectionValueArray = $this->app['tests.service']->getTest($testRequest, $metadataRequest, $requestFrom, $userId);

                if ($itemCollectionValueArray == FALSE) {
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_RETRIEVING_ERROR']);
                    return $response;
                } else {
                    // Return Test list.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemCollectionValueArray, $this->success);
                    return $response;
                }
            } else {

                // Return following error if your doesn't have permission to view the Test.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {
            // Return following error if user doen't have permission to delete test.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Edit the test / Quiz details
     * @param Request $request
     * @return type
     */
    public function updateTest(Request $request) {

        $testId = $request->get('id');

        if ($testId != "") {

            $testData = json_decode($request->getContent(), true);
            $testData['info'] = "Update request Data : ";
            $this->app['log']->writeLog($testData);
            $accessToken = $request->headers->get('Authorization');

            //based on the requestfrom header , differentiate the admin or EU . For EU requestfrom header is empty
            $requestFrom = $request->headers->get('requestFrom');

            // Get clientId from access token       
            $clientId = $this->app['login.service']->getClientId($accessToken);

            // Check userId is passed for admin
            if ($testData['userId']) {
                $userId = $testData['userId'];
            } elseif ($testData['clientUserId']) {
                // Check if cleintUsrId passed fro end user
                $clientUserId = $testData['clientUserId'];
                // Get QP userId for the requested clientUserId
                if (!empty($clientUserId))
                    $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
            }

            if ($userId) {

                if (empty($requestFrom)) {
                    $hasPermission = $this->app['cache']->fetch('endUserPermission');
                } else {
                    // Check user has permission to edit the Tests
                    $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'edit');
                }
                if ($hasPermission) {

                    //check Tests exists
                    $testValue = $this->app['tests.service']->getTestById($testId);

                    if (!empty($testValue)) {

                        // If Tests exists then update it.
                        $response = $this->app['tests.service']->updateTest($testData, $testId, $accessToken, $requestFrom, $userId);

                        if (is_array($response)) {

                            //After successful insertion , return newly created question ID.
                            $response = $this->app['systemsettings.controller']->returnSuccessResponse($response, $this->success);
                            return $response;
                        } elseif ($response == $this->app['cache']->fetch('exists')) {

                            // If Test Association/Test name exists then return duplicate  customized error.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_QUIZ_ERROR']);
                            return $response;
                        } elseif (is_string($response) && $response != $this->app['cache']->fetch('exists')) {

                            // If test Association exists then return duplicate  customized error.
                            $logArray = array('Code' => 4008, 'Title' => $response, 'Description' => $response);
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $logArray);
                            return $response;
                        } elseif (is_int($response)) {

                            // Return Succes response while updating the Test in Admin.
                            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                            return $response;
                        } else {

                            // Return following error if any error occurs while updating the Test.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['UPDATE_QUIZ_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if Test doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to update Tests then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_QUIZ_REQUEST_ERROR']);
                return $response;
            }
        }
    }

    /**
     * Get the test progress of each test id
     * @param Request $request
     * @return mixed
     */
    public function getTestProgress(Request $request) {

        //Get the test id
        $testId = $request->get('id');
        $instanceId = $request->get('instanceId');
        $summary = $request->get('summary');

        //Check the test id exist or not
        $testData = $this->app['tests.service']->getTestById($testId);
        $accessToken = $request->headers->get('Authorization');

        // Get clientId from access token       
        $clientId = $this->app['login.service']->getClientId($accessToken);

        //client user id
        $clientUserId = $request->get('clientUserId');
        $userId = $request->get('userId');

        if ($clientUserId) {
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }


        if ($userId) {

            if (!empty($testData)) {

                $testProgressArray = $this->app['tests.service']->getTestProgress($testId, $userId, $accessToken, $summary, $instanceId);

                // Return Test list.
                if (is_array($testProgressArray)) {
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($testProgressArray, $this->success);
                    return $response;
                } elseif (is_string($testProgressArray)) {

                    // If test Association exists then return duplicate  customized error.
                    $logArray = array('Code' => 4008, 'Title' => $testProgressArray, 'Description' => $testProgressArray);
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $logArray);
                    return $response;
                } else {

                    // Return following error if any error occurs while updating the Test.
                    // $response = $this->app['systemsettings.controller']->returnErrorResponse(4005, "Quiz Not Found", "Quiz not found  for the requested ID", $this->notFound);
                    if (empty($instanceId)) {
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_INSTANCES_NOT_FOUND_ERROR']);
                    } else {
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_INSTANCE_NOT_FOUND_ERROR']);
                    }
                    return $response;
                }
            } else {

                // Return following error if Test details doesn't exists.
//                if (empty($instanceId)) {
//                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_NOT_FOUND_ERROR']);
//                } else {
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_NOT_FOUND_ERROR']);
                //}
                return $response;
            }
        } else {

            // If user doesn't have permission to update Tests then return permission error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Get the test details based on subject/topic/subtopic id == topic id(consider topic id for all three)
     * @param Request $request
     * @return mixed
     */
    public function getTestInstance(Request $request) {

        //Get the topic id
        $topicId = $request->get('id');

        $accessToken = $request->headers->get('Authorization');

        // Get clientId from access token       
        $clientId = $this->app['login.service']->getClientId($accessToken);

        //client user id
        $clientUserId = $request->get('clientUserId');
        $userId = $request->get('userId');

        if ($clientUserId) {
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }

        if ($userId) {
            //Check the test id exist or not
            $testData = $this->app['tests.service']->getTestTaxonomyById($topicId, $userId);

            if (!empty($testData)) {

                // Return Test instance details based on topic id.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($testData, $this->success);
                return $response;
            } else {

                // Return following error if Test details doesn't exists.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['TEST_NOT_FOUND_ERROR']);
                return $response;
            }
        } else {

            // If user doesn't have permission to update Tests then return permission error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Get the progress bar
     * @param Request $request
     * @return type
     */
    public function getTestProgressBar(Request $request) {

        //Get all the query parameter
        $progressData = $request->query->all();

        //get the randomized metadataId
        $metadataId = $request->get('id');

        //Get the topic /subject / subtopic id
        $topicId = $progressData['metadataValueId'];

        //Get the accestoken form header
        $accessToken = $request->headers->get('Authorization');

        //get the requestfrom parameter from header
        $requestFrom = $request->headers->get('requestFrom');


        // Get clientId from access token
        $clientId = $this->app['login.service']->getClientId($accessToken);

        // Check userId is passed for admin
        if ($progressData['userId']) {
            $userId = $progressData['userId'];
        } elseif ($progressData['clientUserId']) {
            // Check if cleintUsrId passed fro end user
            $clientUserId = $progressData['clientUserId'];
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }

        if ($userId) {

            //metadataValue id is mandatory
            if ($topicId) {
                //Check the test id exist or not
                $testData = $this->app['tests.service']->getTestProgressBar($topicId, $userId, $metadataId, $clientId);

                if (!empty($testData)) {

                    // Return Test instance details based on topic id.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($testData, $this->success);
                    return $response;
                } else {

                    // Return following error if Test details doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['TEST_PROGRESS_ERROR']);
                    return $response;
                }
            } else {
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_REQUEST_QUIZ_PROGRESS_ERROR']);
                return $response;
            }
        } else {

            // If user doesn't have permission to update Tests then return permission error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc This function will create the test instance and assign it to the user.
     * @param Request $request
     * @return type
     */
    public function createTestInstance(Request $request) {

        //Create the quiz instance based on the base quiz id
        $testId = $request->get('id');

        $accessToken = $request->headers->get('Authorization');

        // Get clientId from access token
        $clientId = $this->app['login.service']->getClientId($accessToken);

        //client user id
        $clientUserId = $request->get('clientUserId');
        $userId = $request->get('userId');

        if ($clientUserId) {
            // Get QP userId for the requested clientUserId
            $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        }

        if ($testId) {

            if ($userId) {

                $testData = $this->app['tests.service']->getTestById($testId);
                if (!empty($testData)) {

                    //based on the requestfrom header , differentiate the admin or EU . For EU requestfrom header is empty
                    $requestFrom = $request->headers->get('requestFrom');

                    //assign quiz to user
                    $testURL = $this->app['tests.service']->createTestInstance($testId, $userId, $clientId);

                    if ($testURL) {

                        //After successful insertion , return testURL
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($testURL, $this->success);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['CREATE_QUIZ_INSTANCE_ERROR']);
                        return $response;
                    }
                } else {
                    // Return following error if Test details doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUIZ_NOT_FOUND_ERROR']);
                    return $response;
                }
            } else {
                // Return following error if your doesn't have permission to assign user to quiz
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_TEST_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc This function will get the test instance question details
     * @param Request $request
     * @return type
     */
    public function getInstanceItemDetails(Request $request) {

        //Create the quiz instance based on the base quiz id
        $instanceId = $request->get('instanceId');

        $itemId = $request->get('itemId');

        $version = $request->get('version');

        $accessToken = $request->headers->get('Authorization');

        // Get QP userId from access token
        $userId = $this->app['login.service']->getUserIdFromToken($accessToken);

        if ($instanceId) {

            //check its a valid instance
            $validInstance = $this->app['tests.service']->checkValidInstance($instanceId, $userId);

            if ($validInstance) {

                // If item id is passed, then check its a valid instance item id
                if (isset($itemId)) {

                    //check question exists under the test instance
                    $validItem = $this->app['tests.service']->checkValidInstanceItem($instanceId, $itemId, $version);

                    // IF item is valid then proceed
                    if ($validItem) {

                        //get quiz instance details
                        $itemDetails = $this->app['tests.service']->getInstanceItemDetails($instanceId, $itemId, $version);
                    } else {
                        // Return following error if instance question not associated.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUESTION_QUIZ_ASSOC_ERROR']);
                        return $response;
                    }
                } else {
                    // If item_id is not passed, then get the first question details/bookmarked item details if any bookmark
                    //get quiz instance details
                    $itemDetails = $this->app['tests.service']->getInstanceItemDetails($instanceId);
                }

                if ($itemDetails == $this->app['cache']->fetch('Completed')) {

                    // If test is completed then return test instance completion error.  


                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['TEST_INSTANCE_COMPLETED_ERROR']);
                    return $response;
                } else if (!empty($itemDetails)) {

                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemDetails, $this->success);
                    return $response;
                } else {

                    // If any error occurs while getting, then return error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GET_QUIZ_QUESTION_INSTANCE_ERROR']);
                    return $response;
                }
            } else {
                // Return following error if Test instance doesn't exists.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INVALID_QUIZ_INSTANCE_USER_ERROR']);
                return $response;
            }
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_TEST_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * Get the test result items details in summary page
     * @param Request $request
     * @return type
     */
    public function getTestResultItems(Request $request) {

        //Get the instance id
        $instanceId = $request->get('instanceId');

        //Get the testid
        $testId = $request->get('id');

        //Get the accessToken
        $accessToken = $request->headers->get('Authorization');

        $testRequest = $request->query->all();

        //Get the clientUserId
        $clientUserId = $request->query->get('clientUserId');

        $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);

        if ($userId) {
            if ($instanceId && $testId) {

                //Get list of questions for the given quiz instance
                $itemsList = $this->app['tests.service']->getTestResultItems($instanceId, $testId, $userId, $testRequest);

                if (!empty($itemsList)) {

                    //Return questions list for the quiz instance
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemsList, $this->success);
                    return $response;
                } else {

                    // If any error occurs while creating, then return common error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GET_QUESTION_QUIZ_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_TEST_REQUEST_ERROR']);
                return $response;
            }
        } else {
            // Return following error if user doen't have permission to delete test.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * submit the answer
     * @param Request $request
     * @return type
     * @request  {  "answeredItem" : ["10","21"],
      "timeSpent" : 1,
      "bookmarkItem" : 10,
      "submit" : true/false,
      "clientUserId" : 1}
     */
    public function submitTestAnswers(Request $request) {

        //Get the instance id
        $instanceId = $request->get('id');

        //Get the itemId
        $itemId = $request->get('itemId');

        //Get the item version
        $version = $request->get('version');

        //Get the request content
        $answerRequest = json_decode($request->getContent(), true);

        //Get the accessToken
        $accessToken = $request->headers->get('Authorization');

        //Get the clientUserId
        $clientUserId = $answerRequest['clientUserId'];

        $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId);
        if ($userId) {
            if ($instanceId) {

                //Get list of questions for the given quiz instance
                $response = $this->app['tests.service']->submitTestAnswers($instanceId, $itemId, $version, $userId, $answerRequest);

                if (!empty($response)) {

                    //Return questions list for the quiz instance
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($response, $this->created);
                    return $response;
                } else {

                    // If any error occurs while creating, then return common error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['SUBMIT_QUIZ_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_TEST_REQUEST_ERROR']);
                return $response;
            }
        } else {
            // Return following error if user doen't have permission to delete test.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    

}
