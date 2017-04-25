<?php

/*
 * V1.0 Silex Metadata Stubs module
 * @Author : Dhaarani S
 * @Date : 30-08-2016
 * @Puropose : Admin Controller for Metadata
 */

namespace QuizPlat\Admin\Metadata;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Classname : MetadataAdminController;
 * controller class which defines all the stubs skeleton methods.
 */

class MetadataAdminController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
     }
	
        /**
	 * @SWG\Post(
	 * 		path="/metadata",
	 *              summary="Create new metadata tag definition",
     	 * 		tags={"metadata"},
         * 		operationId="metadata_Post",
     	 * 		consumes={"application/json"},
     	 * 		produces={"application/json"}, 
     	 * @SWG\Parameter(
	 *              description="Metadata tag information",
	 *         	in="body",
	 *         	name="metadata",
	 *         	required=true,
         *              @SWG\Schema(ref="#/definitions/MetadataDto"),
	 *     		),
         * @SWG\Response(
      	 *         	response="201",
     	 *         	description="Created",
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
           *         required={"id", "tag_name","description","display_label","tag_type","data_type","mandatory","multiselect","metadata_values"},
           *         @SWG\Property(
           *             property="id",
           *             description="Metadata Tag Id",
           *             type="integer",
           *             format="int32"
           *         ),
           *         @SWG\Property(
           *             property="tag_name",
           *             description="Metadata Tag Name",
           *             type="string"
           *         ),
           *         @SWG\Property(
           *             property="description",
           *             description="Metadata Tag Description",
           *             type="string"
           *         ),
           *         @SWG\Property(
           *             property="display_label",
           *             description="Metadata Tag Display label",
           *             type="string"
           *         ),
           *         @SWG\Property(
           *             property="tag_type",
           *             description="Metadata Tag Type",
           *             enum={"Text","Lookup","Hierachy"}, 
           *             type="string"
           *         ), 
           *        @SWG\Property(
           *             property="data_type",
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
           *             property="metadata_values",
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
           *             property="metadata_id",
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
           *             property="parent_value",
           *             description="Parent Value for hierachal data",
           *             type="string"
           *         ),
           *        
           *     )
           * 
           */
          
    public function createMetadata(Request $request) {
        //Basic Authetication 
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
                   
			//Checking the post parameters passing or not
			if($request->getContent()!='')
			{ 
					$response = array (
								'id' => 123,
								'uri' => 'http://'.$_SERVER['HTTP_HOST'].'/api/metadata/123',
								);  
					   
						return new JsonResponse($response,'201');
			 }
			else
			{
				$error =  array (
											'code' => 1001,
											'message' => "Error While Creating metadata tag ",
											'description' => "Invalid Input parameter",
											'logReference' => "5023"
											) ;
				return new JsonResponse($error,'400');
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
    
      /**
	 * @SWG\Put(
	 * 		path="/metadata/{id}",
	 *              summary="Update existing metadata tag information.",
     	 * 		tags={"metadata"},
         * 		operationId="metadata_Put",
     	 * 		consumes={"application/json"},
         *              produces={"application/json"},
      	 * @SWG\Parameter(
	 *              description="Metadata Id",
	 *         	in="path",
	 *         	name="id",
	 *         	required=true,
         *              type="integer",
         *              format="int32",
         *     		),
         * @SWG\Parameter(
	 *              description="Metadata tag Information",
	 *         	in="body",
	 *         	name="metadata",
	 *         	required=true,
         *               @SWG\Schema(ref="#/definitions/MetadataDto"),
         *     		),
         * @SWG\Response(
      	 *         	response="204",
     	 *         	description="NoContent",
         *     		),
         * @SWG\Response(
      	 *         	response="404",
     	 *         	description="NotFound"
     	 *     		)
	 * 
      	 * )
         */
    public function updateMetadata(Request $request) {
        //Basic authetication
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
        {
                $metadataId = $request->get('id');
                //Check whether the id is empty and is numeric
                if(($metadataId == '') || !is_numeric($metadataId))
                   {
                         $error = array (
                                                'code' => 1001,
                                                'message' => "Error while updating metadata tag information.",
                                                'description' => "Invalid Input parameter",
                                                'logReference' => "5021"
                                                ) ;
                        return new JsonResponse($error,'400');
                   }
                   else
                   {
                       
                       return new JsonResponse("",'204');
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
    
    /**
     * @SWG\Delete(
     *     path="/metadata/{id}",
     *     summary="This method soft deletes metadata tag information based on tag id",
     *     operationId="metadata_Delete",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     tags={"metadata"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="Metadata Id",
     *         required=true,
     *         type="integer",
     *         format="int32",
     *     ),
     * @SWG\Response(
     *         	response="204",
     *         	description="NoContent",
     *     		),
     * @SWG\Response(
     *         	response="404",
     *         	description="NotFound"
     *     		)
     * )
     */   
       public function deleteMetadata(Request $request) 
       {//Basic Authentication
        if($_SERVER['PHP_AUTH_USER']== BASIC_AUTH_USER && $_SERVER['PHP_AUTH_PW']== BASIC_AUTH_PWD)
       {      
                $metadataId = $request->get('id');
                if(($metadataId == '') || !is_numeric($metadataId))
                {
                     $response = array (
                                         'code' => 1001,
                                        'message' => 'Error deleting Metadata.',
                                        'description' => 'Invalid metadata Id',
                                        'logReference' => 5071,
                                     );
                     return new JsonResponse($response,'400');
                }
                else
                {
                    return new JsonResponse("",'204');
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
