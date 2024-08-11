<?php

/**
 * Element class represents an element entity in the database.
 * It provides methods for basic CRUD operations (Create, Read, Update, Delete) and static methods for querying the database.
 */
class Element {
    private $id; // Unique identifier for the element
    private $name; // Name of the element

    /**
     * Constructor to initialize an Element object.
     * 
     * @param string $name The name of the element.
     * @param int|null $id Optional ID of the element. If not provided, it will be set to null.
     */
    public function __construct($name, $id = null) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Save the element to the database. 
     * Inserts a new record into the 'elements' table and updates the object's ID with the new record's ID.
     * 
     * @return Element The current Element object with the updated ID.
     */
    public function save() {
        $conn = Database::getInstance()->getConnection();

        $query = "INSERT INTO elements (name) VALUES (:name);";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        // Get the ID of the newly inserted record
        $this->id = $conn->lastInsertId();

        // Return the current object with the updated ID
        return $this;
    }

    /**
     * Update the element in the database.
     * Requires the element to have an ID set. Throws an exception if the ID is not set.
     * 
     * @return Element The current Element object.
     * @throws Exception If the ID is null.
     */
    public function update() {
        if ($this->id === null) {
            throw new Exception("Cannot update a record without an ID.");
        }

        $conn = Database::getInstance()->getConnection();
        $query = "UPDATE elements SET name = :name WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    /**
     * Delete the element from the database.
     * Requires the element to have an ID set. Throws an exception if the ID is not set.
     * 
     * @return Element The current Element object.
     * @throws Exception If the ID is null.
     */
    public function delete() {
        if ($this->id === null) {
            throw new Exception("Cannot delete a record without an ID.");
        }

        $conn = Database::getInstance()->getConnection();
        $query = "DELETE FROM elements WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    /**
     * Find an element by its ID.
     * 
     * @param int $id The ID of the element to find.
     * @return Element|null The Element object if found, or null if not found.
     */
    public static function find($id) {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM elements WHERE id = :id";
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
     * Find an element by its name.
     * 
     * @param string $name The name of the element to find.
     * @return Element|null The Element object if found, or null if not found.
     */
    public static function findByName($name) {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM elements WHERE name = :name LIMIT 1";
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
     * Get the value of name
     * 
     * @return string The name of the element.
     */ 
    public function getName() {
        return $this->name;
    }

    /**
     * Set the value of name
     * 
     * @param string $name The name to set.
     * @return self The current Element object.
     */ 
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of id
     * 
     * @return int|null The ID of the element.
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     * 
     * @param int $id The ID to set.
     * @return self The current Element object.
     */ 
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
}
