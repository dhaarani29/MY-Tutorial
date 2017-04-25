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
