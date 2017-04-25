(function() {
    'use strict';

    angular.module('app.items').controller('ItemTypesController', function($rootScope, itemsService) {
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
    });

})();
