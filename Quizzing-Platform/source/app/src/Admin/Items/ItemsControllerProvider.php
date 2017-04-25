<?php

/**
 * ItemsControllerProvider - Class to handle the Question/Item module routing.All the Routings along with Http methods defined here.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Items;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class ItemsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        // Router to get Item/Question Types using GET method.
        $controllers->get('/api/itemtypes', 'items.controller:getItemTypes');

        $controllers->get('/api/remediationlinktypes', 'items.controller:getRemediationLinkTypes');

        //Item/Question create with post method.
        $controllers->post('/api/items', 'items.controller:createItem');

        //Get the Item/Question based on Id.
        $controllers->get('/api/items/{id}', 'items.controller:getItemById');

        //Get Item/Question  list. 
        $controllers->get('/api/items', 'items.controller:getItemsList');

        //Update Item/Question with put method.
        $controllers->put('/api/items/{id}', 'items.controller:updateItem');

        //Soft delete Item/Question  based on Id
        $controllers->delete('/api/items/{id}', 'items.controller:deleteItem');

        //Publish the question
        $controllers->get('/api/publishitem/{id}', 'items.controller:publishItem'); 
        
        //Get asset types
        $controllers->get('/api/assettypes', 'items.controller:assetTypes'); 
        
        
        //upload the assets for Question
        $controllers->post('/api/assettempupload', 'items.controller:assetTempUpload');
        
        //Associate item to itembank
        $controllers->put('/api/associateitem/{id}','items.controller:associateItem');
        
        //Get item collection details by itemid
        $controllers->get('/api/associateitem/{id}','items.controller:getItemcollectionById');
        
        //update
        $controllers->get('/api/update','items.controller:updateItemId');

        return $controllers;
    }

}
