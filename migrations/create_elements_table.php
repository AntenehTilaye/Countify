<?php

require 'Database.php'; // Ensure this file contains your Database class definition

class CreateElementsTable extends Database {

    public function up() {
        $query = "CREATE TABLE IF NOT EXISTS elements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo "Table 'elements' created successfully.\n";
    }

    public function down() {
        $query = "DROP TABLE IF EXISTS elements;";
        
        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        echo "Table 'elements' dropped successfully.\n";
    }
}