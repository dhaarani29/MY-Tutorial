/**
 * User Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace User
     * @desc User related business logic service
     * @memberOf Factories
     */
    angular.module('app.user')
        .factory('userService', function($rootScope, $q, $http, config, $log) {
            var obj = {};

            //call to api to fetch all countries
            obj.getcountryList = function() {
                return $http.get(config.apiUrl + 'countrylist')
                    .success(function(data) {
                       
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
            //call to api to fetch all states based on selected country
            obj.getstateList = function(selectedCountryId) {
                return $http.get(config.apiUrl + 'stateslist/'+selectedCountryId)
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
           //call to api to fetch all roles
            obj.getRolesList = function() {
       
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'roles', { params: { noLimit: 1 } })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
            };

            //call to api to fetch all roles NEW API
            obj.getRoleDetails = function(params, tableState) {
                return $http.get(config.apiUrl + 'roles', { params: params })
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
            
            //call to api to fetch all roles NEW API
            obj.getGroupDetails = function(params, tableState) {
                return $http.get(config.apiUrl + 'groups', { params: params })
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };


            //call to api to fetch all groups
            obj.getGroupsList = function() {
               
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'groups', { params: { noLimit: 1 } })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })

            };

            //call to api to insert user
            obj.insertUser = function(userDataDetail) {
                return $http.post(config.apiUrl + 'user', userDataDetail)
                    
                    .success(function(response) {
                        $log.debug(response);
                        if (response.status == 201)
                            return true;
                        else
                            return false;
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
            
            
                //call to api to update user
            obj.updateUser = function(userDetail, id) {
               
                return $http.put(config.apiUrl + 'user/' + id, userDetail)
                    .success(function(response) {
                        if (response.status == 204)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
//                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });                    
                                        


            };
            
             //call to api to get user details depending on primary key id
            obj.getUserById = function(id) {
                return $http.get(config.apiUrl + 'user/' + id)
                    .success(function(response) {
                        //response["success"] = true;
                        
                        return response;
                    })
                    .error(function(response, status) {
                        //response["success"] = false;
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });


            };
             //call to api to delete user based on primary key id
            obj.deleteUser = function(id) {
                return $http.delete(config.apiUrl + 'user/' + id)
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
                    })
            };
            
            
             //call to api to get list of user details based on pagnination or search filters
            obj.getuserDetails = function(params, tableState) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'user', { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });

                    
            };
              //call to api to delete metadata based on primary key id
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
            
            //Get the user associations details
            obj.getuserAssociatedDetails = function(params)
            {
                    var deferred = $q.defer();
                    if(params.selectedButton == '1')
                    {
                        var apiPath = 'roles';
                    }
                    else if(params.selectedButton == '2')
                    {
                         var apiPath = 'groups';
                    }
                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + apiPath, {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
            };
            
              //call to api to asssociate role/group for user 
            obj.associateRoleOrGroup = function(id , params) {
                return $http.put(config.apiUrl + 'associateuser/' + id , params)
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
             //call to api to get user details onclicking myprofile in header
            obj.getUserByIdMyProfile = function(id) {
                return $http.get(config.apiUrl + 'user/' + id, { params: {  myprofile: 1} })
                    .success(function(response) {
                        //response["success"] = true;
                        
                        return response;
                    })
                    .error(function(response, status) {
                        //response["success"] = false;
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });


            };
           return obj;
        });
})();
        
