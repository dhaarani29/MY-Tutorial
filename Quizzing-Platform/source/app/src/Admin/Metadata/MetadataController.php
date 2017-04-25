<?php

/**
 * MetadataController - Handles metadata tag module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Metadata;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/*
 * *** Custom Error Codes: ***
 * 1001:Error retrieving Metadata Type.
 * 1002:Error retrieving Metadata Data Type.
 * 1003:Error Creating Metadata.
 * 1004:Invalid input request for Metadata create.
 * 1005:Metadata Not Found.
 * 1006:Metadata request is empty.
 * 1007:Error retrieving metadata details.
 * 1008:Error Deleting the metadata.
 * 1009:Error while updating metadata.
 * 10* 10:Invalid input request for get metadata.
 * 1011:Invalid input request for Metadata update.
 * 1012:Duplicate Metadata Tag.
 * 1013:Error retrieving instituions list.
 * 1014:Error retrieving taxonomy list.
 * 1015:Invalid input request for get taxonomy.
 * 1016:Invalid input request for get subjects. 
 * 1017:Invalid input request for get snomed terms. 
 * 1011:Duplicate Metadata Tag.
 */

class MetadataController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "metadata";

        //HTTP Codes
        $this->notFound = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->created = $this->app['cache']->fetch('HTTP_CREATED');
        $this->duplicate = $this->app['cache']->fetch('HTTP_DUPLICATE');
        $this->forbidden = $this->app['cache']->fetch('HTTP_FORBIDDEN');
        $this->badrequest = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->nocontent = $this->app['cache']->fetch('HTTP_NOCONTENT');
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to Get the Metadata Tag Types(Free Text , Lookup , Hierachy)
     * @Return : array, Returns all the metadata types.
     */
    public function getMetadataTypes() {

        $listOfMetadataTypes = $this->app['metadata.service']->getMetadataTypes();
        if (!empty($listOfMetadataTypes)) {

            //Return metadata types in json format.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($listOfMetadataTypes, $this->success);
            return $response;
        } else {

            // Return customized error and store customized error in to log.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['METADATA_TYPE_ERROR']);
            return $response;
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to Get the Metadata data Types (String , Numeric , Datetime).
     * @Return : array, Returns all the metadata data types.
     */
    public function getMetadataDataTypes() {

        $listOfMetadataDataTypes = $this->app['metadata.service']->getMetadataDataTypes();
        if (!empty($listOfMetadataDataTypes)) {

            // Return metadata data types in json format
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($listOfMetadataDataTypes, $this->success);
            return $response;
        } else {

            // Retrun customized error and store customized error in to log.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['METADATA_DATA_TYPE_ERROR']);
            return $response;
        }
    }

    /**
     * @param : Metadata request input as defined above.
     * @Desc : Controller method to create the metadata tag in Free text, Lookup,Hierarchy method.
     * @Return : id, Returns last created metadata tag.
     */
    public function createMetadata(Request $request) {

        if (!empty($request->getContent())) {

            $metadata = json_decode($request->getContent(), true);
            $userId = $metadata['userId'];

            if ($userId) {

                // Check user has permission to create metadata tag.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $metadata['info'] = "Request Data : ";
                    $this->app['log']->writeLog($metadata);

                    //Create the metadata based on the Request
                    $createdId = $this->app['metadata.service']->create($metadata);

                    if (is_int($createdId)) {

                        //After successful insertion , return newly created Metadata ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If metadata exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_METADATA_TAG_ERROR']);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['CREATE_METADATA_ERROR']);
                        return $response;
                    }
                } else {
                    // If user doesn't have permission to create metadata then return permission error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_METADATA_REQUEST_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['EMPTY_METADATA_ERROR']);
            return $response;
        }
    }

    /**
     * @param : Metadata id as request param.
     * @Desc : Controller method to get metadata tag by id.
     * @Return : array, Returns metadata tag details.
     */
    public function getMetadataById(Request $request) {

        $metadataId = $request->get('id');
        $metadataParentId = $request->get('metadataValueId');

        //Metadata Id is Mandatory
        if ($metadataId != "") {

            $getMetadata = $request->query->all();
            $requestFrom = $request->headers->get('requestFrom');

            if (empty($requestFrom)) {//Metadata value response for end user
                $accessToken = $request->headers->get('Authorization');
                $clientId = $this->app['login.service']->getClientId($accessToken); //Parse client Id from access token
                // Get QP userId for the requested clientUserId
                $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $getMetadata['clientUserId']);

                if ($clientId && $getMetadata['metadataValueId'] && $getMetadata['clientUserId']) {
                    $metadataList = $this->app['metadata.service']->getTaxonomyList($userId, $clientId, $metadataId, $getMetadata);
                    if (!empty($metadataList)) {
                        // Return the configurations.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($metadataList, $this->success);
                        return $response;
                    } else {
                        // If any errors returned, return the customized error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['METADATA_ERROR']);
                        return $response;
                    }
                } else {
                    // If input request is not valid then return fallowing error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_GET_METADATA_REQUEST_ERROR']);
                    return $response;
                }
            } else {

                $userId = $getMetadata['userId'];

                // Check user has permission to edit metadata tag.
                //$hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');
                $hasPermission = 1;
                if ($hasPermission) {

                    $metadataTagArray = $this->app['metadata.service']->find($metadataId, $metadataParentId);

                    if (!empty($metadataTagArray)) {

                        // Return metadata details.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($metadataTagArray, $this->success);
                        return $response;
                    } else {

                        // Return following error if metadata not exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['METADATA_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to view metadata then return following error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            }
        }
    }

    /**
     * @param : metadata_tag_name,metadat_tag_value.
     * @Desc : Get all the metadata tag based on the request params.
     * @Return : array, Returns metadata tag details.
     */
    public function getMetadata(Request $request) {

        $metadataRequest = $request->query->all();

        $userId = $metadataRequest['userId'];

        // Check user has permission to view metadata tag.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

        if ($hasPermission) {

            $metadataTagArray = $this->app['metadata.service']->getMetadata($metadataRequest);

            // Return metadata list.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($metadataTagArray, $this->success);
            return $response;

            // Return follwoing error if any error occurs while retrieving metadata.
            /* $response = $this->app['systemsettings.controller']->returnErrorResponse(1007, "Error retrieving Metadata Tag List", "Error retrieving Metadata Tag List", $this->notFound);
              return $response; */
        } else {

            // Return following error if your doesn't have permission to view the metadata.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * @param : Metadata request input as defined above.
     * @Desc : Controller method to create the metadata tag in Free text, Lookup,Hierarchy method.
     * @Return : id, Returns last updated metadata tag.
     */
    public function updateMetadata(Request $request) {

        $metadataId = $request->get('id');

        if ($metadataId != "") {

            $updateMetadata = json_decode($request->getContent(), true);
            $updateMetadata['info'] = "Update request Data : ";
            $this->app['log']->writeLog($updateMetadata);

            $userId = $updateMetadata['userId'];

            if ($userId) {

                // Check user has permission to edit metadata tag.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'edit');

                if ($hasPermission) {

                    // Check wether metadata exists to edit.
                    $metdataValue = $this->app['metadata.service']->find($metadataId);

                    if (!empty($metdataValue)) {

                        // If metadata exists then update the metadata.
                        $updated = $this->app['metadata.service']->update($metdataValue, $updateMetadata);

                        if ($updated === true) {

                            // Return if successful update.

                            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                            return $response;
                        } elseif ($updated == $this->app['cache']->fetch('exists')) {

                            // Return following error if metadata exists already for different id.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_METADATA_TAG_ERROR']);
                            return $response;
                        } else {

                            // Return following error if any error occurs while updating the metadata.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['UPDATE_METADATA_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if metadata doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['METADATA_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if user doesn't have permission to edit the metadata.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_METADATA_UPDATE_REQUEST_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @param : Metadata id as request param.
     * @Desc : Controller method to soft delete the metadata tag.
     * @Return : Returns true if deleted.
     */
    public function deleteMetadata(Request $request) {

        $metadataId = $request->get('id'); //metadata Id

        if ($metadataId != "") {

            $deleteMetadata = $request->query->all();

            $userId = $deleteMetadata['userId'];

            // Check user has permission to delete metadata tag.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'delete');

            if ($hasPermission) {

                // soft delete the Metadata tag
                $response = $this->app['metadata.service']->delete($metadataId);

                //If the response is True , Deleted Successfully
                if ($response === true) {

                    // Return if successfully deleted the metadata tag.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                    return $response;
                } else {

                    // Return following error if any error occurs while deleting the metadata.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DELETE_METADATA_ERROR']);
                    return $response;
                }
            } else {

                // Return following error if user doen't have permission to delete metadata.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to Get the Mandatory Metadata Tags.
     * @Return : array, Returns all the mandatory metadata tags.
     */
    public function getMandatoryMetadata() {

        $mandatoryMetadata = $this->app['metadata.service']->getMetadataDetails();

        //Return metadata types in json format.
        $response = $this->app['systemsettings.controller']->returnSuccessResponse($mandatoryMetadata, $this->success);
        return $response;
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to get the instituions list
     * @Return : array, list of institutions
     */
    public function getInstitutions() {

        $instituionsList = $this->app['metadata.service']->getInstitutions();

        if (!empty($instituionsList)) {

            // Return the configurations.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($instituionsList, $this->success);
            return $response;
        } else {

            // If any errors returned, return the customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INSTITUTION_LIST_ERROR']);
            return $response;
        }
    }

    /**
     * @param : productIds & metadataId as request parameter
     * @Desc : Controller method to get the list of subjects/taxonomy first level
     * @Return : array, list of subjects
     */
    public function getSubjects(Request $request) {
        $accessToken = $request->headers->get('Authorization');
        $clientId = $this->app['login.service']->getClientId($accessToken); //Parse client Id from access token
        $productIds = $request->get('productId'); //productId
        $randomMetadataId = $request->get('metadataId'); //randomMetadataId
        //$clientUserId       = $request->query->get('clientUserId');
        $metadataId = $this->app['metadata.service']->getclientMetadataId($clientId, $randomMetadataId); //Get actual client metadata id using clientId and randomclient metadataId
        $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $getMetadata['clientUserId']); // Get QP userId for the requested clientUserId

        if ($clientId && $metadataId && $productIds) {
            $subjectList = $this->app['metadata.service']->getSubjects($productIds, $clientId, $userId, $metadataId);
            // Return the list of subjects.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($subjectList, $this->success);
            return $response;
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_SUBJECT_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * @param : taxonomyId(snomed concept id/QB metadata value id) and taxonomyType as request parameter
     * @Desc : Controller method to get the list of snomed concept terms
     * @Return : array, list of snomed concept terms
     */
    public function getSnomedTerms(Request $request) {
        $taxonomyId = explode(',', $request->get('taxonomyId')); //productId
        $taxonomyType = $request->get('taxonomyType'); //randomMetadataId
        $limitLevel = $request->get('level'); //randomMetadataId

        $idTypeList = array('QB', 'snomed');

        if ($taxonomyId && $taxonomyType && in_array($taxonomyType, $idTypeList)) {
            $termList = $this->app['metadata.service']->getSnomedTerms($taxonomyId, $taxonomyType, $limitLevel);
            // Return the list of subjects.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($termList, $this->success);
            return $response;
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_SNOMED_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * solr indexing the metadata values of all Metadata Types
     * @return type
     */
    public function metadataSolrIndex() {
        $indexingResponse = $this->app['metadata.service']->metadataSolrIndex();
        if ($indexingResponse) {
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($indexingResponse, $this->success);
        } else {
            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INDEX_SOLR_ERROR']);
            return $response;
        }
        return $response;
    }

    /**
     * Solr Search for metadata / taxonomy
     * @param Request $request
     * @return type
     */
    public function metadataSolrSearch(Request $request) {

        //Get taxonomy Name
        $taxonomyName = trim($request->query->get('taxonomyName'));

        //Get metadata Type
        $metadataType = trim($request->query->get('metadataType'));

        //Get metadataId
        $metadataId = trim($request->query->get('metadataId'));

        $searchResults = $this->app['offlinescripts.service']->metadataSolrSearch($taxonomyName, $metadataType, $metadataId);

        if (!empty($searchResults)) {

            // Return the success message. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($searchResults, $this->success);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['READ_SOLR_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc Get subject and topics list along with thier test progress details for the given product ids.
     * @param Request $request
     * @return type
     */
    public function getTaxonomyWithProgress(Request $request) {

        $accessToken = $request->headers->get('Authorization');
        $getMetadata = $request->query->all();

        $clientId = $this->app['login.service']->getClientId($accessToken); //Parse client Id from access token 
        $productIds = $request->get('productIds'); //productId
        $randomMetadataId = $request->get('clientMetadataId'); //randomMetadataId
        $clientUserId = $getMetadata['clientUserId'];
        $metadataId = $this->app['metadata.service']->getclientMetadataId($clientId, $randomMetadataId); //Get actual client metadata id using clientId and randomclient metadataId
        $userId = $this->app['login.service']->getUserIdFromToken($accessToken, $clientUserId); // Get QP userId for the requested clientUserId

        if ($userId && $metadataId && $productIds && $clientUserId) {

            $subjectList = $this->app['metadata.service']->getTaxonomyWithProgress($productIds, $userId);
            // Return the list of subjects.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($subjectList, $this->success);
            return $response;
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_SUBJECT_REQUEST_ERROR']);
            return $response;
        }
    }

    /*
     * @Desc get all product.
     * @param Request $request
     * @return type
     */

    public function getAllProducts(Request $request) {
        $accessToken = $request->headers->get('Authorization');
        $clientId = $this->app['login.service']->getClientId($accessToken); //Parse client Id from access token

        if ($clientId) {
            $productList = $this->app['metadata.service']->getAllProducts($clientId);
            // Return the list of subjects.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($productList, $this->success);
            return $response;
        } else {
            // If input request is not valid then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_PRODUCT_REQUEST_ERROR']);
            return $response;
        }
    }

}
