var app = angular.module("app", ["ngRoute"]);


app.config(function ($routeProvider) {
    $routeProvider
            .when("/login", {
                templateUrl: "app/modules/login/html/login.html",
                controller: "loginController"
            })
            .when("/signup", {
                templateUrl: "app/modules/login/html/signup.html",
                controller: "loginController"
            })
            .when("/home", {
                templateUrl: "app/modules/login/html/signup.html",
                controller: "loginController"
            });
});