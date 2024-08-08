<?php

class Request extends Database {
    private $id;
    private $domain_id;
    private $url_id;
    private $element_id;
    private $time_taken;
    private $duration;

    public function __construct($id = null, $domain_id, $url_id, $element_id, $time_taken, $duration) {
        $this->id = $id;
        $this->domain_id = $domain_id;
        $this->url_id = $url_id;
        $this->element_id = $element_id;
        $this->time_taken = $time_taken;
        $this->duration = $duration;
    }

    public function save() {
        $query = "INSERT INTO requests (domain_id, url_id, element_id, time_taken, duration)
                  VALUES (:domain_id, :url_id, :element_id, :time_taken, :duration);";
        
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":domain_id", $this->domain_id);
        $stmt->bindParam(":url_id", $this->url_id);
        $stmt->bindParam(":element_id", $this->element_id);
        $stmt->bindParam(":time_taken", $this->time_taken);
        $stmt->bindParam(":duration", $this->duration);
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

        $query = "UPDATE requests SET domain_id = :domain_id, url_id = :url_id, element_id = :element_id, 
                  time_taken = :time_taken, duration = :duration WHERE id = :id";

        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":domain_id", $this->domain_id);
        $stmt->bindParam(":url_id", $this->url_id);
        $stmt->bindParam(":element_id", $this->element_id);
        $stmt->bindParam(":time_taken", $this->time_taken);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    public function delete() {
        if ($this->id === null) {
            throw new Exception("Cannot delete a record without an ID.");
        }

        $query = "DELETE FROM requests WHERE id = :id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();
        return $this;
    }

    public static function findOne($id) {
        $query = "SELECT * FROM requests WHERE id = :id";
        $stmt = parent::connect()->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['id'],
                $result['domain_id'],
                $result['url_id'],
                $result['element_id'],
                $result['time_taken'],
                $result['duration']
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
