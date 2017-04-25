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
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

/*
 * *** Custom Error Codes: ***
 * 8001:Invalid Credentials
 * 8002:Access Denied
 * 8003:User doens't exists
 * 8004:Failed to send mail
 * 8005:Invalid reset token
 * 8006:Either reset token is invalid or its expired
 * 8007:Failed to reset the password
 * 
 */

class LoginController {

    protected $app;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "login";

        $this->success = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->badrequest = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->notFound = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->unAuthorized = $this->app['cache']->fetch('HTTP_UNAUTHORIZED');
    }

    /**
     * @Desc Validates the user details and for valid user generate the access token and then stores the token to the particular user.
     * @param Request $request
     * @return type json data with access token details
     * @throws UsernameNotFoundException
     */
    public function login(Request $request) {

        if (!empty($request->getContent())) {

            $loginData = json_decode($request->getContent(), true);

            // Get username and password from post request and then use md5 converted password
            $userName = trim($loginData['userName']);
            $password = $this->app['users.repository']->encodePassword($loginData['password']);

            try {

                // If any of the input is empty then return invlaid credentials.
                if (empty($userName) || empty($password)) {
                    throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['username']));
                }

                // If username and password are passed, then get the userdetails for the specified username.
                $user = $this->app['users']->loadUserByUsername($userName); 

                
                // Check stored password and entered password are same.
                if (!$this->app['security.encoder.digest']->isPasswordValid($user->getPassword(), $password, '')) {
                    throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['username']));
                } else {

                    // After successful username and password validation generate the access token
                    $jwtaccessToken = $this->app['security.jwt.encoder']->encode(['name' => $user->getUsername()]); 
                    
                    //add token prefix "Bearer" to the jwt access token adn then store it
                    $accessToken = $this->app['cache']->fetch('tokenPrefix').' '.$jwtaccessToken;   
                    
                    $userDetails = $this->app['users.repository']->getUserByUsername($userName);
                    $userId = $userDetails['userId'];

                    // Store the access token for the specified username
                    $user = $this->app['users.repository']->storeAccessToken($userId, $accessToken);

                    //After successful user validation and database storing, return access token
                    $response = $this->app['systemsettings.controller']->returnAccessToken($userId,$accessToken, $this->success);
                    return $response;
                }
            } catch (UsernameNotFoundException $e) {

                // If invalid login credentials are passed then return the bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse(8001, "Invalid credentials", "Invalid Username or Password", $this->unAuthorized);
                return $response;
            }
        }
    }

    /**
     * @Desc Return access denied for invalid token.
     * @return type
     */
    public function inValidToken() {

        // If invalid login credentials are passed then return the bad request error.
        $response = $this->app['systemsettings.controller']->returnErrorResponse(8002, "Access Denied", "Invalid Access token, Please retry login", $this->unAuthorized);
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
                $response = $this->app['systemsettings.controller']->returnErrorResponse(8003, "User doens't exists", "Email address not found", $this->badrequest);
                return $response;
            } else if ($user) {
                //After successful email sent to user for either reset password or send username return success message.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($user, $this->success);
                return $response;
            } else {
                // If request email address doesn't exists then through bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse(8004, "Failed to send mail", "Failed to send email to the requested email address", $this->notFound);
                return $response;
            }
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
                    $response = $this->app['systemsettings.controller']->returnErrorResponse(8006, "Either reset token is invalid or its expired", "Either reset token is invalid or its expired.", $this->unAuthorized);
                    return $response;
                }
            } else {
                // If request reset token is empty then through bad request error.
                $response = $this->app['systemsettings.controller']->returnErrorResponse(8005, "Invalid reset token", "Invalid reset token", $this->unAuthorized);
                return $response;
            }
        } else {
            // If request reset token is empty then through bad request error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse(8005, "Invalid reset token", "Invalid reset token", $this->badrequest);
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
                $response = $this->app['systemsettings.controller']->returnErrorResponse(8007, "Failed to reset the password", "Failed to reset the password", $this->notFound);
                return $response;
            }
        }
    }

}
