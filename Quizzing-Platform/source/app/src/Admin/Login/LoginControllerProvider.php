<?php

/**
 * LoginControllerProvider - Authenticate both admin and End users
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Login;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LoginControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        // Verify access token 
        $this->setUpMiddlewares($app);

        $controllers = $app['controllers_factory'];

        // Admin Login router.
        $controllers->post('/api/login', 'login.controller:login');

        // End user Aunthenticate router.
        $controllers->post('/api/authenticate', 'login.controller:authenticate');

        // Admin forgot password router.
        $controllers->post('/api/forgotpassword', 'login.controller:forgotPassword');

        // Admin validate reset password router.
        $controllers->post('/api/validateresetpassword', 'login.controller:validateResetPassword');

        // Admin reset password router.
        $controllers->post('/api/resetpassword', 'login.controller:resetPassword');

        return $controllers;
    }

    /**
     * @Desc Execute before all the routing module starts and checkes wether access token required, if required then validates the access token.
     * @param Application $app
     */
    private function setUpMiddlewares(Application $app) {

        $app->before(function (Request $request) use ($app) {

            if (!$this->isAuthRequiredForPath($app, $request->getPathInfo(), $request->getMethod())) {
                if (!$this->isValidTokenForApplication($app, $this->getTokenFromRequest($app, $request), $this->getRequestFrom($app, $request))) {

                    return $app['login.controller']->inValidToken();
                }
            }
        });
    }

    /**
     * 
     * @param Application $app
     * @param type $path
     * @return boolean
     */
    private function isAuthRequiredForPath(Application $app, $path, $method) {

        $excludePathsForTokenCheck = array("/api/login", "/api/forgotpassword",
            "/api/validateresetpassword", "/api/resetpassword",
            "/api/systemconfig", "/api/clearcache",
            "/api/authenticate", "/api/silverchairtaxonomy",
            "/api/reports/excelexport", "/api/reports/pdfexport", "/api/crons/upload-items",
            "/api/crons/index-metadata");

        if (in_array($path, $excludePathsForTokenCheck) || $method == 'OPTIONS') {//for cross doamin pre-flight request added exception for options method
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param Application $app
     * @param Request $request
     * @return type
     */
    private function getTokenFromRequest(Application $app, Request $request) {
        return $request->headers->get($app['cache']->fetch('tokenHeaderKey'));
    }

    /**
     * 
     * @param Application $app
     * @param Request $request
     * @return type
     */
    private function getRequestFrom(Application $app, Request $request) {

        return $request->headers->get($app['cache']->fetch('requestFrom'));
    }

    /**
     * 
     * @param Application $app
     * @param type $token
     * @return type
     */
    private function isValidTokenForApplication(Application $app, $token, $requestFrom) {

        if ($app['users.repository']->checkToken($token, $requestFrom)) {

            if ($app['login.service']->checkTokenExpired($token) === $app['cache']->fetch('expiredToken')) {
                return false;
            } else {

                return true;
            }
        } else {
            return false;
        }
    }

}
