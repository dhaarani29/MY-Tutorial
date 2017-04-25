<?php

/**
 * This class handling writting the logs to the systems.
 * @author Srinivasu M <srinivasu.m@impelsys.com>
 * @copyright (c) 2016, Impelsys India Pvt. Ltd.
 * @since version-1, 18-October-2016
 */


// Declare namespaces
namespace QuizzingPlatform\Services;

use Silex\Provider\MonologServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\LineFormatter;
use Monolog\Logger;
use Silex\Application;
/*
 * LoggingService - This class handling writting the logs to the systems.
 */
class Log {
    
    /**
     * Initializing the Constructor for the class
     * 
     */
    public $app;
    
    public function __construct(Application $app) {
        
        $this->app = $app;
    }
    
    /**
     * @functionality - This function will write the logs to Logging system as configured.
     * 
     * @param string $message - The message that needs to be logged.
     * 
     * @author - Srinivasu M <srinivasu.m@impelsys.com>.
     * 
     * @copyright (c) 2016, Impelsys India Pvt. Ltd.
     */
    public function writeLog($logMessage = NULL){
        
        // Checking configuration setting values to write logs to ELK/FILE.
        if($this->app['elkConfig']['enableElkLogging'] == 'yes' || $this->app['elkConfig']['writeToFileInELK']== 'yes')
        {
            try{
                $id = 'QB'.DATE('Ymd').rand(1,10000);
                if($this->app['elkConfig']['enableElkLogging'] == 'yes')
                {
                    // Forwarding the Logs to the ELK
                     if(is_array($logMessage) && (array_key_exists("title", $logMessage) && array_key_exists("description", $logMessage)))
                    {
                        $message['timestamp'] = date(\DateTime::ISO8601);
                        foreach($logMessage as $k=>$v){
                            $message[$k] = $v;
                        }
                        //$message['message'] = $logMessage;
                    } else {
                        $message['message'] = json_encode($logMessage);
                        $message['timestamp'] = date(\DateTime::ISO8601);
                    }
                    
                    $params = [
                            'index' => 'qb_exception_index',
                            'type' => 'logmessage',
                            'body' => $message
                        ];
                    $response = $this->app['elk.obj']->index($params);
                    $id = $response['_id'];
                }
                
                if($this->app['elkConfig']['writeToFileInELK'] == 'yes'){
                    // checking for the Log message format [Array/String]
                    if(is_array($logMessage) && array_key_exists("info", $logMessage)) {
                        $data = $logMessage['info'];
                        $logMessage['log_reference'] = $id;
                        unset($logMessage['info']);
                        $data  .= \print_r($logMessage, true);
                        $logMessage = $data;
                    }
                    // Writting the Log information to the File
                    $this->app['mono.log']->addInfo(print_r($logMessage, true));
                }
                // returning the ELK/Manual generated id for Reference
                return $id;
                
            } catch(\Elasticsearch\Common\Exceptions\NoNodesAvailableException $e){
                //$elkErrorMessage = $e->getMessage();
                error_log("Problem in connection to the ELK server..!", 1,"elangovan.n@impelsys.com");
            }
        }
        
        
        
    }


}