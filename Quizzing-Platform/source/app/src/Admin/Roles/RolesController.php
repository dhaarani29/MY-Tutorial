<?php

/**
 * RolesController - Handles Roles module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Roles;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class RolesController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "role";

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
     * Get All the role information
     * @param Request $request
     * @sampleUrl: /api/roles?roleName=admin&description=superadmin&page=1&perPage=10&sort=rolename&userId=1
     */
    public function getAllRoles(Request $request) {

        $roleRequest = $request->query->all();

        //Associated item collection filter
        $associatedRoles = $roleRequest['associated'];

        $userId = $roleRequest['userId'];
        if ($userId) {
            $associatedUserId = $roleRequest['associatedUserId'];

            // Check user has permission to view role details.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

            if ($hasPermission) {

                $roleValueArray = $this->app['roles.service']->getRoles($roleRequest, $associatedRoles, $associatedUserId);

                // Return role list.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($roleValueArray, $this->success);
                return $response;
            } else {

                // Return following error if your doesn't have permission to view the metadata.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {
            // Input user id missing
            return $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
        }
    }

    /**
     * 
     * @param Request $request - Input values
     * @return JSON Array of role information
     */
    public function getRolesById(Request $request) {
        $roleId = $request->get('id');
        
            $roleData = $request->query->all();
            $loggedInUserId = $roleData['userId'];

            if ($loggedInUserId) {
                // Check user has permission to see the user information.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');

                if ($hasPermission) {

                    $roleDataArray = $this->app['roles.service']->find($roleId);
                    if(count($roleDataArray) > 0 && isset($roleDataArray)) {
                        return $this->app['systemsettings.controller']->returnSuccessResponse($roleDataArray, $this->success);
                    } else {
                        return $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INVALID_ROLE_ID_ERROR']);
                    }
                } else {

                    // If user doesn't have permission to view user then return following error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // Input user id missing
                return $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
            }
       
    }

    /**
     * 
     * @param Request $request - Input values
     * @return JSON Array of delete response
     */
    public function deleteRoleById(Request $request) {
        $roleId = $request->get('id');
        if ($roleId != "") {
            $roleData = $request->query->all();
            $loggedInUserId = $roleData['userId'];

            if ($loggedInUserId) {
                // Check user has permission to see the user information.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');

                if ($hasPermission) {
                    $response = $this->app['roles.service']->delete($roleId);
                    if ($response === true) {

                        // Return if successfully deleted the User Details.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                        return $response;
                    } else if ($response == 'alreadyExists') {
                        // Return following error if any error occurs while deleting the role information.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['ROLE_ASSOC_ERROR']);
                        return $response;
                    } else {

                        // Return following error if any error occurs while deleting the User information.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ROLE_DELETE_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to view user then return following error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                // Input user id missing
                return $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
            }
        } else {
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ROLE_ID_ERROR']);
            return $response;
        }
    }
    /*
     * By srilakshmi R
     * to create role
     */
    public function createRole(Request $request) 
    {
        
       
        if (!empty($request->getContent())) {

            $roleData = json_decode($request->getContent(), true);
            $userId = $roleData['userId'];
           
            if ($userId) {

                // Check user has permission to create item collection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $roleData['info'] = "Request Data : ";
                    $this->app['log']->writeLog($roleData);

                    //Create the group based on the Request
                    $createdId = $this->app['roles.service']->create($roleData);

                    if (is_int($createdId)) {

                        //After successful insertion , return newly created item collection ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If item collection Association/item collection name exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_ROLE_NAME_ERROR']);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ROLE_CREATION_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_ROLE_CREATE_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['CREATE_ROLE_EMPTY_ERROR']);
            return $response;
        }
        
    }
    /**
     * 
     * @param Request $request
     * @return JSON Array of Group data
     */
    public function updateRole(Request $request) 
    {
        $roleId = $request->get('id');
         if (!empty($request->getContent())) {

            $roleData = json_decode($request->getContent(), true);
            $userId = $roleData['userId'];
           
            if ($userId) {

                // Check user has permission to create item collection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $roleData['info'] = "Request Data : ";
                    $this->app['log']->writeLog($roleData);
                    $roleDataArray = $this->app['roles.service']->find($roleId);
                    
                    if(!empty($roleDataArray))
                    {
                    //Create the group based on the Request
                    $createdId = $this->app['roles.service']->update($roleData, $roleId);

                    if (is_int($createdId)) {

                        //After successful insertion , return newly created item collection ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If item collection Association/item collection name exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_ROLE_NAME_ERROR']);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ROLE_CREATION_ERROR']);
                        return $response;
                    }
                     }
                    else
                    {
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INVALID_ROLE_ID_ERROR']);
                        return $response; 
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_ROLE_CREATE_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['CREATE_GROUP_EMPTY_ERROR']);
            return $response;
        }
        
    }

}
