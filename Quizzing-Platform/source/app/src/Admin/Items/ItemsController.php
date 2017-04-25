<?php

/**
 * ItemsController - Handles Question/Item module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Items;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/*
 * *** Custom Error Codes: ***
 * 2001:Error retrieving question types.
 * 2002:Error retrieving remediation link types.
 * 2003:Error Creating Question.
 * 2004:Invalid input request for Question create.
 * 2005:Question create request is empty.
 * 2006:Invalid input request for Question update.
 * 2007:Question not found.
 * 2008:Empty asset upload.
 * 2009:Error Deleting the Question.
 * 2010:Error while updating Question.
 * 2011:Failed to Publish the Question.
 * 2012:Error retrieving Asset Types.
 * 2013:Duplicate item Association.
 * 2014:Error while Associating the item.
 * 1010:Don't have permission. Please contact WK admin
 */

class ItemsController {

    protected $app;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "items";

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
     * @param : No parameters
     * @Desc : Controller method to request the items.service to fetch Item/Question types list. 
     * @Return : Returns all the questin types.
     */
    public function getItemTypes() {

        $itemTypes = $this->app['items.service']->getAllItemTypes();

        if (!empty($itemTypes)) {

            // Return the Question types. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemTypes, $this->success);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUESTION_TYPE_ERROR']);
            return $response;
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to request the items.service to fetch remediation link types list. 
     * @Return : Returns all the remediation link types.
     */
    public function getRemediationLinkTypes() {

        $remediationLinkTypes = $this->app['items.service']->getRemediationLinkTypes();

        if (!empty($remediationLinkTypes)) {

            // Return the Remediation link types. 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($remediationLinkTypes, $this->success);
            return $response;
        } else {

            // If any errors occurred, return the following customized error. 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['REMEDIATION_LINK_ERROR']);
            return $response;
        }
    }

    /**
     * @param : Items request input as defined above.
     * @Desc : Controller method to create the Items/Questions.
     * @Return : id, Returns last created item/question.
     */
    public function createItem(Request $request) {

        if (!empty($request->getContent())) {

            $itemsData = json_decode($request->getContent(), true);

            $userId = $itemsData['userId'];

            if ($userId) {

                // Check user has permission to create item/question.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $itemsData['info'] = "Request Data : ";
                    $this->app['log']->writeLog($itemsData);

                    //Create the question/item based on the Request
                    $createdId = $this->app['items.service']->create($itemsData);

                    if ($createdId) {

                        //After successful insertion , return newly created question ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['CREATE_QUESTION_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_CREATE_QUESTION_REQUEST_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['EMPTY_CREATE_QUESTION_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * @param : Items request input as defined above.
     * @Desc : Controller method to create the Items/Questions.
     * @Return : id, Returns last created item/question.
     */
    public function updateItem(Request $request) {

        $itemId = $request->get('id');

        if ($itemId != "") {

            $itemsData = json_decode($request->getContent(), true);
            $itemsData['info'] = "Update request Data : ";
            $this->app['log']->writeLog($itemsData);

            $userId = $itemsData['userId'];

            if ($userId) {

                // Check user has permission to create item/question.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'edit');

                if ($hasPermission) {

                    //check item exists
                    $itemValue = $this->app['items.service']->itemsExists($itemId);

                    if (!empty($itemValue)) {

                        // If item exists then create the item with new version and old identifier.
                        $createdId = $this->app['items.service']->create($itemsData, $itemId);

                        if ($createdId) {

                            //After successful insertion , return newly created question ID.
                            $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                            return $response;
                        } else {

                            // Return following error if any error occurs while updating the item.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['UPDATE_QUESTION_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if item doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUESTION_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_UPDATE_QUESTION_REQUEST_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @param : Item/Question id as request param.
     * @Desc : Controller method to fetch item by id.
     * @Return : array, Returns item/question details.
     */
    public function getItemById(Request $request) {

        $itemId = $request->get('id');

        //Item Id is Mandatory
        if ($itemId != "") {

            $getItem = $request->query->all();

            $userId = $getItem['userId'];
            $version = $getItem['version'];

            // Check user has permission to edit item .
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

            if ($hasPermission) {

                $itemData = $this->app['items.service']->find($itemId, $version);

                if (!empty($itemData)) {

                    // Return item details.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemData, $this->success);
                    return $response;
                } else {

                    // Return following error if item doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUESTION_NOT_FOUND_ERROR']);
                    return $response;
                }
            } else {

                // If user doesn't have permission to view item then return following error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @param : item id as request param.
     * @Desc : Controller method to soft delete the item.
     * @Return : Returns true if deleted.
     */
    public function deleteItem(Request $request) {

        $itemId = $request->get('id'); //item Id

        if ($itemId != "") {

            $deleteItem = $request->query->all();

            $userId = $deleteItem['userId'];
            $isDeleteAll = $deleteItem['isDeleteAll'];
            $version = $deleteItem['version'];

            // Check user has permission to delete item.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'delete');

            if ($hasPermission) {

                // soft delete the item
                $response = $this->app['items.service']->delete($itemId, strtolower($isDeleteAll), $version);

                //If the response is True , Deleted Successfully
                if ($response === true) {
                    // Return if successfully deleted the item.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                    return $response;
                } if (!empty($response)) {

                    // If delete all versions are passed then return the status for each question version along with if any associations
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($response, $this->success);
                    return $response;
                } else {

                    // Return following error if any error occurs while deleting the item.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['DELETE_QUESTION_ERROR']);
                    return $response;
                }
            } else {

                // Return following error if user doen't have permission to delete item.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @Desc : Get all recent versions items for listing
     * @param Request $request
     * @return type
     */
    public function getItemsList(Request $request) {

        $itemRequest = $request->query->all();

        // Metadata filters will be json encoded requests
        $metadataRequest = json_decode($itemRequest['metadataAssoc'], true);

        $userId = $itemRequest['userId'];

        // Check user has permission to view items list.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

        if ($hasPermission) {

            $itemsArray = $this->app['items.service']->getItemsList($itemRequest, $metadataRequest);

            // Return items list.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemsArray, $this->success);
            return $response;
        } else {

            // Return following error if your doesn't have permission to view the item.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * @param : Item/Question id as request param.
     * @Desc : Controller method to publish the item based on item ID.
     * @Return : boolean.
     */
    public function publishItem(Request $request) {

        $itemId = $request->get('id');

        //Item Id is Mandatory
        if ($itemId != "") {

            $getItem = $request->query->all();

            $userId = $getItem['userId'];

            // Check user has permission to public the  item .
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'manageSecurity');

            if ($hasPermission) {

                //check item exists
                $itemValue = $this->app['items.service']->itemsExists($itemId);

                if (!empty($itemValue)) {

                    // If item exists then create the item with new version and old identifier.
                    $publish = $this->app['items.service']->publish($itemId);

                    if ($publish === true) {
                        // Return publish status.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($publish, $this->success);
                        return $response;
                    } else {
                        // Return following error if failed to publish the item
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['PUBLISH_QUESTION_FAILED_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if item doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUESTION_NOT_FOUND_ERROR']);
                    return $response;
                }
            } else {

                // If user doesn't have permission to view item then return following error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @Desc Uploading the assets to temp directory and return the unique name of a file along with temp path.
     * @param Request $request
     * @return type
     */
    public function assetTempUpload(Request $request) {

        if (!empty($request->getContent())) {

            //Get the request body
            $fileContent = json_decode($request->getContent(), true);

            // Call service function to Upload the asset to details to temp folder
            $assetDetails = $this->app['items.service']->assetTempUpload($fileContent);

            //return the temporary filename
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($assetDetails, $this->success);
            return $response;
        } else {

            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['EMPTY_ASSET_UPLOAD_ERROR']);
            return $response;
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to Get the assets Types(Image,Video,Audio)
     * @Return : array, Returns all the assets types.
     */
    public function assetTypes() {

        $assetTypes = $this->app['items.service']->getAssetTypes();
        if (!empty($assetTypes)) {

            //Return assets types in json format.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($assetTypes, $this->success);
            return $response;
        } else {

            // Return customized error and store customized error in to log.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ASSET_TYPE_ERROR']);
            return $response;
        }
    }

    /**
     * Associate/Dissociate item to itemcollection
     * @param Request $request
     * @return type
     */
    public function associateItem(Request $request) {

        //get the associate item id from url
        $itemId = $request->get('id');

        if ($itemId != "") {

            //Get the associate item details and item bank details from post parameters
            $itemAssociateData = json_decode($request->getContent(), true);
            $itemAssociateData['info'] = "Associate Item Data : ";
            $this->app['log']->writeLog($itemAssociateData);
            //Get logged in userid 
            $userId = $itemAssociateData['userId'];

            if ($userId) {

                // Check user has permission to Associate/Dissociate item to itemcollection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'manageAssociation');

                if ($hasPermission) {
                    //Check only active item
                    $inActive = 1;
                    //check item exists which is not deleted and inactive
                    $itemValue = $this->app['items.service']->itemsExists($itemId, $inActive);

                    if (!empty($itemValue)) {

                        // If item exists then associate the item to itemcollection
                        $response = $this->app['items.service']->associateItem($itemAssociateData, $itemId);

                        if ($response === true) {

                            //After successful Association
                            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                            return $response;

                        } elseif ($response == $this->app['cache']->fetch('exists')) {

                            // If item Association exists then return duplicate  customized error.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_ASSOC_ITEM_ERROR']);
                            return $response;
                        } else {

                            // Return following error if any error occurs while Associate/Dissociate the item.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ASSOCIATING_ITEM_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if item doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['QUESTION_NOT_FOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to associate the item to itemcollection then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return following error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_UPDATE_QUESTION_REQUEST_ERROR']);
                return $response;
            }
        }
    }

    /**
     * Get the item collection by item Id
     * @param Request $request
     * @return type
     */
    public function getItemcollectionById(Request $request) {

        $itemId = $request->get('id');

        //Item Id is Mandatory
        if ($itemId != "") {

            $getItem = $request->query->all();

            $userId = $getItem['userId'];

            // Check user has permission to Associate the item .
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'manageAssociation');

            if ($hasPermission) {

                //get the item collection details based on the item id
                $itemCollectionData = $this->app['items.service']->findItemCollection($itemId);

                //Return item collection details.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemCollectionData, $this->success);
                return $response;
            } else {

                // If user doesn't have permission to view item then return following error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    public function updateItemId() {
        $itemId = $this->app['items.repository']->updateItemIdByIdentifier();
        return $itemId;
    }

}
