<?php

/*
 * ItemsService - Item/Question module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Items;

use Silex\Application;

class ItemsService {

    protected $app;
    protected $em;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->em = $app['orm.em'];
    }

    /**
     * @Desc Uploading the assets to temp directory and return the unique name of a file along with temp path.
     * @param Request $request
     * @return type array
     */
    public function assetTempUpload($request) {

        $fileContent = $request;

        //Extract the file extension 
        $fileExtension = pathinfo($fileContent['filename'], PATHINFO_EXTENSION);

        //Generating the unique file name
        $tempfileName = md5(uniqid()) . '.' . $fileExtension;

        //Base64_encoded data
        $data = $fileContent['file'];

        if (!file_exists($this->app['config']['tmp'])) {
            mkdir($this->app['config']['tmp'], 0777, true);
        }


        //open and write the base64_decoded data to file
        $file = fopen($this->app['config']['tmp'] . $tempfileName, "w");
        fwrite($file, base64_decode($data));
        fclose($file);

        $assetDetails = array("assetName" => $tempfileName, "assetPath" => $this->app['cache']->fetch('uItmp'));

        return $assetDetails;
    }

    /**
     * @Desc : Move the files from temp directory to target directory
     * @param type $assetTypeId
     * @param type $assetName
     * @return boolean
     */
    public function assetUpload($assetTypeId, $assetName, $assetTypeName, $isOldAsset) {


        // Using  asset type and get the target directory

        $targetUploadDir = $this->app['config']['uploads'] . $assetTypeName;

        if (!file_exists($targetUploadDir)) {
            mkdir($targetUploadDir, 0777, true);
        }

        $sourceDirFile = $this->app['config']['tmp'] . $assetName;
        $targetDirFile = $targetUploadDir . '/' . $assetName;

        // Move the files from temp directory to target directory
        // If file is successfully moved then return the mime type
        if ($isOldAsset != 1) {
            if (rename($sourceDirFile, $targetDirFile)) {

                // Get the mime type from actual file uploaded path for the file
                $mimeType = mime_content_type($targetDirFile);

                // Return the mimetype
                return $mimeType;
            } else {

                // If failed to move the file then return false
                return false;
            }
        } else {
            // Get the mime type from actual file uploaded path for the file
            $mimeType = mime_content_type($targetDirFile);

            return $mimeType;
        }
    }

    /**
     * Get all item Types
     * @return type
     */
    public function getAllItemTypes() {
        $itemTypes = $this->app['items.repository']->getAllItemTypes();
        return $itemTypes;
    }

    /**
     * Get remediation link types
     * @return type
     */
    public function getRemediationLinkTypes() {
        $remediationLinkTypes = $this->app['items.repository']->getRemediationLinkTypes();
        return $remediationLinkTypes;
    }

    /**
     * Create new item
     * @param type $itemsData
     * @param type $itemId
     * @return type
     */
    public function create($itemsData, $itemId = NULL) {
        $createdId = $this->app['items.repository']->create($itemsData, $itemId);
        return $createdId;
    }

    /**
     * Check the item already exists
     * @param type $itemId
     * @return type
     */
    public function itemsExists($itemId) {
        $itemValue = $this->app['items.repository']->itemsExists($itemId);
        return $itemValue;
    }

    /**
     * Get the item details by itemid
     * @param type $itemId
     * @return type
     */
    public function find($itemId, $version) {
        $itemData = $this->app['items.repository']->find($itemId, '', '', $version);
        return $itemData;
    }

    /**
     * To delete the item
     * @param type $itemId
     * @param type $isDeleteAll
     * @return type
     */
    public function delete($itemId, $isDeleteAll, $version = NULL) {
        $response = $this->app['items.repository']->delete($itemId, $isDeleteAll, $version);

        return $response;
    }

    /**
     * To get the itemlist
     * @param type $itemRequest
     * @param type $metadataRequest
     * @return type
     */
    public function getItemsList($itemRequest, $metadataRequest = NULL) {
        $itemsArray = $this->app['items.repository']->getItemsList($itemRequest, $metadataRequest);
        return $itemsArray;
    }

    /**
     * To publish the item
     * @param type $itemId
     * @return type
     */
    public function publish($itemId) {
        $publish = $this->app['items.repository']->publish($itemId);
        return $publish;
    }

    /**
     * Get the asset types
     * @return type
     */
    public function getAssetTypes() {
        $assetTypes = $this->app['items.repository']->getAssetTypes();
        return $assetTypes;
    }

    /**
     * Associate the item to itembank
     * @param type $itemAssociateData
     * @param type $itemId
     * @return type
     */
    public function associateItem($itemAssociateData, $itemId) {
        $response = $this->app['items.repository']->associateItem($itemAssociateData, $itemId);
        return $response;
    }

    /**
     * Get the item collection
     * @param type $itemId
     * @return type
     */
    public function findItemCollection($itemId) {
        $itemCollectionData = $this->app['items.repository']->findItemCollection($itemId);
        return $itemCollectionData;
    }

}
