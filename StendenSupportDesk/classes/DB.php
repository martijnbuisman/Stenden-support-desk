<?php

class DB {

    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
            $this->checkTables();
        } catch (PDOException $ex) {
            mysql_query("CREATE DATABASE " . Config::get('mysql/db'), mysql_connect(Config::get('mysql/host'), Config::get('mysql/username'), Config::get('mysql/password')));
            echo "<h1>Refresh the page</h1>";
            die($ex->getMessage());
        }
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    private function checkTables(){
        if($this->query("DESCRIBE users")->error()){
            $this->query("CREATE TABLE users ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "username VARCHAR(20), "
                    . "password VARCHAR(64), "
                    . "mail VARCHAR(50), "
                    . "salt VARCHAR(32), "
                    . "name VARCHAR(50), "
                    . "joined DATETIME, "
                    . "IconPath VARCHAR(60), "
                    . "group_id INT)");
        }
        if($this->query("DESCRIBE users_session")->error()){
            $this->query("CREATE TABLE users_session ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "user_id INT, "
                    . "hash VARCHAR(64))");
        }
        if($this->query("DESCRIBE messages")->error()){
            $this->query("CREATE TABLE messages ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "user_id INT, "
                    . "message VARCHAR(250))");
        }
    }

    public function query($sql, $params = array()) {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            if (count($params)) {
                $x = 1;
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }
    
    public function insert($table, $fields = array()) {
        $keys = array_keys($fields);
        $values = '';
        $x = 1;

        foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    
    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        return $this->results()[0];
    }

    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }

}
