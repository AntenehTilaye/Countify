<?php

require_once __DIR__ .'/../config/Database.php';


class CreateDomainsTable extends Database {

    public function up() {
        $query = "CREATE TABLE IF NOT EXISTS domains (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );";

        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo "Table 'domains' created successfully.\n";
    }

    public function down() {
        $query = "DROP TABLE IF EXISTS domains;";
        
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        echo "Table 'domains' dropped successfully.\n";
    }
}

