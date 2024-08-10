<?php

class Validator
{

    private $url;
    private $element;

    public function __construct($url, $element)
    {
        $this->url = $url;
        $this->element = $element;
    }

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

    function validateUrl()
    {
        // First, use the filter to validate the overall structure
        if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        // Then, check for a valid domain and TLD using regex
        $domainPattern = '/^https?:\/\/([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}/';
        return preg_match($domainPattern, $this->url);
    }

    function validateElement()
    {
        // Validate the HTML element (only letters and numbers, starting with a letter)
        return preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $this->element);
    }
}
