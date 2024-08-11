<?php

/**
 * Domain class represents a domain entity in the database.
 * It provides methods for basic CRUD operations (Create, Read, Update, Delete) and static methods for querying the database.
 */
class Domain {
    private $id; // Unique identifier for the domain
    private $name; // Name of the domain

    /**
     * Constructor to initialize a Domain object.
     * 
     * @param string $name The name of the domain.
     * @param int|null $id Optional ID of the domain. If not provided, it will be set to null.
     */
    public function __construct($name, $id = null) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Save the domain to the database. 
     * Inserts a new record into the 'domains' table and updates the object's ID with the new record's ID.
     * 
     * @return Domain The current Domain object with the updated ID.
     */
    public function save() {
        $conn = Database::getInstance()->getConnection();
        $query = "INSERT INTO domains (name) VALUES (:name);";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        // Get the ID of the newly inserted record
        $this->id = $conn->lastInsertId();

        // Return the current object with the updated ID
        return $this;
    }

    /**
     * Update the domain in the database.
     * Requires the domain to have an ID set. Throws an exception if the ID is not set.
     * 
     * @return Domain The current Domain object.
     * @throws Exception If the ID is null.
     */
    public function update() {
        if ($this->id === null) {
            throw new Exception("Cannot update a record without an ID.");
        }

        $conn = Database::getInstance()->getConnection();
        $query = "UPDATE domains SET name = :name WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    /**
     * Delete the domain from the database.
     * Requires the domain to have an ID set. Throws an exception if the ID is not set.
     * 
     * @return Domain The current Domain object.
     * @throws Exception If the ID is null.
     */
    public function delete() {
        if ($this->id === null) {
            throw new Exception("Cannot delete a record without an ID.");
        }

        $conn = Database::getInstance()->getConnection();
        $query = "DELETE FROM domains WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    /**
     * Find a domain by its ID.
     * 
     * @param int $id The ID of the domain to find.
     * @return Domain|null The Domain object if found, or null if not found.
     */
    public static function find($id) {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM domains WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['name'],
                $result['id']
            );
        }
        return null;
    }

    /**
     * Find a domain by its name.
     * 
     * @param string $name The name of the domain to find.
     * @return Domain|null The Domain object if found, or null if not found.
     */
    public static function findByName($name) {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM domains WHERE name = :name LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['name'],
                $result['id']
            );
        }
        return null;
    }

    /**
     * Execute a custom select query.
     * 
     * @param string $query The SQL query to execute.
     * @return array An array of results from the query.
     */
    public static function select($query) {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the value of id
     * 
     * @return int|null The ID of the domain.
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     * 
     * @param int $id The ID to set.
     * @return self The current Domain object.
     */ 
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of name
     * 
     * @return string The name of the domain.
     */ 
    public function getName() {
        return $this->name;
    }

    /**
     * Set the value of name
     * 
     * @param string $name The name to set.
     * @return self The current Domain object.
     */ 
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}
