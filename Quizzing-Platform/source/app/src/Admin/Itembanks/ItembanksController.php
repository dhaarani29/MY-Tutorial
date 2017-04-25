<?php

/**
 * ItembanksController - Handles Itembanks module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Itembanks;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/*
 * *** Custom Error Codes: ***
 * 3001:Duplicate question collection name is not allowed.
 * 3002:Error Creating Item Collection.
 * 3003:Invalid input request for Item collection create.
 * 3004:Item Collection create request is empty.
 * 3005:Item collection Not Found.
 * 3006:Error while updating Item collection.
 * 3007:Invalid input request for Item collection update.", 'Description'=>"Invalid input request for Item collection update.");
 * 3008:Error Deleting the item collection.
 * 3009:Invalid Item collection upload file chunk.
 * 1010:Don't have permission. Please contact WK admin
 */

class ItembanksController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "itembanks";

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
     * Get All the item collection information
     * @param Request $request
     * @return type
     */
    public function getItemcollection(Request $request) {

        $itemCollectionRequest = $request->query->all();

        // Metadata filters will be json encoded requests       
        $metadataRequest = json_decode($itemCollectionRequest['metadataAssoc'], true);

        //Associated item collection filter
        $associatedItemCollection = $itemCollectionRequest['associated'];

        //Associated item id
        $associatedItemId = $itemCollectionRequest['itemId'];
        //Display all item collection
        $allItemCollection = $itemCollectionRequest['allItemCollection'];
        $userId = $itemCollectionRequest['userId'];

        // Check user has permission to view item collection details.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

        if ($hasPermission) {

            $itemCollectionValueArray = $this->app['itemcollection.service']->getItemcollection($itemCollectionRequest, $metadataRequest, $associatedItemCollection, $associatedItemId, $allItemCollection);

            // Return item collection list.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemCollectionValueArray, $this->success);
            return $response;
        } else {

            // Return following error if your doesn't have permission to view the item collection.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Create item bank details
     * @param Request $request
     * @return type
     */
    public function createItemcollection(Request $request) {

        if (!empty($request->getContent())) {

            $itemcollectionData = json_decode($request->getContent(), true);

            $userId = $itemcollectionData['userId'];

            if ($userId) {

                // Check user has permission to create item collection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $itemcollectionData['info'] = "Request Data : ";
                    $this->app['log']->writeLog($itemcollectionData);

                    //Create the item collection based on the Request
                    $createdId = $this->app['itemcollection.service']->create($itemcollectionData);

                    if (is_int($createdId)) {

                        //After successful insertion , return newly created item collection ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If item collection Association/item collection name exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_QUESTION_NAME_ERROR']);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_CREATION_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_ITEM_COLLECTION_CREATE_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['CREATE_ITEM_COLLECTION_EMPTY_ERROR']);
            return $response;
        }
    }

    /**
     * Get item collection by item bank id
     * @param Request $request
     * @return type
     */
    public function getItemcollectionById(Request $request) {

        $itemBankId = $request->get('id');

        //Item Id is Mandatory
        if ($itemBankId != "") {

            $getItemCollection = $request->query->all();

            $userId = $getItemCollection['userId'];

            // Check user has permission to edit item .
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

            if ($hasPermission) {

                $itemCollectionData = $this->app['itemcollection.service']->find($itemBankId);

                if (!empty($itemCollectionData)) {

                    // Return item collection details.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemCollectionData, $this->success);
                    return $response;
                } else {

                    // Return following error if item doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_NOTFOUND_ERROR']);
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
     * Update the Item bank details
     * @param Request $request
     * @return type
     */
    public function updateItemcollection(Request $request) {

        $itemBankId = $request->get('id');

        if ($itemBankId != "") {

            $itemcollectionData = json_decode($request->getContent(), true);
            $itemcollectionData['info'] = "Update request Data : ";
            $this->app['log']->writeLog($itemcollectionData);

            $userId = $itemcollectionData['userId'];

            if ($userId) {

                // Check user has permission to edit the item collection
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'edit');

                if ($hasPermission) {

                    //check item collection exists
                    $itemcollectionValue = $this->app['itemcollection.service']->find($itemBankId);

                    if (!empty($itemcollectionValue)) {

                        // If item collection exists then update it.
                        $response = $this->app['itemcollection.service']->update($itemcollectionData, $itemBankId);

                        if ($response === true) {

                            //After successful insertion , return newly created question ID.
                            $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                            return $response;
                        } elseif ($response == $this->app['cache']->fetch('exists')) {

                            // If item collection Association/item collection name exists then return duplicate  customized error.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_QUESTION_NAME_ERROR']);
                            return $response;
                        } else {

                            // Return following error if any error occurs while updating the Item bank.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_UPDATE_ERROR']);
                            return $response;
                        }
                    } else {

                        // Return following error if Item bank doesn't exists.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_NOTFOUND_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to update Item bank then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_ITEM_COLLECTION_UPDATE_ERROR']);
                return $response;
            }
        }
    }

    /**
     * Delete the Item bank
     * @param Request $request
     * @return type
     */
    public function deleteItemcollection(Request $request) {

        $itemBankId = $request->get('id'); //item Id

        if ($itemBankId != "") {

            $deleteItemcollection = $request->query->all();

            $userId = $deleteItemcollection['userId'];

            // Check user has permission to delete item collection.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'delete');

            if ($hasPermission) {

                //check item collection exists
                $itemcollectionValue = $this->app['itemcollection.service']->find($itemBankId);

                if (!empty($itemcollectionValue)) {

                    // soft delete the item collection
                    $response = $this->app['itemcollection.service']->delete($itemBankId);

                    //If the response is True , Deleted Successfully
                    if ($response === true) {

                        // Return if successfully deleted the item collection.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                        return $response;
                    } else {

                        // Return following error if any error occurs while deleting the item collection..
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_DELETE_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if item doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_NOTFOUND_ERROR']);
                    return $response;
                }
            } else {

                // Return following error if user doen't have permission to delete item collection..
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * Get the item list for Association
     * @param Request $request
     * @return type
     */
    public function getItemList(Request $request) {


        $itemCollectionRequest = $request->query->all();

        // Metadata filters will be json encoded requests       
        $metadataRequest = json_decode($itemCollectionRequest['metadataAssoc'], true);

        $userId = $itemCollectionRequest['userId'];

        // Check user has permission to view role details.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

        if ($hasPermission) {

            $itemCollectionValueArray = $this->app['itemcollection.service']->getItemlist($itemCollectionRequest, $metadataRequest);

            // Return items list for association.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemCollectionValueArray, $this->success);
            return $response;
        } else {

            // Return following error if your doesn't have permission to view the metadata.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Upload the item collection
     * @param Request $request
     * @return type
     */
    public function uploadItemcollection(Request $request) {

        // Set Amazon s3 credentials
        $client = S3Client::factory(array(
                    'version' => 'latest',
                    'region' => 'us-east-1',
                    'credentials' => array(
                        'key' => "AKIAJ24PWCH7FY5RPJIA",
                        'secret' => "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"
                    )
        ));
        // ::factory(
        // array(
        // 'key'    => "AKIAJ24PWCH7FY5RPJIA",
        // 'secret' => "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",
//         'region' => 'us-east-1',
// 'version' => 'latest'        
        //  )
        // );
        $result = $client->listBuckets();
//print_r($result);die;
        $bucket = "quizzing-platform-stg";
        $iterator = $client->getIterator('ListObjects', array(
            'Bucket' => $bucket
        ));

        foreach ($iterator as $object) {
            echo $object['Key'] . "\n";
        }die;
    }

// foreach ($result['Buckets'] as $bucket) {
//     // Each Bucket value will contain a Name and CreationDate
//     echo "{$bucket['Name']} - {$bucket['CreationDate']}\n";
// }die;
    //$questinArray = $this->app['itemcollection.service']->parseItemXML($fileContent);

    public function uploadItemcollectionFile(Request $request) {
        //echo phpinfo(); die;
        $requestData = $request->request->all();
        //print_r($requestData);die;  
        if ($requestData['flowTotalChunks'] === $requestData['flowChunkNumber']) {
            //Extract the file extension 
            $fileExtension = pathinfo($requestData['flowFilename'], PATHINFO_EXTENSION);

            $requestData['tmpFileName'] = md5(uniqid()) . '.' . $fileExtension;
        }
        $chunkStatus = $this->app['itemcollection.service']->createChunkFile($requestData);

        if ($chunkStatus == "completed") {
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($requestData, $this->success);
            return $response;
        } else if ($chunkStatus == "badrequest") {
            // If the current chunk request is invalid and try to retry by badrequest.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_FILE_ITEM_COLLECTION_ERROR']);
            return $response;
        } else if ($chunkStatus == "continue") {
            //If the current chunk is not last and successfully pushed 
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($requestData, $this->success);
            return $response;
        }
    }

    public function createItemcollectionByUpload(Request $request) {
        //echo phpinfo();die;
        //var_dump(class_exists('ZipArchive'));die;
        $itemcollectionData = json_decode($request->getContent(), true);
        //print_r($itemcollectionData);die;
        $userId = $itemcollectionData['userId'];
        if (!empty($request->getContent())) {
            if ($userId) {
                // Check user has permission to create item collection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');
                if ($hasPermission) {
                    $itemcollectionData['description'] = $itemcollectionData['itemBankName'];
                    //Create the item collection based on the Request
                    if ($itemcollectionData['selectBankType'] == $this->app['cache']->fetch('ACTIVE')) {
                        $createdId = $this->app['itemcollection.service']->create($itemcollectionData);
                    } else {
                        $createdId = intval($itemcollectionData['itemBankExistingId']);
                    }
                    //echo "%%%".intval($createdId);die;
                    if (is_int($createdId)) {
                        $itemcollectionData['tmpFileName'];

                        $extractResponse = $this->app['itemcollection.service']->extractFile($itemcollectionData['tmpFileName']);

                        $itemcollectionData['itemBankId'] = $createdId;

                        //$UploadId = $this->app['itemcollection.repository']->insertUploadDetails($itemcollectionData);
                        $UploadResponse = $this->app['itemcollection.service']->insertUploadDetails($itemcollectionData);


                        //After successful insertion , return newly created item collection ID.
                        if ($extractResponse && $UploadResponse == 1) {
                            //insert upload detils in qtiitembankupload table    

                            $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                            return $response;
                        } else {
                            if (empty($itemcollectionData['itemBankExistingId'])) {
                                if ($UploadResponse == $this->app['cache']->fetch('exists')) {


                                    // If any error occurs while creating, then return common error. 
                                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_UPLOADING_NEWBANK_FILE']);
                                    return $response;
                                } elseif ($UploadResponse == $this->app['cache']->fetch('notexists')) {
                                    // If any error occurs while creating, then return common error. 
                                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['UPLOAD_NEWBANK_FILE_NOT_EXIST']);
                                    return $response;
                                }
                            } else {
                                if ($UploadResponse == $this->app['cache']->fetch('exists')) {


                                    // If any error occurs while creating, then return common error. 
                                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_UPLOADING_EXISTBANK_FILE']);
                                    return $response;
                                } elseif ($UploadResponse == $this->app['cache']->fetch('notexists')) {
                                    // If any error occurs while creating, then return common error. 
                                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['UPLOAD_EXISTBANK_FILE_NOT_EXIST']);
                                    return $response;
                                }
                            }
                        }
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If item collection Association/item collection name exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_QUESTION_NAME_ERROR']);
                        return $response;
                    } else {
                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_CREATION_ERROR']);
                        return $response;
                    }
                } else {
                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_ITEM_COLLECTION_CREATE_ERROR']);
                return $response;
            }
        } else {
            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['CREATE_ITEM_COLLECTION_EMPTY_ERROR']);
            return $response;
        }
    }

    /**
     * Get All the item collection information
     * @param Request $request
     * @return type
     */
    public function getItembanklist(Request $request) {
        $itemCollectionRequest = $request->query->all();

        // Metadata filters will be json encoded requests       
        $metadataRequest = json_decode($itemCollectionRequest['metadataAssoc'], true);
        $userId = $itemCollectionRequest['userId'];

        // Check user has permission to view item collection details.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

        if ($hasPermission) {
            $itemCollectionValueArray = $this->app['itemcollection.service']->getItembankList($itemCollectionRequest, $metadataRequest);

            // Return item collection list.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($itemCollectionValueArray, $this->success);
            return $response;
        } else {
            // Return following error if your doesn't have permission to view the item collection.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    public function parseItems() {
        $responselist = $this->app['itemcollection.service']->createUploadedItem();
        echo "<pre>";
        print_r($responselist);
        die;
//        // Return item collection list.
//        $response = $this->app['systemsettings.controller']->returnSuccessResponse($responselist, $this->success);
//        return $response;
    }

    /*
     * By srilakshmi R
     * to publish the itembank which is having imported status
     * along with bank, questions associated will also get published
     */

    public function publishItemCollection(Request $request) {

        $itemCollectionId = $request->get('id');


        //Item Id is Mandatory
        if ($itemCollectionId != "") {

            $getItem = $request->query->all();

            $userId = $getItem['userId'];

            // Check user has permission to public the  item .
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'manageSecurity');

            if ($hasPermission) {

                //check item exists
                $itemCollectionValue = $this->app['itemcollection.service']->itemCollectionExists($itemCollectionId);

                if (!empty($itemCollectionValue)) {

                    // If item exists then create the item with new version and old identifier.

                    $publish = $this->app['itemcollection.repository']->publish($itemCollectionId, $getItem);


                    if ($publish === true) {
                        // Return publish status.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($publish, $this->success);
                        return $response;
                    } else {
                        // Return following error if failed to publish the item
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['PUBLISH_BANK_FAILED']);
                        return $response;
                    }
                } else {

                    // Return following error if item doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_NOTFOUND_ERROR']);
                    return $response;
                }
            } else {

                // If user doesn't have permission to view item then return following error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /*
     * By srilakshmi R
     * to publish the itembank which is having imported status
     * along with bank, questions associated will also get published
     */

    public function importstatus(Request $request) {

        $itemCollectionId = $request->get('id');

        //Item Id is Mandatory
        if ($itemCollectionId != "") {

            $getItem = $request->query->all();

            $userId = $getItem['userId'];

            // Check user has permission to public the  item .
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'edit');

            if ($hasPermission) {

                //check item exists
                $itemCollectionValue = $this->app['itemcollection.repository']->itemCollectionExists($itemCollectionId);
                if (!empty($itemCollectionValue)) {

                    // If item exists then create the item with new version and old identifier.
                    $publish = $this->app['itemcollection.service']->getImportStatus($itemCollectionId, $getItem);

                    if (!empty($publish)) {
                        // Return publish status.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($publish, $this->success);
                        return $response;
                    } else {
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($this->notFound, $this->app['IMPORTSTATUS_BANK_FAILED']);
                        return $response;
                    }
                } else {

                    // Return following error if item doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_NOTFOUND_ERROR']);
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
     * @Desc Export the item bank to xml
     * @param Request $request
     * @return type
     */
    public function exportItemCollection(Request $request) {

        $itemCollectionId = $request->get('id');

        //Item collection Id is Mandatory
        if ($itemCollectionId != "") {

            //check item exists
            $itemCollectionValue = $this->app['itemcollection.repository']->itemCollectionExists($itemCollectionId);

            if (!empty($itemCollectionValue)) {

                // export the item collection
                $itemCollectionExport = $this->app['itemcollection.service']->exportItemCollection($itemCollectionId);

                if ($itemCollectionExport == "noitems") {
                    // If the current chunk request is invalid and try to retry by badrequest.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['NO_ITEMS_ASSOCIATED_TO_ITEM_COLLECTION']);
                    return $response;
                } else if ($itemCollectionExport) {
                    // Return publish status.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->success);
                    return $response;
                } else {
                    // Return following error if item collection doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['FAILED_TO_EXPORT_ITEM_COLLECTION']);
                    return $response;
                }
            } else {

                // Return following error if item collection doesn't exists.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ITEM_COLLECTION_NOTFOUND_ERROR']);
                return $response;
            }
        }
    }

}
