<?php

require_once __DIR__ .'/../config/Database.php';

class CreateElementsTable {

    public function up() {
        $query = "CREATE TABLE IF NOT EXISTS elements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo "Table 'elements' created successfully.\n";
    }

    public function down() {
        $query = "DROP TABLE IF EXISTS elements;";
        
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        echo "Table 'elements' dropped successfully.\n";
    }
}
