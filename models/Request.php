<?php

/**
 * Represents a request record in the database.
 * It provides methods for basic CRUD operations (Create, Read, Update, Delete) and static methods for querying the database.
 */
class Request
{
    // Private properties of the class
    private $id;            // ID of the request
    private $domainId;      // Domain ID associated with the request
    private $urlId;         // URL ID associated with the request
    private $elementId;     // Element ID associated with the request
    private $elementCount;  // Number of elements in the request
    private $dateTime;      // Date and time of the request
    private $duration;      // Duration of the request

    /**
     * Constructor to initialize a Request object.
     *
     * @param int $domainId
     * @param int $urlId
     * @param int $elementId
     * @param int $elementCount
     * @param string $dateTime
     * @param int $duration
     * @param int|null $id
     */
    public function __construct($domainId, $urlId, $elementId, $elementCount, $dateTime, $duration, $id = null)
    {
        $this->id = $id;
        $this->domainId = $domainId;
        $this->urlId = $urlId;
        $this->elementId = $elementId;
        $this->elementCount = $elementCount;
        $this->dateTime = $dateTime;
        $this->duration = $duration;
    }

    /**
     * Save the current request to the database.
     *
     * @return self
     */
    public function save()
    {
        $conn = Database::getInstance()->getConnection();

        // Prepare the SQL query for inserting a new record
        $query = "INSERT INTO requests (domain_id, url_id, element_id, element_count, date_time, duration)
                  VALUES (:domain_id, :url_id, :element_id, :element_count, :date_time, :duration);";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":domain_id", $this->domainId);
        $stmt->bindParam(":url_id", $this->urlId);
        $stmt->bindParam(":element_id", $this->elementId);
        $stmt->bindParam(":element_count", $this->elementCount);
        $stmt->bindParam(":date_time", $this->dateTime);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->execute();

        // Get the ID of the newly inserted record
        $this->id = $conn->lastInsertId();

        // Return the current object with the updated ID
        return $this;
    }

    /**
     * Update the current request record in the database.
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
        $query = "UPDATE requests SET domain_id = :domain_id, url_id = :url_id, element_id = :element_id, element_count = :element_count, 
                  date_time = :date_time, duration = :duration WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":domain_id", $this->domainId);
        $stmt->bindParam(":url_id", $this->urlId);
        $stmt->bindParam(":element_id", $this->elementId);
        $stmt->bindParam(":element_count", $this->elementCount);
        $stmt->bindParam(":date_time", $this->dateTime);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        return $this;
    }

    /**
     * Delete the current request record from the database.
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
        $query = "DELETE FROM requests WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        return $this;
    }

    /**
     * Find a request record by its ID.
     *
     * @param int $id
     * @return self|null
     */
    public static function find($id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM requests WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['domain_id'],
                $result['url_id'],
                $result['element_id'],
                $result['element_count'],
                $result['date_time'],
                $result['duration'],
                $result['id']
            );
        }
        return null;
    }

    /**
     * Get the most recent request record for the given URL and element IDs.
     *
     * @param int $url_id
     * @param int $element_id
     * @return self|null
     */
    public static function getIfRecent($url_id, $element_id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT * FROM requests WHERE (element_id = :element_id AND url_id = :url_id) 
                  AND (TIMESTAMPDIFF(MINUTE, date_time, :current_time) < 5) LIMIT 1";

        $current_time = date('Y-m-d H:i:s');
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":url_id", $url_id, PDO::PARAM_INT);
        $stmt->bindParam(":element_id", $element_id, PDO::PARAM_INT);
        $stmt->bindParam(":current_time", $current_time);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new self(
                $result['domain_id'],
                $result['url_id'],
                $result['element_id'],
                $result['element_count'],
                $result['date_time'],
                $result['duration'],
                $result['id']
            );
        }
        return null;
    }

    /**
     * Get the number of distinct URLs checked for a given domain.
     *
     * @param int $domain_id
     * @return array
     */
    public static function getCheckedURL($domain_id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT COUNT(DISTINCT urls.name) AS num_urls FROM requests JOIN urls ON (urls.id = requests.url_id)
                  WHERE requests.domain_id = :domain_id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":domain_id", $domain_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * Get the average duration of requests for a given domain in the last day.
     *
     * @param int $domain_id
     * @return array
     */
    public static function getAverageTime($domain_id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT AVG(duration) AS avg_time FROM requests 
                  WHERE requests.domain_id = :domain_id AND 
                  requests.date_time >= :current_time - INTERVAL 1 DAY";

        $current_time = date('Y-m-d H:i:s');
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":domain_id", $domain_id, PDO::PARAM_INT);
        $stmt->bindParam(":current_time", $current_time, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * Get the total element count for a given domain and element ID.
     *
     * @param int $domain_id
     * @param int $element_id
     * @return array
     */
    public static function getElementCountForDomain($domain_id, $element_id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT SUM(r.element_count) AS total_count
                    FROM requests r
                    JOIN (
                        SELECT url_id, MAX(date_time) AS latest_date_time
                        FROM requests
                        WHERE domain_id = :domain_id AND element_id = :element_id
                        GROUP BY url_id
                    ) latest_requests
                    ON r.url_id = latest_requests.url_id
                    AND r.date_time = latest_requests.latest_date_time
                    WHERE r.domain_id = :domain_id AND r.element_id = :element_id;
                    ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":domain_id", $domain_id, PDO::PARAM_INT);
        $stmt->bindParam(":element_id", $element_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * Get the total element count for all URLs with a given element ID.
     *
     * @param int $element_id
     * @return array
     */
    public static function getElementCountForAll($element_id)
    {
        $conn = Database::getInstance()->getConnection();
        $query = "SELECT SUM(r.element_count) AS total_count
                    FROM requests r
                    JOIN (
                        SELECT url_id, MAX(date_time) AS latest_date_time
                        FROM requests
                        WHERE element_id = :element_id
                        GROUP BY url_id
                    ) latest_requests
                    ON r.url_id = latest_requests.url_id
                    AND r.date_time = latest_requests.latest_date_time
                    WHERE r.element_id = :element_id;
                    ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":element_id", $element_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
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

    // Getter and Setter methods for private properties

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
     * Get the value of domain id.
     *
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * Set the value of domain id.
     *
     * @param int $domainId
     * @return self
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        return $this;
    }

    /**
     * Get the value of urlId.
     *
     * @return int
     */
    public function getUrlId()
    {
        return $this->urlId;
    }

    /**
     * Set the value of urlId.
     *
     * @param int $urlId
     * @return self
     */
    public function setUrlId($urlId)
    {
        $this->urlId = $urlId;
        return $this;
    }

    /**
     * Get the value of elementId.
     *
     * @return int
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * Set the value of elementId.
     *
     * @param int $elementId
     * @return self
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;
        return $this;
    }

    /**
     * Get the value of element_count.
     *
     * @return int
     */
    public function getElementCount()
    {
        return $this->elementCount;
    }

    /**
     * Set the value of element_count.
     *
     * @param int $elementCount
     * @return self
     */
    public function setElementCount($elementCount)
    {
        $this->elementCount = $elementCount;
        return $this;
    }

    /**
     * Get the value of date_time.
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set the value of date_time.
     *
     * @param string $dateTime
     * @return self
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Get the value of duration.
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration.
     *
     * @param int $duration
     * @return self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }
}
