(function() {
    'use strict';
    //Item Module Creation with its dependencies
    angular.module('app.items', ['app.metadata', 'ngFileUpload'])
        .config(function($stateProvider) {

            //Add module wise translate part
            //$translatePartialLoaderProvider.addPart('items');

            //$translateProvider.useLoader('localeLoaderService', { module: 'items' });

            //Routing definitions for item module
            $stateProvider
                .state('items', {
                    url: '/item',
                    abstract: true,
                    template: '<ui-view/>',
                })
                .state('items.list', {
                    url: '/list',
                    templateUrl: 'app/modules/items/partials/items-list.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_MANAGEMENT' | translate }}",
                        parent: "dashboard.main"
                    },
                })
                .state('items.edit', {
                    url: '/edit/{id:int}/{parentId:int}/{parentItemType:string}',
                    params: {
                        parentId: { squash: true, value: 0 },
                        parentItemType: { squash: true, value: "" }
                    },
                    templateUrl: 'app/modules/items/partials/items-create.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_EDIT' | translate }}",
                        parent: "items.list"
                    }
                })
                .state('items.create', {
                    url: '/create/{parentId:int}/{parentItemType:string}/{flowType:string}',
                    params: {
                        parentId: { squash: true, value: 0 },
                        parentItemType: { squash: true, value: "" },
                        flowType: { squash: true, value: "create" },
                    },
                    templateUrl: 'app/modules/items/partials/items-create.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_CREATE' | translate }}",
                        parent: "items.list"
                    }
                })
                .state('items.view', {
                    url: '/view/{id:int}/{parentId:int}/{parentItemType:string}',
                    params: {
                        parentId: { squash: true, value: 0 },
                        parentItemType: { squash: true, value: "" }
                    },
                    templateUrl: 'app/modules/items/partials/items-view.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_VIEW' | translate }}",
                        parent: "items.list"
                    }
                })
                .state('items.delete', {
                    url: '/delete/{id:int}/{parentId:int}/{parentItemType:string}',
                    params: {
                        parentId: { squash: true, value: 0 },
                        parentItemType: { squash: true, value: "" }
                    },
                    templateUrl: 'app/modules/items/partials/items-view.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_DELETE' | translate }}",
                        parent: "items.list"
                    }
                })
                .state('items.preview', {
                    url: '/preview/{id:int}/{parentId:int}/{parentItemType:string}',
                    params: {
                        parentId: { squash: true, value: 0 },
                        parentItemType: { squash: true, value: "" }
                    },
                    template: function($stateParams) {
                        return '<item-preview item-id="$stateParams.id" item-details=""><button type="button" ng-click="goBack()" class="wk-button" translate="BACK"></button></item-preview>'
                    },
                    controller: function($rootScope, $scope, $state, $stateParams) {
                        $scope.goBack = function() {
                            if (angular.isDefined($rootScope.previousState) && $rootScope.previousState != "")
                                $state.go($rootScope.previousState, $rootScope.previousStateParams);
                            else
                                $state.go('items.list')
                        }
                    },
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_PREVIEW' | translate }}",
                        parent: function($rootScope) {
                            return ($rootScope.previousState || 'dashboard.main')
                        },
                    }
                })
                .state('items.publish', {
                    url: '/publish/{id:int}/{parentId:int}/{parentItemType:string}',
                    params: {
                        parentId: { squash: true, value: 0 },
                        parentItemType: { squash: true, value: "" }
                    },
                    templateUrl: 'app/modules/items/partials/items-view.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_PUBLISH' | translate }}",
                        parent: "items.list"
                    }
                })
                .state('itemtype', {
                    url: '/itemtype',
                    abstract: true,
                    template: '<ui-view/>',
                })
                .state('itemtype.list', {
                    url: '/list',
                    templateUrl: 'app/modules/items/partials/itemtypes-list.html',
                    controller: 'ItemTypesController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEMTYPES' | translate }}",
                        parent: "dashboard.main"
                    }
                })
                .state('itemtype.preview', {
                    url: '/preview/:id',
                    templateUrl: 'app/modules/items/partials/itemtypes-preview.html',
                    controller: 'ItemTypesController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEMTYPE_PREVIEW' | translate }}",
                        parent: "itemtype.list"
                    }
                })
                .state('items.association', {
                    url: '/association/{id:int}',
                    templateUrl: 'app/modules/items/partials/items-associate.html',
                    controller: 'ItemsController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.ITEM_ASSOCIATE' | translate }}",
                        parent: "items.list"
                    }
                })
        })
})();
