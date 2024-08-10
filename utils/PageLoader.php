<?php

class PageLoader
{

    private $url;
    private $element;
    private $domain;
    private $loadTime;
    private $elementCount;
    private $dateTime;
    private $isError;
    private $errorMessage;
    

    public function __construct($url, $element, $loadTime = null, $elementCount = null, $domain = null, $dateTime = null, $isError = false, $errorMessage = "")
    {
        
        $parsedUrl = parse_url($url);

        $this->url = $url;
        $this->element = $element;
        $this->domain = $parsedUrl['host'];
        $this->loadTime = $loadTime;
        $this->elementCount = $elementCount;
        $this->dateTime = $dateTime;
        $this->isError = $isError;
        $this->errorMessage = $errorMessage;
    }

    //fetch Page Details
    public function load()
    {
        // Initialize a cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $this->url);                           // Set the URL to fetch
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                // Return the response as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);                // Follow redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);                         // Timeout after 30 seconds
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // Force HTTP/1.1
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);               // Disable SSL peer verification
        // add user agent header (some servers may block requests that do not contian a proposer user-agent header like colnect.com)
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        // curl_setopt($ch, CURLOPT_VERBOSE, true); // just to see what is going on during the request


        // Start measuring time
        $startTime = microtime(true);

        // Execute the cURL request
        $htmlContent = curl_exec($ch);

        // Calculate the load time
        $loadTime = round((microtime(true) - $startTime) * 1000); // Convert to milliseconds

        // Check for any cURL errors
        if (curl_errno($ch)) {
            $this->isError = true;
            $this->errorMessage = 'Error: something went wrong! Please try Again later'; // . curl_error($ch);
            curl_close($ch);
        }

        // Close the cURL session
        curl_close($ch);

        // Count the number of <img> elements in the HTML content
        $elementCount = $this->countImgElements($htmlContent);

        $this->loadTime = $loadTime;
        $this->elementCount = $elementCount;
        $this->dateTime = date("Y-m-d H:i:s");
    }

    private function countImgElements($htmlContent)
    {
        // Create a new DOMDocument instance
        $dom = new DOMDocument();

        // Suppress errors due to malformed HTML
        libxml_use_internal_errors(true);

        // Load the HTML content into the DOMDocument
        $dom->loadHTML($htmlContent);

        // Clear any errors generated during loading
        libxml_clear_errors();

        // Get all <img> elements in the document
        $imgTags = $dom->getElementsByTagName($this->element);

        // Return the count of <img> elements
        return $imgTags->length;
    }

    /**
     * Get the value of isError
     */ 
    public function isError()
    {
        return $this->isError;
    }

    /**
     * Get the value of errorMessage
     */ 
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set the value of errorMessage
     *
     * @return  self
     */ 
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Get the value of loadTime
     */ 
    public function getLoadTime()
    {
        return $this->loadTime;
    }

    /**
     * Set the value of loadTime
     *
     * @return  self
     */ 
    public function setLoadTime($loadTime)
    {
        $this->loadTime = $loadTime;

        return $this;
    }

    /**
     * Get the value of elementCount
     */ 
    public function getElementCount()
    {
        return $this->elementCount;
    }

    /**
     * Set the value of elementCount
     *
     * @return  self
     */ 
    public function setElementCount($elementCount)
    {
        $this->elementCount = $elementCount;

        return $this;
    }

    /**
     * Get the value of dateTime
     */ 
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set the value of dateTime
     *
     * @return  self
     */ 
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get the value of domain
     */ 
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the value of domain
     *
     * @return  self
     */ 
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get the value of url
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */ 
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of element
     */ 
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set the value of element
     *
     * @return  self
     */ 
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }
}
