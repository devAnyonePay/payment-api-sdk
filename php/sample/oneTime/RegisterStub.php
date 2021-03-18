<?php
require_once __DIR__ . "/../../lib/AnyonePay/autoload.php";

use AnyonePay\AnyonePaySdk;
use AnyonePay\oneTime\RegisterReq;

// AnyonePaySdk::getInstance()->initConfig("PRODUCTION");
AnyonePaySdk::getInstance()->initConfig("SANDBOX");

echo "-------------- [Register Payment START] ----------------------- <br/> \n";

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


$request = new RegisterReq();
$request
    /* Mandatory */->setClientId('dNC0dDfi3BfufreRFaomzahdElienyqS')
    /* Mandatory */->setClientSecret('XWQaC399ctXeFzvzFpU4IAmH4gnR53xr')

    /* Mandatory */->setStoreId(2009230959474681499)
    /* Mandatory */->setBillingMethod('ONE_TIME')
    /* Mandatory */->setAmount(100)

    /* Optional  */->setAdditional(array(
        'firstName' => 'John',
        'middleName' => 'F',
        'lastName' => 'Kenedy',

        'email' => 'test@anyonepay.ph',
        'phone' => '639123456789',

        'billingAddress' => array(
            'province' => 'Metro Manila',
            'city' => 'Makati',
            'street' => 'Street01',
            'addr1' => 'building A',
            'postCode' => '123456',
        )
    ))

    /* Mandatory */->setProduct('test_product')
    /* Optional  */->setProductItems(buildProductItem())
    /* Mandatory */->setReferenceNo('REFERENCE'.strval((new DateTime())->getTimestamp()))

    /* -- Replace host to yours ex) 'https://www.yourshop.ph/payment/payment_result?productNo=123abc' */
    /* Mandatory */->setRedirectUrl('http://testshop.anyonepay.ph/test/payResult.php?v=payment_finish')
    /* Mandatory */->setWebhookUrl('http://testshop.anyonepay.ph/test/webhook.php?v=verifyOrCompletion&call=SANDBOX')
    /* Mandatory */->setCancelUrl('http://testshop.anyonepay.ph/test/payResult.php?v=payment_canceled');

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
echo "-------------- [END] -----------------------------------------  <br/> \n";
?>