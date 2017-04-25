<?php

/*
 * V1.0 Silex Metadata Stubs module
 * @Author : Dhaarani S
 * @Date : 30-08-2016
 * @Puropose : Controller for Metadata
 */

namespace QuizPlat\EndUser\Metadata;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Classname : MetadataController;
 * controller class which defines all the stubs skeleton methods.
 */

class MetadataController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }
	
        /**
	 * @SWG\Get(
	 * 		path="/metadata",
	 *              summary="Get all metadata values available in the system",
     	 * 		tags={"metadata"},
     	 * 		operationId="metadata_Get",
     	 * 		consumes={"application/json"},
     	 * 		produces={"application/json"}, 
     	 * @SWG\Parameter(
	 *              description="Metadata tag name",
	 *         	in="query",
	 *         	name="tag_name",
	 *         	required=false,
	 *         	type="string",
	 *     		),
         * @SWG\Parameter(
	 *         	description="Metadata tag value",
	 *         	in="query",
	 *         	name="tag_value",
	 *         	required=false,
	 *         	type="string",
	 *     		),
         * @SWG\Parameter(
         *         name="sort",
         *         in="query",
         *         description="Fieldname with + or - for ascending or descending sort. Ex : -tag_name : to sort in descending order, +tag_name to sort in ascending order. Available field names : Tag name, Tag type, Data Type & Mandatory.",
         *         required=false,
         *         type="string",
         *     ),
         * @SWG\Parameter(
         *         name="page",
         *         in="query",
         *         description="Page Number for pagination",
         *         required=false,
         *         type="integer",
         *         format="int32"
         *     ),
         * @SWG\Parameter(
         *         name="per_page",
         *         in="query",
         *         description="Per page how many records to list",
         *         required=false,
         *         type="integer",
         *         format="int32"
         *     ),
         * @SWG\Response(
      	 *         	response="200",
     	 *         	description="OK",
         *          @SWG\Schema(ref="#/definitions/MetadataDto"),
     	 *     		),
	 * @SWG\Response(
      	 *         	response="404",
     	 *         	description="Not Found"
     	 *     		)
	 * 
      	 * )
         */
    
         /**
           *    @SWG\Definition(
           *         definition="MetadataDto",
           *         description="Metadata Dto class for web API interaction", 
           *         required={"id", "tagName","description","displayLabel","tagType","dataType","mandatory","multiselect","metadataValues"},
           *         @SWG\Property(
           *             property="id",
           *             description="Metadata Tag Id",
           *             type="integer",
           *             format="int32"
           *         ),
           *         @SWG\Property(
           *             property="tagName",
           *             description="Metadata Tag Name",
           *             type="string"
           *         ),
           *         @SWG\Property(
           *             property="description",
           *             description="Metadata Tag Description",
           *             type="string"
           *         ),
           *         @SWG\Property(
           *             property="displayLabel",
           *             description="Metadata Tag Display label",
           *             type="string"
           *         ),
           *         @SWG\Property(
           *             property="tagType",
           *             description="Metadata Tag Type",
           *             enum={"Text","Lookup","Hierachy"}, 
           *             type="string"
           *         ), 
           *        @SWG\Property(
           *             property="dataType",
           *             description="Metadata Datatype",
           *             enum={"String","Integer","DateTime","Bool"}, 
           *             type="string"
           *         ),
           *        @SWG\Property(
           *             property="mandatory",
           *             description="Metadata Mandatory",
           *             type="boolean"
           *         ),
           *        @SWG\Property(
           *             property="multiselect",
           *             description="Metadata Tag Multiselect",
           *             type="boolean"
           *         ), 
           *        @SWG\Property(
           *             property="metadataValues",
           *             description="List of valid options for given question",
           *             type="array",
           *             @SWG\Items(
           *                  ref="#/definitions/MetadataValues"
           *             ),
           *             )
           *     )
           * 
           */
           
          /**
           *    @SWG\Definition(
           *         definition="MetadataValues",
           *         description="Metadata values class to represent the pre-defined values for given metadata",  
           *         required={"id", "metadata_id","sequence","value","parent_value"},
           *         @SWG\Property(
           *             property="id",
           *             description="Metadata value Id",
           *             type="integer",
           *             format="int32"
           *         ),
           *         @SWG\Property(
           *             property="metadataId",
           *             description="Metadata Id",
           *             type="integer",
           *             format="int32"
           *         ),
           *         @SWG\Property(
           *             property="sequence",
           *             description="Sequence no for array of value zero based",
           *             type="integer",
           *             format="int32"
           *         ),
           *         @SWG\Property(
           *             property="value",
           *             description="Metadata value",
           *             type="string"
           *         ),
           *        @SWG\Property(
           *             property="parentValue",
           *             description="Parent Value for hierachal data",
           *             type="string"
           *         ),
           *        
           *     )
           * 
           */
    public function getMetadata(request $request){
        //Basic Authentication
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
       
                      
                                    $metadata = array(0 => array (
                                                    "id"=> 1,
                                                    "tagName"=> "Status",
                                                    "description"=> "Status metdata description",
                                                    "displayLabel"=>"Status",
                                                    "tagType"=> "Lookup",
                                                    "dataType"=> "String",
                                                    "mandatory"=> true,
                                                    "multiselect"=> false
                                            ),
                                            1 => array (
                                                    "id"=> 2,
                                                    "tagName"=> "Topics",
                                                    "description"=> "Topics metdata description",
                                                    "displayLabel"=> "Topics",
                                                    "tagType"=> "Text",
                                                    "dataType"=> "String",
                                                    "mandatory"=> true,
                                                    "multiselect"=> false
                                            ),
                                            2=>array(
                                                    "id"=> 3,
                                                    "tagName"=> "Keyword",
                                                    "description"=> "Keyword metdata description",
                                                    "displayLabel"=> "Keyword",
                                                    "tagType"=> "Lookup",
                                                    "dataType"=> "String",
                                                    "mandatory"=> true,
                                                    "multiselect"=> false
                                            ));
                          
                       
                        
                        return new JsonResponse($metadata,'200');
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
	 * 		path="/metadata/{id}", 
	 *		summary="This method returns metadata tag information for given metadata tag id.",
      	 * 		tags={"metadata"},
     	 * 		operationId="metadata_GetById",
     	 * 		consumes={"application/json"},
     	 * 		produces={"application/json"}, 
     	 * @SWG\Parameter(
	 *         	description="meta data tag id",
	 *         	in="path",
	 *         	name="id",
	 *         	required=true,
	 *         	type="integer",
	 *         	format="int32"
	 *     ),
      	 * @SWG\Response(
      	 *         	response="200",
     	 *         	description="OK",
         *              @SWG\Schema(ref="#/definitions/MetadataDto")
     	 *     		),
	 * @SWG\Response(
      	 *         	response="404",
     	 *         	description="Not Found"
       	 *     		),
	 * )
         */
	public function getMetadataById(Request $request)
	{ //Basic Authentication
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {           
                    $metadataId = $request->get('id');
                    //Check whether the metadata id is empty or numeric
                    if($metadataId == '' || !is_numeric($metadataId))
                     {
                         $error = array (
                                        'code' => 1001,
                                        'message' => "Error retrieving metadata tag information.",
                                        'description' => "Metadata tag Id should be Number",
                                        'logReference' => "5021"
                                        ) ;
                       
                        return new JsonResponse($error,'400'); 
                     }
                     else
                     {
			$metadata = array(
					"id"=> $metadataId,
					"tagName"=> "Status",
					"description"=> "Different state",
					"displayLabel"=> "Status",
					"tagType"=> "Lookup",
					"dataType"=> "String",
					"mandatory"=> true,
					"multiSelect"=> false,
					"metadataValues"=> array( 
							0 => array(
								"id"=> 1,
								"metadataId"=>3,
								"sequence"=> 0,
								"value"=> "Active"
								),
							1=> array (
								"id"=> 2,
								"metadataId"=> 3,
								"sequence"=> 1,
								"value"=> "Inprogress"),
							2=> array (
								"id"=> 3,
								"metadataId"=> 3,
								"sequence"=> 2,
								"value"=> "Inactive")
					));
                        return new JsonResponse($metadata,'200');
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
