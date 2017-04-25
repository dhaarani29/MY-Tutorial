<?php

/*
 * ItembanksService - Handles Itembanks module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Jagadeeshraj V S
 */

namespace QuizzingPlatform\Admin\Itembanks;

use Aws\S3\S3Client;
use Silex\Application;
use Flow\Autoloader;

//use ZipArchive;
//Register flow autoloader for chunk file upload
//Autoloader::register();

class ItembanksService {

    protected $app;
    protected $em;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->em = $app['orm.em'];
    }

    /**
     * Create Questions and Associate to itembank
     * @return array
     */
    public function createUploadedItem() {
        $response = array(); //upload response
        //Get the Upload File details from item_bank_upload table
        $uploadFileDetails = $this->app['itemcollection.repository']->getUploadDetails();
        if (!empty($uploadFileDetails)) {

            foreach ($uploadFileDetails as $key => $upload) {
                $logResponse = array(); //log error response
                $uploadStatus = array();
                $itemCollectionId = $upload['itemBankId'];
                $userId = $upload['createdBy'];
                $randomFileName = $upload['fileName'];
                $itemBankUploadId = $upload['id'];

                //Get the absolute path
                $absoluteFilePath = $this->app['config']['itemcollectionupload'] . '/' . $randomFileName . '/' . $this->app['config']['uploadFileName'];
                //check for file availability
                if (file_exists($absoluteFilePath)) {

                    //Read xml as SimpleXMLElement object
                    $xmlObject = simplexml_load_file($absoluteFilePath);

                    //count of item details available in xml
                    $totalItems = $xmlObject->children()->wk_question->count();
                    $uploadStatus[$itemCollectionId]['totalNoOfQuestions'] = $totalItems;
                    if ($totalItems > 0) {
                        $i = 1;
                        //Loop item details
                        foreach ($xmlObject->children()->wk_question as $key => $itemXmlData) {

                            //Get Question title
                            $label = $itemXmlData->question_title;
                            $itemXmlAtrributes = $itemXmlData->attributes();
                            if ($itemXmlAtrributes->qmode == $this->app['config']['updateMode']) {
                                $updateMode = $this->app['config']['updateMode'];
                            }

                            if (empty($updateMode)) {
                                //Check the Duplicate item
                                $itemExists = $this->app['items.repository']->checkDuplicateItem($label);
                            } else {
                                $itemExists = array();
                            }

                            if (empty($itemExists)) {
                                $createData = array();
                                //Get the itemType
                                $questionType = (string) $itemXmlData->question_type;
                                $itemTypeId = $this->app['items.repository']->getItemTypeIdByName($questionType);
                                /*
                                 *  Forming the array for create Questions
                                 */
                                $createData = self::parseXmlData($itemXmlData, $itemCollectionId, $userId, NULL, NULL, $randomFileName);

                                //Mandatory fields validation
                                if (!is_array($createData)) {

                                    $msg = ' Question - ' . $i . ': Processing Failed (' . $createData . ' missing )';
                                    $uploadStatus[$itemCollectionId]['failure'] ++;
                                    array_push($response, $msg);
                                    array_push($logResponse, $msg);
                                }
                                //Asset or media validation
                                elseif ($createData['status'] == $this->app['cache']->fetch('notexists')) {
                                    $msg = ' Question - ' . $i . ': ' . $createData['mediaType'] . ' missing (' . $createData['fileName'] . ') - ' . $label;
                                    $uploadStatus[$itemCollectionId]['failure'] ++;
                                    array_push($response, $msg);
                                    array_push($logResponse, $msg);
                                } else {

                                    /*
                                     * Insert the questions
                                     */
                                    if ($itemXmlAtrributes->qmode == $this->app['config']['updateMode']) {
                                        $updateItemId = $itemXmlAtrributes->identificationId;
                                        $existingItemId = $updateItemId;
                                    } else {
                                        $existingItemId = NULL;
                                    }
                                    $itemId = $this->app['items.repository']->create($createData, $existingItemId, $online = 1);
                                    //Create Subquestions in Clinical symptoms and Medical case questions
                                    if ($itemTypeId == $this->app['cache']->fetch('CLINICAL_SYMPTOMS') || $itemTypeId == $this->app['cache']->fetch('MEDICAL_CASE')) {

                                        $subQuestionXml = $itemXmlData->question_cs_sub_questions;
                                        if (!empty($subQuestionXml)) {
                                            foreach ($subQuestionXml->cs_sub_question as $subQuestion) {
                                                $subItemAttributes = $subQuestion->attributes();

                                                if ($subItemAttributes->qmode == $this->app['config']['updateMode']) {
                                                    $updateItemId = $subItemAttributes->identificationId;
                                                    $existingItemId = $updateItemId;
                                                } else {
                                                    $existingItemId = NULL;
                                                }
                                                /*
                                                 * Forming the array for create Sub Questions
                                                 */
                                                $createData = self::parseXmlData($subQuestion, $itemCollectionId, $userId, $itemId, $itemType, $randomFileName);
                                                //Mandatory field validation
                                                if (!is_array($createData)) {
                                                    $msg = ' Question - ' . $i . ' Sub Question : ' . $createData . ' missing ';
                                                    $uploadStatus[$itemCollectionId]['failure'] ++;
                                                    array_push($response, $msg);
                                                    array_push($logResponse, $msg);
                                                } else {
                                                    /*
                                                     * Insert the questions
                                                     */
                                                    $itemId = $this->app['items.repository']->create($createData, $existingItemId, $online = 1);
                                                }
                                            }
                                        }
                                    }
                                    /*
                                     * Associate the questions to itembank
                                     */
                                    $itemPkId = $this->app['items.repository']->getItemPkByVersion($itemId, NULL);
                                    $this->app['itemcollection.repository']->createAssociation($itemPkId['id'], $itemCollectionId, $userId);
                                    $msg = ' Question - ' . $i . ': Processed Successfully';
                                    $uploadStatus[$itemCollectionId]['success'] ++;
                                    array_push($response, $msg);
                                }
                            } else {
                                $msg = ' Question - ' . $i . ': Duplicate Entry -  ' . '"' . $label . '"' . ' already exists ';
                                $uploadStatus[$itemCollectionId]['failure'] ++;
                                array_push($response, $msg);
                                array_push($logResponse, $msg);
                            }
                            $i++;
                        }
                    } else {
                        $msg = ' Items Processing Failed ( Upload Content is empty )';
                        $uploadStatus[$itemCollectionId]['failure'] ++;
                        array_push($response, $msg);
                        array_push($logResponse, $msg);
                    }
                } else {
                    exit('Failed to open ' . $absoluteFilePath);
                }
                $this->app['itemcollection.repository']->storeItemUploadToLog($itemCollectionId, $itemBankUploadId, json_encode($logResponse));
                $this->app['itemcollection.repository']->storeUploadStatus($itemBankUploadId, $uploadStatus, $userId);
            }
        } else {
            $msg = ' No Files to Upload ';
            array_push($response, $msg);
        }


        $this->app['log']->writeLog($msg);
        return $response;
    }

    /**
     * Parse the XML Data
     * @param type $itemXmlData
     * @param type $itemCollectionId
     * @param type $userId
     * @param type $parentId
     * @param type $parentItemType
     * @return boolean
     */
    public function parseXmlData($itemXmlData, $itemCollectionId, $userId, $parentId = NULL, $parentItemType = NULL, $randomFileName) {


        //Get ItemAttributes
        $itemAttributes = $itemXmlData->attributes();

        //Add default Data if the mode is Create
        $createData['userId'] = $userId;
        if ($itemAttributes->qmode == $this->app['config']['createMode']) {
            $createData['status'] = "Imported";
            $createData['version'] = 1;
            $createData['parent'] = isset($parentId) ? $parentId : 0;
            $createData['parentItemType'] = isset($parentItemType) ? $parentItemType : 0;
        } elseif ($itemAttributes->qmode == $this->app['config']['updateMode']) {

            $createData['status'] = "Imported";
            $createData['version'] = 1;
            $createData['parent'] = isset($parentId) ? $parentId : 0;
            $createData['parentItemType'] = isset($parentItemType) ? $parentItemType : 0;
        }

        /*
         * Assign xml content to variable
         */

        //Question Title
        $label = ((string) $itemXmlData->question_title) ? (string) $itemXmlData->question_title : (string) $itemXmlData->cs_sub_question_title;
        $createData['label'] = $label;

        //Question Text
        $promptText = ((string) $itemXmlData->question_text) ? (string) $itemXmlData->question_text : (string) $itemXmlData->cs_sub_question_text;
        $createData['promptText'] = $promptText;

        //Question Type
        $questionType = ((string) $itemXmlData->question_type) ? (string) $itemXmlData->question_type : (string) $itemXmlData->cs_sub_question_type;
        $itemTypeId = $this->app['items.repository']->getItemTypeIdByName($questionType);
        $createData['itemType'] = $itemTypeId;

        //Question Score
        $score = ((string) $itemXmlData->question_score) ? (string) $itemXmlData->question_score : (string) $itemXmlData->cs_sub_question_score;
        $createData['score'] = ($score) ? $score : 1;

        //Question Difficulty
        $difficulty = ((string) $itemXmlData->question_difficulty) ? (string) $itemXmlData->question_difficulty : (string) $itemXmlData->cs_sub_question_difficulty;
        $createData['difficulty'] = ($difficulty) ? $difficulty : 1;

        //Question Correct and Incorrect rationale
        $correctRationaleXml = ((string) $itemXmlData->question_correct_rationale) ? (string) $itemXmlData->question_correct_rationale : (string) $itemXmlData->cs_sub_question_correct_rationale;
        $incorrectRationaleXml = ((string) $itemXmlData->question_incorrect_rationale) ? (string) $itemXmlData->question_incorrect_rationale : (string) $itemXmlData->cs_sub_question_incorrect_rationale;

        //Question correct answer
        $correctAns = ((string) $itemXmlData->correct_answer) ? (string) $itemXmlData->correct_answer : (string) $itemXmlData->cs_sub_correct_answer;
        $correctAnsArr = explode(',', $correctAns);
        /*
         *  create simple choices array
         */
        $ansNum = 0;
        $createData['choiceInteraction']['simpleChoices'] = array();
        if ($itemTypeId == $this->app['cache']->fetch('MULTIPLE_CHOICE')) {
            $choicesArray = isset($itemXmlData->question_multiple_choices) ? $itemXmlData->question_multiple_choices : $itemXmlData->cs_sub_question_multiple_choices;
        } elseif ($itemTypeId == $this->app['cache']->fetch('CHOICE_MULTIPLE')) {
            $choicesArray = isset($itemXmlData->question_choices_multiple) ? $itemXmlData->question_choices_multiple : $itemXmlData->cs_sub_question_choices_multiple;
        } elseif ($itemTypeId == $this->app['cache']->fetch('TRUE_FALSE')) {
            $choicesArray = $itemXmlData->question_true_false;
        } elseif ($itemTypeId == $this->app['cache']->fetch('IMAGE_INTEGRATION')) {
            $choicesArray = $itemXmlData->question_image_integration;
        } elseif ($itemTypeId == $this->app['cache']->fetch('VIDEO_QUESTIONS')) {
            $choicesArray = $itemXmlData->question_video_questions;
        } elseif ($itemTypeId == $this->app['cache']->fetch('MEDICAL_CASE')) {
            $choicesArray = $itemTypeId;
        } elseif ($itemTypeId == $this->app['cache']->fetch('CLINICAL_SYMPTOMS')) {
            $choicesArray = $itemTypeId;
        } elseif ($itemTypeId == $this->app['cache']->fetch('GRAPHIC_OPTION')) {
            $choicesArray = $itemXmlData->question_graphic_option;
        }

        /*
         *  create ModelFeedback array
         */
        $correctRationale = array("outcomeType" => 1, "feedbackText" => $correctRationaleXml);
        $inCorrectRationale = array("outcomeType" => 2, "feedbackText" => $incorrectRationaleXml);
        $createData['modelFeedback'] = array($correctRationale, $inCorrectRationale);

        if ($itemTypeId == $this->app['cache']->fetch("CLINICAL_SYMPTOMS") || $itemTypeId == $this->app['cache']->fetch("MEDICAL_CASE")) {
            $correctRationaleXml = 'correct';
            $incorrectRationaleXml = 'incorrect';
            $correctAns = 'correctans';
        }

        //Question Remediation link details
        $remediationLinkDetails = ($itemXmlData->question_remediation_link->remediation_type) ? $itemXmlData->question_remediation_link->remediation_type : $itemXmlData->cs_sub_question_remediation_link->cs_sub_remediation_type; //remediation link type
        /*
         *  Get Remediation link details collection 
         */
        $createData['remediationLinks'] = array();

        if (!empty($remediationLinkDetails)) {

            foreach ($remediationLinkDetails as $remedy) {
                $linkType = (string) $remedy->attributes()->remediation_link_type;
                $remediationLinkTypeId = $this->app['items.repository']->getRemediationLinkIdByName($linkType);
                $remedyDetail['linkTypeId'] = $remediationLinkTypeId;
                $remedyDetail['linkText1'] = ((string) $remedy->remediation_type_text) ? (string) $remedy->remediation_type_text : (string) $remedy->cs_sub_remediation_type_text;
                $remedyDetail['linkText2'] = ((string) $remedy->remediation_type_link) ? (string) $remedy->remediation_type_link : (string) $remedy->cs_sub_remediation_type_link;
                $remedyDetail['linkText3'] = ((string) $remedy->remediation_type_tooltip) ? (string) $remedy->remediation_type_tooltip : (string) $remedy->cs_sub_remediation_type_tooltip;
                array_push($createData['remediationLinks'], $remedyDetail);
            }
        }

        //Question asset Details
        $additionalFields = ($itemXmlData->question_additional_fields) ? $itemXmlData->question_additional_fields : $itemXmlData->cs_sub_question_additional_fields;
        /*
         * Create assets array
         */
        $createData['assets'] = array();

        if (!empty($additionalFields)) {
            foreach ($additionalFields as $media) {
                $assetExists = self::checkAssetExist($media, $randomFileName);
                if ($assetExists == $this->app['cache']->fetch('exists')) {
                    //Get the asset / media upload details
                    $assetDetails = self::getAssetUploadDetails($media, $itemCollectionId, $randomFileName);
                    array_push($createData['assets'], $assetDetails);
                } else {
                    return $assetExists;
                }
            }
        }
        if (empty($label) || empty($promptText) || empty($choicesArray) || empty($itemTypeId) || empty($correctRationaleXml) || empty($incorrectRationaleXml) || empty($correctAns)) {

            $err = '';
            if (empty($label)) {
                $err .= ' Question Title,';
            }if (empty($promptText)) {
                $err .= ' Question Text,';
            }if (empty($choicesArray)) {
                $err .= ' Question Choices,';
            }if (empty($correctRationaleXml)) {
                $err .= ' Question Correct Rationale,';
            }if (empty($incorrectRationaleXml)) {
                $err .= ' Question Incorrect Rationale,';
            }if (empty($itemTypeId)) {
                $err .= ' Question Type,';
            }if (empty($correctAns)) {
                $err .= ' Question Correct Answer,';
            }
            $createData = '';
            $createData = $err;
            return $createData;
        }
        if ($itemTypeId != $this->app['cache']->fetch('CLINICAL_SYMPTOMS') && $itemTypeId != $this->app['cache']->fetch('MEDICAL_CASE')) {
            $choice = isset($choicesArray->question_choice) ? $choicesArray->question_choice : $choicesArray->cs_sub_question_choice;
            foreach ($choice as $answers) {
                $ansNum++;
                $choiceDetail['correct'] = in_array($ansNum, $correctAnsArr) ? true : false;
                $choiceDetail['label'] = ((string) $answers->question_answer_text) ? (string) $answers->question_answer_text : (string) $answers->cs_sub_question_answer_text;
                $choiceDetail['rationale'] = ((string) $answers->question_rationale) ? (string) $answers->question_rationale : (string) $answers->cs_sub_question_rationale;
                if ($itemTypeId == $this->app['cache']->fetch('GRAPHIC_OPTION')) {
                    $additionalFields = $answers->question_additional_fields;

                    foreach ($additionalFields as $media) {
                        $assetExists = self::checkAssetExist($media, $randomFileName);
                        if ($assetExists == $this->app['cache']->fetch('exists')) {
                            $choiceDetail['value'] = self::getAssetUploadDetails($media, $itemCollectionId, $randomFileName);
                            $choiceDetail['minAsset'] = array();
                        } else {
                            return $assetExists;
                        }
                    }
                }
                array_push($createData['choiceInteraction']['simpleChoices'], $choiceDetail);
            }
        } else {
            $choiceDetail = true;
            $createData['choiceInteraction']['simpleChoices']['correct'] = $choiceDetail;
        }
        //  return $createData;
        /*
         * Create metadata association array
         */
        $createData['metadataAssoc'] = array();
        $metadataValue = array();
        $metadataTagDetails = $itemXmlData->question_meta_tag->meta_tag;
        if (!empty($metadataTagDetails)) {
            foreach ($metadataTagDetails as $metadata) {
                $metadataValueId = (string) $metadata->attributes()->metaTagId;
                $metadataId = (string) $metadata->attributes()->metadataId;
                $metadataTagValue = (string) $metadata->meta_tag_value;
                $metadataTagType = (string) $metadata->meta_tag_type;
                $metadataTypeId = $this->app['metadata.repository']->getMetadataTypeIdByName($metadataTagType);
                //Lookup and hierachy
                if ($metadataTypeId == $this->app['cache']->fetch('HIERARCHY') || $metadataTypeId == $this->app['cache']->fetch('LOOKUP')) {
                    $metadataValue[$metadataId][] = $metadataValueId;
                }
                //Free Text
                if ($metadataTypeId == $this->app['cache']->fetch('FREE_TEXT')) {
                    $metadataValue[$metadataId] = $metadataTagValue;
                }
            }

            $createData['metadataAssoc'] = $metadataValue;
        }
        return $createData;
    }

    public function pushToS3($dirPath, $fileName) {
        $filePath = $dirPath . $fileName;

        // Set Amazon s3 credentials
        $client = S3Client::factory(array(
                    'version' => 'latest',
                    'region' => $this->app['config']['region'],
                    'credentials' => array(
                        'key' => $this->app['config']['accessKey'],
                        'secret' => $this->app['config']['secretAccessKey']
                    )
        ));

        // Upload an object by streaming the contents of a file
        $result = $client->putObject(array(
            'Bucket' => $this->app['config']['bucketName'],
            'Key' => 'assets/' . $fileName,
            'SourceFile' => $filePath
        ));

        // $result = $client->listBuckets();
        // $iterator = $client->getIterator('ListObjects', array(
        //     'Bucket' => $bucket
        // ));
        // foreach ($iterator as $object) {
        //     echo $object['Key'] . "\n";
        // }die;
    }

    public function extractFile($fileName) {
        $filePath = $this->app['config']['tmp'] . $fileName;
        $basePath = $this->app['config']['uploads'] . 'itemcollection/';
        $destinationPath = $basePath . 'tmp/';
        $processingTmpPath = $this->app['config']['uploads'] . 'itemcollection';
        $response = array('error' => false, 'filePath' => $processingTmpPath);
        //echo file_exists($filePath)."jaga";die;
        //Check whether the file exist
        if (file_exists($filePath)) {
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if (!file_exists($processingTmpPath)) {
                mkdir($processingTmpPath, 0777, true);
            }
            rename($filePath, $destinationPath . $fileName);


            $zipFile = new \ZipArchive;
            //echo "&&&&".$destinationPath.$fileName."***" ;
            if ($zipFile->open($destinationPath . $fileName)) {

                $fileName = explode('.zip', $fileName);
                $zipFile->extractTo($processingTmpPath . "/" . $fileName[0]);

                //$zipFile->open($destinationPath.$fileName,  \ZipArchive::CREATE |  \ZipArchive::OVERWRITE);
                $zipFile->close();
            } else {
                return false;
            }

            if (!file_exists($processingTmpPath)) {
                mkdir($processingTmpPath, 0777, true);
            }
            return true;
        } else {
            $response['error'] = true;
            $response['errorDescription'] = 'Compressed file does not exist';
            return $response;
        }
        //return $response;
    }

    public function createChunkFile($requestData) {
        $tmpFolder = $this->app['config']['tmp'] . "itemcollection/tmp";
        if (!file_exists($tmpFolder)) {
            mkdir($tmpFolder, 0777, true);
        }

        $config = new \Flow\Config();
        $config->setTempDir($tmpFolder);
        $file = new \Flow\File($config);

        if ($file->validateChunk()) {
            $file->saveChunk();
        } else {
            // error, invalid chunk upload request, retry
            return "badrequest";
        }

        if ($file->validateFile() && $requestData['flowTotalChunks'] === $requestData['flowChunkNumber'] && $file->save($this->app['config']['tmp'] . '/' . $requestData['tmpFileName'])) {
            return "completed";
        } else {
            return "continue";
        }
    }

    /**
     * Get Asset upload Details
     * @param type $additionalFields
     * @param type $itemCollectionId
     * @return array
     */
    public function getAssetUploadDetails($additionalFields, $itemCollectionId, $randomFileName) {

        //Get the assetType ( IMAGE /VIDEO /AUDIO)
        $assetType = $additionalFields->attributes()->mediaType;
        $assetTypeId = $this->app['items.repository']->getAssetTypeIdByName($assetType);

        //Get Asset filepath
        $mediaDetails = explode("\\", $additionalFields->question_additional_file_path);
        $assetFileName = $mediaDetails[1];

        //Extract the file extension 
        $fileExtension = pathinfo($assetFileName, PATHINFO_EXTENSION);

        //Generating the unique file name
        $tempfileName = md5(uniqid()) . '.' . $fileExtension;

        if (!file_exists($this->app['config']['tmp'])) {
            mkdir($this->app['config']['tmp'], 0777, true);
        }
        $sourceDirFile = $this->app['config']['itemcollectionupload'] . '/' . $randomFileName . $this->app['config']['mediaFolder'] . '/' . $assetFileName;

        $tmpDirFile = $this->app['config']['tmp'] . $tempfileName;
        copy($sourceDirFile, $tmpDirFile);

        //Asset upload details array
        $assetsDetails['assetTypeId'] = $assetTypeId;
        $assetsDetails['fileName'] = $assetFileName;
        $assetsDetails['assetName'] = $tempfileName;
        $assetsDetails['assetPath'] = $this->app['cache']->fetch('uItmp');
        $assetsDetails['progress'] = 100;
        $assetsDetails['upload'] = array();
        return $assetsDetails;
    }

    /**
     * To check the asset exists
     * @param type $media
     * @param type $randomFileName
     * @return type
     */
    public function checkAssetExist($media, $randomFileName) {
        $mediaDetails = explode("\\", $media->question_additional_file_path);
        $mediaType = $media->attributes()->mediaType;
        $assetFileName = $mediaDetails[1];
        $sourceDirFile = $this->app['config']['itemcollectionupload'] . '/' . $randomFileName . $this->app['config']['mediaFolder'] . '/' . $assetFileName;
        if (!file_exists($sourceDirFile)) {
            $assetData['mediaType'] = $mediaType;
            $assetData['fileName'] = $assetFileName;
            $assetData['status'] = $this->app['cache']->fetch('notexists');
            return $assetData;
        } else {
            return $this->app['cache']->fetch('exists');
        }
    }

    //by Srilakshmi R
    //call to get upload status of the collection
    public function getImportStatus($itemCollectionId, $getImportDetails) {

        $importStatus = $this->app['itemcollection.repository']->getImportStatus($itemCollectionId, $getImportDetails);
        return $importStatus;
    }

    /**
     * Get all item collection
     * @param type $itemCollectionRequest
     * @param type $metadataRequest
     * @param type $associatedItemCollection
     * @param type $associatedItemId
     * @return type
     */
    public function getItemcollection($itemCollectionRequest, $metadataRequest, $associatedItemCollection = NULL, $associatedItemId = NULL, $allItemCollection = NULL) {
        $itemCollectionValueArray = $this->app['itemcollection.repository']->getItemcollection($itemCollectionRequest, $metadataRequest, $associatedItemCollection, $associatedItemId, $allItemCollection);
        return $itemCollectionValueArray;
    }

    /**
     * Create new itemcollection
     * @param type $itemcollectionData
     * @return type
     */
    public function create($itemcollectionData) {
        $createdId = $this->app['itemcollection.repository']->create($itemcollectionData);
        return $createdId;
    }

    /**
     * Get item collection by itembankid
     * @param type $itemBankId
     * @return type
     */
    public function find($itemBankId) {
        $itemCollectionData = $this->app['itemcollection.repository']->find($itemBankId);
        return $itemCollectionData;
    }

    /**
     * Update the item collection details
     * @param type $itemcollectionData
     * @param type $itemBankId
     * @return type
     */
    public function update($itemcollectionData, $itemBankId) {
        $response = $this->app['itemcollection.repository']->update($itemcollectionData, $itemBankId);
        return $response;
    }

    /**
     * Delete the itemcollection by itembankid
     * @param type $itemBankId
     * @return type
     */
    public function delete($itemBankId) {
        $response = $this->app['itemcollection.repository']->delete($itemBankId);
        return $response;
    }

    /**
     * Get all item list
     * @param type $itemCollectionRequest
     * @param type $metadataRequest
     * @return type
     */
    public function getItemlist($itemCollectionRequest, $metadataRequest) {
        $itemCollectionValueArray = $this->app['itemcollection.repository']->getItemlist($itemCollectionRequest, $metadataRequest);
        return $itemCollectionValueArray;
    }

    /**
     * Insert the upload details
     * @param type $itemcollectionData
     * @return type
     */
    public function insertUploadDetails($itemcollectionData) {
        $UploadId = $this->app['itemcollection.repository']->insertUploadDetails($itemcollectionData);
        return $UploadId;
    }

    /**
     * Get item bank list
     * @param type $param
     * @return type
     */
    public function getItembankList($itemCollectionRequest, $metadataRequest) {
        $itemCollectionValueArray = $this->app['itemcollection.repository']->getItembankList($itemCollectionRequest, $metadataRequest);
        return $itemCollectionValueArray;
    }

    /**
     * Check itemcollection exists or not
     * @param type $itemCollectionId
     * @return type
     */
    public function itemCollectionExists($itemCollectionId) {
        $itemCollectionValue = $this->app['itemcollection.repository']->itemCollectionExists($itemCollectionId);
        return $itemCollectionValue;
    }

    /**
     * To publish the item collection
     * @param type $itemCollectionId
     * @param type $itemCollectionPublishRequest
     * @return type
     */
    public function publish($itemCollectionId, $itemCollectionPublishRequest) {
        $publish = $this->app['itemcollection.repository']->publish($itemCollectionId, $itemCollectionPublishRequest);
        return $publish;
    }

    /**
     * libxml display error
     * @param type $error
     * @return type
     */
    public function libxml_display_error($error) {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    /**
     * libxml display errors
     * @return type
     */
    function libxml_display_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            return self::libxml_display_error($error);
        }
        libxml_clear_errors();
    }

    /**
     * To validate the xml
     * @param string $folderString
     * @return boolean
     */
    function validateXML($folderString) {

        if (!empty($folderString)) {

            // Enable user error handling 
            libxml_use_internal_errors(true);
            //Xml filename
            $xmlFileName = $this->app['config']['itemcollectionupload'] . '/' . $folderString . '/' . $this->app['config']['uploadFileName'];
            //Xsd fileName
            $xsdFileName = $this->app['config']['itemcollectionupload'] . '/' . $folderString . '/' . $this->app['config']['xsdFileName'];
            $xml = new \DOMDocument();
            $xml->load($xmlFileName);

            if (!$xml->schemaValidate($xsdFileName)) {
                $errString = self::libxml_display_errors();
                return $errString;
            } else {
                //echo $noOfQuestions =  $xml->getElementsByTagName('wk_question')->length;die;
                return $noOfQuestions = $xml->getElementsByTagName('wk_question')->length;
            }
        } else {
            $err = 'Folder name does not exists';
            return $err;
        }
    }

    /**
     * @Desc Export the item bank to xml
     * @param type $itemCollectionId
     * @return type
     */
    public function exportItemCollection($itemCollectionId) {

        // Get formed file path.
        $exportDir = $this->app['config']['itemcollectionupload'] . "/export/" . $itemCollectionId;

        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0777, true);
        }

        $itemCollection = $this->app['itemcollection.repository']->exportItemCollection($itemCollectionId, $exportDir);

        if ($itemCollection) {

            return $itemCollection;
        } else {
            return false;
        }
    }

}
