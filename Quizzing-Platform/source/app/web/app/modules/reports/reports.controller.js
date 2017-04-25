(function () {
    'use strict';

    angular.module('app.reports').controller('ReportsController', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, reportsService) {
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

    })
})();
