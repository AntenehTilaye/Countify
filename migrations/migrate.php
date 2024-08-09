<?php

require 'create_domains_table.php';
require 'create_urls_table.php';
require 'create_elements_table.php';
require 'create_requests_table.php';

class MigrationRunner {

    private $migrations = [];

    public function __construct() {
        $this->migrations[] = new CreateDomainsTable();
        $this->migrations[] = new CreateUrlsTable();
        $this->migrations[] = new CreateElementsTable();
        $this->migrations[] = new CreateRequestsTable();
    }

    public function run() {
        foreach ($this->migrations as $migration) {
            $migration->up();
        }
    }

    public function rollback() {
        foreach ($this->migrations as $migration) {
            $migration->down();
        }
    }
}

// Run migrations
$migrationRunner = new MigrationRunner();

// Apply all migrations
$migrationRunner->run(); 

// Rollback migrations if needed
// $migrationRunner->rollback(); // Revert all migrations
