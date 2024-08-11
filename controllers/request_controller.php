<?php

/**
 * This script handles a POST request to check the presence of a specific HTML element on a given URL.
 * It validates the inputs, loads the page, checks if the domain, URL, and element are already stored in the database,
 * and returns statistics about the element's presence on the page.
 *
 * Steps:
 * 1. Validate the URL and element provided by the user.
 * 2. Load the page and extract the domain, URL, and element.
 * 3. Check if the domain, URL, and element exist in the database, and create new records if necessary.
 * 4. Check if a recent request for the same URL and element exists. If so, return the existing data.
 * 5. If no recent request exists, load the page, count the elements, and save a new request.
 * 6. Return a JSON response with the requested data and general statistics.
 */

// Include necessary files and classes
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Request.php";
require_once __DIR__ . "/../models/Domain.php";
require_once __DIR__ . "/../models/Element.php";
require_once __DIR__ . "/../models/Url.php";
require_once __DIR__ . "/../utils/PageLoader.php";
require_once __DIR__ . "/../utils/Validator.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Retrieve and trim the user inputs
    $url = trim($_POST['url']);
    $element = trim($_POST['element']);

    // Validate the inputs
    $validator = new Validator($url, $element);
    $errors = $validator->validate();

    // If there are validation errors, return them as a JSON response
    if (!empty($errors)) {
        $errors['is_error'] = true;
        $errors['error_type'] = "validation";
        echo json_encode($errors);
        return;
    }

    // create PageLoader object with the provided URL and element
    $ploader = new PageLoader($url, $element);

    // Check if the domain already exists in the database
    $domain = Domain::findByName($ploader->getDomain());
    if (!$domain) {
        // If the domain does not exist, create and save a new domain
        $domain = new Domain($ploader->getDomain());
        $domain->save();
    }

    // Check if the URL already exists in the database
    $url = Url::findByName($ploader->getUrl());
    if (!$url) {
        // If the URL does not exist, create and save a new URL
        $url = new Url($ploader->getUrl());
        $url->save();
    }

    // Check if the element already exists in the database
    $element = Element::findByName($ploader->getElement());
    if (!$element) {
        // If the element does not exist, create and save a new element
        $element = new Element($ploader->getElement());
        $element->save();
    }

    // Check if there is a recent request for the given URL and element
    $req = Request::getIfRecent($url->getId(), $element->getId());

    // If no recent request exists, proceed to load the page and save a new request
    if (!$req) {
        $ploader->load();

        // Handle any errors during page loading
        if ($ploader->isError()) {
            $res = [
                'is_error' => true,
                'error_type' => "loading",
                'error_message' => $ploader->getErrorMessage(),
            ];

            echo json_encode($res);
            return;
        }

        // Create and save a new request with the page loading data
        $req = new Request($domain->getId(), $url->getId(), $element->getId(), $ploader->getElementCount(), $ploader->getDateTime(), $ploader->getLoadTime());
        $req->save();
    }

    // Retrieve various statistics for the response
    $check_url = Request::getCheckedURL($domain->getId());
    $avg_time = Request::getAverageTime($domain->getId());
    $element_domain_count = Request::getElementCountForDomain($domain->getId(), $element->getId());
    $element_all_count = Request::getElementCountForAll($element->getId());

    // Prepare the response data
    $res = [
        'url' => $url->getName(),
        'date' => $req->getDateTime(),
        'duration' => $req->getDuration(),
        'element' => $element->getName(),
        'count' => $req->getElementCount(),
        // General statistics
        'check_url' => $check_url['num_urls'],
        'avg_time' => round($avg_time['avg_time']),
        'element_domain_count' => $element_domain_count['total_count'],
        'element_all_count' => $element_all_count['total_count'],
        'is_error' => false
    ];

    // Return the response as a JSON object
    echo json_encode($res);
}