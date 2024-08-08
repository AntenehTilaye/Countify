<?php

class Element extends Database {
    private $id;
    private $name;

    public function __construct($id = null, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function save() {
        $query = "INSERT INTO elements (name)
                  VALUES (:name);";

        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();

        // Get the ID of the newly inserted record
        $this->id = parent::connect()->lastInsertId();

        // Return the current object with the updated ID
        return $this;
    }

    public function update() {
        if ($this->id === null) {
            throw new Exception("Cannot update a record without an ID.");
        }

        $query = "UPDATE elements SET name = :name WHERE id = :id";

        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    public function delete() {
        if ($this->id === null) {
            throw new Exception("Cannot delete a record without an ID.");
        }

        $query = "DELETE FROM elements WHERE id = :id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    public static function findOne($id) {
        $query = "SELECT * FROM elements WHERE id = :id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['id'],
                $result['name']
            );
        }
        return null;
    }

    public static function select($query) {
        $stmt = parent::connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
