<?php

/**
 * UsersController - Handles users/Groups/Roles module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Users;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * *** Custom Error Codes: ***
 * 5001	"Error retrieving resource permissions."
 * 5002	"Request is empty."
 * 5003	"Duplicate User information"
 * 5004	"Error Creating User data"
 * 5005	"User data request is empty"
 * 5006	"User details Not Found"
 * 5007	"Error Deleting the User information"
 * 5008	"Error while updating User information"
 * 5009	"Error while Associating User information"
 * 5010	"User Email is invalid"
 * 1010:Don't have permission. Please contact WK admin
 * 
 */

class UsersController {

    protected $app;
    protected $module;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {

        $this->app = $app;
        $this->module = "user";

        // HTTP STATUS CODES
        $this->badrequest = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->created = $this->app['cache']->fetch('HTTP_CREATED');
        $this->duplicate = $this->app['cache']->fetch('HTTP_DUPLICATE');
        $this->forbidden = $this->app['cache']->fetch('HTTP_FORBIDDEN');
        $this->nocontent = $this->app['cache']->fetch('HTTP_NOCONTENT');
        $this->notFound = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success = $this->app['cache']->fetch('HTTP_SUCCESS');
    }

    /**
     * 
     * @param type $userId
     * @param type $resource
     * @param type $action
     * @return boolean 
     * @Desc : Controller method to request the users.service to check wether user has permission to access the resource and actions.
     */
    public function checkResourceActionPermission($userId, $resource = "", $action = "") {

        $permissions = $this->app['users.service']->checkResourceActionPermission($userId, $resource, $action);

        if (!empty($permissions)) {

            // If has permission return true
            return true;
        } else {

            // else return false;
            return false;
        }
    }

    /**
     * 
     * @param Request $request
     * @return JsonResponse resource permissions.
     * @Desc : Controller method to request the users.service to get all the resource action which user has permission to access.
     */
    public function getResourcePermissions(Request $request) {

        $resourceData = $request->query->all();

        if (!empty($resourceData)) {

            $userId = $resourceData['userId'];
            $resource = $resourceData['resource'];

            $permissions = $this->app['users.service']->checkResourceActionPermission($userId, $resource);

            if (!empty($permissions)) {

                // If permission exists for requested resource then return the list.
                return new JsonResponse($permissions, $this->success);
            } else {

                // If any erro ocures then return following customized error
                $errorResponse = $this->app['GET_RESOURCE_PERMISSION_ERROR'];

                //Write to Log/ELK
                $log_id = $this->app['log']->writeLog($errorResponse);
                $errorResponse['log_reference'] = $log_id;

                return new JsonResponse($errorResponse, $this->notFound);
            }
        } else {

            // If input request is empty then return fallowing error.
            $errorResponse = $this->app['RESOURCE_REQUEST_EMPTY_ERROR'];

            //Write to Log/ELK
            $log_id = $this->app['log']->writeLog($errorResponse);
            $errorResponse['log_reference'] = $log_id;

            return new JsonResponse($errorResponse, $this->badrequest);
        }
    }

    /**
     * @Desc - Will create a User record.
     * @params - Input JSON Array, Output CreatedId [if success], Error message [if any].
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     */
    public function createUserDetails(Request $request) {
        if (!empty($request->getContent())) {

            $userData = json_decode($request->getContent(), true);
            $userId = $userData['userId'];

            // Check user has permission to create user information.
            $hasPermission = $this->app['users.service']->checkResourceActionPermission($userId, $this->module, 'create');

            if ($hasPermission) {


                // Store the request data in to logs. 
                $userData['info'] = "Request Data : ";
                $this->app['log']->writeLog($userData);

                $userEmail = ($userData['userEmail']) ? trim($userData['userEmail']) : NULL;
                if ($userEmail) {
                    // Validate email address, only if field having @ symbol
                    $emailDomainName = substr(strrchr($userEmail, "@"), 1);
                    $emailFullDomainName = 'www.' . $emailDomainName;
                    if (!checkdnsrr($emailDomainName, "ANY") || !checkdnsrr($emailFullDomainName, "ANY")) {
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['INVALID_USER_EMAIL_ERROR']);
                        return $response;
                    }
                }
                $accessToken = $request->headers->get('Authorization');

                $requestFrom = $request->headers->get('requestFrom');

                //Create the user Information based on the Request
                $createdId = $this->app['users.service']->createUser($userData, $accessToken, $requestFrom);

                if (is_int($createdId)) {

                    //After successful insertion , return newly created User ID.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($createdId, $this->created);
                    return $response;
                } elseif ($createdId == $this->app['cache']->fetch('exists')) {

                    // If user Information exists then return duplicate  customized error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_USER_INFO_ERROR']);
                    return $response;
                } else {

                    // If any error occurs while creating, then return common error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['CREATE_USER_INFO_ERROR']);
                    return $response;
                }
            } else {

                // If user doesn't have permission to create user Info then return permission error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        } else {

            // If input request is empty then return following error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['EMPTY_USER_INFO_REQUEST_ERROR']);
            return $response;
        }
    }

    /**
     * @desc Provides user information for the given user id
     * @return Json Array Will return the User information in JSON format
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     * 
     */
    public function getUserById(Request $request) {
        $userId = $request->get('id');
        if ($userId != "") {

            $getUserdata = $request->query->all();
            $loggedInUserId = $getUserdata['userId'];

            //If the Api is calling from myprofile , no need to check the user permission
            $myProfile = $getUserdata['myprofile'];

            // Check user has permission to see the user information.
            $hasPermission = $this->app['users.service']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');
            if (!empty($myProfile)) {
                $hasPermission = 1;
            }
            if ($hasPermission) {
                $userDataArray = $this->app['users.service']->find($userId);
                if (!empty($userDataArray)) {

                    // Return user details.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($userDataArray, $this->success);
                    return $response;
                } else {

                    // Return following error if user not exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['USER_INFO_NOT_FOUND_ERROR']);
                    return $response;
                }
            } else {

                // If user doesn't have permission to view user then return following error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /*
     * @Desc - Get all user information
     */

    public function getAllUsers(Request $request) {

        $getUserdata = $request->query->all();
        $loggedInUserId = $getUserdata['userId'];
        if ($loggedInUserId != "") {

            // Check user has permission to see the user information.
            $hasPermission = $this->app['users.service']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');

            if ($hasPermission) {
                $userDataArray = $this->app['users.service']->getUsersList($getUserdata);


                $response = $this->app['systemsettings.controller']->returnSuccessResponse($userDataArray, $this->success);
                return $response;
            }

            //Return following error if user don't have permission to delete User information.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc - Will delete a User record.
     * @params - Input UserId
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Dhaarani S <dharani.s@impelsys.com>
     */
    public function deleteUser(Request $request) {
        $userId = $request->get('id'); //User Id
        if ($userId != "") {

            $getUserdata = $request->query->all();
            $loggedInUserId = $getUserdata['userId'];

            // Check user has permission to delete user Details.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'delete');

            if ($hasPermission) {

                // soft delete the User 
                $response = $this->app['users.service']->deleteUser($userId);

                //If the response is True , Deleted Successfully
                if ($response === true) {

                    // Return if successfully deleted the User Details.
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                    return $response;
                } else {

                    // Return following error if any error occurs while deleting the User information.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['DELETE_USER_INFO_ERROR']);
                    return $response;
                }
            } else {

                //Return following error if user don't have permission to delete User information.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @Desc - Will update a User record.
     * @params - Input UserId and update data
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Dhaarani S <dharani.s@impelsys.com>
     * @param Request $request
     * @return type
     */
    public function updateUser(Request $request) {

        $userId = $request->get('id');
        $accessToken = $request->headers->get('Authorization');

        $requestFrom = $request->headers->get('requestFrom');

        if ($userId != "") {

            $updateUser = json_decode($request->getContent(), true);
            $updateUser['info'] = "Update request Data : ";
            $this->app['log']->writeLog($updateUser);

            //If the Api is calling from myprofile , no need to check the user permission
            $myProfile = $updateUser['resource'];

            $loggedInUserId = $updateUser['userId'];

            // Check user has permission to edit user Information.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'edit');

            if ($myProfile == 'myprofile') {
                $hasPermission = 1;
            }

            if ($hasPermission) {

                // Check wether user details exists to edit.
                $userValue = $this->app['users.service']->find($userId);

                if (!empty($userValue)) {


                    $userEmail = ($updateUser['userEmail']) ? trim($updateUser['userEmail']) : NULL;
                    if ($userEmail) {
                        // Validate email address, only if field having @ symbol
                        $emailDomainName = substr(strrchr($userEmail, "@"), 1);
                        $emailFullDomainName = 'www.' . $emailDomainName;
                        if (!checkdnsrr($emailDomainName, "ANY") || !checkdnsrr($emailFullDomainName, "ANY")) {
                            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['INVALID_USER_EMAIL_ERROR']);
                            return $response;
                        }
                    }

                    // If user exists then update the user information.
                    $updated = $this->app['users.service']->update($userValue, $updateUser, $accessToken, $requestFrom);

                    if ($updated === true) {

                        // Return if successful update.

                        $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                        return $response;
                    } elseif ($updated == $this->app['cache']->fetch('exists')) {

                        // Return following error if user exists already for different id.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->duplicate, $this->app['DUPLICATE_USER_INFO_ERROR']);
                        return $response;
                    } else {

                        // Return following error if any error occurs while updating the user.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['UPDATE_USER_INFO_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if user doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['USER_INFO_NOT_FOUND_ERROR']);
                    return $response;
                }
            } else {

                // Return following error if user doesn't have permission to edit the user.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

    /**
     * @Desc - Will fetch user association [groups/roles] details
     * @params - userBelongsTo id and user id
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * @author Srinivasu M <Srinivasu.m@impelsys.com>
     * @param Request $request
     * @return json array of assciation information
     */
    public function userAssociation(Request $request) {

        $userId = $request->get('id');

        $getUserdata = $request->query->all();
        $loggedInUserId = $getUserdata['userId'];
        if ($loggedInUserId != "") {

            // Check user has permission to see the user information.
            $hasPermission = $this->app['users.service']->checkResourceActionPermission($loggedInUserId, $this->module, 'view');

            if ($hasPermission) {

                $userDataArray = $this->app['users.service']->getUsersAssociationList($getUserdata, $userId);
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($userDataArray, $this->success);
                return $response;
            }

            //Return following error if user don't have permission to delete User information.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Associate the roles and groups to users
     * @param Request $request
     * @return type
     */
    public function associateUser(Request $request) {

        //Associate user id
        $userId = $request->get('id');

        if ($userId != "") {

            //Decode the request
            $updateUser = json_decode($request->getContent(), true);
            $updateUser['info'] = "Update request Data : ";
            $this->app['log']->writeLog($updateUser);

            //Get permission userid
            $loggedInUserId = $updateUser['userId'];

            // Check user has permission to associate  user to roles and groups
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'manageAssociation');

            if ($hasPermission) {

                // Check wether user details exists to edit.
                $userValue = $this->app['users.service']->find($userId);

                if (!empty($userValue)) {

                    // If user exists then update the user information.
                    $updated = $this->app['users.service']->associateUser($userValue, $updateUser);

                    if ($updated === true) {

                        // Return if successful association.
                        $response = $this->app['systemsettings.controller']->returnSuccessResponse('', $this->nocontent);
                        return $response;
                    } else {

                        //Return following error if any error occurs while associating roles/groups the user.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['ASSOCIATING_USER_INFO_ERROR']);
                        return $response;
                    }
                } else {

                    // Return following error if user doesn't exists.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['USER_INFO_NOT_FOUND_ERROR']);
                    return $response;
                }
            } else {

                // Return following error if user doesn't have permission to edit the user.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
                return $response;
            }
        }
    }

}
