/**
 * Metadata Factory
 * @namespace Factories
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';
    /**
     * @namespace Metadata
     * @desc Metadata related business logic service
     * @memberOf Factories
     */
    angular.module('app.metadata')
        .factory('metadataService', function($rootScope, $q, $http, config, $log) {
            var obj = {};

            //call to api to fetch all metadata types()
            obj.getMetadataTypesList = function() {
                return $http.get(config.apiUrl + 'metadatatypes')
                    .success(function(data) {
                        console.log(data)
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };

            //call to api to fetch all metadata data types(string,numeric.datetime)
            obj.getMetadataDataTypesList = function() {
                    return $http.get(config.apiUrl + 'metadatadatatypes')
                        .success(function(data) {
                            console.log(data)
                            return data;

                        })
                        .error(function(response, status) {
                            $log.error(response, status) //Log custom errors
                        });
                }
                //call to api to insert metadata
            obj.insertMetadata = function(metaDataDetail) {
                return $http.post(config.apiUrl + 'metadata', metaDataDetail)
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
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });


            };

            //call to api to update metadata
            obj.updateMetadata = function(metaDataDetail, id) {
                console.log(metaDataDetail);
                return $http.put(config.apiUrl + 'metadata/' + id, metaDataDetail)
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
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });
            };
            //call to api to get metadata details depending on primary key id
            obj.getMetadataById = function(id, params) {

                return $http.get(config.apiUrl + 'metadata/' + id, { params: params })
                    .success(function(response) {
                        //response["success"] = true;
                        console.log(response)
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
            //call to api to delete metadata based on primary key id
            obj.deleteMetadata = function(id) {
                return $http.delete(config.apiUrl + 'metadata/' + id)
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
            //call to api to get list of metadata details based on pagnination or search filters
            obj.getMetadataDetails = function(params, tableState) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'metadata', { params: params })
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

            //api call to get all institutions
            obj.getInstitutions = function(params, tableState) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'institutions', { params: params })
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };

            //api call to get all institutions
            obj.getSnomedDetails = function(taxanomyIds) {
                return $http.get(config.apiUrl + 'snomed/' + taxanomyIds + '/QB')
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            obj.getMandatoryMetadata = function() {
                return $http.get(config.apiUrl + 'mandatorymetadata')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            return obj;
        });




})();
