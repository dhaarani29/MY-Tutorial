(function() {
    'use strict';
    //Metadata Module Creation with its dependencies
    angular.module('app.metadata', [])
        .config(function($stateProvider) {

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
        })
})();
