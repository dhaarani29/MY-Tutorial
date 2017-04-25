<?php

/**
 * RolesControllerProvider - Handles the Roles module routing. All the Routings along with Http methods defined here.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Roles;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class RolesControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        
        $controllers = $app['controllers_factory'];
        
          //Listing All the Roles
        $controllers->get('/api/roles', 'roles.controller:getAllRoles');
        
        // Get role by id
        $controllers->get('/api/roles/{id}', 'roles.controller:getRolesById');
        
        // Get role by id
        $controllers->get('/api/roles/create', 'roles.controller:getRolesById');
        
        
        // Delete role by id
        $controllers->delete('/api/roles/{id}', 'roles.controller:deleteRoleById');
        
        //Create the role
        $controllers->post('/api/roles', 'roles.controller:createRole');
        
        //Edit the role
        $controllers->put('/api/roles/{id}', 'roles.controller:updateRole');
        
        return $controllers;
    }

}