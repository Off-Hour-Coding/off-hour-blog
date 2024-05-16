<?php


$routes = [

    "/" => "HomeController@index",
    
    "/topic/{id}" => "PagesController@topic",
    
    "/admin" => "AdminController@index",
    "/admin/auth" => "AdminController@auth",
    "/admin/registration" => "AdminController@registrer",
    "/create/post" => "AdminController@create_new_topic"

];