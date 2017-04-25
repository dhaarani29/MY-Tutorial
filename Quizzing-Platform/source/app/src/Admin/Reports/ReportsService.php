<?php

/*
 * ReportsService - Handles Reports module business logic.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Dhaarani S
 */

namespace QuizzingPlatform\Admin\Reports;

use Silex\Application;

class ReportsService {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function getUserQuizzingReports($inputParams, $export = NULL) {
        return $this->app['reports.repository']->getUserQuizzingReports($inputParams, $export);
    }

    public function getUsageReport($reportRequest, $export = NULL) {

        $reportData = $this->app['reports.repository']->getUsageData($reportRequest, $export);

        return $reportData;
    }

    public function getClientReports($inputParams = array(), $export = NULL) {

        return $this->app['reports.repository']->getClientReports($inputParams, $export);
    }

    /**
     * Export the Details in Excel
     * @param type $headerListExcel
     * @param type $resultListExcel
     * @param type $workSheetName
     * @param type $fileName
     * @return boolean
     */
    public function excelCreate($headerListExcel, $resultListExcel, $workSheetName, $fileName) {

        //Call the PHPExcel Class
        $objPHPExcel = new \PHPExcel();

        //Set the font family
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');

        //Set the fontsize
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

        //Set the Worksheet name
        $objPHPExcel->getActiveSheet()->setTitle($workSheetName); //Set the worksheet title
        // table header data
        $colStart = 'A';
        foreach ($headerListExcel as $headerValue) {

            //Setting the value of coulmn A and row 1
            $objPHPExcel->getDefaultStyle()->getFont()->setBold();

            $objPHPExcel->getActiveSheet()->setCellValue($colStart . '1', $headerValue);
            //Increament the column
            $colStart++;
        }

        // table result data
        $rowStart = 2;
        if ($resultListExcel != NULL) {
            foreach ($resultListExcel as $resultValueArray) {
                $colStart = 'A';
                foreach ($resultValueArray as $resultValue) {

                    //Set the cell value
                    $objPHPExcel->getActiveSheet()->setCellValue($colStart . $rowStart, $resultValue);
                    $maxColCount = $colStart;
                    $colStart++;
                }
                $rowStart++;
            }
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue($colStart . $rowStart, $resultValue);
        }

        //Auto increamenting the column size based on the Column Data
        for ($incColumn = 'A'; $incColumn <= $maxColCount; $incColumn++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($incColumn)->setAutoSize(true);
        }

        //Save the file
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        //Header
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\".$fileName.xlsx\"");
        header("Cache-Control: max-age=0");
        $objWriter->save('php://output');
        return true;
    }

    public function getMetadataReport($inputParams = array(), $export = NULL) {

        return $this->app['reports.repository']->getMetadataReport($inputParams, $export);
    }

    /**
     * Export the details in PDF
     * @param type $fileName
     * @param type $html
     * @return boolean
     */
    public function pdfCreate($fileName, $html) {

        $pdf = new \TCPDF();

        //set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Quizzing Platform');
        $pdf->SetTitle('Reports');

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $pdf->SetFont('times', '', 10);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_end_clean();

        $pdf->Output($fileName, 'D');
        return true;
    }

    /**
     * export the reports details in excel
     * @param type $reportRequest
     */
    public function excelExport($reportRequest) {

        //Get the report Type
        $reportType = $reportRequest['reportType'];
        $date = date("Y_m_d");
        switch ($reportType) {
            /*
             * ClientReport Config
             */
            case 'clientreport':
                $export = 1;
                $clientReportDetails = self::getClientReports($reportRequest, $export); //Get the clientreport data
                $dataList = $clientReportDetails['data'];

                $headerList = array('Client Name ', 'Student Count', 'Quiz Count'); //Define the header

                $workSheetName = 'ClientReport'; //Define the worksheet name

                $fileName = 'ClientReport' . $date; //Define the filename
                break;

            /*
             * Studentusage Report Config
             */
            case 'studentusagereport':
                $export = 1;
                $studentUsageReport = self::getUsageReport($reportRequest, $export); //Get the student usage data
                $dataList = $studentUsageReport['data'];

                foreach ($dataList as $key => $data) {
                    unset($dataList[$key]['quizId'], $dataList[$key]['testTypeName']);
                }

                $headerList = array('Quiz Name', 'First Name', 'Last Name', 'ClientName', 'Quiz Status'); //Define header

                $workSheetName = 'StudentUsageReport'; //set the worksheetname

                $fileName = 'StudentUsageReport_' . $date; //set the filename
                break;

            /*
             * MetadataReport
             */
            case 'metadatareport':
                $export = 1;
                $metadataReport = self::getMetadataReport($reportRequest, $export); //Get the Metadata report
                $dataList = $metadataReport['data'];
            
                foreach ($dataList as $key => $data) {
                    unset($dataList[$key]['id'], $dataList[$key]['metadata'], $dataList[$key]['parentId'], $dataList[$key]['level']);
                }

                $headerList = array('Metadata Name','Topic', 'Description', 'No of Questions'); //Define header

                $workSheetName = 'MetadataReport'; //set the worksheetname

                $fileName = 'MetadataReport' . $date; //set the filename
                break;

            /*
             * MetadataReport
             */
            case 'itemreport':
                $export = 1;
                $itemReport = self::getIncorrectItems($reportRequest, $export); //Get the incorrect item details
                $dataList = $itemReport['data'];

                foreach ($dataList as $key => $data) {
                    unset($dataList[$key]['itemId']);
                }

                $headerList = array('Question title', 'Description', 'Incorrect Count'); //Define header

                $workSheetName = 'ItemReport'; //set the worksheetname

                $fileName = 'ItemReport' . $date; //set the filename
                break;

            /*
             * User Quizzing report
             */
            case 'userquizzingreport':
                $export = 1;
                $itemReport = self::getUserQuizzingReports($reportRequest, $export); //Get the incorrect item details
                $dataList = $itemReport['data'];

                $headerList = array('Topic', 'Description', 'Quiz Count'); //Define header

                $workSheetName = 'User Quizzing Report'; //set the worksheetname

                $fileName = 'UserQuizzingReport' . $date; //set the filename
                break;
        }

        //Call the excel create function to create the excel
        $response = self::excelCreate($headerList, $dataList, $workSheetName, $fileName);

        return $response;
    }

    /**
     * Export the reports in pdf format
     * @param type $reportRequest
     * @return type
     */
    public function pdfExport($reportRequest) {
        //Get the report Type
        $reportType = $reportRequest['reportType'];
        $date = date("Y_m_d");
        switch ($reportType) {
            /*
             * ClientReport Config
             */
            case 'clientreport':
                $export = 1;
                $clientReportDetails = self::getClientReports($reportRequest, $export); //Get the clientreport data
                $dataList = $clientReportDetails['data'];
                $title = ' Client Report';
                $headerList = array('Client Name ', 'Student Count', 'Quiz Count'); //Define the header
                //Table content
                foreach ($dataList as $data) {
                    $resulthtml .= '<tr><td>' . $data['clientName'] . '</td>'
                            . '<td>' . $data['studentsCount'] . '</td>'
                            . '<td>' . $data['quizCount'] . '</td>' .
                            '</tr>';
                }

                $fileName = 'ClientReport' . $date . '.pdf'; //Define the filename
                break;

            /*
             * Studentusage Report Config
             */
            case 'studentusagereport':
                $export = 1;
                $studentUsageReport = self::getUsageReport($reportRequest, $export); //Get the student usage data
                $dataList = $studentUsageReport['data'];
                $title = ' Student Usage Report';
                //Table content
                foreach ($dataList as $data) {
                    $resulthtml .= '<tr><td>' . $data['title'] . '</td>'
                            . '<td>' . $data['firstName'] . '</td>'
                            . '<td>' . $data['lastName'] . '</td>'
                            . '<td>' . $data['clientName'] . '</td>'
                            . '<td>' . $data['testStatus'] . '</td>'
                            . '</tr>';
                }
                $headerList = array('Quiz Name', 'First Name', 'Last Name', 'ClientName', 'Quiz Status'); //Define header


                $fileName = 'StudentUsageReport_' . $date . '.pdf'; //set the filename
                break;

            /*
             * Metadata Report Config
             */
            case 'metadatareport':
                $export = 1;
                $studentUsageReport = self::getMetadataReport($reportRequest, $export); //Get the Metadata data
                $dataList = $studentUsageReport['data'];
                $title = ' Metadata Report';
                //Table content
                foreach ($dataList as $data) {
                    $resulthtml .= '<tr><td>' . $data['metadataName'] . '</td>'
                            . '<td>' . $data['value'] . '</td>'
                            . '<td>' . $data['description'] . '</td>'
                            . '<td>' . $data['noOfQuestion'] . '</td>'
                            . '</tr>';
                }
                $headerList = array('Metadata Name','Topic', 'Description', 'No of Questions'); //Define header


                $fileName = 'MetadataReport' . $date . '.pdf'; //set the filename
                break;

            /*
             * Item Report Config
             */
            case 'itemreport':
                $export = 1;
                $studentUsageReport = self::getIncorrectItems($reportRequest, $export); //Get the Item data
                $dataList = $studentUsageReport['data'];
                $title = ' Item Report';
                //Table content
                foreach ($dataList as $data) {
                    $resulthtml .= '<tr><td>' . $data['label'] . '</td>'
                            . '<td>' . $data['promptText'] . '</td>'
                            . '<td>' . $data['incorrectCount'] . '</td>'
                            . '</tr>';
                }
                $headerList = array('Question title', 'Description', 'Incorrect Count'); //Define header


                $fileName = 'Itemreport' . $date . '.pdf'; //set the filename
                break;


            /*
             * User Quizzing Report Config
             */
            case 'userquizzingreport':
                $export = 1;
                $studentUsageReport = self::getUserQuizzingReports($reportRequest, $export); //Get the Item data
                $dataList = $studentUsageReport['data'];
                $title = ' User Quizzing Report';
                //Table content
                foreach ($dataList as $data) {
                    $resulthtml .= '<tr><td>' . $data['title'] . '</td>'
                            . '<td>' . $data['description'] . '</td>'
                            . '<td>' . $data['quizCount'] . '</td>'
                            . '</tr>';
                }
                $headerList = array('Topic', 'Description', 'Quiz Count'); //Define header


                $fileName = 'UserQuizzingReport' . $date . '.pdf'; //set the filename
                break;
        }

        //Forming the html content for pdf
        $html = '<html><h1 align="center"> ' . $title . '</h1>
            <table border="1px " align="center" ><tr>';
        //table header
        foreach ($headerList as $value) {
            $html .= '<th><b>' . $value . '</b></th>';
        }
        $html .= '</tr>' . $resulthtml . '</table></html>';


        //Call the excel create function to create the excel
        $response = self::pdfCreate($fileName, $html);

        return $response;
    }

    /**
     * Get incorrect Item details
     * @param type $reportRequest
     * @return type
     */
    public function getIncorrectItems($reportRequest, $export = NULL) {

        $reportData = $this->app['reports.repository']->getIncorrectItems($reportRequest, $export);

        return $reportData;
    }

}
