<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use HelpersClass\Helpers;
use Midspace\Database;
require_once("Database.php");
require_once("./classes/Helpers.php");
require_once("./classes/QueryHelper.php");

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
                    "slug" => ":slug",
                    "email" => ":email",
                    "phone" => ":phone",
                    "password" => ":password",
                    "access_level" => ":access_level",
                    "accessed_at" => ":accessed_at"
                ],
            ],
            [
                ":name" => $name,
                ":slug" => $this->Helpers->createSlug($name),
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

        $hashed_password = $user_data->results[0]->password; // get the password field of database

        if ($this->Helpers->verifyPassword($password, $hashed_password)) { // verify hash_pass and input password 
            return $user_data->results[0]; // if password is right, return user data
        }
        // if not, will return false or nothing
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
            "users", // will update that table
            [
                "name" => ":name", // the field name will rechieve the placeholder :name because this
                "slug" => ":slug",
                "email" => ":email",
                "phone" => ":phone",
            ],
            [
                ":name" => $name, // and the placeholder :name will rechieve the value of $name
                ":slug" => $this->Helpers->createSlug($name),
                ":email" => $email,
                ":phone" => $phone,
            ],
            ["id" => $id] // here is the condition of query WHERE field id will rechieve the id value
        );

        return $this->db->execute_non_query($data->query, $data->placeholders);
    }


    public function FetchUserByID(int $id)
    {
        return $this->Select("id, name, slug, profile_pic", "users", "id = :id",  [":id" => $id]);
    }

    public function FetchUserBySlug(string $slug)
    {
        return $this->Select("*", "users", "slug = :slug",  [":slug" => $slug]);
    }

    public function FetchUserByName(string $name)
    {
        return $this->Select("*", "users", "name = :name",  [":name" => $name]);
    }

    public function UpdatePassword(int $user_id, string $new_password)
    {
        $data = $this->update_field($user_id, ["password" => $this->Helpers->hashPassword($new_password)]);
        return $this->db->execute_non_query($data->query, $data->placeholders);
    }

    public function UpdateProfilePic(int $user_id, int $profile_pic_ref)
    {
        $data = $this->update_field($user_id, ["profile_pic" => $profile_pic_ref]);
        return $this->db->execute_non_query($data->query, $data->placeholders);
    }

    public function UpdateAccessLevel(int $user_id, int $new_access_level)
    {
        $data = $this->update_field($user_id, ["access_level" => $new_access_level]);
        return $this->db->execute_non_query($data->query, $data->placeholders);
    }

    public function AuthToken($token) {
        $id = $this->Helpers->encodeURL($this->Select("id", "users", "auth_token = :token",  [":token" => $token])->results[0]->id);
        if ($id == "") {
            return false;
        }
        return $id;
    }
}
