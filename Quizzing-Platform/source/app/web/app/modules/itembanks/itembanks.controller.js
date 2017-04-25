(function () {
    'use strict';


    angular.module('app.itembanks').controller('ItemcollectionsController', function ($rootScope, $scope, $window, $log, $uibModal, $localStorage, $filter, $timeout, config, itembanksService) {

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

    })
})();
