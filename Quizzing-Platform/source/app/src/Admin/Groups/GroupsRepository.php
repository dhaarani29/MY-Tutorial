<?php

/**
 * GroupsRepository - It's the repository class file to handle the groups module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Groups;

//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\OrgGroup;
use QuizzingPlatform\Entity\SecRole;
use QuizzingPlatform\Entity\OrgGroupRoleAssociation;
use QuizzingPlatform\Entity\OrgGroupUsers;

class GroupsRepository implements RepositoryInterface {

    protected $em;
    protected $app;

    // Defines doctrine entity manager in constructor.
    public function __construct(Application $app) {
        $this->em = $app['orm.em'];
        $this->app = $app;
        $this->dateTime = $app['config']['dbDate'];
        $this->hierarchyids = array();
    }

    /**
     * Fetching all the groups based on the search filters(group name and description)
     * @param type $groupRequest
     * @return boolean
     */
    public function getGroups($groupRequest, $associatedGroups = NULL, $associatedUserId = NULL) {

        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $groupName = $description = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "groupName";

            // Declare a result array to return. 
            $groupValues = array();

            // Check if request is not null.
            if (!empty($groupRequest)) {

                foreach ($groupRequest as $key => $mrequest) {

                    // get values for $groupName,$description,$perPage  
                    $$key = $mrequest;
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

            // Get Total of all the groups based on the applied filters. 
            $totalGroups = self::getgroupCount($groupName, $description, $associatedGroups, $associatedUserId);

            $groupValues['total'] = $totalGroups; // Total metadata count for pagination.
            $groupValues['data'] = array();

            // Check if count is greater than 0
            if ($totalGroups > 0) {
                //Fetching the group Details
                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('og.groupId as id', 'og.groupName as groupName', 'og.description as description')
                        ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                        ->where('og.isDeleted = :isDeleted') // Retrieve only active group's
                        ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

                // If groupname filter is passed, then add in to where condition.
                if ($groupName != "") {
                    $query->andWhere($qb->expr()->like('og.groupName', ':groupName'))
                            ->setParameter('groupName', '%' . $groupName . '%');
                }

                // If description filter is passed, then add in to where condition.
                if ($description != "") {
                    $query->andWhere($qb->expr()->like('og.description', ':description'))
                            ->setParameter('description', '%' . $description . '%');
                }


                //Associated & non associated user groups
                if ($associatedGroups != NULL) {
                    //Fetching the group associated with the user
                    $qbs = $this->em->createQueryBuilder();
                    $qbs->select('og.groupId as id')
                            ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                            ->join('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogu.group=og.groupId')
                            ->where('og.isDeleted = :status') // Retrieve only active role's
                            ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                            ->andWhere('ogu.user=:auserId')
                            ->setParameter('auserId', $associatedUserId);

                    $subQuery = $qbs->getQuery()->getArrayResult();

                    //If the user associated to group return the group details
                    if (!empty($subQuery)) {

                        $subQueryResult = array();

                        //Push the group ids
                        foreach ($subQuery as $key => $value) {
                            array_push($subQueryResult, $value['id']);
                        }

                        $associatedUserGroups = implode(',', $subQueryResult);
                        //fetching only associated group for the particular user
                        if ($associatedGroups == $this->app['cache']->fetch('ACTIVE')) {

                            $query->andWhere($qb->expr()->In('og.groupId', $associatedUserGroups));
                        }
                        //Fetching only dissociated group for the particular user
                        elseif ($associatedGroups == $this->app['cache']->fetch('DELETED')) {

                            $query->andWhere($qb->expr()->notIn('og.groupId', $associatedUserGroups));
                        }
                    }
                    //If the user not associated to group display all the groups
                    else {
                        //For associated group is 0
                        if ($associatedGroups == $this->app['cache']->fetch('ACTIVE')) {
                            return 0;
                        }
                        //For dissociated group return all the groups
                        elseif ($associatedGroups == $this->app['cache']->fetch('DELETED')) {
                            $query->andWhere('1=1');
                        }
                    }
                }if (empty($noLimit)) {
                    // Add limits and sorting to query.
                    $query->setFirstResult($offset)
                            ->setMaxResults($perPage)
                            ->orderBy($sortField, $sortType);
                } else {
                    $query->orderBy($sortField, 'ASC');
                }


                // Get the results
                $groupReturnValues = $qb->getQuery()->getArrayResult(); //->getSQL(); //

                $groupValues['data'] = $groupReturnValues;
            }

            //Return the result array.
            return $groupValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'group get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * get the group count based on the filters
     * @param type $groupName
     * @param type $description
     * @return type
     */
    public function getgroupCount($groupName = NULL, $description = NULL, $associatedGroups = NULL, $associatedUserId = NULL) {

        //Fetching the count of group
        $qb = $this->em->createQueryBuilder();
        $totalQuery = $qb->select('COUNT(og.groupId) as total')
                ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                ->where('og.isDeleted = :isDeleted') // Retrieve only active group's
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        // If groupname filter is passed, then add in to where condition.
        if ($groupName != "") {
            $totalQuery->andWhere($qb->expr()->like('og.groupName', ':groupName'))
                    ->setParameter('groupName', '%' . $groupName . '%');
        }

        // If description filter is passed, then add in to where condition.
        if ($description != "") {
            $totalQuery->andWhere($qb->expr()->like('og.description', ':description'))
                    ->setParameter('description', '%' . $description . '%');
        }
        //Associated & non associated user groups
        if ($associatedGroups != NULL) {
            //Fetching the group associated with the user
            $qbs = $this->em->createQueryBuilder();
            $qbs->select('og.groupId as id')
                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                    ->join('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogu.group=og.groupId')
                    ->where('og.isDeleted = :status') // Retrieve only active role's
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                    ->andWhere('ogu.user=:auserId')
                    ->setParameter('auserId', $associatedUserId);

            $subQuery = $qbs->getQuery()->getArrayResult();
            //If the user associated to group return the assoicated/dissociated details
            if (!empty($subQuery)) {

                $subQueryResult = array();
                //Push the group ids
                foreach ($subQuery as $key => $value) {
                    array_push($subQueryResult, $value['id']);
                }

                $associatedUserGroups = implode(',', $subQueryResult);
                //For associated groups
                if ($associatedGroups == $this->app['cache']->fetch('ACTIVE')) {

                    $totalQuery->andWhere($qb->expr()->In('og.groupId', $associatedUserGroups));
                }
                //For dissociated groups
                elseif ($associatedGroups == $this->app['cache']->fetch('DELETED')) {

                    $totalQuery->andWhere($qb->expr()->notIn('og.groupId', $associatedUserGroups));
                }
            }
            //If the user not associated to group return all groups
            else {
                if ($associatedGroups == $this->app['cache']->fetch('ACTIVE')) {
                    return 0;
                } elseif ($associatedGroups == $this->app['cache']->fetch('DELETED')) {
                    $totalQuery->andWhere('1=1');
                }
            }
        }
        // Get the results
        $totalGroups = $qb->getQuery()->getSingleScalarResult();

        return $totalGroups;
    }

    /**
     * Get the count of roles and users associated to group
     * @param type $groupId
     * @param type $roleName
     * @param type $firstName
     * @param type $lastName
     * @return boolean
     */
    public function getGroupByIdCount($groupId = NULL, $roleName = NULL, $firstName = NULL, $lastName = NULL) {

        try {
            $groupArrayDetails = array();
            if ($groupId > 0) {

                //Get the group name and description
                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('og.groupName', 'og.groupId as id', 'og.description')
                        ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                        ->where('og.groupId = :groupId')
                        ->setParameter('groupId', $groupId)
                        ->andWhere('og.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);
                $groupDataValues = $qb->getQuery()->getArrayResult();

                // group id record exists check.
                if (count($groupDataValues) > 0) {
                    $groupArrayDetails = $groupDataValues[0];

                    /*
                     * Role details
                     */
                    $dbObj = $this->em->createQueryBuilder();
                    $query = $dbObj->select('sr.roleName as roleName', 'sr.roleId')
                            ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                            ->leftjoin('QuizzingPlatform\Entity\OrgGroupRoleAssociation', 'ogra', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogra.group')
                            ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogra.role=sr.roleId')
                            ->where('og.groupId = :groupId')
                            ->setParameter('groupId', $groupId)
                            ->andWhere('og.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE'])
                            ->andWhere('sr.status = :status')
                            ->setParameter('status', $this->app['isDeleted']['ACTIVE']);

                    if ($roleName != '') {
                        $query->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                                ->setParameter('roleName', '%' . $roleName . '%');
                    }

                    $roleDetails = $dbObj->getQuery()->getArrayResult();
                    $groupArrayDetails['roleDetails']['total'] = count($roleDetails);


                    /*
                     * User Details
                     */
                    $dbObj = $this->em->createQueryBuilder();
                    $query = $dbObj->select('oup.userName', 'oup.userId', 'oup.emailAddress as emailAddress', 'oup.firstName as firstName', 'oup.lastName as lastName')
                            ->from('QuizzingPlatform\Entity\OrgUserProfile', 'oup')
                            ->leftjoin('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogu.user=oup.userId')
                            ->join('QuizzingPlatform\Entity\OrgGroup', 'og', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                            ->where('og.groupId = :groupId')
                            ->setParameter('groupId', $groupId)
                            ->andWhere('og.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE'])
                            ->andWhere('oup.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);
                    ;
                    if ($firstName != '') {
                        $query->andWhere($qb->expr()->like('oup.firstName', ':firstName'))
                                ->setParameter('firstName', '%' . $firstName . '%');
                    }if ($lastName != '') {
                        $query->andWhere($qb->expr()->like('oup.lastName', ':lastName'))
                                ->setParameter('lastName', '%' . $lastName . '%');
                    }

                    $userDetails = $dbObj->getQuery()->getArrayResult();
                    $groupArrayDetails['userDetails']['total'] = count($userDetails);
                }
            }

            return $groupArrayDetails;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'group get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Get the group association details
     * @param type $groupId
     * @param type $groupData
     * @return boolean
     */
    public function getGroupByid($groupId = NULL, $groupData = array()) {

        try {
            $groupArrayDetails = array();
            if ($groupId > 0) {

                // Assign filters to null by default
                $roleName = $firstName = $lastName = "";

                // Define default offset value
                $offset = $this->app['cache']->fetch('offset');

                // Page number
                $page = $this->app['cache']->fetch('page');

                // Per page count
                $perPage = $this->app['cache']->fetch('limit');

                // Default sorting type
                $sortType = $this->app['cache']->fetch('sortType');

                if (!empty($groupData)) {

                    foreach ($groupData as $key => $mrequest) {

                        // get values for $groupName,$description,$perPage  
                        $$key = $mrequest;
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

                //Get the count of roles and groups associated to group
                $groupDetailsCount = self::getGroupByIdCount($groupId, $roleName, $firstName, $lastName);

                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('og.groupName', 'og.groupId as id', 'og.description')
                        ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                        ->where('og.groupId = :groupId')
                        ->setParameter('groupId', $groupId)
                        ->andWhere('og.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);
                $groupDataValues = $qb->getQuery()->getArrayResult();


                // group id record exists check.
                if (count($groupDataValues) > 0) {
                    $groupArrayDetails = $groupDataValues[0];

                    /*
                     * Role Details
                     */
                    $dbObj = $this->em->createQueryBuilder();
                    $query = $dbObj->select('sr.roleName as roleName', 'sr.roleId')
                            ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                            ->leftjoin('QuizzingPlatform\Entity\OrgGroupRoleAssociation', 'ogra', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogra.group')
                            ->join('QuizzingPlatform\Entity\SecRole', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogra.role=sr.roleId')
                            ->where('og.groupId = :groupId')
                            ->setParameter('groupId', $groupId)
                            ->andWhere('og.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE'])
                            ->andWhere('sr.status = :status')
                            ->setParameter('status', $this->app['isDeleted']['ACTIVE']);

                    if ($roleName != '') {
                        $query->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                                ->setParameter('roleName', '%' . $roleName . '%');
                    }if ($roleSort) {
                        $sortField = isset($sortField) ? $sortField : 'roleName';
                        $query->orderBy($sortField, $sortType);
                    }
                    $query->setFirstResult($offset)
                            ->setMaxResults($perPage);
                    $roleDetails = $dbObj->getQuery()->getArrayResult();
                    $groupArrayDetails['roleDetails']['total'] = $groupDetailsCount['roleDetails']['total'];
                    $groupArrayDetails['roleDetails']['data'] = $roleDetails;

                    /*
                     * User Details
                     */
                    $dbObj = $this->em->createQueryBuilder();
                    $query = $dbObj->select('oup.userName', 'oup.userId', 'oup.emailAddress as emailAddress', 'oup.firstName as firstName', 'oup.lastName as lastName')
                            ->from('QuizzingPlatform\Entity\OrgUserProfile', 'oup')
                            ->leftjoin('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'ogu.user=oup.userId')
                            ->join('QuizzingPlatform\Entity\OrgGroup', 'og', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                            ->where('og.groupId = :groupId')
                            ->setParameter('groupId', $groupId)
                            ->andWhere('og.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE'])
                            ->andWhere('oup.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);
                    ;
                    if ($firstName != '') {
                        $query->andWhere($qb->expr()->like('oup.firstName', ':firstName'))
                                ->setParameter('firstName', '%' . $firstName . '%');
                    }if ($lastName != '') {
                        $query->andWhere($qb->expr()->like('oup.lastName', ':lastName'))
                                ->setParameter('lastName', '%' . $lastName . '%');
                    }
                    if ($userSort) {
                        $sortField = isset($sortField) ? $sortField : 'emailAddress';
                        $query->orderBy($sortField, $sortType);
                    }
                    $query->setFirstResult($offset)
                            ->setMaxResults($perPage);

                    $userDetails = $dbObj->getQuery()->getArrayResult();
                    $groupArrayDetails['userDetails']['total'] = $groupDetailsCount['userDetails']['total'];
                    $groupArrayDetails['userDetails']['data'] = $userDetails;
                }
            }
            if ($userSort) {
                unset($groupArrayDetails['roleDetails']);
            } else if ($roleSort) {
                unset($groupArrayDetails['userDetails']);
            }

            return $groupArrayDetails;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'group get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * Create the group
     * @param type $groupData
     * @return boolean
     */
    public function create($groupData = null) {

        try {
            //Input Request For group Creation
            //check Duplicate test name
            $qb = $this->em->createQueryBuilder();
            $qb->select('og.groupId')
                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                    ->where('og.groupName =:groupName')
                    ->andWhere('og.isDeleted =:isDeleted')
                    ->setParameter('groupName', $groupData['groupName'])
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
            $groupExist = $qb->getQuery()->getArrayResult();
            $userId = $groupData['userId'];

            //Get Roles and Users for associated to Groups
            $associationType = $groupData['associationType']; // 1- Role and 2-Users
            $userRoleAssociation = $groupData['getAssociation']; //Role or user Id
            //If its not duplicate
            if (empty($groupExist)) {

                /*
                 * Insert into org_group table
                 */
                $qtiGroup = new OrgGroup ();
                $qtiGroup->setGroupName($groupData['groupName']);
                $qtiGroup->setDescription($groupData['description']);
                $qtiGroup->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                $qtiGroup->setCreatedBy($userId);
                $qtiGroup->setCreatedDate($this->dateTime);
                $qtiGroup->setModifiedBy($userId);
                $qtiGroup->setModifiedDate($this->dateTime);
                $this->em->persist($qtiGroup); //Inserting the Above Field Values to group table
                $this->em->flush();
                $groupId = $qtiGroup->getGroupId();

                /*
                 * Group to Role Association
                 */

                if ($associationType == $this->app['userStatus']['ROLE']) {
                    $rolesList = explode(',', $userRoleAssociation);
                    foreach ($rolesList as $key => $role) {
                        $roles = $this->em->getReference('QuizzingPlatform\Entity\SecRole', $role);
                        $orgGroupRoleAssociation = new OrgGroupRoleAssociation ();
                        $orgGroupRoleAssociation->setGroup($qtiGroup);
                        $orgGroupRoleAssociation->setRole($roles);
                        $this->em->persist($orgGroupRoleAssociation);
                    }
                    $this->em->flush();
                }

                /*
                 * Group to User Association
                 */ else {
                    $usersList = explode(',', $userRoleAssociation);
                    foreach ($usersList as $key => $user) {

                        $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $user);

                        // Change user_belongs to Groups
                        $orgUserProfile->setUserBelongsTo($this->app['userStatus']['GROUP']);

                        $users = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $user);
                        $orgGroupUsers = new OrgGroupUsers();
                        $orgGroupUsers->setGroup($qtiGroup);
                        $orgGroupUsers->setUser($users);
                        $orgGroupUsers->setCreatedBy($userId);
                        $orgGroupUsers->setCreatedDate($this->dateTime);
                        $orgGroupUsers->setModifiedBy($userId);
                        $orgGroupUsers->setModifiedDate($this->dateTime);
                        $this->em->persist($orgGroupUsers);
                    }
                    $this->em->flush();
                }return $groupId;
            } else {
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Group creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    /**
     * Get the group id
     * @param type $groupId
     * @return type
     */
    public function find($groupId) {

        //get item bank details
        $qb = $this->em->createQueryBuilder();

        $qb->select('og.groupId', 'og.groupName', 'og.description')
                ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                ->where('og.groupId =:groupId')
                ->andWhere('og.isDeleted =:isDeleted')
                ->setParameter('groupId', $groupId)
                ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));

        $groupData = $qb->getQuery()->getArrayResult();

        $groupDataValues = $groupData[0];
        return $groupDataValues;
    }

    public function update($groupData, $groupId) {
        try {
            //Input Request For group Creation
            //check Duplicate test name
            $qb = $this->em->createQueryBuilder();
            $qb->select('og.groupId')
                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                    ->where('og.groupName =:groupName')
                    ->andWhere('og.isDeleted =:isDeleted')
                    ->setParameter('groupName', $groupData['groupName'])
                    ->andWhere('og.groupId !=:groupId')
                    ->setParameter('groupId', $groupId)
                    ->setParameter('isDeleted', $this->app['cache']->fetch('ACTIVE'));
            $groupExist = $qb->getQuery()->getArrayResult();
            $userId = $groupData['userId'];

            //Get Roles and Users for associated to Groups
            $associationType = $groupData['associationType'];
            $userRoleAssociation = $groupData['getAssociation'];


            //If its not duplicate
            if (empty($groupExist)) {

                //$qtiGroupId = $this->em->getRepository('QuizzingPlatform\Entity\OrgGroup')->findOneByGroupId(array('groupId' => $groupId));
                $qtiGroup = $this->em->getReference('QuizzingPlatform\Entity\OrgGroup', $groupId);
                $qtiGroup->setGroupName($groupData['groupName']);
                $qtiGroup->setDescription($groupData['description']);
                $qtiGroup->setIsDeleted($this->app['cache']->fetch('ACTIVE'));
                $qtiGroup->setModifiedBy($userId);
                $qtiGroup->setModifiedDate($this->dateTime);
                //$this->em->persist($qtiGroup); //updating the Above Field Values to group table
                $this->em->flush();
                $groupId = $qtiGroup->getGroupId();


                /*
                 * Group to Role Association
                 */
                if ($associationType == $this->app['userStatus']['ROLE']) {

                    /*
                     * Delete the existing entry of this group and recreate new entry
                     */
                    //Delete Role Association to Group
                    self::deleteRoleAssocByGroupId($groupId);
                    //Delete User Association to Group
                    self::deleteUserAssocByGroupId($groupId);

                    $rolesList = explode(',', $userRoleAssociation);
                    foreach ($rolesList as $key => $role) {
                        $roles = $this->em->getReference('QuizzingPlatform\Entity\SecRole', $role);
                        $orgGroupRoleAssociation = new OrgGroupRoleAssociation();
                        $orgGroupRoleAssociation->setGroup($qtiGroup);
                        $orgGroupRoleAssociation->setRole($roles);
                        $this->em->persist($orgGroupRoleAssociation);
                    }
                    $this->em->flush();
                }
                /*
                 * Group to User Association
                 */ else {

                    /*
                     * Delete the existing entry of this group and recreate new entry
                     */
                    //Delete Role Association to Group
                    self::deleteRoleAssocByGroupId($groupId);
                    //Delete User Association to Group
                    self::deleteUserAssocByGroupId($groupId);

                    $usersList = explode(',', $userRoleAssociation);
                    foreach ($usersList as $key => $user) {

                        $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $user);

                        // Change user_belongs to Groups
                        $orgUserProfile->setUserBelongsTo($this->app['userStatus']['GROUP']);

                        $users = $this->em->getReference('QuizzingPlatform\Entity\OrgUserProfile', $user);
                        $orgGroupUsers = new OrgGroupUsers();
                        $orgGroupUsers->setGroup($qtiGroup);
                        $orgGroupUsers->setUser($users);
                        $orgGroupUsers->setCreatedBy($userId);
                        $orgGroupUsers->setCreatedDate($this->dateTime);
                        $orgGroupUsers->setModifiedBy($userId);
                        $orgGroupUsers->setModifiedDate($this->dateTime);
                        $this->em->persist($orgGroupUsers);
                    }
                    $this->em->flush();
                }
                return $groupId;
            } else {
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Group updation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    public function delete($groupId) {
        try {

            // Check user record exists
            $groupDataExists = self::checkGroupAlreadyExists($groupId);

            //Check for User already exists
            if (!empty($groupDataExists)) {

                // Checks whether the group is mapped with the users or not
                $userGroupMappingCheck = self::groupAssociatedWithUsers($groupId);
                if (empty($userGroupMappingCheck)) {
                    // soft delete the User details 
                    $orgUserProfile = $this->em->getReference('QuizzingPlatform\Entity\OrgGroup', $groupId);

                    // Change is_deleted field to 0
                    $orgUserProfile->setIsDeleted($this->app['cache']->fetch('DELETED'));
                    $this->em->flush();
                    $this->app['log']->writeLog("Successfully soft deleted the Group Information : " . $groupId);
                    return true;
                } else {
                    $this->app['log']->writeLog("Failed to soft delete the Group Information, group associated with the users : " . $groupId);
                    return 'mappingExists';
                }
            } else {
                $this->app['log']->writeLog("Failed to  soft delete the Group Information");
                return false;
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'User Deletion Exception  => ' . $ex->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    public function checkGroupAlreadyExists($groupId = NULL) {
        if ($groupId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('og.groupName')
                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og');
            if ($groupId) {
                $qb->where('og.groupId= :groupId')
                        ->setParameter('groupId', $groupId);
            }
            $groupDataExists = $qb->getQuery()->getArrayResult();
            return $groupDataExists;
        }
        return FALSE;
    }

    /**
     * 
     * @param integer $groupId - Group id for which we need to check users association
     * @desc - Checks whether any Users already associated with the given group id
     */
    public function groupAssociatedWithUsers($groupId = NULL) {
        if ($groupId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('og.groupName')
                    ->from('QuizzingPlatform\Entity\OrgGroup', 'og')
                    ->join('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu', \Doctrine\ORM\Query\Expr\Join::WITH, 'og.groupId=ogu.group')
                    ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=ogu.user')
                    ->where('og.groupId= :groupId')
                    ->setParameter('groupId', $groupId)
                    ->andWhere('og.isDeleted = :isDeleted')
                    ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE'])
                    ->andWhere('oup.isDeleted = :isDeleted')
                    ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);

            $groupDataExists = $qb->getQuery()->getArrayResult();
            return $groupDataExists;
        }
        return FALSE;
    }

    public function deleteRoleAssocByGroupId($groupId) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('QuizzingPlatform\Entity\OrgGroupRoleAssociation', 'ogra')
                        ->where('ogra.group= :group')
                        ->setParameter('group', $groupId)
                        ->getQuery()->execute();
    }

    public function deleteUserAssocByGroupId($groupId) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('QuizzingPlatform\Entity\OrgGroupUsers', 'ogu')
                        ->where('ogu.group= :group')
                        ->setParameter('group', $groupId)
                        ->getQuery()->execute();
    }

}
