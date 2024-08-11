<?php

// Require the Database connection class
require_once __DIR__ .'/../config/Database.php';

/**
 * CreateUrlsTable class is responsible for managing the creation and deletion of the 'urls' table in the database.
 * This class includes methods for migrating up (creating the table) and down (dropping the table).
 */
class CreateUrlsTable {

    /**
     * Method to create the 'urls' table in the database if it does not already exist.
     * The table has an auto-incrementing primary key 'id' and a 'name' column for storing URL names.
     */
    public function up() {
        // SQL query to create the 'urls' table
        $query = "CREATE TABLE IF NOT EXISTS urls (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        // Get the PDO connection from the Database singleton
        $pdo = Database::getInstance()->getConnection();
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Output a success message
        echo "Table 'urls' created successfully.\n";
    }

    /**
     * Method to drop the 'urls' table from the database if it exists.
     */
    public function down() {
        // SQL query to drop the 'urls' table
        $query = "DROP TABLE IF EXISTS urls;";
        
        // Get the PDO connection from the Database singleton
        $pdo = Database::getInstance()->getConnection();
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        // Output a success message
        echo "Table 'urls' dropped successfully.\n";
    }
}
