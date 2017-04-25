<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Stub API's for Tests module using silex.
 */

namespace QuizPlat\EndUser\Tests;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;

/*
 * Classname : TestsController;
 * controller class which defines all the stubs skeleton methods.
 */

 

class TestsController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * @SWG\Get(
     *     path="/tests",
     *     summary="Provides list of Assessment Tests available in the system.",
     *     operationId="getQuizList",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"tests"},
     *     @SWG\Parameter(
     *         name="tag_name",
     *         in="query",
     *         description="Tag names seperated by comma to filter test list",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="title",
     *         in="query",
     *         description="Title name to filter test list",
     *         required=false,
     *         type="string",
     *        
     *     ),
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="External integrated  Client Id",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="external_user_id",
     *         in="query",
     *         description="User Id used in external system.",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Fieldname with + or - for ascending or descending sort. Ex : -title : to sort in descending order, +title to sort in ascending order. Available field names : Quiz name & status.",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page Number for pagination",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Per page how many records to list",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @SWG\Items(
     *             ref="#/definitions/testList"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="NotFound"
     *     )                               
     * )
     */ 

    /**
     * @SWG\Definition(
     *  definition="testList",
     *  required={"id","identifier","title","label","version","test_type","status"},
     *  type="array",
     *   @SWG\Items(     
     *         @SWG\Property(
     *             property="id",
     *             description="Test Id",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="identifier",
     *             description="Test Identifier",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="title",
     *             description="Test title - name",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="label",
     *             description="Test title - description",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="version",
     *             description="Test current version",
     *             type="integer",
     *             format="int32"
     *         ),     
     *         @SWG\Property(
     *             property="testType",
     *             description="Test type",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="status",
     *             description="Test status(P - Published).",
     *             type="string"
     *         )
     *       )     
     *  )     
     */
    public function getTestList(Request $request)
    {
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 

        $testList = array (
                              0 => 
                              array (
                                'id' => 1,
                                'identifier' => 'A0001',
                                'title' => 'Anatomy Quiz',
                                'label' => 'Anatomy related Question published year 2016',
                                'version' => 2,
                                'testType' => 2,
                                'status' => 'P',
                              ),
                              1 => 
                              array (
                                'id' => 2,
                                'identifier' => 'A0002',
                                'title' => 'MathQuiz',
                                'label' => 'Math quiz published year 2014',
                                'version' => 1,
                                'testType' => 1,
                                'status' => 'P',
                              ),
                              2 => 
                              array (
                                'id' => 3,
                                'identifier' => 'A0003',
                                'title' => 'Anatomy advance quiz',
                                'label' => 'Anatomy advance quiz published year 2016',
                                'version' => 3,
                                'testType' => 2,
                                'status' => 'P',
                              ),
                            );
        return new JsonResponse($testList,'200');
        }
        else
        {
             $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;     
             return new JsonResponse($errorMsg,'401');
        }

    }

    /**
     * @SWG\Get(
     *     path="/tests/{id}",
     *     summary=" Retrieves the Assessment Test information based on test internal id.",
     *     operationId="getTestById",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"tests"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Test Id",
     *         required=true,
     *         type="integer",
     *         format="int32", 
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @SWG\Schema(
     *             ref="#/definitions/TestID"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="NotFound"
     *     )       
     * )
     */
        /**
     * @SWG\Definition(
     *  definition="TestID",
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="identifier",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="title",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="label",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="version",
     *             type="integer",
     *             format="int32"
     *         ),     
     *         @SWG\Property(
     *             property="testType",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="testMode",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="enableFeedback",
     *             type="boolean"
     *         ),                   
     *         @SWG\Property(
     *             property="testFeedback",
     *             type="string"
     *         ),                   
     *         @SWG\Property(
     *             property="navigationType",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="randomize",
     *             type="boolean"
     *         ), 
     *         @SWG\Property(
     *             property="effectiveDate",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="startDate",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="endDate",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="status",
     *             type="string"
     *         ),      
     *         @SWG\Property(
     *             property="testTarget",
     *             type="string",
     *             ref="#/definitions/TestTargetDto"
     *         ), 
     *         @SWG\Property(
     *             property="timeLimits",
     *             type="string",
     *             ref="#/definitions/TimeLimitDto"
     *         ),                   
     *         @SWG\Property(
     *             property="testItembanks",
     *             type="array",
     *             @SWG\Items(
     *                  ref="#/definitions/ItemBankListName"
     *             )
     *         ),
     *         @SWG\Property(
     *             property="testItems",
     *             type="array",
     *             @SWG\Items(
     *                  ref="#/definitions/item"
     *             )
     *         )     
     *  )     
     */ 
    /**
     * @SWG\Definition(
     *  definition="TestDto",
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="identifier",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="title",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="label",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="version",
     *             type="integer",
     *             format="int32"
     *         ),     
     *         @SWG\Property(
     *             property="test_type",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="test_mode",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="enable_feedback",
     *             type="boolean"
     *         ),                   
     *         @SWG\Property(
     *             property="test_feedback",
     *             type="string"
     *         ),                   
     *         @SWG\Property(
     *             property="navigation_type",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="randomize",
     *             type="boolean"
     *         ), 
     *         @SWG\Property(
     *             property="effective_date",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="start_date",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="end_date",
     *             type="string"
     *         ), 
     *         @SWG\Property(
     *             property="status",
     *             type="string"
     *         ),      
     *         @SWG\Property(
     *             property="test_target",
     *             type="string",
     *             ref="#/definitions/TestTargetDto"
     *         ), 
     *         @SWG\Property(
     *             property="time_limits",
     *             type="string",
     *             ref="#/definitions/TimeLimitDto"
     *         ),                   
     *         @SWG\Property(
     *             property="test_itembanks",
     *             type="array",
     *             @SWG\Items(
     *                  ref="#/definitions/ItemBankList"
     *             )
     *         ),
     *         @SWG\Property(
     *             property="test_items",
     *             type="array",
     *             @SWG\Items(
     *                  ref="#/definitions/itemtests"
     *             )
     *         )     
     *  )     
     */ 

    /**
     *   @SWG\Definition(
     *         definition="TestTargetDto",
     *         required={"type", "noOfQuestions"},
     *         @SWG\Property(
     *             property="type",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="noOfQuestions",
     *             type="integer",
     *             format="int32"
     *         )
     *     )
     */  

    /**
     *   @SWG\Definition(
     *         definition="TimeLimitDto",
     *         required={"minTime", "maxTime","allowLateSubmission"},
     *         @SWG\Property(
     *             property="minTime",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="maxTime",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="allowLateSubmission",
     *             type="boolean"
     *         )
     *     )
     */    
    public function getTestById(Request $request)
    {
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
			$testId = $request->get('id');
			if($testId == "" || !is_numeric($testId)){
				$response = array (
								  'code' => 4001,
								  'message' => 'Error retrieving assessment test data.',
								  'description' => 'Invalid test id',
								  'logReference' => 5071,
								);

				return new JsonResponse($response,'400');

			} else {

				$response = json_decode('[{
									"id": 3,
									"identifier": "A10001",
									"title": "Anatomy Question Bank 3",
									"label": "Anatomy related Question published year 2016",
									"version": 2,
									"testType": "Self",
									"testMode": "Review",
									"enableFeedback": true,
									"testFeedback": "final test feed back text",
									"navigationType": "Sequential",
									"timeLimits": {
										"minTime": 60,
										"maxTime": 90,
										"allowLateSubmission": false
									},
									"randomize": true,
									"effectiveDate": "2016-03-22",
									"startDate": "2016-03-25",
									"endDate": "2017-03-25",
									"status": "P",
									"testTarget": {
										"type": 1,
										"noOfQuestions": 100
									},
									"testItembanks": [{
										"id": 1,
										"name": "Anatomy QB001",
										"version": 2
									},
									{
										"id": 2,
										"name": "Anatomy QB002",
										"version": 1
									}
									],
									"testItems": [{
										"id": 1,
										"identifier": "QA0001",
										"itemType": "MC",
										"title": "Question 1",
										"version": 2
									},
									{
										"id": 2,
										"identifier": "QA0002",
										"itemType": "MC",
										"title": "Question 2",
										"version": 1
									},
									{
										"id": 3,
										"identifier": "QA0003",
										"itemType": "MC",
										"title": "Question 3",
										"version": 4
									}]
								}]', true);
				return new JsonResponse($response,'200');
			}
        }
        else
        {
            $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;    
             return new JsonResponse($errorMsg,'401'); 
        }
    }

    /**
     * @SWG\Delete(
     *     path="/tests/{id}",
     *     summary="Soft deletes exisitng Assessment Test.",
     *     operationId="deleteQuizById",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"tests"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Quiz Id",
     *         required=true,
     *         type="integer",
     *         format="int32", 
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="No Content"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *     )       
     * )
     */ 

    public function deleteTestById(Request $request)
    {
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
			$testId = $request->get('id');
			if($testId == "" || !is_numeric($testId)){
				$response = array (
								  'code' => 4001,
								  'message' => 'Error deleting test data.',
								  'description' => 'Invalid test Id',
								  'logReference' => 5071,
								);

				return new JsonResponse($response,'400');

			}else{

				return new JsonResponse("",'204');
			}
        }
        else
        {
             $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;    
             return new JsonResponse($errorMsg,'401');
        }
    }


    /**
     * @SWG\Post(
     *     path="/tests",
     *     tags={"tests"},
     *     operationId="addTest",
     *     summary="Creates new Assessement Test.",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="test",
     *         in="body",
     *         description="Asessment Item information",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/TestDto"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Created",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *     )        
     * )
     */

    public function addTest(Request $request)
    {
         if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
        
        
			$contentTypeCheck = strpos($request->headers->get('Content-Type'), 'application/json');
			$data = json_decode($request->getContent(), true);

			if (0 !== $contentTypeCheck || !is_array($data)) {

				$response = array (
								  'code' => 4002,
								  'message' => 'No content found to insert.',
								  'description' => 'Empty assessment data',
								  'logReference' => 5071,
								);

			return new JsonResponse($response,'204');

			}else{
				$response = array (
								  'id' => 123,
								  'uri' => 'http://'.$_SERVER['HTTP_HOST'].'/api/tests/123',
								);     
				return new JsonResponse($response,'201');
			}
        }
        else
        {
             $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;    
             return new JsonResponse($errorMsg,'401'); 
        }
    }

    /**
     * @SWG\Put(
     *     path="/tests/{id}",
     *     tags={"tests"},
     *     summary="Updates Assessment Test information.",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Test Id",
     *         required=true,
     *         type="integer",
     *         format="int32", 
     *     ),     
     *     @SWG\Parameter(
     *         name="test",
     *         in="body",
     *         description="Asessment Item information",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/TestDto"),
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="No Content"
     *     ),       
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *     )        
     * )
     */
    public function updateTest(Request $request)
    {
         if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
        
			$contentTypeCheck = strpos($request->headers->get('Content-Type'), 'application/json');
			$data = json_decode($request->getContent(), true);
			$response = "";
			$testId = $request->get('id');
			$responseCode = 204;

			if (0 !== $contentTypeCheck || !is_array($data)) {
				$response = array (
								  'code' => 4003,
								  'message' => 'No content found to insert.',
								  'description' => 'Empty assessment data',
								  'logReference' => 5071,
								);
				$responseCode = 400;

			}else if($testId == "" || !is_numeric($testId)){

				$response = array (
								  'code' => 4001,
								  'message' => 'Error updating test data.',
								  'description' => 'Invalid test Id',
								  'logReference' => 5071,
								);
				$responseCode = 400;

			}

			return new JsonResponse($response,$responseCode);
        }
        else
        {
               $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;      
             return new JsonResponse($errorMsg,'401'); 
        }

    }
    

    /**
     * @SWG\Get(
     *     path="/tests/{id}/progress/{client_user_id}",
     *     summary="Retrieves detailed Quiz (Assessment Test) progress for specified external user.",
     *     tags={"tests"},
     *     operationId="tests_usertestprogress",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id, mandatory parameter.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Parameter(
     *         name="client_user_id",
     *         in="path",
     *         description="client user id, mandatory parameter.",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Quiz Progress Response",
     *         @SWG\Schema(ref="#/definitions/Tests"),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found",
     *     )
     *     
     * )
     */

    /**
     * *   @SWG\Definition(
     *         definition="Tests",
     *         @SWG\Property(property="clientUserId",type="integer",format="int32"),
     *         @SWG\Property(property="clientId", type="string"),
     *         @SWG\Property(property="testId",type="integer",format="int32"),
     *         @SWG\Property(property="status",type="integer",format="int32"),
     *         @SWG\Property(property="totalTimeSpent",type="integer",format="int32"),
     *         @SWG\Property(property="bookmark",type="integer", format="int32"),
     *         @SWG\Property(property="correct",type="integer",format="int32"),
     *         @SWG\Property(property="incorrect",type="integer",format="int32"),
     *         @SWG\Property(property="unattempted",type="integer",format="int32"),
     *         @SWG\Property(property="grade",type="integer", format="int32"),
     *         @SWG\Property(property="testStart",type="string"),
     *         @SWG\Property(property="testLastAttempted",type="string"),
     *         @SWG\Property(
     *             property="itemsProgress",
     *              type="object",
     *             @SWG\Property(property="id",type="integer"),
     *             @SWG\Property(property="version",type="integer"),
     *             @SWG\Property(property="attemptCount",type="integer"),
     *             @SWG\Property(property="score",type="integer"),
     *             @SWG\Property(
     *                              property="lastResponse",
     *                              type="object",
     *                              @SWG\Property(property="correct",type="boolean"),
     *                              @SWG\Property(property="value",type="string"),
     *                          ),
     *             @SWG\Property(property="timeSpent",type="integer")
     *         )
     *     )
     * 
     */
    public function getTestsProgress(Request $request) {
         if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
        
            if ($request->get('id') != "" && $request->get('client_user_id') != "" && is_numeric($request->get('id'))) {
                $response = json_decode('{
                    "clientUserId": "HLU001",
                    "clientId": "HL",
                    "testId": "AT001",
                    "status": "I",
                    "totalTimeSpent": 7,
                    "bookmark": 3,
                    "correct": 2,
                    "incorrect": 1,
                    "unattempted": 2,
                    "grade": "",
                    "testStart": "20160214",
                    "testLastAttempted": "20160224",
                    "itemsProgress": [{
                            "id": "Q001",
                            "version": 1,
                            "attemptCount": 1,
                            "score": 1,
                            "lastResponse": {
                                    "correct": true,
                                    "value": "C"
                            },
                            "timeSpent": 2
                    },
                    {
                            "id": "Q002",
                            "version": 2,
                            "attemptCount": 1,
                            "score": 1,
                            "lastResponse": {
                                    "correct": true,
                                    "value": "Text Response"
                            },
                            "timeSpent": 2
                    },
                    {
                            "id": "Q003",
                            "Version": 1,
                            "attemptCount": 2,
                            "score": 0,
                            "lastResponse": {
                                    "correct": false,
                                    "value": "A"
                            },
                            "timeSpent": 3
                    },
                    {
                            "id": "Q004",
                            "version": 1,
                            "attemptCount": 0
                    },
                    {
                            "id": "Q005",
                            "version": 1,
                            "attemptCount": 0
                    }]
                }');
                return new JsonResponse($response,'200');
                
            } else {
                $response = array (
                              'code' => 4051,
                              'message' => 'Error retrieving Quiz progress data.',
                              'description' => 'Invalid test id',
                              'logReference' => 5071,
                            );

                return new JsonResponse($response,'400');
            }
        }
        else
        {
               $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;   
             return new JsonResponse($errorMsg,'401'); 
        }
    }

    /**
     * @SWG\Get(
     *     path="/tests/{id}/progress",
     *     summary=" Retrieves Assessment Test progress details for all users enrolled for given Client. ",
     *     tags={"tests"},
     *     operationId="tests_allusertestprogress",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id, mandatory parameter.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Quiz Progress Response for all users.",
     *         @SWG\Schema(ref="#/definitions/AllUsersTests")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found"
     *     )
     *     
     * )
     */

    /**
     * *   @SWG\Definition(
     *         definition="AllUsersTests",
     *         @SWG\Property(property="clientUserId",type="integer",format="int32"),
     *         @SWG\Property(property="clientId",type="string"),
     *         @SWG\Property(property="testId",type="integer",format="int32"),
     *         @SWG\Property(property="status",type="integer",format="int32"),
     *         @SWG\Property(property="totalTimeSpent",type="integer",format="int32"),
     *         @SWG\Property(property="bookmark",type="integer",format="int32"),
     *         @SWG\Property(property="correct",type="integer",format="int32"),
     *         @SWG\Property(property="incorrect",type="integer",format="int32"),
     *         @SWG\Property(property="unattempted",type="integer",format="int32"),
     *         @SWG\Property(property="grade",type="integer",format="int32"),
     *         @SWG\Property(property="testStart",type="string"),
     *         @SWG\Property(property="testLastAttempted",type="string")
     *     )
     * 
     */
    
    public function getAllTestsProgress(Request $request) {
         if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        { 
        
            if ($request->get('id') != "" && is_numeric($request->get('id'))) {
                $response = json_decode('[{
                        "clientUserId": "HLU001",
                        "clientId": "HL",
                        "testId": "AT001",
                        "status": "I",
                        "totalTimeSpent": 7,
                        "bookmark": 3,
                        "correct": 2,
                        "incorrect": 1,
                        "unattempted": 2,
                        "grade": "",
                        "testStart": "20160214",
                        "testLastAttempted": "20160224"
                },
                {
                        "clientUserId": "HLU002",
                        "clientId": "HL",
                        "testId": "AT001",
                        "status": "C",
                        "totalTimeSpent": 15,
                        "bookmark": 5,
                        "correct": 3,
                        "incorrect": 2,
                        "unattempted": 0,
                        "grade": "B",
                        "TestStart": "20160216",
                        "TestLastAttempted": "20160225"
                },
                {
                        "clientUserId": "HLU001",
                        "clientId": "HL",
                        "testId": "AT001",
                        "status": "C",
                        "totalTimeSpent": 7,
                        "bookmark": 5,
                        "correct": 4,
                        "incorrect": 1,
                        "unattempted": 0,
                        "grade": "A",
                        "testStart": "20160217",
                        "testLastAttempted": "20160227"
                }]');
                
                return new JsonResponse($response,200);
            } else {
                $response = array (
                              'code' => 4051,
                              'message' => 'Error retrieving Quiz progress data.',
                              'description' => 'Invalid test id',
                              'logReference' => 5071,
                            );

                return new JsonResponse($response,'400');
            }

        }
        else
        {
             $errorMsg =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;   
             return new JsonResponse($errorMsg,'401');
        }
    }

}
