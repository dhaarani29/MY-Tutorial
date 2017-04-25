/**
 * @namespace EUP
 * @desc All the webservice calls and services for eup will be handled in this factory 
 * @memberOf Factories
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';

    angular.module('eupapp')
        .factory('eupService', function($rootScope, $log, $http, $filter, $window, $q, jwtHelper, config) {
            var obj = {};

            //Capture current item user answer and time spent
            obj.saveAnswer = function(itemDetail, testInstanceId, attemptDetails) {
                return $http.post(config.apiUrl + 'tests/instance/' + testInstanceId + '/items/' + itemDetail.itemId + '/version/' + itemDetail.version, attemptDetails)
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

            //call to api to get basic test details depending on primary key id
            obj.getTestById = function(id, version) {
                return $http.get(config.apiUrl + 'tests/' + id, { params: { version: version } })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            //call to api to get item details based on test instance and item id
            obj.getTestInstanceItemById = function(itemDetail, testInstanceId) {
                var apiUrl = config.apiUrl + 'tests/instance/' + testInstanceId + '/items';

                if (itemDetail && itemDetail.itemId && itemDetail.version)
                    apiUrl = config.apiUrl + 'tests/instance/' + testInstanceId + '/items/' + itemDetail.itemId + '/version/' + itemDetail.version;


                return $http.get(apiUrl)
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            //decode jwt token and assign details to scope
            obj.decodeToken = function(token) {
                if (angular.isUndefined(token) || token.split('.').length != 3)
                    return false;

                $rootScope.userDetails = jwtHelper.decodeToken(token);
                console.log($rootScope.userDetails)
                if (angular.isDefined($rootScope.userDetails.clientUserId))
                    return true;
                else
                    return false;
            }

            //Convert seconds to respective type
            obj.convertTime = function(sec, type) {
                if (type == "min") {
                    return $filter('number')(sec / 60, 2) + "min";
                } else if (type == "time") {
                    if (angular.isDefined(sec) && angular.isNumber(sec)) {
                        var hours = $window.Math.floor(sec / 3600, 0);
                        var minutes = $window.Math.floor((sec - (hours * 3600)) / 60, 0);
                        var seconds = sec - (hours * 3600) - (minutes * 60);
                        if (hours < 10) { hours = "0" + hours; }
                        if (minutes < 10) { minutes = "0" + minutes; }
                        if (seconds < 10) { seconds = "0" + seconds; }

                        if (hours != "00")
                            return hours + ':' + minutes + ':' + seconds;
                        else
                            return minutes + ':' + seconds;
                    } else
                        return "00:00";
                }
            }

            //call to api to get test progress details
            obj.getTestProgress = function(testId, testInstanceId) {
                return $http.get(config.apiUrl + 'tests/' + testId + '/instances/' + testInstanceId, { params: { 'summary': true } })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            //call to api to get list of item details based on pagnination or search filters
            obj.getSummaryDetails = function(testId, testInstanceId, params) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of item details
                return $http.get(config.apiUrl + 'tests/' + testId + '/instances/' + testInstanceId + '/summary', { params: params })
                    .then(function(response) {
                        deferred.resolve(response);
                        return deferred.promise;
                    })
            };

            return obj;
        });
})();
