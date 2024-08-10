<?php

class Database
{
    private $host = 'localhost';
    private $dbname = "element_counter";
    private $dbusername = "root";
    private $dbpassword = "";
    
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->dbusername, $this->dbpassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
