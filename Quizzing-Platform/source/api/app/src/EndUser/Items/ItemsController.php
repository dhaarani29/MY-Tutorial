<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Design of "Stubs Module" using silex framework for POC.
 */

namespace QuizPlat\EndUser\Items;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;



class ItemsController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }


/**
  
     *   @SWG\Definition(
 *         definition="item",
 *         required={"id", "identifier","itemType","title","label"},
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
 *             property="itemType",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="title",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="label",
 *             type="string"
 *         )
 *     )
     */
   
/**
  
     *   @SWG\Definition(
 *         definition="itemtests",
 *         required={"id", "identifier","itemType","title","label"},
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
 *             property="version",
 *             type="integer",
 *             format="int32"
 *         )
 *     )
     */
   
       /**
     * @SWG\Get(
     *     path="/items",
        *  tags={"items"},
        *  summary="Retrieve list of available Assessment Items (Questions), optionally filter data by list of tagNames or title",
     *     operationId="findItems",
     *   consumes={"application/json"},
           produces={"application/json"},
     *     @SWG\Parameter(
     *         name="tag_name",
     *         in="query",
     *         description="tags to filter by",
     *         required=false,
     *         type="string",
     *        
     *        
     *     ),
        
     *     @SWG\Parameter(
     *         name="title",
     *         in="query",
     *         description="title to filter by",
     *         required=false,
     *         type="string",
     *        
     *     ),
        @SWG\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Fieldname with + or - for ascending or descending sort. Ex : -tag_name : to sort in descending order, +tag_name to sort in ascending order. Available field names : Label, Identifier, Type & Status.",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="query",
     *         description="Pass status as Published or not(P - Published, A - Authored)",
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
     *         response=200,
     *         description="item response",
     *         @SWG\Schema(
     *             ref="#/definitions/item"
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found",
     *       
     *     ),
     *   deprecated = false
     * )
     */
     // Sample Method to return user details
    public function getItems(Request $request) {
    
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        {
         $tag_name =  $request->get(tag_name);
         $status =  $request->get(status);
         $title = $request->get(title);
         if($status!= '' && $status != 'P' && $status != 'A' )
         {
             $errorMsg = '[{
                                        "code": 2500,
                                        "message": "Status should be: P - Published, A - Authored",
                                        "description": "string",
                                        "logReference": "string"
             }]';    
               $errorMsg = json_decode($errorMsg, true);
		 return new JsonResponse($errorMsg,400);
         }
         
  
         $user = '[{
                            "id": 1,
                            "identifier": "QA0001",
                            "itemType": "MC",
                            "title": "Question 1",
                            "label": "Completion of the Human Genome Project has revealed that human cells have a repertoire of genes of which approximate number?"
                    },
                    {
                            "id": 2,
                            "identifier": "QA0002",
                            "itemType": "MC",
                            "title": "Question 2 ",
                            "label ": "Match the following agents most commonly involved with cancer of that particular organ"
                    },
                    {
                            "id": 3,
                            "identifier": "QA0003",
                            "itemType": "TF",
                            "title": "Question 3 ",
                            "label": "Case-control studies examining the role of diet in the cause of cancer do not suffer from recall bias."
                    },
                    {
                            "id": 4,
                            "identifier": "QA0004",
                            "itemType": "CM",
                            "title": "Question 4 ",
                            "label": "Which of the following statement(s) is/are TRUE about granulosa cell tumors of the ovary?"
                    },
                    {
                            "id": 5,
                            "identifier": "QA0005",
                            "itemType": "FI",
                            "title": "Question 5 ",
                            "label": "The ______________________ indicates which mathematical operations in an equation to do first. "
                    },
                    {
                            "id": 6,
                            "identifier": "QA0006",
                            "itemType": "FI",
                            "title": "Question 6 ",
                            "label": "View Image. In this equation, x = ________________. "
                    },
                    {
                            "id": 7,
                            "identifier": "QA0007",
                            "itemType": "FI",
                            "title": "Question 7 ",
                            "label": "View Image, where y = –5, d = 7.2, f = –2, p = 4, and t = 1/3. In this equation, q = _________________. "
                    },
                    {
                            "id": 8,
                            "identifier": "QA0008",
                            "itemType": "FI",
                            "title": "Question 8",
                            "label": "_____________________ represents the number of times a base number, such as 10, is multiplied by itself. "
                    },
                    {
                            "id": 9,
                            "identifier": "QA0009",
                            "itemType": "FI",
                            "title": "Question 9",
                            "label": "A(n) _________________ is a statement asserting the equality of two quantities. "
                    },
                    {
                            "id": 10,
                            "identifier": "QA0010",
                            "itemType": "FI",
                            "title": "Question 10",
                            "label": "The _____________ is a line drawn from the central point of a circle to any point on the circle. "
                    }
                    ]';
         
          $user = json_decode($user, true);
		 return new JsonResponse($user,200);
        }
        else
        { 
            $errorRes = array(
                    "code"=> 'A001',
                    "message"=> "Api Authentication failed,Please check username/password",
                    "description"=> "string",
                    "logReference"=> "string"
                );

            
		 return new JsonResponse($errorRes,401);
        }
    }
    

    
    /**
     * @SWG\Get(
     *     path="/items/{id}",
     *     tags={"items"},
     *     summary = "Retrieve specific assessment Item (Question) data by Id.",
     *     operationId="findItemById",
     *     consumes={"application/json"},
           produces={"application/json"},
     *     deprecated = false, 
     *     @SWG\Parameter(
     *         description="ID of question to fetch",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="item response",
     *         @SWG\Schema(ref="#/definitions/itembyid")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found",
     *         
     *     )
     * )
     */
    public function findItemById(Request $request)
    {
        $id =  $request->get("id");    
        $status =  $request->get(status);
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        {
            if($status!= '' && $status != 'P' && $status != 'A' )
         {
             $errorMsg = '[{
                                        "code": 2500,
                                        "message": "Status should be: P - Published, A - Authored",
                                        "description": "string",
                                        "logReference": "string"
             }]';    
               $errorMsg = json_decode($errorMsg, true);
		 return new JsonResponse($errorMsg,400);
         }
           if($id == '' || !is_numeric($id))
            {
                $errorRes = array(
                    "code"=> 2001,
                    "message"=> "The item should be numeric",
                    "description"=> "Error retrieving item data.",
                    "logReference"=> "3444"
                );

                return new JsonResponse($errorRes,400);
            }
        $responseString = '{
	"id": 1,
	"identifier": "QA0001",
	"itemType": "MC",
	"title": "Question 1",
	"label": "Completion of the Human Genome Project has revealed that human cells have a repertoire of genes of which approximate number?",
	"language": "",
	"version": 3,
	"promptText": "Sample Multiple Choice question text?",
	"assets":[{
		"assetId": 1,
		"assetName": "ortho related image",
		"assetType_id": 1,
		"fileName": "ortho_item_no.img",
		"altTitle": "Tool tip for ortho image",
		"isPrivate": false
	}],
	"score": 100,
	"modelFeedback": [{
		"outcomeType": 0,
		"showHide": true,
		"feedbackText": "Failed feedback text."
	},
	{
		"outcomeType": 1,
		"showHide": true,
		"feedbackText": "correct feedback text."
	}],
	"remediationLinks":[{
		"linkTypeId": 1,
		"linkText1": "Reference to",
		"linkText2": "13th Edition Sample book",
		"linkText3": "p481-495"
	}],
	"timeDependant": true,
	"timeLimit": 2,
	"adaptive": false,
	"difficulty": 5,
	"status": "P",
	"toolName": "",
	"toolVersion": "",
	"choiceInteraction": {
		"shuffle": true,
		"maxChoice": 1,
		"minChoice": 0,
		"simpleChoices": [{
			"resourceIdentifier": 34001,
			"fixed": false,
			"label": "Choice 1",
			"value": "C1",
			"correct": true
		},
		{
			"resourceIdentifier": 34002,
			"fixed": false,
			"label": "Choice 2",
			"value": "C2",
			"correct": false
		},
		{
			"resourceIdentifier": 34003,
			"fixed": false,
			"label": "Choice 3",
			"value": "C3",
			"correct": false
		},
		{
			"resourceIdentifier": 34004,
			"fixed": true,
			"label": "None of the above",
			"value": "C4",
			"correct": false
		}]
	}
}';
         $responseString = json_decode($responseString, true);
		 return new JsonResponse($responseString,200);
    }
        else
        { 
            $errorRes = array(
                    "code"=> 'A001',
                    "message"=> "Api Authentication failed,Please check username/password",
                    "description"=> "string",
                    "logReference"=> "string"
                );

            
		 return new JsonResponse($errorRes,401);
        }
    }

 /**
    @SWG\Definition(
 *         definition="errorModel",
 *         required={"code", "message" , "description" ,"log_reference"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="description",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="logReference",
 *             type="string"
 *         )
 *     )
  * 
  */
    
    /**
  
     *   @SWG\Definition(
 *         definition="itembyid",
     *     type= "object",
 *         required={"id", "identifier","itemType","title","label","language","version","promptText","assets",
     *               "score","modelFeedback","remediationLinks","timeDependant","timeLimit","adaptive","difficulty","status",
     *               "toolName","toolVersion","choiceInteraction"},
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
 *             property="itemType",
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
 *             property="language",
 *             type="string"
 *         ),
 *         @SWG\Property(
 *             property="version",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="promptText",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="assets",
     *          type="object",
 *             @SWG\Property(property="assetId",type="integer"),
     *         @SWG\Property(property="assetName",type="string"),
     *         @SWG\Property(property="assetTypeId",type="integer"),
     *         @SWG\Property(property="fileName",type="string"),
     *         @SWG\Property(property="altTitle",type="string"),
     *         @SWG\Property(property="isPrivate",type="boolean")
 *          
 *         ),
 *         @SWG\Property(
 *             property="score",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *               property="modelFeedback",
 *             @SWG\Property(property="outcomeType",type="integer"),
     *         @SWG\Property(property="showHide",type="boolean"),
     *         @SWG\Property(property="feedbackText",type="string"),
 *         ),
 *         @SWG\Property(
 *            property="remediationLinks",
 *             @SWG\Property(property="linkTypeId",type="integer"),
     *         @SWG\Property(property="linkText1",type="string"),
     *         @SWG\Property(property="linkText2",type="string"), 
     *         @SWG\Property(property="linkText3",type="string"), 
     *       
 *         ),
 *         @SWG\Property(
 *             property="timeDependant",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="timeLimit",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="adaptive",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="difficulty",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="status",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="toolName",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="toolVersion",
 *             type="integer"
 *         ),
 *         @SWG\Property(
 *             property="choiceInteraction",
 *           @SWG\Property(property="shuffle",type="boolean"),
     *         @SWG\Property(property="maxChoice",type="integer"),
     *         @SWG\Property(property="minChoice",type="integer"),
     *         @SWG\Property(property="simpleChoices",
     * 
     *              @SWG\Property(property="resourceIdentifier",type="string"),
     *              @SWG\Property(property="fixed",type="boolean"),
     *              @SWG\Property(property="label",type="string"),
     *              @SWG\Property(property="value",type="string"),
     *              @SWG\Property(property="correct",type="boolean"),
     * 
     *          ),
     *        
 *         )
 *         
     * 
 *     )
     */

    
    
    public function oauthValidate(Request $request)
    {
       header('Location:  http://localhost/swagger/o2c?access-token=1FzOVl6NmBqEtycXnRIeUg&token-type=Bearer&client=453454355345435&expiry=1459736879&uid=god@gmail.com');
       
       die;
    }

    public function oauthToken(Request $request)
    {
       echo "$%#$%#%#$D45345435345";die;
    }
    }
