'use strict';

angular.module('app')
    .factory('localeLoaderService', function($state, $http, $q,$rootElement) {
        // return loaderFn
        return function(options) {
            var deferred = $q.defer();
            var data = {};
console.log($rootElement.attr('ng-app'))            
           // console.log(options)
            $http.get('/app/common/locale/en.json').success(function(commonFile) {
                angular.extend(data, commonFile);

                $http.get('/app/modules/' + options.module + '/partials/locale-' + options.key + '.json').success(function(moduleB) {
                    angular.extend(data, moduleB);
                    deferred.resolve(data);
                });
            });
            return deferred.promise;
        }
    });
