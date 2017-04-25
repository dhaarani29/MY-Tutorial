<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Stub API's for ItembankAdmin module using silex.
 */

namespace QuizPlat\Admin\ItembankAdmin;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Classname : ItembankAdminController;
 * controller class which defines all the stubs skeleton methods.
 */

class ItembankAdminController {

    protected $app;

    public function __construct(Application $app) {
        
       $this->app = $app;
    }

    /**
     * @SWG\Delete(
     *     path="/itembanks/{id}",
     *     summary="Soft delete Item Bank (Question Bank).",
     *     tags={"itembanks"},
     *     operationId="itembanks_DeleteQuestionBank",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item bank ID to delete.",
     *         required=true,
     *         type="integer",
     *         format="int32", 
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="NoContent"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not found"
     *     )
     *     
     * )
     */

    
    public function deleteItemBank(Request $request) {
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
           
            if(is_numeric($request->get('id'))) {
                 return new JsonResponse("",'204');
            }
            else {
                $response = array (
                              'code' => 3004,
                              'message' => 'Error deleting Item/question bank data.',
                              'description' => 'Invalid item bank id',
                              'logReference' => 5041,
                            );

                return new JsonResponse($response,'400');
            }
        }
        else {
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
     * @SWG\Post(
     *     path="/itembanks",
     *     summary="Create a new Item Bank (Question Bank) along with list of Items / Questions.",
     *     tags={"itembanks"},
     *     operationId="itembanks_PostQuestionBank",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="itembank",
     *         in="body",
     *         description="Item Bank Object",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/ItemBankDto"),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Item bank created."
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Itembank not found"
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
     *                  property="itembank_items",
     *                  type="object",
     *                  @SWG\Property(property="id",type="integer"),
     *                  @SWG\Property(property="identifier",type="string"),
     *                  @SWG\Property(property="item_type",type="string"),
     *                  @SWG\Property(property="title",type="string"),
     *                  @SWG\Property(property="label",type="string"),
     *                  @SWG\Property(property="version",type="integer")
     *              )
     *         )
     *     )
     * 
     */
    
        
     public function createItemBank(Request $request) {
         
         if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
           
         $response = array (
                                        'id' => 123,
                                        'uri' => 'http://'.$_SERVER['HTTP_HOST'].'/api/itembanks/123',
                                        );  
                               
                                return new JsonResponse($response,'201');
            
         
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
     * @SWG\Put(
     *     path="/itembanks/{id}",
     *     summary="Update Item Bank (Question Bank) information.",
     *     tags={"itembanks"},
     *     operationId="itembanks_PutQuestionBank",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          name = "id",  
     *           in="path",
     *           type="integer",
     *           format="int32",
     *           description = "Itembank id" ,
     *           required=true
     *     ),
     *      @SWG\Parameter(
     *         name="itembank",
     *         in="body",
     *         description="Item Bank Object",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/ItemBankDto"),
     *     ),
     *    @SWG\Response(
     *         	response="204",
     *         	description="NoContent",
     *     		),
     *     @SWG\Response(
     *         response="404",
     *         description="Itembank not found"
     *     )
     * )
     */
    
        
     public function updateItemBank(Request $request) {
         if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
           
        if(!is_numeric($request->get('id'))) {
                $response = array (
                              'code' => 3005,
                              'message' => 'Error updating Item/question bank data.',
                              'description' => 'Invalid item bank id',
                              'logReference' => 5031,
                            );

                return new JsonResponse($response,'400');
        }
        else {
           
               return new JsonResponse("",'204');
        } 
        }
 else {  $error =  array (
                                           'code' => "A001",
                                           'message' => "Api Authentication failed,Please check username/password",
                                           'description' => "String",
                                           'logReference' => "string"
                                           ) ;
            return new JsonResponse($error,'401');}
         
     }

   

}
