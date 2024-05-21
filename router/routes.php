<?php


$routes = [

    "/" => "PagesController@index",
    
    "/topic/{id}" => "PagesController@topic",
    
    "/admin" => "AdminController@index",
    "/admin/auth" => "AdminController@auth",
    "/admin/registration" => "AdminController@registrer",
    "/create/post" => "PublishingController@create_new_topic",
    "/api/v1/create/post" => "PublishingController@create_new_topic_API",
    "/delete/post" => "PublishingController@delete_topic",
    "/get/user" => "UserController@get_user_by_id"
    
];