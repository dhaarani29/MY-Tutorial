(function() {
    'use strict';
    //Role Module Creation with its dependencies
    angular.module('app.reports', [])
        .config(function($stateProvider, $urlRouterProvider) {

            $urlRouterProvider.otherwise('/dashboard');
            //Routing definitions for role module
            $stateProvider
                .state('reports', {
                    url: '/report',
                    abstract: true,
                    template: '<ui-view/>',
                })
                .state('reports.studentusage', {
                    url: '/studentusage',
                    templateUrl: 'app/modules/reports/partials/studentusage.html',
                    controller: 'ReportsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.STUDENT_USAGE_LABEL' | translate }}",
                        parent: "dashboard.main"
                    }
                })
                
                .state('reports.clientreport', {
                    url: '/clientreport',
                    templateUrl: 'app/modules/reports/partials/clientreport.html',
                    controller: 'ReportsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.CLIENT_REPORT_LABEL' | translate }}",
                        parent: "dashboard.main"
                    }
                })
                
                .state('reports.metadatareport', {
                    url: '/metadatareport',
                    templateUrl: 'app/modules/reports/partials/metadatareport.html',
                    controller: 'ReportsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.METADATA_REPORT_LABEL' | translate }}",
                        parent: "dashboard.main"
                    }
                })
                
                .state('reports.userquizzingreport', {
                    url: '/userquizzingreport',
                    templateUrl: 'app/modules/reports/partials/userquizzingreport.html',
                    controller: 'ReportsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.USER_QUIZZING_LABEL' | translate }}",
                        parent: "dashboard.main"
                    }
                })
                
                .state('reports.itemreport', {
                    url: '/itemreport',
                    templateUrl: 'app/modules/reports/partials/itemreport.html',
                    controller: 'ReportsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_REPORT_LABEL' | translate }}",
                        parent: "dashboard.main"
                    }
                })
        });
})();
