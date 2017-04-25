<?php

/*
 * LoginService - Service file to support login module and which implements UserProviderInterface methods to valid the user.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Login;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Silex\Application;

class LoginService implements UserProviderInterface {

    protected $app;
    protected $em;

    public function __construct(Application $app) {
        $this->app = $app;
        $this->em = $app['orm.em'];
    }

    /**
     * @Desc Generate access token for admin users.
     * @param type $userName
     * @param type $rawPassword
     * @return type
     * @throws UsernameNotFoundException
     */
    public function generateAdminAccessToken($userName, $rawPassword) {

        $password = $this->app['users.repository']->encodePassword($rawPassword);

        // If username and password are passed, then get the userdetails for the specified username.
        $user = $this->app['users']->loadUserByUsername($userName);

        //If username is valid continue
        if ($user) {

            // Check stored password and entered password are same.
            if (!$this->app['security.encoder.digest']->isPasswordValid($user->getPassword(), $password, '')) {
                return false; // Return false if passwords are not matching
            } else {

                // If valid user, get user details based on username
                $userDetails = $this->app['users.repository']->getUserFewInfo($userName, NULL);
                $userId = $userDetails['userId'];

                //Get existing access token
                $exisitngAccessToken = $userDetails['accessToken'];

                //Generate and store the access token if required.
                $accessToken = self::generateAndStoreAccessToken($userId, $exisitngAccessToken);

                // If access token not yet expired then return valid token
                if ($accessToken != $this->app['cache']->fetch('expiredToken')) {

                    //Return access token and userid.
                    $returnUser = array("userId" => $userId, "accessToken" => $accessToken);

                    return $returnUser;
                } else {
                    // IF token expired then return expired token error
                    return $this->app['cache']->fetch('expiredToken');
                }
            }
        } else {
            return false;
        }
    }

    /**
     * @Desc Generate access token for end users based on the client_id and secrete code. Example : Silver chair
     * @param type clientCode
     * @param type secretKey
     * @return boolean
     */
    public function generateEndUserAccessToken($clientId, $userParams) {

        //if userparams passed, save or update clientuser details in org_user_table
        if (!empty($userParams)) {

            $userId = $this->app['users.repository']->createEndUser($clientId, $userParams);

            // If valid user, get user details based on userid
            $userDetails = $this->app['users.repository']->getUserFewInfo(NULL, $userId);

            //Get existing access token
            $exisitngAccessToken = $userDetails['accessToken'];

            //Generate and store end user access token if required
            $accessToken = self::generateAndStoreAccessToken($userId, $exisitngAccessToken);

            // If access token not yet expired then return valid token
            if ($accessToken != $this->app['cache']->fetch('expiredToken')) {

                //Return access token and userid.
                $returnUser = array("userId" => $userId, "accessToken" => $accessToken);

                return $returnUser;
            } else {
                // IF token expired then return expired token error
                return $this->app['cache']->fetch('expiredToken');
            }
        } else {
            return false;
        }
    }

    /**
     * @Desc Generate the access token from the user data
     * @param type $userData
     * @return string
     */
    public function generateAccessToken($userData) {

        // After successful user validation generate the access token
        // pass complete user details to the JWT token.
        $jwtaccessToken = $this->app['security.jwt.encoder']->encode($userData);

        //add token prefix "Bearer" to the jwt access token adn then store it
        $accessToken = $this->app['cache']->fetch('tokenPrefix') . ' ' . $jwtaccessToken;

        return $accessToken;
    }

    /**
     * @Desc Generate and store end user access token
     * @param type $userId
     * @param type $accessToken
     * @return type
     */
    public function generateAndStoreAccessToken($userId, $accessToken) {

        //Get existing access token
        $exisitngAccessToken = $accessToken;

        //If access token exists continue
        if ($exisitngAccessToken) {

            // Check stored access token expired or not.
            if (!self::checkTokenExpired($exisitngAccessToken)) {

                // If access token is null then create the new access token or regenerate the new token and update
                $userData = $this->app['users.repository']->getUserInfoForToken($userId);

                //generate the new access token with user details
                $accessToken = self::generateAccessToken($userData);

                // Store the access token for the specified username
                $user = $this->app['users.repository']->storeAccessToken($userId, $accessToken);
            } else {
                //If token not expired, then consider the existing token
                $accessToken = $exisitngAccessToken;
            }
        } else {

            // If access token is null then create the new access token
            $userData = $this->app['users.repository']->getUserInfoForToken($userId);

            //generate the new access token with user details
            $accessToken = self::generateAccessToken($userData);

            // Store the access token for the specified username
            $user = $this->app['users.repository']->storeAccessToken($userId, $accessToken);
        }

        return $accessToken;
    }

    /**
     * @Desc check wether token is expired, if its expired reset the token to null. When user logins in freshly, it will store with fresh token.
     * @param type $exisitngAccessToken
     * @return boolean
     */
    public function checkTokenExpired($exisitngAccessToken) {
        try {
            $accessToken = $exisitngAccessToken;

            // Token will have token prefix seperated by space, hence take only the token without the prefix.
            $accessTokenArray = explode(' ', $exisitngAccessToken);

            //Decode the access token and get the expiration time.
            $data = $this->app['security.jwt.encoder']->decode($accessTokenArray[1]);

            //If token is expired then will recive "Token expired" string from the decode. Compare this with config value to check wether token expired.
            if ($data == $this->app['cache']->fetch('expiredToken')) {
                // If access token is expired, set the access token to null, when they login back it will set with fresh new token
                $this->app['users.repository']->setNullAccessToken($exisitngAccessToken);

                return $this->app['cache']->fetch('expiredToken');
            } else {
                return true;
            }
        } catch (\Firebase\JWT\SignatureInvalidException $e) {//If signature failed
            return false;
        }
    }

    /**
     * @Desc Validate the client for given cleint id and secrete key
     * @param type $clientCode
     * @param type $secretKey
     * @return boolean
     */
    public function validateClient($clientCode, $secretKey) {

        // If clientid and secrete codes are passed, then get the userdetails for the specified client id.
        $user = $this->app['users']->loadUserByClientCode($clientCode);

        //If clientId is valid continue
        if ($user) {

            // Check stored secretcode and passed secret keys are matching.
            if (!$this->app['security.encoder.digest']->isPasswordValid($user->getPassword(), $secretKey, '')) {
                return false; // Return false if secret keys are not matching
            } else {
                $clientDetails = $this->app['users.repository']->getUserByClientCode($clientCode);
                return $clientDetails['clientId'];
            }
        } else {
            return false;
        }
    }

    /**
     * @Desc This is interface method to create USer object by validating the user details.
     * @param type $userName
     * @return User
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($userName) {

        $user = $this->app['users.repository']->getUserFewInfo($userName);

        if (empty($user)) {
            return false;
        }
        return new User($user['userName'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    }

    /**
     * @Desc Create user object for end user using thier clientid and secrete code.
     * @param type clientCode
     * @return User|boolean
     */
    public function loadUserByClientCode($clientCode) {

        $user = $this->app['users.repository']->getUserByClientCode($clientCode);

        if (empty($user)) {
            return false;
        }
        return new User($user['clientCode'], $user['secretKey'], explode(',', $user['roles']), true, true, true, true);
    }

    /**
     * 
     * @param UserInterface $user
     * @return type
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user) {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * 
     * @param type $class
     * @return type
     */
    public function supportsClass($class) {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }

    /**
     * @Desc Either user can reset the password or request to send thier username
     * @param type $emailAddress
     * @param type $action will be resetpassword/sendusername
     * @return type
     */
    public function forgotPassword($emailAddress, $action) {

        // CHeck wether email exists for the request email address.
        $userExists = $this->app['users.repository']->checkUserAlreadyExists(NULL, $emailAddress, NULL);

        if (empty($userExists)) {
            // Return NOTEXISTS if user doesn't exists.
            return $this->app['cache']->fetch('notexists');
        } else {

            // Get user information to sent the email
            $firstName = $userExists[0]['firstName'];
            $lastName = $userExists[0]['firstName'];
            $userName = $userExists[0]['userName'];
            $userId = $userExists[0]['userId'];
            $toEmail = $emailAddress;

            // If action is resetpassword then generate the reset token and send to user.
            if ($action == "resetpassword") {

                // Generate password reset token
                $resetToken = self::generatePasswordResetToken();

                // Store the resetToken againt to the user in database.
                $saveResetToken = $this->app['users.repository']->storePasswordResetToken($userId, $resetToken);

                // If reset token saved along with recovery expiration time then continue to send the mail
                if ($saveResetToken) {

                    // Form the url
                    $url = $this->app['cache']->fetch('host') . 'resetpassword/' . $resetToken;
                    $resetLink = "<a href='$url'> here </a> ";

                    // Create subject and Message body
                    $subject = "Reset WK Quizzing Platform password";
                    $messageBody = "<html><body><table> 
                                <tr><td>Dear $firstName $lastName, </td></tr>
                                <tr><td></td></tr>
                                <tr><td>We have received a <b>'reset password'</b> request. Please click $resetLink to reset your WK Quizzing Platform password. </td></tr>
                                <tr><td></td></tr>
                                <tr><td>Thank you, </td></tr>
                                <tr><td>The WK Quizzing Platform Team </td></tr>
                                </html></body></table>";
                }
            } else if ($action == "sendusername") {

                // If user requests to send thier username then send it via email 
                // Create subject and Message body
                $subject = "Request for WK Quizzing Platform username";
                $messageBody = "<html><body><table> 
                                <tr><td>Dear $firstName $lastName,</td></tr>
                                <tr><td></td></tr>
                                <tr><td>We have received a <b>'send username'</b> request. <b> $userName </b> is your WK Quizzing Platform username. </td></tr>
                                <tr><td></td></tr>
                                <tr><td>Thank you, </td></tr>
                                <tr><td>The WK Quizzing Platform Team  </td></tr>
                                </html></body></table>";
            }

            // Send email to the user
            if (self::sendMail($toEmail, $subject, $messageBody)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @Desc Common function to send the email
     * @param type $toEmail
     * @param type $subject
     * @param type $messageBody
     * @return boolean
     */
    public function sendMail($toEmail, $subject, $messageBody) {

        //$fromEmail = 'Root User<supportteam@wk.com>';

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array($this->app['cache']->fetch('emailDomain') => 'Quizzing Platform Administrator'))
                ->setTo(array($toEmail))
                ->setBody($messageBody)
                ->setContentType("text/html");

        $transport = \Swift_SmtpTransport::newInstance('localhost', 25);

        $mailer = \Swift_Mailer::newInstance($transport);
        if ($mailer->send($message)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $min
     * @param type $max
     * @return type
     */
    private function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0)
            return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    /**
     * @Desc Generate random reset password token
     * @param type $length
     * @return string
     */
    private function generatePasswordResetToken($length = 32) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure(0, strlen($codeAlphabet))];
        }
        return $token;
    }

    /**
     * validate reset password
     * @param type $resetToken
     * @return type
     */
    public function validateResetPassword($resetToken) {

        $validResetToken = $this->app['users.repository']->validateResetPassword($resetToken);

        return $validResetToken;
    }

    /**
     * @Desc reset password
     * @param type $resetToken
     * @param type $password
     * @return type
     */
    public function resetPassword($resetToken, $password) {

        $resetPassword = $this->app['users.repository']->resetPassword($resetToken, $password);

        return $resetPassword;
    }

    /**
     * @Desc Decode access token and return the user details array
     * @param type $accessToken
     * @return type
     */
    public function decodeAccessToken($accessToken) {

        // Token will have token prefix seperated by space, hence take only the token without the prefix.
        $accessTokenArray = explode(' ', $accessToken);

        //Decode the access token and get the expiration time.
        $data = $this->app['security.jwt.encoder']->decode($accessTokenArray[1]);
        $userDetails = json_decode(json_encode($data), True);

        return $userDetails;
    }

    /**
     * Get the clientId
     * @param type $exisitngAccessToken
     * @return boolean
     */
    public function getClientId($accessToken) {

        $userDetails = self::decodeAccessToken($accessToken);
        $clientId = $userDetails['clientId'];

        if (isset($clientId)) {

            return $clientId;
        } else {

            return false;
        }
    }

    /**
     * @Desc : get valid userid based on accesstoken and cross check with user input userId
     * @param type $accessToken
     * @return type
     */
    function getUserIdFromToken($accessToken, $clientUserId = NULL) {

        $userDetails = self::decodeAccessToken($accessToken);
        $decodedUserId = $userDetails['userId'];

        if ($clientUserId) {

            $clientId = self::getClientId($accessToken);
            $userId = $this->app['users.repository']->getUserIdofClientUserId($clientUserId, $clientId);

            if ($decodedUserId == $userId) {

                return $decodedUserId;
            } else {
                return false;
            }
        } else {
            return $decodedUserId;
        }
    }

    /**
     * @Desc : check whether the token signature is valid or not
     * @param string $accessToken
     * @return boolean 
     */
    function verifyTokenSignature($accessToken) {

        $userDetails = self::decodeAccessToken($accessToken);
        $decodedUserId = $userDetails['userId'];

        if ($clientUserId) {

            $clientId = self::getClientId($accessToken);
            $userId = $this->app['users.repository']->getUserIdofClientUserId($clientUserId, $clientId);

            if ($decodedUserId == $userId) {

                return $decodedUserId;
            } else {
                return false;
            }
        } else {
            return $decodedUserId;
        }
    }

}
