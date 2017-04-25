<?php

/**
 * DashboardController - Handles Dashboard Module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Srinivasu M
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Dashboard;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class DashboardController {
	
    protected $app;
	
    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
		
        $this->app 		= $app;
        
        // HTTP codes
        $this->notFound 	= $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success 		= $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->created 		= $this->app['cache']->fetch('HTTP_CREATED');
        $this->duplicate 	= $this->app['cache']->fetch('HTTP_DUPLICATE');
        $this->forbidden 	= $this->app['cache']->fetch('HTTP_FORBIDDEN');
        $this->badrequest 	= $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->nocontent 	= $this->app['cache']->fetch('HTTP_NOCONTENT');
        
    }
    
    /**
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getDashboardCount(Request $request)
    {
        
        $dashboardUrlRequest = $request->query->all();
        $userId = $dashboardUrlRequest['userId'];
        if($userId) {
            
            $returnData = $this->app['dashboard.service']->getDashboardCount($userId);
            return new JsonResponse($returnData, $this->success);
        } else {
            
            $returnData = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $returnData;
        }
    }
	
}
