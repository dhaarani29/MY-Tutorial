<?php

/**
 * ItembanksControllerProvider - Handles the Itembanks module routing. All the Routings along with Http methods defined here.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Itembanks;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class ItembanksControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {


        $controllers = $app['controllers_factory'];

        //Listing All the collection
        $controllers->get('/api/itembanks', 'itemcollection.controller:getItemcollection');

        //Create the Item Collection
        $controllers->post('/api/itembanks', 'itemcollection.controller:createItemcollection');

        //Get Item bank By Id
        $controllers->get('/api/itembanks/{id}', 'itemcollection.controller:getItemcollectionById');

        //Edit the item collection
        $controllers->put('/api/itembanks/{id}', 'itemcollection.controller:updateItemcollection');

        //delete the item collection
        $controllers->delete('/api/itembanks/{id}', 'itemcollection.controller:deleteItemcollection');

        //Item listing for Association
        $controllers->get('/api/itemlist', 'itemcollection.controller:getItemList');

        //Itembanks upload
        $controllers->get('/api/itembanksupload', 'itemcollection.controller:uploadItemcollection');

        //Itemcollection listing for association
        $controllers->get('/api/itembanklist', 'itemcollection.controller:getItembanklist');

        $controllers->post('/api/itembanksfileupload', 'itemcollection.controller:uploadItemcollectionFile');

        $controllers->post('/api/itembanksimport', 'itemcollection.controller:createItemcollectionByUpload');

        //parsing the xml content
        $controllers->get('/api/crons/upload-items', 'itemcollection.controller:parseItems');

        //Publish the question bank n questions associated to it
        $controllers->get('/api/publishitemcollection/{id}', 'itemcollection.controller:publishItemCollection');

        //Get upload/parsing collection status
        $controllers->get('/api/importstatus/{id}', 'itemcollection.controller:importstatus');

        //Export Item collection to xml
        $controllers->get('/api/itemcollection/{id}/export', 'itemcollection.controller:exportItemCollection');

        return $controllers;
    }

}
