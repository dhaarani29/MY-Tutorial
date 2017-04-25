(function () {
    'use strict';

    angular.module('app.group').controller('GroupController', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, groupService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;

        vm.table = vm.table1 = vm.searchFilter = vm.user = vm.group = {};
        vm.table.totalRecords = 0;
        vm.table1.totalRecords = 0;
        vm.table.dataPerPage = config.recordsPerPageDefault;
        vm.table.dataPerPageOptions = config.recordsPerPage;
        vm.table1.dataPerPage = config.recordsPerPageDefault;
        vm.table1.dataPerPageOptions = config.recordsPerPage;
        vm.showdropdown = 0;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        vm.alertConfig = {'show': false};
        vm.selectedOptionUsers = {};
        vm.selectedOptionRole = {};
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

                if (response.status === 200) {
                    vm.group = response.data;
                    vm.group.groupName = vm.group.groupName;//Group Name
                    vm.group.description = vm.group.description;//Group Description

                    //Get Roles details which is associated to group
                    if (vm.group.userDetails.total == 0 && vm.group.roleDetails.total != 0) {
                        vm.roleDetails = vm.group.roleDetails;//Role name and description
                        vm.groupAssociationtype = 1;// 1- Role
                        vm.table.totalRecords = vm.group.roleDetails.total;

                        //Mapping to checkbox model
                        angular.forEach(vm.roleDetails.data, function (value, key) {
                            vm.selectedOptionRole[value.roleId] = true;
                        });
                    }
                    //Get user Details which is associated to role
                    else if (vm.group.roleDetails.total == 0 && vm.group.userDetails.total != 0) {
                        vm.userDetails = vm.group.userDetails;//User details
                        vm.groupAssociationtype = 2; // 2-User
                        vm.table1.totalRecords = vm.group.userDetails.total;

                        //Mapping to checkbox model
                        angular.forEach(vm.userDetails.data, function (value, key) {
                            vm.selectedOptionUsers[value.userId] = true;
                        });
                    } else {
                        vm.pageError = true;
                    }
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

            vm.isFormSubmitted = true;
            vm.group.userId = $rootScope.userId;

            //if the Form is valid
            if ($scope.groupForm.$valid) {

                if (vm.group.association != '') {

                    //Create Group and associate roles and users
                    if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                        groupService.insertGroup(vm.group).then(function (response) {
                            //Sucess response
                            if (response.status === 201) {
                                vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.CREATE_SUCCESS', 'group.list', false);
                            }
                            //Duplication
                            else if (response.status === 409) {
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
                    //Update group and associate / dissociate to group
                    else
                    {
                        groupService.updateGroup(vm.group, vm.id).then(function (response) {
                            //Success
                            if (response.status === 201) {
                                vm.alertConfig.timeOutAlert('wk-alert-success', 'ALERTS.EDIT_SUCCESS', 'group.list', false);
                            }
                            //Duplicate
                            else if (response.status === 409) {
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
            } else {
                vm.errorMsg = 'ERRORS.SELECT_USERROLE_TO_GROUP_ASSOC';
                return false;
            }
        }

        //Associate user and roles to group
        vm.userRoleAssociation = function (association) {

            var userParam = {};

            userParam.selectedRoleUsers = association;

            //Get User list
            if (userParam.selectedRoleUsers == 1)
            {
                //get all the Users selected
                userParam.getUsers = [];
                angular.forEach(vm.selectedOptionUsers, function (value, key) {
                    if (value == true)
                    {
                        userParam.getUsers.push(key);
                    }
                });

                userParam.association = userParam.getUsers.join(',');
                userParam.associationType = 0;//for users
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
                userParam.association = userParam.getRoles.join(',');
                userParam.associationType = 1;//for roles
            }
            vm.group.getAssociation = userParam.association;
            vm.group.associationType = userParam.associationType;
        }


        //This will be called for displaying Users records with pagination , sort etc..
        vm.userListPipe = function (tableStateUser, isSearch, isClear) {

            var params = {};

            vm.showLoader = false; //Shows Loader

            params.userId = $rootScope.userId; //Add userId in request param

            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userListTableState = {};
                vm.searchFilter.firstName = "";
                vm.searchFilter.lastName = "";
                vm.userUnCheck = [];
            }


            //Check if any local tables exist
            //And check if vm.table1.tableStateUserScopeCopy1 is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userListTableState && angular.isUndefined(vm.table1.tableStateScopeCopy1))
                angular.extend(tableStateUser, $localStorage.userListTableState);
            //Extend the stored table state with the current one. 


            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableStateUser.pagination.start = 0;


                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.firstName) && vm.searchFilter.firstName != "")
                    searchParams.firstName = vm.searchFilter.firstName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.lastName) && vm.searchFilter.lastName != "")
                    searchParams.lastName = vm.searchFilter.lastName;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableStateUser.search = angular.copy(searchParams);

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            }

            //Check if existing search filter values exist
            else if (!angular.equals({}, tableStateUser.search)) {
                //Assign the previous search filter values to model
                vm.searchFilter.firstName = tableStateUser.search.firstName;
                vm.searchFilter.lastName = tableStateUser.search.lastName;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableStateUser.search);

            }

            //Finding and assigning current page number
            if (tableStateUser.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableStateUser.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableStateUser.sort.predicate))
                params.sort = (tableStateUser.sort.reverse ? '-' : '+') + tableStateUser.sort.predicate;
            else { //Default Sorting by emailAddress
                params.sort = "+emailAddress";
                tableStateUser.sort.predicate = "emailAddress";
            }

            //Clear all the table state details
            if (isClear === true) {
                params.sort = "+emailAddress";
                tableStateUser.sort.predicate = "emailAddress";
                tableStateUser.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table1.dataPerPage;

            $log.debug("Passed Parameters:" + JSON.stringify(params))

            params.userId = $rootScope.userId;

            //call group service to get userlist details
            if (vm.actionType == 'create' || vm.actionType == 'edit') {
                params.onlyGroup = true;
                groupService.getUsersList(params).then(function (response) {
                    //Success
                    if (response.status == 200) {
                        vm.userDetails = response.data.data;
                        vm.table1.dataPerPageOptions = config.recordsPerPage; //Default date per page options
                        vm.table1.totalRecords = response.data.total;
                        tableStateUser.pagination.numberOfPages = Math.ceil(response.data.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader

                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableStateUser;
                        $localStorage.userDetails = angular.copy(tableStateUser)

                    }
                });
            }
            //Call group service to get group association details
            else {
                params.userSort = true;
                groupService.searchGroups(vm.id, params).then(function (response) {
                    if (response.status === 200) {
                        vm.group = response.data;
                        vm.group.groupName = vm.group.groupName;
                        vm.group.description = vm.group.description;
                        vm.userDetails = vm.group.userDetails;
                        vm.table1.totalRecords = vm.group.userDetails.total;

                        tableStateUser.pagination.numberOfPages = Math.ceil(vm.group.userDetails.total / vm.table.dataPerPage);
                        vm.table1.tableStateScopeCopy1 = tableStateUser;
                        $localStorage.userDetails = angular.copy(tableStateUser)
                        angular.forEach(vm.userDetails.data, function (value, key) {
                            vm.selectedOptionUsers[value.userId] = true;
                        });

                    } else {
                        vm.pageError = true;
                    }
                });
            }
        }


        //This will be called for displaying roles records.
        vm.roleListPipe = function (tableStateRole, isSearch, isClear) {

            var params = {};

            vm.showLoader = false; //Shows Loader

            params.userId = $rootScope.userId; //Add userId in request param


            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.roleListState = {};
                vm.searchFilter.roleName = "";
                vm.roleUnCheck = [];
            }


            //Check if any local tables exist
            //And check if vm.table1.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.roleListState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableStateRole, $localStorage.roleListState);
            //Extend the stored table state with the current one. 


            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableStateRole.pagination.start = 0;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.roleName) && vm.searchFilter.roleName != "")
                    searchParams.roleName = vm.searchFilter.roleName;

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableStateRole.search = angular.copy(searchParams);

                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            }

            //Check if existing search filter values exist
            else if (!angular.equals({}, tableStateRole.search)) {
                //Assign the previous search filter values to model

                vm.searchFilter.roleName = tableStateRole.search.roleName;

                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableStateRole.search);

            }

            //Finding and assigning current page number
            if (tableStateRole.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableStateRole.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            if (angular.isDefined(tableStateRole.sort.predicate))
                params.sort = (tableStateRole.sort.reverse ? '-' : '+') + tableStateRole.sort.predicate;
            else { //Default Sorting by item tag name
                params.sort = "+roleName";
                tableStateRole.sort.predicate = "roleName";
            }
            if (isClear === true) {
                params.sort = "+roleName";
                tableStateRole.sort.predicate = "roleName";
                tableStateRole.sort.reverse = false;
                vm.table.dataPerPage = config.recordsPerPageDefault;
            }
            params.roleSort = true;
            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
            params.userId = $rootScope.userId;

            //call the group service to get role lists
            if (vm.actionType == 'create' || vm.actionType == 'edit') {
                groupService.getRolesList(params).then(function (response) {

                    if (response.status == 200) {

                        vm.roleDetails = response.data.data;
                        vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
                        vm.table.totalRecords = response.data.total;
                        tableStateRole.pagination.numberOfPages = Math.ceil(response.data.total / vm.table.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table.tableStateScopeCopy = tableStateRole;
                        $localStorage.roleDetails = angular.copy(tableStateRole)
                        $log.debug(response.data)
                        $log.debug("Total Result:" + response.data.total)
                    }
                });
            }
            //Call the group service to get details of the particular group
            else {
                //Get group for the given id by calling user/{id} api
                groupService.searchGroups(vm.id, params).then(function (response) {

                    if (response.status === 200) {
                        vm.group = response.data;
                        vm.group.groupName = vm.group.groupName;
                        vm.group.description = vm.group.description;
                        $log.debug(vm.roleDetails);
                        vm.roleDetails = vm.group.roleDetails;
                        vm.table.totalRecords = vm.group.roleDetails.total;
                        tableStateRole.pagination.numberOfPages = Math.ceil(vm.group.roleDetails.total / vm.table.dataPerPage);
                        vm.table.tableStateScopeCopy = tableStateRole;
                        $localStorage.roleDetails = angular.copy(tableStateRole)
                        $log.debug(response.data)
                        $log.debug("Total Result:" + vm.group.roleDetails.total)
                        angular.forEach(vm.roleDetails.data, function (value, key) {
                            vm.selectedOptionRole[value.roleId] = true;
                        });

                    } else {
                        vm.pageError = true;
                    }
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
            if (redirectState != '') { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }

        }

    })
})();