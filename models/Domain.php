<?php

class Domain extends Database {
    private $id;
    private $name;

    public function __construct($name, $id = null) {
        $this->id = $id;
        $this->name = $name;
    }

    public function save() {
        $conn = Database::getInstance()->getConnection();
        $query = "INSERT INTO domains (name)
                  VALUES (:name);";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        // Get the ID of the newly inserted record
        $this->id = $conn->lastInsertId();

        // Return the current object with the updated ID
        return $this;
    }

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

    public static function select($query) {
        $conn = Database::getInstance()->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
