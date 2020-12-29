<?php
require_once __DIR__ . "/../../lib/AnyonePay/autoload.php";

use AnyonePay\AnyonePaySdk;
use AnyonePay\entity\VerifyReq;

// AnyonePaySdk::getInstance()->initConfig("PRODUCTION");
AnyonePaySdk::getInstance()->initConfig("SANDBOX");

echo "-------------- [Retrieve a payment START] ------------------------- <br/> \n";
echo "\n-------------- [START] -------------- <br/> \n";

$request = new VerifyReq();
$request
    /* Mandatory */->setClientId('dNC0dDfi3BfufreRFaomzahdElienyqS')
    /* Mandatory */->setClientSecret('XWQaC399ctXeFzvzFpU4IAmH4gnR53xr')

    /* Mandatory */->setPaymentSeq(2011161159107243276)
;

echo "-------------- [Request] -------------------------------------- <br/> \n";
echo var_dump($request) . " <br/> \n";
$response = $request->send();
echo "-------------- [Response] ------------------------------------- <br/> \n";

if ($response->hasError()) {
    echo var_dump($response->getLastError()) . " <br/> \n";
    return;
}

$respData = $response->getResponse();

echo var_dump($respData) . " <br/> \n";

echo "PaymentSeq : ".$respData->getPaymentSeq()." <br/> \n";
echo "Amount : ".$respData->getAmount()." <br/> \n";
echo "Status : ".$respData->getStatus()." <br/> \n";
echo "ReferenceNo : ".$respData->getReferenceNo()." <br/> \n";
echo "createdTime : ".$respData->getCreatedTime()." <br/> \n";
echo "finishedTime : ".$respData->getFinishedTime()." <br/> \n";
echo "Product : ".$respData->getProduct()." <br/> \n";
echo "Items : <br/> \n";
    var_dump($respData->getItems());
    
echo "-------------- [END] -----------------------------------------  <br/> \n";
?>