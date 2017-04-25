(function() {
    'use strict';
    angular.module('app')
        .config(function($stateProvider, $urlRouterProvider) {

            //Handling URL Not Found Errors
            $urlRouterProvider.otherwise(function($injector, $location) {
                var state = $injector.get('$state');
                state.go('404');
                return $location.path();
            });

            //Routing for error handling pages    
            $stateProvider
                .state('home', {
                    url: '/',
                    templateUrl: 'app/modules/dashboard/partials/dashboard.html',
                    ncyBreadcrumb: {
                        label: "{{ 'DASHBOARD' | translate }}",
                    },
                    controller: 'DashboardController',
                    controllerAs: 'vm'
                })
                .state('404', {
                    // url: '/error',
                    templateUrl: 'app/common/errors/404.html',
                    ncyBreadcrumb: {
                        label: "Error",
                        parent: "dashboard.main"
                    }
                })
                .state('login', {
                    url: '/login',
                    templateUrl: 'app/modules/dashboard/partials/login.html',
                    controller: 'DashboardController',
                    controllerAs: 'vm'
                })
                .state('forgotpassword', {
                    url: '/forgotpassword',
                    templateUrl: 'app/modules/dashboard/partials/forgotpassword.html',
                    controller: 'DashboardController',
                    controllerAs: 'vm'
                })
                .state('resetpassword', {
                    url: '/resetpassword/{id}',
                    templateUrl: 'app/modules/dashboard/partials/resetpassword.html',
                    controller: 'DashboardController',
                    controllerAs: 'vm'
                })

            //Routing for module permission(Authorization) error page    
            $stateProvider
                .state('403', {
                    templateUrl: 'app/common/errors/403.html',
                    ncyBreadcrumb: {
                        label: "Error",
                        parent: "dashboard.main"

                    }
                });

            // User profile - Route
            $stateProvider
                .state('myprofile', {
                    url: '/myprofile',
                    templateUrl: 'app/modules/dashboard/partials/user-profile.html',
                    controller: 'DashboardController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "Myprofile",
                        parent: "dashboard.main"

                    }

                });
        })
})();
