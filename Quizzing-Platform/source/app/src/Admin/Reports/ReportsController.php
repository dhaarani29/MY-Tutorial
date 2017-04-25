<?php

/**
 * ReportsController - Handles Reports module.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Reports;

// Load silex modules
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReportsController {

    protected $app;
    protected $module;

    // Initialise silex application in the constructor.
    public function __construct(Application $app) {
        $this->app = $app;
        $this->module = "reports";
        
        //HTTP Codes
        $this->notFound     = $this->app['cache']->fetch('HTTP_NOTFOUND');
        $this->success      = $this->app['cache']->fetch('HTTP_SUCCESS');
        $this->created      = $this->app['cache']->fetch('HTTP_CREATED');
        $this->duplicate    = $this->app['cache']->fetch('HTTP_DUPLICATE');
        $this->forbidden    = $this->app['cache']->fetch('HTTP_FORBIDDEN');
        $this->badrequest   = $this->app['cache']->fetch('HTTP_BADREQUEST');
        $this->nocontent    = $this->app['cache']->fetch('HTTP_NOCONTENT');
    }

    /**
     * Client Report
     * @param Request $request
     * @return type
     */
    public function clientReport(Request $request) {

        $inputParams = $request->query->all();
        $loggedInUserId = $inputParams['userId'];
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'clientreports');
        if ($hasPermission) {
            $clientReportDetails = $this->app['reports.service']->getClientReports($inputParams);
            //echo "<pre>"; print_r($clientReportDetails); die;
            if (!empty($clientReportDetails)) {
                // Return user details.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($clientReportDetails, $this->success);
                return $response;
            } else {
                // Return following error if user not exists.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['NO_RECORDS_ERROR']);
                return $response;
            }
        } else {
            // If user doesn't have permission to create user Info then return permission error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Get the usage report
     * @param Request $request
     * @return type
     */
    public function getUsage(Request $request) {
        $reportRequest = $request->query->all();

        //Login userId
        $userId = $reportRequest['userId'];

        // Check user has permission to view report.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'studentusagereport');

        if ($hasPermission) {

            $reportData = $this->app['reports.service']->getUsageReport($reportRequest);

            if (!$reportData) {
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['RETRIEVING_ERROR']);
                return $response;
            } else {
                // Return report data.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($reportData, $this->success);
                return $response;
            }
        } else {

            // Return following error if your doesn't have permission to view the report.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * @desc - Method to get the : Which subjects and topics are users quizzing themselves on most often, so that we can focus development on these areas.
     * 
     * @param Input String - Request $request, 
     * 
     * @return JSON - json array of data
     * 
     * @author Srinivasu M <srinivasu.m@impelsys.com>
     * 
     * @copyright (c) 2017, Impelsys India Pvt. Ltd.
     * 
     * @since 09-Feb-2017
     * 
     */
    public function getUserQuizzing(Request $request) {
        $inputParams = $request->query->all();
        $loggedInUserId = $inputParams['userId'];
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($loggedInUserId, $this->module, 'userquizzingreport');
        if ($hasPermission) {
            $userQuizzingReportDetails = $this->app['reports.service']->getUserQuizzingReports($inputParams);
            //echo "<pre>"; print_r($userQuizzingReportDetails); die;
            if (!empty($userQuizzingReportDetails)) {
                // Return user details.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($userQuizzingReportDetails, $this->success);
                return $response;
            } else {
                // Return following error if user not exists.
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['NO_RECORDS_ERROR']);
                return $response;
            }
        } else {
            // If user doesn't have permission to create user Info then return permission error.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Export the student usage report in excel
     * Export the report in Excel format
     * @ClientReportapi : http://localhost/api/excelexport?reportType=clientreport&clientName=wk&sort=-clientName
     * @StudentUsageReportapi : http://localhost/api/excelexport?reportType=studentusagereport&title=test&sort=-title
     * @param Request $request
     */
    public function reportExportExcel(Request $request) {

        //Get the request parameters
        $reportRequest = $request->query->all();

        //Login userId
        $userId = $reportRequest['userId'];

        //Call the service function to export excel
        $response = $this->app['reports.service']->excelExport($reportRequest);

        if ($response) {
            //Return success response if exported successfully
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($response, $this->success);
            return $response;
        } else {
            // Return following error if export failed . 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notfound, $this->app['EXPORT_REPORT_ERROR']);
            return $response;
        }
    }

    /**
     * Export the report in pdf format
     * @ClientReportapi : http://localhost/api/pdfexport?reportType=clientreport&clientName=wk&sort=-clientName
     * @StudentUsageReportapi :  http://localhost/api/pdfexport?reportType=studentusagereport&title=test&sort=-title
     * @param Request $request
     */
    public function reportExportPdf(Request $request) {

        //Get the request parameters
        $reportRequest = $request->query->all();

        //Login userId
        $userId = $reportRequest['userId'];

        //Call the excel create function to create the excel sheet
        $response = $this->app['reports.service']->pdfExport($reportRequest);
        if ($response) {
            //Return success response if exported successfully
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($response, $this->success);
            return $response;
        } else {
            // Return following error if export failed . 
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notfound, $this->app['EXPORT_REPORT_ERROR']);
            return $response;
        }
    }

    /* By srilakshmi
     * To get all metadata with there child nodes in array format and not tree structure.
     *
     */

    public function metadataReport(Request $request) {
        $reportRequest = $request->query->all();

        //Login userId
        $userId = $reportRequest['userId'];

        // Check user has permission to view report.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'metadatareport');

        if ($hasPermission) {

            $reportData = $this->app['reports.service']->getMetadataReport($reportRequest);

            if (!$reportData) {
                $response = $this->app['systemsettings.controller']->returnErrorResponse($this->notFound, $this->app['TEST_RETRIEVING_ERROR']);
                return $response;
            } else {
                // Return report data.
                $response = $this->app['systemsettings.controller']->returnSuccessResponse($reportData, $this->success);
                return $response;
            }
        } else {

            // Return following error if your doesn't have permission to view the report.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

    /**
     * Get Most Incorrect Item report
     * @param Request $request
     */
    public function incorrectItemReport(Request $request) {

        //report request
        $reportRequest = $request->query->all();

        //Login userId
        $userId = $reportRequest['userId'];

        // Check user has permission to view report.
        $hasPermission = $this->app['users.controller']->checkResourceActionPermission($userId, $this->module, 'itemreport');

        if ($hasPermission) {

            $reportData = $this->app['reports.service']->getIncorrectItems($reportRequest);

//            if (!$reportData) {
//                $response = $this->app['systemsettings.controller']->returnErrorResponse(4005, "Error retrieving test Details", "Description of Error retrieving Details.", $this->notFound);
//                return $response;
//            } else {
            // Return report data.
            $response = $this->app['systemsettings.controller']->returnSuccessResponse($reportData, $this->success);
            return $response;
            //   }
        } else {

            // Return following error if your doesn't have permission to view the report.
            $response = $this->app['systemsettings.controller']->returnErrorResponse($this->forbidden, $this->app['PERMISSION_ERROR']);
            return $response;
        }
    }

}
