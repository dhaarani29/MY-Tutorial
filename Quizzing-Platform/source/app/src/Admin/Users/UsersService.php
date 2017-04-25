<?php

/*
 * UsersService - Users module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Users;

use Silex\Application;

class UsersService {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * Create User Details and send mail to that user
     * @param type $userData
     * @return type
     */
    public function createUser($userData, $accessToken = '', $requestFrom = '') {

        $firstName = $userData['firstName']; //User firstName
        $lastName = $userData['lastName']; //User lastName
        $toEmail = $userData['userEmail']; //User emailAddress
        //call the create method in repository

        $response = $this->app['users.repository']->create($userData, $accessToken, $requestFrom);
        //If the user details is created successfully , send mail to new user.
        if (is_numeric($response)) {

            $subject = "Account Created on WK Quizzing Platform";
            $messageBody = "<html><body><table> 
                                <tr><td> Dear $firstName $lastName, </td></tr>
                                <tr><td></td></tr>
                                <tr><td>  Your profile has been created on WK Quizzing Platform. </td></tr>
                                <tr><td></td></tr>
                                <tr></tr>
                                <tr><td>  Thank you, </td></tr>
                                <tr><td>  WK Quizzing Platform Team </td></tr>
                                </html></body></table>";

            // Send email to the user
            $this->app['login.service']->sendMail($toEmail, $subject, $messageBody);
        }

        return $response;
    }

    /**
     * Delete the user details and send mail to user
     * @param type $userId
     * @return type
     */
    public function deleteUser($userId) {

        //Get the user Details
        $userValue = $this->app['users.repository']->find($userId);

        //Delete the user
        $response = $this->app['users.repository']->delete($userId);

        $firstName = $userValue['firstName']; //FirstName
        $lastName = $userValue['lastName']; //LastName
        $toEmail = $userValue['emailAddress']; //email address
        //If Deleted Successfully , send mail to deleted user
        if ($response === true) {

            $subject = "Account Deleted on WK Quizzing Platform";
            $messageBody = "<html><body><table> 
                                <tr><td> Dear $firstName $lastName, </td></tr>
                                <tr><td></td></tr>
                                <tr><td>  Your profile has been deleted on WK Quizzing Platform. </td></tr>
                                <tr><td></td></tr>
                                <tr></tr>
                                <tr><td>  Thank you, </td></tr>
                                <tr><td>  WK Quizzing Platform Team </td></tr>
                                </html></body></table>";

            // Send email to the user
            $this->app['login.service']->sendMail($toEmail, $subject, $messageBody);
        }
        return $response;
    }

    /**
     * Check resource action permission
     * @param type $userId
     * @param type $resource
     * @param type $action
     * @return type
     */
    public function checkResourceActionPermission($userId, $resource = NULL, $action = NULL) {
        $permissions = $this->app['users.repository']->checkResourceActionPermission($userId, $resource, $action);
        return $permissions;
    }

    /**
     * Get user details by userid
     * @param type $userId
     * @return type
     */
    public function find($userId = NULL) {
        $userDataArray = $this->app['users.repository']->find($userId);
        return $userDataArray;
    }

    /**
     * Get user list
     * @param type $getUserdata
     * @return type
     */
    public function getUsersList($getUserdata = array()) {
        $userDataArray = $this->app['users.repository']->getUsersList($getUserdata);
        return $userDataArray;
    }

    /**
     * Update the user details
     * @param type $userValue
     * @param type $updateUser
     * @param type $accessToken
     * @param type $requestFrom
     * @return type
     */
    public function update($userValue, $updateUser, $accessToken = NULL, $requestFrom = NULL) {
        $updated = $this->app['users.repository']->update($userValue, $updateUser, $accessToken, $requestFrom);
        return $updated;
    }

    /**
     * Get user association list
     * @param type $getUserdata
     * @param type $userId
     * @return type
     */
    public function getUsersAssociationList($getUserdata = array(), $userId = NULL) {
        $userDataArray = $this->app['users.repository']->getUsersAssociationList($getUserdata, $userId);
        return $userDataArray;
    }

    /**
     * Associate user
     * @param type $userValue
     * @param type $updateUser
     * @return type
     */
    public function associateUser($userValue, $updateUser) {
        $updated = $this->app['users.repository']->associateUser($userValue, $updateUser);
        return $updated;
    }

}
