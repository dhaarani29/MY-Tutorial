(function() {
    'use strict';
    angular.module('eupapp')
        .config(function($stateProvider, $urlRouterProvider) {

            //Handling URL Not Found Errors
            $urlRouterProvider.otherwise(function($injector, $location) {
                var state = $injector.get('$state');
                state.go('eup.404');
                return $location.path();
            });

            //Routing for error handling pages    
            $stateProvider
                .state('eup', {
                    url: '/enduser',
                    abstract: true,
                    template: '<ui-view/>',
                })
                .state('eup.engine', {
                    url: '/engine/{testId:int}/{testVersion:int}/{testInstanceId:int}',
                    templateUrl: 'app/modules/eup/partials/test-engine.html',
                    controller: 'TestEngineController',
                    controllerAs: 'vm'
                })
                .state('eup.summary', {
                    url: '/summary/{testId:int}/{testInstanceId:int}',
                    templateUrl: 'app/modules/eup/partials/test-summary.html',
                    controller: 'TestSummaryController',
                    controllerAs: 'vm'
                })
                .state('eup.404', {
                    url: '/error/404',
                    templateUrl: 'app/common/errors/euperror.html',
                    controller: function($scope) {
                        $scope.error = {};
                        $scope.error.number = 404;
                        $scope.error.text = "ERRORS.404";
                    }
                })
                .state('eup.401', {
                    url: '/error/401',
                    templateUrl: 'app/common/errors/euperror.html',
                    controller: function($scope) {
                        $scope.error = {};
                        $scope.error.number = 401;
                        $scope.error.text = "ERRORS.401";
                    }
                })
                .state('eup.invalidtest', {
                    url: '/error/invalidtest',
                    templateUrl: 'app/common/errors/euperror.html',
                    controller: function($scope) {
                        $scope.error = {};
                        $scope.error.text = "ERRORS.invalidtest";
                    }
                })
        })
})();
