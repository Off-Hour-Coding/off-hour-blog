<?php

use HelpersClass\Helpers;
use Midspace\Database;
require_once("./Database.php");
require_once("../classes/Helpers.php");
require_once("../classes/QueryHelper.php");

class Posts extends Database {

    private $Helpers;
    private $Sql;

    public function __construct()
    {
      $this->Helpers = new Helpers();
      $this->Sql = new QueryHelper();
    }
    public static function Publishing() {
        
    }
    public function DeletePost() {

    }
    public function UpdatePost() {

    }
    public function FetchPostById (int $id) {
        return $this->execute_query($this->Sql->SelectField("*", "posts", "id = :id"), [":id" => $id]);
    }
    public function FetchAllPosts () {
        
    }
    public function FetchPostsByUser_name() {

    }
    public function FetchPostsByUser_id() {

    }


}

$Posts = new Posts();

print_r($Posts->FetchPostById(1));