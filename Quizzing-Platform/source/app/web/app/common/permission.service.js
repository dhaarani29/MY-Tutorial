'use strict';
/**
 * @namespace Permission
 * @desc Check for user permissions on different modules
 * @memberOf Factories
 * @author Jagadeeshraj V S
 */
angular.module('app')
    .factory('permissionService', function($rootScope, $q, $http, $localStorage, $log, $urlRouter, $state, config) {
        var obj = {};

        obj.checkModulePermission = function(stateEvent, stateDetail, stateParams) {
                //These states/modules will be exempted from permission check

                var exceptionStates = ["404", "403", "dashboard", "itemtype", "home", "myprofile", "login", "forgotpassword", "resetpassword"];


                //Check for states/modules for permission exemption
                if (exceptionStates.indexOf(stateDetail.name.split(".")[0]) === -1) {
                    $log.debug("Module Permission check begins")
                    var actionList = { preview: "view", view: "view", publish: "manageSecurity", list: "view", create: "create", edit: "edit", delete: "delete", security: "manage_security", association: "manageAssociation",studentusage:"view", clientreport:"view", metadatareport:"view", userquizzingreport:"view", itemreport:"view", upload:"create", status:"manageAssociation"} //action name mapping with db data
                    //var moduleList = { metadata: "meta_tag","items":"question" } //module name mapping with db data
                    var moduleName = stateDetail.name.split(".")[0];
                    var actionName = stateDetail.name.split(".")[1];
                    $log.debug("Module:" + moduleName + " & Action:" + actionName)

                    //Check Permission Details in rootScope
                    if ($rootScope.permission && $rootScope.permission[moduleName]) {
                        $log.debug("Module Permission present in rootScope")
                        if ($rootScope.permission[moduleName].indexOf(actionList[actionName]) === -1) { //Check for particular action access
                            permissionError(stateEvent, moduleName, actionName)
                        } else {
                            $log.debug("Module Permission Success")
                            return; //User has permission for current routing state and action
                        }

                    } else if ($localStorage.permission && $localStorage.permission[moduleName]) { //Check Permission Details in Local Storage
                        $log.debug("Module Permission present in rootScope")
                        $rootScope.permission = $rootScope.permission || {}
                        $rootScope.permission[moduleName] = $localStorage.permission[moduleName] //Set the permission in rootScope
                        if ($rootScope.permission[moduleName].indexOf(actionList[actionName]) === -1) { //Check for particular action access
                            permissionError(stateEvent, moduleName, actionName)
                        } else {
                            $log.debug("Module Permission Success")
                            return; //User has permission for current routing state and action
                        }
                    } else { //Get from server
                        $log.debug("Module Permission not in rootscope/localStorage, so getting from server")
                        stateEvent.preventDefault(); //Prevent routing to check for permission
                        getPermissionFromServer(moduleName).then(function(response) {
                            var accessPermission = response.data;
                            if (response.status == 401) { //Authentication error
                                $state.go('login')
                            } else if (angular.isArray(accessPermission)) {
                                $rootScope.permission = $rootScope.permission || {}
                                $localStorage.permission = $localStorage.permission || {}
                                $rootScope.permission[moduleName] = $localStorage.permission[moduleName] = accessPermission //Set the permission in rootScope

                                if (accessPermission.indexOf(actionList[actionName]) === -1) {
                                    permissionError(stateEvent, moduleName, actionName)
                                } else {
                                    $log.debug("Re-route")
                                        //$urlRouter.sync(); //Reload/reprocess current route 
                                    $state.go(stateDetail.name, stateParams)
                                }
                            } else
                                permissionError(stateEvent, moduleName, actionName)
                        });

                    } //end of get permission details from server
                } //end if for state excemption check
            }
            //Get permission details from server based on module & userid
        function getPermissionFromServer(moduleName) {
            return $http.get(config.apiUrl + 'getpermissions', { params: { userId: $rootScope.userId, resource: moduleName } })
                .success(function(response) {
                    console.log(response)
                    return response;
                })
                .error(function(response, status) {
                    console.log(response)

                    $log.error(response, status) //Log custom errors
                    return response;
                })
                .catch(function(response) {
                    $log.debug("Permission Error ", response) //Log custom errors
                    return response;
                });
        }

        //Handle permission errors 
        function permissionError(stateEvent, moduleName, actionName) {
            stateEvent.preventDefault(); //Prevent current routing 
            $log.info("No Access Permission for " + "Module:" + moduleName + " & Action:" + actionName) //log error info
            $state.go('403') //re-route to error page
        }

        return obj;
    });
