<?php

/*
 * SystemsettingsService - Handles System settings module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */

namespace QuizzingPlatform\Admin\Systemsettings;

use Silex\Application;

class SystemsettingsService {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * @Desc This will get the permissions and compare the menus name and gives final menus list to fetch.
     * @param type $permissions
     * @return type
     */
    public function getResourceMenus($permissions) {

        $menusList = array();

        // By default Question types will be visible to all the admin users.
        $menusList[] = array("Master Data", "Manage question types");

        // If any permissions are set, then those menus will be listed.
        if (!empty($permissions)) {

            $menusList[] = in_array("dashboard", $permissions) ? array("Dashboard") : NULL;
            $menusList[] = in_array("metadata", $permissions) ? array("Manage metadata tags") : NULL;
            $menusList[] = in_array("items", $permissions) ? array("Question Collection", "Manage questions") : NULL;
            $menusList[] = in_array("itembanks", $permissions) ? array("Question Collection", "Manage question collection") : NULL;
            $menusList[] = in_array("tests", $permissions) ? array("Quiz Bank", "Manage quizzes") : NULL;
            $menusList[] = in_array("user", $permissions) ? array("User Management", "Manage users") : NULL;
            $menusList[] = in_array("group", $permissions) ? array("User Management", "Manage groups") : NULL;
            $menusList[] = in_array("role", $permissions) ? array("User Management", "Manage roles") : NULL;
            $menusList[] = in_array("reports", $permissions) ? array("Reports", "Student usage report", "Client report", "User Quizzing", "Metadata report", "Item report") : NULL;
            $menusList[] = in_array("systemsettings", $permissions) ? array("System Settings", "System Settings") : NULL;
        }

        $menusArray = array();
        foreach ($menusList as $lists) {
            if (is_array($lists)) {
                foreach ($lists as $list) {
                    $menusArray[] = $list;
                }
            }
        }

        // Get distinct menus list.
        $distinctMenus = array_unique($menusArray);

        return $distinctMenus;
    }

    /**
     * Get UI configurations
     * @return type
     */
    public function getUIConfigurations() {
        $systemConfigs = $this->app['systemsettings.repository']->getUIConfigurations();
        return $systemConfigs;
    }

    /**
     * Get MenuList
     * @param type $accessToken
     * @return type
     */
    public function getMenusList($accessToken) {
        $menusList = $this->app['systemsettings.repository']->getMenusList($accessToken);
        return $menusList;
    }

    /**
     * Clear Server Cache
     * @return type
     */
    public function clearCache() {
        $clearCache = $this->app['systemsettings.repository']->clearCache();
        return $clearCache;
    }

    /**
     * Get all countries
     * @return type
     */
    public function getCountries() {
        $getCountries = $this->app['systemsettings.repository']->getCountries();
        return $getCountries;
    }

    /**
     * Get All states
     * @param type $countryId
     * @return type
     */
    public function getStates($countryId) {
        $getStates = $this->app['systemsettings.repository']->getStates($countryId);
        return $getStates;
    }

    /**
     * Get all groups
     * @param type $groupId
     * @return type
     */
    public function getGroups($groupId = NULL) {
        $getGroups = $this->app['systemsettings.repository']->getGroups($groupId);
        return $getGroups;
    }

    /**
     * Get all roles
     * @param type $roleId
     * @return type
     */
    public function getRoles($roleId = NULL) {
        $getRoles = $this->app['systemsettings.repository']->getRoles($roleId);
        return $getRoles;
    }

    /**
     * Get all status
     * @return type
     */
    public function getAllStatus() {
        $getStatus = $this->app['systemsettings.repository']->getAllStatus();
        return $getStatus;
    }

    /**
     * Get user Type
     * @return type
     */
    public function getUserType() {
        $getUserType = $this->app['systemsettings.repository']->getUserType();
        return $getUserType;
    }

    /**
     * Get system configuration
     * @return type
     */
    public function getSystemconfiguration() {
        $getSystemconfig = $this->app['systemsettings.repository']->getSystemconfiguration();
        return $getSystemconfig;
    }

    /**
     * Update data
     * @param type $updateSysSettings
     * @return type
     */
    public function update($updateSysSettings) {
        $updated = $this->app['systemsettings.repository']->update($updateSysSettings);
        return $updated;
    }

}
