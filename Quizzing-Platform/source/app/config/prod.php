<?php

/**
 * Prod.php - Its a configuration file for application production mode. 
 * This will return all the configurations as a array, later config service provider will handle to store in 
 * silex application variable to access accross the application.
 * 
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * 
 */
/**
 *  Set basic information
 */
$appName = "Quizzing Platform";
$appVersion = "1.0";
$baseDir = realpath(__DIR__ . '/../');
$appMode = false;

$host = 'http://localhost/';

/**
 *  Set Monolog path,directory structure,logger level.
 */
$logfilePath = "$baseDir/var/logs/";
$customizedLogPth = $logfilePath . date('Y') . '/' . date('m') . '/' . date('d') . '/';
$logFile = $customizedLogPth . 'quizzing_platform.log';
$loggerLevel = "Monolog\Logger::INFO";

/* cache directory path */
$cacheDir = "$baseDir/var/cache";

/* Config path */
$configDir = "$baseDir/config";

$uploads = "$baseDir/web/uploads/";
$reportsupload = "$baseDir/web/uploads/reports";
$tmp = "$baseDir/web/tmp/";
$itemcollectionupload = "$baseDir/web/uploads/itemcollection";
$uploadFileName = 'allQuestions.xml';
$xsdFileName = 'allQuestionsXSD.xml';
$mediaFolder = '/media';
$uIuploads = "uploads/"; //AVAILABLE IN SYSTEM_SETTING TABLE
$uItmp = "tmp"; //AVAILABLE IN SYSTEM_SETTING TABLE
$createMode = "C";
$updateMode = "U";
/* Database date format */
$dbDate = new \DateTime();

// Set 3+ years to the current date for effectiveDateTo
$effectiveDateTo = new \DateTime('now +3 year');

/* Set database basic configurations */
$offset = 0; // Query offset //AVAILABLE IN SYSTEM_SETTING TABLE
$page = 1; // Page number //AVAILABLE IN SYSTEM_SETTING TABLE
$paginationLimit = 10; // Per page count //AVAILABLE IN SYSTEM_SETTING TABLE
$sortType = "DESC"; // Default sorting type   //AVAILABLE IN SYSTEM_SETTING TABLE
$tagList = 3;
$emailDomain = 'supportteam@impelsys.com'; //AVAILABLE IN SYSTEM_SETTING TABLE
$increment = 1;
$correct = 1;
$incorrect = 2;
$decrement = -1;
$unAttempted = 0;
$tagDelimiter = ' \ ';
$metadataIndexCount = 200;

/* Authentication error for all admin API's. */
$permissionError = array(
    "code" => '1010',
    "message" => "Don't have permission. Please contact WK admin.",
    "description" => "Don't have permission. Please contact WK admin."
);

/* Values exists to return exists keyword */
$exists = "EXISTS"; //AVAILABLE IN SYSTEM_SETTING TABLE
$notexists = "NOTEXISTS"; //AVAILABLE IN SYSTEM_SETTING TABLE
$quizTime = 300; //Minutes //AVAILABLE IN SYSTEM_SETTING TABLE
$questionTime = 18000; //seconds //AVAILABLE IN SYSTEM_SETTING TABLE
// For login validation and access token specific configuration
$tokenHeaderKey = "Authorization"; //AVAILABLE IN SYSTEM_SETTING TABLE
$tokenPrefix = "Bearer"; //AVAILABLE IN SYSTEM_SETTING TABLE
$expiredToken = "Expired token"; //AVAILABLE IN SYSTEM_SETTING TABLE
$requestFrom = "requestFrom"; //AVAILABLE IN SYSTEM_SETTING TABLE
$accessTokenLifeTime = 5184000; // Will be specified in seconds. 60 Days configured now. //AVAILABLE IN SYSTEM_SETTING TABLE
$resetPasswordExpiration = new \DateTime('now +3 day');  // 3 days;
$endUserPermission = '1'; //AVAILABLE IN SYSTEM_SETTING TABLE
$customQuiz = 'c'; //AVAILABLE IN SYSTEM_SETTING TABLE
$quizVersion = 1; //AVAILABLE IN SYSTEM_SETTING TABLE
$customQuizId = 2; //AVAILABLE IN SYSTEM_SETTING TABLE
$generalQuizId = 1; //AVAILABLE IN SYSTEM_SETTING TABLE
// Add all configuration key value pair in array and return the configvars array.
$configVars['debug'] = $appMode;
$configVars['config'] = array(
    'appDir' => $baseDir, //NOT INCLUDED IN DB
    'cacheDir' => $cacheDir, //NOT INCLUDED IN DB
    'confDir' => $configDir, //NOT INCLUDED IN DB
    'logs.dir' => $logfilePath, //NOT INCLUDED IN DB
    'logfile' => $logFile, //NOT INCLUDED IN DB
    'dbDate' => $dbDate, //NOT INCLUDED IN DB
    'uploads' => $uploads, //NOT INCLUDED IN DB
    'reports' => $reportsupload, //NOT INCLUDED IN DB
    'tmp' => $tmp, //NOT INCLUDED IN DB
    'effectiveDateTo' => $effectiveDateTo, //NOT INCLUDED IN DB
    'resetPasswordExpiration' => $resetPasswordExpiration, //NOT INCLUDED IN DB
    'increment' => $increment,
    'correct' => $correct,
    'incorrect' => $incorrect,
    'unAttempted' => $unAttempted,
    'decrement' => $decrement,
    'tagDelimiter' => $tagDelimiter,
    'qbTaxonomyType' => 'QB',
    'snomedTaxonomyType' => 'snomed',
    'fullTextSearch' => 'FULLTEXT',
    'snomedSynonymTypeId' => 900000000000003001,
    'snomedDescTypeId' => 900000000000013009,
    'snomedRelationTypeId' => 116680003,
    'snomedModuleId' => 900000000000207008,
    'itemcollectionupload' => $itemcollectionupload,
    'uploadFileName' => $uploadFileName,
    'mediaFolder' => $mediaFolder,
    'createMode' => $createMode,
    'updateMode' => $updateMode,
    'xsdFileName' => $xsdFileName,
    'metadataIndexCount' => $metadataIndexCount

// BELOW VARIABLES ARE ALREADY CONFIGURED IN DATABASE & CACHE //
        //'appname' => $appName, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'version' => $appVersion, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'host' => $host, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'logger.level' => $loggerLevel, //AVAILABLE IN SYSTEM_SETTING TABLE with the name logger_level
        //'offset' => $offset, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'page' => $page, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'limit' => $paginationLimit, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'sortType' => $sortType, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'exists' => $exists, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'notexists' => $notexists, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'uIuploads' => $uIuploads, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'uItmp' => $uItmp, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'tagList' => $tagList, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'parentNodes' => "YES", //AVAILABLE IN SYSTEM_SETTING TABLE
        //'childNodes' => "YES", //AVAILABLE IN SYSTEM_SETTING TABLE
        //'tokenHeaderKey' => $tokenHeaderKey, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'tokenPrefix' => $tokenPrefix, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'expiredToken' => $expiredToken, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'requestFrom' => $requestFrom, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'quizTime' => $quizTime, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'questionTime' => $questionTime, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'accessTokenLifeTime' => $accessTokenLifeTime, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'emailDomain' => $emailDomain, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'endUserPermission' => $endUserPermission, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'customQuiz' => $customQuiz, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'quizVersion' => $quizVersion, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'customQuizId' => $customQuizId, //AVAILABLE IN SYSTEM_SETTING TABLE
        //'generalQuizId' => $generalQuizId, //AVAILABLE IN SYSTEM_SETTING TABLE
);

// Store configuration error in configvar.
$configVars['permissionError'] = $permissionError;

/*
 *  Front end config settings
 */
// Store Confirations for UI in configvar
$configVars['uiConfig'] = array(
    'recordsPerPage' => array(10, 20, 50), //For pagination, define number of records to be visible along with default records count 
    'recordsPerPageDefault' => 10,
    'minRecordsPerPage' => array(5, 10, 15), //For pagination for minimum values        
    'minRecordsPerPageDefault' => 5, //default records per page for minimum values
    'alertTimeOut' => 2000, //Close alert in seconds
    'item' => array('itemScoreMin' => 1, //Minimum Item Score
        'itemScoreMax' => 10, //Maximum Item Score
        'itemDifficultyMin' => 1, //Minimum Item Difficulty
        'itemDifficultyMax' => 10, //Maximum Item Difficulty,
        'imageAssetAccept' => ".bmp,.jpg,.jpeg,.png",
        'videoAssetAccept' => ".mp4,.webm,.ogg",
        'graphicAssetAccept' => "bmp,.jpg,.jpeg,.png,.mp4,.webm,.ogg,.mp3,.wav",
        'medcaseAssetAccept' => "bmp,.jpg,.jpeg,.png,.mp4,.webm,.ogg",
        'imageMaxSize' => "5MB",
        'videoMaxSize' => "5MB",
        'graphicMaxSize' => "5MB",
        'medcaseMaxSize' => "5MB",
        'videoAssetId' => 3,
        'audioAssetId' => 2,
        'imageAssetId' => 1
    ),
    'quizTime' => $quizTime,
    'questionTime' => $questionTime
);

// Configuration settings for the Logging using ELK
$configVars['elkConfig'] = [
    'enableElkLogging' => 'yes', // This will enables the ELK settings, make sure ELK Server is configured.
    'writeToFileInELK' => 'yes', // This condition is to write logs to a FILE.
];

// configuration settings for user profile status
$configVars['userStatus'] = [
    'ROLE' => 1,
    'GROUP' => 2
];

$configVars['isDeleted'] = ['DELETED' => 0, 'ACTIVE' => 1];


//S3 configuraion details
$configVars['s3'] = array("accesKey" => "AKIAJ24PWCH7FY5RPJIA",
    "secretAccessKey" => "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",
    "region" => "us-east-1",
    "bucketName" => "quizzing-platform-stg"
);

// Retun the configvar which has all the configurations.
return $configVars;
?>
