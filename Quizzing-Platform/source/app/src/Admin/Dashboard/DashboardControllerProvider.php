<?php

/**
 * DashboardControllerProvider - Class to handle the Dashboard module routing.All the Routings along with Http methods defined here.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Srinivasu M
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Dashboard;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class DashboardControllerProvider implements ControllerProviderInterface {
	
	public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        // Router to get Dashboard/Question-Type Count using GET method.
        $controllers->get('/api/questiontypecount', 'dashboard.controller:getQuestionTypeCount');
        
        // Router to get Dashboard/Metadata-tags Count using GET method.
        $controllers->get('/api/metadatatagscount', 'dashboard.controller:getMetaDataTagsCount');
        
        // Router to get Dashboard/Questions Count using GET method.
        $controllers->get('/api/questionscount', 'dashboard.controller:getQuestionsCount');
        
        // Router to get Dashboard/Question-Bank Count using GET method.
        $controllers->get('/api/questionbankcount', 'dashboard.controller:getQuestionBankCount');
        
        // Router to get Dashboard/quiz Count using GET method.
        $controllers->get('/api/quizcount', 'dashboard.controller:getQuizCount');
        
        // Router to get Dashboard/Users Count using GET method.
        $controllers->get('/api/userscount', 'dashboard.controller:getUsersCount');
        
        // Router to get Dashboard/Users Count using GET method.
        $controllers->get('/api/dashboard', 'dashboard.controller:getDashboardCount');

        return $controllers;
    }
	
	
	
	
}
