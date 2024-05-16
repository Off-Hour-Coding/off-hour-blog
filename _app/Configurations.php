<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config_data = file_get_contents(__DIR__."/config.json");

$config_array = json_decode($config_data, true);

if (json_last_error() != JSON_ERROR_NONE) {
    return;
} 

define("DB_CONFIG", $config_array["database"]);