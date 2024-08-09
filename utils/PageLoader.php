<?php

class PageLoader {

    private $url;
    private $element;
    private $load_time;
    private $element_count;
    private $date;
    private $is_error;
    private $error_message;

    public function __construct($url, $element, $load_time=null, $element_count=null, $date=null, $is_error=false, $error_message="")
    {
        $this->url = $url;
        $this->element = $element;
        $this->load_time = $load_time;
        $this->element_count = $element_count;
        $this->date = $date;
        $this->is_error = $is_error;
        $this->error_message = $error_message;
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
            echo 'Error: ' . curl_error($ch);
            curl_close($ch);
            return null;
        }

        // Close the cURL session
        curl_close($ch);

        // Count the number of <img> elements in the HTML content
        $elementCount = $this->countImgElements($htmlContent);

        // Return the load time, HTML content, and image count as an array
        return [
            'load_time' => $loadTime,
            'html_content' => $htmlContent,
            'element_count' => $elementCount
        ];
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
}