<?php

/**
 * SystemsettingsRepository - It's a model class file to handle the system settings module database operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * 
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Systemsettings;

use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;

class SystemsettingsRepository {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $this->app['config']['dbDate'];
    }

    /**
     * @param : No parameters
     * @desc : Get all configurations related to UI stored in configuration settings and return them.
     * @return : array with list of config variables
     */
    public function getUIConfigurations() {
        try {

            // Get UI configs from config variable and return.
            if (!empty($this->app['uiConfig'])) {
                return $this->app['uiConfig'];
            } else {
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'System config retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @desc  sets configs to config variables and Sets STATUS CODES, HTTP CODES, METADATA TYPES etc in to cache.
     * @return boolean
     */
    public function setConfigs() {

        try {

            $sysConfig = $this->app['cache']->fetch('appname');
            if (!$sysConfig) {
                //Get admin system settings from database and assign it to config variables
                $qb = $this->em->createQueryBuilder();
                $qb->select('sss.systemKey', 'sss.systemValue')
                        ->from('QuizzingPlatform\Entity\SecSystemSetting', 'sss')
                        ->where('sss.portal = :portal')
                        ->setParameter('portal', 'AUP');
                $query = $qb->getQuery();
                $adminSettings = $query->getArrayResult();

                if (!empty($adminSettings)) {
                    foreach ($adminSettings as $asKey => $asVal) {
                        $this->app['cache']->store($asVal['systemKey'], $asVal['systemValue']);
                    }
                } else {
                    return false;
                }


                //Get end user system settings from database and assign it to UIconfig variables
                $qb = $this->em->createQueryBuilder();
                $qb->select('sss.systemKey', 'sss.systemValue')
                        ->from('QuizzingPlatform\Entity\SecSystemSetting', 'sss')
                        ->where('sss.portal = :portal')
                        ->setParameter('portal', 'EUP');
                $query = $qb->getQuery();
                $adminEUPSettings = $query->getArrayResult();
                if (!empty($adminEUPSettings)) {
                    foreach ($adminEUPSettings as $asEupKey => $asEupVal) {
                        $this->app['cache']->store($asEupVal['systemKey'], $asEupVal['systemValue']);
                    }
                } else {
                    return false;
                }
            }

            // Set cmn STATUS codes to cache to use across the application. 
            $cmnstatus = $this->app['cache']->fetch('ACTIVE');

            if (!$cmnstatus) {

                //Get common Status codes to use across application.
                $qb = $this->em->createQueryBuilder();
                $qb->select('qs.statusCode', 'qs.statusName')
                        ->from('QuizzingPlatform\Entity\CmnStatus', 'qs');
                $query = $qb->getQuery();
                $statusList = $query->getArrayResult();

                if (!empty($statusList)) {

                    foreach ($statusList as $status) {
                        $this->app['cache']->store($status['statusName'], $status['statusCode']);
                    }
                } else {
                    return false;
                }
            }

            // Set HTTP CODES in to cache to use across the application.
            $httpcodes = $this->app['cache']->fetch('HTTP_SUCCESS');

            if (!$httpcodes) {

                //Get common Status codes to use across application.
                $qb = $this->em->createQueryBuilder();
                $qb->select('ch.httpCode', 'ch.httpcodeName')
                        ->from('QuizzingPlatform\Entity\CmnHttpcodes', 'ch');
                $query = $qb->getQuery();
                $httpcodes = $query->getArrayResult();

                if (!empty($httpcodes)) {

                    foreach ($httpcodes as $codes) {
                        $this->app['cache']->store($codes['httpcodeName'], $codes['httpCode']);
                    }
                } else {
                    return false;
                }
            }


            // set metadata types labels and values in to cache
            $metatype = $this->app['cache']->fetch('FREE_TEXT');

            if (!$metatype) {

                //Get common Status codes to use across application.
                $qb = $this->em->createQueryBuilder();
                $qb->select('cmt.metadataTypeId', 'cmt.metadataLabel ')
                        ->from('QuizzingPlatform\Entity\CmnMetadataType', 'cmt');
                $query = $qb->getQuery();
                $metatype = $query->getArrayResult();

                if (!empty($metatype)) {

                    foreach ($metatype as $type) {
                        $this->app['cache']->store($type['metadataLabel'], $type['metadataTypeId']);
                    }
                } else {
                    return false;
                }
            }


            // set question/item types labels and values in to cache
            $itemtype = $this->app['cache']->fetch('MULTIPLE_CHOICE');

            if (!$itemtype) {

                //Get item types 
                $qb = $this->em->createQueryBuilder();
                $qb->select('qti.labelText', 'qti.itemTypeId')
                        ->from('QuizzingPlatform\Entity\QtiItemType', 'qti');
                $query = $qb->getQuery();
                $itemtype = $query->getArrayResult();

                if (!empty($itemtype)) {

                    foreach ($itemtype as $type) {
                        $this->app['cache']->store($type['labelText'], $type['itemTypeId']);
                    }
                } else {
                    return false;
                }
            }


            // set asset types labels and values in to cache
            $assetType = $this->app['cache']->fetch('Image');

            if (!$assetType) {

                //Get asset types 
                $qb = $this->em->createQueryBuilder();
                $qb->select('qat.assetTypeId', 'qat.assetTypeName')
                        ->from('QuizzingPlatform\Entity\CmnAssetType', 'qat');
                $query = $qb->getQuery();
                $assetType = $query->getArrayResult();

                if (!empty($assetType)) {

                    foreach ($assetType as $type) {
                        $this->app['cache']->store($type['assetTypeName'], $type['assetTypeId']);
                    }
                } else {
                    return false;
                }
            }

            // set test type id and values into cache
            $testType = $this->app['cache']->fetch('testType');

            if (!$testType) {

                //Get test types 
                $qb = $this->em->createQueryBuilder();
                $qb->select('qtt.testTypeId', 'qtt.testTypeName')
                        ->from('QuizzingPlatform\Entity\QtiTestType', 'qtt');
                $query = $qb->getQuery();
                $testType = $query->getArrayResult();
                if (!empty($testType)) {
                    //Store testype and id
                    foreach ($testType as $type) {
                        $this->app['cache']->store($type['testTypeName'], $type['testTypeId']);
                    }
                } else {
                    return false;
                }
            }

            // set test type id and values into cache
            $testStatus = $this->app['cache']->fetch('Completed');

            if (!$testStatus) {

                //Get test types 
                $qb = $this->em->createQueryBuilder();
                $qb->select('qts.testStatusId', 'qts.testStatusName')
                        ->from('QuizzingPlatform\Entity\QtiTestStatus', 'qts');
                $query = $qb->getQuery();
                $testStatus = $query->getArrayResult();
                if (!empty($testStatus)) {
                    //Store testype and id
                    foreach ($testStatus as $status) {
                        $this->app['cache']->store($status['testStatusName'], $status['testStatusId']);
                    }
                } else {
                    return false;
                }
            }

            // set resource types labels and values in to cache
            $resourceType = $this->app['cache']->fetch('item');

            if (!$resourceType) {

                //Get asset types 
                $qb = $this->em->createQueryBuilder();
                $qb->select('crt.resourceTypeId', 'crt.resourceType')
                        ->from('QuizzingPlatform\Entity\CmnResourceType', 'crt');
                $query = $qb->getQuery();
                $resourceType = $query->getArrayResult();

                if (!empty($resourceType)) {

                    foreach ($resourceType as $type) {
                        $this->app['cache']->store($type['resourceType'], $type['resourceTypeId']);
                    }
                } else {
                    return false;
                }
            }

            // set client details to cache
            $clients = $this->app['cache']->fetch('WK Admin');

            if (!$clients) {

                //Get client details
                $qb = $this->em->createQueryBuilder();
                $qb->select('cc.clientId', 'cc.clientName')
                        ->from('QuizzingPlatform\Entity\CmnClient', 'cc');
                $query = $qb->getQuery();
                $clientDetails = $query->getArrayResult();

                if (!empty($clientDetails)) {

                    foreach ($clientDetails as $client) {
                        $this->app['cache']->store($client['clientName'], $client['clientId']);
                    }
                } else {
                    return false;
                }
            }

            // set user type details to cache
            $userTypes = $this->app['cache']->fetch('EUP');

            if (!$userTypes) {

                //Get client details
                $qb = $this->em->createQueryBuilder();
                $qb->select('out.userTypeId', 'out.userTypeName')
                        ->from('QuizzingPlatform\Entity\OrgUserType', 'out');
                $query = $qb->getQuery();
                $userTypeDetails = $query->getArrayResult();

                if (!empty($userTypeDetails)) {

                    foreach ($userTypeDetails as $type) {
                        $this->app['cache']->store($type['userTypeName'], $type['userTypeId']);
                    }
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Failed to set configs in to cache  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : access token of a logged in user
     * @Desc Get all the menus list for a logged in user and then form to Hierarchical tree using metadata createTreeStructure function 
     * @return array with list of menus.
     */
    public function getMenusList($accessToken) {

        try {

            // Get user id using access token passed.
            $loggedUserId = $this->app['users.repository']->getUserIdByToken($accessToken);

            // Gel all the permissions of a user who logged in
            $permissions = $this->app['users.repository']->getUserPermission($loggedUserId);

            // Get the menus list from permission.
            $menus = $this->app['systemsettings.service']->getResourceMenus($permissions);

            //Get all menus using menus list for logged in user.
            $qb = $this->em->createQueryBuilder();
            $qb->select('sm.id', 'sm.parentId as parentId', 'sm.name', 'sm.url', 'sm.menuorder', 'sm.status')
                    ->from('QuizzingPlatform\Entity\SecMenu', 'sm')
                    ->where('sm.status = :status')
                    ->andWhere('sm.name IN (:name)')
                    ->setParameter('status', 1)
                    ->setParameter('name', $menus)
                    ->orderBy('sm.menuorder', 'ASC');
            $query = $qb->getQuery();
            $menusList = $query->getArrayResult();

            if (!empty($menusList)) {

                // Form the hierarchical structure for menus using metadata service function.
                $menusHierarchy = $this->app['metadata.service']->createHierarchy($menusList, 0);
                return $menusHierarchy;
            } else {
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Menus list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : No parameters
     * @Desc  clear/flush the redis cache 
     * @return true/false
     */
    public function clearCache() {

        try {
            // Use redis cache clear function to clear the cache.
            if ($this->app['cache']->clear()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Menus list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : List of input params
     * @Desc : Get all the Countries list
     * @return array of Countries list.
     */
    public function getCountries() {

        try {

            //Get all the countries.
            $qb = $this->em->createQueryBuilder();
            $qb->select('ctry.countryId', 'ctry.countryName')
                    ->from('QuizzingPlatform\Entity\CmnCountryMaster', 'ctry')
                    ->orderBy('ctry.orderCountry', 'ASC');
            $query = $qb->getQuery();
            $countriesList = $query->getArrayResult();
            return $countriesList;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Countries list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @param : Int $countryId - get the states belongs to the Input country Id
     * @Desc : Get all the States list
     * @return array of states list.
     */
    public function getStates($countryId = NULL) {

        try {

            //Get all the countries.
            $qb = $this->em->createQueryBuilder();

            $qb->select('sts.stateId', 'sts.stateName', 'sts.countryId')
                    ->from('QuizzingPlatform\Entity\CmnStateMaster', 'sts')
                    ->andWhere('sts.countryId = :countryId')
                    ->setParameter('countryId', $countryId)
                    ->orderBy('sts.stateName', 'ASC');
            $query = $qb->getQuery();
            $statesList = $query->getArrayResult();
            return $statesList;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'States list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @param : Int $groupId - get the group details belongs to the Input group Id
     * @Desc : Get all the Group list
     * @return array of Group list.
     */
    public function getGroups($groupId = NULL) {

        try {

            //Get all the countries.
            $qb = $this->em->createQueryBuilder();
            if ($groupId > 0) {
                $qb->select('grp.groupId', 'grp.groupName', 'grp.description')
                        ->from('QuizzingPlatform\Entity\OrgGroup', 'grp')
                        ->andWhere('grp.groupId = :groupId')
                        ->setParameter('groupId', $groupId);
            } else {
                $qb->select('grp.groupId', 'grp.groupName', 'grp.description')
                        ->from('QuizzingPlatform\Entity\OrgGroup', 'grp')
                        ->orderBy('grp.groupId', 'ASC');
            }
            $query = $qb->getQuery();
            $groupList = $query->getArrayResult();
            if ($groupId > 0) {
                return $groupList[0];
            }
            return $groupList;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Group list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @param : Int $roleId - Role id to fetch information specific to role id
     * @Desc : Get all the roles list
     * @return array of roles list.
     */
    public function getRoles($roleId = NULL) {

        try {

            //Get all the countries.
            $qb = $this->em->createQueryBuilder();
            if ($roleId > 0) {
                $qb->select('roles.roleId', 'roles.roleName', 'roles.description')
                        ->from('QuizzingPlatform\Entity\SecRole', 'roles')
                        ->andWhere('roles.roleId = :roleId')
                        ->setParameter('roleId', $roleId);
            } else {
                $qb->select('roles.roleId', 'roles.roleName', 'roles.description')
                        ->from('QuizzingPlatform\Entity\SecRole', 'roles')
                        ->orderBy('roles.roleId', 'ASC');
            }

            $query = $qb->getQuery();
            $rolesList = $query->getArrayResult();
            if ($roleId > 0) {
                return $rolesList[0];
            }
            return $rolesList;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Roles list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Fetch All status information
     * @return boolean
     */
    public function getAllStatus() {
        try {
            //Get all the status.
            $qb = $this->em->createQueryBuilder();

            $qb->select('status.statusId', 'status.statusCode', 'status.statusName', 'status.description')
                    ->from('QuizzingPlatform\Entity\CmnStatus', 'status')
                    ->orderBy('status.statusId', 'ASC');


            $query = $qb->getQuery();
            $statusList = $query->getArrayResult();
            return $statusList;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Status list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Fetch all User type
     * @return boolean
     */
    public function getUserType() {
        try {
            //Get all the usertype.
            $qb = $this->em->createQueryBuilder();

            $qb->select('out.userTypeId', 'out.userTypeName', 'out.description')
                    ->from('QuizzingPlatform\Entity\OrgUserType', 'out')
                    ->orderBy('out.userTypeId', 'ASC');

            $query = $qb->getQuery();
            $userTypeList = $query->getArrayResult();
            return $userTypeList;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'User Type list retrieve Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * @Desc - Get the system Constant values setting configuration
     */
    public function getSystemconfiguration() {
        $systemSettingsArray = array();
        $qb = $this->em->createQueryBuilder();
        $qb->select('ss.systemKey', 'ss.systemValue', 'ss.keyDefinition')
                ->from('QuizzingPlatform\Entity\SecSystemSetting', 'ss')
                ->andWhere('ss.accessFlag = :accessFlag')
                ->setParameter('accessFlag', 1);
        $query = $qb->getQuery();
        $systemSettings = $query->getArrayResult();
        foreach ($systemSettings as $key => $val) {
            $systemSettingsArray[$val['systemKey']] = $val['systemValue'];
        }
        return array($systemSettingsArray);
    }

    public function update($updateSysSettings) {
        if (!empty($updateSysSettings) && $updateSysSettings['userId'] > 0) {

            $userId = $updateSysSettings['userId'];
            unset($updateSysSettings['userId']);
            unset($updateSysSettings['info']);

            foreach ($updateSysSettings as $key => $val) {
                $systemSetting = $this->em->getRepository('QuizzingPlatform\Entity\SecSystemSetting')->findOneBySystemKey(array('SystemKey' => $key));
                $systemSetting->setSystemValue($val);
                $systemSetting->setModifiedBy($userId);
                $systemSetting->setModifiedDate($this->dateTime);

                $this->em->flush();
            }
            // Clearing the cache value for appname variable to reset the System Config values into cache
            $this->app['cache']->store('appname', NULL);
            self::setConfigs();
            return true;
        }
    }

}
