<?php

class PagesController extends RenderView {

    public function topic($page){
        $this->loadView("topic", [
            'title' => 'Topico',
        ]);
    }   
}