<?php

/**
 * RolesRepository - It's the repository class file to handle the Roles module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Roles;

//Silex Application Namespace
use Silex\Application;
use QuizzingPlatform\Services\RepositoryInterface;
use QuizzingPlatform\Services\CommonHelper;
use QuizzingPlatform\Entity\SecRole;
use QuizzingPlatform\Entity\SecRolePermissions;
use QuizzingPlatform\Entity\SecPermission;

class RolesRepository implements RepositoryInterface {

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
     * Fetching all the roles based on the search filters(role name and description)
     * @param type $roleRequest
     * @return boolean
     */
    public function getRoles($roleRequest, $associatedRoles = NULL, $associatedUserId = NULL) {

        try {

            // Declare common sorting,searching and pagination parameters with default values.
            // Assign filters to null by default
            $roleName = $description = "";

            // Define default offset value
            $offset = $this->app['cache']->fetch('offset');

            // Page number
            $page = $this->app['cache']->fetch('page');

            // Per page count
            $perPage = $this->app['cache']->fetch('limit');

            // Default sorting type
            $sortType = $this->app['cache']->fetch('sortType');

            // Default sorting field 
            $sortField = "roleName";

            // Declare a result array to return. 
            $roleValues = array();

            // Check if request is not null.
            if (!empty($roleRequest)) {

                foreach ($roleRequest as $key => $mrequest) {

                    // get values for $roleName,$description,$perPage  
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

            // Get Total of all the roles based on the applied filters. 
            $totalRoles = self::getRoleCount($roleName, $description, $associatedRoles, $associatedUserId);

            $roleValues['total'] = $totalRoles; // Total metadata count for pagination.
            $roleValues['data'] = array();

            // Check if count is greater than 0
            if ($totalRoles > 0) {


                //Fetching the role Details
                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('sr.roleId as id', 'sr.roleName as roleName', 'sr.description as description')
                        ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                        ->where('sr.status = :status') // Retrieve only active role's
                        ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

                // If rolename filter is passed, then add in to where condition.
                if ($roleName != "") {
                    $query->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                            ->setParameter('roleName', '%' . $roleName . '%');
                }

                // If description filter is passed, then add in to where condition.
                if ($description != "") {
                    $query->andWhere($qb->expr()->like('sr.description', ':description'))
                            ->setParameter('description', '%' . $description . '%');
                }
                //Associated & non associated user roles
                if ($associatedRoles != NULL) {
                    //Fetching the roles for the particular user
                    $qbs = $this->em->createQueryBuilder();
                    $qbs->select('sr.roleId as id')
                            ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                            ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=sr.roleId')
                            ->where('sr.status = :status') // Retrieve only active role's
                            ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                            ->andWhere('sura.userId=:userId')
                            ->setParameter('userId', $associatedUserId);

                    $subQuery = $qbs->getQuery()->getArrayResult();
                    //If the user associated to role return the role details
                    if (!empty($subQuery)) {
                        $subQueryResult = array();
                        //Push the role ids
                        foreach ($subQuery as $key => $value) {
                            array_push($subQueryResult, $value['id']);
                        }

                        $associatedUserRoles = implode(',', $subQueryResult);
                        //Associated roles for the user
                        if ($associatedRoles == $this->app['cache']->fetch('ACTIVE')) {

                            $query->andWhere($qb->expr()->In('sr.roleId', $associatedUserRoles));
                        }
                        //Dissociated roles for the user
                        elseif ($associatedRoles == $this->app['cache']->fetch('DELETED')) {

                            $query->andWhere($qb->expr()->notIn('sr.roleId', $associatedUserRoles));
                        }
                    }
                    //If the user is not associated to role return all roles
                    else {
                        if ($associatedRoles == $this->app['cache']->fetch('ACTIVE')) {
                            return 0;
                        } elseif ($associatedRoles == $this->app['cache']->fetch('DELETED')) {
                            $query->andWhere('1=1');
                        }
                    }
                }
                if (empty($noLimit)) {
                    // Add limits and sorting to query.
                    $query->setFirstResult($offset)
                            ->setMaxResults($perPage)
                            ->orderBy($sortField, $sortType);
                } else {
                    $query->orderBy($sortField, 'ASC');
                }

                // Get the results
                $roleReturnValues = $qb->getQuery()->getArrayResult(); //->getSQL(); //
                $roleValues['data'] = $roleReturnValues;
            }

            //Return the result array.
            return $roleValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'Roles get all Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    /**
     * get the role count based on the filters
     * @param type $roleName
     * @param type $description
     * @return type
     */
    public function getRoleCount($roleName = NULL, $description = NULL, $associatedRoles = NULL, $associatedUserId = NULL) {

        //Fetching the count of role
        $qb = $this->em->createQueryBuilder();
        $totalQuery = $qb->select('COUNT(sr.roleId) as total')
                ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                ->where('sr.status = :status') // Retrieve only active role's
                ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));

        // If roleName filter is passed, then add in to where condition.
        if ($roleName != "") {
            $totalQuery->andWhere($qb->expr()->like('sr.roleName', ':roleName'))
                    ->setParameter('roleName', '%' . $roleName . '%');
        }

        // If description filter is passed, then add in to where condition.
        if ($description != "") {
            $totalQuery->andWhere($qb->expr()->like('sr.description', ':description'))
                    ->setParameter('description', '%' . $description . '%');
        }

        //Associated & non associated user roles
        if ($associatedRoles != NULL) {
            //Fetching the roles for the particular user
            $qbs = $this->em->createQueryBuilder();
            $qbs->select('sr.roleId as id')
                    ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                    ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sura.roleId=sr.roleId')
                    ->where('sr.status = :status') // Retrieve only active role's
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'))
                    ->andWhere('sura.userId=:userId')
                    ->setParameter('userId', $associatedUserId);

            $subQuery = $qbs->getQuery()->getArrayResult();

            //If the user associated to role return the role details
            if (!empty($subQuery)) {

                $subQueryResult = array();
                //push the role ids
                foreach ($subQuery as $key => $value) {
                    array_push($subQueryResult, $value['id']);
                }

                $associatedUserRoles = implode(',', $subQueryResult);
                //Associated roles for the user
                if ($associatedRoles == $this->app['cache']->fetch('ACTIVE')) {

                    $totalQuery->andWhere($qb->expr()->In('sr.roleId', $associatedUserRoles));
                }
                //Dissociated roles for the user
                elseif ($associatedRoles == $this->app['cache']->fetch('DELETED')) {

                    $totalQuery->andWhere($qb->expr()->notIn('sr.roleId', $associatedUserRoles));
                }
            }
            //If the user is not associated to role return all roles
            else {
                if ($associatedRoles == $this->app['cache']->fetch('ACTIVE')) {
                    return 0;
                } elseif ($associatedRoles == $this->app['cache']->fetch('DELETED')) {
                    $totalQuery->andWhere('1=1');
                }
            }
        }

        // Get the results
        $totalRoles = $qb->getQuery()->getSingleScalarResult();

        return $totalRoles;
    }

    /**
     * Create the role
     * @param type $roleData
     * @return boolean
     */
    public function create($roleData = null) {

        try {
            //Input Request For role Creation
            //check Duplicate test name
            $qb = $this->em->createQueryBuilder();
            $qb->select('sr.roleId')
                    ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                    ->where('sr.roleName =:roleName')
                    ->andWhere('sr.status =:status')
                    ->setParameter('roleName', $roleData['roleName'])
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            $roleExist = $qb->getQuery()->getArrayResult();
            $userId = $roleData['userId'];
            //If its not duplicate
            if (empty($roleExist)) {
                $qtiRole = new SecRole();

                $qtiRole->setRoleName($roleData['roleName']);
                $qtiRole->setDescription($roleData['description']);
                $qtiRole->setStatus($this->app['cache']->fetch('ACTIVE'));
                $qtiRole->setCreatedBy($userId);
                $qtiRole->setCreatedDate($this->dateTime);
                $qtiRole->setModifiedBy($userId);
                $qtiRole->setModifiedDate($this->dateTime);
                $this->em->persist($qtiRole); //Inserting the Above Field Values to role table
                $this->em->flush();
                $roleId = $qtiRole->getRoleId();

                //insert role permissions into secondary role permissins table
                foreach ($roleData['rolePermission'] as $key => $val) {
                    foreach ($val as $k => $v) {
                        if ($v == 1) {
                            $qryDB = $this->em->createQueryBuilder();
                            $qry = $qryDB->select('sp.permissionId')
                                    ->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                                    
                                    ->where('sp.status = :status')
                                    ->setParameter('status', $this->app['isDeleted']['ACTIVE'])
                                    ->andWhere('sp.resourceTitle = :resourceTitle')
                                    ->setParameter('resourceTitle', $key)
                                    ->andWhere('sp.action = :action')
                                    ->setParameter('action', $k);

                            $permissionData = $qryDB->getQuery()->getArrayResult();
                            //print_R($permissionData);
                            $permissionId = $permissionData[0]['permissionId'];
                            $qtiRoleId = $this->em->getRepository('QuizzingPlatform\Entity\SecRole')->findOneByRoleId(array('roleId' => $roleId));
                            $qtiPermissionId = $this->em->getRepository('QuizzingPlatform\Entity\SecPermission')->findOneByPermissionId(array('permissionId' => $permissionId));
                            $qtiRolePermission = new SecRolePermissions();            
                            $qtiRolePermission->setRole($qtiRoleId);
                            $qtiRolePermission->setPermission($qtiPermissionId);
                            $qtiRolePermission->setCreatedBy($userId);
                            $qtiRolePermission->setCreatedDate($this->dateTime);
                            $qtiRolePermission->setModifiedBy($userId);
                            $qtiRolePermission->setModifiedDate($this->dateTime);
                            $this->em->persist($qtiRolePermission); //Inserting the Above Field Values to role table
                            $this->em->flush();
                            
                        }
                    }
                }

                
                return $roleId;
            } else {
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Role creation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    public function find($id) {
        try {
            $roleDataValues = array();
            if ($id > 0) {


                $qb = $this->em->createQueryBuilder();
                $query = $qb->select('sr.roleName', 'sr.description')
                        ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                        ->where('sr.roleId= :roleId')
                        ->setParameter('roleId', $id)
                        ->andWhere('sr.status = :status')
                        ->setParameter('status', $this->app['isDeleted']['ACTIVE']);

                $rolePrimaryData = $qb->getQuery()->getArrayResult();
                if (isset($rolePrimaryData) && count($rolePrimaryData) > 0) {
                    $qryDB = $this->em->createQueryBuilder();
                    $qry = $qryDB->select('sp.resourceTitle', 'sp.actionTitle', 'IDENTITY(srp.role) AS roleAccess')
                            ->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                            ->leftjoin('QuizzingPlatform\Entity\SecRolePermissions', 'srp', \Doctrine\ORM\Query\Expr\Join::WITH, 'sp.permissionId = srp.permission')
                            ->Where('sp.status = :status')
                            ->setParameter('status', $this->app['isDeleted']['ACTIVE']);

                    $permissionData = $qryDB->getQuery()->getArrayResult();

                    $roleDataValues['permissions'] = $roleTitles = $resourceTitleArra = array();

                    $roleDataValues['roleName'] = $rolePrimaryData[0]['roleName'];
                    $roleDataValues['description'] = $rolePrimaryData[0]['description'];

                    foreach ($permissionData as $key => $val) {
                        $resourceTitleArra[] = $val['resourceTitle'];
                    }
                    $resourceTitleArra = array_unique($resourceTitleArra);
                    if (count($resourceTitleArra) > 0) {
                        foreach ($resourceTitleArra as $k => $v) {
                            $roleDataValues['permissions'][$v]['title'] = $v;

                            foreach ($permissionData as $key => $val) {
                                if ($val['resourceTitle'] === $v) {
                                    $flag = NULL;
                                    $flag = ($val['roleAccess'] == $id || $val['roleAccess'] == NULL) ? "1" : NULL;
                                    SWITCH (strtolower($val['actionTitle'])) {
                                        case 'create' :
                                            $roleDataValues['permissions'][$val['resourceTitle']]['create'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                            break;
                                        case 'edit' :
                                            $roleDataValues['permissions'][$val['resourceTitle']]['edit'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                            break;
                                        case 'delete' :
                                            $roleDataValues['permissions'][$val['resourceTitle']]['delete'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                            break;
                                        case 'view' :
                                            $roleDataValues['permissions'][$val['resourceTitle']]['view'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                            break;
                                        case 'manage association' :
                                            $roleDataValues['permissions'][$val['resourceTitle']]['manageAssociation'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                            break;
                                        case 'manage security' :
                                            $roleDataValues['permissions'][$val['resourceTitle']]['manageSecurity'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $qryDB = $this->em->createQueryBuilder();
                $qry = $qryDB->select('sp.resourceTitle', 'sp.actionTitle')
                        ->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                        //->leftjoin('QuizzingPlatform\Entity\SecRolePermissions', 'srp', \Doctrine\ORM\Query\Expr\Join::WITH, 'sp.permissionId = srp.permission')
                        ->Where('sp.status = :status')
                        ->setParameter('status', $this->app['isDeleted']['ACTIVE']);

                $permissionData = $qryDB->getQuery()->getArrayResult();

                $roleDataValues['permissions'] = $roleTitles = $resourceTitleArra = array();

                $roleDataValues['roleName'] = $rolePrimaryData[0]['roleName'];
                $roleDataValues['description'] = $rolePrimaryData[0]['description'];

                foreach ($permissionData as $key => $val) {
                    $resourceTitleArra[] = $val['resourceTitle'];
                }
                $resourceTitleArra = array_unique($resourceTitleArra);
                if (count($resourceTitleArra) > 0) {
                    foreach ($resourceTitleArra as $k => $v) {
                        $roleDataValues['permissions'][$v]['title'] = $v;

                        foreach ($permissionData as $key => $val) {
                            if ($val['resourceTitle'] === $v) {
                                $flag = NULL;
                                $flag = ($val['roleAccess'] == $id || $val['roleAccess'] == NULL) ? "1" : NULL;
                                SWITCH (strtolower($val['actionTitle'])) {
                                    case 'create' :
                                        $roleDataValues['permissions'][$val['resourceTitle']]['create'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                        break;
                                    case 'edit' :
                                        $roleDataValues['permissions'][$val['resourceTitle']]['edit'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                        break;
                                    case 'delete' :
                                        $roleDataValues['permissions'][$val['resourceTitle']]['delete'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                        break;
                                    case 'view' :
                                        $roleDataValues['permissions'][$val['resourceTitle']]['view'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                        break;
                                    case 'manage association' :
                                        $roleDataValues['permissions'][$val['resourceTitle']]['manageAssociation'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                        break;
                                    case 'manage security' :
                                        $roleDataValues['permissions'][$val['resourceTitle']]['manageSecurity'] = ($val['roleAccess'] == $id) ? "1" : NULL;
                                        break;
                                }
                            }
                        }
                    }
                }
            }
            return $roleDataValues;
        } catch (Exception $e) {

            //Add exceptions to logger.
            $msg = 'role get by id Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);
            return false;
        }
    }

    public function update($roleData, $roleId) {
        try {

            //Input Request For role Creation
            //check Duplicate test name
            $qb = $this->em->createQueryBuilder();
            $qb->select('sr.roleId')
                    ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                    ->where('sr.roleName =:roleName')
                    ->andWhere('sr.status =:status')
                    ->andWhere('sr.roleId !=:roleId')
                    ->setParameter('roleId', $roleId)
                    ->setParameter('roleName', $roleData['roleName'])
                    ->setParameter('status', $this->app['cache']->fetch('ACTIVE'));
            $roleExist = $qb->getQuery()->getArrayResult();
            $userId = $roleData['userId'];
            //If its not duplicate
            if (empty($roleExist)) {
                //print_r($roleData);die;
                $qtiRole = $this->em->getReference('QuizzingPlatform\Entity\SecRole', $roleId);
                $qtiRole->setRoleName($roleData['roleName']);
                $qtiRole->setDescription($roleData['description']);
                $qtiRole->setStatus($this->app['cache']->fetch('ACTIVE'));
                $qtiRole->setModifiedBy($userId);
                $qtiRole->setModifiedDate($this->dateTime);
                //$this->em->persist($qtiRole); //Inserting the Above Field Values to role table
                $this->em->flush();
                $roleId = $qtiRole->getRoleId();
                
                //delete previously saved permissions for that roleid and insert new permissions.
                $qb = $this->em->createQueryBuilder();
                $query = $qb->delete('QuizzingPlatform\Entity\SecRolePermissions', 'srp')
                        ->where('srp.role= :roleId')
                       
                        ->setParameter('roleId', $roleId)
                        ->getQuery()->execute();
                foreach ($roleData['rolePermission'] as $key => $val) {
                    foreach ($val as $k => $v) {
                        if ($v == 1) {
                            $qryDB = $this->em->createQueryBuilder();
                            $qry = $qryDB->select('sp.permissionId')
                                    ->from('QuizzingPlatform\Entity\SecPermission', 'sp')
                                    
                                    ->where('sp.status = :status')
                                    ->setParameter('status', $this->app['isDeleted']['ACTIVE'])
                                    ->andWhere('sp.resourceTitle = :resourceTitle')
                                    ->setParameter('resourceTitle', $key)
                                    ->andWhere('sp.action = :action')
                                    ->setParameter('action', $k);

                            $permissionData = $qryDB->getQuery()->getArrayResult();
                            //print_R($permissionData);
                            $permissionId = $permissionData[0]['permissionId'];
                            $qtiRoleId = $this->em->getRepository('QuizzingPlatform\Entity\SecRole')->findOneByRoleId(array('roleId' => $roleId));
                            $qtiPermissionId = $this->em->getRepository('QuizzingPlatform\Entity\SecPermission')->findOneByPermissionId(array('permissionId' => $permissionId));
                            $qtiRolePermission = new SecRolePermissions();            
                            $qtiRolePermission->setRole($qtiRoleId);
                            $qtiRolePermission->setPermission($qtiPermissionId);
                            $qtiRolePermission->setCreatedBy($userId);
                            $qtiRolePermission->setCreatedDate($this->dateTime);
                            $qtiRolePermission->setModifiedBy($userId);
                            $qtiRolePermission->setModifiedDate($this->dateTime);
                            $this->em->persist($qtiRolePermission); //Inserting the Above Field Values to role table
                            $this->em->flush();
                            
                        }
                    }
                }
                return $roleId;
            } else {
                return $this->app['cache']->fetch('exists');
            }
        } catch (Exception $ex) {
            //Add exceptions to logger.
            $msg = 'Role updation Exception  => ' . $e->getMessage();
            $this->app['log']->writeLog($msg);

            return false;
        }
    }

    public function delete($id) {
        if ($id) {
            $recordExists = self::checkRoleAlreadyExists($id);

            //var_dump($recordExists); die;
            $roleUserAssocData = self::checkRoleAssociatedWithUsers($id);
            if (!empty($roleUserAssocData)) {
                return 'alreadyExists';
            }

            if (!empty($recordExists) && empty($roleUserAssocData)) {
                // If nodeStatus is deleted, then soft delete the lookup value.
                $roleValue = $this->em->getReference('QuizzingPlatform\Entity\SecRole', $id);
                $roleValue->setStatus($this->app['cache']->fetch('DELETED'));
                $this->em->flush();
                return true;
            } else {
                $this->app['log']->writeLog("Failed to  soft delete the Role Information : $id");
                return false;
            }
        }
    }

    public function checkRoleAlreadyExists($roleId = NULL) {
        if ($roleId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('sr.roleName')
                    ->from('QuizzingPlatform\Entity\SecRole', 'sr');
            if ($roleId) {
                $qb->where('sr.roleId= :roleId')
                        ->setParameter('roleId', $roleId);
            }

            $roleDataExists = $qb->getQuery()->getArrayResult();
            return $roleDataExists;
        }
        return FALSE;
    }

    public function checkRoleAssociatedWithUsers($roleId = NULL) {
        if ($roleId) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('sr.roleName')
                    ->from('QuizzingPlatform\Entity\SecRole', 'sr')
                    ->join('QuizzingPlatform\Entity\SecUserRoleAssociation', 'sura', \Doctrine\ORM\Query\Expr\Join::WITH, 'sr.roleId= sura.roleId')
                    ->join('QuizzingPlatform\Entity\OrgUserProfile', 'oup', \Doctrine\ORM\Query\Expr\Join::WITH, 'oup.userId=sura.userId')
                    ->where('sr.roleId= :roleId')
                    ->setParameter('roleId', $roleId)
                    ->andWhere('oup.isDeleted = :isDeleted')
                    ->setParameter('isDeleted', $this->app['isDeleted']['ACTIVE']);


            $roleUserAssocData = $qb->getQuery()->getArrayResult();

            return $roleUserAssocData;
        }
        return FALSE;
    }

}
