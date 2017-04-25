(function() {
    /**
     * @namespace Menu Directive
     * @desc Create the admin site menu 
     * @memberOf Directive
     * @author Jagadeeshraj V S
     */
    'use strict';

    angular.module('app')
        .directive('quizPlatformMenu', function($http, config) {
            return {
                templateUrl: 'app/common/menu/menu.html',
                restrict: 'E',
                replace: true,
                controllerAs: "vm",
                controller: function($rootScope, $state, $localStorage, $element, $attrs, $location, $log, config, loginService) {
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

                },
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
        });
})()
