(function () {
    'use strict';
    //Item Module Creation with its dependencies
    angular.module('app.itembanks', ['app.metadata'])
            .config(function ($stateProvider, $urlRouterProvider) {

                //Routing definitions for user module
                $stateProvider
                        .state('itembanks', {
                            url: '/itembank',
                            abstract: true,
                            template: '<ui-view/>',
                        })
                        .state('itembanks.list', {
                            url: '/list',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-list.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_MANG_LABEL' | translate }}",
                                parent: "dashboard.main"

                            }

                        })
                        .state('itembanks.create', {
                            url: '/create',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-create.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_CREATE_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }

                        })
                        .state('itembanks.edit', {
                            url: '/edit/{id:int}',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-create.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_CREATE_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }

                        })
                        .state('itembanks.view', {
                            url: '/view/{id:int}',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-view.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_VIEW_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }

                        })
                        .state('itembanks.delete', {
                            url: '/delete/{id:int}',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-view.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_DELETE_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }

                        })
                        .state('itembanks.publish', {
                            url: '/publish/{id:int}',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-view.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_VIEW_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }

                        })
                        .state('itembanks.upload', {
                            url: '/upload',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-upload.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_UPLOAD_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }
                        })
                        .state('itembanks.status', {
                            url: '/status/{id:int}',
                            templateUrl: 'app/modules/itembanks/partials/itembanks-status.html',
                            controller: 'ItemcollectionsController',
                            controllerAs: 'vm',
                            ncyBreadcrumb: {
                                label: "{{ 'PAGE_TITLE.ITEMCOLLECTION_UPLOAD_LABEL' | translate }}",
                                parent: "itembanks.list"

                            }

                        })



            });
})();
