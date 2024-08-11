<?php

/**
 * A class to load a web page, measure load time, and count specific HTML elements.
 */
class PageLoader
{
    private $url;            // URL of the page to load
    private $element;        // HTML element to count (e.g., 'img')
    private $domain;         // Domain extracted from the URL
    private $loadTime;       // Time taken to load the page (in milliseconds)
    private $elementCount;   // Count of the specified HTML elements
    private $dateTime;       // Date and time when the page was loaded
    private $isError;        // Flag indicating if an error occurred
    private $errorMessage;   // Error message if an error occurred

    /**
     * Constructor to initialize a PageLoader object.
     *
     * @param string $url
     * @param string $element
     * @param float|null $loadTime
     * @param int|null $elementCount
     * @param string|null $domain
     * @param string|null $dateTime
     * @param bool $isError
     * @param string $errorMessage
     */
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

    /**
     * Fetches the page content and updates load time and element count.
     *
     * @return void
     */
    public function load()
    {
        // Initialize a cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $this->url);                              // Set the URL to fetch
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                         // Return the response as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);                         // Follow redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);                                  // Timeout after 60 seconds
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);          // Force HTTP/1.1
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'); // Set User-Agent header

        // Start measuring time
        $startTime = microtime(true);

        // Execute the cURL request
        $htmlContent = curl_exec($ch);

        // Calculate the load time
        $loadTime = round((microtime(true) - $startTime) * 1000); // Convert to milliseconds

        // Check for any cURL errors
        if (curl_errno($ch)) {
            $this->isError = true;
            $this->errorMessage = 'Error: something went wrong! Please try again later';
            curl_close($ch);
            return;
        }


        // Check if the HTML content contains the string "html"
        if (strpos($htmlContent, 'html') === false) {
            // Handle the case where the content is not valid HTML
            $this->isError = true;
            $this->errorMessage = 'invalid html content';
            curl_close($ch);
            return;
        }

        // Close the cURL session
        curl_close($ch);

        // Count the number of specified elements in the HTML content
        $elementCount = $this->countElements($htmlContent);

        $this->loadTime = $loadTime;
        $this->elementCount = $elementCount;
        $this->dateTime = date("Y-m-d H:i:s");
    }

    /**
     * Count the number of specified HTML elements in the content.
     *
     * @param string $htmlContent
     * @return int
     */
    private function countElements($htmlContent)
    {
        // Create a new DOMDocument instance
        $dom = new DOMDocument();

        // Suppress errors due to malformed HTML
        libxml_use_internal_errors(true);

        // Load the HTML content into the DOMDocument
        $dom->loadHTML($htmlContent);

        // Clear any errors generated during loading
        libxml_clear_errors();

        // Get all specified elements in the document
        $elements = $dom->getElementsByTagName($this->element);

        // Return the count of specified elements
        return $elements->length;
    }

    /**
     * Get the value of isError.
     *
     * @return bool
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * Get the value of errorMessage.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set the value of errorMessage.
     *
     * @param string $errorMessage
     * @return self
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * Get the value of loadTime.
     *
     * @return float|null
     */
    public function getLoadTime()
    {
        return $this->loadTime;
    }

    /**
     * Set the value of loadTime.
     *
     * @param float|null $loadTime
     * @return self
     */
    public function setLoadTime($loadTime)
    {
        $this->loadTime = $loadTime;
        return $this;
    }

    /**
     * Get the value of elementCount.
     *
     * @return int|null
     */
    public function getElementCount()
    {
        return $this->elementCount;
    }

    /**
     * Set the value of elementCount.
     *
     * @param int|null $elementCount
     * @return self
     */
    public function setElementCount($elementCount)
    {
        $this->elementCount = $elementCount;
        return $this;
    }

    /**
     * Get the value of dateTime.
     *
     * @return string|null
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set the value of dateTime.
     *
     * @param string|null $dateTime
     * @return self
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Get the value of domain.
     *
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the value of domain.
     *
     * @param string|null $domain
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Get the value of url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url.
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get the value of element.
     *
     * @return string
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set the value of element.
     *
     * @param string $element
     * @return self
     */
    public function setElement($element)
    {
        $this->element = $element;
        return $this;
    }
}
