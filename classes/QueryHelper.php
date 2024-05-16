<?php
class QueryObject
{
    public $query;
    public $placeholders;

    public function __construct($query, $placeholders)
    {
        $this->query = $query;
        $this->placeholders = $placeholders;
    }
}

class QueryHelper
{

    public function createInsert(array $data, array $insert_data)
    {
        $table = array_keys($data)[0];
        $columns = array_keys($data[$table]);
        $placeholders = array_values($data[$table]);

        $columns_str = implode(", ", $columns);
        $placeholders_str = implode(", ", $placeholders);
        $sql = "INSERT INTO $table ($columns_str) VALUES ($placeholders_str)";

        $filtered_placeholders = array_filter($insert_data, function ($key) use ($placeholders) {
            return in_array($key, $placeholders);
        }, ARRAY_FILTER_USE_KEY);

        return new QueryObject($sql, $filtered_placeholders);
    }

    // $data = [
    //     "user" => [
    //         "name" => ":name",
    //         "email" => ":email",
    //         "password" => ":password"
    //     ]
    // ];

    // $insert_data = [
    //     ":name" => "kerlon", 
    //     ":email" => "kerlon@gmail.com",
    //     ":password" => "123456"
    // ];

    // $result = createInsertQuery($data, $insert_data);
    // print_r($result);

    //     QueryObject Object
    //      (
    //     [query] => INSERT INTO user (name, email, password) VALUES (:name, :email, :password)
    //     [placeholders] => Array
    //         (
    //             [:name] => kerlon
    //             [:email] => kerlon@gmail.com
    //             [:password] => 123456
    //         )
    //      )
    public function createSelectQuery($data, $select_fields, $conditions = [])
    {
        $table = array_keys($data)[0];
        $select_clause_str = implode(", ", $select_fields);
        $where_clause = "";

        if (!empty($conditions)) {
            $where_clause = "WHERE ";
            $conditions_str = [];
            foreach ($conditions as $field => $value) {
                $conditions_str[] = "$field = :$field";
            }
            $where_clause .= implode(" AND ", $conditions_str);
        }

        // Remove os dois pontos dos placeholders na query
        $sql = "SELECT $select_clause_str FROM $table $where_clause";
        $placeholders = array_combine(array_map(function ($key) {
            return ':' . ltrim($key, ':');
        }, array_keys($conditions)), $conditions);

        return new QueryObject($sql, $placeholders);
    }

    // // Criação da consulta SELECT com condição
    // $query = $h->createSelectQuery(
    //     [   
    //         // 
    //          "user" => [ // <== SELECT DA TABELA NOS CAMPOS 
    //             "nome" => "name", //  <== o campo nome vai receber o valor de name ":name" e assim por diante
    //             "email" => "email", // <==
    //             "password" => "password" // <==
    //         ]
    //     ], // fim da array de definição dos campos e valores 
    //     [ 
    //         // Campos Selecionados:
    //         "name",
    //         "email",
    //         "password"
    //     ],
    //     [
    //         // condições 
    //         "email" => "kerlon@gmail.com"
    //     ]
    // );


    function createUpdateQuery($data, $update_data, $condition)
    {
        $table = array_keys($data)[0];

        $columns = array_keys($data[$table]);

        $set_clause = [];
        foreach ($columns as $column) {
            $set_clause[] = "$column = :$column";
        }
        $set_clause_str = implode(", ", $set_clause);

        $sql = "UPDATE $table SET $set_clause_str WHERE $condition";

        $values = [];
        foreach ($columns as $column) {
            $values[":$column"] = $update_data[$column];
        }

        return new QueryObject($sql, $values);
    }

    public function SelectField(string $field, string $table, string $condition="") {
        $query = "SELECT {$field} FROM {$table}";
        $query .= $condition != "" ? " WHERE {$condition}" : "";
        return $query;
    } 

    public function DeleteFrom(string $field, string $table, string $condition="") {
        $query = "DELETE FROM {$table}";
        $query .= $condition != "" ? " WHERE {$condition}" : "";
        return $query;
    }

}

