<?php

/**
 * LoginController - Handles Login/Authentication.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Login;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnexpectedValueException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

/*
 * *** Custom Error Codes: ***
 * 8001:Access token expired.
 * 8002:Invalid credentials.
 * 8003:Input Credentials missing.
 * 8004:Invalid Client Details.
 * 8005:Invalid credentials.
 * 8006:Access Denied.
 * 8007:User doens't exists.
 * 8008:Failed to send mail.
 * 8009:Invalid Input details.
 * 8010:Either reset token is invalid or its expired.
 * 8011:Invalid reset token.
 * 8012:Failed to reset the password.
 * 
 */

class LoginController {

    protected $app;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "login";

        // HTTP Codes
        $this->success      = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->badrequest   = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->notFound     = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->unAuthorized = $this->app['cache']->fetch('HTTP_UNAUTHORIZED');
    }

    /**
     * @Desc Validates the admin user details and for valid user generate the access token and then stores the token to the particular user.
     * @param Request $request
     * @return type json data with access token details
     * @throws UsernameNotFoundException
     */
    public function login(Request $request) {

        if (!empty($request->getContent())) {

            $loginData = json_decode($request->getContent(), true);

            // Get username and password from post request and then use md5 converted password
            $userName = trim($loginData['userName']);
            $rawPassword = trim($loginData['password']);

            try {

                // If any of the input is empty then return invlaid credentials.
                if (empty($userName) || empty($rawPassword)) {
                    throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $userName));
                }

                $userDetails = $this->app['login.service']->generateAdminAccessToken($userName, $rawPassword);

                if ($userDetails === $this->app['cache']->fetch('expiredToken')) {

                    // If invalid login credentials are passed then return the bad request error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['TOKEN_EXPIRED_ERROR']);
                    return $response;
                } elseif (!empty($userDetails)) {

                    //After successful user validation and database storing, return access token
                    $response = $this->app['systemsettings.controller']->returnAccessToken($userDetails['userId'], $userDetails['accessToken'], $this->success);
                    return $response;
                } else {
                    // If invalid login credentials are passed then return the bad request error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_CREDENTAILS_ERROR']);
                    return $response;
                }
            } catch (UnexpectedValueException $e) {

                // If invalid login credentials are passed then return the bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_CREDENTAILS_ERROR']);
                return $response;
            }
        } else {
            // If invalid login credentials are passed then return the bad request error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['EMPTY_CREDENTIALS_ERROR']);
            return $response;
        }
    }

    public function authenticate(Request $request) {

        if (!empty($request->getContent())) {

            $authData = json_decode($request->getContent(), true);

            // Get clientCode and secretKey from post request to authenticate external system users.
            $clientCode = trim($authData['clientCode']);
            $secretKey = trim($authData['secretKey']);

            try {

                // If any of the input is empty then return invalid credentials.
                if (empty($clientCode) || empty($secretKey)) {
                    throw new UsernameNotFoundException(sprintf('Client "%s" does not exist.', $clientCode));
                }

                // Check wether client is valid before generating the end user access token
                $clientId = $this->app['login.service']->validateClient($clientCode, $secretKey);

                if ($clientId) {

                    $userDetails = $this->app['login.service']->generateEndUserAccessToken($clientId, $authData);

                    if ($userDetails === $this->app['cache']->fetch('expiredToken')) {

                        // If invalid login credentials are passed then return the bad request error.
                        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['TOKEN_EXPIRED_ERROR']);
                        return $response;
                    } elseif (!empty($userDetails)) {

                        //After successful user validation and database storing, return access token
                        $response = $this->app['systemsettings.controller']->returnAccessToken('', $userDetails['accessToken'], $this->success);
                        return $response;
                    }
                } else {
                    // If invalid client credentials are passed then return the bad request error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_CLIENT_DETAILS_ERROR']);
                    return $response;
                }
            } catch (UsernameNotFoundException $e) {

                // If invalid login credentials are passed then return the bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_CLIENT_CODE_ERROR']);
                return $response;
            }
        } else {
            // If invalid login credentials are passed then return the bad request error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['EMPTY_CREDENTIALS_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc Return access denied for invalid token.
     * @return type
     */
    public function inValidToken() {

        // If invalid login credentials are passed then return the bad request error.
        $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['ACCESS_DENIED_ERROR']);
        return $response;
    }

    /**
     * @desc check for reset password or send username, accordingly sent the mail to user.
     * @param Request $request
     * @return type
     */
    public function forgotPassword(Request $request) {

        if (!empty($request->getContent())) {

            $userData = json_decode($request->getContent(), true);

            $emailAddress = $userData['emailAddress'];
            $action = $userData['action'];

            $user = $this->app['login.service']->forgotPassword($emailAddress, $action);

            // If email doesn't exists then return bad input request
            if ($user === $this->app['cache']->fetch('notexists')) {

                // If request email address doesn't exists then through bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['USER_EMAIL_NOT_FOUND_ERROR']);
                return $response;
            } else if ($user) {
                //After successful email sent to user for either reset password or send username return success message.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($user, $this->success);
                return $response;
            } else {
                // If request email address doesn't exists then through bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['SEND_MAIL_FAILED_ERROR']);
                return $response;
            }
        } else {
            // If invalid login credentials are passed then return the bad request error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_INPUT_ERROR']);
            return $response;
        }
    }

    /**
     * @desc Validate the reset token which is sent to user and when user comes abck and try resetting the password.
     * @param Request $request
     * @return type
     */
    public function validateResetPassword(Request $request) {

        if (!empty($request->getContent())) {

            $userData = json_decode($request->getContent(), true);

            // Get reset token
            $resetToken = $userData['resetToken'];

            if ($resetToken != "") {

                // Call service to validate the reset token
                $validResetToken = $this->app['login.service']->validateResetPassword($resetToken);
                if ($validResetToken) {

                    //If reset token is valid and tried to reset within the expiry date then return success
                    $response = $this->app['systemsettings.controller']->returnSuccessResponse($validResetToken, $this->success);
                    return $response;
                } else {
                    // If request reset token doesn't exists or expired then through bad request error.
                    $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_EXPIRED_TOKEN_ERROR']);
                    return $response;
                }
            } else {
                // If request reset token is empty then through bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->unAuthorized, $this->app['INVALID_RESET_TOKEN_ERROR']);
                return $response;
            }
        } else {
            // If request reset token is empty then through bad request error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_RESET_TOKEN_ERROR']);
            return $response;
        }
    }

    /**
     * @Desc Reset the password based on reset token and password sent
     * @param Request $request
     * @return type
     */
    public function resetPassword(Request $request) {

        if (!empty($request->getContent())) {

            $userData = json_decode($request->getContent(), true);

            $resetToken = $userData['resetToken'];
            $password = $userData['password'];

            $resetPassword = $this->app['login.service']->resetPassword($resetToken, $password);

            if ($resetPassword) {

                //If password is set successfully then return success.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($resetPassword, $this->success);
                return $response;
            } else {
                // Failed to reset the password
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['PASSWORD_RESET_ERROR']);
                return $response;
            }
        } else {
            // If request reset token is empty then through bad request error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->badrequest, $this->app['INVALID_RESET_TOKEN_ERROR']);
            return $response;
        }
    }

}
