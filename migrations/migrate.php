<?php

// Require the migration classes
require 'create_domains_table.php';
require 'create_urls_table.php';
require 'create_elements_table.php';
require 'create_requests_table.php';

/**
 * MigrationRunner class is responsible for executing and rolling back migrations.
 * It manages a list of migration classes and provides methods to run and rollback all migrations.
 */
class MigrationRunner {

    // Array to hold instances of migration classes
    private $migrations = [];

    /**
     * Constructor to initialize the migration classes.
     */
    public function __construct() {
        // Add migration instances to the migrations array
        $this->migrations[] = new CreateDomainsTable();
        $this->migrations[] = new CreateUrlsTable();
        $this->migrations[] = new CreateElementsTable();
        $this->migrations[] = new CreateRequestsTable();
    }

    /**
     * Method to run all migrations.
     * Executes the 'up' method of each migration class to apply changes to the database.
     */
    public function run() {
        foreach ($this->migrations as $migration) {
            $migration->up();
        }
    }

    /**
     * Method to rollback all migrations.
     * Executes the 'down' method of each migration class to revert changes from the database.
     */
    public function rollback() {
        foreach ($this->migrations as $migration) {
            $migration->down();
        }
    }
}

// Create an instance of MigrationRunner
$migrationRunner = new MigrationRunner();

// Apply all migrations to the database
$migrationRunner->run(); 

// Rollback migrations if needed
// Uncomment the following line to revert all migrations
// $migrationRunner->rollback(); // Revert all migrations
