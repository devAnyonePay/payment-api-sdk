
## 1. API Manual

- Link : http://anyonepay.readme.io/reference

## 2. SDK for PHP

### 2.1. Compatibility
- PHP 5.6 or higher

### 2.2. Install the SDK library
	- Place the lib directory in your source directory.
	- Include the AnyonePay.php file in your source code.
	- Path : php > lib > AnyonePay

### 2.3 Register a new payment
 - php / sample / Merchant / RegisterStub.php ( with debug message )
```
<?php
require_once __DIR__ . "/../../lib/AnyonePay/autoload.php";

use AnyonePay\AnyonePaySdk;
use AnyonePay\entity\RegisterReq;

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
    /* Mandatory */->setClientId('HVqAPkiIYVPTFlIL2ySesln83O7noj7s')
    /* Mandatory */->setClientSecret('N6BCpBUbGvQ3T1awvexzCyaUsYVWNsHL377TJ/BdsMGUjqGjqgx87pLNhv8NNFPi')

    /* Mandatory */->setStoreId(2005271259396999814)
    /* Mandatory */->setAmount(100)

    /* Optional  */->setAdditional(array(
        'firstName' => 'Tester',
        'email' => 'test@anyonepay.ph',
        'phoneNumber' => '639123456789'
    ))

    /* Mandatory */->setProduct('test_product')
    /* Optional  */->setProductItems(buildProductItem())
    /* Mandatory */->setReferenceNo('REFERENCE_A000001')

    /* Mandatory */->setRedirectUrl('https://www.yourshop.ph/payment/payment_result?productNo=123abc')
    /* Mandatory */->setWebhookUrl('https://www.yourshop.ph/payment/webhook?a=b')
    /* Mandatory */->setCancelUrl('https://www.yourshop.ph/payment/payment_cancel?productNo=123abc');

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
```
 - Testing
```
$ php RegisterStub.php

-------------- [Register Payment START] ----------------------- <br/>
-------------- [Request] -------------------------------------- <br/>
object(AnyonePay\entity\RegisterReq)#2 (11) {
  ["clientId"]=>
  string(32) "dNC0dDfi3BfufreRFaomzahdElienyqS"
  ["clientSecret"]=>
  string(32) "XWQaC399ctXeFzvzFpU4IAmH4gnR53xr"
  ["storeId"]=>
  int(2009230959474681499)
  ["amount"]=>
  int(100)
  ["additional"]=>
  array(3) {
    ["firstName"]=>
    string(6) "Tester"
    ["email"]=>
    string(18) "test@anyonepay.ph"
    ["phoneNumber"]=>
    string(12) "639123456789"
  }
  ["product"]=>
  string(12) "test_product"
  ["items"]=>
  array(2) {
    [0]=>
    array(3) {
      ["name"]=>
      string(14) "product-name-1"
      ["count"]=>
      int(1)
      ["price"]=>
      float(1)
    }
    [1]=>
    array(3) {
      ["name"]=>
      string(14) "product-name-2"
      ["count"]=>
      int(4)
      ["price"]=>
      float(2.02)
    }
  }
  ["referenceNo"]=>
  string(19) "REFERENCE1605499152"
  ["redirectUrl"]=>
  string(64) "http://testshop.anyonepay.ph/test/payResult.php?v=payment_finish"
  ["webhookUrl"]=>
  string(79) "http://testshop.anyonepay.ph/test/webhook.php?v=verifyOrCompletion&call=SANDBOX"
  ["cancelUrl"]=>
  string(66) "http://testshop.anyonepay.ph/test/payResult.php?v=payment_canceled"
}
 <br/>
-------------- [Response] ------------------------------------- <br/>
object(AnyonePay\entity\RegisterRes)#8 (3) {
  ["paymentSeq"]=>
  string(19) "2011161159107243276"
  ["referenceNo"]=>
  string(19) "REFERENCE1605499152"
  ["checkoutUrl"]=>
  string(344) "http://api-sandbox.anyonepay.ph/sandbox/web/charging-simulator.html?entry=consent&paymentSeq=2011161159107243276&cancelUrl=http%3A%2F%2Ftestshop.anyonepay.ph%2Ftest%2FpayResult.php%3Fv%3Dpayment_canceled&otr=vWuA1CYiXB0l2Ymq7Qpn1%2BlkPgzrmBd8pPKSJY71zHQs9uaI%2B0ZFhqhiR1FO%2FryfHzMN8Wixtqn7bRwTqvfn0Apa14M0jVU95CFyVXpiEieO%2Fwkx2Lgf0d3rgS2xg3Dr"
}
 <br/>
-------------- [END] -----------------------------------------  <br/>
```
### 2.4 Webhook response when you get a request to verify(to continue charging) and completion(notification of charging) from Anyonepay

- php / test / webhook.php

### 2.5. Retrieve a payment
	- php / sample / Merchant / RetrieveStub.php ( with debug message )
```
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
```
 - Testing
```
$ php RetrieveStub.php
-------------- [Retrieve a payment START] ------------------------- <br/>

-------------- [START] -------------- <br/>
-------------- [Request] -------------------------------------- <br/>
object(AnyonePay\entity\VerifyReq)#2 (3) {
  ["clientId"]=>
  string(32) "dNC0dDfi3BfufreRFaomzahdElienyqS"
  ["clientSecret"]=>
  string(32) "XWQaC399ctXeFzvzFpU4IAmH4gnR53xr"
  ["paymentSeq"]=>
  int(2011161159107243276)
}
 <br/>
-------------- [Response] ------------------------------------- <br/>
object(AnyonePay\entity\VerifyRes)#10 (9) {
  ["result"]=>
  object(stdClass)#6 (3) {
    ["result_code"]=>
    int(200)
    ["result_message"]=>
    string(34) "AOP_GET_PAYMENT_RESULT_200_SUCCESS"
    ["data"]=>
    object(stdClass)#7 (8) {
      ["paymentSeq"]=>
      string(19) "2011161159107243276"
      ["amount"]=>
      float(100)
      ["product"]=>
      string(12) "test_product"
      ["items"]=>
      array(2) {
        [0]=>
        object(stdClass)#8 (3) {
          ["name"]=>
          string(14) "product-name-1"
          ["count"]=>
          int(1)
          ["price"]=>
          int(1)
        }
        [1]=>
        object(stdClass)#9 (3) {
          ["name"]=>
          string(14) "product-name-2"
          ["count"]=>
          int(4)
          ["price"]=>
          float(2.02)
        }
      }
      ["status"]=>
      string(7) "SUCCESS"
      ["finishedTime"]=>
      string(20) "2020-11-16T04:00:27Z"
      ["createdTime"]=>
      string(20) "2020-11-16T03:59:11Z"
      ["referenceNo"]=>
      string(19) "REFERENCE1605499152"
    }
  }
  ["amount"]=>
  float(100)
  ["paymentSeq"]=>
  string(19) "2011161159107243276"
  ["status"]=>
  string(7) "SUCCESS"
  ["referenceNo"]=>
  string(19) "REFERENCE1605499152"
  ["createdTime"]=>
  string(20) "2020-11-16T03:59:11Z"
  ["finishedTime"]=>
  string(20) "2020-11-16T04:00:27Z"
  ["product"]=>
  string(12) "test_product"
  ["items"]=>
  array(2) {
    [0]=>
    object(stdClass)#8 (3) {
      ["name"]=>
      string(14) "product-name-1"
      ["count"]=>
      int(1)
      ["price"]=>
      int(1)
    }
    [1]=>
    object(stdClass)#9 (3) {
      ["name"]=>
      string(14) "product-name-2"
      ["count"]=>
      int(4)
      ["price"]=>
      float(2.02)
    }
  }
}
 <br/>
PaymentSeq : 2011161159107243276 <br/>
Amount : 100 <br/>
Status : SUCCESS <br/>
ReferenceNo : REFERENCE1605499152 <br/>
createdTime : 2020-11-16T03:59:11Z <br/>
finishedTime : 2020-11-16T04:00:27Z <br/>
Product : test_product <br/>
Items : <br/>
array(2) {
  [0]=>
  object(stdClass)#8 (3) {
    ["name"]=>
    string(14) "product-name-1"
    ["count"]=>
    int(1)
    ["price"]=>
    int(1)
  }
  [1]=>
  object(stdClass)#9 (3) {
    ["name"]=>
    string(14) "product-name-2"
    ["count"]=>
    int(4)
    ["price"]=>
    float(2.02)
  }
}
-------------- [END] -----------------------------------------  <br/>
```