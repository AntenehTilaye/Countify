<?php

require_once __DIR__ .'/../config/Database.php';

class CreateUrlsTable extends Database {

    public function up() {
        $query = "CREATE TABLE IF NOT EXISTS urls (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo "Table 'urls' created successfully.\n";
    }

    public function down() {
        $query = "DROP TABLE IF EXISTS urls;";
        
        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        echo "Table 'urls' dropped successfully.\n";
    }
}

