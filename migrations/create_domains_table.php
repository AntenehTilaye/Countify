<?php

// Require the Database connection class
require_once __DIR__ .'/../config/Database.php';

/**
 * CreateDomainsTable class is responsible for managing the creation and deletion of the 'domains' table in the database.
 * This class includes methods for migrating up (creating the table) and down (dropping the table).
 */
class CreateDomainsTable  {

    /**
     * Method to create the 'domains' table in the database if it does not already exist.
     * The table has an auto-incrementing primary key 'id' and a 'name' column for storing domain names.
     */
    public function up() {
        // SQL query to create the 'domains' table
        $query = "CREATE TABLE IF NOT EXISTS domains (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        // Get the PDO connection from the Database singleton
        $pdo = Database::getInstance()->getConnection();
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Output a success message
        echo "Table 'domains' created successfully.\n";
    }

    /**
     * Method to drop the 'domains' table from the database if it exists.
     */
    public function down() {
        // SQL query to drop the 'domains' table
        $query = "DROP TABLE IF EXISTS domains;";
        
        // Get the PDO connection from the Database singleton
        $pdo = Database::getInstance()->getConnection();
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        // Output a success message
        echo "Table 'domains' dropped successfully.\n";
    }
}
