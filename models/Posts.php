<?php

use HelpersClass\Helpers;
use Midspace\Database;
require_once("../_app/Configurations.php");
require_once("./Database.php");

require_once("../classes/Helpers.php");
require_once("../classes/QueryHelper.php");

class Posts {

    private $Helpers;
    private $Sql;
    private $db;
    private function Select(string $field, string $table, string $condition="", array $values = []) {
        return $this->db->execute_query($this->Sql->SelectField($field, $table, $condition), $values);
    }
    public function __construct()
    {
        $this->Helpers = new Helpers();
        $this->Sql = new QueryHelper();
        $this->db = new Database(DB_CONFIG);
    }
    public static function Publishing() {
        
    }
    public function Delete() {

    }
    public function Update() {

    }
    public function FetchPostById (int $id) {
        return $this->db->execute_query($this->Sql->SelectField("*", "posts", "id = :id"), [":id" => $id]);
    }
    public function FetchAll () {
        return $this->Select("*", "posts");
    }
    public function FetchUserPostsByName(string $name) {
        return $this->Select("*", "users", "name = :name",  [":name" => $name]);
    }
    public function FetchUserPostsById(int $id) {
        return $this->Select("*", "users", "id = :id",  [":id" => $id]);
    }


}

$posts = new Posts();
