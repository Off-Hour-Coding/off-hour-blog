<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// define('BASE_PATH', dirname(__DIR__) . '/');
$config_data = file_get_contents(__DIR__."/config.json");

$config_array = json_decode($config_data, true);

if (json_last_error() != JSON_ERROR_NONE) {
    return;
} 

date_default_timezone_set('America/Sao_Paulo');

$dataHoraAtual = new DateTime();
$dataAtual = $dataHoraAtual->format('Y-m-d');
$horaAtual = $dataHoraAtual->format('H:i:s');

define("currentDate", $dataAtual);
define("currentTime", $horaAtual);

$BASE_URL = "https://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI'] . "?") . "/";
define("SITE", "http://" . $_SERVER['SERVER_NAME'] . "/blog");

define("DB_CONFIG", $config_array["database"]);
