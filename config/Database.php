<?php

class Database
{
    private $host = 'localhost';
    private $dbname = "element_counter";
    private $dbusername = "root";
    private $dbpassword = "";


    protected function connect()
    {
        try {
            $pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->dbusername, $this->dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
