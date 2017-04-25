/**
 * User Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function () {
    'use strict';
    /**
     * @namespace User
     * @desc User related business logic service
     * @memberOf Factories
     */
    angular.module('app.itembanks')
            .factory('itembanksService', function ($rootScope, $q, $http, config, $log) {
                var obj = {};
                //call to api to get list of item details based on pagnination or search filters
                obj.getItemsDetails = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'items', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };

                //Call insert item api to insert newly created item details
                obj.insertItemCollection = function (itemDetail) {
                    return $http.post(config.apiUrl + 'itembanks', itemDetail)
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


                //Call insert item api to insert newly created item details
                obj.updateItemCollection = function (itemDetail, id) {
                    return $http.put(config.apiUrl + 'itembanks/' + id, itemDetail)
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
                //Call get itembankss by id api for itembankss details
                obj.getItemCollectionDetailById = function (id) {
                    return $http.get(config.apiUrl + 'itembanks/' + id)
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug("Data not found", response)
                                return response;
                            });

                };
                //Call get itembankss  api for getting all itembankss 
                obj.getItemCollectionDetails = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'itembanks', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })

                };


                //call to api to delete item based
                obj.deleteItemCollection = function (id) {
                    return $http.delete(config.apiUrl + 'itembanks/' + id)
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };
                obj.getItemAssociation = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'itemlist', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };
                obj.ParseItems = function (parseData) {
                    return $http.post(config.apiUrl + 'parseitems', parseData)
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
                obj.importItemCollection = function (importData) {
                    console.log(importData)
                    return $http.post(config.apiUrl + 'itembanksimport', importData)
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
                //call to api to publish item depending on primary key id
                obj.publishItemCollection = function (id, params) {
                    return $http.get(config.apiUrl + 'publishitemcollection/' + id, {params: params})
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                };

                //api call to get upload detgails of itemcollection
                obj.getItemStatusInImport = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'importstatus/' + params.id, {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };

                return obj;
            });

})();
