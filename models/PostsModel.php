<?php

use HelpersClass\Helpers;
use Midspace\Database;
require_once("Database.php");
require_once("./classes/Helpers.php");
require_once("./classes/QueryHelper.php");

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
    public function Publishing(string $title, string $content, string $excerpt, int $author_id, string $status, int $category_id, string $tags) 
    {
        $data = $this->Sql->createInsert(
            [
                "posts" => [
                    "title" => ":title",
                    "slug" => ":slug",
                    "content" => ":content",
                    "excerpt" => ":excerpt",
                    "author_id" => ":author_id",
                    "created_at" => ":created_at",
                    "updated_at" => ":updated_at",
                    "published_at" => ":published_at",
                    "status" => ":status",
                    "category_id" => ":category_id",
                    "tags" => ":tags"
                ],
            ],
            [
                ":title" => $this->Helpers->sanitizeInput($title),
                ":slug" => $this->Helpers->createSlug($title),
                ":content" => $content,
                ":excerpt" => $this->Helpers->sanitizeInput($excerpt),
                ":author_id" => $author_id,
                ":created_at" => $this->Helpers->getCurrentDateTime(),
                ":updated_at" => $this->Helpers->getCurrentDateTime(),
                ":published_at" => $this->Helpers->getCurrentDateTime(),
                ":status" => $status,
                ":category_id" => $category_id,
                ":tags" => $this->Helpers->sanitizeInput($tags)
            ]
        );

        return $this->db->execute_non_query($data->query, $data->placeholders);
    }
    public function Delete(int $id) {
        $data = $this->Sql->createDeleteQuery("posts", ["id" => $id]);
        return $this->db->execute_non_query($data->query, $data->placeholders);
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
        
    }
    public function FetchUserPostsById(int $id) {
        $this->Select("*", "post", "author_id = :id", [":id" => $id]);
    }

}

// $posts = new Posts();

// print_r($posts->Publishing("segunda Postagem do blog kkkkkk", "estou no trabalho fazendo isso e nao me orgulho disso", "Parece lei ter um chefe que nao sabe de bosta nenhuma...", 1, "published", 1, "trabalho, programação, computação, computaria"));

// print_r($posts->Delete(5));