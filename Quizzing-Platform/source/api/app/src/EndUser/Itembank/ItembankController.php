<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Stub API's for Itembank module using silex.
 */

namespace QuizPlat\EndUser\Itembank;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Classname : ItembankController;
 * controller class which defines all the stubs skeleton methods.
 */

class ItembankController {

    protected $app;

    public function __construct(Application $app) {
        
       $this->app = $app;
    }

    /**
     * @SWG\Get(
     *     path="/itembanks",
     *     summary="Retrieve list of available Assessment ItemBanks (Question Banks), optionally filter data by list of item bank name.",
     *     tags={"itembanks"},
     *     operationId="itembanks_GetQuestionBanks",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="tag_name",
     *         in="query",
     *         description="Metadata Tags to filter by. Multiple tags can be passed seperated by comma.",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="bank_name",
     *         in="query",
     *         description="Item Bank Name to filter by",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Fieldname with + or - for ascending or descending sort. Ex : -tag_name : to sort in descending order, +tag_name to sort in ascending order. Available field names : Question bank name, Description, No of question, Status & Metadata tags.",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="query",
     *         description="Item bank status (P - Published, A - Authored)",
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
     *         description="Itembank Response",
     *         @SWG\Schema(ref="#/definitions/ItemBankListDto"),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found"
     *        
     *     )
     * )
     */

    /**
     * *   @SWG\Definition(
     *         definition="ItemBankListDto",
     *         required={"id", "name","description","version","status"},
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="name",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="description",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="version",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="status",
     *             type="string"
     *         )
     *     )
     * 
     */
      /**
     * *   @SWG\Definition(
     *         definition="ItemBankList",
     *         required={"id", "version"},
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *             format="int32"
     *         ),
     *         
     *         @SWG\Property(
     *             property="version",
     *             type="string"
     *         )
     *         
     *     )
     * 
     */
    
      /**
     * *   @SWG\Definition(
     *         definition="ItemBankListName",
     *         required={"id", "name","version"},
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *             format="int32"
     *         ),
       *  @SWG\Property(
     *             property="name",
     *             type="string"
     *           
     *         ),
     *         
     *         @SWG\Property(
     *             property="version",
     *             type="string"
     *         )
     *         
     *     )
     * 
     */
    public function getListItemBanks(Request $request) {
         if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
        
              $response = '[{
                            "id": 1,
                            "name": "Anatomy Question Bank 1",
                            "description": "Anatomy related Question published year 2016",
                            "version": 2,
                            "status": "P"
                    },
                    {
                            "id": 2,
                            "name": "Anatomy Question Bank 2",
                            "description": "Anatomy related Question published year 2016",
                            "version": 2,
                            "status": "P"
                    },
                    {
                            "id": 3,
                            "name": "Anatomy Question Bank 3",
                            "description": "Anatomy related Question published year 2016",
                            "version": 2,
                            "status": "P"
                    },
                    {
                            "id": 4,
                            "name": "Anatomy Question Bank 4",
                            "description": "Anatomy related Question published year 2016",
                            "version": 2,
                            "status": "P"
                    },
                    {
                            "id": 5,
                            "name": "Anatomy Question Bank 5",
                            "description": "Anatomy related Question published year 2016",
                            "version": 2,
                            "status": "P"
                    }]';
               
               return new JsonResponse(json_decode($response),200);
           
        }
      
        else
        {
            $error =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;
            return new JsonResponse($error,'401');
        }
    }

    /**
     * @SWG\Get(
     *     path="/itembanks/{id}",
     *     summary="Retrieve specific Item Bank (Question Bank) data by Id.",
     *     tags={"itembanks"},
     *     operationId="itembanks_GetQuestionBank",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item bank ID",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Itembank Details Response",
     *         @SWG\Schema(ref="#/definitions/ItemBankDto"),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found"
     *         )
     *     )
     * )
     */

    /**
     * *   @SWG\Definition(
     *         definition="ItemBankDto",
     *         description = "Item bank (Question bank) domain model API calls",
     *         @SWG\Property(
     *             property="itembank",
     *              type="object",
     *             @SWG\Property(property="id",type="integer",format="int32",description = "Item bank Id - primary key.",),
     *             @SWG\Property(property="name",type="string",description = "Item bank name."),
     *             @SWG\Property(property="description",type="string",description = "Item bank description."),
     *             @SWG\Property(property="version",type="integer",format="int32",description = "Item bank current version."),
     *             @SWG\Property(property="label",type="string",description = "Item bank status (P - Published, A - Authored)"),
     *             @SWG\Property(
     *                  property="itembankItems",
     *                  type="object",
     *                  @SWG\Property(property="id",type="integer"),
     *                  @SWG\Property(property="identifier",type="string"),
     *                  @SWG\Property(property="itemType",type="string"),
     *                  @SWG\Property(property="title",type="string"),
     *                  @SWG\Property(property="label",type="string"),
     *                  @SWG\Property(property="version",type="integer")
     *              )
     *         )
     *     )
     * 
     */
    public function getItemBankbyId(Request $request) {
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
        
        if(!is_numeric($request->get('id'))) {
                $response = array (
                              'code' => 3001,
                              'message' => 'Error retrieving Item/question bank data.',
                              'description' => 'Invalid item bank id',
                              'logReference' => 5031,
                            );

                return new JsonResponse($response,'400');
            }
         else {
            $response = json_decode('[{
                "id": 3,
                "name": "Anatomy Question Bank 3",
                "description": "Anatomy related Question published year 2016",
                "version": 2,
                "status": "P",
                "items": [
                  {
                            "id": 1,
                            "identifier": "QA0001",
                            "itemType": "MC",
                            "title": "Question 1",
                            "label": "Completion of the Human Genome Project has revealed that human cells have a repertoire of genes of which approximate number?",
                            "version" : 2
                    },
                    {
                            "id": 2,
                            "identifier": "QA0002",
                            "itemType": "MC",
                            "title": "Question 2 ",
                            "label ": "Match the following agents most commonly involved with cancer of that particular organ",
                            "version" : 2
                    },
                    {
                            "id": 3,
                            "identifier": "QA0003",
                            "itemType": "TF",
                            "title": "Question 3 ",
                            "label": "Case-control studies examining the role of diet in the cause of cancer do not suffer from recall bias.",
                            "version" : 2
                    },
                    {
                            "id": 4,
                            "identifier": "QA0004",
                            "itemType": "CM",
                            "title": "Question 4 ",
                            "label": "Which of the following statement(s) is/are TRUE about granulosa cell tumors of the ovary?",
                            "version" : 2
                    },
                    {
                            "id": 5,
                            "identifier": "QA0005",
                            "itemType": "FI",
                            "title": "Question 5 ",
                            "label": "The ______________________ indicates which mathematical operations in an equation to do first. ",
                            "version" : 2
                    },
                    {
                            "id": 6,
                            "identifier": "QA0006",
                            "itemType": "FI",
                            "title": "Question 6 ",
                            "label": "View Image. In this equation, x = ________________. ",
                            "version" : 2
                    },
                    {
                            "id": 7,
                            "identifier": "QA0007",
                            "itemType": "FI",
                            "title": "Question 7 ",
                            "label": "View Image, where y = –5, d = 7.2, f = –2, p = 4, and t = 1/3. In this equation, q = _________________. ",
                            "version" : 2
                    },
                    {
                            "id": 8,
                            "identifier": "QA0008",
                            "itemType": "FI",
                            "title": "Question 8",
                            "label": "_____________________ represents the number of times a base number, such as 10, is multiplied by itself. ",
                            "version" : 2
                    },
                    {
                            "id": 9,
                            "identifier": "QA0009",
                            "itemType": "FI",
                            "title": "Question 9",
                            "label": "A(n) _________________ is a statement asserting the equality of two quantities. ",
                            "version" : 2
                    },
                    {
                            "id": 10,
                            "identifier": "QA0010",
                            "itemType": "FI",
                            "title": "Question 10",
                            "label": "The _____________ is a line drawn from the central point of a circle to any point on the circle. ",
                            "version" : 2
                    }
                ]
            }]');
            return new JsonResponse($response,200);
         } 
        }
        else
        {
            $error =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;
            return new JsonResponse($error,'401');
        }
        
    }

}
