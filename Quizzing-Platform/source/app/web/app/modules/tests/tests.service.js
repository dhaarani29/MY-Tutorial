(function () {
    'use strict';
    /**
     * @namespace User
     * @desc User related business logic service
     * @memberOf Factories
     */
    angular.module('app.tests')
            .factory('testsService', function ($rootScope, $q, $http, config, $log) {
                var obj = {};

                //Call insert quiz api to insert newly created quiz
                obj.insertQuiz = function (quizDetail) {
                    return $http.post(config.apiUrl + 'tests', quizDetail)
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
                //Call update api to update  test details
                obj.updateQuiz = function (quizDetail, id) {
                    return $http.put(config.apiUrl + 'tests/' + id, quizDetail)
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
                //Call get test by id api for test details
                obj.getQuizById = function (id, params) {
                    return $http.get(config.apiUrl + 'tests/' + id, {params: params})
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

                //call to api to delete test based on id
                obj.deleteQuiz = function (id, params) {
                    return $http.delete(config.apiUrl + 'tests/' + id, {params: params})
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };
                obj.getItemBankAssociation = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of itembank details
                    return $http.get(config.apiUrl + 'itembanklist', {params: params})
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


                //call to api to get list of all quizz details based on pagnination or search filters
                obj.getAllTests = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of test details
                    return $http.get(config.apiUrl + 'tests', {params: params})
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