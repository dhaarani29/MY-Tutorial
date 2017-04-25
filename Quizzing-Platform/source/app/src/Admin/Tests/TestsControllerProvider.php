<?php

/**
 * TestsControllerProvider - Class to handle the Tests/Quiz module routing.All the Routings along with Http methods defined here.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Tests;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class TestsControllerProvider implements ControllerProviderInterface {

    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        // create the Quiz /tests
        $controllers->post('/api/tests', 'tests.controller:createTest');

        //Get the test by id
        $controllers->get('/api/tests/{id}', 'tests.controller:getTestById');

        //delete the Test /Quiz
        $controllers->delete('/api/tests/{id}', 'tests.controller:deleteTest');

        //Listing the Test/Quiz
        $controllers->get('/api/tests', 'tests.controller:getTest');

        //Update the Test / Quiz
        $controllers->put('/api/tests/{id}', 'tests.controller:updateTest');

        //Listing the all the test progress for the particular testid
        //$controllers->get('/api/tests/{id}/progress', 'tests.controller:getTestProgress');
        //Get test progress for the test id of individual client user id
        //$controllers->get('/api/tests/{id}/progress/{clientUserId}', 'tests.controller:getTestProgress');
        //Get test progress for the test id of individual client user id based on topic selected(id)
        //Get quiz instance questions list
        //$controllers->get('/api/tests/instance/{instanceId}', 'tests.controller:getTestInstanceItems');

        $controllers->get('/api/tests/{id}/instances', 'tests.controller:getTestProgress');

        //Get test progress for the test instance id of individual client user id 
        $controllers->get('/api/tests/{id}/instances/{instanceId}', 'tests.controller:getTestProgress');


        //Get latest test progress for the test id of individual client user id based on topic selected(id)
        $controllers->get('/api/metadata/{id}/tests/progress', 'tests.controller:getTestProgressBar');

        //Create the quiz instance
        $controllers->post('/api/tests/{id}/instances', 'tests.controller:createTestInstance');

        //Delete the quiz instance
        $controllers->delete('/api/tests/{id}/instances/{instanceId}', 'tests.controller:deleteTestInstance');

        //Get quiz instance first question/bookmarked details
        $controllers->get('/api/tests/instance/{instanceId}/items', 'tests.controller:getInstanceItemDetails');

        //Get quiz instance question details for the given item id
        $controllers->get('/api/tests/instance/{instanceId}/items/{itemId}', 'tests.controller:getInstanceItemDetails');

        //Get quiz instance question details for the given item id with version number
        $controllers->get('/api/tests/instance/{instanceId}/items/{itemId}/version/{version}', 'tests.controller:getInstanceItemDetails');

        //Summary / Review page
        $controllers->get('/api/tests/{id}/instances/{instanceId}/summary', 'tests.controller:getTestResultItems');

        //Submit Answer 
        $controllers->post('/api/tests/instance/{id}/items/{itemId}', 'tests.controller:submitTestAnswers');

        //Submit Answer with version number
        $controllers->post('/api/tests/instance/{id}/items/{itemId}/version/{version}', 'tests.controller:submitTestAnswers');


        return $controllers;
    }

}
