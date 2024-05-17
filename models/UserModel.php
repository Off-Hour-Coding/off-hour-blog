<?php

use Midspace\Database;
use HelpersClass\Helpers;

require_once("../_app/Configurations.php");
require_once("./Database.php");
require_once("../classes/Helpers.php");
require_once("../classes/QueryHelper.php");

class User extends Database
{

    private $Helpers;
    private $Sql;
    private $db;

    // limited method to simple select querys
    private function Select(string $field, string $table, string $condition, array $values)
    {
        return $this->db->execute_query($this->Sql->SelectField($field, $table, $condition), $values);
    }

    public function __construct()
    {
        $this->Helpers = new Helpers();
        $this->Sql = new QueryHelper();
        $this->db = new Database(DB_CONFIG);
    }

    public function Register(string $name, string $email, string $phone, string $password)
    {

        $password = isset($password) ? $this->Helpers->hashPassword($password) : "";

        $data = $this->Sql->createInsert(
            [
                "users" => [
                    "name" => ":name",
                    "email" => ":email",
                    "phone" => ":phone",
                    "password" => ":password",
                    "access_level" => ":access_level",
                    "accessed_at" => ":accessed_at"
                ],
            ],
            [
                ":name" => $name,
                ":email" => $email,
                ":phone" => $phone,
                ":password" => $password,
                ":access_level" => "0",
                ":accessed_at" => $this->Helpers->getCurrentDateTime()

            ]
        );

        return $this->db->execute_non_query($data->query, $data->placeholders);
    }

    public function Auth(string $email, string $password)
    {
        if (!isset($email) || !isset($password)) {
            $res['status'] = 'error';
            $res['message'] = 'Campos de email e senha são obrigatórios.';
            $res['erro'] = true;

            return $res;
        }

        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $user_data = $this->Select("*", "users", "email = :email",  [":email" => $email]);

        if (empty($user_data->results)) return;

        $hashed_password = $user_data->results[0]->password;

        if ($this->Helpers->verifyPassword($password, $hashed_password)) {
            return $user_data->results[0];
        }

        return false;
    }

    public function Delete(int $id)
    {
        $data = $this->Sql->createDeleteQuery("users", ["id" => $id]);
        return $this->db->execute_non_query($data->query, $data->placeholders);
    }

    public function Update(int $id, string $name, string $email, string $phone)
    {
        $data = $this->Sql->createUpdateQuery(
            "users", 
            [
                "name" => ":name",
                "email" => ":email",
                "phone" => ":phone",
            ],
            [
                ":name" => $name,
                ":email" => $email,
                ":phone" => $phone,
            ],
            ["id" => $id]
        );
        
        return $this->db->execute_non_query($data->query, $data->placeholders);
    }

    public function update_field(int $id, array $fields)
    {
        $updateFields = [];
        $updateValues = [];
        
        foreach ($fields as $field => $value) {
            $updateFields[$field] = ":$field";
            $updateValues[":$field"] = $value;
        }

        return $this->Sql->createUpdateQuery(
            "users",
            $updateFields,
            $updateValues,
            ["id" => $id]
        );
    }
    public function FetchUserByID(int $id)
    {
        return $this->Select("*", "users", "id = :id",  [":id" => $id]);
    }

    public function FetchUserByName(string $name)
    {
        return $this->Select("*", "users", "name = :name",  [":name" => $name]);
    }
}

$users = new User();


