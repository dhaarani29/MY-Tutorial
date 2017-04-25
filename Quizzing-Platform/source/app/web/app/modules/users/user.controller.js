(function () {
    'use strict';

    angular.module('app.user').controller('UserController', function ($rootScope, $scope, $window, $log, $localStorage, $filter, $timeout, config, userService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;
        vm.checkRecord = [];
        vm.unCheckRecord = [];
        vm.table = vm.searchFilter = vm.user = {};
        vm.table1 = {}, vm.table1.totalRecords = 0;
        vm.showdropdown = 0;
        vm.selectedOptionRole = {};
        vm.selectedOptionGroup = {};
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.passwordregex = '^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$';
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        //vm.emailRegex = '^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$';
        vm.emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        vm.phoneregex = /^(?=.*[0-9])[- +()0-9]+$/;
        var selectedGroup = [];
        var selectedRole = [];
        vm.alertConfig = {'show': false};
        vm.showdropdown = function () {

            vm.showdropdown = 1;

        };
        vm.pageError = false;
        vm.showLoader = true;

        var userParam = {};
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
            userService.getuserTypeList().then(function (response) {
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
                vm.user.status = vm.activeValue;
            });
            vm.user.status = vm.activeValue;

        } else {
            //call user service to get list of states    
            userService.getStatus().then(function (response) {
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
                    vm.user.status = vm.activeValue;
                });

            });
        }
        if (vm.actionType == "create" || vm.actionType == 'edit') {
            //Get Country list from server or from local storage
            if ($localStorage.countryList) {
                vm.countryList = $localStorage.countryList;
                //select default country as 
                vm.user.selectedOptionCountry = vm.countryList[0];
                var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                userService.getstateList(selectedCountryId).then(function (response) {
                    vm.stateList = response.data;
                    vm.user.selectedOptionState = '';
                });

            } else {
                //call user service to get list of country    
                userService.getcountryList().then(function (response) {
                    $localStorage.countryList = vm.countryList = response.data;
                    //select default country as 
                    vm.user.selectedOptionCountry = vm.countryList[0];
                    var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                    //call user service to get list of states    
                    userService.getstateList(selectedCountryId).then(function (response) {
                        vm.stateList = response.data;
                        vm.user.selectedOptionState = '';
                    });
                });

            }
            //Get State list from server or from local storage

            //call user service to get list of states    
            userService.getstateList(selectedCountryId).then(function (response) {
                vm.stateList = response.data;
                vm.user.selectedOptionState = '';
            });





            //Get State list from server or from local storage

            //call user service to get list of states    
            userService.getRolesList().then(function (response) {
                vm.rolesList = response.results.data;
                $log.debug(vm.rolesList);
                vm.user.selectedOptionRole = vm.rolesList[0];
            });


            //multiselect dropdown logic.
            vm.checkAll = function () {

                if (vm.selectedAll) {
                    vm.selectedAll = true;
                } else {
                    vm.selectedAll = false;
                }

                angular.forEach(vm.rolesList, function (value, key) {
                    vm.selectedOptionRole[value.roleId] = vm.selectedAll;
                });

            };

            vm.inspectcheckAll = function () {
//                vm.count = 0;
//                angular.forEach(vm.selectedOptionRole, function (value, key) {
//                    if (value == true) {
//                        vm.count = vm.count + 1;
//                    }
//                });
//                if (vm.count == vm.rolesList.length) {
//                    vm.selectedAll = true;
//                } else {
//                    vm.selectedAll = false;
//                }
            var userParam = {};
            if (angular.isUndefined(vm.selectRole))
            {
                vm.errorMsg = 'ERRORS.SELECT_ROLE_OR_GROUP';
                return false;
            } else {
                vm.errorMsg = "";

            }
            userParam.selectedRoleGroup = vm.selectRole;
            $log.debug(userParam.selectedRoleGroup);
            $log.debug(vm.selectedOptionGroup);
            if (userParam.selectedRoleGroup == 1)
            {
                //get all the roles selected
                userParam.getRoles = [];
                angular.forEach(vm.selectedOptionRole, function (value, key) {
                    if (value == true)
                    {
                        userParam.getRoles.push(key);
                    }
                });
                if (userParam.getRoles.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_ROLE';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getRoles = userParam.getRoles.join(',');
            }
            else if (userParam.selectedRoleGroup == 2)
            {
                //get all the roles selected
                userParam.getGroups = [];

                angular.forEach(vm.selectedOptionGroup, function (value, key) {
                    if (value == true)
                    {
                        userParam.getGroups.push(key);
                    }
                });
                $log.debug("$$$$$$$$$$"+userParam.getGroups.length);
                if (userParam.getGroups.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_GROUP';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getGroups = userParam.getGroups.join(',');
            }
            }

            //Get State list from server or from local storage

            //call user service to get list of states    
            userService.getGroupsList().then(function (response) {
                vm.groupsList = response.results.data;
                vm.user.selectedOptionGroup = vm.groupsList[0];
            });



            //multiselect dropdown logic.
            vm.checkAllGroup = function () {

                if (vm.selectAllGroup) {
                    vm.selectAllGroup = true;
                } else {
                    vm.selectAllGroup = false;
                }

                angular.forEach(vm.groupsList, function (value, key) {

                    vm.selectedOptionGroup[value.groupId] = vm.selectAllGroup;
                });

            };

            vm.inspectcheckAllGroup = function () {
                vm.count = 0;
                angular.forEach(vm.selectedOptionGroup, function (value, key) {
                    if (value == true) {
                        vm.count = vm.count + 1;
                    }
                });
                if (vm.count == vm.groupsList.length) {
                    vm.selectAllGroup = true;
                } else {
                    vm.selectAllGroup = false;
                }
            }
            vm.showLoader = false;
        }


        //Assign default values and perform actions based on actionType 
        if (vm.actionType == "list") {
            $log.debug($localStorage.userTableState);
            if (angular.isDefined($localStorage.userTableState) && angular.isDefined($localStorage.userTableState.pagination) && angular.isDefined($localStorage.userTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.userTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;
            ; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.USER_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['user'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['user'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['user'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['user'].indexOf('view') !== -1 ? true : false;
            vm.permission['manageSecurity'] = $rootScope.permission['user'].indexOf('manageSecurity') !== -1 ? true : false;
            //Uncomment the below line to activate the User association link
            vm.permission['manageAssociation'] = $rootScope.permission['user'].indexOf('manageAssociation') !== -1 ? true : false;
            vm.selectOption = '';
            //console.log(vm.permission.indexOf('edit') === -1)
            vm.showLoader = false;
        }
        else if (vm.actionType == "create")
        {
            vm.pageTitle = 'ALERTS.CREATE_NEW_USER';
            vm.showLoader = false;
        }
        else if (vm.actionType == "edit")
        {
            vm.pageTitle = 'PAGE_TITLE.USER_EDIT';
            vm.id = $rootScope.$stateParams.id;

            userService.getUserById(vm.id).then(function (response) {
                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.middleInitial = vm.user.middleName;
                    vm.user.country = vm.user.countryId;
                    vm.user.state = vm.user.stateId;
                    vm.user.postalcode = vm.user.postalCode;
                    vm.user.contactHome = vm.user.phone1;
                    vm.user.contactOffice = vm.user.phone2;
                    vm.selectRole = vm.user.userBelongsTo;
                    vm.user.userType = vm.user.userTypeId;
                    var flag = 0
                    //selecting country based on saved value during creation
                    angular.forEach(vm.countryList, function (values, key) {
                        if (values.countryId == vm.user.country && flag == 0)
                        {
                            vm.user.selectedOptionCountry = values;
                            var selectedCountryId = values.countryId;

                            userService.getstateList(selectedCountryId).then(function (response) {
                                vm.stateList = response.data;
                                var stateflag = 0;
                                //vm.user.selectedOptionState = vm.stateList[vm.user.stateId]; 
                                angular.forEach(vm.stateList, function (values, key) {

                                    if (values.stateId == vm.user.stateId && stateflag == 0)
                                    {
                                        vm.user.selectedOptionState = values;
                                        stateflag = 1;
                                    }
                                });
                                // vm.user.selected_State = vm.user.stateId;
                                $log.debug(vm.user.selected_State);
                            });
                            flag = 1;

                        }
                    });

                    //selecting roles based on saved value during creation
                    angular.forEach(vm.user.role, function (value, key) {

                        vm.selectedOptionRole[value.roleId] = true;

                    });
                    //selecting roles based on saved value during creation
                    angular.forEach(vm.user.group, function (value, key) {

                        vm.selectedOptionGroup[value.groupId] = true;

                    });
                } else if (response.status === 404) {
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }
                }
            });
            vm.showLoader = false;
        }
        else if ((vm.actionType == "view" || vm.actionType == "delete") && $rootScope.$stateParams.id !== '') {
            vm.id = $rootScope.$stateParams.id;
            if (vm.actionType == 'view') //check actionType to assign page title
                vm.pageTitle = "PAGE_TITLE.USER_VIEW_LABEL"; //Page title mapped to locale json key of view label
            else
                vm.pageTitle = "PAGE_TITLE.USER_DELETE_LABEL"; //Page title mapped to locale json key of delete label

            //Get user for the given id by calling user/{id} api
            userService.getUserById(vm.id).then(function (response) {
                $log.debug(response);
                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.email;
                    vm.user.middleInitial = vm.user.middleName;
                    vm.user.country = vm.user.countryId;
                    vm.user.state = vm.user.stateId;
                    vm.user.postalcode = vm.user.postalCode;
                    vm.selectRole = vm.user.userBelongsTo;

                    if (vm.user.status == vm.activeValue)
                    {
                        vm.user.status = "LABELS.STATUS_ACTIVE";
                    }
                    else if (vm.user.status == vm.inactiveValue)
                    {
                        vm.user.status = "LABELS.STATUS_INACTIVE";
                    }

                    if (vm.selectRole == 1)
                    {
                        angular.forEach(vm.user.role, function (value, key) {
                            var rolename = value.roleName;
                            selectedRole.push(rolename);
                            vm.role = selectedRole.join(',');
                        });
                    }
                    else if (vm.selectRole == 2)
                    {
                        angular.forEach(vm.user.group, function (value, key) {

                            selectedGroup.push(value.groupName);
                            vm.group = selectedGroup.join(',');
                        });
                    }
                    $log.debug(vm.selectedOptionRole);

                } else if (response.status === 404) {
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }

                }

                vm.showLoader = false;

            });


        } else if (vm.actionType == "association") {
            vm.searchFilter.userdataAssoc = {}, vm.searchFilter.selectedUserDetails = [];
            //vm.searchFilter.status = "Authoring";
            if (angular.isDefined($localStorage.userAssociateTableState) && angular.isDefined($localStorage.userAssociateTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.userAssociateTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault; //Default data per page

            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options


            vm.pageTitle = "PAGE_TITLE.USER_ASSOCIATION_TITLE";

            vm.id = $rootScope.$stateParams.id;

            //vm.closeOtherAccordions = true, 
            vm.otherInfo = false;




        }
        else if (angular.isUndefined(vm.id)) {

            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("user.list")
        }


        //User list table pipe function. 
        //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.userTablePipe = function (tableState, isSearch, isClear) {

            var params = {};
            var flag = 0;

            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {

                $localStorage.userTableState = {};
                vm.searchFilter.firstName = "";
                vm.searchFilter.lastName = "";
                vm.searchFilter.userEmail = "";
                vm.searchFilter.role = "";
                vm.searchFilter.group = "";
                vm.searchFilter.selectRole = "";

            }

            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.userTableState); //Extend the stored table state with the current one. 

            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;


                //Add entered first name in the searchParams
                if (angular.isDefined(vm.searchFilter.firstName) && vm.searchFilter.firstName != "")
                    searchParams.firstName = vm.searchFilter.firstName;

                //Add entered last name in the searchParams
                if (angular.isDefined(vm.searchFilter.lastName) && vm.searchFilter.lastName != "")
                    searchParams.lastName = vm.searchFilter.lastName;

                //Add entered email in the searchParams
                if (angular.isDefined(vm.searchFilter.userEmail) && vm.searchFilter.userEmail !== "")
                    searchParams.emailAddress = vm.searchFilter.userEmail;

                searchParams.selectedRoleGroup = vm.searchFilter.selectRole;

                //Add entered role name in the searchParams
                if (angular.isDefined(vm.searchFilter.role) && vm.searchFilter.role !== "" && searchParams.selectedRoleGroup == 1)
                {
                    searchParams.role = vm.searchFilter.role;
                    vm.errorMsg = "";
                }
                else if ((angular.isUndefined(vm.searchFilter.role) || vm.searchFilter.role == '') && searchParams.selectedRoleGroup == 1)
                {
                    vm.errorMsg = "ERRORS.REQUIRED_ERROR";
                    flag = 1;
                }

                //Add entered group name in the searchParams
                if (angular.isDefined(vm.searchFilter.group) && vm.searchFilter.group !== "" && searchParams.selectedRoleGroup == 2)
                {
                    searchParams.group = vm.searchFilter.group;
                    vm.errorMsg = "";

                }
                else if ((angular.isUndefined(vm.searchFilter.group) || vm.searchFilter.group == '') && searchParams.selectedRoleGroup == 2)
                {
                    vm.errorMsg = "ERRORS.REQUIRED_ERROR";
                    flag = 1;
                }

                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = searchParams;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model
                vm.searchFilter.firstName = tableState.search.firstName;
                vm.searchFilter.lastName = tableState.search.lastName;
                vm.searchFilter.userEmail = tableState.search.emailAddress;
                vm.searchFilter.role = tableState.search.role;
                vm.searchFilter.group = tableState.search.group;

                if ((tableState.search.selectedRoleGroup == 1 && angular.isDefined(tableState.search.role)) || (tableState.search.selectedRoleGroup == 2 && angular.isDefined(tableState.search.group)))
                {
                    vm.searchFilter.selectRole = tableState.search.selectedRoleGroup;
                }
                else
                {
                    vm.searchFilter.selectRole = '';
                }
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                //console.log(vm.searchFilter.metadataType)
            }
            if (flag == 0)
            {
                vm.showLoader = true;
                vm.selectOption = vm.searchFilter.selectRole;
                //Finding and assigning current page number
                if (tableState.pagination.start !== 0)
                    vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
                else
                    vm.pageNumber = 1;

                //Add sort filters in the params
                if (angular.isDefined(tableState.sort.predicate))
                    params.sort = (tableState.sort.reverse ? '-' : '+') + tableState.sort.predicate;
                else { //Default Sorting by user email
                    params.sort = "+firstName";
                    tableState.sort.predicate = "firstName";
                }
                if (isClear === true) {
                    params.sort = "+firstName";
                    tableState.sort.predicate = "firstName";
                    tableState.sort.reverse = false;
                    vm.table.dataPerPage = 10;
                    //console.log(tableState.sort)
                }

                params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;

                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call metadata service to get list of metadata details 
                userService.getuserDetails(params, tableState).then(function (response) {

                    vm.userDetails = response.results.data;

                    $log.debug(response);
                    vm.table.totalRecords = response.results.total;
                    tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    vm.table.tableStateScopeCopy = $localStorage.userTableState = tableState;
                    $log.debug(response.results)
                    $log.debug("Total Result:" + response.results.total)
                });
            }
        };
        vm.createUser = function () {


            userParam = userFormValidation();
            
            $log.debug($scope.userForm.$valid);
            $log.debug(vm.user);
            //calling create user api and checking response.If status is true return to listing page else display error message.
            if (userParam && $scope.userForm.$valid == true) {
                if (angular.isUndefined(vm.id) && vm.actionType == "create") {
                    userParam.userType = vm.userType;
                    userService.insertUser(userParam).then(function (response) {

                        $window.scroll(0, 0);
                        if (response.status === 201) {
                            vm.alertConfig.class = 'wk-alert-success';
                            vm.alertConfig.details = 'ALERTS.CREATE_SUCCESS';
                            vm.alertConfig.isList = false;
                            $timeout(function () {
                                vm.alertConfig.show = false; //Hides alert
                                $rootScope.$state.go('user.list');
                            }, 2000);
                        }
                        else if (response.status === 409) {
                            if(response.data.code === 5003)
                            {
                            var displayMsg = 'ERRORS.DUPLICATE_USER_NAME';
                            }
                            else if(response.data.code === 5010)
                            {
                            var displayMsg = 'ERRORS.INVALID_EMAIL';   
                            }
                            vm.alertConfig.class = 'wk-alert-danger';
                            // vm.alertConfig.details = [{ "errorMsg": "DUPLICATE_TAG_NAME" }];
                            vm.alertConfig.details = displayMsg;
                            vm.alertConfig.isList = false;
                        }
                    });
                } else {
                    //calling update metadata api and checking response
                    userParam.changePassword = vm.user.changePassword;
                    userParam.userType = vm.user.userType;
                    $log.debug(userParam);
                    userService.updateUser(userParam, vm.id).then(function (response) {
                        $window.scroll(0, 0);
                        if (response.status === 204) {
                            vm.alertConfig.class = 'wk-alert-success';
                            vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                            vm.alertConfig.isList = false;

                        } else if (response.status === 409) {
                            if(response.data.code === 5003)
                            {
                            var displayMsg = 'ERRORS.DUPLICATE_USER_NAME';
                            }
                            else if(response.data.code === 5010)
                            {
                            var displayMsg = 'ERRORS.INVALID_EMAIL';   
                            }
                           
                            vm.alertConfig.class = 'wk-alert-danger';
                            vm.alertConfig.details = displayMsg;
                            vm.alertConfig.isList = false;
                        } else {
                            vm.alertConfig.class = 'wk-alert-danger';
                            vm.alertConfig.details = 'ALERTS.EDIT_FAILED';
                            vm.alertConfig.isList = false;

                        }
                    });

                }
                vm.alertConfig.show = true;

            }
        };

        var userFormValidation = function () {
            var userParam = {};
            if(vm.user.password != vm.user.confirmPassword)
            {
                
                $scope.userForm.$valid = false;
                return false;
            }
            
            userParam.userName = vm.user.userName;
            userParam.userEmail = vm.user.userEmail;
            userParam.password = vm.user.password;
            userParam.firstName = vm.user.firstName;
            if (!angular.isUndefined(vm.user.middleInitial))
            {
                userParam.middleInitial = vm.user.middleInitial;
            }
            userParam.lastName = vm.user.lastName;
            userParam.address1 = vm.user.address1;
            if (!angular.isUndefined(vm.user.address2))
            {
                userParam.address2 = vm.user.address2;
            }
            if (!angular.isUndefined(vm.user.address3))
            {
                userParam.address3 = vm.user.address3;
            }
            if (!angular.isUndefined(vm.user.address4))
            {
                userParam.address4 = vm.user.address4;
            }
            userParam.phone1 = vm.user.contactHome;
            userParam.phone2 = vm.user.contactOffice;
            userParam.city = vm.user.city;

            userParam.countryId = vm.user.selectedOptionCountry.countryId;
            userParam.stateId = vm.user.selectedOptionState.stateId;
            
            if (angular.isUndefined(vm.selectRole))
            {
                vm.errorMsg = 'ERRORS.SELECT_ROLE_OR_GROUP';
                return false;
            } else {
                vm.errorMsg = "";

            }
            userParam.selectedRoleGroup = vm.selectRole;
            $log.debug(userParam.selectedRoleGroup);
            $log.debug(vm.selectedOptionGroup);
            if (userParam.selectedRoleGroup == 1)
            {
                //get all the roles selected
                userParam.getRoles = [];
                angular.forEach(vm.selectedOptionRole, function (value, key) {
                    if (value == true)
                    {
                        userParam.getRoles.push(key);
                    }
                });
                if (userParam.getRoles.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_ROLE';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getRoles = userParam.getRoles.join(',');
            }
            else if (userParam.selectedRoleGroup == 2)
            {
                //get all the roles selected
                userParam.getGroups = [];

                angular.forEach(vm.selectedOptionGroup, function (value, key) {
                    if (value == true)
                    {
                        userParam.getGroups.push(key);
                    }
                });
                $log.debug("$$$$$$$$$$"+userParam.getGroups.length);
                if (userParam.getGroups.length == 0)
                {
                    vm.errorRoleMsg = 'ERRORS.SELECT_MIN_GROUP';
                    return false;
                } else {
                    vm.errorRoleMsg = "";

                }
                userParam.getGroups = userParam.getGroups.join(',');
            }
            userParam.postalcode = vm.user.postalcode;
            userParam.status = vm.user.status;
            userParam.userId = $rootScope.userId;

            if (vm.selectRole == '')
            {
                vm.errorMsg = '';
            }
            $log.debug(userParam);
            return userParam;


        }
        vm.getStates = function () {
            var selectedCountryId = vm.user.selectedOptionCountry.countryId;

            userService.getstateList(selectedCountryId).then(function (response) {
                vm.stateList = response.data;
                vm.user.selectedOptionState = '';
            });

        }
        vm.copyemail = function () {

            if (vm.user_name_email == true)
            {
                vm.user.userName = vm.user.userEmail;
            }
            else if (vm.user_name_email == false)
            {
                vm.user.userName = '';
            }

        }
        vm.unchecksame = function () {
            vm.user_name_email = false;
        }

        //Deletes the user based on user id
        vm.deleteUser = function () {
            userService.deleteUser(vm.id).then(function (response) {
                vm.alertConfig.show = true;
                $window.scroll(0, 0);
                if (response) {
                    vm.alertConfig.class = 'wk-alert-success';
                    vm.alertConfig.msg = 'ALERTS.DELETE_SUCCESS';

                } else {
                    vm.alertConfig.class = 'wk-alert-danger';
                    vm.alertConfig.msg = 'ALERTS.DELETE_FAILED';
                }

                vm.alertConfig.isList = false;
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go('user.list');
                }, 2000);
            });
        }

        //Called when alert display time out
        vm.closeAlert = function () {
            vm.alertConfig.show = false; //Hides alert
            if (vm.alertConfig.class == "wk-alert-success" && !vm.validationError)
                $rootScope.$state.go('user.list');
        };

        //User association related information
        vm.userNonAssociateTablePipe = function (tableState, isSearch, isClear) {
           
            vm.errorMsg = '';
            vm.associatedErrorMsg = '';
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userNonAssociateTableState = {};
                if (vm.preSelectRole == 1)
                {
                    vm.searchFilter.roleNameNonAssociated = "";
                }
                else if (vm.preSelectRole == 2)
                {
                    vm.searchFilter.groupNameNonAssociated = "";
                }

                vm.searchFilter.selectedUserAssocDetails = [];
                vm.searchFilter.userdataAssoc = {};
                vm.questionCheck = [];
            }
            //Check if any local tables exist
            //And check if vm.table.tableStateScopeCopy is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userNonAssociateTableState && angular.isUndefined(vm.table.tableStateScopeCopy))
                angular.extend(tableState, $localStorage.userNonAssociateTableState); //Extend the stored table state with the current one. 
            //
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState.pagination.start = 0;
                vm.showLoader = true;

                if (vm.preSelectRole == 1)
                {
                    //Add entered item name in the searchParams
                    if (angular.isDefined(vm.searchFilter.roleNameNonAssociated) && vm.searchFilter.roleNameNonAssociated != "")
                        searchParams.roleName = vm.searchFilter.roleNameNonAssociated;
                }
                else if (vm.preSelectRole == 2)
                {
                    //Add entered item name in the searchParams
                    if (angular.isDefined(vm.searchFilter.groupNameNonAssociated) && vm.searchFilter.groupNameNonAssociated != "")
                        searchParams.groupName = vm.searchFilter.groupNameNonAssociated;
                }



                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.userdataAssoc) && !angular.equals({}, vm.searchFilter.userdataAssoc))
                    searchParams.userdataAssoc = vm.searchFilter.userdataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState.search.selectedUserAssocDetails = vm.searchFilter.selectedUserAssocDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model


                vm.searchFilter.userdataAssoc = tableState.search.userdataAssoc || {};
                vm.searchFilter.selectedUserAssocDetails = tableState.search.selectedUserAssocDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState.search);
                delete params.selectedUserAssocDetails; //selectedUserAssocDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState.pagination.start / vm.table.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
           { //Default Sorting by item tag name
                if (vm.preSelectRole == 1)
                {
                    params.sort = (tableState.sort.reverse ? '-' : '+') + "roleName";
                   
                }
                else if (vm.preSelectRole == 2)
                {
                   params.sort = (tableState.sort.reverse ? '-' : '+') + "groupName";
                }
            }
            if (isClear === true) {
                if (vm.preSelectRole == 1)
                {
                    params.sort = "+roleName";
                    //tableState.sort.predicate = "roleNameNonAssociated";
                }
                else if (vm.preSelectRole == 2)
                {
                    params.sort = "+groupName";
                    //tableState.sort.predicate = "groupNameNonAssociated";
                }
                tableState.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table.dataPerPage;
            if(angular.isUndefined(vm.preSelectRole))
            {
            //call item service to get list of item details 
            userService.getUserById(vm.id).then(function (response) {
                $log.debug(response);

                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.firstName = vm.user.firstName;
                    vm.user.lastName = vm.user.lastName;
                    vm.preSelectRole = vm.user.userBelongsTo;


                    params.associated = 0;
                    params.associatedUserId = vm.id;
                    params.UserId = $rootScope.userId;
                    params.selectedButton = vm.preSelectRole;
                    $log.debug("Passed Parameters NA:" + JSON.stringify(params))
                    userService.getuserAssociatedDetails(params).then(function (response) {

                        vm.userNonAssociatedDetails = response.results.data;
                        vm.table.totalRecords = response.results.total;
                        tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table.tableStateScopeCopy = tableState;
                        $localStorage.userNonAssociateTableState = angular.copy(tableState)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)


                    });
                }
                else if (response.status === 404) { //Error in case of data not found on server
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;
            });
        }
        else
        {
             params.associated = 0;
                    params.associatedUserId = vm.id;
                    params.UserId = $rootScope.userId;
                    params.selectedButton = vm.preSelectRole;
                    $log.debug("Passed Parameters NA:" + JSON.stringify(params))
                    userService.getuserAssociatedDetails(params).then(function (response) {

                        vm.userNonAssociatedDetails = response.results.data;
                        vm.table.totalRecords = response.results.total;
                        tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table.tableStateScopeCopy = tableState;
                        $localStorage.userNonAssociateTableState = angular.copy(tableState)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)


                    });
        }
        }
        //This will be called for displaying associated records.
        vm.userAssociateTablePipe = function (tableState1, isSearch, isClear) {
            vm.errorMsg = '';
            vm.associatedErrorMsg = '';
            var params = {};
            vm.metadataAccordianOpen = false; //Close metadata accordion filter
            vm.showLoader = true; //Shows Loader
            params.userId = $rootScope.userId; //Add userId in request param
            //this is to clear the search filter form and display the default records
            if (isClear === true) {
                $localStorage.userAssociateTableState = {};
                if (vm.preSelectRole == 1)
                {
                    vm.searchFilter.roleName = "";
                }
                else if (vm.preSelectRole == 2)
                {
                    vm.searchFilter.groupName = "";
                }


                vm.searchFilter.selectedUserAssocDetails = [];
                vm.searchFilter.userdataAssoc = {};
                vm.userAssocUnCheck = [];
            }
            //Check if any local tables exist
            //And check if vm.table1.tableStateScopeCopy1 is undefined to avoid extend state process for search, pagination and dateCountPerPage changes. 
            if ($localStorage.userAssociateTableState && angular.isUndefined(vm.table1.tableStateScopeCopy1))
                angular.extend(tableState1, $localStorage.userAssociateTableState); //Extend the stored table state with the current one. 
            //Check the action is from search and this block add search filters to the params 
            if (isSearch === true) {
                var searchParams = {};
                tableState1.pagination.start = 0;
                vm.showLoader = true;

                //Add entered item name in the searchParams
                if (vm.preSelectRole == 1)
                {
                    if (angular.isDefined(vm.searchFilter.roleName) && vm.searchFilter.roleName != "")
                        searchParams.roleName = vm.searchFilter.roleName;
                }
                else if (vm.preSelectRole == 2)
                {
                    if (angular.isDefined(vm.searchFilter.groupName) && vm.searchFilter.groupName != "")
                        searchParams.groupName = vm.searchFilter.groupName;
                }

                //Add entered metadata filter details in the searchParams
                if (angular.isDefined(vm.searchFilter.userdataAssoc) && !angular.equals({}, vm.searchFilter.userdataAssoc))
                    searchParams.userdataAssoc = vm.searchFilter.userdataAssoc


                //Assign the searchParms obj to table state search obj. 
                //This will be used later used to preserve the search filter values when user comes refresh current page/comes from different page.
                tableState1.search = angular.copy(searchParams);

                //Adding selected metadata filter values. This will not passed as searchParam so storing it after table state after searchParam
                tableState1.search.selectedUserAssocDetails = vm.searchFilter.selectedUserAssocDetails;
                //Bind the Search Params with actual params variable
                params = angular.extend(params, searchParams);
            } else if (!angular.equals({}, tableState1.search)) { //Check if existing search filter values exist
                //Assign the previous search filter values to model



                vm.searchFilter.userdataAssoc = tableState1.search.userdataAssoc || {};
                vm.searchFilter.selectedUserAssocDetails = tableState1.search.selectedUserAssocDetails || [];
                //Assign existing search filter to params to preserve old search state
                params = angular.extend(params, tableState1.search);
                delete params.selectedUserAssocDetails; //selectedUserAssocDetails not required to pass in api as param. Hence removing.
            }

            //Finding and assigning current page number
            if (tableState1.pagination.start !== 0)
                vm.pageNumber = Math.ceil(tableState1.pagination.start / vm.table1.dataPerPage) + 1;
            else
                vm.pageNumber = 1;

            //Add sort filters in the params
            
            { //Default Sorting by item tag name
                if (vm.preSelectRole == 1)
                {
                    params.sort = (tableState1.sort.reverse ? '-' : '+') + "roleName";
                   
                }
                else if (vm.preSelectRole == 2)
                {
                   params.sort = (tableState1.sort.reverse ? '-' : '+') + "groupName";
                }
            }
            if (isClear === true) {
                if (vm.preSelectRole == 1)
                {
                    params.sort = "+roleName";
                    //tableState1.sort.predicate = "roleName";
                }
                else if (vm.preSelectRole == 2)
                {
                    params.sort = "+groupName";
                   // tableState1.sort.predicate = "groupName";
                }
                tableState1.sort.reverse = false;
                vm.table1.dataPerPage = config.recordsPerPageDefault;
            }

            params.page = vm.pageNumber, params.perPage = vm.table1.dataPerPage;

            if(angular.isUndefined(vm.preSelectRole))
            {
            //Get item detail from server based on item id
            userService.getUserById(vm.id).then(function (response) {
                $log.debug(response);

                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.firstName = vm.user.firstName;
                    vm.user.lastName = vm.user.lastName;
                    vm.preSelectRole = vm.user.userBelongsTo;
                    //params.userId = $rootScope.userId;
                    params.associated = 1;
                    params.associatedUserId = vm.id;
                    params.selectedButton = vm.preSelectRole;
                    params.UserId = $rootScope.userId;
                    $log.debug("Passed Parameters UA:" + JSON.stringify(params))
                    //call item service to get list of itembank associated details 

                    userService.getuserAssociatedDetails(params).then(function (response) {

                        $log.debug(response);
                        vm.userAssociatedDetails = response.results.data;
                        vm.table1.totalRecords = response.results.total;
                        tableState1.pagination.numberOfPages = Math.ceil(response.results.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableState1;
                        $localStorage.userAssociateTableState = angular.copy(tableState1)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)

                    });

                } else if (response.status === 404) { //Error in case of data not found on server
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }

                }
                vm.showLoader = false;

            });

            }
            else
            {
                  params.associated = 1;
                    params.associatedUserId = vm.id;
                    params.selectedButton = vm.preSelectRole;
                    params.UserId = $rootScope.userId;
                    $log.debug("Passed Parameters UA:" + JSON.stringify(params))
                    //call item service to get list of itembank associated details 

                    userService.getuserAssociatedDetails(params).then(function (response) {

                        $log.debug(response);
                        vm.userAssociatedDetails = response.results.data;
                        vm.table1.totalRecords = response.results.total;
                        tableState1.pagination.numberOfPages = Math.ceil(response.results.total / vm.table1.dataPerPage);
                        vm.showLoader = false; //Hide loader
                        //Save the current table state in localstorage
                        vm.table1.tableStateScopeCopy1 = tableState1;
                        $localStorage.userAssociateTableState = angular.copy(tableState1)
                        $log.debug(response.results)
                        $log.debug("Total Result:" + response.results.total)

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
            if (!isList) { //Redirect if alert type is not list. List will be used for showing server side errors.
                $timeout(function () {
                    vm.alertConfig.show = false; //Hides alert
                    $rootScope.$state.go(redirectState); //Redirects to provided state
                }, config.alertTimeOut);
            }
        }

        //save associate/dis associate changes
        //called on clicking submit of question association
        vm.associateUserData = function (onlyValidate) {
            $log.debug("%%%%%"+onlyValidate);
            var params = {};
            if (vm.preSelectRole == 1)
            {
                params.userBelongsTo = 1
            }
            else if (vm.preSelectRole == 2)
            {
                params.userBelongsTo = 2
            }
            if (vm.activeTabIndex == 0) {

                vm.isSubmitDisabled = 1;
                var alertMsg = 'ALERTS.DISASSOCIATE_SUCCESS';
                //validating id atleast one question bank is selected


                params.getAssociation = [];
                angular.forEach(vm.unCheckRecord, function (value, key) {
                    
                    if (value == true) //get all records to be removed
                        params.getAssociation.push(key);
                });
                var flag = params.getAssociation.length;
                params.getAssociation = params.getAssociation.join(',');

                params.userId = $rootScope.userId;
                params.associated = 0;



            } else if (vm.activeTabIndex == 1) {
                var alertMsg = 'ALERTS.ASSOCIATE_SUCCESS';
                vm.isRemoveDisabled = 1;



                params.getAssociation = [];
                
                angular.forEach(vm.checkRecord, function (value, key) {
                    if (value == true) //get all records to be added
                        params.getAssociation.push(key);
                });
                var flag = params.getAssociation.length;
                params.getAssociation = params.getAssociation.join(',');
                params.userId = $rootScope.userId;
                params.associated = 1;
                
                //validating id atleast one question bank is selected

            }
            
            //call api to save user to role/group association
            if (flag > 0) {
                vm.errorMsg = '';
                vm.associatedErrorMsg = '';
                if(onlyValidate != 1)
                {
                userService.associateRoleOrGroup(vm.id, params).then(function (response) {
                    if (response.status == 204) {
                        vm.alertConfig.timeOutAlert('wk-alert-success', alertMsg, '', true);

                    } else {
                        vm.alertConfig.timeOutAlert('wk-alert-success', alertMsg, '', true);
                    }
                    //refresh tab based on associate or nonassociate
                    if (vm.activeTabIndex == 0) {
                        vm.userAssociateTablePipe(vm.table.tableStateScopeCopy, true);
                        vm.unCheckRecord = []; //Need to clear checked marks in  associated tab records
                    }
                    if (vm.activeTabIndex == 1) {
                        //console.log(vm.tableStateScopeCopy1)
                        vm.activeTabIndex = 0;
                        vm.userAssociateTablePipe(vm.table.tableStateScopeCopy, true);
                        vm.checkRecord = []; //Need to clear checked marks in non associated tab records
                    }
                });
            }

            }
            else
            {
                if (vm.preSelectRole == 1)
                {
                    if (vm.activeTabIndex == 1)
                    {
                        vm.errorMsg = 'ERRORS.SELECT_USERROLEASSOC';
                    }
                    if (vm.activeTabIndex == 0)
                    {
                        vm.associatedErrorMsg = 'ERRORS.SELECT_USERROLEASSOC';
                    }
                }
                else
                {
                    if (vm.activeTabIndex == 1)
                    {
                        vm.errorMsg = 'ERRORS.SELECT_USERGROUPASSOC';
                    }
                    if (vm.activeTabIndex == 0)
                    {
                        vm.associatedErrorMsg = 'ERRORS.SELECT_USERGROUPASSOC';
                    }
                }
            
                return false;
            }
        }
        
    })
})();
