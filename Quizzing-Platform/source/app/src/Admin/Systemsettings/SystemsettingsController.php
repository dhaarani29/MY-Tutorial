<?php

/**
 * SystemsettingsController - Handles generic system level settings requests.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * 
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Systemsettings;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * *** Custom Error Codes: ***
 * 6001:Error retrieving system configurations.
 * 6002:Error retrieving menus list.
 * 6003:Error clearing the cache.
 * 6005:Error retrieving Country list.
 * 6006:Error retrieving States list.
 * 6007:Error retrieving States list.
 * 6008:Error retrieving Group list.
 * 6009:Error retrieving Group data by id.
 * 6010:Error retrieving Group list.
 * 6011:Error retrieving Roles list.
 * 6012:Error retrieving Roles list.
 * 6013:Error retrieving role list.
 * 6014:Error retrieving status list.
 * 6015:Error retrieving user Type list.
 * 6016:Error retrieving System setting list.
 * 6017:Email is invalid
 * 6018:Error while updating information.
 * 6019:Input param missing.
 */

class SystemsettingsController {

    protected $app;
    public $notFound;
    public $success;
    protected $module;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;

        // HTTP Codes
        $this->notFound = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->badrequest = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->duplicate = $this->app['cache']->fetch('HTTP_DUPLICATE');
        $this->nocontent = $this->app['cache']->fetch('HTTP_NOCONTENT');
        $this->forbidden = $this->app['cache']->fetch('HTTP_FORBIDDEN');

        // Module name
        $this->module = "systemsettings";
    }

    /**
     * @desc : Returns the json successful response.
     * @param type $value
     * @param type $http_code
     * @return JsonResponse
     */
    public function returnSuccessResponse($value, $http_code) {

        // Returns the json successful response.
        return new JsonResponse($value, $http_code);
    }

    /**
     * @desc : Returns access token.
     * @param type $value
     * @param type $http_code
     * @return JsonResponse
     */
    public function returnAccessToken($userId, $token, $http_code) {

        $response = array(
            "token" => $token
        );

        if ($userId) {
            $response["userId"] = $userId;
        }

        // Returns the json successful response.
        return new JsonResponse($response, $http_code);
    }

    /**
     * @Desc : Compose error case response and return.
     * @param type $code
     * @param type $message
     * @param type $description
     * @param type $http_code
     * @return JsonResponse
     */
    public function returnErrorResponse($http_code = NULL, $errorArray = array()) {

        //Compose error case array and returns the error response.
        $errorResponse = array("code" => 4040, "title" => "Log message content is missing.", "description" => "Input Log message content is missing.");

        if (count($errorArray) > 0) {
            $errorResponse = $errorArray;
        }

        //Stores the logs in elk and gets the reference id from elk.
        $log_id = $this->app['log']->writeLog($errorResponse);

        // Add the log reference id to response array.
        $errorResponse['log_reference'] = $log_id;

        // Return error json array.
        return new JsonResponse($errorResponse, $http_code);
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to request the systemsettings.service to fetch system level configurations. 
     * @Return : Returns all the questin types.
     */
    public function getUIConfig() {

        $systemConfigs = $this->app['systemsettings.service']->getUIConfigurations();

        if (!empty($systemConfigs)) {

            // Return the configurations.
            return self::returnSuccessResponse($systemConfigs, $this->success);
        } else {

            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['SYSTEM_CONFIG_ERROR']);
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to Get menus list.
     * @Return : array, Returns menus list.
     */
    public function getMenusList(Request $request) {

        $accessToken = $request->headers->get($this->app['cache']->fetch('tokenHeaderKey'));

        $menusList = $this->app['systemsettings.service']->getMenusList($accessToken);

        if (!empty($menusList)) {

            // Return the configurations.
            return self::returnSuccessResponse($menusList, $this->success);
        } else {

            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['MENU_LIST_ERROR']);
        }
    }

    /**
     * @param : No parameters
     * @Desc : Controller method to clear the redis cache.
     * @Return : 204, no content
     */
    public function clearCache() {
        $clearCache = $this->app['systemsettings.service']->clearCache();

        if ($clearCache) {

            //If successfully cleared the cache return true 
            return true;
        } else {

            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['CLEAR_CACHE_ERROR']);
        }
    }

    /**
     * @Desc : Get all the Countries list
     * @return JSON Return the list of countries in JSON format.
     * @author Srinivasu M <srinivasu.m@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function getCountriesList() {

        $getCountries = $this->app['systemsettings.service']->getCountries();
        if (is_array($getCountries) && count($getCountries) > 0) {

            return self::returnSuccessResponse($getCountries, $this->success);
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['COUNTRY_LIST_ERROR']);
        }
    }

    /**
     * @Desc : Get all the States list
     * @return JSON Return the list of countries in JSON format.
     * @author Srinivasu M <srinivasu.m@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function getStatesList(Request $request) {

        $countryId = $request->get('country_id');
        if (is_numeric($countryId) && $countryId > 0) {
            $getStates = $this->app['systemsettings.service']->getStates($countryId);
            if (is_array($getStates) && count($getStates) > 0) {

                return self::returnSuccessResponse($getStates, $this->success);
            } else {
                // If any errors returned, return the customized error. 
                return self::returnErrorResponse($this->notFound, $this->app['STATE_LIST_ERROR']);
            }
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['INVALID_STATE_ID_ERROR']);
        }
    }

    /**
     * @Desc : Get all the Groups list
     * @return JSON Return the list of Groups in JSON format.
     * @author Srinivasu M <srinivasu.m@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function getGroupsList() {

        $getGroups = $this->app['systemsettings.service']->getGroups();
        if (is_array($getGroups) && count($getGroups) > 0) {

            return self::returnSuccessResponse($getGroups, $this->success);
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['GROUP_LIST_ERROR']);
        }
    }

    /**
     * @Desc : Get group list by id
     * @return JSON Return the list of specific group info in JSON format.
     * @author Srinivasu M <srinivasu.m@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function getGroupsListById(Request $request) {
        $groupId = $request->get('id');
        if (is_numeric($groupId) && $groupId > 0) {
            $getGroups = $this->app['systemsettings.service']->getGroups($groupId);
            if (is_array($getGroups) && count($getGroups) > 0) {
                return self::returnSuccessResponse($getGroups, $this->success);
            } else {
                // If any errors returned, return the customized error. 
                return self::returnErrorResponse($this->notFound, $this->app['GROUP_BYID_ERROR']);
            }
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['INVALID_GROUP_ID_ERROR']);
        }
    }

    /**
     * @Desc : Get roles information
     * @return JSON Return the list of roles info in JSON format.
     * @author Srinivasu M <srinivasu.m@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function rolesInformation() {
        $getRoles = $this->app['systemsettings.service']->getRoles();
        if (is_array($getRoles) && count($getRoles) > 0) {
            return self::returnSuccessResponse($getRoles, $this->success);
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['ROLE_LIST_ERROR']);
        }
    }

    /**
     * @Desc : Get roles information
     * @return JSON Return the list of roles info in JSON format.
     * @author Srinivasu M <srinivasu.m@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function rolesInformationById(Request $request) {
        $roleId = $request->get('id');
        if (is_numeric($roleId) && $roleId > 0) {
            $getRoles = $this->app['systemsettings.service']->getRoles($roleId);
            if (is_array($getRoles) && count($getRoles) > 0) {
                return self::returnSuccessResponse($getRoles, $this->success);
            } else {
                // If any errors returned, return the customized error. 
                return self::returnErrorResponse($this->notFound, $this->app['ROLE_LIST_BYID_ERROR']);
            }
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['INVALID_ROLE_ID_ERROR']);
        }
    }

    /**
     * @Desc : Get All status 
     * @return JSON Return the list of status info in JSON format.
     * @author Dhaarani S <dharani.s@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function getAllStatus() {
        $getStatus = $this->app['systemsettings.service']->getAllStatus();
        if (is_array($getStatus) && count($getStatus) > 0) {
            return self::returnSuccessResponse($getStatus, $this->success);
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['STATUS_ERROR']);
        }
    }

    /**
     * @Desc : Get All user type 
     * @return JSON Return the list of status info in JSON format.
     * @author Dhaarani S <dharani.s@impelsys.com>.
     * @copyright (c) Nov-2016, Impelsys India Pvt. Ltd.
     * 
     */
    public function getUserType() {

        $getUserType = $this->app['systemsettings.service']->getUserType();
        if (is_array($getUserType) && count($getUserType) > 0) {
            return self::returnSuccessResponse($getUserType, $this->success);
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['USERTYPE_LIST_ERROR']);
        }
    }

    /**
     * @desc - used to generate the Client Secret Key, this is just to get the random client secret key
     * @return string - Client secret key
     * 
     */
    public function generateVendorSecretKey() {
        $secretKey = md5(uniqid(microtime()));
        $returnArr = array('Notice' => "Currently not storing this information anywhere.", 'SecretKey' => $secretKey);
        return self::returnSuccessResponse($returnArr, $this->success);
    }

    /**
     * @desc - Get the System Configuration values
     * @return array -Array of system configuration values
     */
    public function getSystemconfiguration() {
        $getSystemconfig = $this->app['systemsettings.service']->getSystemconfiguration();
        if (is_array($getSystemconfig) && count($getSystemconfig) > 0) {
            return self::returnSuccessResponse($getSystemconfig, $this->success);
        } else {
            // If any errors returned, return the customized error. 
            return self::returnErrorResponse($this->notFound, $this->app['SYSTEM_SETTING_LIST_ERROR']);
        }
    }

    public function updateSystemconfiguration(Request $request) {

        $loggedInUserId = $request->get('userId');
        $updateSysSettings = json_decode($request->getContent(), true);
        $updateSysSettings['info'] = "Update request Data : ";
        $this->app['log']->writeLog($updateSysSettings);

        if ($loggedInUserId) {
            // Check user has permission to edit user Information.
            $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'update');

            if ($hasPermission) {

                $userEmail = ($updateSysSettings['emailDomain']) ? trim($updateSysSettings['emailDomain']) : NULL;
                if ($userEmail) {
                    // Validate email address, only if field having @ symbol
                    $emailDomainName = substr(strrchr($userEmail, "@"), 1);
                    $emailFullDomainName = 'www.' . $emailDomainName;
                    if (!checkdnsrr($emailDomainName, "ANY") || !checkdnsrr($emailFullDomainName, "ANY")) {
                        return self::returnErrorResponse($this->duplicate, $this->app['SYSTEM_SETTING_EMAIL_ERROR']);
                    }
                }

                $updateSysSettings['userId'] = $loggedInUserId;
                // If user exists then update the user information.
                $updated = $this->app['systemsettings.service']->update($updateSysSettings);

                if ($updated === true) {
                    // Return if successful update.
                    return self::returnSuccessResponse('', $this->nocontent);
                } else {

                    // Return following error if any error occurs while updating the user.
                    return self::returnErrorResponse($this->notFound, $this->app['SYSTEM_SETTING_UPDATION_ERROR']);
                }
            } else {
                // Return following error if user doesn't have permission to edit the user.
                return self::returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            }
        } else {
            // Return following error if user doesn't have permission to edit the user.
            return self::returnErrorResponse($this->badrequest, $this->app['INPUT_USER_ID_ERROR']);
        }
    }

}
