<?php

// include the prod configuration
require __DIR__ . '/prod.php';

// Configuration specific to dev mode.
$appMode = true;
$loggerLevel = "Monolog\Logger::DEBUG";

// Overwrite prod configurations with dev configurations.
$configVars['debug'] = $appMode;
$configVars['config']['logger.level'] = $loggerLevel;

// Return the config values.
return $configVars;
?>
