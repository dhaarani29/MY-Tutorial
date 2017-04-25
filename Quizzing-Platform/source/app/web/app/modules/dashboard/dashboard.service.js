/**
 * Group Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.dashboard')
        .factory('dashboardService', function($rootScope, $q, $http, config, $log,$localStorage) {
            var obj = {};

            //call to api to fetch all groups
            obj.getDashboardDetails = function() {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'dashboard')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            obj.forgotPassword = function(userDetail) {
                 return $http.post(config.apiUrl + 'forgotpassword', userDetail)
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
            }
            obj.validateResetPassword = function(id) {
                
                    return $http.post(config.apiUrl + 'validateresetpassword', id)
                    .success(function(response) {
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
                        $log.debug(response)
                        return response;
                    });

            };
            obj.resetPassword = function(resetDetails) {
                
                    return $http.post(config.apiUrl + 'resetpassword', resetDetails)
                    .success(function(response) {
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
                        $log.debug(response)
                        return response;
                    });

            };
            
            
            
          return obj;
        });
        
})();