(function() {
    /**
     * @namespace Common Directive
     * @desc Directives which are used through out site 
     * @memberOf Directive
     * @author Jagadeeshraj V S
     */
    'use strict';
    var app = angular.module('app')
    app.directive('focusFirstInvalid', function() {
        return {
            restrict: 'A',
            link: function(scope, elem) {
                // set up event handler on the form element
                elem.on('submit', function() {
                    // find the first invalid element
                    var firstInvalid = elem[0].querySelector('.ng-invalid,.wk-invalid');
                    // if we find one, set focus
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                });
            }
        };
    });
    app.directive('ngEnter', function() {
        return function(scope, element, attrs) {
            element.bind("keydown keypress", function(event) {
                if (event.which === 13) {
                    scope.$apply(function() {
                        scope.$eval(attrs.ngEnter);
                    });

                    event.preventDefault();
                }
            });
        };
    });
    app.filter('isEmptyObject', function() {
        return function(obj) {
            return (angular.equals({}, obj) || angular.isUndefined(obj));
        };
    });
})();

'use strict';


angular.module('app')
    .directive('quizPlatformFooter', function() {
        return {
            templateUrl: 'app/common/footer/footer.html',
            restrict: 'E',
            replace: true,
            controller: ['$scope', '$filter', function($scope, $filter) {
                var currentDate = new Date();
                $scope.rights = {};
                $scope.rights.year = $filter('date')(currentDate, "yyyy");
            }]
        }
    });

'use strict';

angular.module('app')
    .directive('quizPlatformHeader', function() {
        return {
            templateUrl: 'app/common/header/header.html',
            restrict: 'E',
            replace: true
        }
    });

/**
 * @namespace LOGIN
 * @desc to set and get cookies
 * @memberOf Factories
 * @author Srilakshmi R
 */

'use strict';

angular.module('app')
    .service('httpInterceptor', ['$injector', '$q', '$rootScope', '$localStorage', function($injector, $q, $rootScope, $localStorage) {

        return {
            'request': function(config) {
                var apiPattern = /\/api\//;
                config.headers = config.headers || {};
                config.params = config.params || {};
                if (angular.fromJson($localStorage.currentUser) && apiPattern.test(config.url)) {
                    config.headers.Authorization = $localStorage.currentUser.token;
                    config.headers.requestFrom = "admin";
                    config.params.userId = $localStorage.currentUser.userId;
                }
                return config;
            },
            'responseError': function(rejection, config) {
                var hideUrl = 'login';
                
                if (rejection.status === 401 && ($rootScope.$state.current.name != 'login' && $rootScope.$state.current.name != 'forgotpassword' && $rootScope.$state.current.name != 'resetpassword')) {
                    //loginService.logoutUser();
                    
                    var loginService = $injector.get('loginService');
                    loginService.logoutUser();

                }

                return $q.reject(rejection);
            }
        };
    }]);

'use strict';

angular.module('app')
    .factory('localeLoaderService', ['$state', '$http', '$q', '$rootElement', function($state, $http, $q,$rootElement) {
        // return loaderFn
        return function(options) {
            var deferred = $q.defer();
            var data = {};
console.log($rootElement.attr('ng-app'))            
           // console.log(options)
            $http.get('/app/common/locale/en.json').success(function(commonFile) {
                angular.extend(data, commonFile);

                $http.get('/app/modules/' + options.module + '/partials/locale-' + options.key + '.json').success(function(moduleB) {
                    angular.extend(data, moduleB);
                    deferred.resolve(data);
                });
            });
            return deferred.promise;
        }
    }]);

'use strict';

angular.module('app')
    .directive('quizPlatformLoginFooter', function() {
        return {
            templateUrl: 'app/common/loginfooter/loginfooter.html',
            restrict: 'E',
            replace: true,
        }
    });

'use strict';

angular.module('app')
    .directive('quizPlatformLoginHeader', function() {
        return {
            templateUrl: 'app/common/loginheader/loginheader.html',
            restrict: 'E',
            replace: true,
        }
    });

(function() {
    /**
     * @namespace Menu Directive
     * @desc Create the admin site menu 
     * @memberOf Directive
     * @author Jagadeeshraj V S
     */
    'use strict';

    angular.module('app')
        .directive('quizPlatformMenu', ['$http', 'config', function($http, config) {
            return {
                templateUrl: 'app/common/menu/menu.html',
                restrict: 'E',
                replace: true,
                controllerAs: "vm",
                controller: ['$rootScope', '$state', '$localStorage', '$element', '$attrs', '$location', '$log', 'config', 'loginService', function($rootScope, $state, $localStorage, $element, $attrs, $location, $log, config, loginService) {
                    var vm = this;
                    vm.mobMenu = false;
                    vm.mobSubMenu = false;
                    vm.goToMenu = function($event, url) {
                        angular.element(document.querySelector('li.main-menu.active')).removeClass('active')
                        var parentNode = $event.target.parentNode;
                        while (parentNode.className.indexOf('main-menu') === -1) {
                            parentNode = parentNode.parentNode;
                        }
                        angular.element(parentNode).addClass('active')
                        if (angular.isDefined(url) && url != "")
                        {
                            $location.url(url);
                            vm.mobMenu = !vm.mobMenu;
                            
                        }
                    }
                    vm.toggleMobMenu = function() {
                        vm.mobMenu = !vm.mobMenu;
                        //$log.debug("Mob Menu:" + vm.mobMenu)
                    }
                    vm.toggleMobSubMenu = function(index) {

                        angular.forEach($rootScope.menuList, function(value, key) {
                            if (key === index)
                            {
                               
                                vm.mobSubMenu[key] = !vm.mobSubMenu[key]
                            }
                            else
                                vm.mobSubMenu[key] = false;
                        });
                    }

                    vm.logoutWK = function() {
                        loginService.logoutUser()
                    }

                }],
                link: function(scope, elm, $attrs) {
                    function getMenuData() {
                        $http.get(config.apiUrl + 'adminmenu')
                            .success(function(data) {
                                scope.menuList = data
                            })
                    };
                    scope.$watch(function() {
                        return scope.hideMenu;
                    }, function(newVal, oldVal) {
                        if (newVal && !scope.menuList && scope.userId) {
                            getMenuData();
                        }

                    }, true);
                }
            }
        }]);
})()

'use strict';
/**
 * @namespace Permission
 * @desc Check for user permissions on different modules
 * @memberOf Factories
 * @author Jagadeeshraj V S
 */
angular.module('app')
    .factory('permissionService', ['$rootScope', '$q', '$http', '$localStorage', '$log', '$urlRouter', '$state', 'config', function($rootScope, $q, $http, $localStorage, $log, $urlRouter, $state, config) {
        var obj = {};

        obj.checkModulePermission = function(stateEvent, stateDetail, stateParams) {
                //These states/modules will be exempted from permission check

                var exceptionStates = ["404", "403", "dashboard", "itemtype", "home", "myprofile", "login", "forgotpassword", "resetpassword"];


                //Check for states/modules for permission exemption
                if (exceptionStates.indexOf(stateDetail.name.split(".")[0]) === -1) {
                    $log.debug("Module Permission check begins")
                    var actionList = { preview: "view", view: "view", publish: "manageSecurity", list: "view", create: "create", edit: "edit", delete: "delete", security: "manage_security", association: "manageAssociation",studentusage:"view", clientreport:"view", metadatareport:"view", userquizzingreport:"view", itemreport:"view", upload:"create", status:"manageAssociation"} //action name mapping with db data
                    //var moduleList = { metadata: "meta_tag","items":"question" } //module name mapping with db data
                    var moduleName = stateDetail.name.split(".")[0];
                    var actionName = stateDetail.name.split(".")[1];
                    $log.debug("Module:" + moduleName + " & Action:" + actionName)

                    //Check Permission Details in rootScope
                    if ($rootScope.permission && $rootScope.permission[moduleName]) {
                        $log.debug("Module Permission present in rootScope")
                        if ($rootScope.permission[moduleName].indexOf(actionList[actionName]) === -1) { //Check for particular action access
                            permissionError(stateEvent, moduleName, actionName)
                        } else {
                            $log.debug("Module Permission Success")
                            return; //User has permission for current routing state and action
                        }

                    } else if ($localStorage.permission && $localStorage.permission[moduleName]) { //Check Permission Details in Local Storage
                        $log.debug("Module Permission present in rootScope")
                        $rootScope.permission = $rootScope.permission || {}
                        $rootScope.permission[moduleName] = $localStorage.permission[moduleName] //Set the permission in rootScope
                        if ($rootScope.permission[moduleName].indexOf(actionList[actionName]) === -1) { //Check for particular action access
                            permissionError(stateEvent, moduleName, actionName)
                        } else {
                            $log.debug("Module Permission Success")
                            return; //User has permission for current routing state and action
                        }
                    } else { //Get from server
                        $log.debug("Module Permission not in rootscope/localStorage, so getting from server")
                        stateEvent.preventDefault(); //Prevent routing to check for permission
                        getPermissionFromServer(moduleName).then(function(response) {
                            var accessPermission = response.data;
                            if (response.status == 401) { //Authentication error
                                $state.go('login')
                            } else if (angular.isArray(accessPermission)) {
                                $rootScope.permission = $rootScope.permission || {}
                                $localStorage.permission = $localStorage.permission || {}
                                $rootScope.permission[moduleName] = $localStorage.permission[moduleName] = accessPermission //Set the permission in rootScope

                                if (accessPermission.indexOf(actionList[actionName]) === -1) {
                                    permissionError(stateEvent, moduleName, actionName)
                                } else {
                                    $log.debug("Re-route")
                                        //$urlRouter.sync(); //Reload/reprocess current route 
                                    $state.go(stateDetail.name, stateParams)
                                }
                            } else
                                permissionError(stateEvent, moduleName, actionName)
                        });

                    } //end of get permission details from server
                } //end if for state excemption check
            }
            //Get permission details from server based on module & userid
        function getPermissionFromServer(moduleName) {
            return $http.get(config.apiUrl + 'getpermissions', { params: { userId: $rootScope.userId, resource: moduleName } })
                .success(function(response) {
                    console.log(response)
                    return response;
                })
                .error(function(response, status) {
                    console.log(response)

                    $log.error(response, status) //Log custom errors
                    return response;
                })
                .catch(function(response) {
                    $log.debug("Permission Error ", response) //Log custom errors
                    return response;
                });
        }

        //Handle permission errors 
        function permissionError(stateEvent, moduleName, actionName) {
            stateEvent.preventDefault(); //Prevent current routing 
            $log.info("No Access Permission for " + "Module:" + moduleName + " & Action:" + actionName) //log error info
            $state.go('403') //re-route to error page
        }

        return obj;
    }]);

'use strict';

angular.module('app')
    .service('testService', ['$q', '$location', '$localStorage','itemsService', function($q, $location, $localStorage, itemsService) {
    
    return this;
    }]);
