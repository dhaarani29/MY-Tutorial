/**
 * @namespace LOGIN
 * @desc to set and get cookies
 * @memberOf Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';

    angular.module('app.dashboard')
        .factory('rememberMeService', function($rootScope, $log, $http, $q, config) {
            
  
         function fetchValue(name) {
                var gCookieVal = document.cookie.split("; ");
                for (var i=0; i < gCookieVal.length; i++)
                {
                    // a name/value pair (a crumb) is separated by an equal sign
                    var gCrumb = gCookieVal[i].split("=");
                    if (name === gCrumb[0])
                    {
                        var value = '';
                        try {
                            value = angular.fromJson(gCrumb[1]);
                        } catch(e) {
                            value = unescape(gCrumb[1]);
                        }
                        return value;
                    }
                }
                // a cookie with the requested name does not exist
                return null;
            }
            return function(name, values) {
                
                $log.debug(values);
                if(arguments.length === 1) return fetchValue(name);
                var cookie = name + '=';
                if(typeof values === 'object') {
                    var expires = '';
                    cookie += (typeof values.value === 'object') ? angular.toJson(values.value) + ';' : values.value + ';';
                   
                } else {
                    cookie += values + ';';
                }
                document.cookie = cookie;
            }
        });
})();