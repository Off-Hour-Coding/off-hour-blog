<?php
require_once("./_app/Configurations.php"); 
require_once("./_app/functions.php"); 


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