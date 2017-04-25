<?php

/**
 * DashboardRepository - It's the model class file to handle the Dashboard module database operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Srinivasu M
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Dashboard;

use Silex\Application;
use QuizzingPlatform\Services\CommonHelper;

//Entity Files
use QuizzingPlatform\Entity\OrgUserProfile;
use QuizzingPlatform\Entity\QtiItemType;

use QuizzingPlatform\Entity\CmnAsset;


class DashboardRepository {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->effectiveDateTo = $app['config']['effectiveDateTo'];
        $this->statusArr = array($this->app['cache']->fetch('ACTIVE'), $this->app['cache']->fetch('INACTIVE'));

    }

    /**
     * @desc - get the question's type information count
     * @return boolean / Array of questions type info
     */
    function getQuestionTypeCount()
    {
        
        try {
            
            // If its not set in cache, fetch from DB and set to cache and then return the question types.
            $qb = $this->em->createQueryBuilder();
            $qb->select('COUNT(it.itemTypeId) AS questionTypeCount')
                    ->from('QuizzingPlatform\Entity\QtiItemType', 'it')
                    ->where('it.status = :status')
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            
            $query = $qb->getQuery();
            $itemTypes = $query->getArrayResult();
            if(isset($itemTypes[0]['questionTypeCount'])){
                return $itemTypes[0];
            } else {
                return false;
            }
            
            
        } catch (Exception $ex) {
            
            //Add exceptions to logger.
            $msg = 'Dashboard Item types count Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
        
    }
    
}
