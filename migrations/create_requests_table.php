<?php

require_once __DIR__ .'/../config/Database.php';

class CreateRequestsTable extends Database {

    public function up() {
        $pdo = Database::getInstance()->getConnection();

        // Create the requests table
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

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo "Table 'requests' created successfully with foreign keys.\n";
    }

    public function down() {
        $query = "DROP TABLE IF EXISTS requests;";
        
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        echo "Table 'requests' dropped successfully.\n";
    }
}
