<?php

/**
 * A class to validate a URL and an HTML element name.
 */
class Validator
{
    private $url;        // URL to be validated
    private $element;    // HTML element name to be validated

    /**
     * Constructor to initialize a Validator object.
     *
     * @param string $url
     * @param string $element
     */
    public function __construct($url, $element)
    {
        $this->url = $url;
        $this->element = $element;
    }

    /**
     * Validate the URL and HTML element name.
     *
     * @return array An array of errors, if any
     */
    public function validate()
    {
        $errors = array();

        // Validate URL
        if (!$this->validateUrl()) {
            $errors['url'] = "Invalid URL provided.";
        }

        // Validate HTML Element
        if (!$this->validateElement()) {
            $errors['element'] = "Invalid HTML element name provided.";
        }

        return $errors;
    }

    /**
     * Validate the URL format.
     *
     * @return bool True if the URL is valid, false otherwise
     */
    function validateUrl()
    {
        // Use filter_var to validate the overall structure of the URL
        if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        // Check for a valid domain and TLD using regex
        $domainPattern = '/^https?:\/\/([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}/';
        return preg_match($domainPattern, $this->url);
    }

    /**
     * Validate the HTML element name.
     *
     * @return bool True if the element name is valid, false otherwise
     */
    function validateElement()
    {
        // Validate the HTML element name (only letters and numbers, starting with a letter)
        return preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $this->element);
    }
}
