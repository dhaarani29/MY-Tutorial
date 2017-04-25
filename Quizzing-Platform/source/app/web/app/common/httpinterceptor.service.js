/**
 * @namespace LOGIN
 * @desc to set and get cookies
 * @memberOf Factories
 * @author Srilakshmi R
 */

'use strict';

angular.module('app')
    .service('httpInterceptor', ['$injector', '$q', '$rootScope', '$localStorage', function($injector, $q, $rootScope, $localStorage) {

        return {
            'request': function(config) {
                var apiPattern = /\/api\//;
                config.headers = config.headers || {};
                config.params = config.params || {};
                if (angular.fromJson($localStorage.currentUser) && apiPattern.test(config.url)) {
                    config.headers.Authorization = $localStorage.currentUser.token;
                    config.headers.requestFrom = "admin";
                    config.params.userId = $localStorage.currentUser.userId;
                }
                return config;
            },
            'responseError': function(rejection, config) {
                var hideUrl = 'login';
                
                if (rejection.status === 401 && ($rootScope.$state.current.name != 'login' && $rootScope.$state.current.name != 'forgotpassword' && $rootScope.$state.current.name != 'resetpassword')) {
                    //loginService.logoutUser();
                    
                    var loginService = $injector.get('loginService');
                    loginService.logoutUser();

                }

                return $q.reject(rejection);
            }
        };
    }]);
