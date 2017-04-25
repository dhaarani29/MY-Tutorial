<?php

/**
 * ReportsControllerProvider - Handle the Reports module routing. All the Routings along with Http methods defined here.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */
// Declare namespaces

namespace QuizzingPlatform\Admin\Reports;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class ReportsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        //client report
        $controllers->get('/api/reports/clientreport', 'reports.controller:clientReport');

        //studentusage report
        $controllers->get('/api/reports/studentusagereport', 'reports.controller:getUsage');

        //Export Excel
        $controllers->get('/api/reports/excelexport', 'reports.controller:reportExportExcel');

        //Export Pdf
        $controllers->get('/api/reports/pdfexport', 'reports.controller:reportExportPdf');

        //User Quizzing report
        $controllers->get('/api/reports/userquizzingreport', 'reports.controller:getUserQuizzing');

        //Metadata report
        $controllers->get('/api/reports/metadatareport', 'reports.controller:metadataReport');

        //Most Incorrect item report
        $controllers->get('/api/reports/itemreport', 'reports.controller:incorrectItemReport');

        return $controllers;
    }

}
