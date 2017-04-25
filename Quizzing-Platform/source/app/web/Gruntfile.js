/*
 *  Gruntfile.js
 *  Used for deployement tasks(annotate,concat and uglify) the admin and eup app code for WK Quizzing platform
 *  Created By :Jagadeeshraj V S
 *  Created On :07-Mar-2017 
 */
module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        ngAnnotate: {
            options: {
                singleQuotes: true
            },
            adminapp: {
                files: {
                    './dist/min-safe/adminjs/app.route.js': ['./app/app.route.js'],
                    './dist/min-safe/adminjs/app.config.js': ['./app/app.config.js'],
                    './dist/min-safe/adminjs/app.common.js': ['./app/common/**/*.js', '!./app/common/**/euphttpinterceptor.service.js'],
                    './dist/min-safe/adminjs/app.modules.js': ['./app/modules/**/*module.js', './app/modules/**/*service.js', './app/modules/**/*controller.js', './app/modules/**/*directive.js', '!./app/modules/eup/*.js'],
                }
            },
            eupapp: {
                files: {
                    './dist/min-safe/eupjs/eupapp.route.js': ['./app/eupapp.route.js'],
                    './dist/min-safe/eupjs/eupapp.config.js': ['./app/eupapp.config.js'],
                    './dist/min-safe/eupjs/app.common.js': ['./app/common/euphttpinterceptor.service.js'],
                    './dist/min-safe/eupjs/app.modules.js': ['./app/modules/eup/*module.js', './app/modules/eup/*service.js', './app/modules/eup/*controller.js', './app/modules/eup/*directive.js'],
                }
            }
        },
        concat: {
            options: {
                separator: ';'
            },
            adminapp: {
                src: ['app/lib/angular/angular.min.js',
                    'app/lib/angular-ui-router/release/angular-ui-router.min.js',
                    'app/lib/angular-smart-table/dist/smart-table.min.js',
                    'app/lib/angular-messages/angular-messages.min.js',
                    'app/lib/angular-cookies/angular-cookies.min.js',
                    'app/lib/angular-translate/angular-translate.min.js',
                    'app/lib/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
                    'app/lib/angular-translate-loader-partial/angular-translate-loader-partial.min.js',
                    'app/lib/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js',
                    'app/lib/ngstorage/ngStorage.min.js',
                    'app/lib/angular-bootstrap/ui-bootstrap.min.js',
                    'app/lib/angular-bootstrap/ui-bootstrap-tpls.min.js',
                    'app/lib/angular-breadcrumb/dist/angular-breadcrumb.min.js',
                    'app/lib/ng-file-upload/ng-file-upload.min.js',
                    'app/lib/angular-jwt/dist/angular-jwt.min.js',
                    'app/lib/ng-flow/dist/ng-flow-standalone.min.js',
                    // 'app/env.js',
                    'app/app.js',
                    'dist/min-safe/adminjs/*.js'
                ],
                dest: './dist/adminapp.js'
            },
            eupapp: {
                src: ['app/lib/angular/angular.min.js',
                    'app/lib/angular-ui-router/release/angular-ui-router.min.js',
                    'app/lib/angular-smart-table/dist/smart-table.min.js',
                    'app/lib/angular-messages/angular-messages.min.js',
                    'app/lib/angular-cookies/angular-cookies.min.js',
                    'app/lib/angular-translate/angular-translate.min.js',
                    'app/lib/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
                    'app/lib/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js',
                    'app/lib/ngstorage/ngStorage.min.js',
                    'app/lib/angular-bootstrap/ui-bootstrap.min.js',
                    'app/lib/angular-bootstrap/ui-bootstrap-tpls.min.js',
                    'app/lib/angular-breadcrumb/dist/angular-breadcrumb.min.js',
                    'app/lib/ng-file-upload/ng-file-upload.min.js',
                    'app/lib/angular-jwt/dist/angular-jwt.min.js',
                    'app/lib/angular-google-chart/ng-google-chart.js',
                    // 'app/env.js',
                    'app/eupapp.js',
                    'dist/min-safe/eupjs/*.js'
                ],
                dest: './dist/eupapp.js'
            }
        },
        uglify: {
            options: {
                banner: '/*! WK Quizzing Platform \n Bulid On:<%= grunt.template.today("dd-mm-yyyy") %> */\n',
                mangle: false
            },
            adminapp: {
                src: ['./dist/adminapp.js'],
                dest: './dist/adminapp.min.js'
            },
            eupapp: {
                src: ['./dist/eupapp.js'],
                dest: './dist/eupapp.min.js'
            }
        },
        jshint: {
            options: {
                curly: true,
                eqeqeq: true,
                eqnull: true,
                browser: true,
                globals: {
                    jQuery: true
                },
                reporter: require('jshint-stylish')
            },
            uses_defaults: ['app/modules/**/*.js']
        }
    });

    //load grunt tasks
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-ng-annotate');
    grunt.loadNpmTasks('grunt-contrib-jshint');

    //register grunt default task
    grunt.registerTask('default', ['ngAnnotate', 'concat', 'uglify']);

};
