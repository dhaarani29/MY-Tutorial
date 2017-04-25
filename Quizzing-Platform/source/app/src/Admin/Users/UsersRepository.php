<?php

/**
 * UsersRepository - It's the model class file to handle the users/Groups/Roles module database operations.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Users;

//Entity Namespace
use QuizzingPlatform\Entity\OrgUserProfile;
use QuizzingPlatform\Entity\OrgGroupUsers;
use QuizzingPlatform\Entity\OrgGroup;
use QuizzingPlatform\Entity\SecRole;
use QuizzingPlatform\Entity\CmnCountryMaster;
use QuizzingPlatform\Entity\CmnStateMaster;
use QuizzingPlatform\Entity\SecUserRoleAssociation;
use QuizzingPlatform\Entity\QtiItemType;
use QuizzingPlatform\Entity\OrgUserType;
use QuizzingPlatform\Entity\OrgUserProfileExtension;
use QuizzingPlatform\Entity\OrgGroupRoleAssociation;
use QuizzingPlatform\Entity\orgUserAuth;
//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;

class UsersRepository {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
    }

    /**
     * @param : No parameters
     * @Desc : This method checks for the user resource permissions
     * @Return : Returns users permissions based on the resource and the action.
     */
    public function checkResourceActionPermission($userId, $resource = "", $action = "", $callFromAction = NULL) {

        try {

            $userBelongsTo = NULL;

            $qbInstance = $this->em->createQueryBuilder();
            $permissionQuery = $qbInstance->select('usr.userBelongsTo')
                    ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                    ->where('usr.userId = :userId')
                    ->setParameter('userId', $userId);
            $resultQuery = $qbInstance->getQuery();
            $userInfo = $resultQuery->getArrayResult();
            if (isset($userInfo) && $userInfo[0]['userBelongsTo'] > 0) {
                $userBelongsTo = $userInfo[0]['userBelongsTo'];  // 1=> Role & 2=> Group
            }

            $qb = $this->em->createQueryBuilder();
            if ($userBelongsTo == 1) {
                // Check permission using SecUserRoleAssociation mapping
                if ($callFromAction == 'getUserPermission') {
                    $permissionQuery = $qb->select('distinct(sp.resource) as resource');
                } else {
                    $permissionQuery = $qb->select('sp.action');
                }

                $permissionQuery->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                        ->join('QuizzingPlatform\Entity\SecRolePermissions', 'srp', \Doctrine\ORM\Query\Expr\Join::WITH, 'srp.permission=sp.permissionId')
                        ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=srp.role')
                        ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.roleId=sura.roleId')
                        ->where('sp.status = :permissionstatus')
                        ->andWhere('sr.status = :rolestatus')
                        ->andWhere('sura.userId = :userid')
                        ->setParameter('permissionstatus', 1)
                        ->setParameter('rolestatus', 1)
                        ->setParameter('userid', $userId);
            } else if ($userBelongsTo == 2) {
                // Check permission using org_group_role_association mapping
                if ($callFromAction == 'getUserPermission') {
                    $permissionQuery = $qb->select('distinct(sp.resource) as resource');
                } else {
                    $permissionQuery = $qb->select('sp.action');
                }

                $permissionQuery->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                        ->join('QuizzingPlatform\Entity\SecRolePermissions', 'srp', \Doctrine\ORM\Query\Expr\Join::WITH, 'srp.permission=sp.permissionId')
                        ->join('QuizzingPlatform\Entity\OrgGroupRoleAssociation', 'ogra', \Doctrine\ORM\Query\Expr\Join::WITH, 'srp.role=ogra.role')
                        ->join('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogra.group=ogu.group')
                        ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogra.role=sr.roleId')
                        ->where('sp.status = :permissionstatus')
                        ->andWhere('sr.status = :rolestatus')
                        ->andWhere('ogu.user = :userid')
                        ->setParameter('permissionstatus', 1)
                        ->setParameter('rolestatus', 1)
                        ->setParameter('userid', $userId);
            } else {
                // Previous Code
                if ($callFromAction == 'getUserPermission') {
                    $permissionQuery = $qb->select('distinct(sp.resource) as resource');
                } else {
                    $permissionQuery = $qb->select('sp.action');
                }

                $permissionQuery->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                        ->join('QuizzingPlatform\Entity\SecRolePermissions', 'srp', \Doctrine\ORM\Query\Expr\Join::WITH, 'srp.permission=sp.permissionId')
                        ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=srp.role')
                        ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.roleId=sura.roleId')
                        ->where('sp.status = :permissionstatus')
                        ->andWhere('sr.roleId = :rolestatus')
                        ->andWhere('sura.userId = :userid')
                        ->setParameter('permissionstatus', 1)
                        ->setParameter('rolestatus', 1)
                        ->setParameter('userid', $userId);
            }

            // If resource  filters is passed, then add in to where condition.
            if ($resource != "") {
                $permissionQuery->andWhere($qb->expr()->eq('sp.resource', ':resource'))
                        ->setParameter('resource', $resource);
            }

            // pass action to where condition if action is passed.
            if ($action != "") {
                $permissionQuery->andWhere($qb->expr()->eq('sp.action', ':action'))
                        ->setParameter('action', $action);
            }

            $query = $qb->getQuery();
            $permissions = $query->getArrayResult();

            $permissionArray = array();
            foreach ($permissions as $key => $value) {
                // List the resources to list in the menu bar, call request from getUserPermission()
                if ($callFromAction == 'getUserPermission') {
                    $permissionArray[] = $value['resource'];
                } else {
                    //Check the permission for the given module, action & per user.
                    $permissionArray[] = $value['action'];
                }
            }

            // If any error occures while fetching return false.  
            if (empty($permissionArray)) {
                return false;
            }
            // Else return the permission array. 
            else {
                return $permissionArray;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item types listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Create the user Information
     * @param Array $userData - input user info, Output created id / Error message.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     */
    public function create($userData = array(), $accessToken = '', $requestFrom = '') {
        try {

            // GET THE USER NAME / EMAIL TO CHECK ALREADY EXIST
            $userEmail = ($userData['userEmail']) ? trim($userData['userEmail']) : NULL;
            $userName = trim($userData['userName']);
            //Check for duplicate user info.
            $userdataExists = self::checkUserAlreadyExists(NULL, $userEmail, $userName);

            // If user data not exists, then proceed to create new user data.
            if (empty($userdataExists)) {
                $createdUserId = FALSE;


                $clientId = $this->app['login.service']->getClientId($accessToken, $requestFrom);
                $clientPkId = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByClientId(array('clientId' => $clientId));
                $userData['clientPkId'] = $clientPkId;
                //Create User MASTER PROFILE details
                $createdUserId = self::createUserMasterProfile($userData, 'create');

                if ($createdUserId) {

                    //Create User SUB PROFILE details
                    $subUserProfileId = self::createSubUserProfile($userData, $createdUserId, 'create');

                    if ($subUserProfileId) {
                        $selectedRoleGroup = trim($userData['selectedRoleGroup']);
                        // GROUP / ROLES DATA               
                        $getGroups = (isset($userData['getGroups'])) ? ($userData['getGroups']) : NULL;
                        $getRoles = (isset($userData['getRoles'])) ? ($userData['getRoles']) : NULL;
                        $userId = trim($userData['userId']);

                        if ($selectedRoleGroup == $this->app['userStatus']['GROUP'] && !empty($getGroups)) {

                            // Creating GROUPS information to Newly created User
                            $groupsCreated = self::createGroupsToUser($getGroups, $createdUserId, $userId);
                            if (!$groupsCreated) {
                                $this->app['log']->writeLog("Failed to store Group information for user data : " . $createdUserId);
                            }
                        } else if ($selectedRoleGroup == $this->app['userStatus']['ROLE'] && !empty($getRoles)) {

                            // Creating ROLES information to Newly created User
                            $rolesCreated = self::createRolesToUser($getRoles, $createdUserId, $userId);
                            if (!$rolesCreated) {
                                $this->app['log']->writeLog("Failed to store Roles information for user data : " . $createdUserId);
                            }
                        }

                        $this->app['log']->writeLog("Successfully created User data : " . $createdUserId);

                        // Return newly created user id
                        return $createdUserId;
                    } else {

                        $deletedMasterUserId = self::deleteMasterUserProfile($createdUserId);
                        $this->app['log']->writeLog("Failed in creating the user information : " . $createdUserId);
                        return false;
                    }
                } else {
                    return false;
                }
            } else {

                // If user exists, then return exists keyword.
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Userdata creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Insert into org_user_profile table
     * @param type $userMasterData
     * @param type $action
     * @param type $urlUserId
     * @return type
     */
    public function createUserMasterProfile($userMasterData = array(), $action = NULL, $urlUserId = NULL) {
        $createdUserId = NULL;
        if ($userMasterData) {
            //USER PROFILE MASTER DATA
            $userName = trim($userMasterData['userName']);
            $userEmail = trim($userMasterData['userEmail']);
            $passWord = self::encodePassword($userMasterData['password']);
            $firstName = trim($userMasterData['firstName']);
            $middleInitial = (isset($userMasterData['middleInitial'])) ? trim($userMasterData['middleInitial']) : NULL;
            $lastName = (isset($userMasterData['lastName'])) ? trim($userMasterData['lastName']) : NULL;
            $status = trim($userMasterData['status']);
            $isDeleted = $this->app['isDeleted']['ACTIVE'];
            $userId = trim($userMasterData['userId']);
            $clientPkId = $userMasterData['clientPkId'];
            if (!isset($userMasterData['resource']) && $userMasterData['resource'] != 'myprofile') {
                $selectedRoleGroup = trim($userMasterData['selectedRoleGroup']);
                if ($selectedRoleGroup == $this->app['userStatus']['GROUP']) {
                    $userBelongsToFlag = $this->app['userStatus']['GROUP'];
                } else if ($selectedRoleGroup == $this->app['userStatus']['ROLE']) {
                    $userBelongsToFlag = $this->app['userStatus']['ROLE'];
                }
            }


            //Fetching the foreign key value(userTypeId) from org_user_type table
            $userType = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserType')->findOneByUserTypeId(array('userTypeId' => $userMasterData['userType']));
            $orgUserProfile = NULL;
            if ($action == 'create') {
                /**
                 * Insertion for org_user_profile table
                 */
                //Creating the object for org_user_profile
                $orgUserProfile = new orgUserProfile();
                $orgUserProfile->setPassword($passWord);
                $orgUserProfile->setIsDeleted($isDeleted);
                $orgUserProfile->setCreatedBy($userId);
                $orgUserProfile->setCreatedDate($this->dateTime);
                $orgUserProfile->setEffectiveDate($this->dateTime);
            }
            if ($action == 'update') {

                // Update org_user_profile table
                $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $urlUserId);
                $orgUserProfile->setclientUserId($urlUserId);
                if ($userMasterData['changePassword'] === true) {//If changepassword is true,update the password
                    $orgUserProfile->setPassword($passWord);
                }
            }

            $orgUserProfile->setClient($clientPkId); //clientId
            $orgUserProfile->setUserType($userType); //setting the User type
            $orgUserProfile->setUserName($userName); //Setting the setUserName(String)
            $orgUserProfile->setFirstName($firstName); //setting the firstname
            $orgUserProfile->setMiddleName($middleInitial); //setting the middle name
            $orgUserProfile->setLastName($lastName); //setting the lastname
            $orgUserProfile->setEmailAddress($userEmail); //setting the email address
            if (!isset($userMasterData['resource']) && $userMasterData['resource'] != 'myprofile') {
                $orgUserProfile->setStatus($status); //setting the status
                $orgUserProfile->setUserBelongsTo($userBelongsToFlag); //setting the user associated to role or group
            }
            $orgUserProfile->setModifiedBy($userId); //modified by
            $orgUserProfile->setModifiedDate($this->dateTime); //modified date

            if ($action == 'create') {
                $this->em->persist($orgUserProfile); //Inserting the Above Field Values to org_user_profile table
            }

            $this->em->flush();
            $createdUserId = $orgUserProfile->getUserId();
            if ($action == 'create') {
                $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $createdUserId);
                $orgUserProfile->setclientUserId($createdUserId);
                $this->em->flush();
            }
        }
        return $createdUserId;
    }

    /**
     * insert into user profile extension table
     * @param type $userSubProfileData
     * @param type $createdUserId
     * @param string $action
     * @return boolean
     */
    public function createSubUserProfile($userSubProfileData = array(), $createdUserId = NULL, $action = NULL) {

        if (is_array($userSubProfileData) && $createdUserId > 0) {
            if (empty($userSubProfileData['stateId']) && empty($userSubProfileData['countryId'])) {
                return true;
            }
            //USER SUB PROFILE MASTER DATA
            $address1 = trim($userSubProfileData['address1']);
            $address2 = (isset($userSubProfileData['address2'])) ? trim($userSubProfileData['address2']) : NULL;
            $address3 = (isset($userSubProfileData['address3'])) ? trim($userSubProfileData['address3']) : NULL;
            $address4 = (isset($userSubProfileData['address4'])) ? trim($userSubProfileData['address4']) : NULL;
            $city = (isset($userSubProfileData['city'])) ? trim($userSubProfileData['city']) : NULL;
            $postalcode = trim($userSubProfileData['postalcode']);
            $phone1 = (isset($userSubProfileData['phone1'])) ? trim($userSubProfileData['phone1']) : NULL;
            $phone2 = (isset($userSubProfileData['phone2'])) ? trim($userSubProfileData['phone2']) : NULL;

            //Fetching the foreign key value(stateID) from CmnStateMaster table
            $stateId = $this->em->getRepository('QuizzingPlatform\Entity\CmnStateMaster')->findOneBystateId(array('stateId' => $userSubProfileData['stateId']));

            //Fetching the foreign key value(countryId) from CmnCountryMaster table
            $countryId = $this->em->getRepository('QuizzingPlatform\Entity\CmnCountryMaster')->findOneBycountryId(array('countryId' => $userSubProfileData['countryId']));

            //Fetching the foreign key value(user) from OrgUserProfileExtension table
            $userId = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfileExtension')->findOneByuser(array('user' => $createdUserId));

            /**
             * Insertion for org_user_profile table
             */
            if ($action == 'update') {
                //Update OrgUserProfileExtension table     
                $orgUserProfileExtension = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfileExtension')->findOneByUser(array('user' => $createdUserId));
                //If the User details not present in OrgUserProfileExtension,create new record
                if (empty($orgUserProfileExtension)) {
                    $action = 'create';
                }
            }
            if ($action == 'create') {
                $user = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfile')->findOneByUserId(array('userId' => $createdUserId));

                $orgUserProfileExtension = new orgUserProfileExtension();
                $orgUserProfileExtension->setUser($user);
            }

            $orgUserProfileExtension->setAddress1($address1); //setting the Address1
            $orgUserProfileExtension->setAddress2($address2); //setting the Address2
            $orgUserProfileExtension->setAddress3($address3); //setting the Address3
            $orgUserProfileExtension->setAddress4($address4); //setting the Address4
            $orgUserProfileExtension->setCity($city); //setting the City
            $orgUserProfileExtension->setState($userSubProfileData['stateId']); //setting the state
            $orgUserProfileExtension->setCountry($userSubProfileData['countryId']); //setting the country
            $orgUserProfileExtension->setPostalCode($postalcode); //setting the postalcode
            $orgUserProfileExtension->setPhone1($phone1); //setting the phone1
            $orgUserProfileExtension->setPhone2($phone2); //setting the phone2


            if ($action == 'create') {
                $this->em->persist($orgUserProfileExtension);
            }

            $this->em->flush();
            return true;
        }
        return FALSE;
    }

    /**
     * delete the master user id, when failed to create the sub user profile
     * @param type $masterUserId
     * @return boolean
     */
    public function deleteMasterUserProfile($masterUserId = NULL) {
        if ($createdUserId > 0) {
            // Delete master User info
            $qb = $this->em->createQueryBuilder();
            $query = $qb->delete('QuizzingPlatform\Entity\orgUserProfile', 'oup')
                            ->where('oup.userId= :userId')
                            ->setParameter('userId', $createdUserId)
                            ->getQuery()->execute();
            return true;
        }
        return false;
    }

    /**
     * @desc Creates a group information for a newly created user 
     * @param String    $getGroups - group ids with comma seperated
     * @param Integer   $createdUserId - newly created user-id
     * @param Integer   $userId - The admin user-id who is creating the record.
     * @return Boolean  boolean value of the record created status (true/false).
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     */
    public function createGroupsToUser($getGroups = NULL, $createdUserId = NULL, $userId = NULL) {
        // Check for all mandatory input values
        if (!empty($getGroups) && $createdUserId > 0 && $userId > 0) {

            try {
                $userGroupIdsArr = explode(",", $getGroups);
                foreach ($userGroupIdsArr as $groupId) {
                    //Fetching the foreign key value(groupId) from org_group table
                    $groupIdInstance = $this->em->getRepository('QuizzingPlatform\Entity\OrgGroup')->findOneByGroupId(array('groupId' => $groupId));

                    //Fetching the foreign key value(userTypeId) from org_user_type table
                    $createdUserIdInstance = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfile')->findOneByUserId(array('userId' => $createdUserId));

                    $orgGroupUsers = new OrgGroupUsers();
                    $orgGroupUsers->setGroup($groupIdInstance);
                    $orgGroupUsers->setUser($createdUserIdInstance);
                    $orgGroupUsers->setCreatedBy($userId);
                    $orgGroupUsers->setCreatedDate($this->dateTime);
                    $orgGroupUsers->setModifiedBy($userId);
                    $orgGroupUsers->setModifiedDate($this->dateTime);
                    $this->em->persist($orgGroupUsers); //Inserting the Above Field Values to OrgGroupUsers table
                }
                $this->em->flush();
                return $orgGroupUsers->getId();
            } catch (Exception $e) {
                $msg = 'User group creation Exception  => ' . $e->getMessage();
                $this->app['log']->writeLog($msg);
                return false;
            }
        }
        return false;
    }

    /**
     * @desc Creates a role information for a newly created user
     * @param String    $getRoles - Role ids with comma seperated
     * @param Integer   $createdUserId - newly created user-id
     * @param Integer   $userId - The admin user-id who is creating the record.
     * @return Boolean  boolean value of the record created status (true/false).
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     */
    public function createRolesToUser($getRoles = NULL, $createdUserId = NULL, $userId = NULL) {
        // Check for all mandatory input values
        if (!empty($getRoles) && $createdUserId > 0 && $userId > 0) {
            try {

                $failed = $created = 0;
                $userRoleIdsArr = explode(",", $getRoles);
                foreach ($userRoleIdsArr as $roleId) {
                    //Fetching the foreign key value(userTypeId) from org_user_type table
                    $createdUserIdInstance = $this->em->getRepository('QuizzingPlatform\Entity\orgUserProfile')->findOneByUserId(array('userId' => $createdUserId));

                    $secUserRoleAssociation = new SecUserRoleAssociation();
                    $secUserRoleAssociation->setUserId($createdUserId);
                    $secUserRoleAssociation->setRoleId($roleId);
                    $secUserRoleAssociation->setCreatedBy($userId);
                    $secUserRoleAssociation->setCreatedDate($this->dateTime);
                    $secUserRoleAssociation->setModifiedBy($userId);
                    $secUserRoleAssociation->setModifiedDate($this->dateTime);
                    $this->em->persist($secUserRoleAssociation); //Inserting the Above Field Values to SecUserRoleAssociation table
                }
                $this->em->flush();
                return $secUserRoleAssociation->getId();
            } catch (Exception $e) {
                $msg = 'User role creation Exception  => ' . $e->getMessage();
                $this->app['log']->writeLog($msg);
                return false;
            }
        }
        return false;
    }

    /**
     * Fetching the user information
     * @param Int $userId - For which the user information needs to be feteched
     * @return Array information about the user
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     */
    public function find($userId = NULL) {
        try {
            // Fetch details from org_user_profile table 
            $qb = $this->em->createQueryBuilder(); // 'usr.address3', 'usr.address4', 
            $query = $qb->select('usr.userId as userId', 'cc.clientId', 'usrtype.userTypeName', 'usrtype.userTypeId', 'usr.userName', 'usr.firstName', 'usr.middleName', 'usr.lastName', 'usr.emailAddress AS emailAddress', 'usr.userBelongsTo as userBelongsTo', 'usr.status', 'usrpe.address1', 'usrpe.address2', 'usrpe.address3', 'usrpe.address4', 'usrpe.city', 'usrpe.state as stateId', 'usrpe.country as countryId', 'usrpe.postalCode', 'usrpe.phone1', 'usrpe.phone2', 'ctry.countryName', 'state.stateName'
                    )
                    ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                    ->leftjoin('QuizzingPlatform\Entity\OrgUserProfileExtension', 'usrpe', \Doctrine\ORM\Query\Expr\Join::WITH, 'usrpe.user=usr.userId')
                    ->leftjoin('QuizzingPlatform\Entity\CmnCountryMaster', 'ctry', \Doctrine\ORM\Query\Expr\Join::WITH, 'ctry.countryId=usrpe.country')
                    ->leftjoin('QuizzingPlatform\Entity\CmnStateMaster', 'state', \Doctrine\ORM\Query\Expr\Join::WITH, 'state.stateId=usrpe.state')
                    ->join('QuizzingPlatform\Entity\OrgUserType', 'usrtype', \Doctrine\ORM\Query\Expr\Join::WITH, 'usrtype.userTypeId=usr.userType')
                    ->join('QuizzingPlatform\Entity\CmnClient', 'cc', \Doctrine\ORM\Query\Expr\Join::WITH, 'cc.clientId=usr.client')
                    ->where('usr.userId = :userId')
                    ->setParameter('userId', $userId)
                    ->andWhere('usr.isDeleted = :isDeleted')
                    ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);
            $userDataValues = $qb->getQuery()->getArrayResult();

            // If user info exists then continue
            if (!empty($userDataValues)) {
                $selectedRoleGroup = $userDataValues[0]['userBelongsTo'];
                if ($selectedRoleGroup == $this->app['userStatus']['GROUP']) {
                    $qb = $this->em->createQueryBuilder();
                    $grpQuery = $qb->select('og.groupId', 'og.groupName')
                            ->from('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu')
                            ->join('QuizzingPlatform\Entity\OrgGroup', 'og', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                            ->where('ogu.user = :userId')
                            ->setParameter('userId', $userId);
                    $grpDetails = $qb->getQuery()->getArrayResult();

                    if (!empty($grpDetails)) {

                        // assign Group details to return array.
                        $userDataValues[0]['group'] = $grpDetails;
                    }
                } elseif ($selectedRoleGroup == $this->app['userStatus']['ROLE']) {

                    $qb = $this->em->createQueryBuilder();
                    $rolesQuery = $qb->select('sura.roleId', 'sr.roleName')
                            ->from('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura')
                            ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=sr.roleId')
                            ->where('sura.userId = :userId')
                            ->setParameter('userId', $userId);
                    $rolesDetails = $qb->getQuery()->getArrayResult();
                    if (!empty($rolesDetails)) {

                        // assign Group details to return array.
                        $userDataValues[0]['role'] = $rolesDetails;
                    }
                }

                // Return user details 
                return $userDataValues[0];
            } else {

                //Tag Not Found
                return false;
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'User information GetbyId Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Fetching All user information with search filters
     * @param type $userSearchdata
     * @return type
     */
    function getUsersList($userSearchdata = array()) {
        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $firstName = $lastName = $emailAddress = $totalUserData = $group = $role = $onlyGroup = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "usr.userId";

            // Declare a result array to return. 
            $userdataRequest = array();

            // Check if request is not null.
            if (!empty($userSearchdata)) {

                foreach ($userSearchdata as $tagName => $tagValue) {
                    // get values for $firstName, $lastName, $userEmail, $role, $group
                    $$tagName = $tagValue;
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

            // GET THE USER TOTAL COUNT 
            $totalUserData = self::getAllUsersCount($firstName, $lastName, $emailAddress, $group, $role, $onlyGroup);
            $userdataRequest['total'] = count($totalUserData);
            $userdataRequest['data'] = array();


            ################# Get USER DATA ################
            if ($totalUserData > 0) {
                // Get user data
                $qb = $this->em->createQueryBuilder();
                $dataQuery = $qb->select('usr.userId', 'usr.userName as userName', 'usr.firstName AS firstName', 'usr.middleName', 'usr.lastName AS lastName', 'usr.emailAddress as emailAddress', 'usr.status', 'usr.userBelongsTo as userBelongsTo', 'usrtype.userTypeId as usertypeid', 'usrpe.address1', 'usrpe.address2', 'usrpe.address3', 'usrpe.address4', 'usrpe.city', 'usrpe.state AS stateId', 'usrpe.country AS countryId', 'usrpe.postalCode', 'usrpe.phone1', 'usrpe.phone2', 'ctry.countryName', 'state.stateName', 'usrtype.userTypeName')
                        ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                        ->leftjoin('QuizzingPlatform\Entity\OrgUserProfileExtension', 'usrpe', \Doctrine\ORM\Query\Expr\Join::WITH, 'usr.userId=usrpe.user')
                        ->leftjoin('QuizzingPlatform\Entity\CmnCountryMaster', 'ctry', \Doctrine\ORM\Query\Expr\Join::WITH, 'ctry.countryId=usrpe.country')
                        ->leftjoin('QuizzingPlatform\Entity\CmnStateMaster', 'state', \Doctrine\ORM\Query\Expr\Join::WITH, 'state.stateId=usrpe.state')
                        ->join('QuizzingPlatform\Entity\OrgUserType', 'usrtype', \Doctrine\ORM\Query\Expr\Join::WITH, 'usrtype.userTypeId=usr.userType');

                if (strlen($group) > 0 || strlen($role) > 0) {
                    if (strlen($group) > 0 && strlen($role) == 0) {
                        // SEARCH for GROUP
                        $dataQuery->leftjoin('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogu.user=usr.userId')
                                ->join('QuizzingPlatform\Entity\OrgGroup', 'og', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                                ->Where('usr.userBelongsTo = :userBelongsTo')
                                ->setParameter('userBelongsTo', $this->app['userStatus']['GROUP'])
                                ->andWhere($qb->expr()->like('og.groupName', ':groupName'))
                                ->setParameter('groupName', '%' . $group . '%');
                        $dataQuery->groupby('ogu.user');
                    } elseif (strlen($group) == 0 && strlen($role) > 0) {
                        // SEARCH for ROLE
                        $dataQuery->leftjoin('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.userId=usr.userId')
                                ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=sr.roleId')
                                ->Where('usr.userBelongsTo = :userBelongsTo')
                                ->setParameter('userBelongsTo', $this->app['userStatus']['ROLE'])
                                ->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                                ->setParameter('roleName', '%' . $role . '%');
                        $dataQuery->groupby('sura.userId');
                    }
                }


                // Get only NOT DELETED user Info
                $dataQuery->andWhere('usr.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);

                // If tagName filter is passed, then add in to where condition.
                if ($emailAddress != "") {
                    $dataQuery->andWhere($qb->expr()->like('usr.emailAddress', ':emailAddress'))
                            ->setParameter('emailAddress', '%' . $emailAddress . '%');
                }

                // If description filter is passed, then add in to where condition.
                if ($firstName != "") {
                    $dataQuery->andWhere($qb->expr()->like('usr.firstName', ':firstName'))
                            ->setParameter('firstName', '%' . $firstName . '%');
                }

                // If description filter is passed, then add in to where condition.
                if ($lastName != "") {
                    $dataQuery->andWhere($qb->expr()->like('usr.lastName', ':lastName'))
                            ->setParameter('lastName', '%' . $lastName . '%');
                }

                //If onlyGroup parameter is passed , then get users which is only associated to group
                if ($onlyGroup != "") {
                    $dataQuery->andWhere('usr.userBelongsTo=:userBelongsTo')
                            ->setParameter('userBelongsTo', $this->app['userStatus']['GROUP']);
                }

                $dataQuery->andWhere('usr.userType= :userType')
                        ->setParameter('userType', $this->app['cache']->fetch('ADMIN'));

                // Add limits and sorting to query.
                $dataQuery->setFirstResult($offset)
                        ->setMaxResults($perPage)
                        ->orderBy($sortField, $sortType);

                $userDataValues = $qb->getQuery()->getArrayResult();
                //Fetching the role info or group info associated to the user
                foreach ($userDataValues AS $key => $val) {

                    $userId = $val['userId'];
                    $qb = $this->em->createQueryBuilder();
                    if ($val['userBelongsTo'] == $this->app['userStatus']['GROUP']) {
                        $totalQuery = $qb->select('og.groupName')
                                ->from('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu')
                                ->leftjoin('QuizzingPlatform\Entity\OrgGroup', 'og', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                                ->where('ogu.user = :userId')
                                ->setParameter('userId', $userId);
                        $groupInfo = $qb->getQuery()->getArrayResult();
                        $userDataValues[$key]['group'] = self::arrayToStringConversion($groupInfo, 'groupName');
                    } else if ($val['userBelongsTo'] == $this->app['userStatus']['ROLE']) {
                        $totalQuery = $qb->select('sr.roleName')
                                ->from('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura')
                                ->leftjoin('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=sr.roleId')
                                ->where('sura.userId = :userId') // Retrieve only active userId's
                                ->setParameter('userId', $userId);
                        $rolesInfo = $qb->getQuery()->getArrayResult();
                        $userDataValues[$key]['role'] = self::arrayToStringConversion($rolesInfo, 'roleName');
                    }
                }
                $userdataRequest['data'] = $userDataValues;
            }
            return $userdataRequest;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Get all user data Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
        }
    }

    /**
     * Soft delete the user information
     * @param Int $userId - For which the user information needs to be Deleted
     * @return True[successfully deleted] false[failed to delete]
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Dhaarani S <dharani.s@impelsys.com>
     */
    public function delete($userId) {
        try {

            // Check user record exists
            $userdataExists = self::checkUserAlreadyExists($userId);

            //Check for User already exists
            if (!empty($userdataExists)) {

                // soft delete the User details 
                $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $userId);

                // Change is_deleted field to 0
                $orgUserProfile->setIsDeleted($this->app['cache']->fetch('DELETED'));
                $this->em->flush();
                $this->app['log']->writeLog("Successfully soft deleted the User Information : " . $userId);
                return true;
            } else {
                $this->app['log']->writeLog("Failed to  soft delete the User Information");
                return false;
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'User Deletion Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * update the user information
     * @param type $userValue
     * @param type $updateUser
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Dhaarani S <dharani.s@impelsys.com>
     * @return boolean
     */
    public function update($userValue, $updateUser, $accessToken = '', $requestFrom = '') {
        try {

            $userId = $userValue['userId'];

            //echo $updateUser['userName']; die;
            //Check for duplicate userName and Emailaddress.
            $qb = $this->em->createQueryBuilder();
            $qb->select('usr.userName')
                    ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                    ->where('usr.userName= :userName')
                    ->setParameter('userName', $updateUser['userName'])
                    ->orWhere('usr.emailAddress= :emailAddress')
                    ->setParameter('emailAddress', $updateUser['userEmail'])
                    ->andWhere('usr.userId!= :userId')
                    ->setParameter('userId', $userId)
                    ->andWhere('usr.isDeleted=:isDeleted')
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                    ->andWhere('usr.userType= :userType')
                    ->setParameter('userType', $this->app['cache']->fetch('ADMIN'));
            $userdataExists = $qb->getQuery()->getArrayResult();

            if (empty($userdataExists)) {

                $clientId = $this->app['login.service']->getClientId($accessToken, $requestFrom);
                $clientPkId = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByClientId(array('clientId' => $clientId));
                $updateUser['clientPkId'] = $clientPkId;
                //Update user info into User Profile information
                $lastInsertedId = self::createUserMasterProfile($updateUser, 'update', $userId);

                //Update org_user_subprofile table
                self::createSubUserProfile($updateUser, $userId, 'update');

                //Condition not to touch the users Group/Role table information, while updating the logged in user MY-PROFILE information.
                if (isset($updateUser['resource']) && $updateUser['resource'] == 'myprofile') {
                    return true;
                }

                //If roles and groups are updating , delete the old records and insert new record
                if ($lastInsertedId) {

                    $roles = $updateUser['getRoles'];
                    $groups = $updateUser['getGroups'];
                    $updateUserId = $updateUser['userId'];

                    // check if roles are mapped with user
                    if ($roles != "") {
                        self::deleteRolesByUserId(NULL, $userId);
                        self::deleteGroupsByUserId(NULL, $userId);
                        // Re-create roles . call function createRolesToUser to store userroles
                        $storeRoles = self::createRolesToUser($roles, $lastInsertedId, $updateUserId);
                        if (!$storeRoles) {
                            $this->app['log']->writeLog("Failed to store Roles for User : " . $updateUserId);
                        }
                    }
                    // check if groups are mapped with user
                    if ($groups != "") {

                        self::deleteRolesByUserId(NULL, $userId);
                        self::deleteGroupsByUserId(NULL, $userId);
                        // Re-create groups . call function createGroupsToUser to store usergroups
                        $storeGroups = self::createGroupsToUser($groups, $lastInsertedId, $updateUserId);
                        if (!$storeGroups) {
                            $this->app['log']->writeLog("Failed to store Groups for User : " . $updateUserId);
                        }
                    }
                    return true;
                }
            } else {

                // If different user exists with same username or emailaddress then return exists .
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'User Updation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the total user count 
     * @param String $firstName 
     * @param String $lastName
     * @param String $emailAddress
     * @param String $group
     * @param String $role
     * @return Int user total count
     */
    public function getAllUsersCount($firstName = NULL, $lastName = NULL, $emailAddress = NULL, $group = NULL, $role = NULL, $onlyGroup = NULL) {
        ################# Get TOTAL COUNT ################
        // Get Total of all the user info based on the applied filters.
        $qb = $this->em->createQueryBuilder();
        $totalQuery = $qb->select('usr.userId')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->join('QuizzingPlatform\Entity\OrgUserType', 'usrtype', \Doctrine\ORM\Query\Expr\Join::WITH, 'usrtype.userTypeId=usr.userType');

        // SEARCH BY GROUP / ROLE
        if (strlen($group) > 0 || strlen($role) > 0) {

            if (strlen($group) > 0 && strlen($role) == 0) {
                // SEARCH BY GROUP NAME
                $totalQuery->leftjoin('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogu.user=usr.userId')
                        ->join('QuizzingPlatform\Entity\OrgGroup', 'og', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                        ->Where('usr.userBelongsTo = :userBelongsTo')
                        ->setParameter('userBelongsTo', $this->app['userStatus']['GROUP'])
                        ->andWhere($qb->expr()->like('og.groupName', ':groupName'))
                        ->setParameter('groupName', '%' . $group . '%');
                $totalQuery->groupby('ogu.user');
            } elseif (strlen($group) == 0 && strlen($role) > 0) {
                // SEARCH BY ROLE NAME
                $totalQuery->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.userId=usr.userId')
                        ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=sr.roleId')
                        ->Where('usr.userBelongsTo = :userBelongsTo')
                        ->setParameter('userBelongsTo', $this->app['userStatus']['ROLE'])
                        ->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                        ->setParameter('roleName', '%' . $role . '%');
                $totalQuery->groupby('sura.userId');
            }
        }

        $totalQuery->andWhere('usr.isDeleted = :isDeleted')
                ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);

        $totalQuery->andWhere('usr.userType= :userType')
                ->setParameter('userType', $this->app['cache']->fetch('ADMIN'));

        // If tagName filter is passed, then add in to where condition.
        if ($emailAddress != "") {
            $totalQuery->andWhere($qb->expr()->like('usr.emailAddress', ':emailAddress'))
                    ->setParameter('emailAddress', '%' . $emailAddress . '%');
        }

        // If description filter is passed, then add in to where condition.
        if ($firstName != "") {
            $totalQuery->andWhere($qb->expr()->like('usr.firstName', ':firstName'))
                    ->setParameter('firstName', '%' . $firstName . '%');
        }

        // If description filter is passed, then add in to where condition.
        if ($lastName != "") {
            $totalQuery->andWhere($qb->expr()->like('usr.lastName', ':lastName'))
                    ->setParameter('lastName', '%' . $lastName . '%');
        }

        //If onlyGroup parameter is passed , then get users which is only associated to group
        if ($onlyGroup != "") {
            $totalQuery->andWhere('usr.userBelongsTo=:userBelongsTo')
                    ->setParameter('userBelongsTo', $this->app['userStatus']['GROUP']);
        }

        // Get the results
        $totalUserData = $qb->getQuery()->getArrayResult();
        return $totalUserData;
    }

    /**
     * convert array to string
     * @param Array $arrayValues  Input array which needs to be converted to string
     * @param String $KeyName - key name on which the values in an array to be considered
     * @return string String values of input array
     */
    public function arrayToStringConversion($arrayValues = array(), $KeyName = NULl) {

        $returnValues = NULL;
        if (is_array($arrayValues)) {

            foreach ($arrayValues AS $key => $val) {
                $returnValues .= $val[$KeyName] . ", ";
            }
            $returnValues = rtrim($returnValues, ", ");
        }
        return $returnValues;
    }

    /**
     * Checking the user already exist or not
     * @param Integer $userId
     * @param String $userEmail
     * @param String $userName
     * @return boolean
     */
    public function checkUserAlreadyExists($userId = NULL, $userEmail = NULL, $userName = NULL) {

        if ($userId || $userEmail || $userName) {
            $userdataExists = NULL;
            $qb = $this->em->createQueryBuilder();
            $qb->select('usr.userName', 'usr.userId', 'usr.firstName', 'usr.lastName')
                    ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr');
            if ($userId) {
                $qb->where('usr.userId= :userId')
                        ->setParameter('userId', $userId);
            }

            if ($userEmail && empty($userId)) {
                $qb->Where('usr.emailAddress = :emailAddress')
                        ->setParameter('emailAddress', $userEmail);
            } else if ($userEmail) {
                $qb->andWhere('usr.emailAddress = :emailAddress')
                        ->setParameter('emailAddress', $userEmail);
            }

            if ($userName && $userEmail) {
                $qb->orwhere('usr.userName= :userName')
                        ->setParameter('userName', $userName);
            } else if ($userName) {
                $qb->andwhere('usr.userName= :userName')
                        ->setParameter('userName', $userName);
            }

            $qb->andWhere('usr.isDeleted= :isDeleted')
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

            $userdataExists = $qb->getQuery()->getArrayResult();
            return $userdataExists;
        }
        return false;
    }

    /**
     * Delete the role by user id
     * @param type $userId
     */
    public function deleteRolesByUserId($getUserAssociation = NULL, $userId = NULL) {
        if ($userId) {
            // Delete all roles and user association and recreate.
            $qb = $this->em->createQueryBuilder();
            $query = $qb->delete('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura')
                    ->where('sura.userId= :userId')
                    ->setParameter('userId', $userId);

            //Delete particular roles for the user
            if ($getUserAssociation != NULL) {
                $roleId = explode(',', $getUserAssociation);
                $query->andWhere('sura.roleId IN( :roleId)')
                        ->setParameter('roleId', $roleId);
            }
            $qb->getQuery()->execute();
        }
    }

    /**
     * Delete the Group by userId
     * @param type $userId
     */
    public function deleteGroupsByUserId($getUserAssociation = NULL, $userId = NULL) {
        if ($userId) {
            // Delete all groups and user association and recreate.
            $qb = $this->em->createQueryBuilder();
            $query = $qb->delete('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu')
                    ->where('ogu.user= :userId')
                    ->setParameter('userId', $userId);

            //Delete particular groups for the user
            if ($getUserAssociation != NULL) {
                $groupId = explode(',', $getUserAssociation);
                $query->andWhere('ogu.group IN( :groupId)')
                        ->setParameter('groupId', $groupId);
            }
            $qb->getQuery()->execute();
        }
    }

    /**
     * Associate the user to roles or groups
     * @param type $userValue
     * @param type $associateUser
     * @return boolean
     */
    public function associateUser($userValue, $associateUser) {

        try {

            //Getting the update user Id
            $userId = $userValue['userId'];

            //User belongs to roles or groups
            $userBelongsToFlag = $associateUser['userBelongsTo'];

            //Association or Dissociation Flag
            $association = $associateUser['associated'];

            //Get user Association details of roles / groups
            $getUserAssociation = $associateUser['getAssociation'];

            //Update user id
            $updateUserId = $associateUser['userId'];

            //get the reference of org user profile table
            $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $userId);

            //Update the user belongs to roles or groups
            $orgUserProfile->setUserBelongsTo($userBelongsToFlag);

            $this->em->flush();

            //Associate or dissociate Roles
            if ($userBelongsToFlag == $this->app['userStatus']['ROLE']) {

                //Delete all the Groups associated to user
                self::deleteGroupsByUserId(NULL, $userId);

                //Dissociate roles from user
                if ($association == $this->app['cache']->fetch('DELETED')) {

                    //Delete the specific roles for a particular user
                    self::deleteRolesByUserId($getUserAssociation, $userId);
                }

                //Associate Roles to user
                elseif ($association == $this->app['cache']->fetch('ACTIVE')) {

                    //create roles to user
                    $storeRoles = self::createRolesToUser($getUserAssociation, $userId, $updateUserId);

                    if (!$storeRoles) {
                        $this->app['log']->writeLog("Failed to store Roles for User : " . $updateUserId);
                    }
                }
            }

            //Associate or dissociate  Groups
            if ($userBelongsToFlag == $this->app['userStatus']['GROUP']) {

                //Delete all the roles for a particular user
                self::deleteRolesByUserId(NULL, $userId);

                if ($association == $this->app['cache']->fetch('DELETED')) {

                    //Delete the Groups for tha particular user
                    self::deleteGroupsByUserId($getUserAssociation, $userId);
                }
                //Associate Roles from user
                elseif ($association == $this->app['cache']->fetch('ACTIVE')) {

                    //create the groups for the user
                    $storeGroups = self::createGroupsToUser($getUserAssociation, $userId, $updateUserId);

                    if (!$storeGroups) {
                        $this->app['log']->writeLog("Failed to store Groups for User : " . $updateUserId);
                    }
                }
            }

            return true;
        } catch (Exception $ex) {

            //Add exceptions to logger.
            $msg = 'User Association Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * get User association list
     * @param type $getUserdata
     * @param type $userId
     * @return boolean
     */
    public function getUsersAssociationList($getUserdata = Array(), $userId = NULL) {
        if (!empty($getUserdata)) {
            if (isset($getUserdata['belongsToId']) && isset($getUserdata['userId'])) {
                $name = $getUserdata['name'];
                // fetch roles associated with the users
                if ($getUserdata['belongsToId'] == 1) {
                    // Fetch user selected ROLES (associated=1 means pre selected data)
                    if (isset($getUserdata['associated']) && $getUserdata['associated'] == 1) {
                        try {
                            $qb = $this->em->createQueryBuilder();
                            $query = $qb->select('sr.roleName as name', 'sr.roleId as id')
                                    ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                                    ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.roleId=sura.roleId')
                                    ->where('sura.userId= :userId')
                                    ->setParameter('userId', $userId);
                            if (isset($name) && strlen($name)) {
                                $query->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                                        ->setParameter('roleName', '%' . $name . '%');
                            }

                            $userAssoData = $qb->getQuery()->getArrayResult();

                            $returnData['total'] = count($userAssoData);
                            $returnData['association'] = $userAssoData;
                            return $returnData;
                        } catch (Exception $ex) {
                            //Add exceptions to logger.
                            $msg = 'Get User Association Exception  => ' . $e->getMessage();
                            $this->app['log']->writeLog($msg);
                            return false;
                        }
                    } // Fetch user not selected ROLES
                    if (isset($getUserdata['associated']) && $getUserdata['associated'] == 0) {
                        try {
                            $qb = $this->em->createQueryBuilder();
                            //SELECT sec_role.role_name, sec_role.role_id FROM sec_role JOIN `sec_user_role_association` sura ON (sec_role.role_id = sura.role_id) WHERE sura.user_id = 1 
                            $query = $qb->select('sr.roleName as name', 'sr.roleId as id')
                                    ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                                    ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.roleId != sura.roleId')
                                    ->where('sura.userId= :userId')
                                    ->setParameter('userId', $userId);

                            if (isset($name) && strlen($name)) {
                                $query->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                                        ->setParameter('roleName', '%' . $name . '%');
                            }

                            $userAssoData = $qb->getQuery()->getArrayResult();
                            $returnData['total'] = count($userAssoData);
                            $returnData['association'] = $userAssoData;
                            return $returnData;
                        } catch (Exception $ex) {
                            //Add exceptions to logger.
                            $msg = 'Get User Association Exception  => ' . $e->getMessage();
                            $this->app['log']->writeLog($msg);
                            return false;
                        }
                    }
                }

                if ($getUserdata['belongsToId'] == 2) {
                    if (isset($getUserdata['associated']) && $getUserdata['associated'] == 1) {
                        try {
                            $qb = $this->em->createQueryBuilder();
                            //SELECT sec_role.role_name, sec_role.role_id FROM sec_role JOIN `sec_user_role_association` sura ON (sec_role.role_id = sura.role_id) WHERE sura.user_id = 1 
                            $query = $qb->select('og.groupName AS name', 'og.groupId AS id')
                                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                                    ->join('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.groupId=ogu.group')
                                    ->where('ogu.user= :userId')
                                    ->setParameter('userId', $userId);

                            if (isset($name) && strlen($name)) {
                                $query->andWhere($qb->expr()->like('og.groupName', ':groupName'))
                                        ->setParameter('groupName', '%' . $name . '%');
                            }

                            $userAssoData = $qb->getQuery()->getArrayResult();
                            $returnData['total'] = count($userAssoData);
                            $returnData['association'] = $userAssoData;
                            return $returnData;
                        } catch (Exception $ex) {
                            //Add exceptions to logger.
                            $msg = 'Get User Association Exception  => ' . $e->getMessage();
                            $this->app['log']->writeLog($msg);
                            return false;
                        }
                    } else if ($getUserdata['associated'] == 0) {
                        try {
                            $qb = $this->em->createQueryBuilder();
                            //SELECT sec_role.role_name, sec_role.role_id FROM sec_role JOIN `sec_user_role_association` sura ON (sec_role.role_id = sura.role_id) WHERE sura.user_id = 1 
                            $query = $qb->select('og.groupName AS name', 'og.groupId AS id')
                                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                                    ->join('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.groupId != ogu.group')
                                    ->where('ogu.user= :userId')
                                    ->setParameter('userId', $userId);

                            if (isset($name) && strlen($name)) {
                                $query->andWhere($qb->expr()->like('og.groupName', ':groupName'))
                                        ->setParameter('groupName', '%' . $name . '%');
                            }

                            $userAssoData = $qb->getQuery()->getArrayResult();
                            $returnData['total'] = count($userAssoData);
                            $returnData['association'] = $userAssoData;
                            return $returnData;
                        } catch (Exception $ex) {
                            //Add exceptions to logger.
                            $msg = 'Get User Association Exception  => ' . $e->getMessage();
                            $this->app['log']->writeLog($msg);
                            return false;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * @Desc encode the password to md5 format.
     * @param type $password
     * @return type encoded password
     */
    public function encodePassword($password) {

        return md5(trim($password));
    }

    /**
     * @Desc Fetch user details based on username.
     * @param type $userName
     * @return type array
     */
    public function getUserFewInfo($userName = NULL, $userId = NULL) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('usr.userId', 'usr.userName', 'usr.password', 'oua.accessToken ')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->leftJoin('QuizzingPlatform\Entity\OrgUserAuth', 'oua', \Doctrine\ORM\Query\Expr\Join::WITH, 'oua.user=usr.userId')
                ->where('usr.isDeleted= :isDeleted')
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        if ($userName) {
            $qb->andWhere('usr.userName= :userName')
                    ->setParameter('userName', $userName);
        } else if ($userId) {
            $qb->andWhere('usr.userId= :userId')
                    ->setParameter('userId', $userId);
        }

        $userDetails = $qb->getQuery()->getArrayResult();
        return $userDetails[0];
    }

    /**
     * @Desc end user client id validation
     * @param type clientCode
     * @return type
     */
    public function getUserByClientCode($clientCode) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('oc.clientId', 'oc.clientCode', 'oc.secretKey', 'oc.clientName')
                ->from('QuizzingPlatform\Entity\CmnClient', 'oc')
                ->where('oc.clientCode= :clientCode')
                ->andWhere('oc.status= :status')
                ->setParameter('clientCode', $clientCode)
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

        $userDetails = $qb->getQuery()->getArrayResult();

        return $userDetails[0];
    }

    /**
     * @Desc Store Access token detials for the specified username
     * @param type $userName
     * @param type $accessToken
     */
    public function storeAccessToken($userId, $accessToken = NULL) {

        $orgUserAuthExists = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserAuth')->findOneByUser(array('user' => $userId));

        $orgUserProfile = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfile')->findOneByUserId(array('userId' => $userId));

        //If the User details not present in OrgUserauth,create new record
        if (empty($orgUserAuthExists)) {

            //Add entry to user auth table
            $orgUserAuth = new orgUserAuth();
            $orgUserAuth->setUser($orgUserProfile);
            $orgUserAuth->setCreatedBy($userId);
            $orgUserAuth->setCreatedDate($this->dateTime);
        } else {
            $orgUserAuth = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserAuth')->findOneByUser(array('user' => $userId));
        }

        $orgUserAuth->setAccessToken($accessToken);
        $orgUserAuth->setModifiedBy($userId);
        $orgUserAuth->setModifiedDate($this->dateTime);

        if (empty($orgUserAuthExists)) {
            $this->em->persist($orgUserAuth);
        }
        $this->em->flush();

        return true;
    }

    /**
     * @Desc Store Access token detials for the specified username
     * @param type $userName
     * @param type $accessToken
     */
    public function storeEndUserAccessToken($clientCode, $accessToken) {

        // Store Access token detials for the specified clientcode
        $oauthClients = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByClientCode(array('clientCode' => $clientCode));
        $oauthClients->setAccessToken($accessToken); //setting the access token for end users to null
        $this->em->flush();
        return true;
    }

    /**
     * @Desc  Store Access token to null
     * @param type $userId
     * @return boolean
     */
    public function setNullAccessToken($exisitngAccessToken) {

        // Store Access token to null
        $orgUserAuth = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserAuth')->findOneByAccessToken(array('accessToken' => $exisitngAccessToken));
        $orgUserAuth->setAccessToken(""); //setting the access token for admin users.
        $this->em->flush();
        return true;
    }

    /**
     * @Desc check its a valid token and user is in active.
     * @param type $accessToken
     * @return boolean
     */
    public function checkToken($accessToken = NULL, $requestFrom = NULL) {
        // check requested token is valid or not

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('oua.accessToken')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->join('QuizzingPlatform\Entity\OrgUserAuth', 'oua', \Doctrine\ORM\Query\Expr\Join::WITH, 'oua.user=usr.userId')
                ->where('usr.isDeleted= :isDeleted')
                ->andWhere('oua.accessToken= :accessToken')
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('accessToken', $accessToken);

        $tokenExists = $qb->getQuery()->getArrayResult();
        return $tokenExists[0]['accessToken'];
    }

    /**
     * @Desc Get user id by access token
     * @param type $accessToken
     * @return type
     */
    public function getUserIdByToken($accessToken) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('usr.userId')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->join('QuizzingPlatform\Entity\OrgUserAuth', 'oua', \Doctrine\ORM\Query\Expr\Join::WITH, 'oua.user=usr.userId')
                ->where('oua.accessToken= :accessToken')
                ->andWhere('usr.isDeleted= :isDeleted')
                ->setParameter('accessToken', $accessToken)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $tokenExists = $qb->getQuery()->getArrayResult();

        return $tokenExists[0]['userId'];
    }

    /**
     * @Desc get users distinct permission
     * @param : No parameters
     * @Desc : This method fetches the Item/Question Types which is manually fed to database. 
     * @Return : Returns all the questin types.
     */
    public function getUserPermission($userId) {

        try {

            $permissionArray = array();
            $permissionArray = $this::checkResourceActionPermission($userId, NULL, NULL, $callFromAction = "getUserPermission");
            return $permissionArray;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Item types listing Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * @desc Store reset password and recovery expiry time for the the user.
     * @param type $userId
     * @param type $resetToken
     * @param type $expirationTime
     * @return boolean
     */
    public function storePasswordResetToken($userId, $resetToken) {

        $orgUserProfileExtension = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfileExtension')->findOneByUser(array('user' => $userId));
        $orgUserProfileExtension->setResetPasswordToken($resetToken); //setting password token
        $orgUserProfileExtension->setResetPasswordExpiry($this->app['config']['resetPasswordExpiration']); //setting the expiration time
        $orgUserProfileExtension->setResetPasswordIdentifier(0); //setting the reset identifier to 0 
        $this->em->flush();
        return true;
    }

    /**
     * @Desc validate the reset password along with check recovery expiry time.
     * @param type $resetToken
     * @return boolean
     */
    public function validateResetPassword($resetToken) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('usr.userId')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->join('QuizzingPlatform\Entity\OrgUserProfileExtension', 'oupe', \Doctrine\ORM\Query\Expr\Join::WITH, 'oupe.user=usr.userId')
                ->where('oupe.resetPasswordToken= :resetPasswordToken')
                ->andWhere('oupe.resetPasswordExpiry >= :presenttime')
                ->andWhere('usr.isDeleted= :isDeleted')
                ->andWhere('oupe.resetPasswordIdentifier= :resetPasswordIdentifier')
                ->setParameter('resetPasswordToken', $resetToken)
                ->setParameter('presenttime', $this->dateTime)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('resetPasswordIdentifier', 0);

        $tokenExists = $qb->getQuery()->getArrayResult();
        if (!empty($tokenExists)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Desc  Reset the password with new password
     * @param type $resetToken
     * @param type $password
     * @return boolean
     */
    public function resetPassword($resetToken, $password) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('usr.userId')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->join('QuizzingPlatform\Entity\OrgUserProfileExtension', 'oupe', \Doctrine\ORM\Query\Expr\Join::WITH, 'oupe.user=usr.userId')
                ->where('oupe.resetPasswordToken= :resetPasswordToken')
                ->andWhere('usr.isDeleted= :isDeleted')
                ->setParameter('resetPasswordToken', $resetToken)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $userData = $qb->getQuery()->getArrayResult();

        $userId = $userData[0]['userId'];

        // Encode the password with md5 and then store it.
        $passWord = self::encodePassword($password);

        // Save the new password
        $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $userId);
        $orgUserProfile->setPassword($passWord);
        $this->em->flush();

        $orgUserProfileExtension = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserProfileExtension')->findOneByUser(array('user' => $userId));
        $orgUserProfileExtension->setResetPasswordIdentifier(1); //once password reset, set this flag to true to avoid subsequesnt tries.
        $this->em->flush();

        return true;
    }

    /**
     * @Desc Return WK admin portal client Id
     * @return type
     */
    public function getWkAdminPortalClientId() {

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('oc.clientId')
                ->from('QuizzingPlatform\Entity\CmnClient', 'oc')
                ->where('oc.clientId= :clientId')
                ->andWhere('oc.status= :status')
                ->setParameter('clientId', $this->app['cache']->fetch('WK Admin'))
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

        $clientDetails = $qb->getQuery()->getArrayResult();
        return $clientDetails[0]['clientId'];
    }

    /**
     * @Desc Get the QP userid for clientUSerId
     * @param type $clientUserId
     * @return type
     */
    public function getUserIdofClientUserId($clientUserId, $clientPkId) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('usr.userId')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->where('usr.clientUserId= :clientUserId')
                ->andWhere('usr.client= :clientPk')
                ->andWhere('usr.isDeleted= :isDeleted')
                ->setParameter('clientUserId', $clientUserId)
                ->setParameter('clientPk', $clientPkId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $userData = $qb->getQuery()->getArrayResult();

        $userId = $userData[0]['userId'];

        return $userId;
    }

    /**
     * @Desc Create/Update end user information
     * @param type $clientId
     * @param array $userParams
     * @return boolean
     */
    public function createEndUser($clientId, $userParams = NULL) {

        $qb = $this->em->createQueryBuilder();
        $qb->select('usr.userId')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->where('usr.clientUserId= :clientUserId')
                ->andWhere('usr.isDeleted= :isDeleted')
                ->andWhere('usr.client= :clientPk')
                ->setParameter('clientPk', $clientId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'))
                ->setParameter('clientUserId', $userParams['clientUserId']);

        $userDetails = $qb->getQuery()->getArrayResult();
        $userId = $userDetails[0]['userId'];

        // Capture few users information
        $userEmail = ($userParams['email']) ? trim($userParams['email']) : NULL;
        $firstName = ($userParams['firstName']) ? trim($userParams['firstName']) : NULL;
        $lastName = ($userParams['lastName']) ? trim($userParams['lastName']) : NULL;

        //Configure few additional details
        $userType = $this->app['cache']->fetch('EUP'); //end user type 
        $isDeleted = $this->app['cache']->fetch('ACTIVE');
        $status = $this->app['cache']->fetch('ACTIVE');
        $clientUserId = trim($userParams['clientUserId']);


        //Fetching the foreign key values for usertype and clinetpkid
        $userTypeObj = $this->em->getRepository('QuizzingPlatform\Entity\OrgUserType')->findOneByUserTypeId(array('userTypeId' => $userType));
        $clientPkId = $this->em->getRepository('QuizzingPlatform\Entity\CmnClient')->findOneByClientId(array('clientId' => $clientId));

        if (empty($userDetails)) {
            /**
             * Insertion for org_user_profile table
             */
            //Creating the object for org_user_profile
            $orgUserProfile = new orgUserProfile();
            $orgUserProfile->setStatus($status);
            $orgUserProfile->setIsDeleted($isDeleted);
            $orgUserProfile->setCreatedBy($userId);
            $orgUserProfile->setCreatedDate($this->dateTime);
            $orgUserProfile->setEffectiveDate($this->dateTime);
        } else {

            // Update org_user_profile table
            $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $userId);
        }
        $orgUserProfile->setUserType($userTypeObj); //setting the User type
        $orgUserProfile->setClient($clientPkId); //clientId
        $orgUserProfile->setclientUserId($clientUserId); //clientuserid
        $orgUserProfile->setFirstName($firstName); //setting the firstname
        $orgUserProfile->setLastName($lastName); //setting the lastname
        $orgUserProfile->setEmailAddress($userEmail); //setting the email address
        $orgUserProfile->setModifiedBy($userId); //modified by
        $orgUserProfile->setModifiedDate($this->dateTime); //modified date
        //To create freshly do persiste
        if (empty($userDetails)) {
            $this->em->persist($orgUserProfile); //Inserting the Above Field Values to org_user_profile table
        }

        //Finally update/create the user entry
        $this->em->flush();

        //Get userId
        $userId = $orgUserProfile->getUserId();

        // Return userId created
        return $userId;
    }

    /**
     * @Desc Get few user information for access token generation
     * @param type $userId
     * @return type
     */
    public function getUserInfoForToken($userId) {

        // Fetch details from org_user_profile table 
        $qb = $this->em->createQueryBuilder(); // 'usr.address3', 'usr.address4', 
        $query = $qb->select('usr.userId', 'usr.clientUserId', 'cc.clientId', 'usrtype.userTypeName', 'usr.firstName', 'usr.middleName', 'usr.lastName')
                ->from('QuizzingPlatform\Entity\OrgUserProfile', 'usr')
                ->join('QuizzingPlatform\Entity\OrgUserType', 'usrtype', \Doctrine\ORM\Query\Expr\Join::WITH, 'usrtype.userTypeId=usr.userType')
                ->join('QuizzingPlatform\Entity\CmnClient', 'cc', \Doctrine\ORM\Query\Expr\Join::WITH, 'cc.clientId=usr.client')
                ->where('usr.userId = :userId')
                ->setParameter('userId', $userId)
                ->andWhere('usr.isDeleted = :isDeleted')
                ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);
        $userDataValues = $qb->getQuery()->getArrayResult();

        return $userDataValues[0];
    }

}
