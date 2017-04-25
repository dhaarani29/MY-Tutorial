'use strict';

angular.module('app')
    .config(['$translateProvider', '$locationProvider', '$logProvider', '$provide', '$httpProvider', function($translateProvider, $locationProvider, $logProvider, $provide, $httpProvider) {

        //Removing the # and get in to HTML5 mode
        $locationProvider
            .html5Mode(true)

        /* Language Setting Starts */
        //Loads the language file json based on choosen locale    
        $translateProvider
            .useStaticFilesLoader({
                prefix: './app/common/locale/locale-',
                suffix: '.json'
            })
            //Set default language
            .preferredLanguage('en')
            //Store last choosen language in cookie      
            // .useCookieStorage()
            .useSanitizeValueStrategy('escape');
        //Determine prefered language from browser settings
        //.determinePreferredLanguage();
        /* Language Setting Ends */

        //Push interceptor service for Http webservice calls
        $httpProvider.interceptors.push('httpInterceptor');


        /* Loading Server Side Config Starts */
        var config;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', window.__env.apiUrl + "systemconfig", false);
        xhr.send();
        if (xhr.status == '200') {
            config = angular.extend(window.__env, JSON.parse(xhr.responseText)); //Merge server side config with client side environment variables
        }
        $provide.constant('config', config); //Set config constant
        /* Loading Server Side Config Ends */
        //Enable/Disable logs  
        $logProvider.debugEnabled(__env.enableDebug);

    }])
    .run(['$rootScope', '$state', '$stateParams', '$urlRouter', '$window', '$location', '$localStorage', 'permissionService', '$http', 'loginService', function($rootScope, $state, $stateParams, $urlRouter, $window, $location, $localStorage, permissionService, $http, loginService) {
        //Sharing the current state and its parameters with rootscope
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;

        //Checks and Verfications During Routing Start
        $rootScope.$on('$stateChangeStart', function(event, to, toParams, from, fromParams, userInfo) {
            var toStateName = to.name.split(".")[0];

            if (toStateName == 'resetpassword' || toStateName == 'forgotpassword' || toStateName == 'login') {
                $rootScope.hideMenu = false;
            } else {
                $rootScope.hideMenu = true;
                //Check for user authentication
                loginService.checkUserAuthentication(to);

                //Check for module/action permission in the state change
                permissionService.checkModulePermission(event, to, toParams);
            }
        });

        //Handling Errors during state change
        $rootScope.$on('$stateChangeError', function(event) {
            console.log(event)
            $state.go('404');
        });
        //Set the previous and current state in rootScope
        $rootScope.$on('$stateChangeSuccess', function(ev, to, toParams, from, fromParams) {
            $rootScope.previousState = from.name;
            $rootScope.previousStateParams = fromParams;
            $rootScope.currentState = to.name;

        });
    }])
