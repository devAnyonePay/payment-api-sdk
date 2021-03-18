<?php
    include(dirname(__FILE__) . '/../basic_auth.php');
?>
<?php

/**
 * This is test file for Anyonepay team only
 */

require_once __DIR__ . "/../../lib/AnyonePay/autoload.php";

use AnyonePay\AnyonePaySdk;
use AnyonePay\recurrence\RegisterReq4Recurrence;

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

$p_storeId = $pm->{'storeId'};
$p_billingMethod = $pm->{'billingMethod'};

$p_pgChannel = $pm->{'pgChannel'};
$p_interval = $pm->{'interval'};
$p_intervalCount = $pm->{'intervalCount'};
$p_startDate = $pm->{'startDate'};

$p_amount = $pm->{'amount'};

$p_firstName = $pm->{'firstName'};
$p_middleName = $pm->{'middleName'};
$p_lastName = $pm->{'lastName'};

$p_email = $pm->{'email'};
$p_phone = $pm->{'phone'};

$p_product = $pm->{'product'};
$p_productDescription = $pm->{'productDescription'};
$p_refNo = $pm->{'refNo'};

$p_redirect = $pm->{'redirectUri'};
$p_webhook = $pm->{'webhookUri'};
$p_cancel = $pm->{'cancelUri'};

function buildBillingAddress($pm)
{
    return array(
        'province' => $pm->{'province'},
        'city' => $pm->{'city'},
        'street' => $pm->{'street'},
        'addr1' => $pm->{'addr1'},
        'postCode' => $pm->{'postCode'},
    );
}

function buildProductItem()
{
    return array(
        0 =>
        array(
            'name' => 'product-name-1',
            'count' => 1,
            'price' => 1.00,
        ),
        1 =>
        array(
            'name' => 'product-name-2',
            'count' => 4,
            'price' => 2.02,
        ),
    );
}

function buildCheckoutOptions($pm)
{
    if( $pm->{'pgChannel'} ){
        return array(
            'directPay' => array(
                'pgChannel' => $pm->{'pgChannel'},
            ),
        );
    }
    return array(
        'none' => ''
    );
}

$request = new RegisterReq4Recurrence();
$request
    ->setClientId($p_clientId)
    ->setClientSecret($p_clientSecret)
    
    ->setStoreId($p_storeId)
    ->setBillingMethod($p_billingMethod)

    ->setInterval($p_interval)
    ->setIntervalCount($p_intervalCount)
    ->setStartDate($p_startDate)

    ->setAmount($p_amount)
    // Optional --------------------------------
    ->setAdditional(array(
        'firstName' => $p_firstName,
        'middleName' => $p_middleName,
        'lastName' => $p_lastName,

        'email' => $p_email,
        'phone' => $p_phone,

        'billingAddress' => buildBillingAddress($pm),
        'checkoutOptions' => buildCheckoutOptions($pm),
    ))
    // Optional --------------------------------
    ->setProduct($p_product)
    ->setProductDescription($p_productDescription)
    ->setProductItems(buildProductItem()) // Optional
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