(function() {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.dashboard')
        .factory('loginService', function($rootScope, $state, $q, $window, $log, $http, $localStorage, jwtHelper) {
            var user = {};
            var sessionTimeout = 60 * 20 * 1000;

            //Logout user while closing broser tab directly
            //$window.onbeforeunload = logoutUser;

            function loginUser(userDetails) {
                $log.debug(userDetails);
                var deferred = $q.defer();

                $http.post("/api/login", userDetails)
                    .then(function(result) {

                        deferred.resolve(result);
                    }, function(error) {

                        deferred.resolve(error);
                    });

                return deferred.promise;
            }

            //Used to check whether the user logged in . If not takes the user to login page.
            function checkUserAuthentication(toState) {
                var publicPages = ['login', 'forgotpassword', 'resetpassword']; //List of public pages
                var isNotPublicPage = publicPages.indexOf(toState.name.split(".")[0]) === -1; //Avoid authentication check for public pages

                if ($localStorage.currentUser && angular.isDefined($localStorage.currentUser.token)) { //Check user details in localstorage
                    var expToken = $rootScope.token = $localStorage.currentUser.token;
                    $rootScope.userDetails = jwtHelper.decodeToken(expToken);
                    $rootScope.userId = $rootScope.userDetails.userId;
                    $log.debug($rootScope.userDetails);
                    $log.debug("check sessio out", isSessionTimeOut());
                    if (isSessionTimeOut()) //If seesion timeout
                        logoutUser();
                } else if (isNotPublicPage && !$localStorage.currentUser) { // if userdetails not exist in local storage
                    logoutUser(); //Clear locastorage data and redirect to login page
                }
            }

            function fetchValue(name) {
                var gCookieVal = document.cookie.split("; ");
                for (var i = 0; i < gCookieVal.length; i++) {
                    // a name/value pair (a crumb) is separated by an equal sign
                    var gCrumb = gCookieVal[i].split("=");
                    if (name === gCrumb[0]) {
                        var value = '';
                        try {
                            value = angular.fromJson(gCrumb[1]);
                        } catch (e) {
                            value = unescape(gCrumb[1]);
                        }
                        return value;
                    }
                }
                // a cookie with the requested name does not exist
                return null;
            }

            function setCookie(name, values) {

                $log.debug(values);
                if (arguments.length === 1)
                    return fetchValue(name);
                var cookie = name + '=';
                if (typeof values === 'object') {
                    var expires = '';
                    cookie += (typeof values.value === 'object') ? angular.toJson(values.value) + ';' : values.value + ';';

                } else {
                    cookie += values + ';';
                }
                document.cookie = cookie;
            }

            function logoutUser() {
                //Clear Local Storage
                $localStorage.$reset();
                //Clear rootScope Data
                delete $rootScope['menuList'];
                delete $rootScope['permission'];
                delete $rootScope['userDetails'];
                delete $rootScope['userId'];
                //Redirect to login page
                $state.go('login')
            }

            function isSessionTimeOut() {
                //code to check if user is idle for 20 min and logout them out on any action

                $log.debug("check function");
                $localStorage.now = new Date();
                var publicPages = ['login', 'forgotpassword', 'resetpassword'];

                if ($localStorage.now - $localStorage.lastDigestRun > sessionTimeout && ($rootScope.$state.current.name.split(".")[1] != 'login' && $rootScope.$state.current.name.split(".")[1] != 'forgotpassword' && $rootScope.$state.current.name.split(".")[1] != 'resetpassword')) {
                    // logout here,when idle time is more than 20 minutes

                    return true;

                } else {
                    $localStorage.lastDigestRun = new Date();
                    return false;
                }
                //end of idle check    


            }
            return {
                loginUser: loginUser,
                setCookie: setCookie,
                logoutUser: logoutUser,
                checkUserAuthentication: checkUserAuthentication,
                isSessionTimeOut: isSessionTimeOut

            };
        });
})();
