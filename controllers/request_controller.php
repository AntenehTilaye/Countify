<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Request.php";
require_once __DIR__ . "/../models/Domain.php";
require_once __DIR__ . "/../models/Element.php";
require_once __DIR__ . "/../models/Url.php";
require_once __DIR__ . "/../utils/PageLoader.php";
require_once __DIR__ . "/../utils/Validator.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $url = trim($_POST['url']);
    $element = trim($_POST['element']);

    $validator = new Validator($url, $element);
    $errors = $validator->validate();

    if(!empty($errors)){
        echo json_encode($errors);
        return;
    } 

    $ploader = new PageLoader($url, $element);


    if ($ploader->isError()) {
        echo $ploader->getErrorMessage();
        return;
    }

    $domain = Domain::findByName($ploader->getDomain());
    if(!$domain) {
        $domain = new Domain($ploader->getDomain());
        $domain->save();
    }

    $url = Url::findByName($ploader->getUrl());
    if(!$url) {
        $url = new Url($ploader->getUrl());
        $url->save();
    }

    $element = Element::findByName($ploader->getElement());
    if(!$element) {
        $element = new Element($ploader->getElement());
        $element->save();
    }

    $pre_req = Request::getIfRecent($url->getId(), $element->getId());

        
    
    if($pre_req){

        $check_url = Request::getCheckedURL($domain->getId());
        $avg_time = Request::getAverageTime($domain->getId());
        $element_domain_count = Request::getElementCountForDomain($domain->getId(), $element->getId());
        $element_all_count = Request::getElementCountForAll($element->getId());

        $res = array(
            'url'=> $url->getName(),
            'date' => $pre_req->getDateTime(),
            'duration' => $pre_req->getDuration(),
            'element' => $element->getName(),
            'count' => $pre_req->getElementCount(),
            //general stats
            'check_url' => $check_url['num_urls'],
            'avg_time' => $avg_time['avg_time'],
            'element_domain_count' => $element_domain_count['total_count'],
            'element_all_count' => $element_all_count['total_count']
        );

        echo json_encode($res);
        return;
    }

    
    $ploader->load();
    $req = new Request($domain->getId(), $url->getId(), $element->getId(), $ploader->getElementCount(), $ploader->getDateTime(), $ploader->getLoadTime());
    $req->save();

    $check_url = Request::getCheckedURL($domain->getId());
    $avg_time = Request::getAverageTime($domain->getId());
    $element_domain_count = Request::getElementCountForDomain($domain->getId(), $element->getId());
    $element_all_count = Request::getElementCountForAll($element->getId());

    $res = array(
        'url'=> $url->getName(),
        'date' => $req->getDateTime(),
        'duration' => $req->getDuration(),
        'element' => $element->getName(),
        'count' => $req->getElementCount(),
        //general stats
        'check_url' => $check_url['num_urls'],
        'avg_time' => $avg_time['avg_time'],
        'element_domain_count' => $element_domain_count['total_count'],
        'element_all_count' => $element_all_count['total_count']
    );


    echo json_encode($res);

}

