'use strict';

angular.module('app')
    .directive('quizPlatformLoginHeader', function() {
        return {
            templateUrl: 'app/common/loginheader/loginheader.html',
            restrict: 'E',
            replace: true,
        }
    });
