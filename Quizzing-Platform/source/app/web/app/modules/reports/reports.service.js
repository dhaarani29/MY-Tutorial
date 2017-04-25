/**
 * Role Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function () {
    'use strict';
    /**
     * @namespace Role
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.reports')
            .factory('reportsService', function ($rootScope, $q, $http, config, $log) {
                var obj = {};

                //call to api to get all usage report data
                obj.getUsageData = function (params) {
                    return $http.get(config.apiUrl + 'reports/studentusagereport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };

                //call to api to get all usage report data
                obj.getClientReportData = function (params) {
                    return $http.get(config.apiUrl + 'reports/clientreport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };


                //call to api to get all metadata report data
                obj.getMetadataReport = function (params) {
                    return $http.get(config.apiUrl + 'reports/metadatareport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };

                //call to api to get all usage report data
                obj.getUserQuizzingReportData = function (params) {
                    return $http.get(config.apiUrl + 'reports/userquizzingreport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };


                //call to api to get all usage report data
                obj.getItemWrongData = function (params) {
                    return $http.get(config.apiUrl + 'reports/itemreport', {params: params})
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

