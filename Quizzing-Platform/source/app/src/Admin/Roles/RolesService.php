<?php

/*
 * RolesService - Handles Roles module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Roles;

use Silex\Application;

class RolesService {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * To Get all the roles
     * @param type $roleRequest
     * @param type $associatedRoles
     * @param type $associatedUserId
     * @return type
     */
    public function getRoles($roleRequest, $associatedRoles = NULL, $associatedUserId = NULL) {
        $roleValueArray = $this->app['roles.repository']->getRoles($roleRequest, $associatedRoles, $associatedUserId);
        return $roleValueArray;
    }

    /**
     * To find the role details based on the roleid
     * @param type $roleId
     * @return type
     */
    public function find($roleId) {
        $roleDataArray = $this->app['roles.repository']->find($roleId);
        return $roleDataArray;
    }

    /**
     * To Delete the role by roleid
     * @param type $roleId
     * @return type
     */
    public function delete($roleId) {
        $response = $this->app['roles.repository']->delete($roleId);
        return $response;
    }
/**
     * To create the role
     * @param type $roleData
     * @return type
     */
    public function create($roleData = null) {
        $response = $this->app['roles.repository']->create($roleData);
        return $response;
    }
     /**
     * To update the role
     * @param type $roleData
     * @return type
     */
    public function update($roleData = null, $roleId) {
        $response = $this->app['roles.repository']->update($roleData, $roleId);
        return $response;
    }
}
