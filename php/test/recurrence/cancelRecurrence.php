<?php
    include(dirname(__FILE__) . '/../basic_auth.php');
?>
<?php

/**
 * This is test file for Anyonepay team only
 */

require_once __DIR__ . "/../../lib/AnyonePay/autoload.php";

use AnyonePay\AnyonePaySdk;
use AnyonePay\recurrence\CancelReq4Recurrence;

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

$p_subscriptionSeq = $pm->{'subscriptionSeq'};
$p_processByRedirectUrl = $pm->{'processByRedirectUrl'};

$p_refNo = $pm->{'refNo'};

$p_redirect = $pm->{'redirectUri'};
$p_webhook = $pm->{'webhookUri'};
$p_cancel = $pm->{'cancelUri'};

$request = new CancelReq4Recurrence();

$request
    ->setClientId($p_clientId)
    ->setClientSecret($p_clientSecret)
    
    ->setSubscriptionSeq($p_subscriptionSeq)
    ->setProcessByRedirectUrl($p_processByRedirectUrl=="TRUE"?true:false)

    ->setReferenceNo($p_refNo)

    ->setRedirectUrl($p_redirect)
    ->setWebhookUrl($p_webhook)
    ->setCancelUrl($p_cancel)
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
echo "" . $respData->serialize() . "\n";