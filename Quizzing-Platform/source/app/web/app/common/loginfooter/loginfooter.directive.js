'use strict';

angular.module('app')
    .directive('quizPlatformLoginFooter', function() {
        return {
            templateUrl: 'app/common/loginfooter/loginfooter.html',
            restrict: 'E',
            replace: true,
        }
    });
