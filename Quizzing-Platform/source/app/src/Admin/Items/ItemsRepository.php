<?php

/**
 * ItemsRepository - It's the model class file to handle the Question/Item module database operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Items;

use Silex\Application;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\QtiItemType;
use QuizzingPlatform\Entity\QtiItem;
use QuizzingPlatform\Entity\QtiItemModelFeedback;
use QuizzingPlatform\Entity\QtiItemRemediationLinks;
use QuizzingPlatform\Entity\QtiItemChoiceInteraction;
use QuizzingPlatform\Entity\QtiItemSimpleChoice;
use QuizzingPlatform\Entity\CmnAsset;
use QuizzingPlatform\Entity\QtiItemAssets;
use QuizzingPlatform\Entity\QtiItemBankMembers;
use QuizzingPlatform\Entity\QtiItemSimpleChoiceAssets;
use QuizzingPlatform\Entity\QtiItemRelation;
use QuizzingPlatform\Entity\QtiItemLatestVersion;

class ItemsRepository {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->effectiveDateTo = $app['config']['effectiveDateTo'];
        $this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));
        $this->graphicOption = $this->app['cache']->fetch('GRAPHIC_OPTION');
        $this->medicalCase = $this->app['cache']->fetch('MEDICAL_CASE');
        $this->clinicalSymptoms = $this->app['cache']->fetch('CLINICAL_SYMPTOMS');
        $this->qtiStatusArr = array('published', 'authoring');
    }

    /**
     * @param : No parameters
     * @Desc : This method fetches the Item/Question Types which is manually fed to database. 
     * @Return : array with list of question types.
     */
    public function getAllItemTypes() {

        try {

            // If question type stored in cache, then directly return from cache.
            $itemTypes = $this->app['cache']->fetch('itemTypes');
            $itemTypes = json_decode($itemTypes);

            if (!empty($itemTypes)) {

                // If question/item types retrieved successfully, then return them.
                return $itemTypes;
            } else {

                // If its not set in cache, fetch from DB and set to cache and then return the question types.
                $qb = $this->em->createQueryBuilder();
                $qb->select('it.itemTypeId', 'it.itemTypeName', 'it.itemTypeDescription', 'it.itemTypeDisplayOrder', 'it.status', 'it.labelText')
                        ->from('QuizzingPlatform\Entity\QtiItemType', 'it')
                        ->where('it.status = :status')
                        ->setParameter('status', 1)
                        ->orderBy('it.itemTypeDisplayOrder', 'ASC');
                $query = $qb->getQuery();
                $itemTypes = $query->getArrayResult();

                if (!$itemTypes) {

                    // If any error occures while fetching return false. 
                    return false;
                } else {

                    // Else return the response.  
                    $this->app['cache']->store('itemTypes', json_encode($itemTypes));
                    return $itemTypes;
                }
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item types listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @param : No parameters
     * @Desc : This method fetches the remediation link types which is manually fed to database. 
     * @Return : array with list of remediation link types.
     */
    public function getRemediationLinkTypes() {

        try {

            // If remediation link types stored in cache, then directly return from cache.
            $remediationLinkTypes = $this->app['cache']->fetch('remediationLinkTypes');
            $remediationLinkTypes = json_decode($remediationLinkTypes);

            if (!empty($remediationLinkTypes)) {

                // If question/item types retrieved successfully, then return them.
                return $remediationLinkTypes;
            } else {

                // If its not set in cache, fetch from DB and set to cache and then return the remediation link types.
                $qb = $this->em->createQueryBuilder();
                $qb->select('rlt.remediationLinkTypeId', 'rlt.remediationLinkTypeName')
                        ->from('QuizzingPlatform\Entity\QtiRemediationLinkType', 'rlt')
                        ->orderBy('rlt.remediationLinkTypeId', 'ASC');
                $query = $qb->getQuery();
                $remediationLinkTypes = $query->getArrayResult();

                if (!$remediationLinkTypes) {

                    // If any error occures while fetching return false. 
                    return false;
                } else {

                    // Else return the response.  
                    $this->app['cache']->store('remediationLinkTypes', json_encode($remediationLinkTypes));
                    return $remediationLinkTypes;
                }
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Remediation link types listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * 
     * @return type string
     */
    public function generateIdentifier() {

        // Generate uniqueId for question identifier
        $uniqueId = uniqid('IDENT_');
        $uniqueId = str_replace('.', '-', $uniqueId);
        return $uniqueId;

        /* $uniqueId = join('-', str_split(bin2hex(openssl_random_pseudo_bytes(8)), 4));
          return $uniqueId; */
    }

    /**
     * @param : Items request input as defined above.
     * @desc : Controller method to create item/question.
     * @return : id, Returns last created item id.
     */
    public function create($itemsData, $existingItemId = NULL, $online = NULL) {
        try {

            $userId = $itemsData['userId'];

            /**
             * for update get the old itempk id first
             */
            if ($existingItemId) {

                //get latest version item id details 
                $latestItemDetails = self::getLatestItemId($existingItemId);
                $existingItemPkId = $latestItemDetails['id'];
            }

            // Created common function to create fe item details which is helpful for recreation of parent when medical all clinical symptoms child questions updated.
            $qtiItem = self::createPartialItem($itemsData, $existingItemId, $online);

            // Assign newly created item id to variable.
            $itemId = $qtiItem->getItemId();
            $itemPkId = $qtiItem->getId();

            /**
             *  Store correct answer and incorrect answer rationale to qti_item_model_feedback table
             */
            $modalFeedback = $itemsData['modelFeedback'];

            if (!empty($modalFeedback)) {
                foreach ($modalFeedback as $feedback) {


                    //Fetching the foreign key value(outcome_type_id) from qti_feedback_outcome_type table
                    $outcomeTypeId = $this->em->getRepository('QuizzingPlatform\Entity\QtiFeedbackOutcomeType')->findOneByOutcomeTypeId(array('outcomeTypeId ' => $feedback['outcomeType']));

                    $qtiItemFeedback = new QtiItemModelFeedback();
                    $qtiItemFeedback->setItemPk($qtiItem); //Set item id
                    $qtiItemFeedback->setOutcomeType($outcomeTypeId); //Set outcome type id
                    $qtiItemFeedback->setShowHide($feedback['showHide']); //Show or hide feedback
                    $qtiItemFeedback->setFeedbackText($feedback['feedbackText']); //add feedback text
                    $qtiItemFeedback->setCreatedBy($userId); //Created By (user id )
                    $qtiItemFeedback->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $qtiItemFeedback->setModifiedBy($userId); //Modified By(userid)
                    $qtiItemFeedback->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($qtiItemFeedback); //Inserting the Above Field Values to QtiItemModelFeedback table 
                }

                $this->em->flush();
            }

            /**
             *  Store remediation links for questions
             */
            $remediationLinks = $itemsData['remediationLinks'];

            if (!empty($remediationLinks)) {
                foreach ($remediationLinks as $links) {

                    //Fetching the foreign key value(linkTypeId) from QtiRemediationLinkType table
                    $remediationLinkType = $this->em->getRepository('QuizzingPlatform\Entity\QtiRemediationLinkType')->findOneByRemediationLinkTypeId(array('remediationLinkTypeId ' => $links['linkTypeId']));

                    $qtiItemRemediationLinks = new QtiItemRemediationLinks();
                    $qtiItemRemediationLinks->setItemPk($qtiItem); //Set item id
                    $qtiItemRemediationLinks->setRemediationLinkType($remediationLinkType); //Set remediation link type
                    $qtiItemRemediationLinks->setLinkText1(($links['linkText1']) ? $links['linkText1'] : NULL); //add Link text1
                    $qtiItemRemediationLinks->setLinkText2(($links['linkText2']) ? $links['linkText2'] : NULL); //add Link text2
                    $qtiItemRemediationLinks->setLinkText3((!empty(trim($links['linkText3']))) ? $links['linkText3'] : NULL); //add Link text3
                    $qtiItemRemediationLinks->setCreatedBy($userId); //Created By (user id )
                    $qtiItemRemediationLinks->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $qtiItemRemediationLinks->setModifiedBy($userId); //Modified By(userid)
                    $qtiItemRemediationLinks->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($qtiItemRemediationLinks); //Inserting the Above Field Values to QtiItemRemediationLinks table 
                }

                $this->em->flush();
            }


            /**
             *  Store additional choice answers attributes to choice interaction
             */
            if (!empty($itemsData['choiceInteraction'])) {
                $promptText = $itemsData['choiceInteraction']['promptText'];
                $shuffle = $itemsData['choiceInteraction']['shuffle'];
                $maxChoice = $itemsData['choiceInteraction']['maxChoice'];
                $minChoice = $itemsData['choiceInteraction']['minChoice'];
                $itemScore = $itemsData['choiceInteraction']['itemScore'];
                $isPartialScore = $itemsData['choiceInteraction']['isPartialScore'];
                $isNegativeScore = $itemsData['choiceInteraction']['isNegativeScore'];

                $qtiItemChoiceInteraction = new QtiItemChoiceInteraction();
                $qtiItemChoiceInteraction->setItemPk($qtiItem); //Set item id
                $qtiItemChoiceInteraction->setPromptText($promptText); //Set item id
                $qtiItemChoiceInteraction->setShuffle($shuffle); //Set wether answer can be shuffled while display
                $qtiItemChoiceInteraction->setMinChoice($minChoice); // Set minimum answers can be selected
                $qtiItemChoiceInteraction->setMaxChoice($maxChoice); //set maximum answers can be selected
                $qtiItemChoiceInteraction->setItemScore($itemScore); //Set question score for correct answer
                $qtiItemChoiceInteraction->setIsPartialScore($isPartialScore); //Wether question can be partially evaluated
                $qtiItemChoiceInteraction->setIsNegativeScore($isNegativeScore); //Wether can be negatively scored for wrong answer
                $qtiItemChoiceInteraction->setCreatedBy($userId); //Created By (user id )
                $qtiItemChoiceInteraction->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                $qtiItemChoiceInteraction->setModifiedBy($userId); //Modified By(userid)
                $qtiItemChoiceInteraction->setModifiedDate($this->dateTime); //Modified date(current Date)
                $this->em->persist($qtiItemChoiceInteraction); //Inserting the Above Field Values to QtiItemRemediationLinks table
                $this->em->flush();
            }

            /**
             *  Store choice answers simple choices.
             */
            $simpleChoices = $itemsData['choiceInteraction']['simpleChoices'];

            if (!empty($simpleChoices)) {
                foreach ($simpleChoices as $choice) {

                    $qtiItemSimpleChoice = new QtiItemSimpleChoice();
                    $qtiItemSimpleChoice->setItemPk($qtiItem); //Set item id
                    $qtiItemSimpleChoice->setResourceIdentifier($choice['resourceIdentifier']); //Set item id
                    $qtiItemSimpleChoice->setLabel($choice['label']); //Set answer label

                    $choiceValue = $choice['value'];
                    // For Graphic Option question type, answers will be assets, hence we should exclude while storing value for graphic option type questions
                    if ($itemsData['itemType'] != $this->graphicOption) {
                        $qtiItemSimpleChoice->setValue($choiceValue); // Set answer value
                    }

                    $qtiItemSimpleChoice->setFixed($choice['fixed']); //set answers are fixed
                    $qtiItemSimpleChoice->setCorrect($choice['correct']); //Set correct answers
                    $qtiItemSimpleChoice->setRationale($choice['rationale']); //Set rationale for each answer
                    $qtiItemSimpleChoice->setCreatedBy($userId); //Created By (user id )
                    $qtiItemSimpleChoice->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $qtiItemSimpleChoice->setModifiedBy($userId); //Modified By(userid)
                    $qtiItemSimpleChoice->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($qtiItemSimpleChoice); //Inserting the Above Field Values to QtiItemRemediationLinks table 
                    $this->em->flush();

                    // If question type is Graphic Option type, then answers will assets, hence upload the assets and store it to QtiItemSimpleChoiceAssests
                    if ($itemsData['itemType'] == $this->graphicOption) {

                        // Through commonAssetStore function upload the assets and store it to common asset table
                        $cmnAsset = self::commonAssetStore($choiceValue['assetTypeId'], $choiceValue['assetName'], $choiceValue['fileName'], $choiceValue['altTitle'], $choiceValue['oldAsset']);
                        if ($cmnAsset) {
                            $qtiItemSimpleChoiceAssets = new QtiItemSimpleChoiceAssets();
                            $qtiItemSimpleChoiceAssets->setAsset($cmnAsset); //Set asset id
                            $qtiItemSimpleChoiceAssets->setItemSimpleChoice($qtiItemSimpleChoice); //Set item id
                            $qtiItemSimpleChoiceAssets->setCreatedBy($userId); //Created By (user id )
                            $qtiItemSimpleChoiceAssets->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                            $qtiItemSimpleChoiceAssets->setModifiedBy($userId); //Modified By(userid)
                            $qtiItemSimpleChoiceAssets->setModifiedDate($this->dateTime); //Modified date(current Date)
                            $this->em->persist($qtiItemSimpleChoiceAssets); //Inserting the Above Field Values to QtiItemRemediationLinks table 
                            $this->em->flush();
                        } else {
                            $this->app['log']->writeLog("Failed to store the assets for choice answers : " . $itemSimpleChoice);
                        }
                    }
                }
            }

            //Index the question when we do updation for a PUBLISHED question.
            if ($existingItemPkId) {

                $itemDetails = self::getItemType($itemPkId);

                // Check if status is publsihed
                if ($itemDetails['statusName'] == "Published") {

                    //Index the question in solr
                    $document = $this->app['offlinescripts.repository']->solrIndex($itemId);

                    // If its failed to index, then log this question in logs.
                    if (!$document) {

                        //If failed to index the question, then add to logs.
                        $msg = 'Failed to index the Question ' . $itemId;
                        $this->app['log']->writeLog($msg);
                    }
                }
            }


            /* Below code applies for Medical Case and Clinical Symptoms question type.
             * If Item type is any of the above, then if parent Id passed is 0 then store it as parent item 
             * and store only above information and return the newly created question id 
             */

            $parentItemId = isset($itemsData['parent']) ? $itemsData['parent'] : 0; // Parent Id will be maintianed for Medical Case and Clinical Symptoms questions, for other question parentId will be 0
            // If parent id is passed then get the pkid to associate the childs.
            if ($parentItemId) {

                //get latest version item id details of the parent
                $latestParentItemDetails = self::getLatestItemId($parentItemId);
                $parentPkId = $latestParentItemDetails['id'];
            } else {
                $parentPkId = $parentItemId;
            }


            if ((($parentPkId != 0)) || ($itemsData['itemType'] == $this->medicalCase) || ($itemsData['itemType'] == $this->clinicalSymptoms )) {

                // CASE PARENT QUESTION CREATE/UPDATE : This code applies for parent question cration/updation. 
                if ($parentPkId == 0) {

                    //CASE UPDATE PARENT QUESTION : While doing update, we crete the parent also freshly and we need to associated the childs to this newly created parent item ID. 
                    if ($existingItemPkId) {

                        // This will take the childs from existingItem and assiciates to the new item.
                        self::associateParentChilditems($existingItemPkId, $qtiItem, $userId, '', $itemPkId);

                        if (!empty($itemsData['childOrder'])) {
                            self::orderChildItems($itemPkId, $itemsData['childOrder']);
                        }
                    }

                    //Return newly created item ID for both create and update request for Medical case and clinical symptoms.
                    return $itemId;
                }

                // CASE UPDATE CHILD QUESTION : Following code will execute if trying to update the child question, then parent should also get recreate and associations has to be done for new parent item id
                if ((($parentPkId != 0) && ($existingItemPkId != NULL)) || (($parentPkId != 0) && ($itemsData['flowType'] == "edit"))) {

                    $parentItemDetails = self::find($parentItemId);

                    $newParent = self::createPartialItem($parentItemDetails, $parentItemId, $online);

                    // Assign newly created item id to variable.
                    $newParentId = $newParent->getId();

                    // This will take the childs from existingItem and assiciates to the new item.
                    self::associateParentChilditems($parentPkId, $newParent, $userId, $existingItemPkId, $itemPkId, $itemsData['flowType']);

                    //CASE : New child item created while editing. 

                    $childOrder = self::getChildOrder($newParentId);

                    //Create the parent and child association if parent Id is passed.
                    $qtiItemRelation = new QtiItemRelation();
                    $qtiItemRelation->setItemPk($newParent); //Set parent item id
                    $qtiItemRelation->setRelation($qtiItem); //Set associated child item id
                    $qtiItemRelation->setSequence($childOrder); //Set child order
                    $qtiItemRelation->setCreatedBy($userId); //Created By (user id )
                    $qtiItemRelation->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $qtiItemRelation->setModifiedBy($userId); //Modified By(userid)
                    $qtiItemRelation->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($qtiItemRelation); //Inserting the Above Field Values to QtiItemRelation table 
                    $this->em->flush();

                    return $parentItemId;
                }


                // CASE CREATE NEW CHILD : After creating new child question, need to associate to the parent question.
                if (($parentPkId != 0) && ($existingItemPkId == NULL)) {


                    //Fetching the foreign key value(item id for parent) from Qtiitem table
                    $parentItem = $this->em->getRepository('QuizzingPlatform\Entity\QtiItem')->findOneById(array('id ' => $parentPkId));

                    $childOrder = self::getChildOrder($parentPkId);

                    //Create the parent and child association if parent Id is passed.
                    $qtiItemRelation = new QtiItemRelation();
                    $qtiItemRelation->setItemPk($parentItem); //Set parent item id
                    $qtiItemRelation->setRelation($qtiItem); //Set associated child item id
                    $qtiItemRelation->setSequence($childOrder); //Set child order
                    $qtiItemRelation->setCreatedBy($userId); //Created By (user id )
                    $qtiItemRelation->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $qtiItemRelation->setModifiedBy($userId); //Modified By(userid)
                    $qtiItemRelation->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($qtiItemRelation); //Inserting the Above Field Values to QtiItemRelation table 
                    $this->em->flush();

                    return $parentItemId;
                }
            }

            //Return newly created item ID for both create and update request for except Medical and clinical symptom question as we returning in the respective code block
            return $itemId;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item/Question creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc Get latest item id 
     * @param type $itemId
     * @return type
     */
    public function getLatestItemId($itemId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('qi.id', 'qi.itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->join('QuizzingPlatform\Entity\QtiItemLatestVersion', 'qilv', \Doctrine\ORM\Query\Expr\Join::WITH, 'qilv.itemId=qi.itemId AND qilv.version = qi.version')
                ->where('qi.itemId = :itemId')
                ->andWhere('qi.isDeleted = :isDeleted') // Retrieve only active items
                ->setParameter('itemId', $itemId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $itemData = $qb->getQuery()->getArrayResult();
        return $itemData[0];
    }

    /**
     * 
     * @param type $childItems
     */
    public function orderChildItems($itemPkId, $childItems) {

        foreach ($childItems as $key => $item) {

            //get latest version item id details 
            $latestItemDetails = self::getLatestItemId($item);
            $relationPkId = $latestItemDetails['id'];

            $order = $key + 1;

            //Fetching the foreign key value(item id for parent) from Qtiitem table
            /* $qtiItemRelation = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemRelation')->findOneByRelation(array('relation' => $itemPkId));
              $qtiItemRelation->setSequence($order);
              $this->em->flush();
             */

            $qb = $this->em->createQueryBuilder();
            $qb->update('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                    ->set('qir.sequence', '?1')
                    ->where('qir.relation =?2')
                    ->andWhere('qir.itemPk   =?3')
                    ->setParameter('1', $order)
                    ->setParameter('2', $relationPkId)
                    ->setParameter('3', $itemPkId);
            $result = $qb->getQuery()->execute();
        }
    }

    /**
     * 
     * @param type $oldParentItemPkId
     * @param type $newParentQtiItem
     * @param type $userId
     * @param type $existingItemPkId
     * @param type $itemId
     */
    public function associateParentChilditems($oldParentItemPkId, $newParentQtiItem, $userId, $existingItemPkId = NULL, $itemPkId = NULL, $flowType = NULL) {

        $childItemIds = array();

        // CASE PARENT QUESTION UPDATE
        if ($existingItemPkId == NULL) {

            // Fetch all the associated questions to the old question id. and restore the association to the new Id. 
            $qb = $this->em->createQueryBuilder();
            $qb->select('qia.id')
                    ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.itemPk')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.relation')
                    ->where('qi.id = :id')
                    ->setParameter('id', $oldParentItemPkId);
            $oldChildItemIds = $qb->getQuery()->getArrayResult();
            foreach ($oldChildItemIds as $child) {
                $childItemIds[] = $child['id'];
            }

            /* if ($itemPkId != NULL) {
              // add newly created child to associate.
              array_push($childItemIds, $itemPkId);
              } */
        } else {

            // CASE CHILD QUESTION UPDATE.
            // Fetch all the associated questions to the old question id and dont consider the old child id to associate while updating.
            $qb = $this->em->createQueryBuilder();
            $qb->select('qia.id')
                    ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.itemPk')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.relation')
                    ->where('qi.id = :id')
                    ->andWhere('qia.id != :childItemId')
                    ->setParameter('id', $oldParentItemPkId)
                    ->setParameter('childItemId', $existingItemPkId);

            $oldChildItemIds = $qb->getQuery()->getArrayResult();

            foreach ($oldChildItemIds as $child) {
                $childItemIds[] = $child['id'];
            }
            // add newly created child to associate.
            array_push($childItemIds, $itemPkId);
        }

        if (!empty($childItemIds)) {

            foreach ($childItemIds as $childIds) {

                $childItemObj = $this->em->getRepository('QuizzingPlatform\Entity\QtiItem')->findOneById(array('id' => $childIds));

                $childOrder = self::getChildOrder($itemPkId);

                //Recreate the association with new parent Item id.
                $qtiItemRelation = new QtiItemRelation();
                $qtiItemRelation->setItemPk($newParentQtiItem); //Set parent item id
                $qtiItemRelation->setRelation($childItemObj); //Set associated child item id
                $qtiItemRelation->setSequence($childOrder); //Set child order
                $qtiItemRelation->setCreatedBy($userId); //Created By (user id )
                $qtiItemRelation->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                $qtiItemRelation->setModifiedBy($userId); //Modified By(userid)
                $qtiItemRelation->setModifiedDate($this->dateTime); //Modified date(current Date)
                $this->em->persist($qtiItemRelation); //Inserting the Above Field Values to QtiItemRelation table 
            }

            $this->em->flush();
        }
    }

    /**
     * @Desc Get child order to store
     * @param type $parentPkId
     * @return int
     */
    public function getChildOrder($parentPkId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('qir.sequence')
                ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                ->where('qir.itemPk = :id')
                ->setParameter('id', $parentPkId)
                ->setFirstResult(0)
                ->setMaxResults(1)
                ->orderBy('qir.relation', 'DESC');

        $childItemOrder = $qb->getQuery()->getArrayResult();

        if (!empty($childItemOrder)) {
            $previousChildOrder = $childItemOrder[0]['sequence'];
            $childOrder = $previousChildOrder + 1;
        } else {
            $childOrder = 1;
        }

        return $childOrder;
    }

    /**
     * @Desc : // Through commonAssetStore function upload the assets and store it to common asset table
     * @param type $assetTypeId
     * @param type $assetName
     * @param type $fileName
     * @param type $altTitle
     * @param type $isOldAsset
     * @return CmnAsset
     */
    public function createPartialItem($itemsData, $existingItemId, $online = NULL) {


        // For update get the item ID and increament the question version and use the same old identifier. 
        if ($existingItemId) {

            //get latest version item id details 
            $latestItemDetails = self::getLatestItemId($existingItemId);
            $existingItemPkId = $latestItemDetails['id'];

            // Get the item Id to store
            $itemId = $existingItemId;

            $itemIdObj = $this->em->getRepository('QuizzingPlatform\Entity\QtiItem')->findOneById(array('id' => $existingItemPkId));

            // use the old identifier for updates
            $idenfier = $itemIdObj->getQtiIdentifier();

            // For update increment the version number
            $version = $itemIdObj->getVersion() + 1;
        } else {

            // For creating new question generate the idenfier.
            $idenfier = self::generateIdentifier();

            // Get the item Id to store by checking previously stored highest itemid
            $qb = $this->em->createQueryBuilder();

            $qb->select('MAX(qi.itemId) as previousItemId')
                    ->from('QuizzingPlatform\Entity\QtiItem', 'qi');
            $lastItem = $qb->getQuery()->getArrayResult();

            $previousItemId = $lastItem[0]['previousItemId'];

            //Increment the prevousitemid by 1 to get next itemid
            $itemId = isset($previousItemId) ? $previousItemId + 1 : 0;

            //use the version which end user sends 
            $version = $itemsData['version'];
        }

        $userId = $itemsData['userId'];

        $parentId = $itemsData['parent']; // Parent Id will be maintianed for Medical Case and Clinical Symptoms questions, for other question parentId will be 0

        if ($parentId != 0) {

            //get latest version item id details of the parent
            $latestItemDetails = self::getLatestItemId($parentId);
            $parentPkId = $latestItemDetails['id'];

            //Check for parent status wether its published/authoring, then assign that status to childs
            $parentItemType = self::getItemType($parentPkId);
            $status = $parentItemType['statusName'];
        } else {
            $status = $itemsData['status'];
        }

        $parentId = ($itemsData['parent'] != 0) ? 1 : '0'; // Parent Id will be maintianed for Medical Case and Clinical Symptoms questions, for other question parentId will be 0
        //Fetching the foreign key value(item_type_id) from qti_item_type table
        $itemTypeId = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemType')->findOneByItemTypeId(array('itemTypeId' => $itemsData['itemType']));

        //Fetching the foreign key value(status_id) from qti_status table
        $qtiStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiStatus')->findOneByStatusName(array('statusName' => $status));
        if ($online) {
            $online = $this->app['cache']->fetch('DELETED'); //word template uploaded question
        } else {
            $online = $this->app['cache']->fetch('ACTIVE'); //admin created question
        }

        /**
         * For create/update create new question with the new versions.
         */
        $qtiItem = new QtiItem();
        $qtiItem->setItemId($itemId); //Set Item id
        $qtiItem->setItemType($itemTypeId); //Set Item type id
        $qtiItem->setStatus($qtiStatus); //Set status
        $qtiItem->setQtiIdentifier($idenfier); //Item identifier
        $qtiItem->setVersion($version); //Item Version
        $qtiItem->setLabel($itemsData['label']); //Item label
        $qtiItem->setPromptText($itemsData['promptText']); //Question desc
        $qtiItem->setLanguage(($itemsData['language']) ? $itemsData['language'] : "en-US"); //Question language
        $qtiItem->setScore(($itemsData['score']) ? $itemsData['score'] : 1); //Question score
        $qtiItem->setTimeDependant(($itemsData['timeDependant']) ? $itemsData['timeDependant'] : true); //Question time dependant 
        $qtiItem->setMaxTimeLimit(($itemsData['timeLimit']) ? $itemsData['timeLimit'] : NULL); //Question time limit
        $qtiItem->setAdaptive(($itemsData['adaptive']) ? $itemsData['adaptive'] : false); //Is Question adpative
        $qtiItem->setToolName(($itemsData['toolName']) ? $itemsData['toolName'] : NULL); //Question tool name
        $qtiItem->setToolVersion(($itemsData['toolVersion']) ? $itemsData['toolVersion'] : NULL); //Question tool version
        $qtiItem->setDifficulty($itemsData['difficulty']); //Question difficulty
        $qtiItem->setIsDeleted($this->app['cache']->fetch('ACTIVE')); //Question status
        $qtiItem->setParent($parentId); // parent != 0 for Medical case and clinical symptoms questions. Else will be 0.
        $qtiItem->setOnline($online); // 1-created through item, 0-uploaded from question bank 
        $qtiItem->setEffectiveDateFrom($this->dateTime); //Question effective date from
        $qtiItem->setEffectiveDateTo($this->effectiveDateTo); // Set question to be effective till 3 years from the date of question creation.
        $qtiItem->setCreatedBy($userId); //Created By (user id )
        $qtiItem->setCreatedDate($this->dateTime); //CreatedDate (current Date)
        $qtiItem->setModifiedBy($userId); //Modified By(userid)
        $qtiItem->setModifiedDate($this->dateTime); //Modified date(current Date)
        $this->em->persist($qtiItem); //Inserting the Above Field Values to QtiItem table
        $this->em->flush();

        // Assign newly created item id to variable.
        $itemId = $qtiItem->getItemId();
        $itemPkId = $qtiItem->getId();

        if ($existingItemId) {

            /*
             * Update the latest version
             */
            $qtiItemLatestVersion = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemLatestVersion')->findOneByItemId(array('itemId' => $existingItemId));
            $qtiItemLatestVersion->setVersion($version);
            $this->em->flush();
        } else {
            /*
             * Insert into latest version table
             */
            $qtiItemLatestVersion = new QtiItemLatestVersion();
            $qtiItemLatestVersion->setItemId($itemId);
            $qtiItemLatestVersion->setVersion($version);
            $this->em->persist($qtiItemLatestVersion);
            $this->em->flush();
        }

        /*
         * Item association to itembank always with latest items
         * change the old item to new item in itembank association table
         */
        if ($existingItemPkId != NULL) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('qibm.id')
                    ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                    ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qibm.itemPk')
                    ->where('qi.id=:id')
                    ->setParameter('id', $existingItemPkId);
            $itemAssocid = $qb->getQuery()->getArrayResult();

            if (!empty($itemAssocid)) {
                foreach ($itemAssocid as $key => $value) {
                    $qtiItemBankMembers = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBankMembers', $value['id']);
                    $qtiItemBankMembers->setItemPk($qtiItem);
                }
                $this->em->flush();
            }
        }

        /*
         * Item association to test/Quiz always with latest items
         * change the old item to new item in test to itembank association table
         */
        if ($existingItemPkId != NULL) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('qti.id')
                    ->from('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                    ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qti.itemPk')
                    ->where('qi.id=:id')
                    ->setParameter('id', $existingItemPkId);
            $itemAssocid = $qb->getQuery()->getArrayResult();

            if (!empty($itemAssocid)) {
                foreach ($itemAssocid as $key => $value) {
                    $qtiItemBankMembers = $this->em->getReference('QuizzingPlatform\Entity\QtiTestItems', $value['id']);
                    $qtiItemBankMembers->setItemPk($qtiItem);
                }
                $this->em->flush();
            }
        }

        /**
         * Store Question assets information
         */
        $assetsDetails = $itemsData['assets'];

        if (!empty($assetsDetails)) {

            foreach ($assetsDetails as $asset) {

                // Through commonAssetStore function upload the assets and store it to common asset table
                $cmnAsset = self::commonAssetStore($asset['assetTypeId'], $asset['assetName'], $asset['fileName'], $asset['altTitle'], $asset['oldAsset']);
                if ($cmnAsset) {
                    $qtiItemAssets = new QtiItemAssets();
                    $qtiItemAssets->setAsset($cmnAsset); //Set asset id
                    $qtiItemAssets->setItemPk($qtiItem); //Set item id
                    $qtiItemAssets->setCreatedBy($userId); //Created By (user id )
                    $qtiItemAssets->setCreatedDate($this->dateTime); //CreatedDate (current Date)
                    $qtiItemAssets->setModifiedBy($userId); //Modified By(userid)
                    $qtiItemAssets->setModifiedDate($this->dateTime); //Modified date(current Date)
                    $this->em->persist($qtiItemAssets); //Inserting the Above Field Values to QtiItemRemediationLinks table 
                    $this->em->flush();
                } else {
                    $this->app['log']->writeLog("Failed to store the assets for item : " . $itemPkId);
                }
            }
        }

        /**
         *  Store metadata association to the question.
         */
        $metadataAssociation = $itemsData['metadataAssoc'];

        if (!empty($metadataAssociation)) {

            $metadataAssoc = $this->app['metadata.repository']->storeMetadataResourceAssociation($this->app['cache']->fetch('item'), $metadataAssociation, $itemPkId, $userId);
            if (!$metadataAssoc) {
                $this->app['log']->writeLog("Failed to store question metadata association for item : " . $itemPkId);
            }
        }


        return $qtiItem;
    }

    public function commonAssetStore($assetTypeId, $assetName, $fileName, $altTitle, $isOldAsset) {
        //Fetching the foreign key value(assetTypeId) from CmnAssetType table
        $assetType = $this->em->getRepository('QuizzingPlatform\Entity\CmnAssetType')->findOneByAssetTypeId(array('assetTypeId ' => $assetTypeId));

        $assetTypeName = $assetType->getAssetTypeName();

        // Call service function to move the asset from temp directory to actual upload directory
        // if($isOldAsset != 1)
        $mimeType = $this->app['items.service']->assetUpload($assetTypeId, $assetName, $assetTypeName, $isOldAsset);

        // If file is successfully moved and returned with mime type then store the asset details in to table.

        if ($mimeType) {

            $cmnAsset = new CmnAsset();
            $cmnAsset->setAssetType($assetType); //Set asset type id
            $cmnAsset->setAssetName($assetName); //Set asset unique name
            $cmnAsset->setFileName($fileName); //Set actual file name
            $cmnAsset->setAltTitle($altTitle); // Set alt title for file
            $cmnAsset->setMimeType($mimeType); //set mime type for a file
            $cmnAsset->setEffectiveDate($this->dateTime); //Set effective date
            $cmnAsset->setCreatedBy($userId); //Created By (user id )
            $cmnAsset->setCreatedDate($this->dateTime); //CreatedDate (current Date)
            $cmnAsset->setModifiedBy($userId); //Modified By(userid)
            $cmnAsset->setModifiedDate($this->dateTime); //Modified date(current Date)
            $this->em->persist($cmnAsset); //Inserting the Above Field Values to QtiItemRemediationLinks table 
            $this->em->flush();

            return $cmnAsset;
        }
    }

    /**
     * @Desc : Get all versions of item based on the identifier
     * @param type $itemId
     * @return type
     */
    public function getAllquestionVersions($itemId) {

        // Fetch all versions of the table for the given Identifier.
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.id as id', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->where('qi.itemId = :itemId')
                ->andWhere('qi.isDeleted = :isDeleted')
                ->setParameter('itemId', $itemId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->orderBy('qi.version', 'DESC');
        $itemVersions = $qb->getQuery()->getArrayResult();

        return $itemVersions;
    }

    /**
     * @Desc Check whether the item exists or not
     * @param type $id
     * @param type $inActive
     * @return type
     */
    public function itemsExists($id, $inActive = NULL) {

        //get latest version item id details
        $latestItemDetails = self::getLatestItemId($id);
        $itemPkId = $latestItemDetails['id'];

        // Check wether item exists for the passed item ids.
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.id as id')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->where('qi.id = :id')
                ->setParameter('id', $itemPkId);

        if ($inActive != NULL) {
            $query->andWhere('qi.isDeleted = :isDeleted')
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
        } else {
            $query->andWhere('qi.isDeleted IN (:isDeleted)')
                    ->setParameter('isDeleted', $this->statusArr);
        }

        $itemIds = $qb->getQuery()->getArrayResult();

        return $itemIds;
    }

    /**
     * @Desc Check is there any parent exists for the given item id.
     * @param type $childId
     * @return boolean
     */
    public function checkParentExists($childId) {

        // First get the parent ID of the childId passed. 
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('MAX(qia.id) as parentItemId')
                ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                ->where('qi.id = :relation')
                ->setParameter('relation', $childId);
        $parentItem = $qb->getQuery()->getArrayResult();

        if (!empty($parentItem)) {
            //If parent exists the return the parent id
            $parentItemId = $parentItem[0]['parentItemId'];

            return $parentItemId;
        } else {

            // Else return false;
            return false;
        }
    }

    /**
     * @Desc Get parent Item details for the given id
     * @param type $childId
     * @return type
     */
    public function getParentItemDetails($parentItemPkId) {

        $parentItemDetails = array();

        // Then get the parent item details for the parent ID.
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.id', 'qi.itemId as parentItemId', 'qi.label', 'qi.promptText', 'qit.itemTypeId as itemType', 'qit.labelText as labelText')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                ->where('qi.id = :id')
                ->setParameter('id', $parentItemPkId);
        $itemValues = $qb->getQuery()->getArrayResult();

        // Assign parent details to parent array;
        $parentItemDetails = $itemValues[0];

        //Fetch assets details
        $qb = $this->em->createQueryBuilder();
        $instQuery = $qb->select('ca.assetId', 'ca.assetName', 'cat.assetTypeId', 'cat.assetTypeName', 'ca.fileName', 'ca.altTitle')
                ->from('QuizzingPlatform\Entity\QtiItemAssets', 'qia')
                ->join('QuizzingPlatform\Entity\CmnAsset', 'ca', \Doctrine\ORM\Query\Expr\Join::WITH, 'ca.assetId=qia.asset')
                ->join('QuizzingPlatform\Entity\CmnAssetType', 'cat', \Doctrine\ORM\Query\Expr\Join::WITH, 'cat.assetTypeId=ca.assetType')
                ->where('qia.itemPk = :id')
                ->setParameter('id', $parentItemPkId);
        $assetDetails = $qb->getQuery()->getArrayResult();


        if (!empty($assetDetails)) {

            $filePath = $this->app['cache']->fetch('uIuploads') . $assetDetails[0]['assetTypeName'];
            // Assign parent asset details.
            $parentItemDetails['assets'] = $assetDetails[0];
            $parentItemDetails['assets']['assetPath'] = $filePath;
        }

        // Finally return parent items details
        return $parentItemDetails;
    }

    /**
     * @param : type $itemPkId.
     * @Desc : Fetching the item/question details based on the itemd
     * @Return : array. contains all the question details.
     */
    public function find($itemId, $quizEngine = NULL, $testId = NULL, $version = NULL) {
        try {

            //get latest version item id details 
            $latestItemDetails = self::getLatestItemId($itemId);

            // Get item details for the mentioned ID.
            $qb = $this->em->createQueryBuilder();
            $query = $qb->select('qi.itemId as id', 'qit.itemTypeId as itemType', 'qit.itemTypeName as itemTypeName', 'qit.labelText as labelText', 'qs.statusName as status', 'qi.qtiIdentifier as identifier', 'qi.version', 'qi.label', 'qi.promptText', 'qi.timeDependant', 'qi.maxTimeLimit as timeLimit', 'qi.adaptive', 'qi.score', 'qi.difficulty', 'qi.toolName', 'qi.toolVersion', 'qi.isDeleted')
                    ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                    ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                    ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
                    ->where('qi.itemId = :itemId')
                    ->setParameter('itemId', $itemId);

            if (!$quizEngine) {
                $query->andWhere('qi.isDeleted IN (:isDeleted)')
                        ->setParameter('isDeleted', $this->statusArr);
            }

            if (isset($version)) {

                $query->andWhere('qi.version=:version')
                        ->setParameter('version', $version);
            } else {

                $query->andWhere('qi.version=:version')
                        ->setParameter('version', $latestItemDetails['version']);
            }

            $itemValues = $qb->getQuery()->getArrayResult();

            if (isset($version)) {
                $itemPkDetails = self::getItemPkByVersion($itemId, $version, $quizEngine);
                $itemPkId = $itemPkDetails['id'];
            } else {
                $itemPkId = $latestItemDetails['id'];
            }

            // If question exists then continue
            if (!empty($itemValues)) {

                // Declare result array to return
                $itemReturnValues = array();

                // Assign item details to return array.
                $itemReturnValues = $itemValues[0];
                $itemType = $itemValues[0]['itemType'];
                $identifier = $itemValues[0]['identifier'];

                if (isset($quizEngine)) {

                    // Check if any parent exists for teh give item id to check is par tof medical or clinical symptom child question.
                    $parentItemPkId = self::checkParentExists($itemPkId);

                    // If parent exist then get the parent item id details.
                    if ($parentItemPkId) {

                        // Get the parent item details and assign it to final return value
                        $parentDetails = self::getParentItemDetails($parentItemPkId);
                        $itemReturnValues['parentId'] = $parentDetails['parentItemId'];
                        $itemReturnValues['parentItemType'] = $parentDetails['itemType'];
                        $itemReturnValues['parentLabelText'] = $parentDetails['labelText'];

                        $itemReturnValues['parentDetails']['label'] = $parentDetails['label'];
                        $itemReturnValues['parentDetails']['promptText'] = $parentDetails['promptText'];
                        $itemReturnValues['parentDetails']['assets'] = $parentDetails['assets'];
                    }
                }

                if (empty($quizEngine)) {
                    // Get all versions of the item along with item id and assign to return array.
                    $itemReturnValues['versionsList'] = self::getAllquestionVersions($itemId);
                }

                //Fetch modal feedabck for questions
                $qb = $this->em->createQueryBuilder();
                $instQuery = $qb->select('qfot.outcomeTypeId as outcomeType', 'qimf.feedbackText', 'qimf.showHide')
                        ->from('QuizzingPlatform\Entity\QtiItemModelFeedback', 'qimf')
                        ->join('QuizzingPlatform\Entity\QtiFeedbackOutcomeType', 'qfot', \Doctrine\ORM\Query\Expr\Join::WITH, 'qfot.outcomeTypeId=qimf.outcomeType')
                        ->where('qimf.itemPk = :id')
                        ->setParameter('id', $itemPkId);
                $modalFeedbackDetails = $qb->getQuery()->getArrayResult();

                if (!empty($modalFeedbackDetails)) {

                    // assign feedback details to return array.
                    $itemReturnValues['modelFeedback'] = $modalFeedbackDetails;
                }

                //Fetch remediation links for a question
                $qb = $this->em->createQueryBuilder();
                $instQuery = $qb->select('qrlt.remediationLinkTypeId as linkTypeId', 'qrlt.remediationLinkTypeName as linkTypeName', 'qirl.linkText1', 'qirl.linkText2', 'qirl.linkText3')
                        ->from('QuizzingPlatform\Entity\QtiItemRemediationLinks', 'qirl')
                        ->join('QuizzingPlatform\Entity\QtiRemediationLinkType', 'qrlt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qrlt.remediationLinkTypeId=qirl.remediationLinkType')
                        ->where('qirl.itemPk = :id')
                        ->setParameter('id', $itemPkId);
                $remediationLinkDetails = $qb->getQuery()->getArrayResult();

                if (!empty($remediationLinkDetails)) {

                    // assign remediation details to return array.
                    $itemReturnValues['remediationLinks'] = $remediationLinkDetails;
                }


                //Fetch choice interaction details for a question
                $qb = $this->em->createQueryBuilder();
                $instQuery = $qb->select('qict.shuffle', 'qict.maxChoice', 'qict.minChoice')
                        ->from('QuizzingPlatform\Entity\QtiItemChoiceInteraction', 'qict')
                        ->where('qict.itemPk = :id')
                        ->setParameter('id', $itemPkId);
                $choiceInteractionDetails = $qb->getQuery()->getArrayResult();

                if (!empty($choiceInteractionDetails)) {

                    // assign choice details to return array.
                    $itemReturnValues['choiceInteraction'] = $choiceInteractionDetails[0];
                }

                //Fetch simple choices 
                $qb = $this->em->createQueryBuilder();
                $fields = array('qisc.id as choiceId', 'qisc.resourceIdentifier', 'qisc.fixed', 'qisc.label', 'qisc.value', 'qisc.rationale', 'qisc.correct');

                // If question type is Grpahic option then get asset details and add to selece fields array.
                if ($itemType == $this->graphicOption) {
                    array_push($fields, 'ca.assetId', 'ca.assetName', 'cat.assetTypeId', 'cat.assetTypeName', 'ca.fileName', 'ca.altTitle');
                }


                $choiceQuery = $qb->select($fields)
                        ->from('QuizzingPlatform\Entity\QtiItemSimpleChoice', 'qisc');

                // If question type is Grpahic option then join asset related tables to get assets
                if ($itemType == $this->graphicOption) {
                    $choiceQuery->join('QuizzingPlatform\Entity\QtiItemSimpleChoiceAssets', 'qisca', \Doctrine\ORM\Query\Expr\Join::WITH, 'qisca.itemSimpleChoice=qisc.id')
                            ->join('QuizzingPlatform\Entity\CmnAsset', 'ca', \Doctrine\ORM\Query\Expr\Join::WITH, 'ca.assetId=qisca.asset')
                            ->join('QuizzingPlatform\Entity\CmnAssetType', 'cat', \Doctrine\ORM\Query\Expr\Join::WITH, 'cat.assetTypeId=ca.assetType');
                }
                $choiceQuery->where('qisc.itemPk = :id')
                        ->setParameter('id', $itemPkId);
                $simpleChoiceDetails = $qb->getQuery()->getArrayResult();

                if (!empty($simpleChoiceDetails)) {
                    $fixedKeys = array();
                    $simpleChoices = array();
                    $shuffleKeys = array();

                    foreach ($simpleChoiceDetails as $key => $choice) {

                        if ($choice['fixed']) {
                            $fixedKeys[] = $key;
                        } else {
                            $shuffleKeys[] = $key;
                        }

                        $simpleChoices[$key]['choiceId'] = $choice['choiceId'];
                        $simpleChoices[$key]['resourceIdentifier'] = $choice['resourceIdentifier'];
                        $simpleChoices[$key]['fixed'] = $choice['fixed'];
                        $simpleChoices[$key]['label'] = $choice['label'];
                        $simpleChoices[$key]['rationale'] = ($choice['rationale']) ? $choice['rationale'] : NULL;
                        $simpleChoices[$key]['placeHolderText'] = $choice['placeHolderText'];
                        $simpleChoices[$key]['correct'] = $choice['correct'];

                        // If question type is Grpahic option then get the answer value from assets else get from simple choice table
                        if ($itemType == $this->graphicOption) {
                            $assetDetails = array();

                            $filePath = $this->app['cache']->fetch('uIuploads') . $choice['assetTypeName'];

                            $assetDetails['assetId'] = $choice['assetId'];
                            $assetDetails['assetName'] = $choice['assetName'];
                            $assetDetails['assetTypeId'] = $choice['assetTypeId'];
                            $assetDetails['fileName'] = $choice['fileName'];
                            $assetDetails['assetPath'] = $filePath;
                            $assetDetails['altTitle'] = $choice['altTitle'];
                            $assetDetails['oldAsset'] = 1;

                            $simpleChoices[$key]['value'] = $assetDetails;
                        } else {
                            $simpleChoices[$key]['value'] = $choice['value'];
                        }
                    }

                    // Foollowing code is to make randomize the answers if flag is set.
                    // Check if question is used by quiz engine
                    if ($quizEngine) {

                        $simpleChoicesFinalList = array();

                        // Get the quiz details based on the quiz id.
                        $testDetails = $this->app['tests.repository']->find($testId, '', $userId);
                        $randomizeAnswer = 1; //$testDetails['randomizeAnswer'];
                        // Check if random answers are set.
                        if ($randomizeAnswer) {

                            if (!empty($shuffleKeys)) {
                                // Then shuffle the shuffle list keys
                                shuffle($shuffleKeys);

                                // First load all the shuffle list choices to final choice array.
                                foreach ($shuffleKeys as $key) {
                                    $simpleChoicesFinalList[] = $simpleChoices[$key];
                                }
                            }

                            // Then load fixed choice to final choice array.
                            foreach ($fixedKeys as $key) {

                                // Check if the fixed key already exists in the final list then insert this to that position and move other elements by one level up
                                if (array_key_exists($key, $simpleChoicesFinalList)) {

                                    //insertValueAtPosition() will insert the value to the specific position and moves other items to next index.
                                    $simpleChoicesFinalList = self::insertValueAtPosition($simpleChoicesFinalList, $key, $simpleChoices[$key]);
                                } else {

                                    // If key not exists then directly append the choice
                                    $simpleChoicesFinalList[] = $simpleChoices[$key];
                                }
                            }
                        }
                    } else {

                        // If not quiz engine mode, then assgin simple choice to final list variable.
                        $simpleChoicesFinalList = $simpleChoices;
                    }

                    // assign choice details to return array.
                    $itemReturnValues['choiceInteraction']['simpleChoices'] = $simpleChoicesFinalList;
                }

                //Fetch assets details
                $qb = $this->em->createQueryBuilder();
                $instQuery = $qb->select('ca.assetId', 'ca.assetName', 'cat.assetTypeId', 'cat.assetTypeName', 'ca.fileName', 'ca.altTitle')
                        ->from('QuizzingPlatform\Entity\QtiItemAssets', 'qia')
                        ->join('QuizzingPlatform\Entity\CmnAsset', 'ca', \Doctrine\ORM\Query\Expr\Join::WITH, 'ca.assetId=qia.asset')
                        ->join('QuizzingPlatform\Entity\CmnAssetType', 'cat', \Doctrine\ORM\Query\Expr\Join::WITH, 'cat.assetTypeId=ca.assetType')
                        ->where('qia.itemPk = :id')
                        ->setParameter('id', $itemPkId);
                $assetDetails = $qb->getQuery()->getArrayResult();

                if (!empty($assetDetails)) {

                    $uploadedAssets = array();
                    foreach ($assetDetails as $key => $assets) {
                        $filePath = $this->app['cache']->fetch('uIuploads') . $assets['assetTypeName'];

                        $uploadedAssets[$key]['assetId'] = $assets['assetId'];
                        $uploadedAssets[$key]['assetName'] = $assets['assetName'];
                        $uploadedAssets[$key]['assetTypeId'] = $assets['assetTypeId'];
                        $uploadedAssets[$key]['fileName'] = $assets['fileName'];
                        $uploadedAssets[$key]['assetPath'] = $filePath;
                        $uploadedAssets[$key]['altTitle'] = $assets['altTitle'];
                        $uploadedAssets[$key]['oldAsset'] = 1;
                    }

                    // assign asset details to return array.
                    $itemReturnValues['assets'] = $uploadedAssets;
                }

                if (empty($quizEngine)) {
                    //Fetch Metadata association details 
                    $metadataAssoc = $this->app['metadata.repository']->getMetadataResourceAssociation('item', $itemPkId);

                    if (!empty($metadataAssoc['metadataIds'])) {

                        // Get concatenated metadatas to get all the details of the selected metadatas
                        $metadataIds = $metadataAssoc['metadataIds'];

                        // Delete metadataIds from return array. 
                        unset($metadataAssoc['metadataIds']);

                        // assign metadata association details to return array.
                        $itemReturnValues['metadataAssoc'] = $metadataAssoc;
                        $itemReturnValues['metadataPrev'] = $metadataAssoc; //added  by srilAKSHMI to display saved node details in metadfata popup in front end.

                        if (!empty($metadataIds)) {

                            //Get complete metadata details which are all associated. 
                            $metadatasDetails = $this->app['metadata.repository']->getMetadataDetails($metadataIds);

                            // assign complete details of metadata's association details to return array.
                            $itemReturnValues['selectedMetaDetails'] = $metadatasDetails;
                        }
                    }
                }

                // Return result array
                return $itemReturnValues;
            } else {

                //Question Not Found
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Question GetbyId Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc Used in insert the value at the specific location.
     * @param array $choiceArray
     * @param type $position
     * @param type $choiceValue
     * @return type
     */
    public function insertValueAtPosition($choiceArray, $position, $choiceValue) {

        $maxIndex = count($choiceArray) - 1;
        if ($position === 0) {
            array_unshift($choiceArray, $choiceValue);
        } elseif (($position > 0) && ($position <= $maxIndex)) {
            $firstHalf = array_slice($choiceArray, 0, $position);
            $secondHalf = array_slice($choiceArray, $position);
            $choiceArray = array_merge($firstHalf, array($choiceValue), $secondHalf);
        }

        return $choiceArray;
    }

    /**
     * @Desc : Soft delete the item based on the Id
     * @param type $ids
     * @return boolean
     */
    public function delete($itemId, $isDeleteAll, $version) {

        try {

            $ids = array();

            // $isDeleteAll flag is true then identifier will be passed in the place of ID.
            // we need to fetch all the questions mapped to the identifier and delete. 
            // When boolean value passed through URL, it passes as string, hence comparing with string
            if ($isDeleteAll == "true") {

                // Get item_id for the mentioned identifier.
                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('qi.id as id')
                        ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                        ->where('qi.itemId = :itemId')
                        ->andWhere('qi.isDeleted = :isDeleted')
                        ->setParameter('itemId', $itemId)
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
                $itemIds = $qb->getQuery()->getArrayResult();

                foreach ($itemIds as $id) {
                    $ids[] = $id['id'];
                }
            } else {

                if (isset($version)) {
                    $itemPkDetails = self::getItemPkByVersion($itemId, $version);
                    $itemPkId = $itemPkDetails['id'];
                } else {
                    $latestItemDetails = self::getLatestItemId($itemId);
                    $itemPkId = $latestItemDetails['id'];
                }
                if ($itemPkId != NULL) {
                    $ids[] = $itemPkId;
                }
            }


            if (!empty($ids)) {

                $associatedIds = array();

                //Check whether item is associated to Question bank, if so get assocaited bank collection names
                $qb = $this->em->createQueryBuilder();
                $qb->select('qi.id as itemId ', 'qib.itemBankName')
                        ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                        ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qibm.itemPk')
                        ->leftJoin('QuizzingPlatform\Entity\QtiItemBank', 'qib', \Doctrine\ORM\Query\Expr\Join::WITH, 'qib.itemBankId=qibm.itemBank')
                        ->where('qibm.itemPk IN (:id)')
                        ->setParameter('id', $ids);
                $itemBankItemAssoc = $qb->getQuery()->getArrayResult();

                foreach ($itemBankItemAssoc as $assoc) {
                    $associatedIds[] = $assoc['itemId'];
                    $itembankDetails[$assoc['itemId']][] = $assoc['itemBankName'];
                }

                //Check whether item is associated to Quiz, if so get associated quiz names.
                $qb = $this->em->createQueryBuilder();
                $qb->select('qi.id as itemId ', 'qt.title as testName')
                        ->from('QuizzingPlatform\Entity\QtiTestItems', 'qti')
                        ->join('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qti.itemPk')
                        ->leftJoin('QuizzingPlatform\Entity\QtiTest', 'qt', \Doctrine\ORM\Query\Expr\Join::WITH, 'qt.id=qti.testPk')
                        ->where('qti.itemPk IN (:id)')
                        ->setParameter('id', $ids);
                $testItemAssoc = $qb->getQuery()->getArrayResult();

                foreach ($testItemAssoc as $assoc) {
                    $associatedIds[] = $assoc['itemId'];
                    $testDetails[$assoc['itemId']][] = $assoc['testName'];
                }

                // Make all the associated questions as INACTIVE. 
                foreach ($associatedIds as $id) {
                    $qtiItem = $this->em->getReference('QuizzingPlatform\Entity\QtiItem', $id);
                    $qtiItem->setIsDeleted($this->app['cache']->fetch('INACTIVE'));
                }
                $this->em->flush();

                // Take the difference from all itemIds and the associated item ids.  
                $itemIdsToDelete = array_diff($ids, $associatedIds);

                //Delete the question from solr
                $document = $this->app['offlinescripts.repository']->solrDelete($itemId);

                // If its failed to delete, then log this question in logs.
                if (!$document) {

                    //If failed to index the question, then add to logs.
                    $msg = 'Failed to delete the Question ' . $itemId;
                    $this->app['log']->writeLog($msg);
                }

                // If any item is not associated to question collection or quiz bank then delete that item.
                if (!empty($itemIdsToDelete)) {

                    //Delete the question.
                    foreach ($itemIdsToDelete as $id) {
                        // soft delete the item with status 0
                        $qtiItem = $this->em->getReference('QuizzingPlatform\Entity\QtiItem', $id);
                        $qtiItem->setIsDeleted($this->app['cache']->fetch('DELETED'));
                    }
                    $this->em->flush();
                }

                // Get the next active item and store to relation table. 
                if ($isDeleteAll != "true") {

                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('MAX(qi.version) as version')
                            ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                            ->where('qi.isDeleted = :isDeleted') // Retrieve only active items
                            ->andWhere('qi.parent = :parent') // Retrieve only parent=0 items
                            ->andWhere('qi.itemId = :itemId') // Retrieve only parent=0 items
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('parent', 0)
                            ->setParameter('itemId', $itemId);
                    $previousVersionItem = $qb->getQuery()->getArrayResult();

                    $version = $previousVersionItem[0]['version'];

                    if (!empty($version)) {

                        // Update the next latest version to table
                        $qtiItemLatestVersion = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemLatestVersion')->findOneByItemId(array('itemId' => $itemId));
                        $qtiItemLatestVersion->setVersion($version);
                        $this->em->flush();

                        ######## Re-Index the question in solr for the recent version item ###########

                        $itemPkDetails = self::getItemPkByVersion($itemId, $version);
                        $previousItemPkId = $itemPkDetails['id'];

                        $itemDetails = self::getItemType($previousItemPkId);

                        // Check if status is publsihed
                        if ($itemDetails['statusName'] == "Published") {

                            $document = $this->app['offlinescripts.repository']->solrIndex($itemId);

                            // If its failed to index, then log this question in logs.
                            if (!$document) {

                                //If failed to index the question, then add to logs.
                                $msg = 'Failed to index the Question ' . $itemId;
                                $this->app['log']->writeLog($msg);
                            }
                        }

                        #############################################################################SSSS
                    }
                }


                // If is delete all versions set then return the status of all versions of questions.
                if ($isDeleteAll == "true") {

                    // Fetch all the item versions status along with status and version details
                    $qb = $this->em->createQueryBuilder();
                    $qb->select('qi.id as itemId', 'qi.isDeleted as statusCode', 'cs.statusName', 'qi.version as version')
                            ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                            ->leftJoin('QuizzingPlatform\Entity\CmnStatus', 'cs', \Doctrine\ORM\Query\Expr\Join::WITH, 'cs.statusCode=qi.isDeleted')
                            ->where('qi.id IN (:itemId)')
                            ->setParameter('itemId', $ids)
                            ->orderBy('version', 'DESC');
                    $itemIdsStatus = $qb->getQuery()->getArrayResult();

                    $idsStatus = array();

                    foreach ($itemIdsStatus as $key => $statusIds) {

                        // Check if same item assoacited with itembank then get associate the item bank name.
                        if (!empty($itembankDetails)) {
                            if (array_key_exists($statusIds['itemId'], $itembankDetails)) {
                                $itembanks = implode(',', $itembankDetails[$statusIds['itemId']]);
                            } else {
                                $itembanks = NULL;
                            }
                        }


                        // Check if same item assoacited with quiz then get associate the quiz name.
                        if (!empty($testDetails)) {
                            if (array_key_exists($statusIds['itemId'], $testDetails)) {
                                $tests = implode(',', $testDetails[$statusIds['itemId']]);
                            } else {
                                $tests = NULL;
                            }
                        }

                        $idsStatus[$key]['itemId'] = $statusIds['itemId'];
                        $idsStatus[$key]['statusCode'] = $statusIds['statusCode'];
                        $idsStatus[$key]['statusName'] = $statusIds['statusName'];
                        $idsStatus[$key]['version'] = $statusIds['version'];
                        $idsStatus[$key]['itemBankName'] = $itembanks;
                        $idsStatus[$key]['testName'] = $tests;
                    }

                    return $idsStatus;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item Deletion Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @Desc Get items total count
     * @param type $label
     * @param type $identifier
     * @param type $itemTypeId
     * @param type $status
     * @return type
     */
    public function getItemsCount($label = NULL, $identifier = NULL, $itemTypeId = NULL, $status = NULL, $metadataRequest = array(), $parent = NULL) {

        // For Medical Case and Clinical Symptoms questions get only associated items for its respective listing page.
        if ($parent != 0) {

            //get latest version item id details of the parent
            $latestItemDetails = self::getLatestItemId($parent);
            $parentPkId = $latestItemDetails['id'];

            // If parentId is passed then get all the items associated with that parent.
            $qb = $this->em->createQueryBuilder();
            $totalQuery = $qb->select('COUNT(DISTINCT(qi.id)) as total')
                    ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                    ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                    ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                    ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
                    ->where('qi.isDeleted = :isDeleted') // Retrieve only active items
                    ->andWhere('qia.id = :parent') // Retrieve assocaited items
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('parent', $parentPkId);
        } else {
            // Get all items total count with applied filters
            $subQuery = self::getSubQueryForHighestVersion();

            // Fetch All the items based on the applied filters. Retrieve only active items's
            $qb = $this->em->createQueryBuilder();
            $totalQuery = $qb->select('COUNT(DISTINCT(qi.id)) as total')
                    ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                    ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                    ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status');
            if (!empty($metadataRequest)) {
                $totalQuery->leftJoin('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.resourceId=qi.id')
                        ->leftJoin('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=crm.metadata');
            }

            $totalQuery->where($qb->expr()->in('qi.id', $subQuery))
                    ->andWhere('qi.isDeleted = :isDeleted') // Retrieve only active items
                    ->andWhere('qi.parent = :parent') // Retrieve only parent=0 items
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->setParameter('parent', 0);


            // If label filter is passed, then add in to where condition.
            if ($label != "") {
                $totalQuery->andWhere($qb->expr()->like('qi.label', ':label'))
                        ->setParameter('label', '%' . $label . '%');
            }

            // If identifier filter is passed, then add in to where condition.
            if ($identifier != "") {
                $totalQuery->andWhere($qb->expr()->like('qi.qtiIdentifier', ':identifier'))
                        ->setParameter('identifier', '%' . $identifier . '%');
            }

            // If itemTypeId filter is passed, then add in to where condition.
            if ($itemTypeId != "") {
                $totalQuery->andWhere('qit.itemTypeId = :itemTypeId')
                        ->setParameter('itemTypeId', $itemTypeId);
            }

            // If status filter is passed, then add in to where condition.
            if ($status != "") {
                $totalQuery->andWhere('qs.statusName = :status')
                        ->setParameter('status', $status);
            } else {
                $totalQuery->andWhere('qs.statusName IN (:status)')
                        ->setParameter('status', $this->qtiStatusArr);
            }

            // If any metadata filters are passed. 
            if (!empty($metadataRequest)) {

                $andX = $qb->expr()->orX();

                foreach ($metadataRequest as $key => $association) {

                    // Check metadata type for the metadata passed
                    $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                    // Added below code to convert to array for hierarchy data as it has check in IN condition.
                    if (is_numeric($association)) {

                        //Check if associated metadata is of type hierarchy
                        if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                            // Get all the parent ids of the node passed
                            $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);
                            $parentNodes = $childNodes = array();

                            if ($this->app['cache']->fetch('parentNodes') == "YES") {
                                // Get all the parent ids of the node passed
                                $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $association);
                            }

                            if ($this->app['cache']->fetch('childNodes') == "YES") {
                                //Get all the child nodes of the passed node.
                                $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $association);
                            }

                            $association = array_unique(array_merge($parentNodes, $childNodes));
                        }
                    }

                    // Its common for both hierarchy and lookup
                    if (is_array($association)) {

                        $condition = $qb->expr()->andx();

                        $or = $qb->expr()->orX();

                        // Check for metadata Id
                        $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                        $or->add($qb->expr()->in('crm.value', $association));

                        //Check for all the metadata values
//                        foreach ($association as $value) {
//                            $or->add($qb->expr()->in('crm.value', $value));
//                        }

                        $condition->add($or);
                    } else {

                        // Its for free text metadata.
                        $condition = $qb->expr()->andx();
                        $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                        $condition->add($qb->expr()->like('crm.value', $qb->expr()->literal('%' . $association . '%')));
                    }

                    $andX->add($condition);
                }

                $totalQuery->andWhere($andX);
                $totalQuery->andWhere('crm.resourceType=:resourceTypeId')
                        ->setParameter('resourceTypeId', $this->app['cache']->fetch('item'));
            }
        }
        // Get the results
        $totalItems = $qb->getQuery()->getSingleScalarResult();
        return $totalItems;
    }

    /**
     * @Desc : This is the common subquery to get the hightest version of the questions
     * @return type
     */
    public function getSubQueryForHighestVersion($limit = NULL) {

        $subQuery = $this->em->createQueryBuilder()
                ->select('MAX(qia.id)')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qia')
                ->where('qia.isDeleted = :isDeleted') // Retrieve only active items
                ->andWhere('qia.parent = :parent') // Retrieve only parent=0 items
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('parent', 0)
                ->groupBy('qia.itemId');
        if ($limit) {
            $subQuery->setFirstResult(0)
                    ->setMaxResults($limit);
        }

        $finalSubQuery = $subQuery->getDQL();

        return $finalSubQuery;
    }

    /**
     * @Desc : Fetching all item details with pagination.
     * @param type $itemRequest
     * @return list of items
     */
    public function getItemsList($itemRequest, $metadataRequest = array()) {
        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $label = $identifier = $itemTypeId = $status = "";

            //By default get all the questions, incase if parentId != 0 is passed, then its Medical Case or Clinical Symptom question,
            //then get its associated child items.
            $parent = 0;

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "id";

            // Declare a result array to return. 
            $itemReturnValues = array();

            // Check if request is not null.
            if (!empty($itemRequest)) {

                foreach ($itemRequest as $key => $request) {

                    // get values for $label = $identifier = $itemTypeId = $satus ;
                    $$key = $request;
                }

                // Logic found in CommonHelper class to get the offset value.
                $offset = CommonHelper::getOffset($page, $perPage);

                if (isset($sort)) {
                    // If sorting field is passed.
                    // Logic found in CommonHelper class to get the sorting type and sorting field.
                    $sort = CommonHelper::getSortingDetails($sort);

                    // Sorting type ASC/DESC
                    $sortType = $sort['type'];

                    // Sorting field name
                    $sortField = $sort['field'];
                }
            }

            // Get Total of all the items based on the applied filters. 
            $totalItems = self::getItemsCount($label, $identifier, $itemTypeId, $status, $metadataRequest, $parent);

            $itemReturnValues['total'] = $totalItems; // Total item count for pagination.
            $itemReturnValues['data'] = array();

            // Check if count is greater than 0
            if ($totalItems > 0) {

                // For Medical Case and Clinical Symptoms questions get only associated items for its respective listing page.
                if ($parent != 0) {

                    //get latest version item id details of the parent
                    $latestItemDetails = self::getLatestItemId($parent);
                    $parentPkId = $latestItemDetails['id'];

                    // If parentId is passed then get all the items associated with that parent.
                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('qi.itemId as id', 'qi.label as label', 'qi.qtiIdentifier as identifier', 'qit.itemTypeId as itemTypeId', 'qit.itemTypeName as itemType', 'qs.statusName as status', 'qir.sequence as childOrder')
                            ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                            ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.relation')
                            ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.itemPk')
                            ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                            ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
                            ->where('qi.isDeleted = :isDeleted') // Retrieve only active items
                            ->andWhere('qia.id = :parent') // Retrieve assocaited items
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('parent', $parentPkId);
                } else {

                    $subQuery = self::getSubQueryForHighestVersion();

                    // Fetch All the items based on the applied filters. Retrieve only active items's
                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('qi.itemId as id', 'qi.label as label', 'qi.qtiIdentifier as identifier', 'qit.itemTypeId as itemTypeId', 'qit.itemTypeName as itemType', 'qs.statusName as status')
                            ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                            ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                            ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status');
                    if (!empty($metadataRequest)) {
                        $query->leftJoin('QuizzingPlatform\Entity\CmnResourceMetadata', 'crm', \Doctrine\ORM\Query\Expr\Join::WITH, 'crm.resourceId=qi.id')
                                ->leftJoin('QuizzingPlatform\Entity\CmnMetadata', 'cm', \Doctrine\ORM\Query\Expr\Join::WITH, 'cm.metadataId=crm.metadata');
                    }

                    $query->where($qb->expr()->in('qi.id', $subQuery))
                            ->andWhere('qi.isDeleted = :isDeleted') // Retrieve only active items
                            ->andWhere('qi.parent = :parent') // Retrieve only parent=0 items
                            ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                            ->setParameter('parent', 0);

                    // If label filter is passed, then add in to where condition.
                    if ($label != "") {
                        $query->andWhere($qb->expr()->like('qi.label', ':label'))
                                ->setParameter('label', '%' . $label . '%');
                    }

                    // If identifier filter is passed, then add in to where condition.
                    if ($identifier != "") {
                        $query->andWhere($qb->expr()->like('qi.qtiIdentifier', ':identifier'))
                                ->setParameter('identifier', '%' . $identifier . '%');
                    }

                    // If itemTypeId filter is passed, then add in to where condition.
                    if ($itemTypeId != "") {
                        $query->andWhere('qit.itemTypeId = :itemTypeId')
                                ->setParameter('itemTypeId', $itemTypeId);
                    }

                    // If status filter is passed, then add in to where condition.
                    if ($status != "") {
                        $query->andWhere('qs.statusName = :status')
                                ->setParameter('status', $status);
                    } else {
                        $query->andWhere('qs.statusName IN (:status)')
                                ->setParameter('status', $this->qtiStatusArr);
                    }

                    // If any metadata filters are passed. 
                    if (!empty($metadataRequest)) {

                        $andX = $qb->expr()->orX();

                        foreach ($metadataRequest as $key => $association) {

                            // Check metadata type for the metadata passed
                            $tagTypeId = $this->app['metadata.repository']->getMetadataType($key);

                            // Added below code to convert to array for hierarchy data as it has check in IN condition.
                            if (is_numeric($association)) {


                                //Check if associated metadata is of type hierarchy
                                if ($tagTypeId == $this->app['cache']->fetch('HIERARCHY')) {

                                    // Get all the parent ids of the node passed
                                    $metadataHierachyValue = $this->app['metadata.repository']->getHierarchyDetails($key);

                                    $parentNodes = $childNodes = array();

                                    if ($this->app['cache']->fetch('parentNodes') == "YES") {

                                        // Get all the parent ids of the node passed
                                        $parentNodes = $this->app['metadata.service']->getParentNodeIds($metadataHierachyValue, $association);
                                    }

                                    if ($this->app['cache']->fetch('childNodes') == "YES") {

                                        //Get all the child nodes of the passed node.
                                        $childNodes = $this->app['metadata.service']->getChildNodesIds($metadataHierachyValue, $association);
                                    }

                                    $association = array_unique(array_merge($parentNodes, $childNodes));
                                }
                            }

                            // Its common for both hierarchy and lookup
                            if (is_array($association)) {

                                $condition = $qb->expr()->andx();

                                $or = $qb->expr()->orX();

                                // Check for metadata Id
                                $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                                $or->add($qb->expr()->in('crm.value', $association));

                                //Check for all the metadata values
//                                foreach ($association as $value) {
//                                    $or->add($qb->expr()->in('crm.value', $value));
//                                }

                                $condition->add($or);
                            } else {

                                // Its for free text metadata.
                                $condition = $qb->expr()->andx();
                                $condition->add($qb->expr()->eq('cm.metadataId', $qb->expr()->literal($key)));
                                $condition->add($qb->expr()->like('crm.value', $qb->expr()->literal('%' . $association . '%')));
                            }

                            $andX->add($condition);
                        }

                        $query->andWhere($andX);
                        $query->andWhere('crm.resourceType=:resourceTypeId')
                                ->setParameter('resourceTypeId', $this->app['cache']->fetch('item'));
                    }
                }

                // Add limits and sorting to query.
                $query->setFirstResult($offset)
                        ->setMaxResults($perPage)
                        ->orderBy($sortField, $sortType)
                        ->groupBy('qi.id');

                // Get the results
                $itemValues = $qb->getQuery()->getArrayResult(); //getSQL(); 
                $itemReturnValues['data'] = $itemValues;
            }

            //Return the result array
            return $itemReturnValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @Desc Get item type details
     * @param type $itemId
     * @return type
     */
    public function getItemType($itemPkId) {

        // Then get the item type details for the item ID.
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qit.itemTypeId as itemType', 'qit.labelText as labelText', 'qs.statusId', 'qs.statusName')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->join('QuizzingPlatform\Entity\QtiItemType', 'qit', \Doctrine\ORM\Query\Expr\Join::WITH, 'qit.itemTypeId=qi.itemType')
                ->join('QuizzingPlatform\Entity\QtiStatus', 'qs', \Doctrine\ORM\Query\Expr\Join::WITH, 'qs.statusId=qi.status')
                ->where('qi.id = :id')
                ->setParameter('id', $itemPkId);
        $itemValues = $qb->getQuery()->getArrayResult();

        $itemDetails = $itemValues[0];

        return $itemDetails;
    }

    /**
     * @Desc : Publish the item and if it has any child items publish them as well.
     * @param type $itemId
     * @return boolean
     */
    public function publish($itemId) {
        try {

            //get latest version item id details
            $latestItemDetails = self::getLatestItemId($itemId);
            $itemPkId = $latestItemDetails['id'];

            //Fetching the foreign key value(status_id) from qti_status table
            $qtiStatus = $this->em->getRepository('QuizzingPlatform\Entity\QtiStatus')->findOneByStatusName(array('statusName' => 'Published'));

            // Publish the item
            $qtiItem = $this->em->getReference('QuizzingPlatform\Entity\QtiItem', $itemPkId);
            $qtiItem->setStatus($qtiStatus);
            $this->em->flush();

            // Get the item type
            $itemDetails = self::getItemType($itemPkId);

            $itemType = $itemDetails['itemType'];

            // If item type is medical or clinical type questions, then publish thier child items as well.
            if (($itemType == $this->medicalCase) || ($itemType == $this->clinicalSymptoms)) {

                // Fetch all the associated questions to the parent question id. 
                $qb = $this->em->createQueryBuilder();
                $qb->select('qia.id')
                        ->from('QuizzingPlatform\Entity\QtiItemRelation', 'qir')
                        ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qi', \Doctrine\ORM\Query\Expr\Join::WITH, 'qi.id=qir.itemPk')
                        ->leftJoin('QuizzingPlatform\Entity\QtiItem', 'qia', \Doctrine\ORM\Query\Expr\Join::WITH, 'qia.id=qir.relation')
                        ->where('qi.id = :id')
                        ->setParameter('id', $itemPkId);
                $childItemIds = $qb->getQuery()->getArrayResult();

                if (!empty($childItemIds)) {
                    foreach ($childItemIds as $child) {

                        // Publish the child items
                        $qtiItem = $this->em->getReference('QuizzingPlatform\Entity\QtiItem', $child['id']);
                        $qtiItem->setStatus($qtiStatus);
                    }

                    $this->em->flush();
                }
            }


            //Index the question in solr
            $document = $this->app['offlinescripts.repository']->solrIndex($itemId, 'PUBLISH');

            // If its failed to index, then log this question in logs.
            if (!$document) {

                //If failed to index the question, then add to logs.
                $msg = 'Failed to index the Question ' . $itemId;
                $this->app['log']->writeLog($msg);
            }

            return true;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Publish Item Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @param : No parameters
     * @Desc : Repository method to Get the assets Types(Image,Video,Audio)
     * @Return : array, Returns all the asset types.
     */
    public function getAssetTypes() {
        try {

            // Fetch asset types stored in cache
            $assetTypes = $this->app['cache']->fetch('assetTypes');
            $assetTypes = json_decode($assetTypes);

            if (!empty($assetTypes)) {

                // return from cache.
                return $assetTypes;
            } else {

                // If its not set in cache, fetch from DB and set to cache and then return the asset types.
                $qb = $this->em->createQueryBuilder();
                $qb->select('cat.assetTypeId', 'cat.assetTypeName', 'cat.description')
                        ->from('QuizzingPlatform\Entity\CmnAssetType', 'cat')
                        ->orderBy('cat.assetTypeId', 'ASC');
                $query = $qb->getQuery();
                $assetTypes = $query->getArrayResult();

                if (empty($assetTypes)) {

                    //Return false if asset types doesn't exists.
                    return false;
                } else {

                    // Store asset types to cache and then return.
                    $this->app['cache']->store('assetTypes', json_encode($assetTypes));
                    return $assetTypes;
                }
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Asset types listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc Associate/Dissociate item to item collection
     * @param type $itemAssociateData
     * @param type $itemId
     * @return boolean
     */
    public function associateItem($itemAssociateData, $itemId) {

        try {

            //get latest version item id details 
            $latestItemDetails = self::getLatestItemId($itemId);
            $itemPkId = $latestItemDetails['id'];


            //change the item collection string to array
            $itemCollectionData = explode(',', $itemAssociateData['itemBankId']);
            //For Association
            if ($itemAssociateData['associated'] == $this->app['cache']->fetch('ACTIVE')) {
                //Check the Duplicate Association
                foreach ($itemCollectionData as $itemCollectionId) {
                    $qb = $this->em->createQueryBuilder();
                    $query = $qb->select('qibm.id')
                            ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                            ->where('qibm.itemPk= :id')
                            ->setParameter('id', $itemPkId)
                            ->andWhere('qibm.itemBank=:itemBank')
                            ->setParameter('itemBank', $itemCollectionId);
                    $itemCollectionExist = $query->getQuery()->getArrayResult();
                    //If item collection is not associated ,then associate to item
                    if (empty($itemCollectionExist)) {

                        $userId = $itemAssociateData['userId'];

                        //Insert into qti_item_bank_members table
                        $this->app['itemcollection.repository']->createAssociation($itemPkId, $itemCollectionId, $userId);
                    }
                    //Else return error msg
                    else {
                        //If its associated , change the status is active
                        $qb = $this->em->createQueryBuilder();
                        $qb->select('qibm.id')
                                ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                                ->where('qibm.itemBank=:itemBank')
                                ->andwhere('qibm.itemPk=:id')
                                ->setParameter('itemBank', $itemCollectionId)
                                ->setParameter('id', $itemPkId);
                        $id = $qb->getQuery()->getArrayResult();

                        $qtiItemBankMembers = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBankMembers', $id[0]['id']);
                        $qtiItemBankMembers->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                    }
                }
                $this->em->flush();
                return true;
            }
            //For dissociate
            elseif ($itemAssociateData['associated'] == $this->app['cache']->fetch('DELETED')) {
                foreach ($itemCollectionData as $itemCollectionId) {
                    //Change the association status to de
                    self::deleteItemsAssociation($itemPkId, $itemCollectionId);
                }
                return true;
            }
        } catch (Exception $ex) {

            //Add exceptions to logger.
            $msg = 'Item Association Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @Desc Delete the existing item association for recreating it.
     * @param type $itemId
     * @param type $itemCollectionId
     */
    public function deleteItemsAssociation($itemPkId = '', $itemCollectionId = '') {

        //get latest version item id details
//        $latestItemDetails = self::getLatestItemId($itemId);
//        $itemPkId = $latestItemDetails['id'];

        if ($itemPkId && $itemCollectionId) {
            // Delete all item Association for recreate.
            $qb = $this->em->createQueryBuilder();
            $qb->select('qibm.id')
                    ->from('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm')
                    ->where('qibm.itemBank=:itemBank')
                    ->andwhere('qibm.itemPk=:id')
                    ->setParameter('itemBank', $itemCollectionId)
                    ->setParameter('id', $itemPkId);
            $id = $qb->getQuery()->getArrayResult();

            $qtiItemBankMembers = $this->em->getReference('QuizzingPlatform\Entity\QtiItemBankMembers', $id[0]['id']);
            $qtiItemBankMembers->setIsDeleted($this->app['cache']->fetch('DELETED'));
            $this->em->flush();
        }
    }

    /**
     * Get Item Collection details based on the item id
     * @param type $itemId
     * @return type
     */
    public function findItemCollection($itemId) {
        try {

            if ($itemId != '') {

                //get latest version item id details
                $latestItemDetails = self::getLatestItemId($itemId);
                $itemPkId = $latestItemDetails['id'];

                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('qib.itemBankName', 'qib.itemBankId', 'qib.description')
                        ->from('QuizzingPlatform\Entity\QtiItemBank', 'qib')
                        ->leftJoin('QuizzingPlatform\Entity\QtiItemBankMembers', 'qibm', \Doctrine\ORM\Query\Expr\Join::WITH, 'qibm.itemBank=qib.itemBankId')
                        ->where('qib.isDeleted IN (:isDeleted)') // Retrieve only active/inactive items
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                        ->andWhere('qibm.itemPk=:id')
                        ->setParameter('id', $itemPkId);

                $itemCollectionReturnData = $query->getQuery()->getArrayResult();
                $itemCollectionData['data'] = $itemCollectionReturnData;
                return $itemCollectionData;
            }
        } catch (Exception $ex) {

            //Add exceptions to logger.
            $msg = 'Item Collection Listing by item id Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Check Duplicate item title
     * @param type $title
     * @return type
     */
    public function checkDuplicateItem($title) {

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.itemId')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->where('qi.isDeleted IN (:isDeleted)') // Retrieve only active/inactive items
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->andWhere('qi.label=:label')
                ->setParameter('label', $title)
        ;

        $itemExists = $query->getQuery()->getArrayResult();
        return $itemExists;
    }

    public function updateItemIdByIdentifier() {

        $qb = $this->em->createQueryBuilder();
        $qb->select('MAX(qi.itemId) as itemId', 'qi.qtiIdentifier', 'MAX(qi.version) as version')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->groupBy('qi.qtiIdentifier');

        $itemdetails = $qb->getQuery()->getArrayResult();

        //Update the itemId , if identifier is same
        foreach ($itemdetails as $item) {
            $qb = $this->em->createQueryBuilder();
            $qb->update('QuizzingPlatform\Entity\QtiItem', 'qi')
                    ->set('qi.itemId', $item['itemId'])
                    ->where('qi.qtiIdentifier =:qtiIdentifier')
                    ->setParameter('qtiIdentifier', $item['qtiIdentifier'])
                    ->getQuery()->execute();
            //Insert into latestversion table
            $qtiItemLatestVersion = new QtiItemLatestVersion();
            $qtiItemLatestVersion->setItemId($item['itemId']);
            $qtiItemLatestVersion->setVersion($item['version']);
            $this->em->persist($qtiItemLatestVersion);
            $this->em->flush();
        }

        return true;
    }

    /**
     * @Desc Get item details if itemid and version are passed
     * @param type $itemId
     * @param type $version
     * @return type
     */
    public function getItemPkByVersion($itemId, $version = NULL, $quizEngine = NULL) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('qi.id', 'qi.itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->where('qi.itemId = :itemId')
                ->setParameter('itemId', $itemId);

        if (!empty($version)) {
            $query->andWhere('qi.version=:version')
                    ->setParameter('version', $version);
        }

        if (!$quizEngine) {
            $query->andWhere('qi.isDeleted = :isDeleted') // Retrieve only active items
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
        }

        $itemData = $qb->getQuery()->getArrayResult();

        return $itemData[0];
    }

    /**
     * @Desc get itemid and version details if itemPkid is passed
     * @param type $itemPk
     * @return type
     */
    public function getItemIdByPkId($itemPk) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('qi.itemId', 'qi.version')
                ->from('QuizzingPlatform\Entity\QtiItem', 'qi')
                ->where('qi.id = :id')
                ->setParameter('id', $itemPk);

        $itemData = $qb->getQuery()->getArrayResult();
        return $itemData[0];
    }

    /**
     * @Desc get all the  itemIds of the passed itemPkid's
     * @param type $itemPkIds
     * @return type
     */
    public function getItemIdForPkIds($itemPkIds) {

        $itemIds = array();
        if (!empty($itemPkIds)) {

            foreach ($itemPkIds as $itemPk) {

                $itemIdDetails = self::getItemIdByPkId($itemPk);
                $itemIds[] = $itemIdDetails['itemId'];
            }
        }

        return $itemIds;
    }

    /**
     * Get itemTypeId By itemTypeName (LIKE  Muliple choice ...)
     * @param type $questionType
     * @return type
     */
    public function getItemTypeIdByName($questionType) {
        $itemType = $this->em->getRepository('QuizzingPlatform\Entity\QtiItemType')->findOneByItemTypeName(array('itemTypeName ' => $questionType));
        $itemTypeId = empty($itemType) ? '' : $itemType->getItemTypeId(); //Get itemtypeId
        return $itemTypeId;
    }

    /**
     * Get Remediation Link typeid By Remediatin link Name ( like weblink , text , Ebook)
     * @param type $linkType
     * @return type
     */
    public function getRemediationLinkIdByName($linkType) {
        $remediationLinkType = $this->em->getRepository('QuizzingPlatform\Entity\QtiRemediationLinkType')->findOneByRemediationLinkTypeName(array('remediationLinkTypeName ' => $linkType));
        $remediationLinkId = $remediationLinkType->getRemediationLinkTypeId();
        return $remediationLinkId;
    }

    /**
     * Get AssetTypeId by AssetName (like image , audio , video)
     * @param type $assetType
     * @return type
     */
    public function getAssetTypeIdByName($assetType) {
        $assetTypeObj = $this->em->getRepository('QuizzingPlatform\Entity\CmnAssetType')->findOneByAssetTypeName(array("assetTypeName" => $assetType));
        $assetTypeId = $assetTypeObj->getAssetTypeId();
        return $assetTypeId;
    }

}
