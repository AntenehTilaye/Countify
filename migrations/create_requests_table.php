<?php

// Require the Database connection class
require_once __DIR__ .'/../config/Database.php';

/**
 * CreateRequestsTable class is responsible for managing the creation and deletion of the 'requests' table in the database.
 * This class includes methods for migrating up (creating the table with foreign key constraints) and down (dropping the table).
 */
class CreateRequestsTable {

    /**
     * Method to create the 'requests' table in the database if it does not already exist.
     * The table includes foreign key references to the 'domains', 'urls', and 'elements' tables.
     */
    public function up() {
        // Get the PDO connection from the Database singleton
        $pdo = Database::getInstance()->getConnection();

        // Create the requests table with foreign key constraints
        $query = "CREATE TABLE IF NOT EXISTS requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            domain_id INT NOT NULL,
            url_id INT NOT NULL,
            element_id INT NOT NULL,
            element_count INT NOT NULL,
            date_time datetime NOT NULL,
            duration FLOAT NOT NULL,
            FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
            FOREIGN KEY (url_id) REFERENCES urls(id) ON DELETE CASCADE,
            FOREIGN KEY (element_id) REFERENCES elements(id) ON DELETE CASCADE
        );";

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        // Output a success message
        echo "Table 'requests' created successfully with foreign keys.\n";
    }

    /**
     * Method to drop the 'requests' table from the database if it exists.
     */
    public function down() {
        // SQL query to drop the 'requests' table
        $query = "DROP TABLE IF EXISTS requests;";
        
        // Get the PDO connection from the Database singleton
        $pdo = Database::getInstance()->getConnection();
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        // Output a success message
        echo "Table 'requests' dropped successfully.\n";
    }
}
