<?php
/*
    This API feature is load / saving memory storage values by webhook-config.php html page
    When you want to save(POST) requires BasicAuth
*/
    include(dirname(__FILE__) . '/basic_auth.php');
    include(dirname(__FILE__) . '/shared_cache.php');

    use AnyonePayTest\TestCache;

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    header("Content-type: application/json");

    $rMethod = $_SERVER['REQUEST_METHOD'];
    if ($rMethod === 'POST') {
        // The request is using the POST method
        $data = file_get_contents('php://input');
        TestCache::getInstance()->save_cache(json_decode($data), 'webhook');
        echo $data;
        return;
    }

    if ($rMethod === 'GET') {
        $d = TestCache::getInstance()->get_cache('webhook');
        if( $d == "" ){
            echo "[]";
            return;
        }
        echo $d;
        return;
    }

?>