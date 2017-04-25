(function() {
    'use strict';
    //Item Module Creation with its dependencies
    angular.module('app.tests', ['app.metadata'])
        .config(function($stateProvider, $urlRouterProvider) {
            //Routing definitions for user module
            $stateProvider
                .state('tests', {
                    url: '/test',
                    abstract: true,
                    template: '<ui-view/>',
                })
                .state('tests.list', {
                    url: '/list',
                    templateUrl: 'app/modules/tests/partials/tests-list.html',
                    controller: 'TestsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.TEST_MANG_LABEL' | translate }}",
                        parent: "dashboard.main"

                    }

                })
                .state('tests.create', {
                    url: '/create',
                    templateUrl: 'app/modules/tests/partials/tests-create.html',
                    controller: 'TestsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.TEST_CREATE_LABEL' | translate }}",
                        parent: "tests.list"

                    }

                })
                .state('tests.edit', {
                    url: '/edit/{id:int}',
                    templateUrl: 'app/modules/tests/partials/tests-create.html',
                    controller: 'TestsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.TEST_EDIT_LABEL' | translate }}",
                        parent: "tests.list"

                    }

                })
                .state('tests.view', {
                    url: '/view/{id:int}',
                    templateUrl: 'app/modules/tests/partials/tests-view.html',
                    controller: 'TestsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.TEST_VIEW_LABEL' | translate }}",
                        parent: "tests.list"

                    }

                })
                .state('tests.delete', {
                    url: '/delete/{id:int}',
                    templateUrl: 'app/modules/tests/partials/tests-view.html',
                    controller: 'TestsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.TEST_DELETE_LABEL' | translate }}",
                        parent: "tests.list"

                    }

                })

        });
})();
