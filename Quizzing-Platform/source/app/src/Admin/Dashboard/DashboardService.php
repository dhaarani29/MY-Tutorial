<?php

/*
 * DashboardService - Dashboard module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Srinivasu M
 */

namespace QuizzingPlatform\Admin\Dashboard;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardService {

    protected $app;
    protected $em;

    public function __construct(Application $app) {
        $this->app = $app;
        //$this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));
    }
    
    /**
     * @desc - get the Quiz count information
     * @return array of Quiz count info (or) false
     */
    public function getQuizCount()
    {
        // Module is not yet developed
        return array("quizCount"=>0);
        
    }
    
    /**
     * 
     * @param type $userId - User id
     * @return Array of Dashboard items (url, count & title)
     */
    public function getDashboardCount($userId = NULL) 
    {
        $returnData = array();
        if($userId)
        {
            $i=0;
            // check permission and get the count information for items List
            $questionTypePermission = $this->app['users.repository']->checkResourceActionPermission($userId,'items','view');
         //   if(isset($questionTypePermission) && $questionTypePermission[0] == 'view'){
                
                $questionTypeCountData = $this->app['dashboard.repository']->getQuestionTypeCount();
                if(isset($questionTypeCountData) && $questionTypeCountData['questionTypeCount'] >= 0) { 
                    $returnData[$i]['url'] = '/itemtype/list';
                    $returnData[$i]['title'] = "QUESTION_TYPE";
                    $returnData[$i]['count'] = $questionTypeCountData['questionTypeCount'];
                }
         //   }
  
            //Get metadata count info
            $metaDataTagsPermission = $this->app['users.repository']->checkResourceActionPermission($userId,'metadata','view');
            if(isset($metaDataTagsPermission) && $metaDataTagsPermission[0] == 'view'){

                $metaDataTagsCount = $this->app['metadata.repository']->getMetadataCount();
                if (isset($metaDataTagsCount) && $metaDataTagsCount >= 0) { 
                    $i++;
                    $returnData[$i]['url'] = '/metadata/list';
                    $returnData[$i]['title'] = "METADATA_TAGS";
                    $returnData[$i]['count'] = $metaDataTagsCount;
                }
            }

            //Get questions count info
            $questionDataPermission = $questionTypePermission;//$this->app['users.repository']->checkResourceActionPermission($userId,'items','view');
            if(isset($questionDataPermission) && $questionDataPermission[0] == 'view'){

                $questionData = $this->app['items.repository']->getItemsCount(NULL, NULL, NULL, NULL, array(), $parent = 0);
                if (isset($questionData) && $questionData >= 0 ) { 
                    $i++;
                    $returnData[$i]['url'] = '/item/list';
                    $returnData[$i]['title'] = "QUESTIONS";
                    $returnData[$i]['count'] = $questionData;
                }
            }

            //Get Question Bank count info
            $questionBankPermission = $this->app['users.repository']->checkResourceActionPermission($userId,'itembanks','view');
            if(isset($questionBankPermission) && $questionBankPermission[0] == 'view'){
                
                $questionBankCountData = $this->app['itemcollection.repository']->getitemCollectionCount($bankName = NULL, $description = NULL, $status = NULL, $active = NULL, $metadataRequest = NULL, $associatedItemCollection = NULL, $associatedItemId = NULL);
                if (isset($questionBankCountData) && $questionBankCountData[0]['total'] >= 0) {
                    $i++;
                    $returnData[$i]['url'] = '/itembank/list';
                    $returnData[$i]['title'] = "QUESTION_BANK";
                    $returnData[$i]['count'] = $questionBankCountData[0]['total'];
                }
            }

            //Get Quiz count info
            $quizPermission = $this->app['users.repository']->checkResourceActionPermission($userId,'tests','view');
            if(isset($quizPermission) && $quizPermission[0] == 'view'){
                $quizCountData = $this->app['tests.repository']->getTestCount(NULL, NULL, NULL, NULL);
                if (isset($quizCountData) && $quizCountData >= 0) { 
                    $i++;
                    $returnData[$i]['url'] = '/test/list';
                    $returnData[$i]['title'] = "QUIZ";
                    $returnData[$i]['count'] = $quizCountData;
                }
            }


            //Get User count info
            $userPermission = $this->app['users.repository']->checkResourceActionPermission($userId,'user','view');
            if(isset($userPermission) && $userPermission[0] == 'view'){
                
                $userCountData = $this->app['users.repository']->getAllUsersCount();
                if (isset($userCountData) && count($userCountData) >= 0) { 
                    $i++;
                    $returnData[$i]['url'] = '/user/list';
                    $returnData[$i]['title'] = "USERS";
                    $returnData[$i]['count'] = count($userCountData);
                }
            }
        }
        return $returnData;
    }
    
    
}
