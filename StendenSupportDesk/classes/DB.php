<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
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

    private function checkTables() {
        if ($this->query("DESCRIBE users")->error()) {
            $this->query("CREATE TABLE users ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "username VARCHAR(20), "
                    . "password VARCHAR(64), "
                    . "email VARCHAR(50), "
                    . "salt VARCHAR(32), "
                    . "name VARCHAR(50), "
                    . "joined DATETIME, "
                    . "IconPath VARCHAR(60), "
                    . "company_id INT, "
                    . "function VARCHAR(50))");

            //Insert default user admin
            $salt = Hash::salt(32);
            $this->insert('users', array(
                'username' => 'cassshh',
                'password' => Hash::make('password', $salt),
                'email' => 'casvd@hotmail.com',
                'name' => 'Cas van Dinter',
                'salt' => $salt,
                'joined' => date("Y-m-d H:i:s"),
                'IconPath' => 'icons/default.png',
                'company_id' => 1,
                'function' => 'Administrator'
            ));
//            $this->query("INSERT INTO `stendensupportdesktest`.`users` (`id`, `username`, `password`, `email`, `salt`, `name`, `joined`, `IconPath`, `company_id`, `function`) VALUES ('1', 'cassshh', '23d972229963e493ae68b496969873f8b5c531e71b0063527c2791fc57e05593', 'casvd@hotmail.com', '’N;o$V5J´‚â;C}|eŸá!‹QUƒäb:šÍ(m', 'Cas van Dinter', '2015-01-22 06:15:14', 'icons/default.png', '1', 'Administrator');");
        }
        if ($this->query("DESCRIBE users_session")->error()) {
            $this->query("CREATE TABLE users_session ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "user_id INT, "
                    . "hash VARCHAR(64))");
        }
        if ($this->query("DESCRIBE groups")->error()) {
            $this->query("CREATE TABLE groups ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "name VARCHAR(40), "
                    . "permissions TEXT)");

            //Insert default groups
            $this->insert('groups', array(
                'id' => '1',
                'name' => 'Administrator',
                'permissions' => '{"admin": 1}'
            ));
            $this->insert('groups', array(
                'id' => '2',
                'name' => 'Werknemer',
                'permissions' => '{"werknemer": 1}'
            ));
            $this->insert('groups', array(
                'id' => '3',
                'name' => 'Klant+',
                'permissions' => '{"gb": 1, "ol": 1}'
            ));
            $this->insert('groups', array(
                'id' => '4',
                'name' => 'Klant',
                'permissions' => '{"gb": 1, "ol": 0}'
            ));
        }
        if ($this->query("DESCRIBE company")->error()) {
            $this->query("CREATE TABLE company ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "name VARCHAR(50), "
                    . "adres VARCHAR(40), "
                    . "phone VARCHAR(16), "
                    . "email VARCHAR(30), "
                    . "group_id INT)");

            //Insert standard StendenAdmin & StendenEmployee
            $this->insert('company', array(
                'name' => 'StendenAdmin',
                'adres' => 'AdminAdress',
                'phone' => '0123456789',
                'email' => 'ehelp@stenden.com',
                'group_id' => 1
            ));
            $this->insert('company', array(
                'name' => 'StendenEmployee',
                'adres' => 'EmployeeAdress',
                'phone' => '0123456789',
                'email' => 'ehelp@stenden.com',
                'group_id' => 2
            ));
        }

        if ($this->query("DESCRIBE faq")->error()) {
            $this->query("CREATE TABLE faq ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "question VARCHAR(100), "
                    . "answer TEXT)");
        }
        if ($this->query("DESCRIBE tickets")->error()) {
            $this->query("CREATE TABLE tickets ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "user_id INT, "
                    . "ticket VARCHAR(500), "
                    . "werknemer_id INT, "
                    . "status INT)");
        }
        if ($this->query("DESCRIBE status")->error()) {
            $this->query("CREATE TABLE status ("
                    . "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
                    . "status VARCHAR(30))");
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
