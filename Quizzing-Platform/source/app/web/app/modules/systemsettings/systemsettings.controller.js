(function() {
    'use strict';

    angular.module('app.systemsettings').controller('SystemsettingsController', function($rootScope, $scope, $window, $log, $localStorage, $filter , $timeout, config , systemsettingsService) {
        var vm = this;
        vm.id = $rootScope.$stateParams.id;
       
        vm.table = vm.searchFilter = vm.user = {};
        vm.showdropdown = 0;
        vm.actionType = $rootScope.$state.current.name.split(".")[1];
        vm.alpharegex = '^[a-zA-Z ]+$';
        vm.alphanumericregex = '^[a-zA-Z0-9 ]+$';
        vm.alertConfig = { 'show': false };
        vm.emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        
        vm.showdropdown = function(){
            vm.showdropdown =1;
        };
        
        vm.pageError = false;
        vm.showLoader = true;
       
        var userParam = {};
        var roleDetails = {};
        var pageIntDropDown = { availableOptions: [ {id: '10', name: '10'}, {id: '15', name: '15'}, {id: '20', name: '20'}, {id: '25', name: '25'}, {id: '50', name: '50'}, {id: '75', name: '75'}, {id: '100', name: '100'}]};
      
        
        if ($localStorage.userTypeList) {
            vm.userTypeList = $localStorage.userTypeList;
            //select default country as 
            angular.forEach(vm.userTypeList, function(value, key) {
                       if(value.userTypeName == 'ADMIN')
                        {
                            vm.userType = value.userTypeId;
                        }
                 })

        } else {
            //call user service to get list of usertype
            systemsettingsService.getuserTypeList().then(function(response) {
                $localStorage.userTypeList = vm.userTypeList = response.data;
                 angular.forEach(vm.userTypeList, function(value, key) {
                       if(value.userTypeName == 'ADMIN')
                        {
                            vm.userType = value.userTypeId;
                        }
                 })
               
            });
        
        } 
        /*
        if ($localStorage.statusList) {
            vm.statusList = $localStorage.statusList;
            //select default country as 
            angular.forEach(vm.statusList, function(value, key) {
                 
                if(value.statusName == 'ACTIVE')
                {
                    vm.activeValue = value.statusCode;
                }
                if(value.statusName == 'INACTIVE')
                {
                    vm.inactiveValue = value.statusCode;
                }
                vm.group.status = vm.activeValue;
                });
                vm.group.status = vm.activeValue;
               
        } else {
            //call user service to get list of states    
               groupService.getStatus().then(function(response) {
                   $localStorage.statusList = vm.statusList = response.data;
                   angular.forEach(vm.statusList, function(value, key) {
                       $log.debug(value);
                   if(value.statusName == 'ACTIVE')
                   {
                       vm.activeValue = value.statusCode;
                   }
                   if(value.statusName == 'INACTIVE')
                   {
                       vm.inactiveValue = value.statusCode;
                   }
                   vm.group.status = vm.activeValue;
                   });

           });
        }
        */
        //Assign default values and perform actions based on actionType 
        
        /*
        if (vm.actionType == "list") {
            $log.debug($localStorage.groupTableState);
            if (angular.isDefined($localStorage.groupTableState) && angular.isDefined($localStorage.groupTableState.pagination) && angular.isDefined($localStorage.groupTableState.pagination.number))
                vm.table.dataPerPage = $localStorage.groupTableState.pagination.number
            else
                vm.table.dataPerPage = config.recordsPerPageDefault;; //Default data per page
            
            vm.table.dataPerPageOptions = config.recordsPerPage; //Default date per page options
            vm.pageTitle = "PAGE_TITLE.GROUP_LIST_LABEL"; //Page title mapped to locale json key
            vm.permission = vm.permission || {}
            vm.permission['create'] = $rootScope.permission['group'].indexOf('create') !== -1 ? true : false;
            vm.permission['edit'] = $rootScope.permission['group'].indexOf('edit') !== -1 ? true : false;
            vm.permission['delete'] = $rootScope.permission['group'].indexOf('delete') !== -1 ? true : false;
            vm.permission['view'] = $rootScope.permission['group'].indexOf('view') !== -1 ? true : false;
            vm.showLoader = false;
        } else */
        if ((vm.actionType == "list") && $rootScope.$stateParams.id !== '') {
            
            vm.id = $rootScope.$stateParams.id;
            vm.pageTitle = "PAGE_TITLE.SYSTEMSETTINGS_VIEW_LABEL"; //Page title mapped to locale json key of view label
            
            var params = {};
            params.userId = $rootScope.userId;
      
            vm.emailDomainFlag = vm.limitFlag = vm.appnameFlag = vm.versionFlag = vm.tokenHeaderKeyFlag = false;
            vm.tokenPrefixFlag = vm.quizTimeFlag = vm.questionTimeFlag = vm.accessTokenLifeTimeFlag = false;
            
            
            //Get user for the given id by calling user/{id} api
            systemsettingsService.getSystemSettings(params).then(function(response) {
                vm.systemSettings = response.results[0];
                /** ADMIN CONFIG VALUES **/
                // CHECK EMAIL IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.emailDomain){
                    vm.emailDomain = vm.systemSettings.emailDomain;
                    vm.emailDomainFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.limit) {
                    vm.limit = vm.systemSettings.limit;
                    vm.limitFlag = true;
                    vm.pageIntDropDown = pageIntDropDown.availableOptions;
                    vm.selectedOption = { id:vm.limit, name:vm.limit };
                }
                
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.appname) {
                    vm.appname = vm.systemSettings.appname;
                    vm.appnameFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.version) {
                    vm.version = vm.systemSettings.version;
                    vm.versionFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.tokenHeaderKey) {
                    vm.tokenHeaderKey = vm.systemSettings.tokenHeaderKey;
                    vm.tokenHeaderKeyFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.tokenPrefix) {
                    vm.tokenPrefix = vm.systemSettings.tokenPrefix;
                    vm.tokenPrefixFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.quizTime) {
                    vm.quizTime = vm.systemSettings.quizTime;
                    vm.quizTimeFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.questionTime) {
                    vm.questionTime = vm.systemSettings.questionTime;
                    vm.questionTimeFlag = true;
                }
                
                // CHECK PAGE LIMIT IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.accessTokenLifeTime) {
                    vm.accessTokenLifeTime = vm.systemSettings.accessTokenLifeTime;
                    vm.accessTokenLifeTimeFlag = true;
                }
                
                /** ADMIN CONFIG VALUES END**/
                
                /** EUP CONFIG VALUES **/
                
                vm.recordsPerPage_eupFlag = vm.recordsPerPageDefault_eupFlag = vm.minRecordsPerPage_eupFlag = false;
                vm.minRecordsPerPageDefault_eupFlag = vm.alertTimeOut_eupFlag = vm.questionTime_eupFlag = false;
                vm.itemScoreMin_eupFlag = vm.itemScoreMax_eupFlag = vm.itemDifficultyMin_eupFlag = false;
                vm.itemDifficultyMax_eupFlag = vm.imageAssetAccept_eupFlag = vm.videoAssetAccept_eupFlag = false;
                vm.graphicAssetAccept_eupFlag = vm.medcaseAssetAccept_eupFlag = vm.imageMaxSize_eupFlag = false;
                vm.videoMaxSize_eupFlag = vm.graphicMaxSize_eupFlag = vm.medcaseMaxSize_eupFlag = false; 
                vm.videoAssetId_eupFlag = vm.audioAssetId_eupFlag = vm.imageAssetId_eupFlag = false;
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.recordsPerPage_eup) {
                    vm.recordsPerPage_eup = vm.systemSettings.recordsPerPage_eup;
                    vm.recordsPerPage_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.recordsPerPageDefault_eup) {
                    vm.recordsPerPageDefault_eup = vm.systemSettings.recordsPerPageDefault_eup;
                    vm.recordsPerPageDefault_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.minRecordsPerPage_eup) {
                    vm.minRecordsPerPage_eup = vm.systemSettings.minRecordsPerPage_eup;
                    vm.minRecordsPerPage_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.minRecordsPerPageDefault_eup) {
                    vm.minRecordsPerPageDefault_eup = vm.systemSettings.minRecordsPerPageDefault_eup;
                    vm.minRecordsPerPageDefault_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.alertTimeOut_eup) {
                    vm.alertTimeOut_eup = vm.systemSettings.alertTimeOut_eup;
                    vm.alertTimeOut_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.questionTime_eup) {
                    vm.questionTime_eup = vm.systemSettings.questionTime_eup;
                    vm.questionTime_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemScoreMin_eup) {
                    vm.itemScoreMin_eup = vm.systemSettings.itemScoreMin_eup;
                    vm.itemScoreMin_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemScoreMax_eup) {
                    vm.itemScoreMax_eup = vm.systemSettings.itemScoreMax_eup;
                    vm.itemScoreMax_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemDifficultyMin_eup) {
                    vm.itemDifficultyMin_eup = vm.systemSettings.itemDifficultyMin_eup;
                    vm.itemDifficultyMin_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.itemDifficultyMax_eup) {
                    vm.itemDifficultyMax_eup = vm.systemSettings.itemDifficultyMax_eup;
                    vm.itemDifficultyMax_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.imageAssetAccept_eup) {
                    vm.imageAssetAccept_eup = vm.systemSettings.imageAssetAccept_eup;
                    vm.imageAssetAccept_eupFlag = true;
                }
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.videoAssetAccept_eup) {
                    vm.videoAssetAccept_eup = vm.systemSettings.videoAssetAccept_eup;
                    vm.videoAssetAccept_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.graphicAssetAccept_eup) {
                    vm.graphicAssetAccept_eup = vm.systemSettings.graphicAssetAccept_eup;
                    vm.graphicAssetAccept_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.medcaseAssetAccept_eup) {
                    vm.medcaseAssetAccept_eup = vm.systemSettings.medcaseAssetAccept_eup;
                    vm.medcaseAssetAccept_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.imageMaxSize_eup) {
                    vm.imageMaxSize_eup = vm.systemSettings.imageMaxSize_eup;
                    vm.imageMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.videoMaxSize_eup) {
                    vm.videoMaxSize_eup = vm.systemSettings.videoMaxSize_eup;
                    vm.videoMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.graphicMaxSize_eup) {
                    vm.graphicMaxSize_eup = vm.systemSettings.graphicMaxSize_eup;
                    vm.graphicMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.medcaseMaxSize_eup) {
                    vm.medcaseMaxSize_eup = vm.systemSettings.medcaseMaxSize_eup;
                    vm.medcaseMaxSize_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.videoAssetId_eup) {
                    vm.videoAssetId_eup = vm.systemSettings.videoAssetId_eup;
                    vm.videoAssetId_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.audioAssetId_eup) {
                    vm.audioAssetId_eup = vm.systemSettings.audioAssetId_eup;
                    vm.audioAssetId_eupFlag = true;
                } 
                
                // CHECK recordsPerPage_eup IS ENABLES FOR ADMIN VIEW or NOT
                if(vm.systemSettings.imageAssetId_eup) {
                    vm.imageAssetId_eup = vm.systemSettings.imageAssetId_eup;
                    vm.imageAssetId_eupFlag = true;
                } 
                
                /** EUP CONFIG VALUES END**/
                
                
                vm.showLoader = false;
            });
        } 
       else if (angular.isUndefined(vm.id)) {
          
            $log.error("Invalid State/Action Type")
            $rootScope.$state.go("systemsettings.list")
            
        }
        
        
            //User list table pipe function. 
            //This will be called when any table related action like pagination,search,sort etc performed in the view.
        vm.systemSettingsTablePipe = function(tableState, isSearch, isClear) {
            
            var params = {};
            var flag = 0;
            
            params.userId = $rootScope.userId; //Add userId in request param
            if(flag == 0)
            {
                vm.showLoader = true;
                $log.debug("Passed Parameters:" + JSON.stringify(params))

                //call metadata service to get list of metadata details 
                systemsettingsService.getSystemSettings(params).then(function(response) {
                    
                    //vm.groupDetails = response.results.data;
                    vm.systemSettingsDetails = response.results.data;
                    
                    $log.debug(response);
                    //vm.table.totalRecords = response.results.total;
                    //tableState.pagination.numberOfPages = Math.ceil(response.results.total / vm.table.dataPerPage);
                    vm.showLoader = false; //Hide loader
                    //Save the current table state in localstorage
                    //vm.table.tableStateScopeCopy = $localStorage.groupTableState = tableState;
                    $log.debug(vm.systemSettingsDetails)
                    //$log.debug("Total Result:" + response.results.total)
                });
            }
        };
        
        var systemSettingFormValidation = function () {
            
            var userParam = {};
            userParam.emailDomain = vm.emailDomain;
            userParam.userId = $rootScope.userId;
            userParam.limit = vm.selectedOption.id;
            userParam.appname = vm.appname;
            userParam.version = vm.version;
            userParam.tokenHeaderKey = vm.tokenHeaderKey;
            userParam.tokenPrefix = vm.tokenPrefix;
            userParam.quizTime = vm.quizTime;
            userParam.questionTime = vm.questionTime;
            userParam.accessTokenLifeTime = vm.accessTokenLifeTime;
            userParam.recordsPerPage_eup = vm.recordsPerPage_eup;
            userParam.recordsPerPageDefault_eup = vm.recordsPerPageDefault_eup;
            userParam.minRecordsPerPage_eup = vm.minRecordsPerPage_eup;
            userParam.minRecordsPerPageDefault_eup = vm.minRecordsPerPageDefault_eup;
            userParam.alertTimeOut_eup = vm.alertTimeOut_eup;
            userParam.questionTime_eup = vm.questionTime_eup;
            userParam.itemScoreMin_eup = vm.itemScoreMin_eup;
            userParam.itemScoreMax_eup = vm.itemScoreMax_eup;
            userParam.itemDifficultyMin_eup = vm.itemDifficultyMin_eup;
            userParam.itemDifficultyMax_eup = vm.itemDifficultyMax_eup;
            userParam.imageAssetAccept_eup = vm.imageAssetAccept_eup;
            userParam.videoAssetAccept_eup = vm.videoAssetAccept_eup;
            userParam.graphicAssetAccept_eup = vm.graphicAssetAccept_eup;
            userParam.medcaseAssetAccept_eup = vm.medcaseAssetAccept_eup;
            userParam.imageMaxSize_eup = vm.imageMaxSize_eup;
            userParam.videoMaxSize_eup = vm.videoMaxSize_eup;
            userParam.graphicMaxSize_eup = vm.graphicMaxSize_eup;
            userParam.medcaseMaxSize_eup = vm.medcaseMaxSize_eup;
            userParam.videoAssetId_eup = vm.videoAssetId_eup;
            userParam.audioAssetId_eup = vm.audioAssetId_eup;
            userParam.imageAssetId_eup = vm.imageAssetId_eup;
            
 
            if(!angular.isUndefined(vm.appname)) {
                userParam.appname = vm.appname;
            }
            
            if(!angular.isUndefined(vm.version)) {
                userParam.version = vm.version;
            }
         
            if(!angular.isUndefined(vm.tokenHeaderKey)) {
                userParam.tokenHeaderKey = vm.tokenHeaderKey;
            }
         
            if(!angular.isUndefined(vm.tokenPrefix)) {
                userParam.tokenPrefix = vm.tokenPrefix;
            }
         
            if(!angular.isUndefined(vm.quizTime)) {
                userParam.quizTime = vm.quizTime;
            }
            
            if(!angular.isUndefined(vm.questionTime)) {
                userParam.questionTime = vm.questionTime;
            }
            
            if(!angular.isUndefined(vm.accessTokenLifeTime)) {
                userParam.accessTokenLifeTime = vm.accessTokenLifeTime;
            }
         
            if(!angular.isUndefined(vm.recordsPerPage_eup)) {
                userParam.recordsPerPage_eup = vm.recordsPerPage_eup;
            }
            
            if(!angular.isUndefined(vm.recordsPerPageDefault_eup)) {
                userParam.recordsPerPageDefault_eup = vm.recordsPerPageDefault_eup;
            }
            
            if(!angular.isUndefined(vm.minRecordsPerPage_eup)) {
                userParam.minRecordsPerPage_eup = vm.minRecordsPerPage_eup;
            }
            
            if(!angular.isUndefined(vm.minRecordsPerPageDefault_eup)) {
                userParam.minRecordsPerPageDefault_eup = vm.minRecordsPerPageDefault_eup;
            }
            
            if(!angular.isUndefined(vm.alertTimeOut_eup)) {
                userParam.alertTimeOut_eup = vm.alertTimeOut_eup;
            }
            
            if(!angular.isUndefined(vm.questionTime_eup)) {
                userParam.questionTime_eup = vm.questionTime_eup;
            }
            
            if(!angular.isUndefined(vm.itemScoreMin_eup)) {
                userParam.itemScoreMin_eup = vm.itemScoreMin_eup;
            }
         
            if(!angular.isUndefined(vm.itemScoreMax_eup)) {
                userParam.itemScoreMax_eup = vm.itemScoreMax_eup;
            }
            
            if(!angular.isUndefined(vm.itemDifficultyMin_eup)) {
                userParam.itemDifficultyMin_eup = vm.itemDifficultyMin_eup;
            }
            
            if(!angular.isUndefined(vm.itemDifficultyMax_eup)) {
                userParam.itemDifficultyMax_eup = vm.itemDifficultyMax_eup;
            }
            
            if(!angular.isUndefined(vm.imageAssetAccept_eup)) {
                userParam.imageAssetAccept_eup = vm.imageAssetAccept_eup;
            }
            
            if(!angular.isUndefined(vm.videoAssetAccept_eup)) {
                userParam.videoAssetAccept_eup = vm.videoAssetAccept_eup;
            }
            if(!angular.isUndefined(vm.graphicAssetAccept_eup)) {
                userParam.graphicAssetAccept_eup = vm.graphicAssetAccept_eup;
            }
            
            if(!angular.isUndefined(vm.medcaseAssetAccept_eup)) {
                userParam.medcaseAssetAccept_eup = vm.medcaseAssetAccept_eup;
            }
        
            if(!angular.isUndefined(vm.imageMaxSize_eup)) {
                userParam.imageMaxSize_eup = vm.imageMaxSize_eup;
            }
         
            if(!angular.isUndefined(vm.videoMaxSize_eup)) {
                userParam.videoMaxSize_eup = vm.videoMaxSize_eup;
            }
            
            if(!angular.isUndefined(vm.graphicMaxSize_eup)) {
                userParam.graphicMaxSize_eup = vm.graphicMaxSize_eup;
            }
            
            if(!angular.isUndefined(vm.medcaseMaxSize_eup)) {
                userParam.medcaseMaxSize_eup = vm.medcaseMaxSize_eup;
            }
            
            if(!angular.isUndefined(vm.videoAssetId_eup)) {
                userParam.videoAssetId_eup = vm.videoAssetId_eup;
            }
            
            if(!angular.isUndefined(vm.audioAssetId_eup)) {
                userParam.audioAssetId_eup = vm.audioAssetId_eup;
            }
            
            if(!angular.isUndefined(vm.imageAssetId_eup)) {
                userParam.imageAssetId_eup = vm.imageAssetId_eup;
            }
            
            
           
            if (angular.isUndefined(vm.emailDomain))
            {
                vm.errorMsg = 'ERRORS.USER_NAME_VAL_MSG';
                return false;
            } else {
                vm.errorMsg = "";
            }
            return userParam;
        }
        
        vm.updateSystemSetting = function () {


            userParam = systemSettingFormValidation();
            
            $log.debug($scope.systemForm);
            //calling create user api and checking response.If status is true return to listing page else display error message.
            if (userParam && $scope.systemForm.$valid == true) {
                //calling update metadata api and checking response
                $log.debug(userParam);
                systemsettingsService.updatesystemSetting(userParam, vm.id).then(function (response) {
                    $window.scroll(0, 0);
                    if (response.status === 204) {
                        vm.alertConfig.class = 'wk-alert-success';
                        vm.alertConfig.details = 'ALERTS.EDIT_SUCCESS';
                        vm.alertConfig.isList = false;

                    } else if (response.status === 409) {
                        var displayMsg = 'ERRORS.INVALID_EMAIL';
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
    }).directive('numbersOnly', function(){
        return {
          require: 'ngModel',
          link: function(scope, element, attrs, modelCtrl) {
            modelCtrl.$parsers.push(function (inputValue) {
                // this next if is necessary for when using ng-required on your input. 
                // In such cases, when a letter is typed first, this parser will be called
                // again, and the 2nd time, the value will be undefined
                if (inputValue == undefined) return '' 
                var transformedInput = inputValue.replace(/[^0-9]/g, ''); 
                if (transformedInput!=inputValue) {
                   modelCtrl.$setViewValue(transformedInput);
                   modelCtrl.$render();
                }         

                return transformedInput;         
            });
          }
        };
     });
     
})();