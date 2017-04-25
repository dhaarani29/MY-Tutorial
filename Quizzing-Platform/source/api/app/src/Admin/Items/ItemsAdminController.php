<?php

/*
 * V1.0 Silex Stubs module
 * @Author : Shreelakshmi U
 * @Date : 30-08-2016
 * @Puropose : Design of "Stubs Module" using silex framework for POC.
 */

namespace QuizPlat\Admin\Items;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
  
     *   @SWG\Definition(
 *         definition="Assets",
 *         description="Asset class to define the asset at item level", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="asset_id",
 *             type="integer",
 *             format="int32",
 *             description = "Asset Id" 
 *         ),@SWG\Property(
 *             property="asset_name",
 *             type="string",
 *            
 *             description = "Asset name" 
 *         ),
 *         @SWG\Property(
 *             property="asset_type_id",
 *             type="integer",
 *             format="int32",
 *             description = "Asset type id from look-up table" 
 *         ),@SWG\Property(
 *             property="file_name",
 *             type="string",
 *            
 *             description = "Asset file name" 
 *         ),@SWG\Property(
 *             property="alt_title",
 *             type="string",
 *            
 *             description = "Asset alt text used for tool tip" 
 *         ),@SWG\Property(
 *             property="is_private",
 *             type="boolean",
 *            
 *             description = "Can this asset be private to Item only" 
 *         )
 * 
 *    )
/   
 * 
 * 
/**
  
     *   @SWG\Definition(
 *         definition="ModelFeedback",
 *         description="Question level Feedback options", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="outcome_type",
 *             type="integer",
 *             format="int32",
 *             description = "Outcome type - result" 
 *         ),@SWG\Property(
 *             property="feedback_text",
 *             type="string",
 *            
 *             description = "Feedback text" 
 *         ),
 *         @SWG\Property(
 *             property="show_hide",
 *             type="boolean",
 *            
 *             description = "Show or Hide the feed back text" 
 *         )
 * 
 *    )
/  
 */  
/**
  
     *   @SWG\Definition(
 *         definition="RemediationLinks",
 *         description="Item level remediation link definition", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="link_type_id",
 *             type="integer",
 *             format="int32",
 *             description = "Link type id" 
 *         ),@SWG\Property(
 *             property="link_text1",
 *             type="string",
 *            
 *             description = "Link text1, mostly used for URL and static text" 
 *         )
 *        ,@SWG\Property(
 *             property="link_text2",
 *             type="string",
 *            
 *             description = "Link text2, mostly used for URL and static text" 
 *         ),@SWG\Property(
 *             property="link_text3",
 *             type="string",
 *            
 *             description = "Link text3, mostly used for URL and static text" 
 *         )
 * 
 *    )
/  
 

 /**
  
     *   @SWG\Definition(
 *         definition="ChoiceInteraction",
 *         description="Choice interaction item prompt details", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="shuffle",
 *             type="boolean",
 *             
 *             description = "Can shuffle responses" 
 *         ),@SWG\Property(
 *             property="max_choice",
 *             type="integer",
 *             format="int32",
 *             description = "Max No. of choices for given question / item." 
 *
 *         )
 *        ,@SWG\Property(
 *             property="min_choice",
 *             type="integer",
 *             format="int32",
 *             description = "Min No. of choices for given question / item." 
 *         ),@SWG\Property(
 *             property="simple_choices",
 *             type="array",
 *            
 *             description = "Simple response options" ,
 *             @SWG\Items(
     *             ref="#/definitions/SimpleChoice"
     *         ) 
 *
 *         )
 * 
 *    )
/  
  
 /**
  
     *   @SWG\Definition(
 *         definition="TextEntryInteraction",
 *         description="TextEntry Interaction response details", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="resource_identifier",
 *             type="string",
 *             
 *             description = "Resource Identifier" 
 *         ),@SWG\Property(
 *             property="string_identifier",
 *             type="string",
 *             
 *             description = "String Identifier for Text items" 
 *         ),@SWG\Property(
 *             property="expected_length",
 *             type="integer",
 *             format="int32",
 *             description = "Expected length of an response / answer." 
 *
 *         ),
 *        @SWG\Property(
 *             property="pattern_mask",
 *             type="string",
 *             
 *             description = "Response / answer pattern" 
 *         ),@SWG\Property(
 *             property="place_holder_text",
 *             type="string",
 *             
 *             description = "Hint text for user" 
 *         ),@SWG\Property(
 *             property="format",
 *             type="string",
 *             
 *             description = "Response format" 
 *         )
 * 
 *    )
/  
    
 /**
  
     *   @SWG\Definition(
 *         definition="SimpleChoice",
 *         description="Simple choice item response details", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="resource_identifier",
 *             type="string",
 *             
 *             description = "resource identifier for response options" 
 *         ),@SWG\Property(
 *             property="fixed",
 *             type="boolean",
 *             
 *             description = "Is response position fixed among the given choices" 
 *         ),@SWG\Property(
 *             property="label",
 *             type="string",
 *             
 *             description = "Response label" 
 *
 *         ),
 *        @SWG\Property(
 *             property="value",
 *             type="string",
 *             
 *             description = "Response value" 
 *         ),@SWG\Property(
 *             property="place_holder_text",
 *             type="string",
 *             
 *             description = "Hint text for user" 
 *         ),@SWG\Property(
 *             property="correct",
 *             type="boolean",
 *             
 *             description = "Is correct response"
 *         )
 * 
 *    )
/
  
 /**
 
 
  
     *   @SWG\Definition(
 *         definition="ItemDto",
 *         description="Asssessment Item (Question) information", 
 *         type= "object",
 *         
 *         @SWG\Property(
 *             property="id",
 *             type="integer",
 *             format="int32",
 *             description = "Item primary key - id" 
 *         ),
 *         @SWG\Property(
 *             property="identifier",
 *             type="string",
 *             description = "Item identifier - QTI"  
 *         ),
 *         @SWG\Property(
 *             property="item_type",
 *             type="string",
 *             description = "Item type - Multiple choice, text interaction, ..."  
 *         
 *         ),
 *         @SWG\Property(
 *             property="title",
 *             type="string",
 *             description = "Item title / name"  
 *         ),
 *         @SWG\Property(
 *             property="label",
 *             type="string",
 *             description = "Item label / description"  
 *         ),
 *         @SWG\Property(
 *             property="language",
 *             type="string",
 *             description = "Item language"  
 *         ),
 *         @SWG\Property(
 *             property="version",
 *             type="integer",
 *             format="int32",
 *             description = "Latest version of Item"  
 *         ),
 *         @SWG\Property(
 *             property="prompt_text",
 *             type="string",
 *             description = "Item prompt text"  
 *         ),
 *         @SWG\Property(
 *             property="assets",
 *             type="array",
 *             description = "Item related assets" ,
 *              @SWG\Items(
     *             ref="#/definitions/Assets"
     *         ), 
 *         ),
 *         @SWG\Property(
 *             property="score",
 *             type="integer",
 *             format="int32",
 *             description = "Score for given item"  
 *         ),
 *         @SWG\Property(
 *             property="model_feedback",
 *             type="array",
 *             description = "Feedback text" ,
 *             @SWG\Items(
     *             ref="#/definitions/ModelFeedback"
     *         ),  
 *         ),
 *         @SWG\Property(
 *             property="remediation_links",
 *             type="array",
 *             description = "Redediation links for given Item",  
 *             @SWG\Items(
     *             ref="#/definitions/RemediationLinks"
     *         ),  
 *         ),
 *         @SWG\Property(
 *             property="time_dependant",
 *             type="boolean",
 *             description = "Is question item time dependant?"  
 *         ),
 *         @SWG\Property(
 *             property="time_limit",
 *             type="integer",
 *             format="int32",
 *             description = "Time limit for Item"  
 *         ),
 *         @SWG\Property(
 *             property="adaptive",
 *             type="boolean",
 *             description = "Is Item Adaptive"  
 *         ),
 *         @SWG\Property(
 *             property="difficulty",
 *             type="integer",
 *             format="int32",
 *             description = "Item difficulty used in adaptive"  
 *         ),
 *         @SWG\Property(
 *             property="Status",
 *             type="string",
 *             description = "Item status (P - Published, A-Authoring)"  
 *         ),
 *         @SWG\Property(
 *             property="tool_name",
 *             type="string",
 *             description = "Vendor tool name"  
 *         ),
 *         @SWG\Property(
 *             property="tool_version",
 *             type="string",
 *             description = "Vendor tool version"  
 *         ),
 *         @SWG\Property(
 *             property="choice_interaction",
 *             type="array",
 *             description = "Choice interaction item type details" ,
 *             @SWG\Items(
     *             ref="#/definitions/ChoiceInteraction"
     *         ),   
 *         ),
 *         @SWG\Property(
 *             property="text_entry_interaction",
 *             type="array",
 *             description = "Text entry interaction item type details" ,
 *             @SWG\Items(
     *             ref="#/definitions/TextEntryInteraction"
     *         ),   
 *         )
 *     )
     */
   
class ItemsAdminController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

       /**
     * @SWG\Post(
     *     path="/items/",
        *  tags={"items"},
        *  summary="Create a new Assessment Item (Question)",
     *     operationId="items_PostQuestion",
     *     consumes={"application/json"},
           produces={"application/json"},
     *     @SWG\Parameter(
     *         name="item",
     *         in="body",
     *         description="Asessment Item information",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/ItemDto"
     *         ),
     *        
     *        
     *     ),
        
     *    
  
     *    
        * @SWG\Response(
     *         response=201,
     *         description="Created",
     *        
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
    public function postItem(Request $request)   {
                
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        {
          $response = array (
                             'id' => 123,
                             'uri' => 'http://'.$_SERVER['HTTP_HOST'].'/api/tests/123',
                           );  
        return new JsonResponse($response,201);
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
     * @SWG\Put(
     *     path="/items/{id}",
        *  tags={"items"},
        *  summary="Update Assessment Item (Question) information.",
     *     operationId="items_PutQuestion",
     *     consumes={"application/json"},
           produces={"application/json"},
     *     
     *    
          
          @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item Id - Primary key",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *     ),
     *    @SWG\Parameter(
     *         name="item",
     *         in="body",
     *         description="Assessement Item - Question",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/ItemDto"
     *         ),
     *        
     *        
     *     ),
     *   
        * @SWG\Response(
     *         response=204,
     *         description="NoContent",
     *        
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="NotFound",
     *        
     *     ),
     *   deprecated = false
     * )
     */
     // Sample Method to return user details
    public function updateItem(Request $request)   {
        $id =  $request->get("id");     
       
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        {
            if($id == '' || !is_numeric($id))
            {
                $errorRes = array(
                    "code"=> 2001,
                    "message"=> "The item should be numeric",
                    "description"=> "Error while updating item information.",
                    "logReference"=> "5001"
                );

                return new JsonResponse($errorRes,400);
            }
       
        return new JsonResponse('',204);
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
     * @SWG\Delete(
     *     path="/items/{id}",
        *  tags={"items"},
        *  summary="Soft delete Asssessment Item (Question)",
     *     operationId="items_DeleteQuestion",
     *     consumes={"application/json"},
           produces={"application/json"},
     *     
     *   
         @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item Id",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="OK",
     *       
     *     ),
       
     *     @SWG\Response(
     *         response="404",
     *         description="NotFound",
     *        
     *     ),
     *   deprecated = false
     * )
     */
     // Sample Method to return user details
    public function deleteItem(Request $request)   {
        $id =  $request->get("id");           
        if($_SERVER['PHP_AUTH_USER'] == BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW'] == BASIC_AUTH_PWD)
        {
        
            if($id == '' || !is_numeric($id))
            {
                $errorRes = array(
                    "code"=> 2001,
                    "message"=> "The item should be numeric",
                    "description"=> "Error while deleting item.",
                    "logReference"=> "47765"
                );

                return new JsonResponse($errorRes,400);
            }
            
        
        return new JsonResponse("",204);
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
    

}
