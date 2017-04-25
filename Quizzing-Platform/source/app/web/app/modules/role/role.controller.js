(function () {
    'use strict';

    angular.module('app.role').controller('RoleController', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, roleService) {
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


    })
})();