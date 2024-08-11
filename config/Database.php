<?php

/**
 * Database class for establishing a connection to a MySQL database using PDO.
 * This class follows the Singleton pattern to ensure only one instance of the database connection exists throughout the application.
 */
class Database
{
    // Database connection parameters
    private $host = 'localhost';
    private $dbname = "element_counter";
    private $dbusername = "root";
    private $dbpassword = "";
    
    // Static instance variable to hold the single instance of the Database class
    private static $instance = null;

    // PDO instance for the database connection
    private $pdo;

    /**
     * Private constructor to prevent direct creation of instances.
     * Initializes the PDO connection and sets error handling mode to exceptions.
     */
    private function __construct()
    {
        try {
            // Create a new PDO instance for the database connection
            $this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->dbusername, $this->dbpassword);
            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // If the connection fails, display an error message and terminate the script
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Static method to get the single instance of the Database class.
     * If an instance doesn't exist, it creates one.
     *
     * @return Database The single instance of the Database class
     */
    public static function getInstance()
    {
        // Check if the instance is null (i.e., not yet created)
        if (self::$instance === null) {
            // Create a new instance of the Database class
            self::$instance = new self();
        }
        // Return the instance
        return self::$instance;
    }

    /**
     * Method to get the PDO connection object.
     *
     * @return PDO The PDO connection object
     */
    public function getConnection()
    {
        // Return the PDO connection object
        return $this->pdo;
    }
}
