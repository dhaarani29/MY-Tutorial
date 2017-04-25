(function () {
    'use strict';
    //Role Module Creation with its dependencies
    angular.module('app.role', [])
            .config(function ($stateProvider, $urlRouterProvider) {

                $urlRouterProvider.otherwise('/role');
                //Routing definitions for role module
                $stateProvider
                        .state('role', {
                            url: '/role',
                            abstract: true,
                            template: '<ui-view/>',
                        })
                        .state('role.list', {
                            url: '/list',
                            templateUrl: 'app/modules/role/partials/role-listing.html',
                            controller: 'RoleController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ROLE_MANG_LABEL' | translate }}",
                                parent: "dashboard.main"

                            }

                        })

                        .state('role.view', {
                            url: '/view/{id:int}',
                            templateUrl: 'app/modules/role/partials/role-view.html',
                            controller: 'RoleController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ROLE_VIEW_LABEL' | translate }}",
                                parent: "role.list"

                            }

                        })

                        .state('role.delete', {
                            url: '/delete/{id:int}',
                            templateUrl: 'app/modules/role/partials/role-view.html',
                            controller: 'RoleController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ROLE_DELETE_LABEL' | translate }}",
                                parent: "role.list"

                            }

                        })
                        .state('role.create', {
                            url: '/create',
                            templateUrl: 'app/modules/role/partials/role-create.html',
                            controller: 'RoleController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ROLE_CREATE' | translate }}",
                                parent: "role.list"

                            }

                        })
                        .state('role.edit', {
                            url: '/edit/{id:int}',
                            templateUrl: 'app/modules/role/partials/role-create.html',
                            controller: 'RoleController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ROLE_EDIT' | translate }}",
                                parent: "role.list"

                            }

                        })


            });
})();
