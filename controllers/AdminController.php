<?php
require_once("./_app/Configurations.php"); 
require_once("./_app/functions.php"); 



class AdminController extends RenderView {

    public function index(){

        $this->loadView("admin", [
            'title' => 'Admin Page',
        ]);

    }



    public function update_new_topic() {

    }

    public function delete_topic() {

    }

    
}