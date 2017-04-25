(function () {
    'use strict';
    //Group Module Creation with its dependencies
    angular.module('app.group', [])
            .config(function ($stateProvider, $urlRouterProvider) {

                $urlRouterProvider.otherwise('/group');
                //Routing definitions for user module
                $stateProvider
                        .state('group', {
                            url: '/group',
                            abstract: true,
                            template: '<ui-view/>',
                        })
                        .state('group.list', {
                            url: '/list',
                            templateUrl: 'app/modules/group/partials/group-listing.html',
                            controller: 'GroupController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.GROUP_MANG_LABEL' | translate }}",
                                parent: "dashboard.main"

                            }

                        })

                        .state('group.view', {
                            url: '/view/{id:int}',
                            templateUrl: 'app/modules/group/partials/group-view.html',
                            controller: 'GroupController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.GROUP_VIEW_LABEL' | translate }}",
                                parent: "group.list"

                            }

                        })
                        .state('group.create', {
                            url: '/create',
                            templateUrl: 'app/modules/group/partials/group-create.html',
                            controller: 'GroupController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.GROUP_CREATE' | translate }}",
                                parent: "group.list"

                            }

                        })
                        .state('group.edit', {
                            url: '/edit/{id:int}',
                            templateUrl: 'app/modules/group/partials/group-create.html',
                            controller: 'GroupController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.GROUP_EDIT' | translate }}",
                                parent: "group.list"

                            }

                        })

                        .state('group.delete', {
                            url: '/delete/{id:int}',
                            templateUrl: 'app/modules/group/partials/group-view.html',
                            controller: 'GroupController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.GROUP_DELETE_LABEL' | translate }}",
                                parent: "group.list"

                            }

                        })


            });
})();
