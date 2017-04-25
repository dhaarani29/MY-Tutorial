'use strict';

angular.module('app')
    .directive('quizPlatformHeader', function() {
        return {
            templateUrl: 'app/common/header/header.html',
            restrict: 'E',
            replace: true
        }
    });
