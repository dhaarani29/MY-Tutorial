'use strict';

var metadataApp = angular.module('app.metadata')
metadataApp.directive('metadataFilter', function($rootScope, $log, $filter, $localStorage, $uibModal, metadataService, config) {
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
})
