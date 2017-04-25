(function() {
    'use strict';
    //User Module Creation with its dependencies
    angular.module('app.user', [])
        .config(function($stateProvider, $urlRouterProvider) {

            $urlRouterProvider.otherwise('/user');
            //Routing definitions for user module
            $stateProvider
                .state('user', {
                    url: '/user',
                    abstract: true,
                    template: '<ui-view/>',



                })
                .state('user.list', {
                    url: '/list',
                    templateUrl: 'app/modules/users/partials/user-listing.html',
                    controller: 'UserController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.USER_MANG_LABEL' | translate }}",
                        parent: "dashboard.main"

                    }

                })
                .state('user.create', {
                    url: '/create',
                    templateUrl: 'app/modules/users/partials/user-create.html',
                    controller: 'UserController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.USER_CREATE' | translate }}",
                        parent: "user.list"

                    }

                })
                .state('user.edit', {
                    url: '/edit/{id:int}',
                    templateUrl: 'app/modules/users/partials/user-create.html',
                    controller: 'UserController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.USER_EDIT' | translate }}",
                        parent: "user.list"

                    }

                })

            .state('user.view', {
                    url: '/view/{id:int}',
                    templateUrl: 'app/modules/users/partials/user-view.html',
                    controller: 'UserController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.USER_VIEW_LABEL' | translate }}",
                        parent: "user.list"

                    }

                })
                .state('user.delete', {
                    url: '/delete/{id:int}',
                    templateUrl: 'app/modules/users/partials/user-view.html',
                    controller: 'UserController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.USER_DELETE_LABEL' | translate }}",
                        parent: "user.list"

                    }

                })

            .state('user.association', {
                url: '/association/{id:int}',
                templateUrl: 'app/modules/users/partials/user-association.html',
                controller: 'UserController',
                controllerAs: 'vm',
                ncyBreadcrumb: {
                    label: "{{ 'PAGE_TITLE.USER_ASSOCIATION_TITLE' | translate }}",
                    parent: "user.list"
                }
            })


        });
})();
