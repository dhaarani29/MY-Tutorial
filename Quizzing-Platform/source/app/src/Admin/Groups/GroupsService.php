<?php

/*
 * GroupsService - Handles groups module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Groups;

use Silex\Application;

class GroupsService {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * To Get all the groups
     * @param type $groupRequest
     * @param type $associatedGroups
     * @param type $associatedUserId
     * @return type
     */
    public function getGroups($groupRequest, $associatedGroups = NULL, $associatedUserId = NULL) {

        $groupValueArray = $this->app['groups.repository']->getGroups($groupRequest, $associatedGroups, $associatedUserId);
        return $groupValueArray;
    }

    /**
     * Get the group details By groupId
     * @param type $groupId
     * @param type $groupData
     * @return type
     */
    public function getGroupByid($groupId = NULL, $groupData = NULL) {
        $groupDataArray = $this->app['groups.repository']->getGroupByid($groupId, $groupData);
        return $groupDataArray;
    }

    /**
     * Delete the group By groupid
     * @param type $groupId
     * @return type
     */
    public function delete($groupId) {
        $response = $this->app['groups.repository']->delete($groupId);
        return $response;
    }
    /**
     * To create the group
     * @param type $groupData
     * @return type
     */
    public function create($groupData = null) {
        $response = $this->app['groups.repository']->create($groupData);
        return $response;
    }
    /**
     * Get item collection by itembankid
     * @param type $itemBankId
     * @return type
     */
    public function find($groupId) {
        $groupData = $this->app['groups.repository']->find($groupId);
        return $groupData;
    }
 /**
     * To update the group
     * @param type $groupData
     * @return type
     */
    public function update($groupData = null, $groupId) {
        $response = $this->app['groups.repository']->update($groupData, $groupId);
        return $response;
    }
}
