

app.controller("loginController", function ($scope, $http, $timeout, $window) {

    $scope.fileUpload = function (file) {
        console.log('file uplodade' + file);
        return false;
    }
    $scope.login = function (user) {

        $http({
            method: 'POST',
            url: 'app/backend/login.php',
            data: user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if (response.data.statusCode == '200') {
                $scope.msg = response.data.msg;
                $timeout(function () {
                    $window.location = "http://localhost/dhaarani/#/home";
                }, 2000);

            } else {
                $scope.msg = "invalid credientials";
            }


        });
    }

    $scope.register = function (user) {
        console.log(user);


        $http({
            method: 'POST',
            url: 'app/backend/signup.php',
            data: user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {
            console.log(response);
            $scope.msg = response.data.msg;


        });
    }

});