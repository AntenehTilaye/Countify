<?php

require_once __DIR__ .'/../config/Database.php';

class CreateUrlsTable {

    public function up() {
        $query = "CREATE TABLE IF NOT EXISTS urls (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo "Table 'urls' created successfully.\n";
    }

    public function down() {
        $query = "DROP TABLE IF EXISTS urls;";
        
        $pdo =Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        echo "Table 'urls' dropped successfully.\n";
    }
}

