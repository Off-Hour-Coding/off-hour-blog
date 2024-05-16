<?php

class AdminController extends RenderView {

    public function index(){

        $this->loadView("admin", [
            'title' => 'Admin Page',
        ]);

    }

    public function create_new_topic() {
        if($_SERVER['REQUEST_METHOD'] != "POST") return;
        if(isset($_POST[''])) {
            
        }

    }

    public function update_new_topic() {

    }

    public function delete_topic() {

    }

    
}