(function() {
    'use strict';

    angular.module('app.items').controller('ItemsController', function($rootScope, $scope, $localStorage, $filter, $window, $timeout, $log, $uibModal, itemsService, metadataService, Upload, config) {
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
    });
})();
