<?php

/**
 * GroupControllerProvider - Handles the Group module routing. All the Routings along with Http methods defined here.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Groups;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class GroupsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        
        $controllers = $app['controllers_factory'];
        
        //Listing All the Groups
        $controllers->get('/api/groups', 'groups.controller:getAllGroups');
        
        //Fetch group by id
        $controllers->get('/api/groups/{id}', 'groups.controller:getGroupById');
        
        //Fetch group by id
        $controllers->delete('/api/groups/{id}', 'groups.controller:deleteGroupInfo');
        
        //Create the group
        $controllers->post('/api/groups', 'groups.controller:createGroup');

        //Edit the group
        $controllers->put('/api/groups/{id}', 'groups.controller:updateGroup');

        return $controllers;
    }

}