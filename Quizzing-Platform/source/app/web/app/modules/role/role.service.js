/**
 * Role Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace Role
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.role')
        .factory('roleService', function($rootScope, $q, $http, config, $log) {
            var obj = {};

            //call to api to fetch all groups
            obj.getRolesList = function(id) {
                return $http.get(config.apiUrl + 'roles/'+id)
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });

            };

            

            //call to api to get groupdata details depending on primary key id
            obj.getRoles = function(params) {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'roles' , { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
            };
            
            //call to api to get groupdata details depending on primary key id
            obj.deleteRole = function(id) {
                var deferred = $q.defer();
                return $http.delete(config.apiUrl + 'roles/'+ id)
                    .success(function(response) {
                        if (response.status === 204)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });
            };
            
            //call to api to get all usertypes
            obj.getuserTypeList = function() {
                return $http.get(config.apiUrl + 'usertype')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            obj.getStatus = function(id) {
                return $http.get(config.apiUrl + 'status')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            //Call to create new group
                obj.insertRole = function (roleDetail) {
                    return $http.post(config.apiUrl + 'roles', roleDetail)
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
                  //Call update api to update role
                obj.updateRole = function (roleDetail, id) {
                    return $http.put(config.apiUrl + 'roles/' + id, roleDetail)
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
          return obj;
        });
        
})();
        
