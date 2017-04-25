(function () {
    'use strict';

    angular.module('app.metadata').controller('MetadataController', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, metadataService) {
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

    });

})();
