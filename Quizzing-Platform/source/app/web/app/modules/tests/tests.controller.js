(function() {
    'use strict';

    angular.module('app.tests').controller('TestsController', function($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, testsService, itembanksService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;
        $scope.forms = {};
        vm.actionType = $rootScope.$state.current.name.split(".")[1]
        vm.showLoader = true, vm.pageError = false, vm.closeOtherAccordions = false, vm.isSubmitDisabled = false;
        vm.otherInfo = false;
        vm.alertConfig = { 'show': false }
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
            testsService.getQuizById(vm.id).then(function(response) {
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
                        angular.forEach(vm.quiz.testItemBanks, function(value, key) {

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

        vm.itemCollectionTablePipe = function(tableStateBank, isSearch, isClear) {
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
            testsService.getItemBankAssociation(params, tableStateBank).then(function(response) {
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

        vm.itemTablePipe = function(tableState, isSearch, isClear) {
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
            itembanksService.getItemAssociation(params, tableState).then(function(response) {
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
        vm.quizTablePipe = function(tableStateQuiz, isSearch, isClear) {

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

                testsService.getAllTests(params, tableStateQuiz).then(function(response) {
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
        vm.createQuiz = function() {

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
                    testsService.insertQuiz(quizData).then(function(response) {
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
                    testsService.updateQuiz(quizData, vm.id).then(function(response) {
                        if (response.status === 204) { //On successfull update
                            vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.EDIT_SUCCESS', '', false);
                            vm.activeTabIndex = 0;
                            vm.quiz.questionTime = '';
                        } else if (response.status === 409) {
                            var displayMsg = "ERRORS.DUPLICATE_TEST_NAME";
                            vm.alertConfig.timeOutAlert('wk-alert-danger', displayMsg, '', false);
                            vm.isSubmitDisabled = false;
                        } else { //On update failure
                            vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.EDIT_FAILED', '', false);
                        }

                        testsService.getQuizById(vm.id).then(function(response) {
                            $log.debug(response.data);

                            if (response.status === 200) {
                                vm.quiz = response.data;
                                vm.itemTablePipe(vm.tableItem.itemTableStateScopeCopy, true);

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
                                    angular.forEach(vm.quiz.testItemBanks, function(value, key) {

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

        var validateFormData = function() {
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
                angular.forEach(vm.quiz.questionBankCheck, function(value, key) {

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
        vm.alertConfig.timeOutAlert = function(cssClass, alertMsg, redirectState, isList) {
            $window.scroll(0, 0);
            vm.alertConfig.class = cssClass;
            vm.alertConfig.details = alertMsg;
            vm.alertConfig.isList = isList;
            vm.alertConfig.show = true;
            if (redirectState != '') { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function() {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }

        }
        vm.deleteQuiz = function(isDeleteAll) {
            vm.isSubmitDisabled = true;
            var params = {};
            params.isDeleteAll = isDeleteAll;
            if (!isDeleteAll)
                params.version = (!isDeleteAll) ? vm.quiz.version : '';

            testsService.deleteQuiz(vm.id, params).then(function(response) {
                if (response.status == 200) {

                    //vm.item.deletedInfo = response.data;
                } else if (response.status == 204) {
                    vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.DELETE_SUCCESS', 'tests.list', false);
                } else {
                    vm.alertConfig.timeOutAlert('wk-alert-danger', 'ALERTS.DELETE_FAILED', 'tests.list', false);
                }
            });
        }

        vm.selectItem = function(itemDetails) {
            var itemIndex = vm.isItemExist(itemDetails.id)
            if (itemIndex == -1) {
                var itemData = { itemId: parseInt(itemDetails.id), label: itemDetails.label, version: itemDetails.version }
                vm.quiz.testItems.push(itemData);
            } else {
                vm.quiz.testItems.splice(itemIndex, 1);
            }
        }

        vm.isItemExist = function(itemId) {
            vm.quiz.testItems = vm.quiz.testItems || [];
            itemId = parseInt(itemId)
            return vm.quiz.testItems.map(function(item) {
                return item.itemId;
            }).indexOf(itemId);
        }

        vm.inspectItem = function() {
            $log.debug("$#%#$#$%");
            //vm.quiz.testItems = [];
            vm.quiz.testItemBanks = [];
            // angular.forEach(vm.quiz.questionCheck, function(value, key) {

            //     if (value == true) //get all records to be added
            //         vm.quiz.testItems.push(key);
            // });
            angular.forEach(vm.quiz.questionBankCheck, function(value, key) {

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

        vm.changeVersion = function() {
            var params = {};
            vm.showLoader = true;
            vm.isSubmitDisabled = false;
            params.version = vm.quiz.version;
            //Get test detail from server
            testsService.getQuizById(vm.id, params).then(function(response) {

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

    });



})();
