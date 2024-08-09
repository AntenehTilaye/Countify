<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Request.php";
require_once __DIR__ . "/../models/Domain.php";
require_once __DIR__ . "/../models/Element.php";
require_once __DIR__ . "/../models/Url.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $url = $_POST['url'];
    $element = $_POST['element'];

    $pageDetails = fetchPageDetails($url, $element);

    if ($pageDetails) {
        echo "Load Time: " . $pageDetails['load_time'] . " ms\n" .
            "Number of <img> elements: " . $pageDetails['img_count'] . "\n" .
            "HTML Content: \n" . substr($pageDetails['html_content'], 0, 500) . "..."; // Display first 500 chars
    }
}

