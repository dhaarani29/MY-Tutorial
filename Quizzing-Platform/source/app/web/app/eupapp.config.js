(function() {
    'use strict';

    angular.module('eupapp')
        .config(function($translateProvider, $locationProvider, $logProvider, $provide, $httpProvider) {

            //Removing the # and get in to HTML5 mode
            $locationProvider
                .html5Mode(true)

            /* Language Setting Starts */
            //Loads the language file json based on choosen locale    
            $translateProvider.useStaticFilesLoader({
                    prefix: './app/modules/eup/partials/locale-',
                    suffix: '.json'
                })
                //Set default language
                .preferredLanguage('en')
                .useSanitizeValueStrategy('escape');

            /* Language Setting Ends */
            //Push interceptor service for Http webservice calls
            $httpProvider.interceptors.push('eupHttpInterceptor');

            /* Loading Server Side Config Starts */
            var config;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', window.__env.apiUrl + "systemconfig", false);
            xhr.send();
            if (xhr.status == '200') {
                config = angular.extend(window.__env, JSON.parse(xhr.responseText)); //Merge server side config with client side environment variables
            }
            $provide.constant('config', config); //Set config constant

            //config.token = window.__token;

            /* Loading Server Side Config Ends */
            //Enable/Disable logs  
            $logProvider.debugEnabled(__env.enableDebug);

        })
        .run(function($rootScope, $state, $stateParams, $urlRouter, $window, $location, $localStorage, $http) {
            //Sharing the current state and its parameters with rootscope
            $rootScope.$state = $state;
            $rootScope.$stateParams = $stateParams;
            $rootScope.token = window.__token;

            //Checks and Verfications During Routing Start
            $rootScope.$on('$stateChangeStart', function(event, to, toParams, from, fromParams) {
                var toStateName = to.name.split(".")[0];
            });

            //Handling Errors during state change
            $rootScope.$on('$stateChangeError', function(event) {
                console.log(event)
                $state.go('eup.404');
            });
            //Set the previous and current state in rootScope
            $rootScope.$on('$stateChangeSuccess', function(ev, to, toParams, from, fromParams) {
                $rootScope.previousState = from.name;
                $rootScope.previousStateParams = fromParams;
                $rootScope.currentState = to.name;
            });
        })
})();
