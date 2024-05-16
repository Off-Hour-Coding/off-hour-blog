<?php

use Midspace\Database;
use HelpersClass\Helpers;

require_once("./Database.php");
require_once("../classes/Helpers.php");

class User extends Database{

    private $Helpers;

    public function __construct() {
        $this->Helpers = new Helpers();
    }

    public static function Register() {
        
    }

    public static function Auth() {

    }

    public static function DeleteUser() {

    }

    public static function UpdateUserData() {

    }

    public static function FetchUserByID() {

    }
    
    public static function FetchUserByName() {

    }

}