<?php

/**
 * GroupsController - Handles groups module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Groups;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class GroupsController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "group";

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
     * Get All the group information
     * @param Request $request
     * @sampleUrl: /api/groups?groupName=admin&description=superadmin&page=1&perPage=10&sort=groupName&userId=1
     */
    public function getAllGroups(Request $request) {

        $groupRequest = $request->query->all();

        $userId = $groupRequest['userId'];

        if ($userId) {

            $associatedGroups = $groupRequest['associated'];
            $associatedUserId = $groupRequest['associatedUserId'];
            // Check user has permission to view group details.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'view');

            if ($hasPermission) {

                $groupValueArray = $this->app['groups.service']->getGroups($groupRequest, $associatedGroups, $associatedUserId);
                // Return group list.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($groupValueArray, $this->success);
                return $response;
            } else {

                // Return following error if your doesn't have permission to view the group data.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {
            // Return following error if your doesn't have permission to view the group data.
            return $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
        }
    }

    /**
     * 
     * @param Request $request
     * @return JSON Array of Group data
     */
    public function getGroupById(Request $request) {
        $groupId = $request->get('id');
        if ($groupId != "") {
            $groupData = $request->query->all();
            $loggedInUserId = $groupData['userId'];

            if ($loggedInUserId) {
                // Check user has permission to see the user information.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');

                if ($hasPermission) {

                    $groupDataArray = $this->app['groups.service']->getGroupByid($groupId, $groupData);
                    if (count($groupDataArray) > 0) {
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($groupDataArray, $this->success);
                    } else {
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INVALID_GROUP_ID_ERROR']);
                    }
                    return $response;
                } else {

                    // If user doesn't have permission to view user then return following error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                return $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
            }
        } else {
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GROUP_ID_ERROR']);
            return $response;
        }
    }

    public function deleteGroupInfo(Request $request) {
        $groupId = $request->get('id');
        if ($groupId != "") {
            $groupData = $request->query->all();
            $loggedInUserId = $groupData['userId'];

            if ($loggedInUserId) {
                // Check user has permission to see the user information.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');

                if ($hasPermission) {

                    // soft delete the User 
                    $response = $this->app['groups.service']->delete($groupId);

                    //If the response is True , Deleted Successfully
                    if ($response === true) {

                        // Return if successfully deleted the User Details.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                        return $response;
                    } elseif ($response == 'mappingExists') {

                        // Return following error if any error occurs while deleting the group information.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GROUP_ASSOC_ERROR']);
                        return $response;
                    } else {

                        // Return following error if any error occurs while deleting the group information.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GROUP_DELETE_ERROR']);
                        return $response;
                    }
                } else {

                    //Return following error if user don't have permission to delete User information.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {
                return $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
            }
        } else {
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GROUP_ID_ERROR']);
            return $response;
        }
    }

    /**
     * 
     * @param Request $request
     * @return JSON Array of Group data
     */
    public function createGroup(Request $request) {

        if (!empty($request->getContent())) {

            $groupData = json_decode($request->getContent(), true);
            $userId = $groupData['userId'];

            if ($userId) {

                // Check user has permission to create item collection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $groupData['info'] = "Request Data : ";
                    $this->app['log']->writeLog($groupData);

                    //Create the group based on the Request
                    $createdId = $this->app['groups.service']->create($groupData);

                    if (is_int($createdId)) {

                        //After successful insertion , return newly created item collection ID.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                        return $response;
                    } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                        // If item collection Association/item collection name exists then return duplicate  customized error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_GROUP_NAME_ERROR']);
                        return $response;
                    } else {

                        // If any error occurs while creating, then return common error. 
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GROUP_CREATION_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_GROUP_CREATE_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['CREATE_GROUP_EMPTY_ERROR']);
            return $response;
        }
    }

    /**
     * 
     * @param Request $request
     * @return JSON Array of Group data
     */
    public function updateGroup(Request $request) {
        $groupId = $request->get('id');
        if (!empty($request->getContent())) {

            $groupData = json_decode($request->getContent(), true);
            $userId = $groupData['userId'];

            if ($userId) {

                // Check user has permission to create item collection.
                $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'create');

                if ($hasPermission) {

                    // Store the request data in to logs. 
                    $groupData['info'] = "Request Data : ";
                    $this->app['log']->writeLog($groupData);
                    //check item collection exists
                    $groupValue = $this->app['groups.service']->find($groupId);

                    if (!empty($groupValue)) {
                        //Create the group based on the Request
                        $createdId = $this->app['groups.service']->update($groupData, $groupId);

                        if (is_int($createdId)) {

                            //After successful insertion , return newly created item collection ID.
                            $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                            return $response;
                        } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                            // If item collection Association/item collection name exists then return duplicate  customized error.
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_GROUP_NAME_ERROR']);
                            return $response;
                        } else {

                            // If any error occurs while creating, then return common error. 
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['GROUP_CREATION_ERROR']);
                            return $response;
                        }
                    } else {
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['INVALID_GROUP_ID_ERROR']);
                        return $response;
                    }
                } else {

                    // If user doesn't have permission to create item/question then return permission error. 
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                    return $response;
                }
            } else {

                // If input request is not valid then return fallowing error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_INPUT_GROUP_CREATE_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return fallowing error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['CREATE_GROUP_EMPTY_ERROR']);
            return $response;
        }
    }

}
