<?php
/*
=====================================================
 https://webarmen.com/
-----------------------------------------------------
 Copyright (c) 2018
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: database.php
-----------------------------------------------------
 Назначение: подключение к бд
=====================================================
*/

class Database {
    private $host = "localhost";
    private $db_name = "radio";
    private $username = "radio";
    private $password = "password";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch( PDOException $exception ) {
            die(json_encode(array(
                "status" => "error",
                "message" => $exception->getMessage()
            )));
        }

        return $this->conn;
    }
}
?>