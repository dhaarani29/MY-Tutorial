'use strict';


angular.module('app')
    .directive('quizPlatformFooter', function() {
        return {
            templateUrl: 'app/common/footer/footer.html',
            restrict: 'E',
            replace: true,
            controller: function($scope, $filter) {
                var currentDate = new Date();
                $scope.rights = {};
                $scope.rights.year = $filter('date')(currentDate, "yyyy");
            }
        }
    });
