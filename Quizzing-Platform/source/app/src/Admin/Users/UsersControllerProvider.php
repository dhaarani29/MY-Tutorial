<?php

/**
 * UsersControllerProvider - Handle the users/Groups/Roles module routing. All the Routings along with Http methods defined here.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Users;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class UsersControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        // Get resource permissions.
        $controllers->get('/api/getpermissions', 'users.controller:getResourcePermissions');

        // Create a User information
        $controllers->post('/api/user', 'users.controller:createUserDetails');

        // Get the User information
        $controllers->get('/api/user/{id}', 'users.controller:getUserById');

        // Get all User information
        $controllers->get('/api/user', 'users.controller:getAllUsers');

        //Soft Delete the User information based on the userId
        $controllers->delete('/api/user/{id}', 'users.controller:deleteUser');

        //Update User Information with put method.
        $controllers->put('/api/user/{id}', 'users.controller:updateUser');

        //role or Group association to the user
        $controllers->put('/api/associateuser/{id}', 'users.controller:associateUser');
        
        //Fetch user association (group/role) details
        $controllers->get('/api/userassociation/{id}', 'users.controller:userAssociation');

        return $controllers;
    }

}
