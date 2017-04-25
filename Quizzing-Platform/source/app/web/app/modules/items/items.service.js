/**
 * @namespace Items
 * @desc All the webservice calls for item module will go through this factory 
 * @memberOf Factories
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';

    angular.module('app.items')
        .factory('itemsService', function($rootScope, $log, $http, $q, config) {
            var obj = {};

            $rootScope.test = "test"
                //Get list of item types from server
            obj.getItemTypesList = function() {
                return $http.get(config.apiUrl + 'itemtypes')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            //Get list of remediation types from server
            obj.getRemediationTypesList = function() {
                return $http.get(config.apiUrl + 'remediationlinktypes')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            //Call insert item api to insert newly created item details
            obj.insertItem = function(itemDetail) {
                return $http.post(config.apiUrl + 'items', itemDetail)
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
            //call to api to update item
            obj.updateItem = function(itemDetail, id) {
                console.log(itemDetail);
                return $http.put(config.apiUrl + 'items/' + id, itemDetail)
                    .success(function(response) {
                        if (response.status == 204)
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
            //call to api to get item details depending on primary key id
            obj.getItemById = function(id, version) {
                var params = {};
                if (version)
                    params = { version: version };
                return $http.get(config.apiUrl + 'items/' + id, { params: params })
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
            //call to api to publish item depending on primary key id
            obj.publishItem = function(id) {
                return $http.get(config.apiUrl + 'publishitem/' + id)
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
            };
            //call to api to delete item based
            obj.deleteItem = function(id, params) {
                return $http.delete(config.apiUrl + 'items/' + id, { params: params })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            //call to api to get list of item details based on pagnination or search filters
            obj.getItemsDetails = function(params) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of item details
                return $http.get(config.apiUrl + 'items', { params: params })
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

            obj.associateItem = function(id, params) {

                return $http.put(config.apiUrl + 'associateitem/' + id, params)
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

            obj.getItemAssociatedDetails = function(params) {
                $log.debug(params);

                var deferred = $q.defer();

                //$http call to api endpoints to get list of item details
                return $http.get(config.apiUrl + 'itembanks', { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })

            };
            return obj;
        });
})();
