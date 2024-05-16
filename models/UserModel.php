<?php

use Midspace\Database;
use HelpersClass\Helpers;
require_once("../_app/Configurations.php");
require_once("./Database.php");
require_once("../classes/Helpers.php");
require_once("../classes/QueryHelper.php");

class User extends Database{

    private $Helpers;
    private $Sql;
    private $db;

    // limited method to simple select querys
    private function Select(string $field, string $table, string $condition, array $values) {
        return $this->db->execute_query($this->Sql->SelectField($field, $table, $condition), $values);
    }

    public function __construct() {
        $this->Helpers = new Helpers();
        $this->Sql = new QueryHelper();
        $this->db = new Database(DB_CONFIG);
    }

    public function Register() {
        
    }

    public function Auth() {

    }

    public function DeleteUser() {

    }

    public function UpdateUserData() {

    }

    public function FetchUserByID(int $id) {
        return $this->Select("*", "users", "id = :id",  [":id" => $id]);
    }
    
    public function FetchUserByName(string $name) {
        return $this->Select("*", "users", "name = :name",  [":name" => $name]);
    }

}

$users = new User();

print_r($users->FetchUserByID(1));