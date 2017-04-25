(function() {
    'use strict';
    //Group Module Creation with its dependencies
    angular.module('app.systemsettings', [])
        .config(function($stateProvider, $urlRouterProvider) {

            $urlRouterProvider.otherwise('/systemsettings');
            //Routing definitions for user module
            $stateProvider
                .state('systemsettings', {
                    url: '/systemsettings',
                    abstract: true,
                    template: '<ui-view/>',



                })
                 .state('systemsettings.list', {
                    url: '/list',
                    templateUrl: 'app/modules/systemsettings/partials/systemsettings-listing.html',
                    controller: 'SystemsettingsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.SYSTEMSETTING_LIST_LABEL' | translate }}",
                        parent: "dashboard.main"

                    }

                })
//                
//                .state('group.view', {
//                    url: '/view/{id:int}',
//                    templateUrl: 'app/modules/group/partials/group-view.html',
//                    controller: 'GroupController',
//                    controllerAs: 'vm',
//                    ncyBreadcrumb: {
//                        label: "{{ 'PAGE_TITLE.GROUP_VIEW_LABEL' | translate }}",
//                        parent: "group.list"
//
//                    }
//
//                })
//                .state('group.create', {
//                    url: '/create',
//                    templateUrl: 'app/modules/group/partials/group-create.html',
//                    controller: 'GroupController',
//                    controllerAs: 'vm',
//                    ncyBreadcrumb: {
//                        label: "{{ 'PAGE_TITLE.USER_CREATE' | translate }}",
//                        parent: "group.list"
//
//                    }
//
//                })
//                .state('group.edit', {
//                    url: '/edit/{id:int}',
//                    templateUrl: 'app/modules/group/partials/group-create.html',
//                    controller: 'GroupController',
//                    controllerAs: 'vm',
//                    ncyBreadcrumb: {
//                        label: "{{ 'PAGE_TITLE.USER_EDIT' | translate }}",
//                        parent: "group.list"
//
//                    }
//
//                })
////                
//                 .state('group.delete', {
//                    url: '/delete/{id:int}',
//                    templateUrl: 'app/modules/group/partials/group-view.html',
//                    controller: 'GroupController',
//                    controllerAs: 'vm',
//                    ncyBreadcrumb: {
//                        label: "{{ 'PAGE_TITLE.USER_DELETE_LABEL' | translate }}",
//                        parent: "group.list"
//
//                    }
//
//                })
               
                
        });
})();