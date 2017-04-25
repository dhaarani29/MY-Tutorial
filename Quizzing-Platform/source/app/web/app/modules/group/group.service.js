/**
 * Group Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function () {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.group')
            .factory('groupService', function ($rootScope, $q, $http, config, $log) {
                var obj = {};

                //call to api to fetch all groups
                obj.getGroupsList = function () {
                    return $http.get(config.apiUrl + 'grouplist')
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });

                };

                //call api to fetch Specific group data based on input id
                obj.getGroupsById = function (id) {
                    var deferred = $q.defer();
                    return $http.get(config.apiUrl + 'groups/' + id)
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug(response)
                                return response;
                            });

                };

                //call api to fetch Specific group data based on input id
                obj.searchGroups = function (id, params) {
                    var deferred = $q.defer();
                    return $http.get(config.apiUrl + 'groups/' + id, {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug(response)
                                return response;
                            });

                };

                //call to api to get groupdata details depending on primary key id
                obj.getGroups = function (params) {
                    var deferred = $q.defer();
                    return $http.get(config.apiUrl + 'groups', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                };

                //call to api to get all usertypes
                obj.getuserTypeList = function () {
                    return $http.get(config.apiUrl + 'usertype')
                            .success(function (response) {
                                if (response.status === 200)
                                    return true;
                                else {
                                    $log.error(response, status) //Log custom errors
                                    return false;
                                }
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };

                obj.getStatus = function (id) {
                    return $http.get(config.apiUrl + 'status')
                            .success(function (response) {
                                if (response.status === 200)
                                    return true;
                                else {
                                    $log.error(response, status) //Log custom errors
                                    return false;
                                }
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };


                //call to api to delete metadata based on primary key id
                obj.deleteGroup = function (id) {
                    return $http.delete(config.apiUrl + 'groups/' + id)
                            .success(function (response) {
                                if (response.status === 204)
                                    return true;
                                else {
                                    $log.error(response, status) //Log custom errors
                                    return false;
                                }
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug(response)
                                return response;
                            });
                };

                //Call to create new group
                obj.insertGroup = function (groupDetail) { 
                    return $http.post(config.apiUrl + 'groups', groupDetail)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };


                //Call update api to update group
                obj.updateGroup = function (groupDetail, id) {
                    return $http.put(config.apiUrl + 'groups/' + id, groupDetail)
                            .success(function (response) {
                                if (response.status == 204)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };

                //call to api to fetch all users
                obj.getUsersList = function (params) {
                    return $http.get(config.apiUrl + 'user', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });

                };

                //call to api to fetch all users
                obj.getRolesList = function (params) {
                    return $http.get(config.apiUrl + 'roles', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });

                };

                return obj;
            });

})();

