(function() {
    'use strict';
    //Dashboard Module Creation with its dependencies
    angular.module('app.dashboard', ['app.user'])
        .config(function($stateProvider, $urlRouterProvider) {

            //$translateProvider.useLoader('localeLoaderService', { module: 'metadata'});        
            $urlRouterProvider.otherwise('/dashboard');

            //Routing definitions for metadata module
            $stateProvider
                .state('dashboard', {
                    url: '/dashboard',
                    abstract: true,
                    template: '<ui-view/>',

                })
                .state('dashboard.main', {
                    url: '',
                    templateUrl: 'app/modules/dashboard/partials/dashboard.html',
                    controller: 'DashboardController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'DASHBOARD' | translate }}",
                    }
                })

        })

})();
