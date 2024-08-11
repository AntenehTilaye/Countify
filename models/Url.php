<?php

/**
 * Represents a URL record in the database.
 * It provides methods for basic CRUD operations (Create, Read, Update, Delete) and static methods for querying the database.
 */
class Url
{
    private $id;      // ID of the URL
    private $name;    // Name of the URL

    /**
     * Constructor to initialize a Url object.
     *
     * @param string $name
     * @param int|null $id
     */
    public function __construct($name, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Save the current URL to the database.
     *
     * @return self
     */
    public function save()
    {
        $conn = Database::getInstance()->getConnection();

        // Prepare the SQL query for inserting a new record
        $query = "INSERT INTO urls (name) VALUES (:name);";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        // Get the ID of the newly inserted record
        $this->id = $conn->lastInsertId();

        // Return the current object with the updated ID
        return $this;
    }

    /**
     * Update the current URL record in the database.
     *
     * @return self
     * @throws Exception
     */
    public function update()
    {
        if ($this->id === null) {
            throw new Exception("Cannot update a record without an ID.");
        }

        $conn = Database::getInstance()->getConnection();
        $query = "UPDATE urls SET name = :name WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return $this;
    }

    /**
     * Delete the current URL record from the database.
     *
     * @return self
     * @throws Exception
     */
    public function delete()
    {
        if ($this->id === null) {
            throw new Exception("Cannot delete a record without an ID.");
        }

        $conn = Database::getInstance()->getConnection();
        $query = "DELETE FROM urls WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return $this;
    }

    /**
     * Find a URL record by its ID.
     *
     * @param int $id
     * @return self|null
     */
    public static function find($id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM urls WHERE id = :id";
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
     * Find a URL record by its name.
     *
     * @param string $name
     * @return self|null
     */
    public static function findByName($name)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM urls WHERE name = :name LIMIT 1";
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
     * Execute a custom SQL query and return the results.
     *
     * @param string $query
     * @return array
     */
    public static function select($query)
    {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the value of id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
