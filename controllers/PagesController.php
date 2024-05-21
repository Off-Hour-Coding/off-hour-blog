<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

use HelpersClass\Helpers;

define('BASE_PATH', dirname(__DIR__) . '/');
require_once(BASE_PATH . '_app/Configurations.php');
class PagesController extends RenderView {

    public function index(){

        $this->loadView("home", [
            'title' => 'OffHour Coding | Página inicial',
        ]);
    }  

    public function topic($page){
        $this->loadView("topic", [
            'title' => 'Topico',
        ]);
    }   
}