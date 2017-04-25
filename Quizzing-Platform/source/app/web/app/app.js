(function() {
    'use strict';

    angular.module('app', [
        'ui.router',
        'smart-table',
        'ngMessages',
        'flow',
        // 'ngCookies',
        'pascalprecht.translate',
        'ui.bootstrap',
        'ngStorage',
        'ncy-angular-breadcrumb',
        //'ngTouch',
        'app.dashboard',
        'app.metadata',
        'app.items',
        'app.user',
        'app.itembanks',
        'app.role',
        'app.group',
        'app.tests',
        'angular-jwt',
        'app.systemsettings',
        'app.reports'
    ])


})();
