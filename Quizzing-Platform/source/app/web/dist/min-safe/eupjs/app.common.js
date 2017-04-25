/**
 * @namespace eupHttpInterceptor
 * @desc to handle http request, response and errors
 * @memberOf Services
 * @author Jagadeeshraj V S
 */
(function() {

    'use strict';

    angular.module('eupapp')
        .service('eupHttpInterceptor', ['$injector', '$q', '$rootScope', '$localStorage', function($injector, $q, $rootScope, $localStorage) {

            return {
                'request': function(config) {
                    var apiPattern = /\/api\//;
                    config.headers = config.headers || {};
                    config.params = config.params || {};
                    if (apiPattern.test(config.url)) {
                        config.headers.Authorization = $rootScope.token;
                        if (config.method == "GET")
                            config.params.clientUserId = $rootScope.userDetails.clientUserId;
                        else
                            config.data.clientUserId = $rootScope.userDetails.clientUserId;
                    }
                    return config;
                }
            };
        }]);
})();
