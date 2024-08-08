<?php

require_once "../config/Database.php";
require_once "../models/Request.php";
require_once "../models/Domain.php";
require_once "../models/Element.php";
require_once "../models/Url.php";

if($_SERVER['REQUEST_METHOD']==="POST") {
    $url = $_POST['url'];
    $element = $_POST['element'];


    
}