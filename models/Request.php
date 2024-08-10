<?php

class Request
{
    private $id;
    private $domainId;
    private $urlId;
    private $elementId;
    private $elementCount;
    private $dateTime;
    private $duration;

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

    public function save()
    {
        $conn = Database::getInstance()->getConnection();

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

    public static function select($query)
    {
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
     * Get the value of domain id
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * Set the value of domain id
     *
     * @return  self
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;

        return $this;
    }

    /**
     * Get the value of urlId
     */
    public function getUrlId()
    {
        return $this->urlId;
    }

    /**
     * Set the value of urlId
     *
     * @return  self
     */
    public function setUrlId($urlId)
    {
        $this->urlId = $urlId;

        return $this;
    }

    /**
     * Get the value of elementId
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * Set the value of elementId
     *
     * @return  self
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;

        return $this;
    }

    /**
     * Get the value of element_count
     */
    public function getElementCount()
    {
        return $this->elementCount;
    }

    /**
     * Set the value of element_count
     *
     * @return  self
     */
    public function setElementCount($elementCount)
    {
        $this->elementCount = $elementCount;

        return $this;
    }

    /**
     * Get the value of date_time
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set the value of date_time
     *
     * @return  self
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get the value of duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     *
     * @return  self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }
}
