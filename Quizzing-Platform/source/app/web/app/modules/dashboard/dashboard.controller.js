(function() {
    'use strict';


    angular.module('app.dashboard').controller('DashboardController', function($rootScope, $window, $scope, config, $http, $localStorage, $log, dashboardService, userService, loginService) {

        var vm = this;
        vm.id = $rootScope.$stateParams.id;

        vm.user = {};
        vm.userCookie = {};
        vm.validator = {};
        vm.passwordregex = '^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$';
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        //vm.emailRegex = '^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$';
        vm.emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        vm.phoneregex = /^(?=.*[0-9])[- +()0-9]+$/;
        vm.moduleType = $rootScope.$state.current.name.split(".")[0];

        vm.sendSuccess = 0;
        vm.pageError = false;
        vm.showLoader = true;
        vm.alertConfig = { 'show': false };
        vm.actionType = $rootScope.$state.current.name.split(".")[0];
        vm.invalidusererror = false;
        vm.showdropdown = function() {

            vm.showdropdown = 1;

        };
        var userParam = {};
        //Redirect to dashboarad if the user is already logged in 
        if (vm.actionType == 'login' && angular.isDefined($localStorage.currentUser)) {
            $rootScope.$state.go('dashboard.main')
        }
        if (vm.actionType == 'dashboard' || vm.actionType == 'home') {

            dashboardService.getDashboardDetails().then(function(response) {

                vm.dashboardDetails = response.data;
            });
        }

        if (vm.actionType == 'myprofile') {
            vm.pageTitle = 'PAGE_TITLE.MYPROFILE';

            if ($localStorage.countryList) {
                vm.countryList = $localStorage.countryList;
                //select default country as 
                vm.user.selectedOptionCountry = vm.countryList[0];
                var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                userService.getstateList(selectedCountryId).then(function(response) {
                    vm.stateList = response.data;
                    vm.user.selectedOptionState = '';
                });

            } else {
                //call user service to get list of country    
                userService.getcountryList().then(function(response) {
                    $localStorage.countryList = vm.countryList = response.data;
                    //select default country as 
                    vm.user.selectedOptionCountry = vm.countryList[0];
                    var selectedCountryId = vm.user.selectedOptionCountry.countryId;
                    //call user service to get list of states    
                    userService.getstateList(selectedCountryId).then(function(response) {
                        vm.stateList = response.data;
                        vm.user.selectedOptionState = '';
                    });
                });
            }

            vm.getStates = function() {
                var selectedCountryId = vm.user.selectedOptionCountry.countryId;

                userService.getstateList(selectedCountryId).then(function(response) {
                    vm.stateList = response.data;
                    vm.user.selectedOptionState = '';
                });

            }

            vm.copyemail = function() {

                if (vm.user_name_email == true) {
                    vm.user.userName = vm.user.userEmail;
                } else if (vm.user_name_email == false) {
                    vm.user.userName = '';
                }

            }
            vm.unchecksame = function() {
                vm.user_name_email = false;
            }

            userService.getUserByIdMyProfile($rootScope.userId).then(function(response) {
                if (response.status === 200) {
                    vm.user = response.data;
                    vm.user.userEmail = vm.user.emailAddress;
                    vm.user.userName = vm.user.userName;
                    vm.user.firstName = vm.user.firstName;
                    vm.user.middleInitial = vm.user.middleName;
                    vm.user.lastName = vm.user.lastName;
                    vm.user.address1 = vm.user.address1;
                    vm.user.address2 = vm.user.address2;
                    vm.user.address3 = vm.user.address3;
                    vm.user.address4 = vm.user.address4;
                    vm.user.contactHome = vm.user.phone1;
                    vm.user.contactOffice = vm.user.phone2;
                    vm.user.city = vm.user.city;
                    vm.user.country = vm.user.countryId;
                    vm.user.state = vm.user.stateId;
                    vm.user.postalcode = vm.user.postalCode;
                    vm.user.userType = vm.user.userTypeId;
                    vm.id = $rootScope.userId;
                    var flag = 0

                    //selecting country based on saved value during creation
                    angular.forEach(vm.countryList, function(values, key) {
                        if (values.countryId == vm.user.country && flag == 0) {
                            vm.user.selectedOptionCountry = values;
                            var selectedCountryId = values.countryId;

                            userService.getstateList(selectedCountryId).then(function(response) {
                                vm.stateList = response.data;
                                var stateflag = 0;
                                //vm.user.selectedOptionState = vm.stateList[vm.user.stateId]; 
                                angular.forEach(vm.stateList, function(values, key) {

                                    if (values.stateId == vm.user.stateId && stateflag == 0) {
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
                } else if (response.status === 404) {
                    if (response.data.code == "5006") {
                        vm.pageError = true;
                    }
                }
            });
            vm.showLoader = false;
        }

        vm.updateUser = function() {


            userParam = userFormValidation();

            //calling create user api and checking response.If status is true return to listing page else display error message.
            if (userParam && $scope.userForm.$valid == true) {
                //calling update metadata api and checking response
                userParam.changePassword = vm.user.changePassword;
                userParam.userType = vm.user.userType;
                userParam.resource = 'myprofile';
                $log.debug(userParam);
                vm.id = $rootScope.userId;
                userService.updateUser(userParam, vm.id).then(function(response) {
                    $window.scroll(0, 0);
                    if (response.status === 204) {
                        vm.alertConfig.class = 'wk-alert-success';
                        vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                        vm.alertConfig.isList = false;

                    } else if (response.status === 409) {
                        var displayMsg = 'ERRORS.DUPLICATE_USER_NAME';
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.details = displayMsg;
                        vm.alertConfig.isList = false;
                    } else {
                        vm.alertConfig.class = 'wk-alert-danger';
                        vm.alertConfig.details = 'ALERTS.EDIT_FAILED';
                        vm.alertConfig.isList = false;

                    }
                });

                vm.alertConfig.show = true;

            }
        };

        var userFormValidation = function() {
            var userParam = {};
            $log.debug($scope.userForm.$valid);
            if (vm.user.password != vm.user.confirmPassword) {

                $scope.userForm.$valid = false;
                return false;
            }
            userParam.userName = vm.user.userName;
            userParam.userEmail = vm.user.userEmail;
            userParam.password = vm.user.password;
            userParam.firstName = vm.user.firstName;
            if (!angular.isUndefined(vm.user.middleInitial)) {
                userParam.middleInitial = vm.user.middleInitial;
            }
            userParam.lastName = vm.user.lastName;
            userParam.address1 = vm.user.address1;
            if (!angular.isUndefined(vm.user.address2)) {
                userParam.address2 = vm.user.address2;
            }
            if (!angular.isUndefined(vm.user.address3)) {
                userParam.address3 = vm.user.address3;
            }
            if (!angular.isUndefined(vm.user.address4)) {
                userParam.address4 = vm.user.address4;
            }
            userParam.phone1 = vm.user.contactHome;
            userParam.phone2 = vm.user.contactOffice;
            userParam.city = vm.user.city;

            userParam.countryId = vm.user.selectedOptionCountry.countryId;
            userParam.stateId = vm.user.selectedOptionState.stateId;
            userParam.postalcode = vm.user.postalcode;
            userParam.status = vm.user.status;
            userParam.userId = $rootScope.userId;
            $log.debug(userParam);
            return userParam;
        }


        //if cookies are stored, restore it and display in login page
        if (loginService.setCookie('username') && loginService.setCookie('password')) {
            vm.userCookie.userName = (loginService.setCookie('username'));
            vm.userCookie.password = (loginService.setCookie('password'));
            vm.remember = true;
        }

        //check useremail exist n send email to reset password or to send username
        vm.forgotPassword = function() {

            if ($scope.userForm.$valid == true) {
                dashboardService.forgotPassword(vm.user).then(function(response) {
                    $log.debug(response);
                    if (response.status === 200) {
                        vm.sendSuccess = 1;
                        if (vm.user.action == 'resetpassword') {
                            vm.forgotSuccessMsg = 'LABELS.FORGOT_RESET_MSG';
                        } else if (vm.user.action == 'sendusername') {
                            vm.forgotSuccessMsg = 'LABELS.FORGOT_USERNAME_MSG';
                        }
                    } else if (response.status === 400) {
                        $scope.userForm.userEmail.$error.invalidusererror = true;
                    }

                });
            }
        }
        if (vm.moduleType == 'resetpassword') {

            vm.validator.resetToken = $rootScope.$stateParams.id;
            if (vm.validator.resetToken) {
                dashboardService.validateResetPassword(vm.validator).then(function(response) {
                    $log.debug(response.data.code);
                    if (response.status === 201) {

                    } else if (response.status === 401) {
                        if (response.data.code == 8010) {

                            vm.sendSuccess = 1;
                        }

                    }

                });
            } else {
                $rootScope.$state.go('404');
            }
        }


        //change passsword for the given user based on resetToken
        vm.resetPassword = function() {

            if ($scope.userForm.$valid == true) {
                vm.user.resetToken = vm.validator.resetToken;
                dashboardService.resetPassword(vm.user).then(function(response) {
                    $log.debug(response);
                    if (response.status === 200) {
                        vm.sendSuccess = 2;
                    } else if (response.status === 400) {
                        vm.sendSuccess = 1;
                    }

                });
            }
        }

        //function to validate user and allow login
        vm.loginUser = function() {
            console.log($rootScope)
            if ($scope.userForm.$valid == true) {
                vm.invalidusererror = false;
                loginService.loginUser(vm.userCookie).then(function(response) {
                    $log.debug(response);
                    if (response.status === 200) {
                        //this is used to save as cookie for user's username and password


                        if (vm.remember) {

                            loginService.setCookie('username', (vm.userCookie.userName));
                            loginService.setCookie('password', (vm.userCookie.password));

                        } else {
                            loginService.setCookie('username', '');
                            loginService.setCookie('password', '');
                        }
                        //Assign user authorization detail in local storage
                        $localStorage.currentUser = response.data;
                        console.log($rootScope)

                        $rootScope.$state.go('dashboard.main');

                    } else if (response.status === 401) {
                        if (response.data.code === 8002) {
                            vm.invalidusererror = true;
                        }
                    }

                });
            }
        }

    })
})();
