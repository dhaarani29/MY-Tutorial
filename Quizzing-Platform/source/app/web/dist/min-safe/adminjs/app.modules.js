(function() {
    'use strict';
    //Dashboard Module Creation with its dependencies
    angular.module('app.dashboard', ['app.user'])
        .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

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

        }])

})();

(function () {
    'use strict';
    //Group Module Creation with its dependencies
    angular.module('app.group', [])
            .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {

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


            }]);
})();

(function () {
    'use strict';
    //Item Module Creation with its dependencies
    angular.module('app.itembanks', ['app.metadata'])
            .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {

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



            }]);
})();

(function() {
    'use strict';
    //Item Module Creation with its dependencies
    angular.module('app.items', ['app.metadata', 'ngFileUpload'])
        .config(['$stateProvider', function($stateProvider) {

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
                    controller: ['$rootScope', '$scope', '$state', '$stateParams', function($rootScope, $scope, $state, $stateParams) {
                        $scope.goBack = function() {
                            if (angular.isDefined($rootScope.previousState) && $rootScope.previousState != "")
                                $state.go($rootScope.previousState, $rootScope.previousStateParams);
                            else
                                $state.go('items.list')
                        }
                    }],
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
        }])
})();

(function() {
    'use strict';
    //Metadata Module Creation with its dependencies
    angular.module('app.metadata', [])
        .config(['$stateProvider', function($stateProvider) {

            //Routing definitions for metadata module
            $stateProvider
                .state('metadata', {
                    url: '/metadata',
                    abstract: true,
                    template: '<ui-view/>',
                })
                .state('metadata.list', {
                    url: '/list',
                    templateUrl: 'app/modules/metadata/partials/metadata-listing.html',
                    controller: 'MetadataController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.METADATA_LIST' | translate }}",
                        parent: "dashboard.main"
                    },
                })
                .state('metadata.edit', {
                    url: '/edit/{id:int}',
                    templateUrl: 'app/modules/metadata/partials/metadata-create.html',
                    controller: 'MetadataController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.METADATA_EDIT' | translate }}",
                        parent: "metadata.list"
                    }
                })
                .state('metadata.create', {
                    url: '/create',
                    templateUrl: 'app/modules/metadata/partials/metadata-create.html',
                    controller: 'MetadataController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.METADATA_CREATE' | translate }}",
                        parent: "metadata.list"
                    }
                })
                .state('metadata.view', {
                    url: '/view/{id:int}',
                    templateUrl: 'app/modules/metadata/partials/metadata-view.html',
                    controller: 'MetadataController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.METADATA_VIEW' | translate }}",
                        parent: "metadata.list"
                    }
                })
                .state('metadata.delete', {
                    url: '/delete/{id:int}',
                    templateUrl: 'app/modules/metadata/partials/metadata-view.html',
                    controller: 'MetadataController',
                    controllerAs: 'vm',
                    ncyBreadcrumb: {
                        label: "{{ 'PAGE_TITLE.METADATA_DELETE' | translate }}",
                        parent: "metadata.list"
                    }
                });
        }])
})();

(function() {
    'use strict';
    //Role Module Creation with its dependencies
    angular.module('app.reports', [])
        .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

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
        }]);
})();

(function () {
    'use strict';
    //Role Module Creation with its dependencies
    angular.module('app.role', [])
            .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {

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


            }]);
})();

(function() {
    'use strict';
    //Group Module Creation with its dependencies
    angular.module('app.systemsettings', [])
        .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

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
               
                
        }]);
})();
(function() {
    'use strict';
    //Item Module Creation with its dependencies
    angular.module('app.tests', ['app.metadata'])
        .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
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

        }]);
})();

(function() {
    'use strict';
    //User Module Creation with its dependencies
    angular.module('app.user', [])
        .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

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


        }]);
})();

/**
 * Group Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.dashboard')
        .factory('dashboardService', ['$rootScope', '$q', '$http', 'config', '$log', '$localStorage', function($rootScope, $q, $http, config, $log,$localStorage) {
            var obj = {};

            //call to api to fetch all groups
            obj.getDashboardDetails = function() {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'dashboard')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            obj.forgotPassword = function(userDetail) {
                 return $http.post(config.apiUrl + 'forgotpassword', userDetail)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });
            }
            obj.validateResetPassword = function(id) {
                
                    return $http.post(config.apiUrl + 'validateresetpassword', id)
                    .success(function(response) {
                        if (response.status == 201)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug(response)
                        return response;
                    });

            };
            obj.resetPassword = function(resetDetails) {
                
                    return $http.post(config.apiUrl + 'resetpassword', resetDetails)
                    .success(function(response) {
                        if (response.status == 201)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug(response)
                        return response;
                    });

            };
            
            
            
          return obj;
        }]);
        
})();
(function() {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.dashboard')
        .factory('loginService', ['$rootScope', '$state', '$q', '$window', '$log', '$http', '$localStorage', 'jwtHelper', function($rootScope, $state, $q, $window, $log, $http, $localStorage, jwtHelper) {
            var user = {};
            var sessionTimeout = 60 * 20 * 1000;

            //Logout user while closing broser tab directly
            //$window.onbeforeunload = logoutUser;

            function loginUser(userDetails) {
                $log.debug(userDetails);
                var deferred = $q.defer();

                $http.post("/api/login", userDetails)
                    .then(function(result) {

                        deferred.resolve(result);
                    }, function(error) {

                        deferred.resolve(error);
                    });

                return deferred.promise;
            }

            //Used to check whether the user logged in . If not takes the user to login page.
            function checkUserAuthentication(toState) {
                var publicPages = ['login', 'forgotpassword', 'resetpassword']; //List of public pages
                var isNotPublicPage = publicPages.indexOf(toState.name.split(".")[0]) === -1; //Avoid authentication check for public pages

                if ($localStorage.currentUser && angular.isDefined($localStorage.currentUser.token)) { //Check user details in localstorage
                    var expToken = $rootScope.token = $localStorage.currentUser.token;
                    $rootScope.userDetails = jwtHelper.decodeToken(expToken);
                    $rootScope.userId = $rootScope.userDetails.userId;
                    $log.debug($rootScope.userDetails);
                    $log.debug("check sessio out", isSessionTimeOut());
                    if (isSessionTimeOut()) //If seesion timeout
                        logoutUser();
                } else if (isNotPublicPage && !$localStorage.currentUser) { // if userdetails not exist in local storage
                    logoutUser(); //Clear locastorage data and redirect to login page
                }
            }

            function fetchValue(name) {
                var gCookieVal = document.cookie.split("; ");
                for (var i = 0; i < gCookieVal.length; i++) {
                    // a name/value pair (a crumb) is separated by an equal sign
                    var gCrumb = gCookieVal[i].split("=");
                    if (name === gCrumb[0]) {
                        var value = '';
                        try {
                            value = angular.fromJson(gCrumb[1]);
                        } catch (e) {
                            value = unescape(gCrumb[1]);
                        }
                        return value;
                    }
                }
                // a cookie with the requested name does not exist
                return null;
            }

            function setCookie(name, values) {

                $log.debug(values);
                if (arguments.length === 1)
                    return fetchValue(name);
                var cookie = name + '=';
                if (typeof values === 'object') {
                    var expires = '';
                    cookie += (typeof values.value === 'object') ? angular.toJson(values.value) + ';' : values.value + ';';

                } else {
                    cookie += values + ';';
                }
                document.cookie = cookie;
            }

            function logoutUser() {
                //Clear Local Storage
                $localStorage.$reset();
                //Clear rootScope Data
                delete $rootScope['menuList'];
                delete $rootScope['permission'];
                delete $rootScope['userDetails'];
                delete $rootScope['userId'];
                //Redirect to login page
                $state.go('login')
            }

            function isSessionTimeOut() {
                //code to check if user is idle for 20 min and logout them out on any action

                $log.debug("check function");
                $localStorage.now = new Date();
                var publicPages = ['login', 'forgotpassword', 'resetpassword'];

                if ($localStorage.now - $localStorage.lastDigestRun > sessionTimeout && ($rootScope.$state.current.name.split(".")[1] != 'login' && $rootScope.$state.current.name.split(".")[1] != 'forgotpassword' && $rootScope.$state.current.name.split(".")[1] != 'resetpassword')) {
                    // logout here,when idle time is more than 20 minutes

                    return true;

                } else {
                    $localStorage.lastDigestRun = new Date();
                    return false;
                }
                //end of idle check    


            }
            return {
                loginUser: loginUser,
                setCookie: setCookie,
                logoutUser: logoutUser,
                checkUserAuthentication: checkUserAuthentication,
                isSessionTimeOut: isSessionTimeOut

            };
        }]);
})();

/**
 * @namespace LOGIN
 * @desc to set and get cookies
 * @memberOf Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';

    angular.module('app.dashboard')
        .factory('rememberMeService', ['$rootScope', '$log', '$http', '$q', 'config', function($rootScope, $log, $http, $q, config) {
            
  
         function fetchValue(name) {
                var gCookieVal = document.cookie.split("; ");
                for (var i=0; i < gCookieVal.length; i++)
                {
                    // a name/value pair (a crumb) is separated by an equal sign
                    var gCrumb = gCookieVal[i].split("=");
                    if (name === gCrumb[0])
                    {
                        var value = '';
                        try {
                            value = angular.fromJson(gCrumb[1]);
                        } catch(e) {
                            value = unescape(gCrumb[1]);
                        }
                        return value;
                    }
                }
                // a cookie with the requested name does not exist
                return null;
            }
            return function(name, values) {
                
                $log.debug(values);
                if(arguments.length === 1) return fetchValue(name);
                var cookie = name + '=';
                if(typeof values === 'object') {
                    var expires = '';
                    cookie += (typeof values.value === 'object') ? angular.toJson(values.value) + ';' : values.value + ';';
                   
                } else {
                    cookie += values + ';';
                }
                document.cookie = cookie;
            }
        }]);
})();
/**
 * Group Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function () {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.group')
            .factory('groupService', ['$rootScope', '$q', '$http', 'config', '$log', function ($rootScope, $q, $http, config, $log) {
                var obj = {};

                //call to api to fetch all groups
                obj.getGroupsList = function () {
                    return $http.get(config.apiUrl + 'grouplist')
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });

                };

                //call api to fetch Specific group data based on input id
                obj.getGroupsById = function (id) {
                    var deferred = $q.defer();
                    return $http.get(config.apiUrl + 'groups/' + id)
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug(response)
                                return response;
                            });

                };

                //call api to fetch Specific group data based on input id
                obj.searchGroups = function (id, params) {
                    var deferred = $q.defer();
                    return $http.get(config.apiUrl + 'groups/' + id, {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug(response)
                                return response;
                            });

                };

                //call to api to get groupdata details depending on primary key id
                obj.getGroups = function (params) {
                    var deferred = $q.defer();
                    return $http.get(config.apiUrl + 'groups', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                };

                //call to api to get all usertypes
                obj.getuserTypeList = function () {
                    return $http.get(config.apiUrl + 'usertype')
                            .success(function (response) {
                                if (response.status === 200)
                                    return true;
                                else {
                                    $log.error(response, status) //Log custom errors
                                    return false;
                                }
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };

                obj.getStatus = function (id) {
                    return $http.get(config.apiUrl + 'status')
                            .success(function (response) {
                                if (response.status === 200)
                                    return true;
                                else {
                                    $log.error(response, status) //Log custom errors
                                    return false;
                                }
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };


                //call to api to delete metadata based on primary key id
                obj.deleteGroup = function (id) {
                    return $http.delete(config.apiUrl + 'groups/' + id)
                            .success(function (response) {
                                if (response.status === 204)
                                    return true;
                                else {
                                    $log.error(response, status) //Log custom errors
                                    return false;
                                }
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug(response)
                                return response;
                            });
                };

                //Call to create new group
                obj.insertGroup = function (groupDetail) { 
                    return $http.post(config.apiUrl + 'groups', groupDetail)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };


                //Call update api to update group
                obj.updateGroup = function (groupDetail, id) {
                    return $http.put(config.apiUrl + 'groups/' + id, groupDetail)
                            .success(function (response) {
                                if (response.status == 204)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };

                //call to api to fetch all users
                obj.getUsersList = function (params) {
                    return $http.get(config.apiUrl + 'user', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });

                };

                //call to api to fetch all users
                obj.getRolesList = function (params) {
                    return $http.get(config.apiUrl + 'roles', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });

                };

                return obj;
            }]);

})();


/**
 * User Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function () {
    'use strict';
    /**
     * @namespace User
     * @desc User related business logic service
     * @memberOf Factories
     */
    angular.module('app.itembanks')
            .factory('itembanksService', ['$rootScope', '$q', '$http', 'config', '$log', function ($rootScope, $q, $http, config, $log) {
                var obj = {};
                //call to api to get list of item details based on pagnination or search filters
                obj.getItemsDetails = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'items', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };

                //Call insert item api to insert newly created item details
                obj.insertItemCollection = function (itemDetail) {
                    return $http.post(config.apiUrl + 'itembanks', itemDetail)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };


                //Call insert item api to insert newly created item details
                obj.updateItemCollection = function (itemDetail, id) {
                    return $http.put(config.apiUrl + 'itembanks/' + id, itemDetail)
                            .success(function (response) {
                                if (response.status == 204)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };
                //Call get itembankss by id api for itembankss details
                obj.getItemCollectionDetailById = function (id) {
                    return $http.get(config.apiUrl + 'itembanks/' + id)
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug("Data not found", response)
                                return response;
                            });

                };
                //Call get itembankss  api for getting all itembankss 
                obj.getItemCollectionDetails = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'itembanks', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })

                };


                //call to api to delete item based
                obj.deleteItemCollection = function (id) {
                    return $http.delete(config.apiUrl + 'itembanks/' + id)
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };
                obj.getItemAssociation = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'itemlist', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };
                obj.ParseItems = function (parseData) {
                    return $http.post(config.apiUrl + 'parseitems', parseData)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });

                }
                obj.importItemCollection = function (importData) {
                    console.log(importData)
                    return $http.post(config.apiUrl + 'itembanksimport', importData)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });
                }
                //call to api to publish item depending on primary key id
                obj.publishItemCollection = function (id, params) {
                    return $http.get(config.apiUrl + 'publishitemcollection/' + id, {params: params})
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                };

                //api call to get upload detgails of itemcollection
                obj.getItemStatusInImport = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + 'importstatus/' + params.id, {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };

                return obj;
            }]);

})();

/**
 * @namespace Items
 * @desc All the webservice calls for item module will go through this factory 
 * @memberOf Factories
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';

    angular.module('app.items')
        .factory('itemsService', ['$rootScope', '$log', '$http', '$q', 'config', function($rootScope, $log, $http, $q, config) {
            var obj = {};

            $rootScope.test = "test"
                //Get list of item types from server
            obj.getItemTypesList = function() {
                return $http.get(config.apiUrl + 'itemtypes')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            //Get list of remediation types from server
            obj.getRemediationTypesList = function() {
                return $http.get(config.apiUrl + 'remediationlinktypes')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            //Call insert item api to insert newly created item details
            obj.insertItem = function(itemDetail) {
                return $http.post(config.apiUrl + 'items', itemDetail)
                    .success(function(response) {
                        if (response.status == 201)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug(response)
                        return response;
                    });


            };
            //call to api to update item
            obj.updateItem = function(itemDetail, id) {
                console.log(itemDetail);
                return $http.put(config.apiUrl + 'items/' + id, itemDetail)
                    .success(function(response) {
                        if (response.status == 204)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug(response)
                        return response;
                    });
            };
            //call to api to get item details depending on primary key id
            obj.getItemById = function(id, version) {
                var params = {};
                if (version)
                    params = { version: version };
                return $http.get(config.apiUrl + 'items/' + id, { params: params })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });

            };
            //call to api to publish item depending on primary key id
            obj.publishItem = function(id) {
                return $http.get(config.apiUrl + 'publishitem/' + id)
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
            };
            //call to api to delete item based
            obj.deleteItem = function(id, params) {
                return $http.delete(config.apiUrl + 'items/' + id, { params: params })
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            //call to api to get list of item details based on pagnination or search filters
            obj.getItemsDetails = function(params) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of item details
                return $http.get(config.apiUrl + 'items', { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
            };

            obj.associateItem = function(id, params) {

                return $http.put(config.apiUrl + 'associateitem/' + id, params)
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });


            };

            obj.getItemAssociatedDetails = function(params) {
                $log.debug(params);

                var deferred = $q.defer();

                //$http call to api endpoints to get list of item details
                return $http.get(config.apiUrl + 'itembanks', { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })

            };
            return obj;
        }]);
})();

/**
 * Metadata Factory
 * @namespace Factories
 * @author Jagadeeshraj V S
 */
(function() {
    'use strict';
    /**
     * @namespace Metadata
     * @desc Metadata related business logic service
     * @memberOf Factories
     */
    angular.module('app.metadata')
        .factory('metadataService', ['$rootScope', '$q', '$http', 'config', '$log', function($rootScope, $q, $http, config, $log) {
            var obj = {};

            //call to api to fetch all metadata types()
            obj.getMetadataTypesList = function() {
                return $http.get(config.apiUrl + 'metadatatypes')
                    .success(function(data) {
                        console.log(data)
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };

            //call to api to fetch all metadata data types(string,numeric.datetime)
            obj.getMetadataDataTypesList = function() {
                    return $http.get(config.apiUrl + 'metadatadatatypes')
                        .success(function(data) {
                            console.log(data)
                            return data;

                        })
                        .error(function(response, status) {
                            $log.error(response, status) //Log custom errors
                        });
                }
                //call to api to insert metadata
            obj.insertMetadata = function(metaDataDetail) {
                return $http.post(config.apiUrl + 'metadata', metaDataDetail)
                    .success(function(response) {
                        if (response.status == 201)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });


            };

            //call to api to update metadata
            obj.updateMetadata = function(metaDataDetail, id) {
                console.log(metaDataDetail);
                return $http.put(config.apiUrl + 'metadata/' + id, metaDataDetail)
                    .success(function(response) {
                        if (response.status == 204)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });
            };
            //call to api to get metadata details depending on primary key id
            obj.getMetadataById = function(id, params) {

                return $http.get(config.apiUrl + 'metadata/' + id, { params: params })
                    .success(function(response) {
                        //response["success"] = true;
                        console.log(response)
                        return response;
                    })
                    .error(function(response, status) {
                        //response["success"] = false;
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });

            };
            //call to api to delete metadata based on primary key id
            obj.deleteMetadata = function(id) {
                return $http.delete(config.apiUrl + 'metadata/' + id)
                    .success(function(response) {
                        //response["success"] = true;

                        return response;
                    })
                    .error(function(response, status) {
                        //response["success"] = false;
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });
            };
            //call to api to get list of metadata details based on pagnination or search filters
            obj.getMetadataDetails = function(params, tableState) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'metadata', { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
            };

            //api call to get all institutions
            obj.getInstitutions = function(params, tableState) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'institutions', { params: params })
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };

            //api call to get all institutions
            obj.getSnomedDetails = function(taxanomyIds) {
                return $http.get(config.apiUrl + 'snomed/' + taxanomyIds + '/QB')
                    .success(function(response) {
                        return response;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        $log.debug("Data not found", response)
                        return response;
                    });
            };

            obj.getMandatoryMetadata = function() {
                return $http.get(config.apiUrl + 'mandatorymetadata')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });
            };
            return obj;
        }]);




})();

/**
 * Role Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function () {
    'use strict';
    /**
     * @namespace Role
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.reports')
            .factory('reportsService', ['$rootScope', '$q', '$http', 'config', '$log', function ($rootScope, $q, $http, config, $log) {
                var obj = {};

                //call to api to get all usage report data
                obj.getUsageData = function (params) {
                    return $http.get(config.apiUrl + 'reports/studentusagereport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };

                //call to api to get all usage report data
                obj.getClientReportData = function (params) {
                    return $http.get(config.apiUrl + 'reports/clientreport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };


                //call to api to get all metadata report data
                obj.getMetadataReport = function (params) {
                    return $http.get(config.apiUrl + 'reports/metadatareport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };

                //call to api to get all usage report data
                obj.getUserQuizzingReportData = function (params) {
                    return $http.get(config.apiUrl + 'reports/userquizzingreport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };


                //call to api to get all usage report data
                obj.getItemWrongData = function (params) {
                    return $http.get(config.apiUrl + 'reports/itemreport', {params: params})
                            .success(function (data) {
                                return data;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            });
                };


                return obj;
            }]);

})();


/**
 * Role Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace Role
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.role')
        .factory('roleService', ['$rootScope', '$q', '$http', 'config', '$log', function($rootScope, $q, $http, config, $log) {
            var obj = {};

            //call to api to fetch all groups
            obj.getRolesList = function(id) {
                return $http.get(config.apiUrl + 'roles/'+id)
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });

            };

            

            //call to api to get groupdata details depending on primary key id
            obj.getRoles = function(params) {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'roles' , { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
            };
            
            //call to api to get groupdata details depending on primary key id
            obj.deleteRole = function(id) {
                var deferred = $q.defer();
                return $http.delete(config.apiUrl + 'roles/'+ id)
                    .success(function(response) {
                        if (response.status === 204)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });
            };
            
            //call to api to get all usertypes
            obj.getuserTypeList = function() {
                return $http.get(config.apiUrl + 'usertype')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            obj.getStatus = function(id) {
                return $http.get(config.apiUrl + 'status')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            //Call to create new group
                obj.insertRole = function (roleDetail) {
                    return $http.post(config.apiUrl + 'roles', roleDetail)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };
                  //Call update api to update role
                obj.updateRole = function (roleDetail, id) {
                    return $http.put(config.apiUrl + 'roles/' + id, roleDetail)
                            .success(function (response) {
                                if (response.status == 204)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };
          return obj;
        }]);
        
})();
        

/**
 * Group Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace Group
     * @desc Groups related business logic service
     * @memberOf Factories
     */
    angular.module('app.systemsettings')
        .factory('systemsettingsService', ['$rootScope', '$q', '$http', 'config', '$log', function($rootScope, $q, $http, config, $log) {
            var obj = {};


            //call to api to get all usertypes
            obj.getuserTypeList = function() {
                return $http.get(config.apiUrl + 'usertype')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            obj.getSystemSettings = function(params) {
                var deferred = $q.defer();
                /*
                return $http.get(config.apiUrl + 'systemconfiguration', { params: params })
                .success(function(data) {
                    return data;
                })
                .error(function(response, status) {
                    $log.error(response, status) //Log custom errors
                });
                */
               return $http.get(config.apiUrl + 'systemconfiguration' , { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
            };
            
            obj.updatesystemSetting = function(systemSettingsParams)
            {
               
                return $http.put(config.apiUrl + 'systemconfiguration', systemSettingsParams)
                    .success(function(response) {
                        if (response.status == 204)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
//                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });                    
                                        


            };

            /*
            //call to api to fetch all groups
            obj.getGroupsList = function() {
                return $http.get(config.apiUrl + 'grouplist')
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };

            //call api to fetch Specific group data based on input id
            obj.getGroupsById = function(id) {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'groups/'+ id)
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
            
            //call api to fetch Specific group data based on input id
            obj.searchGroups = function(id, params) {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'groups/'+ id, { params: params })
                    .success(function(data) {
                        return data;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };

            //call to api to get groupdata details depending on primary key id
            obj.getGroups = function(params) {
                var deferred = $q.defer();
                return $http.get(config.apiUrl + 'groups' , { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
            };
            
            //call to api to get all usertypes
            obj.getuserTypeList = function() {
                return $http.get(config.apiUrl + 'usertype')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            obj.getStatus = function(id) {
                return $http.get(config.apiUrl + 'status')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
             
            //call to api to delete metadata based on primary key id
            obj.deleteGroup = function(id) {
                return $http.delete(config.apiUrl + 'groups/' + id)
                    .success(function(response) {
                        if (response.status === 204)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });
            };
            */
            
          return obj;
        }]);
        
})();
        

(function () {
    'use strict';
    /**
     * @namespace User
     * @desc User related business logic service
     * @memberOf Factories
     */
    angular.module('app.tests')
            .factory('testsService', ['$rootScope', '$q', '$http', 'config', '$log', function ($rootScope, $q, $http, config, $log) {
                var obj = {};

                //Call insert quiz api to insert newly created quiz
                obj.insertQuiz = function (quizDetail) {
                    return $http.post(config.apiUrl + 'tests', quizDetail)
                            .success(function (response) {
                                if (response.status == 201)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };
                //Call update api to update  test details
                obj.updateQuiz = function (quizDetail, id) {
                    return $http.put(config.apiUrl + 'tests/' + id, quizDetail)
                            .success(function (response) {
                                if (response.status == 204)
                                    return true;
                                else
                                    return false;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                $log.debug(response)
                                return response;
                            });


                };
                //Call get test by id api for test details
                obj.getQuizById = function (id, params) {
                    return $http.get(config.apiUrl + 'tests/' + id, {params: params})
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                                return response;
                            })
                            .catch(function (response) {
                                //response.data["success"] = false;
                                $log.debug("Data not found", response)
                                return response;
                            });

                };

                //call to api to delete test based on id
                obj.deleteQuiz = function (id, params) {
                    return $http.delete(config.apiUrl + 'tests/' + id, {params: params})
                            .success(function (response) {
                                return response;
                            })
                            .error(function (response, status) {
                                $log.error(response, status) //Log custom errors
                            })
                };
                obj.getItemBankAssociation = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of itembank details
                    return $http.get(config.apiUrl + 'itembanklist', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };


                //call to api to get list of all quizz details based on pagnination or search filters
                obj.getAllTests = function (params, tableState) {
                    var deferred = $q.defer();

                    //$http call to api endpoints to get list of test details
                    return $http.get(config.apiUrl + 'tests', {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });
                };
                return obj;
            }]);
})();
/**
 * User Factory
 * @namespace Factories
 * @author Srilakshmi R
 */
(function() {
    'use strict';
    /**
     * @namespace User
     * @desc User related business logic service
     * @memberOf Factories
     */
    angular.module('app.user')
        .factory('userService', ['$rootScope', '$q', '$http', 'config', '$log', function($rootScope, $q, $http, config, $log) {
            var obj = {};

            //call to api to fetch all countries
            obj.getcountryList = function() {
                return $http.get(config.apiUrl + 'countrylist')
                    .success(function(data) {
                       
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
            //call to api to fetch all states based on selected country
            obj.getstateList = function(selectedCountryId) {
                return $http.get(config.apiUrl + 'stateslist/'+selectedCountryId)
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
           //call to api to fetch all roles
            obj.getRolesList = function() {
       
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'roles', { params: { noLimit: 1 } })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
            };

            //call to api to fetch all roles NEW API
            obj.getRoleDetails = function(params, tableState) {
                return $http.get(config.apiUrl + 'roles', { params: params })
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };
            
            //call to api to fetch all roles NEW API
            obj.getGroupDetails = function(params, tableState) {
                return $http.get(config.apiUrl + 'groups', { params: params })
                    .success(function(data) {
                        return data;

                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    });

            };


            //call to api to fetch all groups
            obj.getGroupsList = function() {
               
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'groups', { params: { noLimit: 1 } })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })

            };

            //call to api to insert user
            obj.insertUser = function(userDataDetail) {
                return $http.post(config.apiUrl + 'user', userDataDetail)
                    
                    .success(function(response) {
                        $log.debug(response);
                        if (response.status == 201)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });                    
                    

            };
            
            
                //call to api to update user
            obj.updateUser = function(userDetail, id) {
               
                return $http.put(config.apiUrl + 'user/' + id, userDetail)
                    .success(function(response) {
                        if (response.status == 204)
                            return true;
                        else
                            return false;
                    })
                    .error(function(response, status) {
//                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug(response)
                        return response;
                    });                    
                                        


            };
            
             //call to api to get user details depending on primary key id
            obj.getUserById = function(id) {
                return $http.get(config.apiUrl + 'user/' + id)
                    .success(function(response) {
                        //response["success"] = true;
                        
                        return response;
                    })
                    .error(function(response, status) {
                        //response["success"] = false;
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });


            };
             //call to api to delete user based on primary key id
            obj.deleteUser = function(id) {
                return $http.delete(config.apiUrl + 'user/' + id)
                    .success(function(response) {
                        if (response.status === 204)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            
             //call to api to get list of user details based on pagnination or search filters
            obj.getuserDetails = function(params, tableState) {
                var deferred = $q.defer();

                //$http call to api endpoints to get list of metadata details
                return $http.get(config.apiUrl + 'user', { params: params })
                    .then(function(response) {
                        deferred.resolve({
                            results: response.data,
                        });
                        return deferred.promise;
                    })
                    // .error(function(response, status) {
                    //     $log.error(response, status) //Log custom errors
                    // });

                    
            };
              //call to api to delete metadata based on primary key id
            obj.getStatus = function(id) {
                return $http.get(config.apiUrl + 'status')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            
            //call to api to get all usertypes
            obj.getuserTypeList = function() {
                return $http.get(config.apiUrl + 'usertype')
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
            
            //Get the user associations details
            obj.getuserAssociatedDetails = function(params)
            {
                    var deferred = $q.defer();
                    if(params.selectedButton == '1')
                    {
                        var apiPath = 'roles';
                    }
                    else if(params.selectedButton == '2')
                    {
                         var apiPath = 'groups';
                    }
                    //$http call to api endpoints to get list of item details
                    return $http.get(config.apiUrl + apiPath, {params: params})
                            .then(function (response) {
                                deferred.resolve({
                                    results: response.data,
                                });
                                return deferred.promise;
                            })
            };
            
              //call to api to asssociate role/group for user 
            obj.associateRoleOrGroup = function(id , params) {
                return $http.put(config.apiUrl + 'associateuser/' + id , params)
                    .success(function(response) {
                        if (response.status === 200)
                            return true;
                        else {
                            $log.error(response, status) //Log custom errors
                            return false;
                        }
                    })
                    .error(function(response, status) {
                        $log.error(response, status) //Log custom errors
                    })
            };
             //call to api to get user details onclicking myprofile in header
            obj.getUserByIdMyProfile = function(id) {
                return $http.get(config.apiUrl + 'user/' + id, { params: {  myprofile: 1} })
                    .success(function(response) {
                        //response["success"] = true;
                        
                        return response;
                    })
                    .error(function(response, status) {
                        //response["success"] = false;
                        $log.error(response, status) //Log custom errors
                        return response;
                    })
                    .catch(function(response) {
                        //response.data["success"] = false;
                        $log.debug("Data not found", response)
                        return response;
                    });


            };
           return obj;
        }]);
})();
        

(function() {
    'use strict';


    angular.module('app.dashboard').controller('DashboardController', ['$rootScope', '$window', '$scope', 'config', '$http', '$localStorage', '$log', 'dashboardService', 'userService', 'loginService', function($rootScope, $window, $scope, config, $http, $localStorage, $log, dashboardService, userService, loginService) {

        var vm = this;
        vm.id = $rootScope.$stateParams.id;

        vm.user = {};
        vm.userCookie = {};
        vm.validator = {};
        vm.passwordregex = '^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$';
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        //vm.emailRegex = '^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$';
        vm.emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        vm.phoneregex = /^(?=.*[0-9])[- +()0-9]+$/;
        vm.moduleType = $rootScope.$state.current.name.split(".")[0];

        vm.sendSuccess = 0;
        vm.pageError = false;
        vm.showLoader = true;
        vm.alertConfig = { 'show': false };
        vm.actionType = $rootScope.$state.current.name.split(".")[0];
        vm.invalidusererror = false;
        vm.showdropdown = function() {

            vm.showdropdown = 1;

        };
        var userParam = {};
        //Redirect to dashboarad if the user is already logged in 
        if (vm.actionType == 'login' && angular.isDefined($localStorage.currentUser)) {
            $rootScope.$state.go('dashboard.main')
        }
        if (vm.actionType == 'dashboard' || vm.actionType == 'home') {

            dashboardService.getDashboardDetails().then(function(response) {

                vm.dashboardDetails = response.data;
            });
        }

        if (vm.actionType == 'myprofile') {
            vm.pageTitle = 'PAGE_TITLE.MYPROFILE';

            if ($localStorage.countryList) {
                vm.countryList = $localStorage.countryList;
                //select default country as 
                vm.user.selectedOptionCountry = vm.countryList[0];
                var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                userService.getstateList(selectedCountryId).then(function(response) {
                    vm.stateList = response.data;
                    vm.user.selectedOptionState = '';
                });

            } else {
                //call user service to get list of country    
                userService.getcountryList().then(function(response) {
                    $localStorage.countryList = vm.countryList = response.data;
                    //select default country as 
                    vm.user.selectedOptionCountry = vm.countryList[0];
                    var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                    //call user service to get list of states    
                    userService.getstateList(selectedCountryId).then(function(response) {
                        vm.stateList = response.data;
                        vm.user.selectedOptionState = '';
                    });
                });
            }

            vm.getStates = function() {
                var selectedCountryId = vm.user.selectedOptionCountry.countryId;

                userService.getstateList(selectedCountryId).then(function(response) {
                    vm.stateList = response.data;
                    vm.user.selectedOptionState = '';
                });

            }

            vm.copyemail = function() {

                if (vm.user_name_email == true) {
                    vm.user.userName = vm.user.userEmail;
                } else if (vm.user_name_email == false) {
                    vm.user.userName = '';
                }

            }
            vm.unchecksame = function() {
                vm.user_name_email = false;
            }

            userService.getUserByIdMyProfile($rootScope.userId).then(function(response) {
                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.userName = vm.user.userName;
                    vm.user.firstName = vm.user.firstName;
                    vm.user.middleInitial = vm.user.middleName;
                    vm.user.lastName = vm.user.lastName;
                    vm.user.address1 = vm.user.address1;
                    vm.user.address2 = vm.user.address2;
                    vm.user.address3 = vm.user.address3;
                    vm.user.address4 = vm.user.address4;
                    vm.user.contactHome = vm.user.phone1;
                    vm.user.contactOffice = vm.user.phone2;
                    vm.user.city = vm.user.city;
                    vm.user.country = vm.user.countryId;
                    vm.user.state = vm.user.stateId;
                    vm.user.postalcode = vm.user.postalCode;
                    vm.user.userType = vm.user.userTypeId;
                    vm.id = $rootScope.userId;
                    var flag = 0

                    //selecting country based on saved value during creation
                    angular.forEach(vm.countryList, function(values, key) {
                        if (values.countryId == vm.user.country && flag == 0) {
                            vm.user.selectedOptionCountry = values;
                            var selectedCountryId = values.countryId;

                            userService.getstateList(selectedCountryId).then(function(response) {
                                vm.stateList = response.data;
                                var stateflag = 0;
                                //vm.user.selectedOptionState = vm.stateList[vm.user.stateId]; 
                                angular.forEach(vm.stateList, function(values, key) {

                                    if (values.stateId == vm.user.stateId && stateflag == 0) {
                                        vm.user.selectedOptionState = values;
                                        stateflag = 1;
                                    }
                                });
                                // vm.user.selected_State = vm.user.stateId;
                                $log.debug(vm.user.selected_State);
                            });
                            flag = 1;

                        }
                    });
                } else if (response.status === 404) {
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }
                }
            });
            vm.showLoader = false;
        }

        vm.updateUser = function() {


            userParam = userFormValidation();

            //calling create user api and checking response.If status is true return to listing page else display error message.
            if (userParam && $scope.userForm.$valid == true) {
                //calling update metadata api and checking response
                userParam.changePassword = vm.user.changePassword;
                userParam.userType = vm.user.userType;
                userParam.resource = 'myprofile';
                $log.debug(userParam);
                vm.id = $rootScope.userId;
                userService.updateUser(userParam, vm.id).then(function(response) {
                    $window.scroll(0, 0);
                    if (response.status === 204) {
                        vm.alertConfig.class = 'wk-alert-success';
                        vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                        vm.alertConfig.isList = false;

                    } else if (response.status === 409) {
                        var displayMsg = 'ERRORS.DUPLICATE_USER_NAME';
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.details = displayMsg;
                        vm.alertConfig.isList = false;
                    } else {
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.details = 'ALERTS.EDIT_FAILED';
                        vm.alertConfig.isList = false;

                    }
                });

                vm.alertConfig.show = true;

            }
        };

        var userFormValidation = function() {
            var userParam = {};
            $log.debug($scope.userForm.$valid);
            if (vm.user.password != vm.user.confirmPassword) {

                $scope.userForm.$valid = false;
                return false;
            }
            userParam.userName = vm.user.userName;
            userParam.userEmail = vm.user.userEmail;
            userParam.password = vm.user.password;
            userParam.firstName = vm.user.firstName;
            if (!angular.isUndefined(vm.user.middleInitial)) {
                userParam.middleInitial = vm.user.middleInitial;
            }
            userParam.lastName = vm.user.lastName;
            userParam.address1 = vm.user.address1;
            if (!angular.isUndefined(vm.user.address2)) {
                userParam.address2 = vm.user.address2;
            }
            if (!angular.isUndefined(vm.user.address3)) {
                userParam.address3 = vm.user.address3;
            }
            if (!angular.isUndefined(vm.user.address4)) {
                userParam.address4 = vm.user.address4;
            }
            userParam.phone1 = vm.user.contactHome;
            userParam.phone2 = vm.user.contactOffice;
            userParam.city = vm.user.city;

            userParam.countryId = vm.user.selectedOptionCountry.countryId;
            userParam.stateId = vm.user.selectedOptionState.stateId;
            userParam.postalcode = vm.user.postalcode;
            userParam.status = vm.user.status;
            userParam.userId = $rootScope.userId;
            $log.debug(userParam);
            return userParam;
        }


        //if cookies are stored, restore it and display in login page
        if (loginService.setCookie('username') && loginService.setCookie('password')) {
            vm.userCookie.userName = (loginService.setCookie('username'));
            vm.userCookie.password = (loginService.setCookie('password'));
            vm.remember = true;
        }

        //check useremail exist n send email to reset password or to send username
        vm.forgotPassword = function() {

            if ($scope.userForm.$valid == true) {
                dashboardService.forgotPassword(vm.user).then(function(response) {
                    $log.debug(response);
                    if (response.status === 200) {
                        vm.sendSuccess = 1;
                        if (vm.user.action == 'resetpassword') {
                            vm.forgotSuccessMsg = 'LABELS.FORGOT_RESET_MSG';
                        } else if (vm.user.action == 'sendusername') {
                            vm.forgotSuccessMsg = 'LABELS.FORGOT_USERNAME_MSG';
                        }
                    } else if (response.status === 400) {
                        $scope.userForm.userEmail.$error.invalidusererror = true;
                    }

                });
            }
        }
        if (vm.moduleType == 'resetpassword') {

            vm.validator.resetToken = $rootScope.$stateParams.id;
            if (vm.validator.resetToken) {
                dashboardService.validateResetPassword(vm.validator).then(function(response) {
                    $log.debug(response.data.code);
                    if (response.status === 201) {

                    } else if (response.status === 401) {
                        if (response.data.code == 8010) {

                            vm.sendSuccess = 1;
                        }

                    }

                });
            } else {
                $rootScope.$state.go('404');
            }
        }


        //change passsword for the given user based on resetToken
        vm.resetPassword = function() {

            if ($scope.userForm.$valid == true) {
                vm.user.resetToken = vm.validator.resetToken;
                dashboardService.resetPassword(vm.user).then(function(response) {
                    $log.debug(response);
                    if (response.status === 200) {
                        vm.sendSuccess = 2;
                    } else if (response.status === 400) {
                        vm.sendSuccess = 1;
                    }

                });
            }
        }

        //function to validate user and allow login
        vm.loginUser = function() {
            console.log($rootScope)
            if ($scope.userForm.$valid == true) {
                vm.invalidusererror = false;
                loginService.loginUser(vm.userCookie).then(function(response) {
                    $log.debug(response);
                    if (response.status === 200) {
                        //this is used to save as cookie for user's username and password


                        if (vm.remember) {

                            loginService.setCookie('username', (vm.userCookie.userName));
                            loginService.setCookie('password', (vm.userCookie.password));

                        } else {
                            loginService.setCookie('username', '');
                            loginService.setCookie('password', '');
                        }
                        //Assign user authorization detail in local storage
                        $localStorage.currentUser = response.data;
                        console.log($rootScope)

                        $rootScope.$state.go('dashboard.main');

                    } else if (response.status === 401) {
                        if (response.data.code === 8002) {
                            vm.invalidusererror = true;
                        }
                    }

                });
            }
        }

    }])
})();

(function () {
    'use strict';

    angular.module('app.group').controller('GroupController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'groupService', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, groupService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;

        vm.table = vm.searchFilter = vm.user = vm.group = {};
        vm.showdropdown = 0;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        vm.alertConfig = {'show': false};

        vm.showdropdown = function () {
            vm.showdropdown = 1;
        };

        vm.pageError = false;
        vm.showLoader = true;

        var userParam = {};
        var roleDetails = {};

        if ($localStorage.userTypeList) {
            vm.userTypeList = $localStorage.userTypeList;
            //select default country as 
            angular.forEach(vm.userTypeList, function (value, key) {
                if (value.userTypeName == 'ADMIN')
                {
                    vm.userType = value.userTypeId;
                }
            })

        } else {
            //call user service to get list of usertype
            groupService.getuserTypeList().then(function (response) {
                $localStorage.userTypeList = vm.userTypeList = response.data;
                angular.forEach(vm.userTypeList, function (value, key) {
                    if (value.userTypeName == 'ADMIN')
                    {
                        vm.userType = value.userTypeId;
                    }
                })

            });

        }
        if ($localStorage.statusList) {
            vm.statusList = $localStorage.statusList;
            //select default country as 
            angular.forEach(vm.statusList, function (value, key) {

                if (value.statusName == 'ACTIVE')
                {
                    vm.activeValue = value.statusCode;
                }
                if (value.statusName == 'INACTIVE')
                {
                    vm.inactiveValue = value.statusCode;
                }
                vm.group.status = vm.activeValue;
            });
            vm.group.status = vm.activeValue;

        } else {
            //call user service to get list of states    
            groupService.getStatus().then(function (response) {
                $localStorage.statusList = vm.statusList = response.data;
                angular.forEach(vm.statusList, function (value, key) {
                    $log.debug(value);
                    if (value.statusName == 'ACTIVE')
                    {
                        vm.activeValue = value.statusCode;
                    }
                    if (value.statusName == 'INACTIVE')
                    {
                        vm.inactiveValue = value.statusCode;
                    }
                    vm.group.status = vm.activeValue;
                });

            });
        }

        //Assign default values and perform actions based on actionType 
        if (vm.actionType == "list") {
            $log.debug($localStorage.groupTableState);
            if (angular.isDefined($localStorage.groupTableState) && angular.isDefined($localStorage.groupTableState.pagination) && angular.isDefined($localStorage.groupTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.groupTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.GROUP_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['group'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['group'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['group'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['group'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else if (vm.actionType == "create") {
            vm.pageTitle = "PAGE_TITLE.GROUP_CREATE"; //Page title mapped to locale json key
            vm.showLoader = false;
        } else if ((vm.actionType == "view" || vm.actionType == "delete" || vm.actionType == "edit") && $rootScope.$stateParams.id !== '') {

            vm.group.id = vm.id = $rootScope.$stateParams.id;
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.GROUP_VIEW_LABEL"; //Page title mapped to locale json key of view label

            else if (vm.actionType == 'edit') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.GROUP_EDIT"; //Page title mapped to locale json key of edit label

            else
                vm.pageTitle = "PAGE_TITLE.GROUP_DELETE_LABEL"; //Page title mapped to locale json key of delete label
            var params = {};
            params.userId = $rootScope.userId;

            //Get group for the given id by calling user/{id} api
            groupService.getGroupsById(vm.id).then(function (response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.group = response.data;
                    vm.group.groupName = vm.group.groupName;
                    vm.group.description = vm.group.description;
//                    vm.table.totalRecords = vm.group.total;
//                    tableState.pagination.numberOfPages = Math.ceil(vm.group.total / vm.table.dataPerPage);
//                    vm.showLoader = false; //Hide loader
                    vm.roleDetails = vm.group.roleDetails;
                } else {
                    vm.pageError = true;
                }
            });
            vm.showLoader = false;
        } else if (angular.isUndefined(vm.id)) {

            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("group.list")
        }


        //User list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.userTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {

                $localStorage.groupTableState = {};
                vm.searchFilter.groupName = "";
                vm.searchFilter.description = "";

            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.groupTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.groupTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;


                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.groupName) && vm.searchFilter.groupName != "")
                    searchParams.groupName = vm.searchFilter.groupName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.description) && vm.searchFilter.description != "")
                    searchParams.description = vm.searchFilter.description;


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.groupName = tableState.search.groupName;
                vm.searchFilter.description = tableState.search.description;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0)
            {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by user email
                    params.sort = "+groupName";
                    tableState.sort.predicate = "groupName";
                }
                if (isClear === true) {
                    params.sort = "+groupName";
                    tableState.sort.predicate = "groupName";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = 10;
                    console.log(tableState.sort)
                }

                //params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call metadata service to get list of metadata details 
                groupService.getGroups(params).then(function (response) {

                    vm.groupDetails = response.results.data;

                    $log.debug(response);
                    vm.table.totalRecords = response.results.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.groupTableState = tableState;
                    $log.debug(vm.groupDetails)
                    $log.debug("Total Result:" + response.results.total)
                });
            }
        };


        vm.groupTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.grouproleTableState = {};
                vm.searchFilter.roleName = "";
            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.grouproleTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.grouproleTableState); //Extend the stored table state with the current one. 
            params.userId = $rootScope.userId;
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.roleName) && vm.searchFilter.roleName != "")
                    searchParams.roleName = vm.searchFilter.roleName;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);

            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.roleName = tableState.search.roleName;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0)
            {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by user email
                    params.sort = "+roleName";
                    tableState.sort.predicate = "roleName";
                }
                // Apend the group id to the URL
                vm.id = $rootScope.$stateParams.id;
                params.groupId = vm.id;

                if (isClear === true) {
                    params.sort = "+roleName";
                    tableState.sort.predicate = "roleName";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = 10;
                    console.log(tableState.sort)
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

                $log.debug("Passed Parameters:" + JSON.stringify(params))
                //call metadata service to get list of metadata details 
                groupService.searchGroups(vm.id, params).then(function (response) {

                    $log.debug(response);
                    if (response.status === 200) {
                        vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
                        vm.group = response.data;
                        vm.group.groupName = vm.group.groupName;
                        vm.group.description = vm.group.description;
                        vm.roleDetails = vm.group.roleDetails;
                        vm.table.totalRecords = vm.group.total;
                        tableState.pagination.numberOfPages = Math.ceil(vm.group.total / vm.table.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table.tableStateScopeCopy = $localStorage.grouproleTableState = tableState;
                        $log.debug(response.results)
                        $log.debug("Total Result:" + vm.group.total)
                    } else if (response.status === 404) {
                        if (response.data.code == "1107") {
                            vm.pageError = true;
                        }
                    }
                });
                vm.showLoader = false;
            }
        };

        //Deletes the user based on user id
        vm.deleteGroup = function () {

            if ($localStorage.groupTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.groupTableState); //Extend the stored table state with the current one. 

            groupService.deleteGroup(vm.id).then(function (response) {
                vm.alertConfig.show = true;
                $window.scroll(0, 0);
                console.log("response")
                console.log(response)
                console.log("/response")
                if (response.status === 204) {
                    vm.alertConfig.class = 'wk-alert-success';
                    vm.alertConfig.msg = 'ALERTS.DELETE_SUCCESS';
                    vm.alertConfig.isList = false;

                } else if (response.status === 409) {
                    var displayMsg = "ERRORS.DUPLICATE_GROUP_NAME";
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = displayMsg;
                    vm.alertConfig.isList = true;
                } else {
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = 'ALERTS.DELETE_FAILED';
                    vm.alertConfig.isList = false;
                }

                vm.alertConfig.show = true;
                if (vm.alertConfig.isList == false)
                {
                    $timeout(function () {
                        vm.alertConfig.show = false; //Hides alert
                        $rootScope.$state.go('group.list');
                    }, 2000);
                }
            });
        }

        //function to create new group
        vm.createGroup = function ()
        {
            $log.debug(vm.group);
            return false;
            vm.isFormSubmitted = true;
            vm.group.userId = $rootScope.userId;
            if ($scope.groupForm.$valid) {
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                    groupService.insertGroup(vm.group).then(function (response) {
                        if (response.status === 201) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', 'group.list', false);
                        } else if (response.status === 409) {
                            if (response.data.code == "1112") {
                                var displayMsg = 'ERRORS.DUPLICATE_GROUP_NAME';
                                vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                                vm.isSubmitDisabled = false;
                            }
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', '', false);
                        }
                    });
                } else
                {

                    groupService.updateGroup(vm.group, vm.id).then(function (response) {
                        if (response.status === 201) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', 'group.list', false);
                        } else if (response.status === 409) {
                            if (response.data.code == "1112") {
                                var displayMsg = 'ERRORS.DUPLICATE_GROUP_NAME';
                                vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                                vm.isSubmitDisabled = false;
                            }
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', '', false);
                        }
                    });

                }
            }
        }

        vm.inspectcheckAll = function (association) {
            console.log(association);return false;
            var userParam = {};
            if (angular.isUndefined(association))
            {
                vm.errorMsg = 'ERRORS.SELECT_ROLE_OR_GROUP';
                return false;
            } else {
                vm.errorMsg = "";

            }
            userParam.selectedRoleUsers = association;
            $log.debug(userParam.selectedRoleUsers);
//            /$log.debug(vm.selectedOptionGroup);
            if (userParam.selectedRoleUsers == 1)
            {
                userParam.getRoles = [];
                //get all the Users selected
                userParam.getUsers = [];
                angular.forEach(vm.selectedOptionUsers, function (value, key) {
                    if (value == true)
                    {
                        userParam.getUsers.push(key);
                    }
                });
                if (userParam.getUsers.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_ROLE';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                vm.group.getAssociation = userParam.getUsers.join(',');
                vm.group.associationType = "users";
            } else if (userParam.selectedRoleUsers == 2)
            {
                //get all the roles selected
                userParam.getRoles = [];

                angular.forEach(vm.selectedOptionRole, function (value, key) {
                    if (value == true)
                    {
                        userParam.getRoles.push(key);
                    }
                });
                $log.debug("$$$$$$$$$$" + userParam.getRoles.length);
                if (userParam.getRoles.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_GROUP';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                vm.group.getAssociation = userParam.getRoles.join(',');
                vm.group.associationType = "users";
            }
        }


        //This will be called for displaying Users records
        vm.userListPipe = function (tableState1, isSearch, isClear) {

            var params = {};

            //vm.showLoader = true; //Shows Loader

            params.userId = $rootScope.userId; //Add userId in request param


            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userListTableState = {};
                vm.searchFilter.firstName = "";
                vm.searchFilter.lastName = "";
                vm.userUnCheck = [];
            }
            // console.log(vm.searchFilter.firstName);return false;

            //Check if any local tables exist
            //And check if vm.table1.tableStateScopeCopy1 is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userListTableState && angular.isUndefined(vm.table1.tableStateScopeCopy1))
                angular.extend(tableState1, $localStorage.userListTableState);
            //Extend the stored table state with the current one. 


            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState1.pagination.start = 0;
                //vm.showLoader = true;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.firstName) && vm.searchFilter.firstName != "")
                    searchParams.firstName = vm.searchFilter.firstName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.lastName) && vm.searchFilter.lastName != "")
                    searchParams.lastName = vm.searchFilter.lastName;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState1.search = angular.copy(searchParams);

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            }

            //Check if existing search filter values exist
            else if (!angular.equals({}, tableState1.search)) {
                //Assign the previous search filter values to model
                vm.searchFilter.firstName = tableState1.search.firstName;
                vm.searchFilter.lastName = tableState1.search.lastName;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState1.search);

            }

            //Finding and assigning current page number
            if (tableState1.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState1.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState1.sort.predicate))
                params.sort = (tableState1.sort.reverse ? '-' : '+') + tableState1.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+userName";
                tableState1.sort.predicate = "userName";
            }
            if (isClear === true) {
                params.sort = "+userName";
                tableState1.sort.predicate = "userName";
                tableState1.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table1.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))


            params.userId = $rootScope.userId;
//            params.itemId = vm.id;
//            params.associated = 1;
            //call item service to get list of itembank associated details 
            if (vm.activeTabIndex == 0) {
                groupService.getUsersList(params).then(function (response) {

                    if (response.status == 200) {

                        vm.userDetails = response.data.data;
                        vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
                        vm.table1.totalRecords = response.data.total;
                        tableState1.pagination.numberOfPages = Math.ceil(response.data.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableState1;
                        $localStorage.userDetails = angular.copy(tableState1)
                        $log.debug(response.data)
                        $log.debug("Total Result:" + response.data.total)
                    }
                });
            }
        }


        //This will be called for displaying roles records.
        vm.roleListPipe = function (tableState1, isSearch, isClear) {

            var params = {};

            //vm.showLoader = true; //Shows Loader

            params.userId = $rootScope.userId; //Add userId in request param


            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.roleListState = {};
                vm.searchFilter.roleName = "";
                vm.roleUnCheck = [];
            }


            //Check if any local tables exist
            //And check if vm.table1.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.roleListState && angular.isUndefined(vm.table1.tableStateScopeCopy))
                angular.extend(tableState1, $localStorage.roleListState);
            //Extend the stored table state with the current one. 


            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState1.pagination.start = 0;
                //vm.showLoader = true;


                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.roleName) && vm.searchFilter.roleName != "")
                    searchParams.roleName = vm.searchFilter.roleName;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState1.search = angular.copy(searchParams);

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            }

            //Check if existing search filter values exist
            else if (!angular.equals({}, tableState1.search)) {
                //Assign the previous search filter values to model

                vm.searchFilter.roleName = tableState1.search.roleName;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState1.search);

            }

            //Finding and assigning current page number
            if (tableState1.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState1.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState1.sort.predicate))
                params.sort = (tableState1.sort.reverse ? '-' : '+') + tableState1.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+roleName";
                tableState1.sort.predicate = "roleName";
            }
            if (isClear === true) {
                params.sort = "+roleName";
                tableState1.sort.predicate = "roleName";
                tableState1.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))


            params.userId = $rootScope.userId;
//            params.itemId = vm.id;
//            params.associated = 1;
            //call item service to get list of itembank associated details 

            groupService.getRolesList(params).then(function (response) {

                if (response.status == 200) {

                    vm.roleDetails = response.data.data;
                    vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
                    vm.table.totalRecords = response.data.total;
                    tableState1.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = tableState1;
                    $localStorage.roleDetails = angular.copy(tableState1)
                    $log.debug(response.data)
                    $log.debug("Total Result:" + response.data.total)
                }
            });

        }


        //Used for alerting on success/failure
        vm.alertConfig.timeOutAlert = function (cssClass, alertMsg, redirectState, isList) {
            $window.scroll(0, 0);
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (redirectState != '') { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }

        }

    }])
})();
(function () {
    'use strict';


    angular.module('app.itembanks').controller('ItemcollectionsController', ['$rootScope', '$scope', '$window', '$log', '$uibModal', '$localStorage', '$filter', '$timeout', 'config', 'itembanksService', function ($rootScope, $scope, $window, $log, $uibModal, $localStorage, $filter, $timeout, config, itembanksService) {

        var vm = this;
        vm.id = $rootScope.$stateParams.id;
        $scope.forms = {};
        vm.questionSelectedPreview = '';
        vm.showPreview = false;
        vm.item = {};
        vm.itemCollection = {};
        vm.itemCollectionPublish = {};
        vm.itemCollection.selectedMetaDetails = [];
        vm.itemCollection.metadataAssoc = {};
        vm.previewAsset = false;
        //vm.itemCollection.questionCheck = [];
        vm.questionUnCheck = [];
        vm.actionType = $rootScope.$state.current.name.split(".")[1]
        vm.showLoader = true, vm.pageError = false, vm.closeOtherAccordions = false, vm.isSubmitDisabled = false;
        vm.otherInfo = false;
        vm.alertConfig = {'show': false}
        vm.table = {}, vm.table.totalRecords = 0;
        vm.table1 = {}, vm.table1.totalRecords = 0;
        vm.associatedTab = 1
        //api call to fetch all questions
        vm.table = {}, vm.searchFilter = {}, vm.searchFilter.metadataAssoc = {}, vm.searchFilter.selectedMetaDetails = [];



        if (angular.isDefined($localStorage.itemsTableState) && angular.isDefined($localStorage.itemsTableState.pagination.number))
            vm.table.dataPerPage = $localStorage.itemsTableState.pagination.number
        else
            vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

        vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
        if (vm.actionType == "create" || vm.actionType == "edit") { //List of actions related to create page
            vm.itemCollection.metadataAssoc = {}, vm.itemCollection.selectedMetaDetails = [];
            vm.showLoader = false;

            vm.itemCollection.userId = $rootScope.userId;
            vm.itemCollection.statusName = "Published";

            if (vm.actionType == 'create') //check actionType to assign page title
            {
                vm.itemCollection.getItems = [];
                vm.pageTitle = "PAGE_TITLE.ITEMCOLLECTION_CREATE_LABEL";
                vm.itemCollection.firstName = $rootScope.userDetails.firstName;
                vm.itemCollection.lastName = $rootScope.userDetails.lastName;
            } else if (vm.actionType == 'edit')
                vm.pageTitle = "PAGE_TITLE.ITEMCOLLECTION_EDIT_LABEL";


        } else if (vm.actionType == "list") { //Block of actions related to list action
            vm.table = {}, vm.searchFilter = {}, vm.searchFilter.metadataAssoc = {}, vm.searchFilter.selectedMetaDetails = [];


            vm.showLoader = false;
            if (angular.isDefined($localStorage.itemsTableState) && angular.isDefined($localStorage.itemsTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.itemsTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.ITEMCOLLECTION_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['itembanks'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['itembanks'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['itembanks'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['itembanks'].indexOf('view') !== -1 ? true : false;
            vm.permission['manageSecurity'] = $rootScope.permission['itembanks'].indexOf('manageSecurity') !== -1 ? true : false;
            vm.permission['manageAssociation'] = $rootScope.permission['itembanks'].indexOf('manageAssociation') !== -1 ? true : false;

        } else if (vm.actionType == "upload") { //Block of actions related to list action
            vm.uploadConfig = {target: config.apiUrl + 'itembanksfileupload', testChunks: false, singleFile: true, chunkSize: 1024 * 1024 * 5, headers: {Authorization: $localStorage.currentUser.token, requestFrom: 'admin'}};
            vm.upload = {};
            var params = {};
            vm.upload.contentType = '';
            params.allItemCollection = true;
            itembanksService.getItemCollectionDetails(params).then(function (response) {

                if (response.results.total > 0) {
                    //$log.debug(response.results.data);
                    vm.itemcollectionList = response.results.data;
                    //vm.upload.itemBankId = vm.itemcollectionList[0];


                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "3005") {
                        vm.pageError = true;
                    }
                }
            });



        } else if (vm.actionType == "status") { //Block of actions related to list action
            vm.table = {}, vm.searchFilter = {};


            vm.showLoader = false;
            if (angular.isDefined($localStorage.importStatusInCollectionState) && angular.isDefined($localStorage.importStatusInCollectionState.pagination.number))
                vm.table.dataPerPage = $localStorage.importStatusInCollectionState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.ITEMCOLLECTION_STATUS_LABEL"; //Page title mapped to locale json key
        } else if (angular.isUndefined(vm.id)) {
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("itembanks.list")
        }

        if (vm.actionType == "edit" || vm.actionType == "view" || vm.actionType == "delete" || vm.actionType == "publish") {
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.ITEMCOLLECTION_VIEW_LABEL";
            else if (vm.actionType == 'delete')
                vm.pageTitle = "PAGE_TITLE.ITEMCOLLECTION_DELETE_LABEL";

            vm.showLoader = false;
            //call itemcollection service to get itembank details based on id 
            itembanksService.getItemCollectionDetailById(vm.id).then(function (response) {
                $log.debug(response.data);

                if (response.status === 200) {
                    vm.itemCollection = response.data;
                    vm.itemCollection.questionCheck = [];
                    vm.itemCollection.getItemsarray = [];
                    //written below condition because when thereis no mandatory tags, below objects are assigned undefined.To avoid it making it null
                    if (vm.itemCollection.selectedMetaDetails == '' || angular.isUndefined(vm.itemCollection.selectedMetaDetails)) {
                        vm.itemCollection.selectedMetaDetails = [];
                        vm.itemCollection.metadataAssoc = {};
                    }

                    // if (vm.actionType == "edit") {
                    //     angular.forEach(vm.itemCollection.getItems, function(value, key) {

                    //         vm.itemCollection.getItemsarray.push(value.itemId);
                    //         vm.itemCollection.questionCheck[value.itemId] = true;
                    //     });
                    //     // vm.itemCollection.getItemsarray = vm.itemCollection.getItemsarray.join(',');
                    // }
                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "3005") {
                        vm.pageError = true;
                    }

                }

            });
        }
        //Items list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.itemTablePipe = function (tableState, isSearch, isClear) {
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showTableLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records

            if (isClear === true) {
                $localStorage.itemTableStateInCollection = {};
                vm.searchFilter.label = "";
                vm.searchFilter.identifier = "";
                vm.searchFilter.itemTypeId = "All";
                vm.searchFilter.status = "";
                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};
                vm.itemCollection.selectedMetaDetails = [];
                $scope.forms.itemForm.clearFilterSearch();
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemTableStateInCollection && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.itemTableStateInCollection); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showTableLoader = true;

                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.label) && vm.searchFilter.label != "")
                    searchParams.label = vm.searchFilter.label;

                //Add entered item identifier in the searchParams
                if (angular.isDefined(vm.searchFilter.identifier) && vm.searchFilter.identifier != "")
                    searchParams.identifier = vm.searchFilter.identifier;

                //Add chosen item type in the searchParams
                if (angular.isDefined(vm.searchFilter.itemTypeId) && vm.searchFilter.itemTypeId !== "All")
                    searchParams.itemTypeId = vm.searchFilter.itemTypeId;

                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc

                //Add chosen item status in the searchParams
                if (angular.isDefined(vm.searchFilter.status) && vm.searchFilter.itemTypeId != "")
                    searchParams.status = vm.searchFilter.status;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.label = tableState.search.label;
                vm.searchFilter.identifier = tableState.search.identifier;
                vm.searchFilter.status = tableState.search.status;
                vm.searchFilter.itemTypeId = tableState.search.itemTypeId || "All";
                vm.searchFilter.metadataAssoc = tableState.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableState.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+label";
                tableState.sort.predicate = "label";
            }
            if (isClear === true) {
                params.sort = "+label";
                tableState.sort.predicate = "label";
                tableState.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))
            if (vm.actionType == "create") {
                params.action = 'create';

            } else if (vm.actionType == "edit") {

                params.action = 'edit';
                params.itemBankId = vm.id;
            } else if (vm.actionType == "view" || vm.actionType == "delete" || vm.actionType == "publish") {
                params.action = 'view';
                params.itemBankId = vm.id;
            }
            itembanksService.getItemAssociation(params, tableState).then(function (response) {
                vm.itemDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showTableLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.table.tableStateScopeCopy = tableState;
                //$localStorage.itemTableStateInCollection = angular.copy(tableState)
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)


            });

        }

        //Used to create/update items
        vm.createItemCollection = function () {

            vm.itemCollection.userId = $rootScope.userId;

            var validateFormResponse = validateFormData();

            //Check the form is valid and other custom validations.
            if (validateFormResponse && $scope.itemCollectionForm.$valid) {
                var itemData = {};
                //delete itemData.selectedMetaDetails;
                //delete itemData.questionCheck;
                //delete itemData.getItems;
                //delete itemData.getItemsarray;

                itemData.itemBankName = vm.itemCollection.itemBankName;
                itemData.description = vm.itemCollection.description;
                itemData.statusName = vm.itemCollection.statusName;
                itemData.metadataAssoc = vm.itemCollection.metadataAssoc;
                itemData.userId = vm.itemCollection.userId;
                itemData.associated = vm.itemCollection.getItems;
                //itemData.dissociated = vm.itemCollection.dissociated;



                $log.debug(angular.toJson(itemData, true));


                //Calling create item api and checking response.If status is true return to listing page else display error message.
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {

                    itembanksService.insertItemCollection(itemData).then(function (response) {
                        if (response.status === 201) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', 'itembanks.list', false);
                        } else if (response.status === 409) {
                            var displayMsg = 'ERRORS.DUPLICATE_BANKNAME';
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                            vm.isSubmitDisabled = false;
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', '', false);
                        }

                    });
                } else {
                    itemData.itemBankId = vm.itemCollection.itemBankId;
                    //Calling update item api and checking response
                    itembanksService.updateItemCollection(itemData, vm.id).then(function (response) {
                        if (response.status === 204) { //On successfull update
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.EDIT_SUCCESS', '', false);
                            vm.itemTablePipe(vm.table.tableStateScopeCopy, true);
                        } else { //On update failure
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.EDIT_FAILED', '', false);
                        }
                    });

                }
            }
        }

        var validateFormData = function () {
            // vm.itemCollection.associated = [];
            // $log.debug(vm.itemCollection.questionCheck);
            // angular.forEach(vm.itemCollection.questionCheck, function(value, key) {
            //     if (value == true) //get all records to be added
            //         vm.itemCollection.associated.push(key);
            // });

            if (vm.itemCollection.getItems && vm.itemCollection.getItems.length == 0) {
                vm.errorMsg = 'ERRORS.SELECT_QUESTION';
                $scope.accordion.groups[0].isOpen = true;

                return false;
            } else {
                vm.errorMsg = '';

                // vm.itemCollection.associated = vm.itemCollection.associated.join(',');

                // var associatedItems = vm.itemCollection.associated.split(','); //convert string to array
                // if (vm.actionType == "edit") {
                //     //this logic is used to find the difference in associated array and checked array, and get ids which are dissociated.
                //     var seen = [];
                //     vm.itemCollection.dissociated = [];
                //     for (var i = 0; i < associatedItems.length; i++)
                //         seen[associatedItems[i]] = true;
                //     for (var i = 0; i < vm.itemCollection.getItemsarray.length; i++)
                //         if (!seen[vm.itemCollection.getItemsarray[i]])
                //             vm.itemCollection.dissociated.push(vm.itemCollection.getItemsarray[i]);

                //     vm.itemCollection.dissociated = vm.itemCollection.dissociated.join(',');

                //     vm.itemCollection.getItemsarray = [];
                //     for (var i = 0; i < associatedItems.length; i++) {
                //         vm.itemCollection.getItemsarray.push(associatedItems[i]);

                //     }

                // }
                return true;
            }

        }

        //Used for alerting on success/failure
        vm.alertConfig.timeOutAlert = function (cssClass, alertMsg, redirectState, isList) {
            $window.scroll(0, 0);
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (redirectState != '') { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }

        }

        //Items list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.itemCollectionTablePipe = function (tableState, isSearch, isClear) {
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader

            //this is to clear the search filter form and display the default records

            if (isClear === true) {
                $localStorage.itemCollectionTableState = {};
                vm.searchFilter.bankName = "";
                vm.searchFilter.description = "";
                vm.searchFilter.itemTypeId = "All";
                vm.searchFilter.status = "";
                vm.searchFilter.createBankByUpload = "";
                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};
                $scope.itemForm.clearFilterSearch();
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemCollectionTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.itemCollectionTableState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.bankName) && vm.searchFilter.bankName != "")
                    searchParams.bankName = vm.searchFilter.bankName;

                //Add entered item description in the searchParams
                if (angular.isDefined(vm.searchFilter.description) && vm.searchFilter.description != "")
                    searchParams.description = vm.searchFilter.description;


                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc

                //Add chosen itembank status (published/nonpublished) in the searchParams
                if (angular.isDefined(vm.searchFilter.status) && vm.searchFilter.status != "")
                    searchParams.status = vm.searchFilter.status;

                //Add chosen itembank status(active/inactive) in the searchParams
                if (angular.isDefined(vm.searchFilter.active) && vm.searchFilter.active != "")
                    searchParams.active = vm.searchFilter.active;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.bankName = tableState.search.bankName;
                vm.searchFilter.description = tableState.search.description;
                vm.searchFilter.status = tableState.search.status;
                vm.searchFilter.active = tableState.search.active;
                vm.searchFilter.metadataAssoc = tableState.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableState.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+bankName";
                tableState.sort.predicate = "bankName";
            }
            if (isClear === true) {
                params.sort = "+bankName";
                tableState.sort.predicate = "bankName";
                tableState.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))

            //call item service to get list of item details 
            itembanksService.getItemCollectionDetails(params, tableState).then(function (response) {
                $log.debug(response);
                vm.itemCollectionDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.table.tableStateScopeCopy = tableState;
                $localStorage.itemCollectionTableState = angular.copy(tableState)
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)
            });
        }

        vm.selectItem = function (itemDetails) {
            var itemIndex = vm.isItemExist(itemDetails.id)
            if (itemIndex == -1) {
                var itemData = {itemId: parseInt(itemDetails.id), label: itemDetails.label, version: itemDetails.version}
                vm.itemCollection.getItems.push(itemData);
            } else {
                vm.itemCollection.getItems.splice(itemIndex, 1);
            }
        }

        vm.isItemExist = function (itemId) {
            itemId = parseInt(itemId)
            return vm.itemCollection.getItems.map(function (item) {
                return item.itemId;
            }).indexOf(itemId);
        }

        vm.inspectcheckAll = function () {
            vm.itemCollection.associated = [];
            $log.debug("validateOnly");
            angular.forEach(vm.itemCollection.questionCheck, function (value, key) {

                if (value == true) //get all records to be added
                    vm.itemCollection.associated.push(key);
            });
            if (vm.itemCollection.associated.length == 0) {
                vm.errorMsg = 'ERRORS.SELECT_QUESTION';
                $scope.accordion.groups[0].isOpen = true;

                return false;
            } else {
                vm.errorMsg = ''
            }
        }

        vm.deleteItemCollection = function () {
            vm.isSubmitDisabled = true;

            itembanksService.deleteItemCollection(vm.id).then(function (response) {
                if (response.status == 200) {

                    //vm.item.deletedInfo = response.data;
                } else if (response.status == 204) {
                    vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.DELETE_SUCCESS', 'itembanks.list', false);
                } else {
                    vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.DELETE_FAILED', 'itembanks.list', false);
                }
            });
        }
        vm.getPreview = function (id) {
            vm.otherInfo = true;
            vm.questionSelectedPreview = id;
        }

        //Handles asset upload process for IMAGE/VIDEO/GRAPHIC item types
        vm.uploadComplete = function (file, message, flowObj) {
            var response = angular.fromJson(message);
            vm.upload = angular.extend(vm.upload, response);
            vm.uploadError = false;
        }

        //Checks for file type/extension and size once it is added to import 
        vm.validateImportFile = function (file, event, flowObj) {
            var ext = angular.lowercase(file.file.name.split('.')[1]);
            if (ext != "zip") {
                flowObj.files = [];
                vm.uploadError = true;
                vm.uploadErrorText = "ERRORS.INVALID_FILE_FORMAT";
                return false;
            } else if ((file.file.size / (1024 * 1024)) >= 500) {
                flowObj.files = [];
                vm.uploadError = true;
                vm.uploadErrorText = "ERRORS.MAX_FILE_SIZE";
                return false;
            } else {
                vm.uploadError = false;
                return true;
            }
        }

        //Final import of item collection 
        vm.createBankByUpload = function () {

            vm.isFormSubmitted = true;
            if (angular.isUndefined(vm.upload.selectBankType)) {
                vm.bankErrorMsg = 'ERRORS.SELECT_NEW_OR_EXIST';
                vm.upload.selectBankType = 1;
                return false;
            } else {
                vm.bankErrorMsg = "";

            }
            if (angular.isUndefined(vm.upload.contentType) || vm.upload.contentType == '') {
                vm.contentErrorMsg = 'ERRORS.SELECT_CONTENT_TYPE';
                //vm.upload.selectBankType = 1;
                return false;
            } else {
                vm.contentErrorMsg = "";

            }

            if (angular.isUndefined(vm.upload.tmpFileName)) {
                vm.uploadError = true;
                vm.uploadErrorText = "ERRORS.FILE_REQUIRED";
            } else if ($scope.itemCollectionUploadForm.$valid) {
                vm.isSubmitDisabled = false;
                console.log("Successfully uploaded")
                vm.upload.userId = $rootScope.userId;
                if (vm.upload.selectBankType == 2) {
                    vm.upload.itemBankExistingId = vm.upload.itemBankId.itemBankId;

                }
                if (vm.upload.selectBankType == 1) {
                    vm.upload.statusName = "Imported";
                }
                itembanksService.importItemCollection(vm.upload).then(function (response) {
                    if (response.status === 201) {
                        if (vm.upload.selectBankType == 1) {
                            var displayMsg = 'ALERTS.UPLOAD_NEWBANK_SUCCESS';
                        }
                        else
                        {
                            var displayMsg = 'ALERTS.UPLOAD_EXISTBANK_SUCCESS';
                        }
                        vm.alertConfig.timeOutAlert('wk-alert-success', displayMsg, 'itembanks.list', false);
                    } else if (response.status === 409) {
                        var displayMsg = 'ERRORS.DUPLICATE_BANKNAME';
                        vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                        vm.isSubmitDisabled = false;
                    }
                    else if (response.status === 400) {

                        if (response.data.code == "3010") {
                            var displayMsg = 'ERRORS.INVALID_UPLOADING_NEWBANK_FILE';
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);

                        }
                        else if (response.data.code == "3011")
                        {
                            var displayMsg = 'ERRORS.UPLOAD_NEWBANK_FILE_NOT_EXIST';
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);

                        }
                        else if (response.data.code == "3012")
                        {
                            var displayMsg = 'ERRORS.INVALID_UPLOADING_EXISTBANK_FILE';
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);

                        }
                        else if (response.data.code == "3013")
                        {
                            var displayMsg = 'ERRORS.UPLOAD_EXISTBANK_FILE_NOT_EXIST';
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);

                        }

                        //vm.alertConfig.timeOutAlert('wk-alert-danger', response.data.description, '', false);
                        vm.isSubmitDisabled = false;
                    } else {
                        vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', '', false);
                    }
                    var params = {};

                    params.allItemCollection = true;
                    itembanksService.getItemCollectionDetails(params).then(function (response) {

                        if (response.results.total > 0) {
                            //$log.debug(response.results.data);
                            vm.itemcollectionList = response.results.data;
                            //vm.upload.itemBankId = vm.itemcollectionList[0];


                        } else if (response.status === 404) { //Error page in case data not found on server
                            if (response.data.code == "3005") {
                                vm.pageError = true;
                            }
                        }
                    });
                });
            }
        }


        //Used to publish current item collection
        vm.publishItemCollection = function ($publishValue, $statusDependedValue) {
            vm.itemCollectionPublish.publishValue = $publishValue;
            vm.itemCollectionPublish.itemIds = [];
            vm.itemCollectionPublish.statusDependedValue = $statusDependedValue;
            if ($publishValue == 'selected') {
                //collect all selected items if publish selected is clicked
                angular.forEach(vm.itemCollectionPublish.itemId, function (value, key) {

                    if (value == true) //get all records to be added
                        vm.itemCollectionPublish.itemIds.push(key);
                });
                if (vm.itemCollectionPublish.itemIds.length == 0) {
                    vm.errorMsg = 'ERRORS.SELECT_QUESTION';
                    $scope.accordion.groups[0].isOpen = true;
                    return false;
                }
                vm.itemCollectionPublish.itemIds = vm.itemCollectionPublish.itemIds.join(',');

            } else {
                vm.itemCollectionPublish.itemIds = 'all';
            }


            vm.isSubmitDisabled = true;
            //calling publish item api to publish the item
            itembanksService.publishItemCollection(vm.id, vm.itemCollectionPublish).then(function (response) {
                if (response.status === 200) {
                    if ($statusDependedValue == 'Published') {
                        var succMsg = 'ALERTS.PUBLISH_SUCCESS';
                    } else if ($statusDependedValue == 'Authoring') {
                        var succMsg = 'ALERTS.AUTHORING_SUCCESS';
                    }
                    vm.alertConfig.timeOutAlert('wk-alert-success', succMsg, 'itembanks.list', false);
                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "3005") {
                        vm.pageError = true;
                    }

                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "3014") {
                        if ($statusDependedValue == 'Published') {
                            var failMsg = 'ALERTS.PUBLISH_FAILED';
                        } else if ($statusDependedValue == 'Authoring') {
                            var failMsg = 'ALERTS.AUTHORING_FAILED';
                        }
                        vm.alertConfig.timeOutAlert('wk-alert-danger', failMsg, 'itembanks.list', false);
                    }

                } else {
                    if ($statusDependedValue == 'Published') {
                        var failMsg = 'ALERTS.PUBLISH_FAILED';
                    } else if ($statusDependedValue == 'Authoring') {
                        var failMsg = 'ALERTS.AUTHORING_FAILED';
                    }
                    vm.alertConfig.timeOutAlert('wk-alert-danger', failMsg, 'itembanks.list', false);
                }

            });
        }

        //table to display upload item status of item collection
        vm.itemCollectionSatusTablePipe = function (tableState, isSearch, isClear) {
            var params = {};

            vm.showTableLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param


            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.importStatusInCollectionState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.importStatusInCollectionState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 


            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+createdDate";
                tableState.sort.predicate = "createdDate";
            }


            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))
            params.id = vm.id;
            itembanksService.getItemStatusInImport(params, tableState).then(function (response) {
                vm.itemStatusDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showTableLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.table.tableStateScopeCopy = tableState;
                $localStorage.importStatusInCollectionState = angular.copy(tableState)
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)


            });

        }
        var ViewErrorModal = function ($scope, $uibModalInstance, getError) {
            var $ctrl = this;
            if (getError == null)
            {
                $ctrl.getError = [];
                getError = 'No Errors';
                $ctrl.getError.push(getError);
            }
            else
            {
                $ctrl.getError = getError;

            }
            //$log.debug($ctrl.getError) 
            $ctrl.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };
        }
        ViewErrorModal.$inject = ['$scope', '$uibModalInstance', 'getError'];

        vm.openErrorModal = function (getError) {


            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ViewErrorModal',
                controller: ViewErrorModal,
                controllerAs: '$ctrl',
                size: 'lg',
                resolve: {
                    getError: function () {
                        return getError;
                    }
                }
            });
        }

    }])
})();

(function() {
    'use strict';

    angular.module('app.items').controller('ItemsController', ['$rootScope', '$scope', '$localStorage', '$filter', '$window', '$timeout', '$log', '$uibModal', 'itemsService', 'metadataService', 'Upload', 'config', function($rootScope, $scope, $localStorage, $filter, $window, $timeout, $log, $uibModal, itemsService, metadataService, Upload, config) {
        var vm = this;
        vm.item = {};
        vm.previewAsset = false;
        vm.questionCheck = [];
        vm.questionUnCheck = [];
        vm.actionType = $rootScope.$state.current.name.split(".")[1]
        vm.showLoader = true, vm.pageError = false, vm.closeOtherAccordions = false, vm.isSubmitDisabled = false;
        vm.alertConfig = { 'show': false }
        vm.table = {}, vm.table.totalRecords = 0;
        vm.table1 = {}, vm.table1.totalRecords = 0;
        vm.associatedTab = 1
        vm.itemFeedbackTypes = [
            { "outcomeType": 1, "outcomeName": "LABELS.CORRECT_RATIONALE", "outcomeTooltip": "TOOLTIPS.CORRECT_RATIONALE", "lengthError": "ERRORS.CORRECT_RATIONALE_LENGTH_MSG" },
            { "outcomeType": 2, "outcomeName": "LABELS.INCORRECT_RATIONALE", "outcomeTooltip": "TOOLTIPS.INCORRECT_RATIONALE", "lengthError": "ERRORS.INCORRECT_RATIONALE_LENGTH_MSG" }
        ];

        var assignAnswerTemplate = function() {
            vm.answerTemplate = "multiple-choice-answer";
            vm.itemDescriptionLang = "LABELS.ITEM_TEXT";
            if (vm.selectedItemType.labelText == "GRAPHIC_OPTION")
                vm.answerTemplate = "graphic-option-answer";

            if (vm.selectedItemType.labelText == "CLINICAL_SYMPTOMS") {
                vm.itemDescriptionLang = "LABELS.CLINICAL_PRESENTING_SYMPTOMS";
            } else if (vm.selectedItemType.labelText == "MEDICAL_CASE") {
                vm.itemDescriptionLang = "LABELS.MEDICAL_SCENARIO";
            }
        };

        if (['edit', 'create', 'list'].indexOf(vm.actionType) != -1) {
            //Get itemtype details from server or from local storage
            if ($localStorage.itemTypeDetails) {
                vm.itemTypeDetails = $localStorage.itemTypeDetails;
            } else {
                itemsService.getItemTypesList().then(function(response) {
                    $localStorage.itemTypeDetails = vm.itemTypeDetails = response.data;
                });
            }
        }

        if (vm.actionType == "list") { //Block of actions related to list action
            vm.table = {}, vm.searchFilter = {}, vm.searchFilter.metadataAssoc = {}, vm.searchFilter.selectedMetaDetails = [];

            vm.searchFilter.itemTypeId = "All";
            //vm.searchFilter.status = "Authoring";
            if (angular.isDefined($localStorage.itemsTableState) && angular.isDefined($localStorage.itemsTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.itemsTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.ITEM_LIST"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['items'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['items'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['items'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['items'].indexOf('view') !== -1 ? true : false;
            vm.permission['manageSecurity'] = $rootScope.permission['items'].indexOf('manageSecurity') !== -1 ? true : false;
            vm.permission['manageAssociation'] = $rootScope.permission['items'].indexOf('manageAssociation') !== -1 ? true : false;


        } else if (vm.actionType == "association") {
            vm.table = {}, vm.searchFilter = {}, vm.searchFilter.metadataAssoc = {}, vm.searchFilter.selectedMetaDetails = [];
            //vm.searchFilter.status = "Authoring";
            if (angular.isDefined($localStorage.itemAssociateTableState) && angular.isDefined($localStorage.itemAssociateTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.itemAssociateTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options

            vm.pageTitle = "PAGE_TITLE.ITEM_ASSOCIATE";
            vm.id = $rootScope.$stateParams.id;
            vm.closeOtherAccordions = true, vm.otherInfo = false;

            //Get item detail from server based on item id
            itemsService.getItemById(vm.id).then(function(response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.item = response.data;

                    $log.debug(vm.item);
                } else if (response.status === 404) { //Error in case of data not found on server
                    if (response.data.code == "2007") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;

            });

        } else if (['view', 'preview', 'delete', 'publish'].indexOf(vm.actionType) != -1 && angular.isDefined($rootScope.$stateParams.id)) { //Block of common actions related to 'view' or 'preview' or 'delete' or 'publish'
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.ITEM_VIEW";
            else if (vm.actionType == 'delete')
                vm.pageTitle = "PAGE_TITLE.ITEM_DELETE";
            else if (vm.actionType == 'preview')
                vm.pageTitle = "PAGE_TITLE.ITEM_PREVIEW";
            else if (vm.actionType == 'publish')
                vm.pageTitle = "PAGE_TITLE.ITEM_PUBLISH";
            else if (vm.actionType == 'association')
                vm.pageTitle = "PAGE_TITLE.ITEM_ASSOCIATE";

            vm.id = $rootScope.$stateParams.id;
            vm.closeOtherAccordions = true, vm.otherInfo = false;
            vm.itemDescriptionLang = "LABELS.ITEM_TEXT";


            //Get item detail from server based on item id
            itemsService.getItemById(vm.id).then(function(response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.item = response.data;
                    if (vm.actionType != 'preview' && (vm.item.labelText == "CLINICAL_SYMPTOMS" || vm.item.labelText == "MEDICAL_CASE")) {
                        vm.getchildItemDetails();
                    }
                    //Change language text dynamically based on ype
                    if (vm.item.labelText == "CLINICAL_SYMPTOMS") {
                        vm.itemDescriptionLang = "LABELS.CLINICAL_PRESENTING_SYMPTOMS";
                    } else if (vm.item.labelText == "MEDICAL_CASE") {
                        vm.itemDescriptionLang = "LABELS.MEDICAL_SCENARIO";
                    }
                    vm.item.parent = $rootScope.$stateParams.parentId;
                    vm.item.parentItemType = $rootScope.$stateParams.parentItemType;
                    if (vm.item.parent != '' || vm.item.parent != '0') {
                        itemsService.getItemById(vm.item.parent).then(function(response) {

                            if (response.status === 200) {
                                if (response.data.version == 1 && response.data.versionsList.length > 1) //Check to avoid editing older version of item
                                    vm.pageError = true;
                                else
                                    vm.parentItem = response.data;
                            } else if (response.status === 404) { //Error in case of data not found on server
                                if (response.data.code == "2007") {
                                    vm.pageError = true;
                                }

                            }
                        });
                    }
                } else if (response.status === 404) { //Error in case of data not found on server
                    if (response.data.code == "2007") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;

            });
            //Get remediation type details from server or from local storage
            if ($localStorage.remediationTypeDetails) {
                vm.remediationTypeDetails = $localStorage.remediationTypeDetails;
            } else {
                itemsService.getRemediationTypesList().then(function(response) {
                    $localStorage.remediationTypeDetails = vm.remediationTypeDetails = response.data;
                });
            }

        } else if (vm.actionType == "edit" && angular.isDefined($rootScope.$stateParams.id)) { //List of actions related to edit page
            vm.pageTitle = "PAGE_TITLE.ITEM_EDIT";
            vm.id = $rootScope.$stateParams.id;
            vm.item.assets = [], vm.item.selectedMetaDetails = [];
            vm.difficultyMin = config.item.itemDifficultyMin;
            vm.difficultyMax = config.item.itemDifficultyMax;
            vm.scoreMin = config.item.itemScoreMin;
            vm.scoreMax = config.item.itemScoreMax;
            vm.customValidation = { valid: true, minChoice: false, minRemedy: false, minCorrectAnswer: false, minAsset: false, invalidAsset: false };

            //Get item detail from server
            itemsService.getItemById(vm.id).then(function(response) {
                console.log(response)
                if (response.status === 200) {
                    if (response.data.version == 1 && response.data.versionsList.length > 1) //Check to avoid editing older version of item
                        vm.pageError = true;
                    else
                        vm.item = response.data;
                    vm.selectedItemType = $filter("filter")(vm.itemTypeDetails, { itemTypeId: vm.item.itemType }, true)[0];
                    $log.debug(vm.selectedItemType)
                        //written below condition because when thereis no mandatory tags, below objects are assigned undefined.To avoid it making it null
                    if (vm.item.selectedMetaDetails == '' || angular.isUndefined(vm.item.selectedMetaDetails)) {
                        vm.item.selectedMetaDetails = [];
                        vm.item.metadataAssoc = {};
                        vm.item.metadataPrev = {};
                    }


                    if (vm.selectedItemType.labelText == "IMAGE_INTEGRATION") {
                        vm.assetAcceptType = config.item.imageAssetAccept;
                        vm.assetMaxSize = config.item.imageMaxSize;
                        vm.assetTypeId = vm.item.assets[0].assetTypeId
                    } else if (vm.selectedItemType.labelText == "VIDEO_QUESTIONS") {
                        vm.assetAcceptType = config.item.videoAssetAccept;
                        vm.assetMaxSize = config.item.videoMaxSize;
                        vm.assetTypeId = vm.item.assets[0].assetTypeId
                    } else if (vm.selectedItemType.labelText == "GRAPHIC_OPTION") {
                        vm.assetAcceptType = config.item.graphicAssetAccept;
                        vm.assetMaxSize = config.item.graphicMaxSize;
                        //vm.assetTypeId = vm.item.value[0].assetTypeId
                    } else if (vm.selectedItemType.labelText == "CLINICAL_SYMPTOMS" || vm.selectedItemType.labelText == "MEDICAL_CASE") {
                        vm.assetAcceptType = config.item.medcaseAssetAccept;
                        vm.assetMaxSize = config.item.medcaseMaxSize;
                        vm.assetTypeId = angular.isDefined(vm.item.assets) ? vm.item.assets[0].assetTypeId : '';
                        vm.item.assets = vm.item.assets || [];
                        vm.itemDetailShowLoader = true;
                        var params = { userId: $rootScope.userId, parent: vm.id }; //Add userId in request param
                        itemsService.getItemsDetails(params).then(function(response) {
                            console.log(response)
                            vm.itemChildDetails = response.results.data;
                            vm.itemDetailShowLoader = false;
                        });
                    }

                    vm.item.parent = $rootScope.$stateParams.parentId;

                    vm.item.parentItemType = $rootScope.$stateParams.parentItemType;
                    assignAnswerTemplate(); //UI change based on selected item type.
                    if (vm.item.parent != '' || vm.item.parent != '0') {
                        itemsService.getItemById(vm.item.parent).then(function(response) {

                            if (response.status === 200) {
                                if (response.data.version == 1 && response.data.versionsList.length > 1) //Check to avoid editing older version of item
                                    vm.pageError = true;
                                else
                                    vm.parentItem = response.data;
                            } else if (response.status === 404) { //Error in case of data not found on server
                                if (response.data.code == "2007") {
                                    vm.pageError = true;
                                }

                            }
                        });
                    }
                    //vm.item.metadataPrev = vm.item.metadataAssoc ;
                   
                    $log.debug(vm.item);
                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "2007") {
                        vm.pageError = true;
                    }
                }
                vm.showLoader = false;
            });

            //Get remediation type details from server or from local storage
            if ($localStorage.remediationTypeDetails) {
                vm.remediationTypeDetails = $localStorage.remediationTypeDetails;
            } else {
                itemsService.getRemediationTypesList().then(function(response) {
                    $localStorage.remediationTypeDetails = vm.remediationTypeDetails = response.data;
                });
            }

        } else if (vm.actionType == "create") { //List of actions related to create page
            console.log($rootScope.$stateParams.parentId + '--' + $rootScope.$stateParams.parentItemType)
            vm.item.metadataAssoc = {}, vm.item.choiceInteraction = {}, vm.item.modelFeedback = [], vm.item.assets = {}, vm.item.selectedMetaDetails = [];
            vm.showLoader = false;
            vm.item.version = 1;
            vm.item.status = "Authoring";
            vm.pageTitle = "PAGE_TITLE.ITEM_CREATE";
            vm.difficultyMin = vm.item.difficulty = config.item.itemDifficultyMin;
            vm.difficultyMax = config.item.itemDifficultyMax;
            vm.scoreMin = vm.item.score = config.item.itemScoreMin;
            vm.scoreMax = config.item.itemScoreMax;
            vm.item.choiceInteraction.simpleChoices = [{ correct: true }];
            vm.item.remediationLinks = []; //[{ linkTypeId: 1, linkText1: "", linkText2: "", linkText3: "" }];
            vm.customValidation = { valid: true, minChoice: false, minRemedy: false, minCorrectAnswer: false, minAsset: false, invalidAsset: false };
            vm.item.parent = $rootScope.$stateParams.parentId;
            vm.item.parentItemType = $rootScope.$stateParams.parentItemType;

            //Restrict child item type creation for MEDICAL_CASE/CLINICAL_SYMPTOMS
            if (vm.item.parentItemType != "" && (vm.item.parentItemType == "MEDICAL_CASE" || vm.item.parentItemType == "CLINICAL_SYMPTOMS")) {
                vm.item.flowType = $rootScope.$stateParams.flowType; //Flow type is added to check creation of child comes from which page. Either its fresh create of parent or edit of parent page.
                vm.itemTypeDetails = $filter("filter")(vm.itemTypeDetails, function(item) { //Restrict child item type creation for MEDICAL_CASE/CLINICAL_SYMPTOMS
                    return (item.labelText == "CHOICE_MULTIPLE" || item.labelText == "MULTIPLE_CHOICE") //Filter only these MULTIPLE_CHOICE/CHOICE_MULTIPLE types
                }, true);
                //call getitembyid api tofetch parent question title
                if (vm.item.parent != '' || vm.item.parent != '0') {
                    itemsService.getItemById(vm.item.parent).then(function(response) {
                        console.log(response)
                        if (response.status === 200) {
                            if (response.data.version == 1 && response.data.versionsList.length > 1) //Check to avoid editing older version of item
                                vm.pageError = true;
                            else
                                vm.parentItem = response.data;
                        } else if (response.status === 404) { //Error in case of data not found on server
                            if (response.data.code == "2007") {
                                vm.pageError = true;
                            }

                        }
                    });
                }
            }
            vm.selectedItemType = vm.itemTypeDetails[0]; //Make first type in the itemtype list as default/preselected item type
            vm.item.itemType = vm.selectedItemType.itemTypeId;
            assignAnswerTemplate(); //UI change based on selected item type.
            //Get remediation type details from server or from local storage
            if ($localStorage.remediationTypeDetails) {
                vm.remediationTypeDetails = $localStorage.remediationTypeDetails;
            } else {
                itemsService.getRemediationTypesList().then(function(response) {
                    $localStorage.remediationTypeDetails = vm.remediationTypeDetails = response.data;
                });
            }
        } else if (angular.isUndefined(vm.id)) {
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("items.list")
        }

        //Items list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.itemTablePipe = function(tableState, isSearch, isClear) {
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.itemTableState = {};
                vm.searchFilter.label = "";
                vm.searchFilter.identifier = "";
                vm.searchFilter.itemTypeId = "All";
                vm.searchFilter.status = "";
                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};
                vm.item.selectedMetaDetails = [];
                $scope.itemForm.clearFilterSearch();
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.itemTableState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.label) && vm.searchFilter.label != "")
                    searchParams.label = vm.searchFilter.label;

                //Add entered item identifier in the searchParams
                if (angular.isDefined(vm.searchFilter.identifier) && vm.searchFilter.identifier != "")
                    searchParams.identifier = vm.searchFilter.identifier;

                //Add chosen item type in the searchParams
                if (angular.isDefined(vm.searchFilter.itemTypeId) && vm.searchFilter.itemTypeId !== "All")
                    searchParams.itemTypeId = vm.searchFilter.itemTypeId;

                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc

                //Add chosen item status in the searchParams
                if (angular.isDefined(vm.searchFilter.status) && vm.searchFilter.itemTypeId != "")
                    searchParams.status = vm.searchFilter.status;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.label = tableState.search.label;
                vm.searchFilter.identifier = tableState.search.identifier;
                vm.searchFilter.status = tableState.search.status;
                vm.searchFilter.itemTypeId = tableState.search.itemTypeId || "All";
                vm.searchFilter.metadataAssoc = tableState.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableState.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }
            console.log(vm.searchFilter.selectedMetaDetails); 
            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+label";
                tableState.sort.predicate = "label";
            }
            if (isClear === true) {
                params.sort = "+label";
                tableState.sort.predicate = "label";
                tableState.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
            $log.debug("Passed Parameters:" + JSON.stringify(params))

            //call item service to get list of item details 
            itemsService.getItemsDetails(params).then(function(response) {
                vm.itemDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.table.tableStateScopeCopy = tableState;
                $localStorage.itemTableState = angular.copy(tableState)
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)
            });
        };

        //Items list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.itemNonAssociateTablePipe = function(tableState, isSearch, isClear) {

            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.itemNonAssociateTableState = {};
                vm.searchFilter.bankNameNonAsso = "";

                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};
                vm.questionCheck = [];
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemNonAssociateTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.itemNonAssociateTableState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.bankNameNonAsso) && vm.searchFilter.bankNameNonAsso != "")
                    searchParams.bankName = vm.searchFilter.bankNameNonAsso;


                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.bankNameNonAsso = tableState.search.bankName;

                vm.searchFilter.metadataAssoc = tableState.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableState.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+bankName";
                tableState.sort.predicate = "bankName";
            }
            if (isClear === true) {
                params.sort = "+bankName";
                tableState.sort.predicate = "bankName";
                tableState.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))


            params.userId = $rootScope.userId;
            params.itemId = vm.id;
            params.associated = 0;
            //call item service to get list of item details 

            itemsService.getItemAssociatedDetails(params, tableState).then(function(response) {
                if (angular.isDefined(response.results.data)) {
                    vm.itemBankDetails = response.results.data;
                    vm.table.totalRecords = response.results.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = tableState;
                    $localStorage.itemNonAssociateTableState = angular.copy(tableState)
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.results.total)

                }
            });
        }

        //This will be called for displaying associated records.
        vm.itemAssociateTablePipe = function(tableState1, isSearch, isClear) {

            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.itemAssociateTableState = {};
                vm.searchFilter.bankName = "";

                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};
                vm.questionUnCheck = [];
            }
            //Check if any local tables exist
            //And check if vm.table1.tableStateScopeCopy1 is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemAssociateTableState && angular.isUndefined(vm.table1.tableStateScopeCopy1))
                angular.extend(tableState1, $localStorage.itemAssociateTableState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState1.pagination.start = 0;
                vm.showLoader = true;

                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.bankName) && vm.searchFilter.bankName != "")
                    searchParams.bankName = vm.searchFilter.bankName;


                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState1.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState1.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState1.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.bankName = tableState1.search.bankName;

                vm.searchFilter.metadataAssoc = tableState1.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableState1.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState1.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState1.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState1.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState1.sort.predicate))
                params.sort = (tableState1.sort.reverse ? '-' : '+') + tableState1.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+bankName";
                tableState1.sort.predicate = "bankName";
            }
            if (isClear === true) {
                params.sort = "+bankName";
                tableState1.sort.predicate = "bankName";
                tableState1.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table1.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))


            params.userId = $rootScope.userId;
            params.itemId = vm.id;
            params.associated = 1;
            //call item service to get list of itembank associated details 
            if (vm.activeTabIndex == 0) {
                itemsService.getItemAssociatedDetails(params).then(function(response) {

                    if (angular.isDefined(response.results.data)) {

                        vm.itemBankAssociated = response.results.data;

                        vm.table1.totalRecords = response.results.total;
                        tableState1.pagination.numberOfPages = Math.ceil(response.results.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableState1;
                        $localStorage.itemAssociateTableState = angular.copy(tableState1)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)
                    }
                });
            }
        }

        //Used to add remediation links while creating/updating items 
        vm.addRemediation = function() {
            // if (vm.item.remediationLinks.length === 0) {
            //     //vm.customValidation.valid = true;
            //     vm.customValidation.minRemedy = false;
            // }
            vm.item.remediationLinks = vm.item.remediationLinks || [];
            vm.item.remediationLinks.push({ linkTypeId: 1, linkText1: "", linkText2: "", linkText3: "" });

        }

        //Used to delete remediation links while creating/updating items 
        vm.deleteRemediation = function(index) {
            vm.item.remediationLinks.splice(index, 1);
        }

        //Used to add answer choices while creating/updating items 
        vm.addChoice = function() {
            if (vm.item.choiceInteraction.simpleChoices.length === 0) {
                vm.customValidation.valid = true;
                vm.customValidation.minChoice = false;
            }
            vm.item.choiceInteraction.simpleChoices = vm.item.choiceInteraction.simpleChoices || []
            vm.item.choiceInteraction.simpleChoices.push({ correct: false, label: "", rationale: "" });
        }

        //Used to delete answer choices while creating/updating items 
        vm.deleteChoice = function(index) {
            vm.item.choiceInteraction.simpleChoices.splice(index, 1);
            if (vm.item.choiceInteraction.simpleChoices.length === 0) {
                vm.customValidation.valid = false;
                vm.customValidation.minChoice = true;
            }
        }

        //Used to reselect correct answers while updating/other type view pages 
        vm.correctAnswerSelection = function(currentIndex) {
            angular.forEach(vm.item.choiceInteraction.simpleChoices, function(choice, choiceIndex) {
                if (currentIndex === choiceIndex)
                    choice.correct = true;
                else
                    choice.correct = false;
            })
        }

        //Used to check validation of itemForm before creation/updation of item
        //Includes custom validation for few inputs 
        var validateFormData = function() {
            //Validation for lookup empty values
            angular.forEach(vm.item.selectedMetaDetails, function(metadata, index) {
                if ((metadata.tagTypeId == 2 || metadata.tagTypeId == 3) && (angular.isUndefined(vm.item.metadataAssoc[metadata.id]) || vm.item.metadataAssoc[metadata.id].length === 0))

                {
                    $scope.itemForm['metavalue' + index].$setValidity('minValue', false);
                }


            });
            //Validate for empty Asset
            if (vm.selectedItemType.labelText == 'IMAGE_INTEGRATION' || vm.selectedItemType.labelText == 'VIDEO_QUESTIONS' || vm.selectedItemType.labelText == 'CLINICAL_SYMPTOMS' || vm.selectedItemType.labelText == 'MEDICAL_CASE') {
                if (angular.isDefined(vm.item.errFiles) && vm.item.errFiles.length != 0) {
                    vm.customValidation.valid = false;
                    vm.customValidation.invalidAsset = true;
                } else if ((angular.isUndefined(vm.item.assets) || vm.item.assets.length == 0) && vm.selectedItemType.labelText != 'CLINICAL_SYMPTOMS' && vm.selectedItemType.labelText != 'MEDICAL_CASE') {
                    vm.customValidation.valid = false; //Check minimum asset for video/audio items
                    vm.customValidation.minAsset = true;
                }
            } else if (vm.selectedItemType.labelText == 'GRAPHIC_OPTION') {
                if (vm.item.choiceInteraction.simpleChoices.length !== 0) {
                    angular.forEach(vm.item.choiceInteraction.simpleChoices, function(choice) {
                        if (angular.isUndefined(choice.value) || choice.value.length == 0) {
                            vm.customValidation.valid = false;
                            choice.minAsset = true;
                        }
                    });
                }
            }
            //Ignore choice related validations for CLINICAL_SYMPTOMS & MEDICAL_CASE qquestions
            if (vm.selectedItemType.labelText != 'CLINICAL_SYMPTOMS' && vm.selectedItemType.labelText != 'MEDICAL_CASE') {
                var minCorrectCheck = false;
                angular.forEach(vm.item.choiceInteraction.simpleChoices, function(choice) {
                    if (angular.isDefined(choice.correct) && choice.correct == true)
                        minCorrectCheck = true;

                    if (vm.selectedItemType.labelText == 'GRAPHIC_OPTION') {
                        if (angular.isUndefined(choice.value) || choice.value.length == 0) {
                            vm.customValidation.valid = false;
                            choice.minAsset = true;
                        }
                    }
                });

                if (!minCorrectCheck) {
                    vm.customValidation.valid = false;
                    vm.customValidation.minCorrectAnswer = true;
                } else {
                    vm.customValidation.valid = true;
                    vm.customValidation.minCorrectAnswer = false;
                }

                //Validation for empty answer choice details
                if (vm.item.choiceInteraction.simpleChoices.length === 0) {
                    $log.debug("no answer choice")
                    vm.customValidation.valid = false;
                    vm.customValidation.minChoice = true;
                }
            }
            //If form is invalid open all the accordions
            if (!$scope.itemForm.$valid || vm.customValidation.minChoice || vm.customValidation.minCorrectAnswer || vm.customValidation.minAsset || vm.customValidation.invalidAsset) {
                angular.forEach(vm.accordion, function(isOpen, accodianName) {
                    vm.accordion[accodianName] = true;
                });
                return false;
            } else
                return true;
        };
        //Used to create/update items
        vm.createItem = function(addChild) {
            vm.isFormSubmitted = true;
            vm.item.userId = $rootScope.userId;

            //Check whether the form is valid and other custom validations.
            if (validateFormData()) {
                var itemData = vm.item;
                delete itemData.selectedMetaDetails;
                vm.isSubmitDisabled = true; //disable save buuton
                var redirectState = 'items.list' //default redirect state
                var stateParams = {};
                $log.debug(angular.toJson(vm.item, true))

                //Calling create item api and checking response.If status is true return to listing page else display error message.
                if (angular.isUndefined(vm.id) && vm.actionType == "create") { //Check for create/update action
                    itemsService.insertItem(itemData).then(function(response) {
                        if (response.status === 201) {
                            // Whenever page is redirected to create page.
                            // We need to pass current action as flow type to differentiate the new item creation flow comes from edit/create page
                            if (addChild) { //When clicks on save and add question button
                                redirectState = "items.create"; //redirect to create
                                if (vm.item.parent == 0 && (vm.selectedItemType.labelText == 'MEDICAL_CASE' || vm.selectedItemType.labelText == 'CLINICAL_SYMPTOMS')) {
                                    stateParams = { parentId: response.data, parentItemType: vm.selectedItemType.labelText, flowType: vm.actionType }; //When clicks save and add while creating parent. Add details as param.
                                } else if (vm.item.parent != 0) {
                                    stateParams = { parentId: response.data, parentItemType: vm.item.parentItemType, flowType: vm.actionType }; //When clicks save and add while creating child of some parent.Add new parent details as state param
                                }
                            }
                            //else if (!addChild && (vm.item.parentItemType == 'MEDICAL_CASE' || vm.item.parentItemType == 'CLINICAL_SYMPTOMS')) {
                            //     //When clicking just save from child question shud come back to parent edit. This is applicable only for MEDICAL_CASE/CLINICAL_SYMPTOMS
                            //     redirectState = "items.edit";
                            //     stateParams = { id: response.data }; //after updating children coming back to new parent id edit page
                            // }
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', redirectState, false, stateParams);
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', redirectState, false);
                        }
                    });
                } else {
                    //Calling update item api and checking response
                    itemsService.updateItem(itemData, vm.id).then(function(response) {
                        //For normal update redirects to items.list page
                        //Below code for other update scenarios in MEDICAL_CASE/CLINICAL_SYMPTOMS
                        if (addChild) { //When clicking save and add question. This will handle edit page in both parent and child
                            redirectState = "items.create";
                            var itemType = (vm.selectedItemType.labelText != "") ? vm.selectedItemType.labelText : vm.item.parentItemType; //based on parent or child edit page gets parent question type.
                            stateParams = { parentId: response.data, parentItemType: itemType, flowType: vm.actionType };
                        } else if (!addChild && (vm.item.parentItemType == 'MEDICAL_CASE' || vm.item.parentItemType == 'CLINICAL_SYMPTOMS')) {
                            //When clicking just save from child question shud come back to parent edit. This is applicable only for MEDICAL_CASE/CLINICAL_SYMPTOMS
                            redirectState = "items.edit";
                            stateParams = { id: response.data, parentId: 0, parentItemType: "" }; //after updating children coming back to new parent id edit page
                        }
                        if (response.status === 204 || response.status === 201) { //On successfull update
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.EDIT_SUCCESS', redirectState, false, stateParams);
                        } else { //On update failure
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.EDIT_FAILED', 'items.list', false);
                        }
                    });
                }
            }
        };
        //When user clicks the cancel button in different scenarios in create item page
        vm.cancelCreate = function() {
            if ($rootScope.$stateParams.parentId != 0) //Cancel during child creation takes them back to parent edit
                $rootScope.$state.go("items.edit", { id: $rootScope.$stateParams.parentId, parentId: 0, parentItemType: "" });
            else //Else normal scenario takes to item list page
                $rootScope.$state.go("items.list");
        };
        //When user clicks the cancel button in different scenarios in view item page
        vm.cancelView = function() {
            if ($rootScope.$stateParams.parentId != 0 && $rootScope.previousState != "" && $rootScope.previousStateParams != {})
                $rootScope.$state.go($rootScope.previousState, $rootScope.previousStateParams); //Cancel during view child item takes them back to parent view
            else //Else normal scenario takes to item list page
                $rootScope.$state.go("items.list");
        };
        //Check the condition to enable save and add button in create/edit
        vm.enableAddChild = function() {
            if (vm.actionType == "create") { //Condtions to enable while create
                if ($rootScope.$stateParams.parentId == 0 && angular.isDefined(vm.selectedItemType) && (vm.selectedItemType.labelText == 'MEDICAL_CASE' || vm.selectedItemType.labelText == 'CLINICAL_SYMPTOMS'))
                    return true; //Enable add child button for parent question creation of both MEDICAL_CASE & CLINICAL_SYMPTOMS
                else if ($rootScope.$stateParams.parentId != 0 && $rootScope.$stateParams.parentItemType == 'MEDICAL_CASE')
                    return true; //Enable add child button only for medical case. This is creating another child while in the process of creating child.
                else
                    return false; //Disable this button for other type create
            } else if (vm.actionType == "edit") { //Condtions to enable while edit
                if (angular.isDefined(vm.selectedItemType) && vm.selectedItemType.labelText == 'MEDICAL_CASE')
                    return true; //Enable while editing MEDICAL_CASE question
                else if (angular.isDefined(vm.selectedItemType) && vm.selectedItemType.labelText == 'CLINICAL_SYMPTOMS' && angular.isDefined(vm.itemChildDetails) && vm.itemChildDetails.length == 0) {
                    return true; //Enable while editng CLINICAL_SYMPTOMS questions and number of child questions shud be 0 for CLINICAL_SYMPTOMS
                } else
                    return false //Disable while editing other type questions
            }
        };
        //Up/Down re-ordering child items
        vm.reorderChild = function(originIndex, destinationIndex) {
            //Re-order list in the UI
            var tmp = {};
            tmp = vm.itemChildDetails[destinationIndex];
            vm.itemChildDetails[destinationIndex] = vm.itemChildDetails[originIndex];
            vm.itemChildDetails[originIndex] = tmp;
            //Re-order item id to update the server
            var tmpOrder;
            tmpOrder = vm.item.childOrder[destinationIndex];
            vm.item.childOrder[destinationIndex] = vm.item.childOrder[originIndex];
            vm.item.childOrder[originIndex] = tmpOrder;
        };
        //Handles asset upload process for IMAGE/VIDEO/GRAPHIC item types
        vm.itemAssetUpload = function(choiceDetails, files, errFiles) {
            if (vm.selectedItemType.labelText == "GRAPHIC_OPTION") {
                choiceDetails.value = {};
                choiceDetails.errFiles = errFiles[0];
            } else {
                vm.item.assets = [];
                vm.item.errFiles = errFiles;
            }

            angular.forEach(files, function(file) {
                var fileType = file.type.split("/")[0];
                var fileExt = file.type.split("/")[1];

                var itemAssetAccept = { "IMAGE_INTEGRATION": ["image"], "VIDEO_QUESTIONS": ["video"], "GRAPHIC_OPTION": ["image", "video", "audio"], "CLINICAL_SYMPTOMS": ["image", "video"], "MEDICAL_CASE": ["image", "video"] }
                var itemAssetExt = vm.assetAcceptType.split(',');
                //Check for valid file type and extension
                if (itemAssetAccept[vm.selectedItemType.labelText].indexOf(fileType) != -1 && itemAssetExt.indexOf('.' + fileExt) != -1) {
                    var assetTypeId;
                    if (fileType == 'video')
                        assetTypeId = config.item.videoAssetId;
                    else if (fileType == 'audio')
                        assetTypeId = config.item.audioAssetId;
                    else if (fileType == 'image')
                        assetTypeId = config.item.imageAssetId;

                    if (vm.selectedItemType.labelText == "GRAPHIC_OPTION") {
                        file.assetTypeId = assetTypeId;
                        choiceDetails.value = file
                    } else {
                        vm.assetTypeId = file.assetTypeId = assetTypeId;
                        vm.item.assets.push(file);
                    }
                    file.fileName = file.name;
                    var fileReader = new FileReader();
                    fileReader.readAsDataURL(file);
                    vm.isSubmitDisabled = true;
                    fileReader.onload = function(e) {
                        var dataUrl = e.target.result;
                        var base64Data = dataUrl.substr(dataUrl.indexOf('base64,') + 'base64,'.length);
                        file.upload = Upload.http({
                            url: config.apiUrl + 'assettempupload',
                            data: { file: base64Data, filename: file.name }
                        });

                        file.upload.then(function(response) {
                            $log.debug(file)
                            vm.isSubmitDisabled = false;
                            $timeout(function() {
                                file = angular.extend(file, response.data);
                                vm.customValidation.valid = true;
                                vm.customValidation.minAsset = false;
                                if (vm.selectedItemType.labelText == "GRAPHIC_OPTION")
                                    choiceDetails.minAsset = false;
                            });
                        }, function(response) {
                            if (response.status < 0) {
                                $log.debug("Upload asset error", response)
                            }
                        }, function(evt) {
                            file.progress = Math.min(100, parseInt(100.0 *
                                evt.loaded / evt.total));
                        });
                    }
                } else {
                    if (vm.selectedItemType.labelText == "GRAPHIC_OPTION") {
                        choiceDetails.errFiles = file;
                    } else {
                        vm.item.errFiles.push(file);
                    }
                    file.$error = $filter('translate')('ERRORS.INVALID_ASSET');
                }
            });
        };

        //Hide/Show Asset Preview 
        vm.togglePreviewAsset = function(assetDetails) {
            $log.debug(vm.selectedItemType)
            $log.debug(assetDetails)
            if (!vm.previewAsset) {
                if (vm.selectedItemType.labelText == "GRAPHIC_OPTION") {
                    vm.assetTypeId = assetDetails.assetTypeId;
                }
                vm.previewAsset = true;
                vm.previewImage = '/' + assetDetails.assetPath + "/" + assetDetails.assetName;
                $log.debug(vm.previewImage);
            } else {
                vm.previewAsset = false;
                if (vm.assetTypeId == 2) {
                    var player = angular.element(document.querySelector('#previewAudio'))[0];
                    player.pause();
                } else if (vm.assetTypeId == 3) {
                    var player = angular.element(document.querySelector('#previewVideo'))[0];
                    player.pause();
                }
            }
        }

        //Block of actions during item type change
        vm.changeItemType = function(typeId) {
            vm.selectedItemType = $filter("filter")(vm.itemTypeDetails, { itemTypeId: typeId }, true)[0];
            if (vm.selectedItemType.labelText == "TRUE_FALSE")
                vm.item.choiceInteraction.simpleChoices = [{ correct: true, label: "True" }, { correct: false, label: "False" }];
            else
                vm.item.choiceInteraction.simpleChoices = [{ correct: true }];
            vm.item.remediationLinks = vm.item.remediationLinks || [];

            if (vm.selectedItemType.labelText == "IMAGE_INTEGRATION") {
                vm.assetAcceptType = config.item.imageAssetAccept;
                vm.assetMaxSize = config.item.imageMaxSize;
                vm.item.assets = [], vm.item.errFiles = [];
            } else if (vm.selectedItemType.labelText == "VIDEO_QUESTIONS") {
                vm.assetAcceptType = config.item.videoAssetAccept;
                vm.assetMaxSize = config.item.videoMaxSize;
                vm.item.assets = [], vm.item.errFiles = [];
            } else if (vm.selectedItemType.labelText == "GRAPHIC_OPTION") {
                vm.assetAcceptType = config.item.graphicAssetAccept;
                vm.assetMaxSize = config.item.graphicMaxSize;
                vm.item.assets = [], vm.item.errFiles = [];
            } else if (vm.selectedItemType.labelText == "CLINICAL_SYMPTOMS" || vm.selectedItemType.labelText == "MEDICAL_CASE") {
                vm.assetAcceptType = config.item.medcaseAssetAccept;
                vm.assetMaxSize = config.item.medcaseMaxSize;
                vm.item.assets = [], vm.item.errFiles = [];
            }
            assignAnswerTemplate();
        };
        //Used to get details of different version of item from view/delete page 
        vm.changeVersion = function() {
            vm.showLoader = true;
            vm.isSubmitDisabled = false;
            //Get item detail from server
            itemsService.getItemById(vm.item.id, vm.item.version).then(function(response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.item = response.data;
                    vm.item.parent = 0; //To show version list back again. If vm.item.parent is other than zero then it will be child view so version list will be hidden

                    if (vm.item.labelText == "CLINICAL_SYMPTOMS" || vm.item.labelText == "MEDICAL_CASE") {
                        vm.getchildItemDetails();
                    } else if (vm.actionType == "view") {
                        //In case of multiple correct answers create a comma seperated answer string
                        angular.forEach(vm.item.choiceInteraction.simpleChoices, function(choice, key) {
                            if (choice.correct == true) {
                                if (angular.isDefined(vm.item.correctAnswer))
                                    vm.item.correctAnswer = vm.item.correctAnswer + ',' + choice.label;
                                else
                                    vm.item.correctAnswer = choice.label;
                            }
                        });
                        //Assign basic asset details which applicable for asset related wuestion types
                        if ((vm.item.labelText == 'IMAGE_INTEGRATION' || vm.item.labelText == 'VIDEO_QUESTIONS' || vm.item.labelText == 'GRAPHIC_OPTION') && angular.isDefined(vm.item.assets)) {
                            vm.assetTypeId = vm.item.assets["0"].assetTypeId;
                            vm.itemAssetPath = '/' + vm.item.assets["0"].assetPath + "/" + vm.item.assets["0"].assetName;
                        }
                    }
                    $log.debug(vm.metadata);
                } else if (response.status === 404) {
                    if (response.data.code == "2007") {
                        vm.pageError = true;
                    }
                }
                vm.showLoader = false;
            });
        };
        //Get child item details from the server
        vm.getchildItemDetails = function() {
            var params = { userId: $rootScope.userId, parent: vm.item.id, sort: "+childOrder" };
            itemsService.getItemsDetails(params).then(function(response) {
                vm.itemChildDetails = response.results.data;
                vm.itemDetailShowLoader = false;
            });
        };

        //Used to delete current version of item/All versions of item.
        vm.deleteItem = function(isDeleteAll) {
            vm.isSubmitDisabled = true;
            //var itemId = (isDeleteAll) ? vm.item.id : vm.item.id
            var params = { isDeleteAll: isDeleteAll, version: vm.item.version }
            itemsService.deleteItem(vm.item.id, params).then(function(response) {
                if (isDeleteAll && response.status == 200) {
                    vm.otherInfo = true;
                    vm.item.deletedInfo = response.data;
                } else if (!isDeleteAll && response.status == 204) {
                    vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.DELETE_SUCCESS', 'items.list', false);
                } else {
                    vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.DELETE_FAILED', 'items.list', false);
                }
            });
        }

        //Used to publish current item
        vm.publishItem = function() {
            vm.isSubmitDisabled = true;
            //calling publish item api to publish the item
            itemsService.publishItem(vm.item.id).then(function(response) {
                if (response.status === 200)
                    vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.PUBLISH_SUCCESS', 'items.list', false);
                else
                    vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.PUBLISH_FAILED', 'items.list', false);

            });
        }

        //Used for alerting on success/failure
        vm.alertConfig.timeOutAlert = function(cssClass, alertMsg, redirectState, isList, stateParams) {
            $window.scroll(0, 0);
            console.log(redirectState)
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (!isList) { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function() {
                    vm.alertConfig.show = false; //Hides alert
                    console.log($rootScope.$stateParams)
                    console.log(stateParams)
                    if (redirectState == $rootScope.currentState && $rootScope.$stateParams.parentId == stateParams.parentId)
                        $rootScope.$state.reload();
                    else
                        $rootScope.$state.go(redirectState, stateParams); //Redirects to provided state
                }, config.alertTimeOut);
            }
        }

        //called on clicking submit of question association
        vm.associateQuestionBank = function(validateOnly) {
            $log.debug(vm.activeTabIndex);
            var params = {};
            if (vm.activeTabIndex == 0) {

                vm.isSubmitDisabled = 1;
                var alertMsg = 'ALERTS.DISASSOCIATE_SUCCESS';

                //validating id atleast one question bank is selected
                //validating id atleast one question bank is selected

                vm.associatedErrorMsg = '';
                vm.errorMsg = '';

                params.itemBankId = [];
                angular.forEach(vm.questionUnCheck, function(value, key) {
                    if (value == true) //get all records to be removed
                        params.itemBankId.push(key);
                });
                var flag = params.itemBankId.length;
                if (flag == 0) {
                    vm.associatedErrorMsg = 'ERRORS.SELECT_QUESTIONBANK';
                    return false;
                } else {
                    params.itemBankId = params.itemBankId.join(',');

                    params.userId = $rootScope.userId;
                    params.associated = 0;
                    $log.debug(params.itemBankId);
                }

            } else if (vm.activeTabIndex == 1) {
                var alertMsg = 'ALERTS.ASSOCIATE_SUCCESS';
                vm.isRemoveDisabled = 1;

                //validating id atleast one question bank is selected

                vm.errorMsg = '';
                vm.associatedErrorMsg = '';
                params.itemBankId = [];
                $log.debug(vm.questionCheck);
                angular.forEach(vm.questionCheck, function(value, key) {
                    if (value == true) //get all records to be added
                        params.itemBankId.push(key);
                });
                var flag = params.itemBankId.length;
                if (flag == 0) {
                    vm.errorMsg = 'ERRORS.SELECT_QUESTIONBANK';
                    return false;
                } else {
                    params.itemBankId = params.itemBankId.join(',');
                    params.userId = $rootScope.userId;
                    params.associated = 1;
                    $log.debug(params.itemBankId);
                }
            }

            //call api to save question association
            if (flag > 0) {
                if (validateOnly != 1) {
                    itemsService.associateItem(vm.id, params).then(function(response) {
                        if (response.status == 204) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', alertMsg, '', true);

                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-success', alertMsg, '', true);
                        }
                        //refresh tab based on associate or nonassociate
                        if (vm.activeTabIndex == 0) {
                            vm.itemAssociateTablePipe(vm.table.tableStateScopeCopy, true);
                            vm.questionUnCheck = []; //Need to clear checked marks in  associated tab records
                        }
                        if (vm.activeTabIndex == 1) {
                            vm.activeTabIndex = 0;
                            vm.itemAssociateTablePipe(vm.table.tableStateScopeCopy, true);
                            vm.questionCheck = []; //Need to clear checked marks in non associated tab records
                        }
                    });
                }

            }
        }

        var SnomedModalCtrl = function($scope, $uibModalInstance, taxanomyIds) {
            var $ctrl = this;
            $ctrl.showModalLoader = true;
            metadataService.getSnomedDetails(taxanomyIds).then(function(response) {
                $ctrl.snomedDetails = response.data;
                $ctrl.showModalLoader = false;
            });
            $ctrl.cancel = function() {
                $uibModalInstance.dismiss('cancel');
            };
        }
        SnomedModalCtrl.$inject = ['$scope', '$uibModalInstance', 'taxanomyIds'];

        vm.openSnomedDetails = function(metadata) {
            if (angular.isDefined(vm.item.metadataAssoc[metadata.id]) && vm.item.metadataAssoc[metadata.id].length > 0) {
                var selectedValue = [];
                angular.forEach(vm.item.metadataAssoc[metadata.id], function(value, key) {
                    selectedValue.push(value.id)
                });
                var modalInstance = $uibModal.open({
                    animation: true,
                    templateUrl: '/app/modules/metadata/partials/snomed-details-modal.html',
                    controller: SnomedModalCtrl,
                    controllerAs: '$ctrl',
                    size: 'lg',
                    resolve: {
                        taxanomyIds: function() {
                            return selectedValue.join();
                        }
                    }
                });
            }
        }
    }]);
})();

(function() {
    'use strict';

    angular.module('app.items').controller('ItemTypesController', ['$rootScope', 'itemsService', function($rootScope, itemsService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;

        vm.templateUrl = "app/modules/items/partials/preview/" + vm.id + ".html";
        vm.backbtn = "app/modules/items/partials/preview/backbtn.html";
        vm.table = vm.searchFilter = vm.metadata = {};
        vm.table.totalRecords = 0;
        vm.showLoader = true;
        //Check for the data and assign respective data
        if ($rootScope.$state.current.name == "itemtype.preview" && $rootScope.$stateParams.id !== undefined) {

            vm.itemTypeId = $rootScope.$stateParams.id;
        } else {
            //call itemservice to get list of list of item types
            itemsService.getItemTypesList().then(function(response) {
                vm.itemTypeDetails = response.data;
                vm.showLoader = false;
                vm.table.totalRecords = response.data.length;
            });
            vm.table.dataPerPage = 10; //Default data per page

            vm.table.dataPerPageOptions = [10, 20, 30]; //Default date per page options

        }
    }]);

})();

(function () {
    'use strict';

    angular.module('app.metadata').controller('MetadataController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'metadataService', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, metadataService) {
        var vm = this;
        vm.actionType = $rootScope.$state.current.name.split(".")[1]
        vm.id = $rootScope.$stateParams.id;
        vm.showLoader = true;
        vm.table = vm.searchFilter = vm.metadata = {};
        var metadataPostParam = {};
        vm.institutionSelected = {};
        vm.metadata.mandatory = 'yes';
        vm.metadata.multiselect = 'no';
        vm.tagValue = [];
        vm.alertConfig = {'show': false}
        vm.table.totalRecords = 0;
        vm.institutions = {};
        vm.institutionSelected = {};
        vm.institutionDrop = "false";
        vm.pageError = false;
        /*Datepicker configuration*/
        vm.dateOptions = {
            dateDisabled: false
        };
        vm.popup = {};
        vm.openDatePicker = function (index) {
            vm.popup[index] = {};
            vm.popup[index].opened = true;
        };

        /*End of Datepicker configuration*/

        //Get Metadata tag datatypes from server or from local storage
        if ($localStorage.metatagDataTypes) {
            vm.metadataDataTypes = $localStorage.metatagDataTypes;
            vm.metadata.selectedOptionMetadataType = vm.metadataDataTypes[0];

        } else {
            //call metadata service to get list of metadatatag datatypes    
            metadataService.getMetadataDataTypesList().then(function (response) {
                $localStorage.metatagDataTypes = vm.metadataDataTypes = response.data;
                vm.metadata.selectedOptionMetadataType = vm.metadataDataTypes[0];
            });
        }

        //Get Metadata types from server or from local storage
        if ($localStorage.metadataTypes) {
            vm.metadataTypes = $localStorage.metadataTypes;
            vm.metadata.selectedOptionMetadata = vm.metadataTypes[0];
        } else {
            //call metadata service to get list of metadata types   
            metadataService.getMetadataTypesList().then(function (response) {
                $localStorage.metadataTypes = vm.metadataTypes = response.data;
                vm.metadata.selectedOptionMetadata = vm.metadataTypes[0];
            });
        }
        //Get institutions from server or from local storage
        if ($localStorage.institutions) {
            vm.institutions = $localStorage.institutions;
            vm.metadata.selectedOptionInstitution = vm.institutions[0];
        } else {
            //call institution serviceto get list of institution   
            metadataService.getInstitutions().then(function (response) {

                $localStorage.institutions = vm.institutions = response.data;
                vm.metadata.selectedOptionInstitution = vm.institutions[0];

            });
        }
        //Assign default values and perform actions based on actionType 
        if (vm.actionType == "list") {
            if (angular.isDefined($localStorage.metadataTableState) && angular.isDefined($localStorage.metadataTableState.pagination) && angular.isDefined($localStorage.metadataTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.metadataTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page
            vm.searchFilter.metadataType = "All"; //Default search filter value for metadtat type
            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.METADATA_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['metadata'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['metadata'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['metadata'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['metadata'].indexOf('view') !== -1 ? true : false;

            //console.log(vm.permission.indexOf('edit') === -1)

        } else if ((vm.actionType == "view" || vm.actionType == "delete") && angular.isDefined($rootScope.$stateParams.id)) {
            vm.id = $rootScope.$stateParams.id;
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.METADATA_VIEW_LABEL"; //Page title mapped to locale json key of view label
            else
                vm.pageTitle = "PAGE_TITLE.METADATA_DELETE_LABEL"; //Page title mapped to locale json key of delete label

            var params = {};
            params.metadataValueId = 0;
            //Get metadata for the given id by calling metadata/{id} api
            metadataService.getMetadataById(vm.id, params).then(function (response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.metadata = response.data;
                    if (vm.metadata.tagTypeId == 3)
                        vm.selectedNode = vm.metadata.metadataValues[0];
                    $log.debug(vm.metadata);
                } else if (response.status === 404) {
                    if (response.data.code == "1005") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;

            });


        } else if (vm.actionType == "edit" && angular.isDefined($rootScope.$stateParams.id)) {
            vm.pageTitle = "PAGE_TITLE.METADATA_EDIT_LABEL";
            vm.id = $rootScope.$stateParams.id;
            var params = {};
            params.metadataValueId = 0;
            //Fetch metadata for the given id by calling metadata/{id} api
            metadataService.getMetadataById(vm.id, params).then(function (response) {
                $log.debug(response);
                if (response.status === 200) {

                    vm.metadata = response.data;

                    if (vm.metadata.mandatory == true) {
                        vm.metadata.mandatory = 'yes';
                    } else {
                        vm.metadata.mandatory = 'no';
                    }
                    if (vm.metadata.multiselect == true) {
                        vm.metadata.multiselect = 'yes';
                    } else {
                        vm.metadata.multiselect = 'no';
                    }

                    //preselect metadatatype when edit is selected   
                    angular.forEach(vm.metadataDataTypes, function (metadataTypeValues, key) {


                        if (metadataTypeValues.dataTypeId == vm.metadata.dataTypeId) {
                            vm.metadata.selectedOptionMetadataType = metadataTypeValues;
                        }


                    });

                    //preselect metadatatype when edit is selected   
                    angular.forEach(vm.metadataTypes, function (metadataValues, key) {


                        if (metadataValues.tagTypeId == vm.metadata.tagTypeId) {
                            vm.metadata.selectedOptionMetadata = metadataValues;
                        }



                    });

                    //metadataPostParam.tagValue= [];
                    vm.tagValue.splice(0, 1);
                    if (vm.metadata.tagTypeId == 3)
                        vm.selectedNode = vm.metadata.metadataValues[0];
                    angular.forEach(vm.metadata.metadataValues, function (metadataValues, key) {

                        if (vm.metadata.selectedOptionMetadataType.dataTypeId == 3) {
                            metadataValues.value = new Date(metadataValues.value);
                        }

                        vm.tagValue.push({value: metadataValues.value, id: metadataValues.id, nodeStatus: metadataValues.nodeStatus});


                    });


                    //preselect the institution which wer selected while creation/edit
                    angular.forEach(vm.metadata.institutions, function (value, key) {

                        vm.institutionSelected[value.id] = true;

                    });


                    if (angular.isArray(vm.metadata.institutions) && (vm.metadata.institutions.length == vm.institutions.length)) {
                        vm.selectedAll = true;
                    } else {
                        vm.selectedAll = false;
                    }
                    if (vm.metadata.selectedOptionMetadataType.dataTypeId == 1) {
                        vm.regex = '';
                    }
                    if (vm.metadata.selectedOptionMetadataType.dataTypeId == 2) {
                        vm.regex = '^[0-9]+$';

                    }
                    if (vm.metadata.selectedOptionMetadataType.dataTypeId == 3) {
                        vm.regex = '';
                    }


                    //preselect the institution which wer selected while creation/edit
                    angular.forEach(vm.metadata.institutions, function (value, key) {

                        vm.institutionSelected[value.id] = true;
                    });
                    if (angular.isArray(vm.metadata.institutions) && (vm.metadata.institutions.length == vm.institutions.length)) {
                        vm.selectedAll = true;
                    } else {
                        vm.selectedAll = false;
                    }
                } else if (response.status === 404) {
                    if (response.data.code == "1005") {
                        vm.pageError = true;
                    }
                }
                vm.showLoader = false;



            });


        } else if (vm.actionType == "create") {

            vm.showLoader = false;
            vm.pageTitle = "PAGE_TITLE.METADATA_CREATE_LABEL";
            vm.metadata.metadataValues = [];
            vm.selectedNode = {};
            //set institutions checked for all by default
            vm.selectedAll = true;
            angular.forEach(vm.institutions, function (value, key) {
                vm.institutionSelected[value.id] = true;
            });


        } else if (angular.isUndefined(vm.id)) {
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("metadata.list")
        }

        //Metadata list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.metadataTablePipe = function (tableState, isSearch, isClear) {
            var params = {};
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {

                $localStorage.metadataTableState = {};
                vm.searchFilter.metadataName = "";
                vm.searchFilter.metadataDesc = "";
                vm.searchFilter.metadataType = "All";
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.metadataTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.metadataTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                //Add entered metadata name in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataName) && vm.searchFilter.metadataName != "")
                    searchParams.tagName = vm.searchFilter.metadataName;

                //Add entered metadata description in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataDesc) && vm.searchFilter.metadataDesc != "")
                    searchParams.description = vm.searchFilter.metadataDesc;

                //Add chosen metadata type in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataType) && vm.searchFilter.metadataType !== "All")
                    searchParams.tagTypeId = vm.searchFilter.metadataType;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.metadataName = tableState.search.tagName;
                vm.searchFilter.metadataDesc = tableState.search.description;
                vm.searchFilter.metadataType = tableState.search.tagTypeId || "All";
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                //console.log(vm.searchFilter.metadataType)
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by metadata tag name
                params.sort = "+tagName";
                tableState.sort.predicate = "tagName";
            }
            if (isClear === true) {
                params.sort = "+tagName";
                tableState.sort.predicate = "tagName";
                tableState.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
                ;
                //console.log(tableState.sort)
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))

            //call metadata service to get list of metadata details 
            metadataService.getMetadataDetails(params, tableState).then(function (response) {
                //console.log(response.results)
                vm.metadataDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.table.tableStateScopeCopy = $localStorage.metadataTableState = tableState;
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)
            });
        };
        //Deletes the metadata based on metadata id
        vm.deleteMetadata = function () {
            metadataService.deleteMetadata(vm.id).then(function (response) {

                $window.scroll(0, 0);

                if (response.status === 204) {
                    vm.alertConfig.class = 'wk-alert-success';
                    vm.alertConfig.msg = 'ALERTS.DELETE_SUCCESS';
                    vm.alertConfig.show = true;
                    vm.alertConfig.isList = false;
                    $timeout(function () {
                        vm.alertConfig.show = false; //Hides alert
                        $rootScope.$state.go('metadata.list');
                    }, 2000);
                } else if (response.status === 409) {

                    if (response.data.code == "1008") {
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.show = true;
                        vm.alertConfig.msg = "ERRORS.ERROR_DELETING_METADATA";
                    }
                } else {
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = 'ALERTS.DELETE_FAILED';
                    vm.alertConfig.show = true;
                }


            });
        }

        // //Called when alert display time out
        // vm.closeAlert = function() {
        //     vm.alertConfig.show = false; //Hides alert
        //     if (vm.alertConfig.class == "wk-alert-success" && !vm.validationError)
        //         $rootScope.$state.go('metadata.list');
        // };
        //Called when create/update action performed 
        vm.createMetadata = function () {
            //validation on submit for lookup values depending on datatype selected
            metadataPostParam = metadataFormValidation();
            $log.debug(metadataPostParam)
            //calling create metadata api and checking response.If status is true return to listing page else display error message.
            if (metadataPostParam && $scope.metadataForm.$valid) {
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                    metadataService.insertMetadata(metadataPostParam).then(function (response) {

                        $window.scroll(0, 0);
                        if (response.status === 201) {
                            vm.alertConfig.class = 'wk-alert-success';
                            vm.alertConfig.details = 'ALERTS.CREATE_SUCCESS';
                            vm.alertConfig.isList = false;
                            $timeout(function () {
                                vm.alertConfig.show = false; //Hides alert
                                $rootScope.$state.go('metadata.list');
                            }, 2000);
                        } else if (response.status === 409) {
                            vm.alertConfig.class = 'wk-alert-danger';
                            // vm.alertConfig.details = [{ "errorMsg": "DUPLICATE_TAG_NAME" }];
                            vm.alertConfig.details = "ERRORS.DUPLICATE_TAG_NAME";
                            vm.alertConfig.isList = false;
                        }
                    });
                } else {
                    //calling update metadata api and checking response
                    metadataService.updateMetadata(metadataPostParam, vm.id).then(function (response) {
                        $window.scroll(0, 0);
                        if (response.status === 204) {
                            vm.alertConfig.class = 'wk-alert-success';
                            vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                            vm.alertConfig.isList = false;
                        } else if (response.status === 409) {
                            vm.alertConfig.class = 'wk-alert-danger';
                            vm.alertConfig.details = "ERRORS.DUPLICATE_TAG_NAME";
                            vm.alertConfig.isList = false;
                        } else {
                            vm.alertConfig.class = 'wk-alert-danger';
                            vm.alertConfig.details = 'ALERTS.EDIT_FAILED';
                            vm.alertConfig.isList = false;
                        }
                        $timeout(function () {
                            vm.alertConfig.show = false; //Hides alert
                            $rootScope.$state.reload();
                        }, 2000);
                    });

                }
                vm.alertConfig.show = true;
            }
        }
        //Used to check for duplicate names in tree tag.
        //Will check node name uniqueness in the same level
        var checkTreeTagDuplicate = function (treeData) {
            var tagNames = [];
            var isDuplicate = false;
            angular.forEach(treeData, function (tree, key) {
                if (tagNames.indexOf(tree.value) === -1 && angular.isDefined(tree.value)) { //Checking whether the name exist already in the same level. 
                    if (tree.nodeStatus != "deleted") { //If this node name is new and node is not deleted then push the new name to name list
                        tagNames.push(tree.value);
                        //Check the unique names for children(if exist) by calling checkTreeTagDuplicate() recursively
                        if (angular.isDefined(tree.children) && tree.children.length > 0)
                            if (checkTreeTagDuplicate(tree.children)) {
                                isDuplicate = true;
                                return;
                            }
                    }
                } else if (angular.isDefined(tree.value)) {

                    isDuplicate = true;
                    console.log(tree.value);
                    return;
                }
            });

            return isDuplicate;
        }

        //code to add more divs when lookup is selected.    
        vm.addTag = function () {
            vm.tagValue.push({value: '', nodeStatus: 'created'});
        };

        //code to remove divs when delete icon is selected.    
        vm.removeTag = function (tagToRemove) {
            if (!vm.metadata.resourceAssociated || angular.isUndefined(tagToRemove.id)) { //Check for metadata association with resource.
                if (tagToRemove.nodeStatus == 'selected' || tagToRemove.nodeStatus == 'updated') {
                    tagToRemove.nodeStatus = "deleted";
                } else {
                    var index = vm.tagValue.indexOf(tagToRemove);
                    vm.tagValue.splice(index, 1);
                }

                $log.debug('tag deleted')
            }
        };

        //Changes node status when we modify node name/description of existing nodes 
        vm.updateStatus = function (tagvalue) {
            if (tagvalue.nodeStatus == "selected")
                tagvalue.nodeStatus = "updated";
        }

        vm.onChangeMetadataType = function () {
            $log.debug(vm.metadata.selectedOptionMetadata.tagTypeId);
            vm.metadata.multiselect = 'no';
            if (vm.metadata.selectedOptionMetadata.tagTypeId == '3') {
                vm.metadata.selectedOptionMetadataType.dataTypeId = 1;
            }

        };

        vm.onChangeDataType = function () {
            if (vm.metadata.selectedOptionMetadataType.dataTypeId == 1) {
                vm.regex = '';
            }
            if (vm.metadata.selectedOptionMetadataType.dataTypeId == 2) {

                vm.regex = '^[0-9]+$';

            }
            if (vm.metadata.selectedOptionMetadataType.dataTypeId == 3) {
                vm.regex = '';
            }

        };
        //multiselect dropdown logic.
        vm.checkAll = function () {

            if (vm.selectedAll) {
                vm.selectedAll = true;
            } else {
                vm.selectedAll = false;
            }
            $log.debug(vm.selectedAll);
            angular.forEach(vm.institutions, function (value, key) {

                vm.institutionSelected[value.id] = vm.selectedAll;
            });

        };

        vm.inspectcheckAll = function () {
            vm.count = 0;
            angular.forEach(vm.institutionSelected, function (value, key) {
                if (value == true) {
                    vm.count = vm.count + 1;
                }
            });
            if (vm.count == vm.institutions.length) {
                vm.selectedAll = true;
            } else {
                vm.selectedAll = false;
            }
        }

        var metadataFormValidation = function () {
            if (vm.metadata.selectedOptionMetadataType.dataTypeId == 1) {
                vm.regex = '';
            }
            if (vm.metadata.selectedOptionMetadataType.dataTypeId == 2) {

                vm.regex = '^[0-9]+$';

            }
            if (vm.metadata.selectedOptionMetadataType.dataTypeId == 3) {
                vm.regex = '';
            }
            //if medata type selected is lookup and tag value is empty,dispaly error message minimum one value required.
            var tagLength = $filter('filter')(vm.tagValue, {nodeStatus: '!deleted'}, true).length
            if (tagLength == 0 && vm.metadata.selectedOptionMetadata.tagTypeId == 2) {
                vm.errorMsg = 'ERRORS.MINIMUM_LOOKUPVALUE';
                return false;
            } else {
                vm.errorMsg = "";

            }
            //validation ended
            var metadataPostParam = {};

            //forming input array for metadata insert
            if (vm.metadata.mandatory == 'yes') {
                metadataPostParam.mandatory = true;
            } else {
                metadataPostParam.mandatory = false;
            }
            if (vm.metadata.multiselect == 'yes') {
                metadataPostParam.multiselect = true;
            } else {
                metadataPostParam.multiselect = false;
            }
            metadataPostParam.tagName = vm.metadata.tagName;
            metadataPostParam.description = vm.metadata.description;
            metadataPostParam.displayLabel = vm.metadata.displayLabel;

            metadataPostParam.tagTypeId = vm.metadata.selectedOptionMetadata.tagTypeId;
            metadataPostParam.dataTypeId = vm.metadata.selectedOptionMetadataType.dataTypeId;

            metadataPostParam.metadataValues = [];
            $scope.flag = 0;
            //logic to assign all tag values to array
            if (vm.metadata.selectedOptionMetadata.tagTypeId == 2) {
                angular.forEach(vm.tagValue, function (metadataValues, key) {


                    angular.forEach(vm.tagValue, function (metadataValues2, key2) {

                        if (key != key2) {
                            if (metadataValues2.value == metadataValues.value) {
                                $scope.flag = 1;


                            }

                        }
                    });
                    vm.metadataTagValue = metadataValues.value;
                    if (vm.metadata.selectedOptionMetadataType.dataTypeId == 3) {
                        vm.metadataTagValue = $filter('date')(metadataValues.value);

                    }

                    metadataPostParam.metadataValues.push({id: metadataValues.id, value: vm.metadataTagValue, sequence: key + 1, nodeStatus: metadataValues.nodeStatus});


                });
                if ($scope.flag == 1) {
                    vm.errorMsg = 'ERRORS.DUPLICATE_LOOKUP_VALUE';
                    return false;
                } else {
                    vm.errorMsg = '';
                }
            }

            //Assign tree values from scope to param variable
            if (vm.metadata.selectedOptionMetadata.tagTypeId == 3) {
                metadataPostParam.metadataValues = angular.copy(vm.metadata.metadataValues);
                var rootNodesCount = $filter('filter')(metadataPostParam.metadataValues, {nodeStatus: "!deleted"}, true).length; //obj.children.length;

                if (rootNodesCount == 0) {
                    vm.errorMsg = 'ERRORS.MINIMUM_TREETAG';
                    return false;
                } else if (checkTreeTagDuplicate(metadataPostParam.metadataValues)) {
                    vm.errorMsg = 'ERRORS.DUPLICATE_TREETAG';
                    return false;
                } else {
                    vm.errorMsg = "";
                }
            }


            //get all the institution selected
            metadataPostParam.institutions = [];
            angular.forEach(vm.institutionSelected, function (value, key) {
                if (value == true)
                    metadataPostParam.institutions.push(key);
            });
            metadataPostParam.institutions = metadataPostParam.institutions.join(',');

            if (metadataPostParam.institutions.length == 0) {
                vm.multiselectErrorMsg = "ERRORS.SELECT_INSTITUTE";
                return false;
            } else {
                vm.multiselectErrorMsg = "";
            }
            $log.debug($scope.metadataForm.$valid);
            metadataPostParam.id = vm.id;
            metadataPostParam.userId = $rootScope.userId;
            return metadataPostParam;

        }

    }]);

})();

(function () {
    'use strict';

    angular.module('app.reports').controller('ReportsController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'reportsService', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, reportsService) {
        var vm = this;
        vm.table = vm.searchFilter = vm.role = {};
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.showLoader = true;
        vm.dateFormat = 'yyyy-MM-dd';
        vm.dateOptions = {
            formatYear: 'yyyy',
            startingDay: 1,
            minDate: new Date(2016, 1, 1),
            maxDate: new Date(2025, 12, 31)
        };


        //Assign default values and perform actions based on actionType 
        if (vm.actionType == "studentusage") {
            if (angular.isDefined($localStorage.roleTableState) && angular.isDefined($localStorage.roleTableState.pagination) && angular.isDefined($localStorage.roleTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.roleTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.STUDENT_USAGE_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['view'] = $rootScope.permission['reports'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else if (vm.actionType == "clientreport") {
            if (angular.isDefined($localStorage.roleTableState) && angular.isDefined($localStorage.roleTableState.pagination) && angular.isDefined($localStorage.roleTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.roleTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.CLIENT_REPORT_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['view'] = $rootScope.permission['reports'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else if (vm.actionType == "metadatareport") {
            if (angular.isDefined($localStorage.metadataArrayTableState) && angular.isDefined($localStorage.metadataArrayTableState.pagination) && angular.isDefined($localStorage.metadataArrayTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.metadataArrayTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.METADATA_REPORT_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['view'] = $rootScope.permission['reports'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else if (vm.actionType == "userquizzingreport") {
            if (angular.isDefined($localStorage.roleTableState) && angular.isDefined($localStorage.roleTableState.pagination) && angular.isDefined($localStorage.roleTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.roleTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.USER_QUIZZING_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['view'] = $rootScope.permission['reports'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else if (vm.actionType == "itemreport") {
            if (angular.isDefined($localStorage.roleTableState) && angular.isDefined($localStorage.roleTableState.pagination) && angular.isDefined($localStorage.roleTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.roleTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.ITEM_REPORT_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['view'] = $rootScope.permission['reports'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else {
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("dashboard.main")
        }


        //Usage report table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.usageReportTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.usageTableState = {};
                vm.searchFilter.title = "";
                vm.searchFilter.startDate = "";
                vm.searchFilter.endDate = "";
            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.usageTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.usageTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.title) && vm.searchFilter.title != "")
                    searchParams.title = vm.searchFilter.title;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.startDate) && vm.searchFilter.startDate != "")
                    searchParams.startDate = $filter('date')(vm.searchFilter.startDate, "yyyy-MM-dd");

                //Add entered endDate in the searchParams
                if (angular.isDefined(vm.searchFilter.endDate) && vm.searchFilter.endDate != "")
                    searchParams.endDate = $filter('date')(vm.searchFilter.endDate, "yyyy-MM-dd");

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.title = tableState.search.title;
                vm.searchFilter.startDate = new Date(tableState.search.startDate);
                vm.searchFilter.endDate = new Date(tableState.search.endDate);

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0) {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by item tag name
                    params.sort = "+title";
                    tableState.sort.predicate = "title";
                }
                if (isClear === true) {
                    params.sort = "+title";
                    tableState.sort.predicate = "title";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = config.recordsPerPageDefault;
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
                $log.debug("Passed Parameters:" + JSON.stringify(params))


                //call report service to get list of usage details 
                reportsService.getUsageData(params).then(function (response) {
                    console.log(response)
                    vm.usageDetails = response.data.data;
                    $log.debug(response);
                    vm.table.totalRecords = response.data.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.usageTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.data.total)
                });
            }
        };

        vm.exportReport = function (reportType, fileType, tableState) {

            var params = {};
            var searchParams = {};
            var url = ""; //backend api url
            params.userId = $rootScope.userId; //Add userId in request param
            var queryParam = '&userId=' + params.userId; //Assigning the query parameter

            //Add entered value in the searchParams
            if (angular.isDefined(vm.searchFilter.value) && vm.searchFilter.value != "") {
                searchParams.value = vm.searchFilter.value;
                queryParam = queryParam + '&value=' + searchParams.value;
            }
            //Add entered clientName in the searchParams
            if (angular.isDefined(vm.searchFilter.clientName) && vm.searchFilter.value != "") {
                searchParams.clientName = vm.searchFilter.clientName;
                queryParam = queryParam + '&clientName=' + searchParams.clientName;
            }

            //Add entered description in the searchParams
            if (angular.isDefined(vm.searchFilter.description) && vm.searchFilter.description != "") {
                searchParams.description = vm.searchFilter.description;
                queryParam = queryParam + '&description=' + searchParams.description;
            }

            //Add entered title in the searchParams
            if (angular.isDefined(vm.searchFilter.title) && vm.searchFilter.title != "") {
                searchParams.title = vm.searchFilter.title;
                queryParam = queryParam + '&title=' + searchParams.title;
            }

            //Add entered startDate in the searchParams
            if (angular.isDefined(vm.searchFilter.startDate) && vm.searchFilter.startDate != "") {
                searchParams.startDate = $filter('date')(vm.searchFilter.startDate, "yyyy-MM-dd");
                queryParam = queryParam + '&startDate=' + searchParams.startDate;
            }

            //Add entered endDate in the searchParams
            if (angular.isDefined(vm.searchFilter.endDate) && vm.searchFilter.endDate != "") {
                searchParams.endDate = $filter('date')(vm.searchFilter.endDate, "yyyy-MM-dd");
                queryParam = queryParam + '&endDate=' + searchParams.endDate;
            }

            //Add entered first name in the searchParams
            if (angular.isDefined(vm.searchFilter.label) && vm.searchFilter.label != "") {
                searchParams.label = vm.searchFilter.label;
                queryParam = queryParam + '&label=' + searchParams.label;
            }

            //Define the sorting type
            if (angular.isDefined(tableState.sort.predicate)) {
                params.sort = (tableState.sort.reverse ? '-' : '%2B') + tableState.sort.predicate;
                var sort = params.sort;
                queryParam = queryParam + '&sort=' + sort;
            } else { //Default Sorting by item tag name
                params.sort = "-title";
                queryParam = queryParam + '&sort=' + params.sort;
            }

            //If the fileType is excel , return the excel api url
            if (fileType == 'excel') {
                var url = config.apiUrl + 'reports/excelexport?reportType=' + reportType + queryParam;
            }
            //If the fileType is pdf , return the pdf api url
            else if (fileType == 'pdf') {
                var url = config.apiUrl + 'reports/pdfexport?reportType=' + reportType + queryParam;
            }
            //Open the api url in the new tab
            $window.open(url);
        }

        //Usage report table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.metadataReportTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.metadataArrayTableState = {};
                vm.searchFilter.value = "";
                vm.searchFilter.description = "";

            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.metadataArrayTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.metadataArrayTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.value) && vm.searchFilter.value != "")
                    searchParams.value = vm.searchFilter.value;
                if (angular.isDefined(vm.searchFilter.description) && vm.searchFilter.description != "")
                    searchParams.description = vm.searchFilter.description;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.value = tableState.search.value;

                vm.searchFilter.description = tableState.search.description;
              
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0) {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by item tag name
                    params.sort = "-value";
                    tableState.sort.predicate = "value";
                }
                if (isClear === true) {
                    params.sort = "-value";
                    tableState.sort.predicate = "value";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = config.recordsPerPageDefault;
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
                $log.debug("Passed Parameters:" + JSON.stringify(params))


                //call report service to get list of usage details 
                reportsService.getMetadataReport(params).then(function (response) {

                    vm.metadataDetails = response.data.data;
                    

                    vm.table.totalRecords = response.data.total;
                    $log.debug(response.data.total);
                    tableState.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.metadataArrayTableState = tableState;
                    
                    $log.debug("Total Result:" + response.data.total)
                });
            }
        };

        vm.clientReportTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.clientTableState = {};
                vm.searchFilter.clientName = "";
                vm.searchFilter.startDate = "";
                vm.searchFilter.endDate = "";
            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.clientTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.clientTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.clientName) && vm.searchFilter.clientName != "")
                    searchParams.clientName = vm.searchFilter.clientName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.startDate) && vm.searchFilter.startDate != "")
                    searchParams.startDate = $filter('date')(vm.searchFilter.startDate, "yyyy-MM-dd");

                //Add entered endDate in the searchParams
                if (angular.isDefined(vm.searchFilter.endDate) && vm.searchFilter.endDate != "")
                    searchParams.endDate = $filter('date')(vm.searchFilter.endDate, "yyyy-MM-dd");

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.clientName = tableState.search.clientName;
                vm.searchFilter.startDate = new Date(tableState.search.startDate);
                vm.searchFilter.endDate = new Date(tableState.search.endDate);

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0) {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by item tag name
                    params.sort = "+clientName";
                    tableState.sort.predicate = "clientName";
                }
                if (isClear === true) {
                    params.sort = "+clientName";
                    tableState.sort.predicate = "clientName";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = config.recordsPerPageDefault;
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
                $log.debug("Passed Parameters:" + JSON.stringify(params))


                //call report service to get list of usage details 
                reportsService.getClientReportData(params).then(function (response) {
                    console.log(response)
                    vm.clientReportDetails = response.data.data;//.data;
                    //$log.debug("response.data.data");
                    vm.table.totalRecords = response.data.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.clientTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.data.total)
                });
            }
        };

        //User quizzing report
        vm.userQuizzingReportTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userquizzingTableState = {};
                vm.searchFilter.title = "";
            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userquizzingTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.userquizzingTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.title) && vm.searchFilter.title != "")
                    searchParams.title = vm.searchFilter.title;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.title = tableState.search.title;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0) {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by item tag name
                    params.sort = "-quizCount";
                    tableState.sort.predicate = "quizCount";
                }
                if (isClear === true) {
                    params.sort = "-quizCount";
                    tableState.sort.predicate = "quizCount";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = config.recordsPerPageDefault;
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call report service to get list of usage details 
                reportsService.getUserQuizzingReportData(params).then(function (response) {
                    console.log(response)
                    vm.userquizzingReportDetails = response.data.data;//.data;
                    vm.table.totalRecords = response.data.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.userquizzingTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.data.total)
                });
            }
        };

        //Usage report table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.itemReportTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.itemTableState = {};
                vm.searchFilter.label = "";

            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.itemTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;

                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.label) && vm.searchFilter.label != "")
                    searchParams.label = vm.searchFilter.label;


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.label = tableState.search.label;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0) {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by item tag name
                    params.sort = "+label";
                    tableState.sort.predicate = "label";
                }
                if (isClear === true) {
                    params.sort = "+label";
                    tableState.sort.predicate = "label";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = config.recordsPerPageDefault;
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
                $log.debug("Passed Parameters:" + JSON.stringify(params))


                //call report service to get list of usage details 
                reportsService.getItemWrongData(params).then(function (response) {
                    console.log(response)
                    vm.itemDetails = response.data.data;
                    $log.debug(response);
                    vm.table.totalRecords = response.data.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.itemTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.data.total)
                });
            }
        };

    }])
})();

(function () {
    'use strict';

    angular.module('app.role').controller('RoleController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'roleService', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, roleService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;

        vm.table = vm.searchFilter = vm.role = {};
        vm.showdropdown = 0;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        vm.alertConfig = {'show': false};

        vm.showdropdown = function () {
            vm.showdropdown = 1;
        };

        vm.pageError = false;
        vm.showLoader = true;

        var userParam = {};
        var rolePermissions = {};

        if ($localStorage.userTypeList) {
            vm.userTypeList = $localStorage.userTypeList;
            //select default country as 
            angular.forEach(vm.userTypeList, function (value, key) {
                if (value.userTypeName == 'ADMIN')
                {
                    vm.userType = value.userTypeId;
                }
            })

        } else {
            //call user service to get list of usertype
            roleService.getuserTypeList().then(function (response) {
                $localStorage.userTypeList = vm.userTypeList = response.data;
                angular.forEach(vm.userTypeList, function (value, key) {
                    if (value.userTypeName == 'ADMIN')
                    {
                        vm.userType = value.userTypeId;
                    }
                })

            });

        }
        if ($localStorage.statusList) {
            vm.statusList = $localStorage.statusList;
            //select default country as 
            angular.forEach(vm.statusList, function (value, key) {

                if (value.statusName == 'ACTIVE')
                {
                    vm.activeValue = value.statusCode;
                }
                if (value.statusName == 'INACTIVE')
                {
                    vm.inactiveValue = value.statusCode;
                }
                vm.role.status = vm.activeValue;
            });
            vm.role.status = vm.activeValue;

        } else {
            //call user service to get list of states    
            roleService.getStatus().then(function (response) {
                $localStorage.statusList = vm.statusList = response.data;
                angular.forEach(vm.statusList, function (value, key) {
                    $log.debug(value);
                    if (value.statusName == 'ACTIVE')
                    {
                        vm.activeValue = value.statusCode;
                    }
                    if (value.statusName == 'INACTIVE')
                    {
                        vm.inactiveValue = value.statusCode;
                    }
                    vm.role.status = vm.activeValue;
                });

            });
        }

        //Assign default values and perform actions based on actionType 
        if (vm.actionType == "list") {
            $log.debug($localStorage.roleTableState);
            if (angular.isDefined($localStorage.roleTableState) && angular.isDefined($localStorage.roleTableState.pagination) && angular.isDefined($localStorage.roleTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.roleTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.ROLE_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['role'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['role'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['role'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['role'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        }
        else if (vm.actionType == "create") {
            vm.pageTitle = "PAGE_TITLE.ROLE_CREATE"; //Page title mapped to locale json key
            
              //Get user for the given id by calling user/{id} api
            roleService.getRolesList('create').then(function (response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.role = response.data;
                    vm.role.roleName = vm.role.roleName;
                    vm.role.description = vm.role.description;
                    vm.role.rolePermission = vm.role.permissions;
                    vm.showLoader = false;
                } else {
                    vm.pageError = true;
                }
                //if permission not applicable for ant title then disable that here
                angular.forEach(vm.role.rolePermission, function (value, key) {
                   
                if(angular.isUndefined(value.create))
                {
                    value.create = 'disable';
                }
                if(angular.isUndefined(value.edit))
                {
                    value.edit = 'disable';
                }
                 if(angular.isUndefined(value.delete))
                {
                    value.delete = 'disable';
                }
                if(angular.isUndefined(value.view))
                {
                    value.view = 'disable';
                }
                if(angular.isUndefined(value.manageAssociation))
                {
                    value.manageAssociation = 'disable';
                }
                if(angular.isUndefined(value.manageSecurity))
                {
                    value.manageSecurity = 'disable';
                }
            })
            });
            vm.showLoader = false;


        }
        else if ((vm.actionType == "view" || vm.actionType == "delete" || vm.actionType == "edit") && $rootScope.$stateParams.id !== '') {
            vm.role.id = vm.id = $rootScope.$stateParams.id;
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.ROLE_VIEW_LABEL"; //Page title mapped to locale json key of view label
            else if (vm.actionType == 'edit') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.ROLE_EDIT"; //Page title mapped to locale json key of edit label
            else
                vm.pageTitle = "PAGE_TITLE.ROLE_DELETE_LABEL"; //Page title mapped to locale json key of delete label

            //Get user for the given id by calling user/{id} api
            roleService.getRolesList(vm.id).then(function (response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.role = response.data;
                    vm.role.roleName = vm.role.roleName;
                    vm.role.description = vm.role.description;
                    vm.rolePermissions = vm.role.permissions;
                    vm.role.rolePermission = angular.copy(vm.role.permissions);
                    vm.showLoader = false;
                } else {
                    vm.pageError = true;
                }
                   //if permission not applicable for ant title then disable that here
                angular.forEach(vm.role.rolePermission, function (value, key) {
                   
                if(angular.isUndefined(value.create))
                {
                    value.create = 'disable';
                }
                else if(value.create == '1')
                {
                    value.create = true;
                }
                if(angular.isUndefined(value.edit))
                {
                    value.edit = 'disable';
                }
                 else if(value.edit == '1')
                {
                    value.edit = true;
                }
                 if(angular.isUndefined(value.delete))
                {
                    value.delete = 'disable';
                }
                 else if(value.delete == '1')
                {
                    value.delete = true;
                }
                if(angular.isUndefined(value.view))
                {
                    value.view = 'disable';
                }
                 else if(value.view == '1')
                {
                    value.view = true;
                }
                if(angular.isUndefined(value.manageAssociation))
                {
                    value.manageAssociation = 'disable';
                }
                 else if(value.manageAssociation == '1')
                {
                    value.manageAssociation = true;
                }
                if(angular.isUndefined(value.manageSecurity))
                {
                    value.manageSecurity = 'disable';
                }
                 else if(value.manageSecurity == '1')
                {
                    value.manageSecurity = true;
                }
            })
            });
            vm.showLoader = false;

        }
        else if (angular.isUndefined(vm.id)) {

            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("group.list")
        }


        //User list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.userTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {

                $localStorage.roleTableState = {};
                vm.searchFilter.roleName = "";
                vm.searchFilter.description = "";

            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.roleTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.roleTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;


                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.roleName) && vm.searchFilter.roleName != "")
                    searchParams.roleName = vm.searchFilter.roleName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.description) && vm.searchFilter.description != "")
                    searchParams.description = vm.searchFilter.description;


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.roleName = tableState.search.roleName;
                vm.searchFilter.description = tableState.search.description;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }
            if (flag == 0)
            {
                vm.showLoader = true;

                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by user email
                    params.sort = "+roleName";
                    tableState.sort.predicate = "roleName";
                }
                if (isClear === true) {
                    params.sort = "+roleName";
                    tableState.sort.predicate = "roleName";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = 10;
                    console.log(tableState.sort)
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call role service to get list of role details 
                roleService.getRoles(params).then(function (response) {
                    vm.roleDetails = response.results.data;
                    $log.debug(response);
                    vm.table.totalRecords = response.results.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.roleTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.results.total)
                });
            }
        };


        //Deletes the role based on Role id
        vm.deleteRole = function () {

            roleService.deleteRole(vm.id).then(function (response) {

                vm.alertConfig.show = true;
                $window.scroll(0, 0);

                if (response.status === 204) {
                    vm.alertConfig.class = 'wk-alert-success';
                    vm.alertConfig.msg = 'ALERTS.DELETE_SUCCESS';
                    vm.alertConfig.isList = false;
                } else if (response.status === 409) {
                    var displayMsg = "ERRORS.DUPLICATE_ROLE_NAME";
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = displayMsg;
                    vm.alertConfig.isList = true;
                } else {
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = 'ALERTS.DELETE_FAILED';
                    vm.alertConfig.isList = false;
                }

                vm.alertConfig.show = true;
                if (vm.alertConfig.isList == false)
                {
                    $timeout(function () {
                        vm.alertConfig.show = false; //Hides alert
                        $rootScope.$state.go('role.list');
                    }, 2000);
                }
            });
        }

        //function to create new role
        vm.createRole = function ()
        {
            vm.isFormSubmitted = true;
            vm.role.userId = $rootScope.userId;
            if ($scope.roleForm.$valid) {
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                    roleService.insertRole(vm.role).then(function (response) {
                        if (response.status === 201) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', 'role.list', false);
                        } else if (response.status === 409) {
                            if (response.data.code == "1108") {
                                var displayMsg = 'ERRORS.DUPLICATE_ROLE_NAME';
                                vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                                vm.isSubmitDisabled = false;
                            }
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', '', false);
                        }
                    });
                }
                else
                {

                     roleService.updateRole(vm.role,vm.id).then(function (response) {
                        if (response.status === 201) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.EDIT_SUCCESS', 'role.list', false);
                        } else if (response.status === 409) {
                            if (response.data.code == "1108") {
                                var displayMsg = 'ERRORS.DUPLICATE_ROLE_NAME';
                                vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                                vm.isSubmitDisabled = false;
                            }
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.EDIT_FAILED', '', false);
                        }
                    });

                }
            }
        }

        //Used for alerting on success/failure
        vm.alertConfig.timeOutAlert = function (cssClass, alertMsg, redirectState, isList) {
            $window.scroll(0, 0);
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (redirectState != '') { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }

        }


    }])
})();
(function() {
    'use strict';

    angular.module('app.systemsettings').controller('SystemsettingsController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'systemsettingsService', function($rootScope, $scope, $window, $log, $localStorage, $filter , $timeout, config , systemsettingsService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;
       
        vm.table = vm.searchFilter = vm.user = {};
        vm.showdropdown = 0;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        vm.alertConfig = { 'show': false };
        vm.emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        
        vm.showdropdown = function(){
            vm.showdropdown =1;
        };
        
        vm.pageError = false;
        vm.showLoader = true;
       
        var userParam = {};
        var roleDetails = {};
        var pageIntDropDown = { availableOptions: [ {id: '10', name: '10'}, {id: '15', name: '15'}, {id: '20', name: '20'}, {id: '25', name: '25'}, {id: '50', name: '50'}, {id: '75', name: '75'}, {id: '100', name: '100'}]};
      
        
        if ($localStorage.userTypeList) {
            vm.userTypeList = $localStorage.userTypeList;
            //select default country as 
            angular.forEach(vm.userTypeList, function(value, key) {
                       if(value.userTypeName == 'ADMIN')
                        {
                            vm.userType = value.userTypeId;
                        }
                 })

        } else {
            //call user service to get list of usertype
            systemsettingsService.getuserTypeList().then(function(response) {
                $localStorage.userTypeList = vm.userTypeList = response.data;
                 angular.forEach(vm.userTypeList, function(value, key) {
                       if(value.userTypeName == 'ADMIN')
                        {
                            vm.userType = value.userTypeId;
                        }
                 })
               
            });
        
        } 
        /*
        if ($localStorage.statusList) {
            vm.statusList = $localStorage.statusList;
            //select default country as 
            angular.forEach(vm.statusList, function(value, key) {
                 
                if(value.statusName == 'ACTIVE')
                {
                    vm.activeValue = value.statusCode;
                }
                if(value.statusName == 'INACTIVE')
                {
                    vm.inactiveValue = value.statusCode;
                }
                vm.group.status = vm.activeValue;
                });
                vm.group.status = vm.activeValue;
               
        } else {
            //call user service to get list of states    
               groupService.getStatus().then(function(response) {
                   $localStorage.statusList = vm.statusList = response.data;
                   angular.forEach(vm.statusList, function(value, key) {
                       $log.debug(value);
                   if(value.statusName == 'ACTIVE')
                   {
                       vm.activeValue = value.statusCode;
                   }
                   if(value.statusName == 'INACTIVE')
                   {
                       vm.inactiveValue = value.statusCode;
                   }
                   vm.group.status = vm.activeValue;
                   });

           });
        }
        */
        //Assign default values and perform actions based on actionType 
        
        /*
        if (vm.actionType == "list") {
            $log.debug($localStorage.groupTableState);
            if (angular.isDefined($localStorage.groupTableState) && angular.isDefined($localStorage.groupTableState.pagination) && angular.isDefined($localStorage.groupTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.groupTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;; //Default data per page
            
            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.GROUP_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['group'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['group'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['group'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['group'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else */
        if ((vm.actionType == "list") && $rootScope.$stateParams.id !== '') {
            
            vm.id = $rootScope.$stateParams.id;
            vm.pageTitle = "PAGE_TITLE.SYSTEMSETTINGS_VIEW_LABEL"; //Page title mapped to locale json key of view label
            
            var params = {};
            params.userId = $rootScope.userId;
      
            vm.emailDomainFlag = vm.limitFlag = vm.appnameFlag = vm.versionFlag = vm.tokenHeaderKeyFlag = false;
            vm.tokenPrefixFlag = vm.quizTimeFlag = vm.questionTimeFlag = vm.accessTokenLifeTimeFlag = false;
            
            
            //Get user for the given id by calling user/{id} api
            systemsettingsService.getSystemSettings(params).then(function(response) {
                vm.systemSettings = response.results[0];
                /** ADMIN CONFIG VALUES **/
                // CHECK EMAIL IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.emailDomain){
                    vm.emailDomain = vm.systemSettings.emailDomain;
                    vm.emailDomainFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.limit) {
                    vm.limit = vm.systemSettings.limit;
                    vm.limitFlag = true;
                    vm.pageIntDropDown = pageIntDropDown.availableOptions;
                    vm.selectedOption = { id:vm.limit, name:vm.limit };
                }
                
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.appname) {
                    vm.appname = vm.systemSettings.appname;
                    vm.appnameFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.version) {
                    vm.version = vm.systemSettings.version;
                    vm.versionFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.tokenHeaderKey) {
                    vm.tokenHeaderKey = vm.systemSettings.tokenHeaderKey;
                    vm.tokenHeaderKeyFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.tokenPrefix) {
                    vm.tokenPrefix = vm.systemSettings.tokenPrefix;
                    vm.tokenPrefixFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.quizTime) {
                    vm.quizTime = vm.systemSettings.quizTime;
                    vm.quizTimeFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.questionTime) {
                    vm.questionTime = vm.systemSettings.questionTime;
                    vm.questionTimeFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.accessTokenLifeTime) {
                    vm.accessTokenLifeTime = vm.systemSettings.accessTokenLifeTime;
                    vm.accessTokenLifeTimeFlag = true;
                }
                
                /** ADMIN CONFIG VALUES END**/
                
                /** EUP CONFIG VALUES **/
                
                vm.recordsPerPage_eupFlag = vm.recordsPerPageDefault_eupFlag = vm.minRecordsPerPage_eupFlag = false;
                vm.minRecordsPerPageDefault_eupFlag = vm.alertTimeOut_eupFlag = vm.questionTime_eupFlag = false;
                vm.itemScoreMin_eupFlag = vm.itemScoreMax_eupFlag = vm.itemDifficultyMin_eupFlag = false;
                vm.itemDifficultyMax_eupFlag = vm.imageAssetAccept_eupFlag = vm.videoAssetAccept_eupFlag = false;
                vm.graphicAssetAccept_eupFlag = vm.medcaseAssetAccept_eupFlag = vm.imageMaxSize_eupFlag = false;
                vm.videoMaxSize_eupFlag = vm.graphicMaxSize_eupFlag = vm.medcaseMaxSize_eupFlag = false; 
                vm.videoAssetId_eupFlag = vm.audioAssetId_eupFlag = vm.imageAssetId_eupFlag = false;
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.recordsPerPage_eup) {
                    vm.recordsPerPage_eup = vm.systemSettings.recordsPerPage_eup;
                    vm.recordsPerPage_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.recordsPerPageDefault_eup) {
                    vm.recordsPerPageDefault_eup = vm.systemSettings.recordsPerPageDefault_eup;
                    vm.recordsPerPageDefault_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.minRecordsPerPage_eup) {
                    vm.minRecordsPerPage_eup = vm.systemSettings.minRecordsPerPage_eup;
                    vm.minRecordsPerPage_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.minRecordsPerPageDefault_eup) {
                    vm.minRecordsPerPageDefault_eup = vm.systemSettings.minRecordsPerPageDefault_eup;
                    vm.minRecordsPerPageDefault_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.alertTimeOut_eup) {
                    vm.alertTimeOut_eup = vm.systemSettings.alertTimeOut_eup;
                    vm.alertTimeOut_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.questionTime_eup) {
                    vm.questionTime_eup = vm.systemSettings.questionTime_eup;
                    vm.questionTime_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemScoreMin_eup) {
                    vm.itemScoreMin_eup = vm.systemSettings.itemScoreMin_eup;
                    vm.itemScoreMin_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemScoreMax_eup) {
                    vm.itemScoreMax_eup = vm.systemSettings.itemScoreMax_eup;
                    vm.itemScoreMax_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemDifficultyMin_eup) {
                    vm.itemDifficultyMin_eup = vm.systemSettings.itemDifficultyMin_eup;
                    vm.itemDifficultyMin_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemDifficultyMax_eup) {
                    vm.itemDifficultyMax_eup = vm.systemSettings.itemDifficultyMax_eup;
                    vm.itemDifficultyMax_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.imageAssetAccept_eup) {
                    vm.imageAssetAccept_eup = vm.systemSettings.imageAssetAccept_eup;
                    vm.imageAssetAccept_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.videoAssetAccept_eup) {
                    vm.videoAssetAccept_eup = vm.systemSettings.videoAssetAccept_eup;
                    vm.videoAssetAccept_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.graphicAssetAccept_eup) {
                    vm.graphicAssetAccept_eup = vm.systemSettings.graphicAssetAccept_eup;
                    vm.graphicAssetAccept_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.medcaseAssetAccept_eup) {
                    vm.medcaseAssetAccept_eup = vm.systemSettings.medcaseAssetAccept_eup;
                    vm.medcaseAssetAccept_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.imageMaxSize_eup) {
                    vm.imageMaxSize_eup = vm.systemSettings.imageMaxSize_eup;
                    vm.imageMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.videoMaxSize_eup) {
                    vm.videoMaxSize_eup = vm.systemSettings.videoMaxSize_eup;
                    vm.videoMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.graphicMaxSize_eup) {
                    vm.graphicMaxSize_eup = vm.systemSettings.graphicMaxSize_eup;
                    vm.graphicMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.medcaseMaxSize_eup) {
                    vm.medcaseMaxSize_eup = vm.systemSettings.medcaseMaxSize_eup;
                    vm.medcaseMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.videoAssetId_eup) {
                    vm.videoAssetId_eup = vm.systemSettings.videoAssetId_eup;
                    vm.videoAssetId_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.audioAssetId_eup) {
                    vm.audioAssetId_eup = vm.systemSettings.audioAssetId_eup;
                    vm.audioAssetId_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.imageAssetId_eup) {
                    vm.imageAssetId_eup = vm.systemSettings.imageAssetId_eup;
                    vm.imageAssetId_eupFlag = true;
                } 
                
                /** EUP CONFIG VALUES END**/
                
                
                vm.showLoader = false;
            });
        } 
       else if (angular.isUndefined(vm.id)) {
          
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("systemsettings.list")
            
        }
        
        
            //User list table pipe function. 
            //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.systemSettingsTablePipe = function(tableState, isSearch, isClear) {
            
            var params = {};
            var flag = 0;
            
            params.userId = $rootScope.userId; //Add userId in request param
            if(flag == 0)
            {
                vm.showLoader = true;
                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call metadata service to get list of metadata details 
                systemsettingsService.getSystemSettings(params).then(function(response) {
                    
                    //vm.groupDetails = response.results.data;
                    vm.systemSettingsDetails = response.results.data;
                    
                    $log.debug(response);
                    //vm.table.totalRecords = response.results.total;
                    //tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    //vm.table.tableStateScopeCopy = $localStorage.groupTableState = tableState;
                    $log.debug(vm.systemSettingsDetails)
                    //$log.debug("Total Result:" + response.results.total)
                });
            }
        };
        
        var systemSettingFormValidation = function () {
            
            var userParam = {};
            userParam.emailDomain = vm.emailDomain;
            userParam.userId = $rootScope.userId;
            userParam.limit = vm.selectedOption.id;
            userParam.appname = vm.appname;
            userParam.version = vm.version;
            userParam.tokenHeaderKey = vm.tokenHeaderKey;
            userParam.tokenPrefix = vm.tokenPrefix;
            userParam.quizTime = vm.quizTime;
            userParam.questionTime = vm.questionTime;
            userParam.accessTokenLifeTime = vm.accessTokenLifeTime;
            userParam.recordsPerPage_eup = vm.recordsPerPage_eup;
            userParam.recordsPerPageDefault_eup = vm.recordsPerPageDefault_eup;
            userParam.minRecordsPerPage_eup = vm.minRecordsPerPage_eup;
            userParam.minRecordsPerPageDefault_eup = vm.minRecordsPerPageDefault_eup;
            userParam.alertTimeOut_eup = vm.alertTimeOut_eup;
            userParam.questionTime_eup = vm.questionTime_eup;
            userParam.itemScoreMin_eup = vm.itemScoreMin_eup;
            userParam.itemScoreMax_eup = vm.itemScoreMax_eup;
            userParam.itemDifficultyMin_eup = vm.itemDifficultyMin_eup;
            userParam.itemDifficultyMax_eup = vm.itemDifficultyMax_eup;
            userParam.imageAssetAccept_eup = vm.imageAssetAccept_eup;
            userParam.videoAssetAccept_eup = vm.videoAssetAccept_eup;
            userParam.graphicAssetAccept_eup = vm.graphicAssetAccept_eup;
            userParam.medcaseAssetAccept_eup = vm.medcaseAssetAccept_eup;
            userParam.imageMaxSize_eup = vm.imageMaxSize_eup;
            userParam.videoMaxSize_eup = vm.videoMaxSize_eup;
            userParam.graphicMaxSize_eup = vm.graphicMaxSize_eup;
            userParam.medcaseMaxSize_eup = vm.medcaseMaxSize_eup;
            userParam.videoAssetId_eup = vm.videoAssetId_eup;
            userParam.audioAssetId_eup = vm.audioAssetId_eup;
            userParam.imageAssetId_eup = vm.imageAssetId_eup;
            
 
            if(!angular.isUndefined(vm.appname)) {
                userParam.appname = vm.appname;
            }
            
            if(!angular.isUndefined(vm.version)) {
                userParam.version = vm.version;
            }
         
            if(!angular.isUndefined(vm.tokenHeaderKey)) {
                userParam.tokenHeaderKey = vm.tokenHeaderKey;
            }
         
            if(!angular.isUndefined(vm.tokenPrefix)) {
                userParam.tokenPrefix = vm.tokenPrefix;
            }
         
            if(!angular.isUndefined(vm.quizTime)) {
                userParam.quizTime = vm.quizTime;
            }
            
            if(!angular.isUndefined(vm.questionTime)) {
                userParam.questionTime = vm.questionTime;
            }
            
            if(!angular.isUndefined(vm.accessTokenLifeTime)) {
                userParam.accessTokenLifeTime = vm.accessTokenLifeTime;
            }
         
            if(!angular.isUndefined(vm.recordsPerPage_eup)) {
                userParam.recordsPerPage_eup = vm.recordsPerPage_eup;
            }
            
            if(!angular.isUndefined(vm.recordsPerPageDefault_eup)) {
                userParam.recordsPerPageDefault_eup = vm.recordsPerPageDefault_eup;
            }
            
            if(!angular.isUndefined(vm.minRecordsPerPage_eup)) {
                userParam.minRecordsPerPage_eup = vm.minRecordsPerPage_eup;
            }
            
            if(!angular.isUndefined(vm.minRecordsPerPageDefault_eup)) {
                userParam.minRecordsPerPageDefault_eup = vm.minRecordsPerPageDefault_eup;
            }
            
            if(!angular.isUndefined(vm.alertTimeOut_eup)) {
                userParam.alertTimeOut_eup = vm.alertTimeOut_eup;
            }
            
            if(!angular.isUndefined(vm.questionTime_eup)) {
                userParam.questionTime_eup = vm.questionTime_eup;
            }
            
            if(!angular.isUndefined(vm.itemScoreMin_eup)) {
                userParam.itemScoreMin_eup = vm.itemScoreMin_eup;
            }
         
            if(!angular.isUndefined(vm.itemScoreMax_eup)) {
                userParam.itemScoreMax_eup = vm.itemScoreMax_eup;
            }
            
            if(!angular.isUndefined(vm.itemDifficultyMin_eup)) {
                userParam.itemDifficultyMin_eup = vm.itemDifficultyMin_eup;
            }
            
            if(!angular.isUndefined(vm.itemDifficultyMax_eup)) {
                userParam.itemDifficultyMax_eup = vm.itemDifficultyMax_eup;
            }
            
            if(!angular.isUndefined(vm.imageAssetAccept_eup)) {
                userParam.imageAssetAccept_eup = vm.imageAssetAccept_eup;
            }
            
            if(!angular.isUndefined(vm.videoAssetAccept_eup)) {
                userParam.videoAssetAccept_eup = vm.videoAssetAccept_eup;
            }
            if(!angular.isUndefined(vm.graphicAssetAccept_eup)) {
                userParam.graphicAssetAccept_eup = vm.graphicAssetAccept_eup;
            }
            
            if(!angular.isUndefined(vm.medcaseAssetAccept_eup)) {
                userParam.medcaseAssetAccept_eup = vm.medcaseAssetAccept_eup;
            }
        
            if(!angular.isUndefined(vm.imageMaxSize_eup)) {
                userParam.imageMaxSize_eup = vm.imageMaxSize_eup;
            }
         
            if(!angular.isUndefined(vm.videoMaxSize_eup)) {
                userParam.videoMaxSize_eup = vm.videoMaxSize_eup;
            }
            
            if(!angular.isUndefined(vm.graphicMaxSize_eup)) {
                userParam.graphicMaxSize_eup = vm.graphicMaxSize_eup;
            }
            
            if(!angular.isUndefined(vm.medcaseMaxSize_eup)) {
                userParam.medcaseMaxSize_eup = vm.medcaseMaxSize_eup;
            }
            
            if(!angular.isUndefined(vm.videoAssetId_eup)) {
                userParam.videoAssetId_eup = vm.videoAssetId_eup;
            }
            
            if(!angular.isUndefined(vm.audioAssetId_eup)) {
                userParam.audioAssetId_eup = vm.audioAssetId_eup;
            }
            
            if(!angular.isUndefined(vm.imageAssetId_eup)) {
                userParam.imageAssetId_eup = vm.imageAssetId_eup;
            }
            
            
           
            if (angular.isUndefined(vm.emailDomain))
            {
                vm.errorMsg = 'ERRORS.USER_NAME_VAL_MSG';
                return false;
            } else {
                vm.errorMsg = "";
            }
            return userParam;
        }
        
        vm.updateSystemSetting = function () {


            userParam = systemSettingFormValidation();
            
            $log.debug($scope.systemForm);
            //calling create user api and checking response.If status is true return to listing page else display error message.
            if (userParam && $scope.systemForm.$valid == true) {
                //calling update metadata api and checking response
                $log.debug(userParam);
                systemsettingsService.updatesystemSetting(userParam, vm.id).then(function (response) {
                    $window.scroll(0, 0);
                    if (response.status === 204) {
                        vm.alertConfig.class = 'wk-alert-success';
                        vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                        vm.alertConfig.isList = false;

                    } else if (response.status === 409) {
                        var displayMsg = 'ERRORS.INVALID_EMAIL';
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.details = displayMsg;
                        vm.alertConfig.isList = false;
                    } else {
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.details = 'ALERTS.EDIT_FAILED';
                        vm.alertConfig.isList = false;

                    }
                });
                vm.alertConfig.show = true;

            }
        };        
    }]).directive('numbersOnly', function(){
        return {
          require: 'ngModel',
          link: function(scope, element, attrs, modelCtrl) {
            modelCtrl.$parsers.push(function (inputValue) {
                // this next if is necessary for when using ng-required on your input. 
                // In such cases, when a letter is typed first, this parser will be called
                // again, and the 2nd time, the value will be undefined
                if (inputValue == undefined) return '' 
                var transformedInput = inputValue.replace(/[^0-9]/g, ''); 
                if (transformedInput!=inputValue) {
                   modelCtrl.$setViewValue(transformedInput);
                   modelCtrl.$render();
                }         

                return transformedInput;         
            });
          }
        };
     });
     
})();
(function () {
    'use strict';

    angular.module('app.tests').controller('TestsController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'testsService', 'itembanksService', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, testsService, itembanksService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;
        $scope.forms = {};
        vm.actionType = $rootScope.$state.current.name.split(".")[1]
        vm.showLoader = true, vm.pageError = false, vm.closeOtherAccordions = false, vm.isSubmitDisabled = false;
        vm.otherInfo = false;
        vm.alertConfig = {'show': false}
        vm.tableItem = {}, vm.tableItem.totalRecords = 0;
        vm.tableItemBank = {}, vm.tableItemBank.totalRecords = 0;
        vm.table = {}, vm.table.totalRecords = 0;
        vm.associatedTab = 1;
        vm.quiz = {}
        vm.tableItem = {}, vm.searchFilter = {}, vm.searchFilter.metadataAssoc = {}, vm.searchFilter.selectedMetaDetails = [];
        vm.searchFilterBank = {}, vm.searchFilterBank.metadataAssoc = {}, vm.searchFilterBank.selectedMetaDetails = [];
        vm.tableItem.dataPerPage = config.recordsPerPageDefault;
        vm.tableItem.dataPerPageOptions = config.recordsPerPage;
        vm.tableItemBank.dataPerPage = config.recordsPerPageDefault;
        vm.tableItemBank.dataPerPageOptions = config.recordsPerPage;
        vm.quiz.metadataAssoc = {}, vm.quiz.selectedMetaDetails = [];
        vm.showLoader = false;
        vm.quiz.metadataAssoc = {}, vm.quiz.selectedMetaDetails = [];

        vm.checkobjectToarrayItem = 0;
        vm.checkobjectToarrayItemBank = 0;

        if (vm.actionType == 'create') //check actionType to assign page title
        {
            vm.pageTitle = "PAGE_TITLE.TEST_CREATE_LABEL";
            vm.quiz.navigationType = 1;
        } else if (vm.actionType == 'edit')
            vm.pageTitle = "PAGE_TITLE.TEST_EDIT_LABEL";
        else if (vm.actionType == 'view')
            vm.pageTitle = "PAGE_TITLE.TEST_VIEW_LABEL";
        else if (vm.actionType == 'delete')
            vm.pageTitle = "PAGE_TITLE.TEST_DELETE_LABEL";
        else if (vm.actionType == 'list')
            vm.pageTitle = "PAGE_TITLE.TEST_LIST_LABEL";

        if (vm.actionType == "list") { //Block of actions related to list action
            vm.searchFilter = {}, vm.searchFilter.metadataAssoc = {}, vm.searchFilter.selectedMetaDetails = [];


            if (angular.isDefined($localStorage.quizTableStateScopeCopy) && angular.isDefined($localStorage.quizTableStateScopeCopy.pagination.number))
                vm.table.dataPerPage = $localStorage.quizTableStateScopeCopy.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page
            vm.showLoader = false;

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.TEST_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['tests'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['tests'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['tests'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['tests'].indexOf('view') !== -1 ? true : false;
            vm.permission['manageSecurity'] = $rootScope.permission['tests'].indexOf('manageSecurity') !== -1 ? true : false;
            vm.permission['manageAssociation'] = $rootScope.permission['tests'].indexOf('manageAssociation') !== -1 ? true : false;
        } else if (vm.actionType == 'create') {
            vm.showLoader = false;
        } else if (vm.actionType == 'edit' || vm.actionType == 'view' || vm.actionType == 'delete') //check actionType to assign page title
        {
            testsService.getQuizById(vm.id).then(function (response) {
                $log.debug(response.data);

                if (response.status === 200) {
                    vm.quiz = response.data;
                    //vm.quiz.questionCheck = [];
                    vm.quiz.questionBankCheck = [];
                    //vm.quiz.getItemsarray = [];
                    vm.quiz.getItemBanksarray = [];
                    if (vm.quiz.questionTime == 0) {
                        vm.quiz.questionTime = '';
                    }
                    //written below condition because when thereis no mandatory tags, below objects are assigned undefined.To avoid it making it null
                    if (vm.quiz.selectedMetaDetails == '' || angular.isUndefined(vm.quiz.selectedMetaDetails)) {
                        vm.quiz.selectedMetaDetails = [];
                        vm.quiz.metadataAssoc = {};
                    }
                    if (vm.actionType == 'edit') {
                        // angular.forEach(vm.quiz.testItems, function(value, key) {

                        //     vm.quiz.getItemsarray.push(value.itemId);
                        //     vm.quiz.questionCheck[value.itemId] = true;
                        // });
                        angular.forEach(vm.quiz.testItemBanks, function (value, key) {

                            vm.quiz.getItemBanksarray.push(value.itemBankId);
                            vm.quiz.questionBankCheck[value.itemBankId] = true;
                        });
                        // vm.itemCollection.getItemsarray = vm.itemCollection.getItemsarray.join(',');
                    }
                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "4009") {
                        vm.pageError = true;
                    }

                }

            });
        } else if (angular.isUndefined(vm.id)) {
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("tests.list")
        }

        vm.itemCollectionTablePipe = function (tableStateBank, isSearch, isClear) {
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showItemBankLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            params.version = vm.quiz.version; //Add version in request param
            //this is to clear the search filter form and display the default records

            if (isClear === true) {
                $localStorage.itemBankTableStateScopeCopy = {};
                vm.searchFilterBank.bankName = "";
                vm.searchFilterBank.description = "";

                vm.searchFilterBank.selectedMetaDetails = [];
                vm.searchFilterBank.metadataAssoc = {};
                $scope.forms.itemBankForm.clearFilterSearch();
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemBankTableStateScopeCopy && angular.isUndefined(vm.tableItemBank.itemBankTableStateScopeCopy))
                angular.extend(tableStateBank, $localStorage.itemBankTableStateScopeCopy); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableStateBank.pagination.start = 0;


                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilterBank.bankName) && vm.searchFilterBank.bankName != "")
                    searchParams.bankName = vm.searchFilterBank.bankName;

                //Add entered item description in the searchParams
                if (angular.isDefined(vm.searchFilterBank.description) && vm.searchFilterBank.description != "")
                    searchParams.description = vm.searchFilterBank.description;


                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilterBank.metadataAssoc) && !angular.equals({}, vm.searchFilterBank.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilterBank.metadataAssoc



                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableStateBank.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableStateBank.search.selectedMetaDetails = vm.searchFilterBank.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableStateBank.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilterBank.bankName = tableStateBank.search.bankName;
                vm.searchFilterBank.description = tableStateBank.search.description;

                vm.searchFilterBank.metadataAssoc = tableStateBank.search.metadataAssoc || {};
                vm.searchFilterBank.selectedMetaDetails = tableStateBank.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableStateBank.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableStateBank.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableStateBank.pagination.start / vm.tableItemBank.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableStateBank.sort.predicate))
                params.sort = (tableStateBank.sort.reverse ? '-' : '+') + tableStateBank.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+bankName";
                tableStateBank.sort.predicate = "bankName";
            }
            if (isClear === true) {
                params.sort = "+bankName";
                tableStateBank.sort.predicate = "bankName";
                tableStateBank.sort.reverse = false;
                vm.tableItemBank.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.tableItemBank.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))
            if (vm.actionType == "create") {
                params.action = 'create';

            } else if (vm.actionType == "edit") {

                params.action = 'edit';
                params.testId = vm.id;
            } else if (vm.actionType == "view" || vm.actionType == "delete") {
                params.action = 'view';
                params.testId = vm.id;
            }
            //call item service to get list of item details 
            testsService.getItemBankAssociation(params, tableStateBank).then(function (response) {
                $log.debug(response);
                vm.itemCollectionDetails = response.results.data;
                vm.tableItemBank.totalRecords = response.results.total;
                tableStateBank.pagination.numberOfPages = Math.ceil(response.results.total / vm.tableItemBank.dataPerPage);
                vm.showItemBankLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.tableItemBank.itemBankTableStateScopeCopy = tableStateBank;
                //$localStorage.itemBankTableStateScopeCopy = angular.copy(tableStateBank)
                $log.debug(response.results)

                $log.debug("Total Result:" + response.results.total);

            });
        }

        vm.itemTablePipe = function (tableState, isSearch, isClear) {
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showItemLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            params.version = vm.quiz.version; //Add version in request param
            //this is to clear the search filter form and display the default records

            if (isClear === true) {
                $localStorage.itemTableStateScopeCopy = {};
                vm.searchFilter.label = "";
                vm.searchFilter.identifier = "";
                vm.searchFilter.itemTypeId = "All";
                vm.searchFilter.status = "";
                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};

                $scope.forms.itemForm.clearFilterSearch();
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.itemTableStateScopeCopy && angular.isUndefined(vm.tableItem.itemTableStateScopeCopy))
                angular.extend(tableState, $localStorage.itemTableStateScopeCopy); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;


                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.label) && vm.searchFilter.label != "")
                    searchParams.label = vm.searchFilter.label;

                //Add entered item identifier in the searchParams
                if (angular.isDefined(vm.searchFilter.identifier) && vm.searchFilter.identifier != "")
                    searchParams.identifier = vm.searchFilter.identifier;

                //Add chosen item type in the searchParams
                if (angular.isDefined(vm.searchFilter.itemTypeId) && vm.searchFilter.itemTypeId !== "All")
                    searchParams.itemTypeId = vm.searchFilter.itemTypeId;

                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc

                //Add chosen item status in the searchParams
                if (angular.isDefined(vm.searchFilter.status) && vm.searchFilter.itemTypeId != "")
                    searchParams.status = vm.searchFilter.status;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.label = tableState.search.label;
                vm.searchFilter.identifier = tableState.search.identifier;
                vm.searchFilter.status = tableState.search.status;
                vm.searchFilter.itemTypeId = tableState.search.itemTypeId || "All";
                vm.searchFilter.metadataAssoc = tableState.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableState.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.tableItem.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+label";
                tableState.sort.predicate = "label";
            }
            if (isClear === true) {
                params.sort = "+label";
                tableState.sort.predicate = "label";
                tableState.sort.reverse = false;
                vm.tableItem.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.tableItem.dataPerPage;
            $log.debug("Passed Parameters:" + JSON.stringify(params))
            if (vm.actionType == "create") {
                params.action = 'create';

            } else if (vm.actionType == "edit") {

                params.action = 'edit';
                params.testId = vm.id;
            } else if (vm.actionType == "view" || vm.actionType == "delete") {
                params.action = 'view';
                params.testId = vm.id;
            }
            itembanksService.getItemAssociation(params, tableState).then(function (response) {
                vm.itemDetails = response.results.data;
                vm.tableItem.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.tableItem.dataPerPage);
                vm.showItemLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.tableItem.itemTableStateScopeCopy = tableState;
                // $localStorage.itemTableStateScopeCopy = angular.copy(tableState)
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)



            });

        }
        vm.quizTablePipe = function (tableStateQuiz, isSearch, isClear) {

            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showTableLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records

            if (isClear === true) {
                $localStorage.quizTableStateScopeCopy = {};
                vm.searchFilter.label = "";
                vm.searchFilter.title = "";
                vm.searchFilter.clientName = "";
                vm.searchFilter.selectedMetaDetails = [];
                vm.searchFilter.metadataAssoc = {};
                $scope.quizForm.clearFilterSearch();
            }
            //Check if any local tables exist
            //And check if vm.table.tableScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.quizTableStateScopeCopy && angular.isUndefined(vm.table.quizTableStateScopeCopy))
                angular.extend(tableStateQuiz, $localStorage.quizTableStateScopeCopy); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableStateQuiz.pagination.start = 0;
                //Add entered item name in the searchParams
                if (angular.isDefined(vm.searchFilter.label) && vm.searchFilter.label != "")
                    searchParams.label = vm.searchFilter.label;

                //Add entered item identifier in the searchParams
                if (angular.isDefined(vm.searchFilter.title) && vm.searchFilter.title != "")
                    searchParams.title = vm.searchFilter.title;

                //Add entered item identifier in the searchParams
                if (angular.isDefined(vm.searchFilter.clientName) && vm.searchFilter.clientName != "")
                    searchParams.clientName = vm.searchFilter.clientName;

                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataAssoc) && !angular.equals({}, vm.searchFilter.metadataAssoc))
                    searchParams.metadataAssoc = vm.searchFilter.metadataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableStateQuiz.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableStateQuiz.search.selectedMetaDetails = vm.searchFilter.selectedMetaDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableStateQuiz.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.title = tableStateQuiz.search.title;
                vm.searchFilter.label = tableStateQuiz.search.label;
                vm.searchFilter.clientName = tableStateQuiz.search.clientName;
                vm.searchFilter.metadataAssoc = tableStateQuiz.search.metadataAssoc || {};
                vm.searchFilter.selectedMetaDetails = tableStateQuiz.search.selectedMetaDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableStateQuiz.search);
                delete params.selectedMetaDetails; //selectedMetaDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableStateQuiz.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableStateQuiz.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableStateQuiz.sort.predicate))
                params.sort = (tableStateQuiz.sort.reverse ? '-' : '+') + tableStateQuiz.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+title";
                tableStateQuiz.sort.predicate = "title";
            }
            if (isClear === true) {
                params.sort = "+title";
                tableStateQuiz.sort.predicate = "title";
                tableStateQuiz.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))

            testsService.getAllTests(params, tableStateQuiz).then(function (response) {
                vm.quizDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableStateQuiz.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showTableLoader = false; //Hide loader
                //Save the current table state in localstorage
                vm.table.quizTableStateScopeCopy = tableStateQuiz;
                $localStorage.quizTableStateScopeCopy = angular.copy(tableStateQuiz)
                $log.debug(vm.table.quizTableStateScopeCopy)
                $log.debug("Total Result:" + response.results.total)


            });

        }
        //Used to create/update quizes
        vm.createQuiz = function () {

            vm.quiz.userId = $rootScope.userId;

            var validateFormResponse = validateFormData();

            //Check the form is valid and other custom validations.
            if (validateFormResponse && $scope.quizForm.$valid) {
                var quizData = {};

                quizData.title = vm.quiz.title;
                quizData.navigationType = vm.quiz.navigationType;
                quizData.userId = vm.quiz.userId;
                quizData.label = vm.quiz.label;
                quizData.quizTime = vm.quiz.quizTime;
                quizData.questionTime = vm.quiz.questionTime;
                quizData.overrideTimeLimit = vm.quiz.overrideTimeLimit;
                quizData.chooseQuestion = vm.quiz.chooseQuestion;
                quizData.chooseQuestion = vm.quiz.chooseQuestion;
                quizData.randomizeAnswer = vm.quiz.randomizeAnswer;
                quizData.randomizeQuestion = vm.quiz.randomizeQuestion;
                quizData.testMode = vm.quiz.testMode;
                quizData.associatedItems = vm.quiz.testItems;
                quizData.testItemBanks = vm.quiz.testItemBanks;
                quizData.metadataAssoc = vm.quiz.metadataAssoc;
                if (quizData.questionTime == '') {
                    quizData.questionTime = 0;
                }
                //Calling create item api and checking response.If status is true return to listing page else display error message.
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                    $log.debug(angular.toJson(quizData, true));
                    testsService.insertQuiz(quizData).then(function (response) {
                        if (response.status === 201) {
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', 'tests.list', false);
                        } else if (response.status === 409) {
                            var displayMsg = "ERRORS.DUPLICATE_TEST_NAME";
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                            vm.isSubmitDisabled = false;
                        } else {
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.CREATE_FAILED', '', false);
                        }

                    });
                } else {
                    quizData.testId = vm.id;
                    //quizData.associatedItems = vm.quiz.testItems;
                    quizData.associatedItemBanks = vm.quiz.testItemBanks;
                    //quizData.dissociatedItems = vm.quiz.dissociatedItems;
                    //quizData.dissociatedItemBanks = vm.quiz.dissociatedItemBanks;
                    $log.debug(angular.toJson(quizData, true));
                    //Calling update item api and checking response
                    testsService.updateQuiz(quizData, vm.id).then(function (response) {
                        if (response.status === 204) { //On successfull update
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.EDIT_SUCCESS', '', false);
                            vm.itemTablePipe(vm.tableItem.itemTableStateScopeCopy, true);
                            vm.activeTabIndex = 0;
                            vm.quiz.questionTime = '';
                        } else if (response.status === 409) {
                            var displayMsg = "ERRORS.DUPLICATE_TEST_NAME";
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                            vm.isSubmitDisabled = false;
                        } else { //On update failure
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.EDIT_FAILED', '', false);
                        }

                        testsService.getQuizById(vm.id).then(function (response) {
                            $log.debug(response.data);

                            if (response.status === 200) {
                                vm.quiz = response.data;
                                //vm.quiz.questionCheck = [];
                                vm.quiz.questionBankCheck = [];
                                //vm.quiz.getItemsarray = [];
                                vm.quiz.getItemBanksarray = [];
                                if (vm.quiz.questionTime == 0) {
                                    vm.quiz.questionTime = '';
                                }
                                //written below condition because when thereis no mandatory tags, below objects are assigned undefined.To avoid it making it null
                                if (vm.quiz.selectedMetaDetails == '' || angular.isUndefined(vm.quiz.selectedMetaDetails)) {
                                    vm.quiz.selectedMetaDetails = [];
                                    vm.quiz.metadataAssoc = {};
                                }
                                if (vm.actionType == 'edit') {
                                    // angular.forEach(vm.quiz.testItems, function(value, key) {

                                    //     vm.quiz.getItemsarray.push(value.itemId);
                                    //     vm.quiz.questionCheck[value.itemId] = true;
                                    // });
                                    angular.forEach(vm.quiz.testItemBanks, function (value, key) {

                                        vm.quiz.getItemBanksarray.push(value.itemBankId);
                                        vm.quiz.questionBankCheck[value.itemBankId] = true;
                                    });
                                    // vm.itemCollection.getItemsarray = vm.itemCollection.getItemsarray.join(',');
                                }
                            } else if (response.status === 404) { //Error page in case data not found on server
                                if (response.data.code == "4009") {
                                    vm.pageError = true;
                                }

                            }

                        });

                    });

                }
            }
        }

        var validateFormData = function () {
            var errorFlag = 0;

            if (vm.quiz.quizTime > config.quizTime) {
                $scope.quizForm.quizTime.$error.timelimiterror = true;
                errorFlag = 1;
            } else {
                $scope.quizForm.quizTime.$error.timelimiterror = false;
            }
            if (vm.quiz.questionTime > (vm.quiz.quizTime * 60)) {
                $scope.quizForm.questionTime.$error.validationerror = true;
                errorFlag = 1;
            } else {
                $scope.quizForm.questionTime.$error.validationerror = false;

            }
            //vm.quiz.testItems = [];
            vm.quiz.testItemBanks = [];
            // angular.forEach(vm.quiz.questionCheck, function(value, key) {

            //     if (value == true) //get all records to be added
            //         vm.quiz.testItems.push(key);
            // });
            angular.forEach(vm.quiz.questionBankCheck, function (value, key) {

                if (value == true) //get all records to be added
                    vm.quiz.testItemBanks.push(key);
            });
            //Question and QB validation for admin(testType =1)
            if (vm.quiz.testItems.length == 0 && vm.quiz.testItemBanks.length == 0 && vm.quiz.testType == 1) {
                vm.errorMsg = 'ERRORS.SELECT_QUESTION';
                vm.errorMsgBank = 'ERRORS.SELECT_QUESTIONBANK';
                $scope.accordion.groups[0].isOpen = true;
                errorFlag = 1;
            } else {
                vm.errorMsg = '';
                vm.errorMsgBank = '';
                //vm.quiz.testItems = vm.quiz.testItems.join(',');

                //var associatedItems = vm.quiz.testItems.split(','); //convert string to array

                // if (vm.actionType == "edit") {
                //     vm.checkobjectToarrayItem = 1;
                //     //this logic is used to find the difference in associated array and checked array, and get ids which are dissociated.
                //     var seen = [];
                //     vm.quiz.dissociatedItems = [];

                //     for (var i = 0; i < associatedItems.length; i++)
                //         seen[associatedItems[i]] = true;
                //     for (var i = 0; i < vm.quiz.getItemsarray.length; i++)
                //         if (!seen[vm.quiz.getItemsarray[i]])
                //             vm.quiz.dissociatedItems.push(vm.quiz.getItemsarray[i]);

                //     vm.quiz.dissociatedItems = vm.quiz.dissociatedItems.join(',');
                //     vm.quiz.getItemsarray = [];
                //     for (var i = 0; i < associatedItems.length; i++) {

                //         vm.quiz.getItemsarray.push(associatedItems[i]);
                //     }
                // }

            }
            if (vm.quiz.testItemBanks.length > 0) {
                vm.errorMsgBank = '';
                vm.errorMsg = '';
                vm.quiz.testItemBanks = vm.quiz.testItemBanks.join(',');

                var associatedItemBanks = vm.quiz.testItemBanks.split(','); //convert string to array

                if (vm.actionType == "edit") {
                    vm.checkobjectToarrayItemBank = 1;
                    //this logic is used to find the difference in associated array and checked array, and get ids which are dissociated.
                    var seen = [];
                    vm.quiz.dissociatedItemBanks = [];
                    for (var i = 0; i < associatedItemBanks.length; i++)
                        seen[associatedItemBanks[i]] = true;
                    for (var i = 0; i < vm.quiz.getItemBanksarray.length; i++)
                        if (!seen[vm.quiz.getItemBanksarray[i]])
                            vm.quiz.dissociatedItemBanks.push(vm.quiz.getItemBanksarray[i]);

                    vm.quiz.dissociatedItemBanks = vm.quiz.dissociatedItemBanks.join(',');
                    vm.quiz.getItemBanksarray = [];
                    for (var i = 0; i < associatedItemBanks.length; i++) {
                        vm.quiz.getItemBanksarray.push(associatedItemBanks[i]);

                    }
                }
            }
            if (errorFlag == 1) {
                return false;
            }
            return true;


        }
        //Used for alerting on success/failure
        vm.alertConfig.timeOutAlert = function (cssClass, alertMsg, redirectState, isList) {
            $window.scroll(0, 0);
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (redirectState != '') { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }

        }
        vm.deleteQuiz = function (isDeleteAll) {
            vm.isSubmitDisabled = true;
            var params = {};
            params.isDeleteAll = isDeleteAll;
            if (!isDeleteAll)
                params.version = (!isDeleteAll) ? vm.quiz.version : '';

            testsService.deleteQuiz(vm.id, params).then(function (response) {
                if (response.status == 200) {

                    //vm.item.deletedInfo = response.data;
                } else if (response.status == 204) {
                    vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.DELETE_SUCCESS', 'tests.list', false);
                } else {
                    vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.DELETE_FAILED', 'tests.list', false);
                }
            });
        }

        vm.selectItem = function (itemDetails) {
            var itemIndex = vm.isItemExist(itemDetails.id)
            if (itemIndex == -1) {
                var itemData = {itemId: parseInt(itemDetails.id), label: itemDetails.label, version: itemDetails.version}
                vm.quiz.testItems.push(itemData);
            } else {
                vm.quiz.testItems.splice(itemIndex, 1);
            }
        }

        vm.isItemExist = function (itemId) {
            vm.quiz.testItems = vm.quiz.testItems || [];
            itemId = parseInt(itemId)
            return vm.quiz.testItems.map(function (item) {
                return item.itemId;
            }).indexOf(itemId);
        }

        vm.inspectItem = function () {
            $log.debug("$#%#$#$%");
            //vm.quiz.testItems = [];
            vm.quiz.testItemBanks = [];
            // angular.forEach(vm.quiz.questionCheck, function(value, key) {

            //     if (value == true) //get all records to be added
            //         vm.quiz.testItems.push(key);
            // });
            angular.forEach(vm.quiz.questionBankCheck, function (value, key) {

                if (value == true) //get all records to be added
                    vm.quiz.testItemBanks.push(key);
            });
            if (vm.quiz.testItems.length == 0 && vm.quiz.testItemBanks.length == 0) {
                vm.errorMsg = 'ERRORS.SELECT_QUESTION';
                vm.errorMsgBank = 'ERRORS.SELECT_QUESTIONBANK';
                $scope.accordion.groups[0].isOpen = true;

            } else {
                vm.errorMsg = '';
                vm.errorMsgBank = '';
            }
        }

        vm.changeVersion = function () {
            var params = {};
            vm.showLoader = true;
            vm.isSubmitDisabled = false;
            params.version = vm.quiz.version;
            //Get test detail from server
            testsService.getQuizById(vm.id, params).then(function (response) {

                $log.debug(response);
                if (response.status === 200) {
                    vm.quiz = response.data; //Get quiz details
                    vm.itemTablePipe(vm.tableItem.itemTableStateScopeCopy, false, true); //Get test associated items
                    vm.itemCollectionTablePipe(vm.tableItemBank.itemBankTableStateScopeCopy, false, true); //Get test associated itembanks

                } else if (response.status === 404) { //Error page in case data not found on server
                    if (response.data.code == "4009") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;
            });
        }

    }]);



})();

(function () {
    'use strict';

    angular.module('app.user').controller('UserController', ['$rootScope', '$scope', '$window', '$log', '$localStorage', '$filter', '$timeout', 'config', 'userService', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, userService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;
        vm.checkRecord = [];
        vm.unCheckRecord = [];
        vm.table = vm.searchFilter = vm.user = {};
        vm.table1 = {}, vm.table1.totalRecords = 0;
        vm.showdropdown = 0;
        vm.selectedOptionRole = {};
        vm.selectedOptionGroup = {};
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.passwordregex = '^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$';
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        //vm.emailRegex = '^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$';
        vm.emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        vm.phoneregex = /^(?=.*[0-9])[- +()0-9]+$/;
        var selectedGroup = [];
        var selectedRole = [];
        vm.alertConfig = {'show': false};
        vm.showdropdown = function () {

            vm.showdropdown = 1;

        };
        vm.pageError = false;
        vm.showLoader = true;

        var userParam = {};
        if ($localStorage.userTypeList) {
            vm.userTypeList = $localStorage.userTypeList;
            //select default country as 
            angular.forEach(vm.userTypeList, function (value, key) {
                if (value.userTypeName == 'ADMIN')
                {
                    vm.userType = value.userTypeId;
                }
            })

        } else {
            //call user service to get list of usertype
            userService.getuserTypeList().then(function (response) {
                $localStorage.userTypeList = vm.userTypeList = response.data;
                angular.forEach(vm.userTypeList, function (value, key) {
                    if (value.userTypeName == 'ADMIN')
                    {
                        vm.userType = value.userTypeId;
                    }
                })

            });

        }
        if ($localStorage.statusList) {
            vm.statusList = $localStorage.statusList;
            //select default country as 
            angular.forEach(vm.statusList, function (value, key) {

                if (value.statusName == 'ACTIVE')
                {
                    vm.activeValue = value.statusCode;
                }
                if (value.statusName == 'INACTIVE')
                {
                    vm.inactiveValue = value.statusCode;
                }
                vm.user.status = vm.activeValue;
            });
            vm.user.status = vm.activeValue;

        } else {
            //call user service to get list of states    
            userService.getStatus().then(function (response) {
                $localStorage.statusList = vm.statusList = response.data;
                angular.forEach(vm.statusList, function (value, key) {
                    $log.debug(value);
                    if (value.statusName == 'ACTIVE')
                    {
                        vm.activeValue = value.statusCode;
                    }
                    if (value.statusName == 'INACTIVE')
                    {
                        vm.inactiveValue = value.statusCode;
                    }
                    vm.user.status = vm.activeValue;
                });

            });
        }
        if (vm.actionType == "create" || vm.actionType == 'edit') {
            //Get Country list from server or from local storage
            if ($localStorage.countryList) {
                vm.countryList = $localStorage.countryList;
                //select default country as 
                vm.user.selectedOptionCountry = vm.countryList[0];
                var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                userService.getstateList(selectedCountryId).then(function (response) {
                    vm.stateList = response.data;
                    vm.user.selectedOptionState = '';
                });

            } else {
                //call user service to get list of country    
                userService.getcountryList().then(function (response) {
                    $localStorage.countryList = vm.countryList = response.data;
                    //select default country as 
                    vm.user.selectedOptionCountry = vm.countryList[0];
                    var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                    //call user service to get list of states    
                    userService.getstateList(selectedCountryId).then(function (response) {
                        vm.stateList = response.data;
                        vm.user.selectedOptionState = '';
                    });
                });

            }
            //Get State list from server or from local storage

            //call user service to get list of states    
            userService.getstateList(selectedCountryId).then(function (response) {
                vm.stateList = response.data;
                vm.user.selectedOptionState = '';
            });





            //Get State list from server or from local storage

            //call user service to get list of states    
            userService.getRolesList().then(function (response) {
                vm.rolesList = response.results.data;
                $log.debug(vm.rolesList);
                vm.user.selectedOptionRole = vm.rolesList[0];
            });


            //multiselect dropdown logic.
            vm.checkAll = function () {

                if (vm.selectedAll) {
                    vm.selectedAll = true;
                } else {
                    vm.selectedAll = false;
                }

                angular.forEach(vm.rolesList, function (value, key) {
                    vm.selectedOptionRole[value.roleId] = vm.selectedAll;
                });

            };

            vm.inspectcheckAll = function () {
//                vm.count = 0;
//                angular.forEach(vm.selectedOptionRole, function (value, key) {
//                    if (value == true) {
//                        vm.count = vm.count + 1;
//                    }
//                });
//                if (vm.count == vm.rolesList.length) {
//                    vm.selectedAll = true;
//                } else {
//                    vm.selectedAll = false;
//                }
            var userParam = {};
            if (angular.isUndefined(vm.selectRole))
            {
                vm.errorMsg = 'ERRORS.SELECT_ROLE_OR_GROUP';
                return false;
            } else {
                vm.errorMsg = "";

            }
            userParam.selectedRoleGroup = vm.selectRole;
            $log.debug(userParam.selectedRoleGroup);
            $log.debug(vm.selectedOptionGroup);
            if (userParam.selectedRoleGroup == 1)
            {
                //get all the roles selected
                userParam.getRoles = [];
                angular.forEach(vm.selectedOptionRole, function (value, key) {
                    if (value == true)
                    {
                        userParam.getRoles.push(key);
                    }
                });
                if (userParam.getRoles.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_ROLE';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getRoles = userParam.getRoles.join(',');
            }
            else if (userParam.selectedRoleGroup == 2)
            {
                //get all the roles selected
                userParam.getGroups = [];

                angular.forEach(vm.selectedOptionGroup, function (value, key) {
                    if (value == true)
                    {
                        userParam.getGroups.push(key);
                    }
                });
                $log.debug("$$$$$$$$$$"+userParam.getGroups.length);
                if (userParam.getGroups.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_GROUP';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getGroups = userParam.getGroups.join(',');
            }
            }

            //Get State list from server or from local storage

            //call user service to get list of states    
            userService.getGroupsList().then(function (response) {
                vm.groupsList = response.results.data;
                vm.user.selectedOptionGroup = vm.groupsList[0];
            });



            //multiselect dropdown logic.
            vm.checkAllGroup = function () {

                if (vm.selectAllGroup) {
                    vm.selectAllGroup = true;
                } else {
                    vm.selectAllGroup = false;
                }

                angular.forEach(vm.groupsList, function (value, key) {

                    vm.selectedOptionGroup[value.groupId] = vm.selectAllGroup;
                });

            };

            vm.inspectcheckAllGroup = function () {
                vm.count = 0;
                angular.forEach(vm.selectedOptionGroup, function (value, key) {
                    if (value == true) {
                        vm.count = vm.count + 1;
                    }
                });
                if (vm.count == vm.groupsList.length) {
                    vm.selectAllGroup = true;
                } else {
                    vm.selectAllGroup = false;
                }
            }
            vm.showLoader = false;
        }


        //Assign default values and perform actions based on actionType 
        if (vm.actionType == "list") {
            $log.debug($localStorage.userTableState);
            if (angular.isDefined($localStorage.userTableState) && angular.isDefined($localStorage.userTableState.pagination) && angular.isDefined($localStorage.userTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.userTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.USER_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['user'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['user'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['user'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['user'].indexOf('view') !== -1 ? true : false;
            vm.permission['manageSecurity'] = $rootScope.permission['user'].indexOf('manageSecurity') !== -1 ? true : false;
            //Uncomment the below line to activate the User association link
            vm.permission['manageAssociation'] = $rootScope.permission['user'].indexOf('manageAssociation') !== -1 ? true : false;
            vm.selectOption = '';
            //console.log(vm.permission.indexOf('edit') === -1)
            vm.showLoader = false;
        }
        else if (vm.actionType == "create")
        {
            vm.pageTitle = 'ALERTS.CREATE_NEW_USER';
            vm.showLoader = false;
        }
        else if (vm.actionType == "edit")
        {
            vm.pageTitle = 'PAGE_TITLE.USER_EDIT';
            vm.id = $rootScope.$stateParams.id;

            userService.getUserById(vm.id).then(function (response) {
                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.middleInitial = vm.user.middleName;
                    vm.user.country = vm.user.countryId;
                    vm.user.state = vm.user.stateId;
                    vm.user.postalcode = vm.user.postalCode;
                    vm.user.contactHome = vm.user.phone1;
                    vm.user.contactOffice = vm.user.phone2;
                    vm.selectRole = vm.user.userBelongsTo;
                    vm.user.userType = vm.user.userTypeId;
                    var flag = 0
                    //selecting country based on saved value during creation
                    angular.forEach(vm.countryList, function (values, key) {
                        if (values.countryId == vm.user.country && flag == 0)
                        {
                            vm.user.selectedOptionCountry = values;
                            var selectedCountryId = values.countryId;

                            userService.getstateList(selectedCountryId).then(function (response) {
                                vm.stateList = response.data;
                                var stateflag = 0;
                                //vm.user.selectedOptionState = vm.stateList[vm.user.stateId]; 
                                angular.forEach(vm.stateList, function (values, key) {

                                    if (values.stateId == vm.user.stateId && stateflag == 0)
                                    {
                                        vm.user.selectedOptionState = values;
                                        stateflag = 1;
                                    }
                                });
                                // vm.user.selected_State = vm.user.stateId;
                                $log.debug(vm.user.selected_State);
                            });
                            flag = 1;

                        }
                    });

                    //selecting roles based on saved value during creation
                    angular.forEach(vm.user.role, function (value, key) {

                        vm.selectedOptionRole[value.roleId] = true;

                    });
                    //selecting roles based on saved value during creation
                    angular.forEach(vm.user.group, function (value, key) {

                        vm.selectedOptionGroup[value.groupId] = true;

                    });
                } else if (response.status === 404) {
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }
                }
            });
            vm.showLoader = false;
        }
        else if ((vm.actionType == "view" || vm.actionType == "delete") && $rootScope.$stateParams.id !== '') {
            vm.id = $rootScope.$stateParams.id;
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.USER_VIEW_LABEL"; //Page title mapped to locale json key of view label
            else
                vm.pageTitle = "PAGE_TITLE.USER_DELETE_LABEL"; //Page title mapped to locale json key of delete label

            //Get user for the given id by calling user/{id} api
            userService.getUserById(vm.id).then(function (response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.email;
                    vm.user.middleInitial = vm.user.middleName;
                    vm.user.country = vm.user.countryId;
                    vm.user.state = vm.user.stateId;
                    vm.user.postalcode = vm.user.postalCode;
                    vm.selectRole = vm.user.userBelongsTo;

                    if (vm.user.status == vm.activeValue)
                    {
                        vm.user.status = "LABELS.STATUS_ACTIVE";
                    }
                    else if (vm.user.status == vm.inactiveValue)
                    {
                        vm.user.status = "LABELS.STATUS_INACTIVE";
                    }

                    if (vm.selectRole == 1)
                    {
                        angular.forEach(vm.user.role, function (value, key) {
                            var rolename = value.roleName;
                            selectedRole.push(rolename);
                            vm.role = selectedRole.join(',');
                        });
                    }
                    else if (vm.selectRole == 2)
                    {
                        angular.forEach(vm.user.group, function (value, key) {

                            selectedGroup.push(value.groupName);
                            vm.group = selectedGroup.join(',');
                        });
                    }
                    $log.debug(vm.selectedOptionRole);

                } else if (response.status === 404) {
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }

                }

                vm.showLoader = false;

            });


        } else if (vm.actionType == "association") {
            vm.searchFilter.userdataAssoc = {}, vm.searchFilter.selectedUserDetails = [];
            //vm.searchFilter.status = "Authoring";
            if (angular.isDefined($localStorage.userAssociateTableState) && angular.isDefined($localStorage.userAssociateTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.userAssociateTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options


            vm.pageTitle = "PAGE_TITLE.USER_ASSOCIATION_TITLE";

            vm.id = $rootScope.$stateParams.id;

            //vm.closeOtherAccordions = true, 
            vm.otherInfo = false;




        }
        else if (angular.isUndefined(vm.id)) {

            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("user.list")
        }


        //User list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.userTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {

                $localStorage.userTableState = {};
                vm.searchFilter.firstName = "";
                vm.searchFilter.lastName = "";
                vm.searchFilter.userEmail = "";
                vm.searchFilter.role = "";
                vm.searchFilter.group = "";
                vm.searchFilter.selectRole = "";

            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.userTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;


                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.firstName) && vm.searchFilter.firstName != "")
                    searchParams.firstName = vm.searchFilter.firstName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.lastName) && vm.searchFilter.lastName != "")
                    searchParams.lastName = vm.searchFilter.lastName;

                //Add entered email in the searchParams
                if (angular.isDefined(vm.searchFilter.userEmail) && vm.searchFilter.userEmail !== "")
                    searchParams.emailAddress = vm.searchFilter.userEmail;

                searchParams.selectedRoleGroup = vm.searchFilter.selectRole;

                //Add entered role name in the searchParams
                if (angular.isDefined(vm.searchFilter.role) && vm.searchFilter.role !== "" && searchParams.selectedRoleGroup == 1)
                {
                    searchParams.role = vm.searchFilter.role;
                    vm.errorMsg = "";
                }
                else if ((angular.isUndefined(vm.searchFilter.role) || vm.searchFilter.role == '') && searchParams.selectedRoleGroup == 1)
                {
                    vm.errorMsg = "ERRORS.REQUIRED_ERROR";
                    flag = 1;
                }

                //Add entered group name in the searchParams
                if (angular.isDefined(vm.searchFilter.group) && vm.searchFilter.group !== "" && searchParams.selectedRoleGroup == 2)
                {
                    searchParams.group = vm.searchFilter.group;
                    vm.errorMsg = "";

                }
                else if ((angular.isUndefined(vm.searchFilter.group) || vm.searchFilter.group == '') && searchParams.selectedRoleGroup == 2)
                {
                    vm.errorMsg = "ERRORS.REQUIRED_ERROR";
                    flag = 1;
                }

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.firstName = tableState.search.firstName;
                vm.searchFilter.lastName = tableState.search.lastName;
                vm.searchFilter.userEmail = tableState.search.emailAddress;
                vm.searchFilter.role = tableState.search.role;
                vm.searchFilter.group = tableState.search.group;

                if ((tableState.search.selectedRoleGroup == 1 && angular.isDefined(tableState.search.role)) || (tableState.search.selectedRoleGroup == 2 && angular.isDefined(tableState.search.group)))
                {
                    vm.searchFilter.selectRole = tableState.search.selectedRoleGroup;
                }
                else
                {
                    vm.searchFilter.selectRole = '';
                }
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                //console.log(vm.searchFilter.metadataType)
            }
            if (flag == 0)
            {
                vm.showLoader = true;
                vm.selectOption = vm.searchFilter.selectRole;
                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by user email
                    params.sort = "+firstName";
                    tableState.sort.predicate = "firstName";
                }
                if (isClear === true) {
                    params.sort = "+firstName";
                    tableState.sort.predicate = "firstName";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = 10;
                    //console.log(tableState.sort)
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call metadata service to get list of metadata details 
                userService.getuserDetails(params, tableState).then(function (response) {

                    vm.userDetails = response.results.data;

                    $log.debug(response);
                    vm.table.totalRecords = response.results.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.userTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.results.total)
                });
            }
        };
        vm.createUser = function () {


            userParam = userFormValidation();
            
            $log.debug($scope.userForm.$valid);
            $log.debug(vm.user);
            //calling create user api and checking response.If status is true return to listing page else display error message.
            if (userParam && $scope.userForm.$valid == true) {
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                    userParam.userType = vm.userType;
                    userService.insertUser(userParam).then(function (response) {

                        $window.scroll(0, 0);
                        if (response.status === 201) {
                            vm.alertConfig.class = 'wk-alert-success';
                            vm.alertConfig.details = 'ALERTS.CREATE_SUCCESS';
                            vm.alertConfig.isList = false;
                            $timeout(function () {
                                vm.alertConfig.show = false; //Hides alert
                                $rootScope.$state.go('user.list');
                            }, 2000);
                        }
                        else if (response.status === 409) {
                            if(response.data.code === 5003)
                            {
                            var displayMsg = 'ERRORS.DUPLICATE_USER_NAME';
                            }
                            else if(response.data.code === 5010)
                            {
                            var displayMsg = 'ERRORS.INVALID_EMAIL';   
                            }
                            vm.alertConfig.class = 'wk-alert-danger';
                            // vm.alertConfig.details = [{ "errorMsg": "DUPLICATE_TAG_NAME" }];
                            vm.alertConfig.details = displayMsg;
                            vm.alertConfig.isList = false;
                        }
                    });
                } else {
                    //calling update metadata api and checking response
                    userParam.changePassword = vm.user.changePassword;
                    userParam.userType = vm.user.userType;
                    $log.debug(userParam);
                    userService.updateUser(userParam, vm.id).then(function (response) {
                        $window.scroll(0, 0);
                        if (response.status === 204) {
                            vm.alertConfig.class = 'wk-alert-success';
                            vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                            vm.alertConfig.isList = false;

                        } else if (response.status === 409) {
                            if(response.data.code === 5003)
                            {
                            var displayMsg = 'ERRORS.DUPLICATE_USER_NAME';
                            }
                            else if(response.data.code === 5010)
                            {
                            var displayMsg = 'ERRORS.INVALID_EMAIL';   
                            }
                           
                            vm.alertConfig.class = 'wk-alert-danger';
                            vm.alertConfig.details = displayMsg;
                            vm.alertConfig.isList = false;
                        } else {
                            vm.alertConfig.class = 'wk-alert-danger';
                            vm.alertConfig.details = 'ALERTS.EDIT_FAILED';
                            vm.alertConfig.isList = false;

                        }
                    });

                }
                vm.alertConfig.show = true;

            }
        };

        var userFormValidation = function () {
            var userParam = {};
            if(vm.user.password != vm.user.confirmPassword)
            {
                
                $scope.userForm.$valid = false;
                return false;
            }
            
            userParam.userName = vm.user.userName;
            userParam.userEmail = vm.user.userEmail;
            userParam.password = vm.user.password;
            userParam.firstName = vm.user.firstName;
            if (!angular.isUndefined(vm.user.middleInitial))
            {
                userParam.middleInitial = vm.user.middleInitial;
            }
            userParam.lastName = vm.user.lastName;
            userParam.address1 = vm.user.address1;
            if (!angular.isUndefined(vm.user.address2))
            {
                userParam.address2 = vm.user.address2;
            }
            if (!angular.isUndefined(vm.user.address3))
            {
                userParam.address3 = vm.user.address3;
            }
            if (!angular.isUndefined(vm.user.address4))
            {
                userParam.address4 = vm.user.address4;
            }
            userParam.phone1 = vm.user.contactHome;
            userParam.phone2 = vm.user.contactOffice;
            userParam.city = vm.user.city;

            userParam.countryId = vm.user.selectedOptionCountry.countryId;
            userParam.stateId = vm.user.selectedOptionState.stateId;
            
            if (angular.isUndefined(vm.selectRole))
            {
                vm.errorMsg = 'ERRORS.SELECT_ROLE_OR_GROUP';
                return false;
            } else {
                vm.errorMsg = "";

            }
            userParam.selectedRoleGroup = vm.selectRole;
            $log.debug(userParam.selectedRoleGroup);
            $log.debug(vm.selectedOptionGroup);
            if (userParam.selectedRoleGroup == 1)
            {
                //get all the roles selected
                userParam.getRoles = [];
                angular.forEach(vm.selectedOptionRole, function (value, key) {
                    if (value == true)
                    {
                        userParam.getRoles.push(key);
                    }
                });
                if (userParam.getRoles.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_ROLE';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getRoles = userParam.getRoles.join(',');
            }
            else if (userParam.selectedRoleGroup == 2)
            {
                //get all the roles selected
                userParam.getGroups = [];

                angular.forEach(vm.selectedOptionGroup, function (value, key) {
                    if (value == true)
                    {
                        userParam.getGroups.push(key);
                    }
                });
                $log.debug("$$$$$$$$$$"+userParam.getGroups.length);
                if (userParam.getGroups.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_GROUP';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getGroups = userParam.getGroups.join(',');
            }
            userParam.postalcode = vm.user.postalcode;
            userParam.status = vm.user.status;
            userParam.userId = $rootScope.userId;

            if (vm.selectRole == '')
            {
                vm.errorMsg = '';
            }
            $log.debug(userParam);
            return userParam;


        }
        vm.getStates = function () {
            var selectedCountryId = vm.user.selectedOptionCountry.countryId;

            userService.getstateList(selectedCountryId).then(function (response) {
                vm.stateList = response.data;
                vm.user.selectedOptionState = '';
            });

        }
        vm.copyemail = function () {

            if (vm.user_name_email == true)
            {
                vm.user.userName = vm.user.userEmail;
            }
            else if (vm.user_name_email == false)
            {
                vm.user.userName = '';
            }

        }
        vm.unchecksame = function () {
            vm.user_name_email = false;
        }

        //Deletes the user based on user id
        vm.deleteUser = function () {
            userService.deleteUser(vm.id).then(function (response) {
                vm.alertConfig.show = true;
                $window.scroll(0, 0);
                if (response) {
                    vm.alertConfig.class = 'wk-alert-success';
                    vm.alertConfig.msg = 'ALERTS.DELETE_SUCCESS';

                } else {
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = 'ALERTS.DELETE_FAILED';
                }

                vm.alertConfig.isList = false;
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go('user.list');
                }, 2000);
            });
        }

        //Called when alert display time out
        vm.closeAlert = function () {
            vm.alertConfig.show = false; //Hides alert
            if (vm.alertConfig.class == "wk-alert-success" && !vm.validationError)
                $rootScope.$state.go('user.list');
        };

        //User association related information
        vm.userNonAssociateTablePipe = function (tableState, isSearch, isClear) {
           
            vm.errorMsg = '';
            vm.associatedErrorMsg = '';
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userNonAssociateTableState = {};
                if (vm.preSelectRole == 1)
                {
                    vm.searchFilter.roleNameNonAssociated = "";
                }
                else if (vm.preSelectRole == 2)
                {
                    vm.searchFilter.groupNameNonAssociated = "";
                }

                vm.searchFilter.selectedUserAssocDetails = [];
                vm.searchFilter.userdataAssoc = {};
                vm.questionCheck = [];
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userNonAssociateTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.userNonAssociateTableState); //Extend the stored table state with the current one. 
            //
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                if (vm.preSelectRole == 1)
                {
                    //Add entered item name in the searchParams
                    if (angular.isDefined(vm.searchFilter.roleNameNonAssociated) && vm.searchFilter.roleNameNonAssociated != "")
                        searchParams.roleName = vm.searchFilter.roleNameNonAssociated;
                }
                else if (vm.preSelectRole == 2)
                {
                    //Add entered item name in the searchParams
                    if (angular.isDefined(vm.searchFilter.groupNameNonAssociated) && vm.searchFilter.groupNameNonAssociated != "")
                        searchParams.groupName = vm.searchFilter.groupNameNonAssociated;
                }



                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.userdataAssoc) && !angular.equals({}, vm.searchFilter.userdataAssoc))
                    searchParams.userdataAssoc = vm.searchFilter.userdataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedUserAssocDetails = vm.searchFilter.selectedUserAssocDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model


                vm.searchFilter.userdataAssoc = tableState.search.userdataAssoc || {};
                vm.searchFilter.selectedUserAssocDetails = tableState.search.selectedUserAssocDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedUserAssocDetails; //selectedUserAssocDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
           { //Default Sorting by item tag name
                if (vm.preSelectRole == 1)
                {
                    params.sort = (tableState.sort.reverse ? '-' : '+') + "roleName";
                   
                }
                else if (vm.preSelectRole == 2)
                {
                   params.sort = (tableState.sort.reverse ? '-' : '+') + "groupName";
                }
            }
            if (isClear === true) {
                if (vm.preSelectRole == 1)
                {
                    params.sort = "+roleName";
                    //tableState.sort.predicate = "roleNameNonAssociated";
                }
                else if (vm.preSelectRole == 2)
                {
                    params.sort = "+groupName";
                    //tableState.sort.predicate = "groupNameNonAssociated";
                }
                tableState.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
            if(angular.isUndefined(vm.preSelectRole))
            {
            //call item service to get list of item details 
            userService.getUserById(vm.id).then(function (response) {
                $log.debug(response);

                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.firstName = vm.user.firstName;
                    vm.user.lastName = vm.user.lastName;
                    vm.preSelectRole = vm.user.userBelongsTo;


                    params.associated = 0;
                    params.associatedUserId = vm.id;
                    params.UserId = $rootScope.userId;
                    params.selectedButton = vm.preSelectRole;
                    $log.debug("Passed Parameters NA:" + JSON.stringify(params))
                    userService.getuserAssociatedDetails(params).then(function (response) {

                        vm.userNonAssociatedDetails = response.results.data;
                        vm.table.totalRecords = response.results.total;
                        tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table.tableStateScopeCopy = tableState;
                        $localStorage.userNonAssociateTableState = angular.copy(tableState)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)


                    });
                }
                else if (response.status === 404) { //Error in case of data not found on server
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;
            });
        }
        else
        {
             params.associated = 0;
                    params.associatedUserId = vm.id;
                    params.UserId = $rootScope.userId;
                    params.selectedButton = vm.preSelectRole;
                    $log.debug("Passed Parameters NA:" + JSON.stringify(params))
                    userService.getuserAssociatedDetails(params).then(function (response) {

                        vm.userNonAssociatedDetails = response.results.data;
                        vm.table.totalRecords = response.results.total;
                        tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table.tableStateScopeCopy = tableState;
                        $localStorage.userNonAssociateTableState = angular.copy(tableState)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)


                    });
        }
        }
        //This will be called for displaying associated records.
        vm.userAssociateTablePipe = function (tableState1, isSearch, isClear) {
            vm.errorMsg = '';
            vm.associatedErrorMsg = '';
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userAssociateTableState = {};
                if (vm.preSelectRole == 1)
                {
                    vm.searchFilter.roleName = "";
                }
                else if (vm.preSelectRole == 2)
                {
                    vm.searchFilter.groupName = "";
                }


                vm.searchFilter.selectedUserAssocDetails = [];
                vm.searchFilter.userdataAssoc = {};
                vm.userAssocUnCheck = [];
            }
            //Check if any local tables exist
            //And check if vm.table1.tableStateScopeCopy1 is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userAssociateTableState && angular.isUndefined(vm.table1.tableStateScopeCopy1))
                angular.extend(tableState1, $localStorage.userAssociateTableState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState1.pagination.start = 0;
                vm.showLoader = true;

                //Add entered item name in the searchParams
                if (vm.preSelectRole == 1)
                {
                    if (angular.isDefined(vm.searchFilter.roleName) && vm.searchFilter.roleName != "")
                        searchParams.roleName = vm.searchFilter.roleName;
                }
                else if (vm.preSelectRole == 2)
                {
                    if (angular.isDefined(vm.searchFilter.groupName) && vm.searchFilter.groupName != "")
                        searchParams.groupName = vm.searchFilter.groupName;
                }

                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.userdataAssoc) && !angular.equals({}, vm.searchFilter.userdataAssoc))
                    searchParams.userdataAssoc = vm.searchFilter.userdataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState1.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState1.search.selectedUserAssocDetails = vm.searchFilter.selectedUserAssocDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState1.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model



                vm.searchFilter.userdataAssoc = tableState1.search.userdataAssoc || {};
                vm.searchFilter.selectedUserAssocDetails = tableState1.search.selectedUserAssocDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState1.search);
                delete params.selectedUserAssocDetails; //selectedUserAssocDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState1.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState1.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            
            { //Default Sorting by item tag name
                if (vm.preSelectRole == 1)
                {
                    params.sort = (tableState1.sort.reverse ? '-' : '+') + "roleName";
                   
                }
                else if (vm.preSelectRole == 2)
                {
                   params.sort = (tableState1.sort.reverse ? '-' : '+') + "groupName";
                }
            }
            if (isClear === true) {
                if (vm.preSelectRole == 1)
                {
                    params.sort = "+roleName";
                    //tableState1.sort.predicate = "roleName";
                }
                else if (vm.preSelectRole == 2)
                {
                    params.sort = "+groupName";
                   // tableState1.sort.predicate = "groupName";
                }
                tableState1.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table1.dataPerPage;

            if(angular.isUndefined(vm.preSelectRole))
            {
            //Get item detail from server based on item id
            userService.getUserById(vm.id).then(function (response) {
                $log.debug(response);

                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.firstName = vm.user.firstName;
                    vm.user.lastName = vm.user.lastName;
                    vm.preSelectRole = vm.user.userBelongsTo;
                    //params.userId = $rootScope.userId;
                    params.associated = 1;
                    params.associatedUserId = vm.id;
                    params.selectedButton = vm.preSelectRole;
                    params.UserId = $rootScope.userId;
                    $log.debug("Passed Parameters UA:" + JSON.stringify(params))
                    //call item service to get list of itembank associated details 

                    userService.getuserAssociatedDetails(params).then(function (response) {

                        $log.debug(response);
                        vm.userAssociatedDetails = response.results.data;
                        vm.table1.totalRecords = response.results.total;
                        tableState1.pagination.numberOfPages = Math.ceil(response.results.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableState1;
                        $localStorage.userAssociateTableState = angular.copy(tableState1)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)

                    });

                } else if (response.status === 404) { //Error in case of data not found on server
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;

            });

            }
            else
            {
                  params.associated = 1;
                    params.associatedUserId = vm.id;
                    params.selectedButton = vm.preSelectRole;
                    params.UserId = $rootScope.userId;
                    $log.debug("Passed Parameters UA:" + JSON.stringify(params))
                    //call item service to get list of itembank associated details 

                    userService.getuserAssociatedDetails(params).then(function (response) {

                        $log.debug(response);
                        vm.userAssociatedDetails = response.results.data;
                        vm.table1.totalRecords = response.results.total;
                        tableState1.pagination.numberOfPages = Math.ceil(response.results.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableState1;
                        $localStorage.userAssociateTableState = angular.copy(tableState1)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)

                    });
            }
            

        }


        //Used for alerting on success/failure
        vm.alertConfig.timeOutAlert = function (cssClass, alertMsg, redirectState, isList) {
            $window.scroll(0, 0);
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (!isList) { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }
        }

        //save associate/dis associate changes
        //called on clicking submit of question association
        vm.associateUserData = function (onlyValidate) {
            $log.debug("%%%%%"+onlyValidate);
            var params = {};
            if (vm.preSelectRole == 1)
            {
                params.userBelongsTo = 1
            }
            else if (vm.preSelectRole == 2)
            {
                params.userBelongsTo = 2
            }
            if (vm.activeTabIndex == 0) {

                vm.isSubmitDisabled = 1;
                var alertMsg = 'ALERTS.DISASSOCIATE_SUCCESS';
                //validating id atleast one question bank is selected


                params.getAssociation = [];
                angular.forEach(vm.unCheckRecord, function (value, key) {
                    
                    if (value == true) //get all records to be removed
                        params.getAssociation.push(key);
                });
                var flag = params.getAssociation.length;
                params.getAssociation = params.getAssociation.join(',');

                params.userId = $rootScope.userId;
                params.associated = 0;



            } else if (vm.activeTabIndex == 1) {
                var alertMsg = 'ALERTS.ASSOCIATE_SUCCESS';
                vm.isRemoveDisabled = 1;



                params.getAssociation = [];
                
                angular.forEach(vm.checkRecord, function (value, key) {
                    if (value == true) //get all records to be added
                        params.getAssociation.push(key);
                });
                var flag = params.getAssociation.length;
                params.getAssociation = params.getAssociation.join(',');
                params.userId = $rootScope.userId;
                params.associated = 1;
                
                //validating id atleast one question bank is selected

            }
            
            //call api to save user to role/group association
            if (flag > 0) {
                vm.errorMsg = '';
                vm.associatedErrorMsg = '';
                if(onlyValidate != 1)
                {
                userService.associateRoleOrGroup(vm.id, params).then(function (response) {
                    if (response.status == 204) {
                        vm.alertConfig.timeOutAlert('wk-alert-success', alertMsg, '', true);

                    } else {
                        vm.alertConfig.timeOutAlert('wk-alert-success', alertMsg, '', true);
                    }
                    //refresh tab based on associate or nonassociate
                    if (vm.activeTabIndex == 0) {
                        vm.userAssociateTablePipe(vm.table.tableStateScopeCopy, true);
                        vm.unCheckRecord = []; //Need to clear checked marks in  associated tab records
                    }
                    if (vm.activeTabIndex == 1) {
                        //console.log(vm.tableStateScopeCopy1)
                        vm.activeTabIndex = 0;
                        vm.userAssociateTablePipe(vm.table.tableStateScopeCopy, true);
                        vm.checkRecord = []; //Need to clear checked marks in non associated tab records
                    }
                });
            }

            }
            else
            {
                if (vm.preSelectRole == 1)
                {
                    if (vm.activeTabIndex == 1)
                    {
                        vm.errorMsg = 'ERRORS.SELECT_USERROLEASSOC';
                    }
                    if (vm.activeTabIndex == 0)
                    {
                        vm.associatedErrorMsg = 'ERRORS.SELECT_USERROLEASSOC';
                    }
                }
                else
                {
                    if (vm.activeTabIndex == 1)
                    {
                        vm.errorMsg = 'ERRORS.SELECT_USERGROUPASSOC';
                    }
                    if (vm.activeTabIndex == 0)
                    {
                        vm.associatedErrorMsg = 'ERRORS.SELECT_USERGROUPASSOC';
                    }
                }
            
                return false;
            }
        }
        
    }])
})();

(function() {
    'use strict';

    var itemsApp = angular.module('app.items')
    itemsApp.directive('itemPreview', ['$rootScope', '$log', 'itemsService', function($rootScope, $log, itemsService) {
        var directive = {}; //directive object

        directive.restrict = 'E'; //restrict directive only for Element
        directive.require = [];
        directive.templateUrl = "app/modules/items/partials/items-preview.html"; //template url.
        directive.transclude = true;
        directive.bindToController = true;
        directive.scope = {
            itemId: "=",
            itemDetails: "="
        };
        directive.controllerAs = 'vm';
        directive.controller = function($scope, $element, $attrs) {
            var vm = this;
            vm.pageTitle = "PAGE_TITLE.ITEM_PREVIEW";

            if (angular.isUndefined(vm.itemId) || vm.itemId == "") //Check if itemId exist
                vm.pageError = true; //Show data not available error
            else {
                vm.showLoader = true;
                var getCorrectAnswer = function(answerDetails) {
                    //vm.itemDetails.correctAnswer = "";
                    //In case of multiple correct answers create a comma seperated answer string
                    angular.forEach(answerDetails, function(choice, key) {
                        if (choice.correct == true) {
                            if (angular.isDefined(vm.itemDetails.correctAnswer))
                                vm.itemDetails.correctAnswer = vm.itemDetails.correctAnswer + ',' + choice.label;
                            else if (vm.itemDetails.labelText == 'GRAPHIC_OPTION')
                                vm.itemDetails.correctAnswer = choice.value;
                            else
                                vm.itemDetails.correctAnswer = choice.label;
                        }
                    });
                }

                var assignOtherDetails = function() {
                    if (vm.itemDetails.labelText == 'GRAPHIC_OPTION')
                        vm.itemtypeTemplate = "graphic-option-preview"
                    else if (vm.itemDetails.labelText == 'CLINICAL_SYMPTOMS' || vm.itemDetails.labelText == 'MEDICAL_CASE') {
                        if (vm.itemDetails.labelText == 'MEDICAL_CASE')
                            vm.itemtypeTemplate = "medical-question-preview";
                        else
                            vm.itemtypeTemplate = "clinical-question-preview";

                        //Get list of child question details
                        var params = { userId: $rootScope.userId, parent: vm.itemId, sort: "+childOrder" };
                        itemsService.getItemsDetails(params).then(function(response) {
                            vm.itemChildList = response.results.data;
                            if (response.results.total > 0) {
                                vm.getChildDetail(0);
                            } else {
                                vm.showLoader = false;
                            }
                        });
                    } else
                        vm.itemtypeTemplate = "multiple-choice-preview"

                    if (vm.itemDetails.labelText != 'CLINICAL_SYMPTOMS' && vm.itemDetails.labelText != 'MEDICAL_CASE') {
                        getCorrectAnswer(vm.itemDetails.choiceInteraction.simpleChoices);
                    }
                    //Assign basic asset details which applicable for asset related wuestion types
                    if ((vm.itemDetails.labelText == 'IMAGE_INTEGRATION' || vm.itemDetails.labelText == 'VIDEO_QUESTIONS' || vm.itemDetails.labelText == 'CLINICAL_SYMPTOMS' || vm.itemDetails.labelText == 'MEDICAL_CASE') && angular.isDefined(vm.itemDetails.assets)) {
                        vm.assetTypeId = vm.itemDetails.assets["0"].assetTypeId;
                        vm.itemAssetPath = '/' + vm.itemDetails.assets["0"].assetPath + "/" + vm.itemDetails.assets["0"].assetName;
                    }

                }

                vm.getChildDetail = function(index) {
                    vm.showLoader = true;
                    vm.currentChild = index;
                    var childId = vm.itemChildList[index].id;
                    delete vm.itemDetails.correctAnswer;
                    itemsService.getItemById(childId).then(function(response) { //Get item detail from server based on item id
                        if (response.status === 200) {
                            vm.itemChildDetails = response.data;
                            $log.debug("Item details from server:", vm.itemDetails);
                            getCorrectAnswer(vm.itemChildDetails.choiceInteraction.simpleChoices);
                        } else if (response.status === 404) { //Error in case of data not found on server
                            if (response.data.code == "2007") {
                                //vm.pageError = true;
                            }
                        }
                        vm.showLoader = false;
                    });
                }

                if (angular.isUndefined(vm.itemDetails)) { //Check if item details already binded
                    itemsService.getItemById(vm.itemId).then(function(response) { //Get item detail from server based on item id
                        if (response.status === 200) {
                            vm.itemDetails = response.data;
                            assignOtherDetails();
                            $log.debug("Item details from server:", vm.itemDetails);
                        } else if (response.status === 404) { //Error in case of data not found on server
                            if (response.data.code == "2007") {
                                vm.pageError = true;
                                vm.showLoader = false;
                            }
                        }
                        if (vm.itemDetails.labelText != 'CLINICAL_SYMPTOMS' && vm.itemDetails.labelText != 'MEDICAL_CASE')
                            vm.showLoader = false;
                    });
                } else {
                    assignOtherDetails();
                    vm.showLoader = false;
                }
            }


        };
        return directive;
    }]);
})();

'use strict';

var metadataApp = angular.module('app.metadata')
metadataApp.directive('metadataFilter', ['$rootScope', '$log', '$filter', '$localStorage', '$uibModal', 'metadataService', 'config', function($rootScope, $log, $filter, $localStorage, $uibModal, metadataService, config) {
    //directive object
    var directive = {};
    //restrict directive only for Element
    directive.restrict = 'E';
    directive.require = ["^ngMessages", "^pascalprecht.translate", "ngModel"];

    //template url. 
    directive.templateUrl = "app/modules/metadata/partials/metadata-filter.html";
    directive.transclude = false;
    directive.bindToController = true;
    directive.scope = {
        metadataAssoc: "=",
        form: "=form",
        isFormSubmitted: "=isFormSubmitted",
        selectedMetaDetails: "=selectedMetaDetails",
        metadataPrev: "=metadataPrev",
        filterType: "@"
    };

    directive.controllerAs = 'vm';

    directive.controller = function($scope, $element, $attrs) {
        var vm = this;
        vm.getModuleAction = $rootScope.currentState.split('.')[1];
        vm.getModule = $rootScope.currentState.split('.')[0];
        if (vm.getModule == 'items' && vm.getModuleAction == 'create') {
            vm.allowMandatory = 1;
        } else {
            vm.allowMandatory = 0;
        }
        vm.showMetadataTag = true;
        vm.form.clearFilterSearch = function() {
            vm.metadataTablePipe(vm.table.tableStateScopeCopy, true, true);
        }

        $log.debug(vm.filterType);
        vm.searchFilter = vm.table = {};
        vm.table.dataPerPage = 5; //config.recordsPerPageDefault;; //Default data per page
        vm.table.dataPerPageOptions = [5, 10, 15] //config.recordsPerPage; //Default date per page options
        vm.searchFilter.metadataType = "All";
        vm.selectedMetaDetails = vm.selectedMetaDetails || [];
        $log.debug(vm.selectedMetaDetails);
        vm.metadataAssoc = vm.metadataAssoc || {};

        //Get Metadata types from server or from local storage
        if ($localStorage.metadataTypes) {
            vm.metadataTypes = $localStorage.metadataTypes;
            //vm.metadata.selectedOptionMetadata = vm.metadataTypes[0];
        } else {
            //call metadata service to get list of metadata types   
            metadataService.getMetadataTypesList().then(function(response) {
                $localStorage.metadataTypes = vm.metadataTypes = response.data;
                //vm.metadata.selectedOptionMetadata = vm.metadataTypes[0];
            });
        }

        //Get all the mandatory metadata details from server
        if (vm.filterType == "assoc" && $rootScope.currentState.split('.')[0] == "items") {
            metadataService.getMandatoryMetadata().then(function(response) {

                if (response.status === 200) {
                    console.log(vm.selectedMetaDetails);
                    console.log(response.data);
                    if (vm.selectedMetaDetails == '' || response.data == '') {
                        vm.selectedMetaDetails = vm.selectedMetaDetails.concat(response.data);
                    } else {
                        //vm.selectedMetaDetails = vm.selectedMetaDetails.concat(response.data);
                        console.log("valueSelected");
                        angular.forEach(response.data, function(valueSelected, keySelected) {
                            var checkId = valueSelected.id;
                            var isAlreadyAdded = $filter("filter")(vm.selectedMetaDetails, { id: checkId }, true).length;

                            if (isAlreadyAdded == 0) {
                                vm.selectedMetaDetails.push(valueSelected);
                            }
                        });
                    }
                    //vm.selectedMetaDetails = angular.merge([],vm.selectedMetaDetails, response.data);
                    console.log(vm.selectedMetaDetails);
                    if (vm.selectedMetaDetails.length === 0) {
                        vm.showMetadataTag = false;
                    } else {
                        vm.showMetadataTag = true;
                    }
                }

            });
        }

        vm.initialselectedValue = function(metaList) {
            metaList.selectedValue = metaList.selectedValue || [];
            if (vm.getModuleAction != 'create' && vm.getModule == 'items' && angular.isDefined(vm.metadataAssoc[metaList.id])) {
                angular.forEach(vm.metadataAssoc[metaList.id], function(valueMetadata, keyMetadata) {
                    metaList.selectedValue.push(valueMetadata.id);
                });
            }
        }


        //Used to add tag for filter
        vm.addMetadataFilter = function(metadata) {

            var isAlreadyAdded = $filter("filter")(vm.selectedMetaDetails, { id: metadata.id }, true).length
                //ng-class="{'custom-icon-disabled':vm.selectedMetaDetails | filter:{ id: metadata.id }:true).length > 1}"

            if (vm.allowMandatory == 1) {
                var getMandatoryValue = metadata.mandatory;
            } else if (vm.allowMandatory == 0) {
                var getMandatoryValue = metadata.isDisabled;
            }

            if (isAlreadyAdded == 0 && !getMandatoryValue) {

                if (vm.allowMandatory == 1) {
                    metadata.mandatory = true;
                } else if (vm.allowMandatory == 0) {
                    metadata.isDisabled = true;
                }

                if (metadata.tagTypeId != 1) {
                    var params = {};
                    params.metadataValueId = 0;
                    //Get metadata for the given id by calling metadata/{id} api
                    metadataService.getMetadataById(metadata.id, params).then(function(response) {
                        $log.debug(response);
                        if (response.status === 200) {
                            metadata.metadataValues = response.data.metadataValues;
                            //console.log(metadata.metadataValues)
                            vm.selectedMetaDetails.push(angular.copy(metadata));
                            vm.metadataAssoc[metadata.id] = metadata.metadataValues[0].id;
                        }
                    });

                } else
                    vm.selectedMetaDetails.push(angular.copy(metadata));

                vm.showMetadataTag = true;
            }
        }

        //Used to remove added tag from the filter list
        vm.removeMetadataFilter = function(mIndex, metadata) {
            var data = $filter("filter")(vm.metadataDetails, { id: metadata.id }, true)[0]
            vm.selectedMetaDetails.splice(mIndex, 1);

            delete vm.metadataAssoc[metadata.id];

            if (vm.allowMandatory == 1) {
                data.mandatory = false;
            } else if (vm.allowMandatory == 0) {
                data.isDisabled = false;
            }
            $log.debug("length->" + vm.selectedMetaDetails.length);
            if (vm.selectedMetaDetails.length == 0) {
                vm.showMetadataTag = false;
            }

        }

        vm.chooseLookupValue = function(index, valueId) {
            valueId = valueId.toString();
            var metadata = vm.selectedMetaDetails[index];
            vm.metadataAssoc[metadata.id] = vm.metadataAssoc[metadata.id] || []
            if (metadata.multiselect) {
                var valueIndex = vm.metadataAssoc[metadata.id].indexOf(valueId);
                if (valueIndex === -1)
                    vm.metadataAssoc[metadata.id].push(valueId);
                else
                    vm.metadataAssoc[metadata.id].splice(valueIndex, 1)
                if (metadata.metadataValues.length === vm.metadataAssoc[metadata.id].length)
                    metadata.selectedAll = true;
                else
                    metadata.selectedAll = false;
                //console.log(vm.metadataAssoc[metadata.id].length)
                if (vm.metadataAssoc[metadata.id].length === 0)
                    vm.form['metavalue' + index].$setValidity('minValue', false);
                else
                    vm.form['metavalue' + index].$setValidity('minValue', true);
            } else {
                vm.metadataAssoc[metadata.id] = [valueId];
                vm.form['metavalue' + index].$setValidity('minValue', true);
            }

        }

        vm.checkAllLookup = function(metaList, index) {
            if (metaList.selectedAll) {
                vm.metadataAssoc[metaList.id] = [];
                angular.forEach(metaList.metadataValues, function(value, key) {
                    var valueId = value.id.toString();
                    vm.metadataAssoc[metaList.id].push(valueId);
                });
                vm.form['metavalue' + index].$setValidity('minValue', true);
            } else {
                vm.metadataAssoc[metaList.id] = [];
                vm.form['metavalue' + index].$setValidity('minValue', false);
            }
        }

        var HierarichyModalCtrl = function($scope, $uibModalInstance, metadataValues, selectedNode, metadataId) {
            var $ctrl = this;
            $ctrl.metadataValues = metadataValues;
            $ctrl.selectedNode = selectedNode || metadataValues[0];
            $ctrl.metadataId = metadataId;
            $ctrl.metadataAssoc = vm.metadataAssoc[metadataId];
            if(vm.getModule == 'items' && (vm.getModuleAction == 'edit' ))
            {
            $ctrl.metadataPrev = vm.metadataPrev[metadataId];
            }
            console.log("when oprning model");
            console.log($ctrl.metadataPrev);
            $ctrl.ok = function() {
                console.log($ctrl.metadataPrev);
                $uibModalInstance.close($ctrl.selectedNode);
            };

            $ctrl.cancel = function() {
                $uibModalInstance.dismiss('cancel');
            };
        }
        HierarichyModalCtrl.$inject = ['$scope', '$uibModalInstance', 'metadataValues', 'selectedNode', 'metadataId'];

        vm.openHierarichyModal = function(metadata, mIndex) {


            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'hierarichyMetaModal',
                controller: HierarichyModalCtrl,
                controllerAs: '$ctrl',
                size: 'lg',
                resolve: {
                    metadataValues: function() {
                        return metadata.metadataValues;
                    },
                    selectedNode: function() {
                        return metadata.selectedValue;
                    },
                    metadataId: function() {
                        return metadata.id;
                    }
                }
            });

            modalInstance.result.then(function(selectedItem) {
                console.log("after close");
                
                vm.metadataAssoc = vm.metadataAssoc || {}

                if (vm.getModule == 'items' && (vm.getModuleAction == 'edit' || vm.getModuleAction == 'create')) {

                    if (angular.isUndefined(selectedItem.children)) {
                        vm.metadataAssoc[metadata.id] = selectedItem;
                        metadata.selectedValue = selectedItem;
                        metadata.selectedValue.value = selectedItem.length + " nodes selected";


                    } else {
                        var getSelectedItem = [];
                        angular.forEach(selectedItem.children, function(value, key) {
                            getSelectedItem.push(value.id);
                        });

                        vm.metadataAssoc[metadata.id] = getSelectedItem;
                        metadata.selectedValue = getSelectedItem;
                        metadata.selectedValue.value = getSelectedItem.length + " nodes selected";


                    }

                    if (metadata.selectedValue.length == 0) {
                        vm.form['metavalue' + mIndex].$setValidity('minValue', false);
                    } else {
                        vm.form['metavalue' + mIndex].$setValidity('minValue', true);
                    }
                } else {
                    vm.metadataAssoc[metadata.id] = selectedItem.id;
                    metadata.selectedValue = selectedItem;
                }
            }, function() {
                $log.info('modal dismissed at: ' + new Date());
            });
        }

        var SnomedModalCtrl = function($scope, $uibModalInstance, taxanomyIds) {
            var $ctrl = this;
            $ctrl.showModalLoader = true;
            metadataService.getSnomedDetails(taxanomyIds).then(function(response) {
                $ctrl.snomedDetails = response.data;
                $ctrl.showModalLoader = false;
            });
            $ctrl.cancel = function() {
                $uibModalInstance.dismiss('cancel');
            };
        }
        SnomedModalCtrl.$inject = ['$scope', '$uibModalInstance', 'taxanomyIds'];

        vm.openSnomedDetails = function(metadata) {
            if (angular.isDefined(vm.metadataAssoc[metadata.id]) && vm.metadataAssoc[metadata.id].length > 0) {
                var selectedValue = [];
                if (angular.isUndefined(metadata.selectedValue)) {
                    angular.forEach(vm.metadataAssoc[metadata.id], function(value, key) {
                        selectedValue.push(value.id)
                    });
                } else
                    selectedValue = metadata.selectedValue;

                var modalInstance = $uibModal.open({
                    animation: true,
                    templateUrl: '/app/modules/metadata/partials/snomed-details-modal.html',
                    controller: SnomedModalCtrl,
                    controllerAs: '$ctrl',
                    size: 'lg',
                    resolve: {
                        taxanomyIds: function() {
                            return selectedValue.join();
                        }
                    }
                });
            }
        }


        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.metadataTablePipe = function(tableState, isSearch, isClear) {
            var params = {};
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                vm.searchFilter.metadataName = "";
                vm.searchFilter.metadataDesc = "";
                vm.searchFilter.metadataType = "All";
            }
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                //Add entered metadata name in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataName) && vm.searchFilter.metadataName != "")
                    searchParams.tagName = vm.searchFilter.metadataName;

                //Add entered metadata description in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataDesc) && vm.searchFilter.metadataDesc != "")
                    searchParams.description = vm.searchFilter.metadataDesc;

                //Add chosen metadata type in the searchParams
                if (angular.isDefined(vm.searchFilter.metadataType) && vm.searchFilter.metadataType !== "All")
                    searchParams.tagTypeId = vm.searchFilter.metadataType;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.metadataName = tableState.search.tagName;
                vm.searchFilter.metadataDesc = tableState.search.description;
                vm.searchFilter.metadataType = tableState.search.tagTypeId || "All";
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableState.sort.predicate))
                params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
            else { //Default Sorting by metadata tag name
                params.sort = "+tagName";
                tableState.sort.predicate = "tagName";
            }
            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
            $log.debug("Passed Parameters:" + JSON.stringify(params))

            //call metadata service to get list of metadata details 
            metadataService.getMetadataDetails(params, tableState).then(function(response) {
                vm.metadataDetails = response.results.data;
                vm.table.totalRecords = response.results.total;
                tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                vm.showLoader = false; //Hide loader
                vm.table.tableStateScopeCopy = tableState;
                $log.debug(response.results)
                $log.debug("Total Result:" + response.results.total)
                $log.debug(vm.selectedMetaDetails);
                $log.debug("Total Resultfdfsdf:" + response.results.total)

                //enter inside this only if it is other than items create page
                if (angular.isDefined(vm.selectedMetaDetails) && (vm.getModule != 'items' || (vm.getModule == 'items' && vm.getModuleAction == 'edit'))) {
                    angular.forEach(vm.selectedMetaDetails, function(valueSelected, keySelected) {

                        angular.forEach(vm.metadataDetails, function(valueMetadata, keyMetadata) {

                            if (valueMetadata.id == valueSelected.id) {
                                valueMetadata.isDisabled = true;

                                //change the mandatory status to false if module is not item.because mandatory is considered only for item module and not itembank and quiz
                                if (vm.getModule != 'items')
                                    valueSelected.mandatory = false;

                            }
                        });

                    });

                }

                //while clicking on pagination other pages already selected tag should be having add button disabled
                if (angular.isDefined(vm.selectedMetaDetails) && vm.getModule == 'items' && vm.getModuleAction == 'create') {
                    angular.forEach(vm.selectedMetaDetails, function(valueSelected, keySelected) {

                        angular.forEach(vm.metadataDetails, function(valueMetadata, keyMetadata) {

                            if (valueMetadata.id == valueSelected.id) {
                                valueMetadata.mandatory = true;
                            }
                        });

                    });
                }

                //to display "no records" in metadata values section
                if (vm.filterType == "assoc" && angular.isDefined(vm.selectedMetaDetails)) {
                    if (vm.selectedMetaDetails.length === 0) {
                        vm.showMetadataTag = false;
                    } else {
                        vm.showMetadataTag = true;
                    }
                }

                $log.debug(vm.showMetadataTag);
            });
        }
    };
    return directive;
}])

'use strict';

var metadataApp = angular.module('app.metadata')
metadataApp.directive('metadataTree', function () {
    //directive object
    var directive = {};
    console.log("Inside Dir");
    //restrict directive only for Element
    directive.restrict = 'E';
    directive.require = ["^ngMessages", "^pascalprecht.translate"];

    //template url. 
    directive.templateUrl = "app/modules/metadata/partials/metatree-node.html";
    directive.transclude = false;
    directive.bindToController = true;
    directive.scope = {
        mode: "@mode",
        metadataValues: "=",
        metadataId: "=",
        isResourceAssociated: "=",
        metadataForm: "=form",
        currentNode: "=selectedNode",
        metadataAssoc: "=metadataAssoc",
        metadataPrev: "=metadataPrev"
    };

    directive.controllerAs = 'vm';
    directive.controller = function ($scope, $element, $attrs, $window, $filter, $rootScope, metadataService) {
        var vm = this;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.getModule = $rootScope.currentState.split('.')[0];
        // console.log(vm.metadataValues)
        vm.treeMode = $attrs.mode;
        vm.showContextMenu = false;
        vm.allReadySavedNodes = vm.metadataPrev;
        if (vm.getModule == 'items' && (vm.actionType == 'edit' || vm.actionType == 'create'))
        {
            vm.collectNodes = [] || vm.metadataAssoc;
            vm.currentNode = [];
            angular.forEach(vm.metadataAssoc, function (value, key) {

                if (angular.isUndefined(value.id))
                {
                    vm.collectNodes[value] = true;
                }
                else
                {
                    vm.collectNodes[value.id] = true;
                }


            });
            angular.forEach(vm.collectNodes, function (value, key) {
                if (value == true) //get all records to be added
                    vm.currentNode.push(key);
            });

        }
        else
        {
            vm.currentNode = vm.currentNode || vm.metadataValues[0] || {}

        }
        //console.log(!$filter('isEmptyObject')(vm.currentNode))

        vm.toggleVisibility = function (node, checkMultiValues) {
            //event.preventDefault();

            if ((vm.treeMode == 'view' || (vm.metadataForm.treetagName.$valid && vm.metadataForm.treetagDesc.$valid)) || (vm.getModule == 'metadata' && vm.actionType != 'create')) {

                var params = {};
                params.metadataValueId = node.id;
                if (checkMultiValues != 1)
                {
                    //Fetch metadata for the given id by calling metadata/{id} api
                    metadataService.getMetadataById(vm.metadataId, params).then(function (response) {

                        if (response.status === 200) {

                            if (!angular.isUndefined(response.data.metadataValues))
                            {
                                node.children = response.data.metadataValues;

                                vm.currentNode = node


                                var totalAliveChildren = $filter('filter')(node.children, {nodeStatus: "!deleted"}, true).length;
                                if (totalAliveChildren) {
                                    node.childrenVisibility = !node.childrenVisibility;
                                }
                            }
                            else
                            {

                                vm.currentNode = node


                                var totalAliveChildren = $filter('filter')(node.children, {nodeStatus: "!deleted"}, true).length;
                                if (totalAliveChildren) {
                                    node.childrenVisibility = !node.childrenVisibility;
                                }
                            }
                        }
                        if (vm.getModule == 'items' && (vm.actionType == 'edit' || vm.actionType == 'create'))
                        {
                            vm.currentNode = [];

                            angular.forEach(vm.collectNodes, function (value, key) {
                                if (value == true) //get all records to be added
                                    vm.currentNode.push(key);
                            });
                            console.log("FSDFSDF");
                            console.log(vm.currentNode);

                        }
                    });


                }
                else
                {
                    if (vm.getModule == 'items' && (vm.actionType == 'edit' || vm.actionType == 'create'))
                    {
                        vm.currentNode = [];
                        angular.forEach(vm.collectNodes, function (value, key) {
                            if (value == true) //get all records to be added
                                vm.currentNode.push(key);
                        });

                    }
                }

            }
            else

            {
                vm.currentNode = node
                //console.log(vm.currentNode)
                var totalAliveChildren = $filter('filter')(node.children, {nodeStatus: "!deleted"}, true).length;
                if (totalAliveChildren) {
                    node.childrenVisibility = !node.childrenVisibility;
                }
            }

        };



        // vm.isEmptyObject = function(){

        // }
        vm.contextMenu = function (data, $event) {
            //if (vm.metadataForm.treetagName.$valid && vm.metadataForm.treetagDesc.$valid) {
            vm.showContextMenu = true;
            vm.contextMenuData = data;
            //$('#contextmenu-node').css({'border':'1px solid green','top':($event.pageY - 5)+"px",'left':($event.pageX - 5)+"px"});
            vm.cotextMenuStyle = {'top': $event.pageY - 5 + "px", 'left': $event.pageX - 5 + "px"};
            console.log(vm.contextMenuData)
            console.log(vm.cotextMenuStyle)
            console.log('after digest')
            angular.element($window).triggerHandler('resize');

            try {
                $scope.$digest()
            } catch (e) {

            }
        };
        vm.closeContextMenu = function () {
            vm.showContextMenu = false;
            console.log("close contexy menu")

        }
        vm.editTreeNode = function (data) {
            vm.currentNode = data
            vm.showContextMenu = false;
        }
        vm.addTreeNode = function (data) {
            if (angular.isUndefined(vm.metadataForm.treetagName) || (vm.metadataForm.treetagName.$valid && vm.metadataForm.treetagDesc.$valid)) {
                var newNodeIndex = 1;
                if (angular.isUndefined(data)) {
                    // if (!angular.isUndefined(vm.metadataValues) && vm.metadataValues.length > 0)
                    //Adding new node in the root level
                    var nodeCount = $filter('filter')(vm.metadataValues, {nodeStatus: "!deleted"}, true).length;
                    newNodeIndex = vm.metadataValues.push({
                        value: "New Node " + (nodeCount + 1),
                        description: "New Node " + (nodeCount + 1),
                        children: [],
                        childrenVisibility: 1,
                        id: vm.metadataValues.length + 1,
                        nodeStatus: "created"
                    });
                    // else
                    //     vm.metadataValues = [{
                    //         value: "New Node 1",
                    //         description: "New Node 1",
                    //         children: [],
                    //         childrenVisibility: 1,
                    //         id: 1
                    //     }];
                    vm.currentNode = vm.metadataValues[newNodeIndex - 1]
                } else {
                    //Adding new node under some node
                    var nodeCount = $filter('filter')(data.children, {nodeStatus: "!deleted"}, true).length;
                    var newName = data.value + '-' + (nodeCount + 1);

                    newNodeIndex = data.children.push({
                        value: newName,
                        description: newName,
                        children: [],
                        childrenVisibility: 1,
                        id: data.id + '-' + (nodeCount + 1),
                        nodeStatus: "created"
                    });
                    data.childrenVisibility = 1;
                    vm.currentNode = data.children[newNodeIndex - 1] //Making newly created node as current node

                }
                vm.showContextMenu = false;
                vm.firstClick = 1;
                console.log("^^^^" + vm.currentNode.id)
            } else {
                //console.log(vm.metadataForm.treetagName)
            }

        };
        vm.deleteTreeNode = function (data) {
            console.log(data.parent)
            // if (data.nodeStatus == 'created') { 
            var prevNode = deleteNode(vm.metadataValues, data['id']);
            if (prevNode === true) { //For deleting records in root level
                var remainingChild = $filter('filter')(vm.metadataValues, {nodeStatus: "!deleted"}, true);
                if (remainingChild.length > 0) {
                    vm.currentNode = remainingChild[remainingChild.length - 1]; //Return parent node obj for changing current node
                } else {
                    vm.currentNode = {}; //empty current node when all nodes are deleted
                }
            } else
                vm.currentNode = prevNode;
            vm.showContextMenu = false;

        };

        //Recursive function to find and delete the node based on id in the entire tree structure. 
        //The reason of traversing tree is to find next sibling/parent.
        //Make sure you understand the logic by reading comments before modifying this unless do not modify.
        var deleteNode = function (array, id) {
            //Checking for the node by checkig its id inside a loop from tree head
            for (var i = 0; i < array.length; ++i) {
                var obj = array[i];
                if (obj.id === id && obj.nodeStatus == "created") { //Deleting newly created node(Not yet saved in server). Delete that node and its children
                    // splice out 1 element starting at position i
                    array.splice(i, 1);
                    return true;
                } else if (obj.id === id && (obj.nodeStatus == "selected" || obj.nodeStatus == "updated")) { //When deleting existing node
                    obj.nodeStatus = "deleted"; //Just change status. This helps to delete the node data in db.
                    return true;
                }

                var remainingChild = $filter('filter')(obj.children, {nodeStatus: "!deleted"}, true);
                //Checking for children and call the method recursively
                if (remainingChild.length > 0 && deleteNode(obj.children, id)) {
                    obj.childrenVisibility = true;
                    var remainingChild = $filter('filter')(obj.children, {nodeStatus: "!deleted"}, true); //obj.children.length;
                    if (remainingChild.length > 0) { //check for alive child in current level
                        return remainingChild[remainingChild.length - 1]; //Return last alive child of current level
                    } else {
                        return obj; //Return parent node obj for changing current node
                    }
                }
            }
        };

        //Changes node status when we modify node name/description of existing nodes 
        vm.updateStatus = function () {
            if (vm.currentNode.nodeStatus == "selected")
                vm.currentNode.nodeStatus = "updated";
        }
    }

    return directive;
})
        .directive('ngRightClick', ['$parse', function ($parse) {
            return {
                require: "^metadataTree",
                scope: false,
                link: function (scope, element, attrs, ctrl) {
                    if (ctrl.mode == "edit") {
                        var fn = $parse(attrs.ngRightClick);
                        element.bind('contextmenu', function (event) {
                            console.log(angular.toJson(event, true))
                            scope.$apply(function () {
                                console.log("event apply")
                                event.preventDefault();
                                event.stopPropagation();
                                fn(scope, {
                                    $event: event
                                });
                            });
                        });
                    }
                }
            }
        }]);
