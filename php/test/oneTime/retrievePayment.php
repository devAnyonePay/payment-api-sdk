<?php
    include(dirname(__FILE__) . '/basic_auth.php');
?>
<?php

/**
 * This is test file for Anyonepay team only
 */

require_once __DIR__ . "/../../lib/AnyonePay/autoload.php";

use AnyonePay\AnyonePaySdk;
use AnyonePay\oneTime\RetrieveReq;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

header("Content-type: application/json");

$data = file_get_contents('php://input');
$pm = json_decode($data);

$p_profile = $pm->{'profile'};
AnyonePaySdk::getInstance()->initConfig($p_profile);

$p_clientId = $pm->{'clientId'};
$p_clientSecret = $pm->{'clientSecret'};

$p_paymentSeq = $pm->{'paymentSeq'};

$request = new RetrieveReq();
$request
    ->setClientId($p_clientId)
    ->setClientSecret($p_clientSecret)

    ->setPaymentSeq($p_paymentSeq)
;

$response = $request->send();

if( $response->hasError() ){
    if( is_object($response->getLastError()) ){
        echo "". json_encode($response->getLastError());
        return;
    }
    echo "". json_encode($response->getLastError());
    return;
}

$respData = $response->getResponse();
echo "" . json_encode($respData->getResult()) . "\n";