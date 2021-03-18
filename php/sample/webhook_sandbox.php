<?php

namespace Sandbox;
use Exception;

function verifyByPublicKey($data, $signature, $pubkey)
{
  //verify signature
  $ok = openssl_verify($data, $signature, $pubkey, OPENSSL_ALGO_SHA1);
  if ($ok == 1) {
    return true;
  } elseif ($ok == 0) {
    return false;
  } else {
    throw new Exception('Unable to verify data. Please use valid public key. '.openssl_error_string());
  }
}

function signByPrivateKey($data, &$signature, $privkey)
{
  //create signature
  openssl_sign($data, $signature, $privkey, "sha1WithRSAEncryption");
}

// ---------------------------------------------------------------------------------------------------------------------
// To verify web-hook request with Anyonepay's public Key.
$anyonePayPublicKeySandbox = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA/GyfjTokNNVRjpkfVXWHv4uNJEI5OxelxruK2FNvGC4YEjE2OLjnLJe4CL5CJ45zyVLj7sK+qq8K/cErL44SeU22SCIjVRrcGmYnfuUncl5hdTF72rd95dlZ0qUFWUTebBaM+WRBwGy6tYGevDxBiXB9/4fQvPIqzITOS2IFICtObnmT0Bb+3+0mnLe8EXM8/gpyXTteiNLW3cGjqz7802eerEvhbb4R73WMyMq75FMZxNgt1J7+gJznXPByJ3qkVg09Fa8VPe70fP1IoQxGbu+2lbr+poC2As1+05QZcRN86yZ1kcEW9o3Dto4eNQ5cttFR9YOQktFEo9WMyqKwsQIDAQAB";

// --------------------
$anyonepayPublicKey = "-----BEGIN PUBLIC KEY-----"."\n".$anyonePayPublicKeySandbox."\n"."-----END PUBLIC KEY-----";

// ---------------------------------------------------------------------------------------------------------------------
// When you reply of request, use your own public, private key pair.
$yourPublicKey1  = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwbYDmL0TrDmuPPRwnW+OLdvzuvogxbUNZbVL5+hTeoaWv/xmbbPwFAc3w/N6djzKsUtWa4ZWUhfZ4HIoKmqedfO2QhrMBFsXFQ61qYc5cvLUPuaaqONZ69of+6Y6gjJRLboLb5jGmh+AQE+zv/WDUg73fxPRav0OxzmuFiZAGGt06OyEbHzSMKN40z+0wyx95jUS911i4GQZQ2AsJ2COEQmVWGDmsIYWtQD/wfV060bDL+SALVXbOH3R8oHvIdq2Rob0FXZvHfOgMR/QDFIVU7C+sGz/J+DXo1TLd/W/l8dRZLTC3ZmLHyDU/DZWm60vSss4c2KskDSVXCn0I7z9PQIDAQAB";
$yourPrivateKey1 = "MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDBtgOYvROsOa489HCdb44t2/O6+iDFtQ1ltUvn6FN6hpa//GZts/AUBzfD83p2PMqxS1ZrhlZSF9ngcigqap5187ZCGswEWxcVDrWphzly8tQ+5pqo41nr2h/7pjqCMlEtugtvmMaaH4BAT7O/9YNSDvd/E9Fq/Q7HOa4WJkAYa3To7IRsfNIwo3jTP7TDLH3mNRL3XWLgZBlDYCwnYI4RCZVYYOawhha1AP/B9XTrRsMv5IAtVds4fdHyge8h2rZGhvQVdm8d86AxH9AMUhVTsL6wbP8n4NejVMt39b+Xx1FktMLdmYsfINT8NlabrS9KyzhzYqyQNJVcKfQjvP09AgMBAAECggEAGgmGGDyp7UdfhS/yBydcfAuJikNerlR0tYwV5NkUh26Zyrp5Zht+lKKXu0350vS+5e/TcOaAqc4s6f9OKM1l+ULF58LEU32O77SndF8JuMqs/rtuZG8SwiHylJe2W5O516SfLuPhJgw/5+UOqymr+haq67jVOEVFfD/jh3SivuVOSKiqT1HeFmdesbwmMwKxdGks8h3kvtsl1ao38mErx/yljIRwDEzL/CIC0kKreykqQ6sdJQOZrivzLezOVCtyF4w6zYYSiyxSbEwBUkVmxd+0dzJ+K7lhc/zwbKma0LWKt+dsdSxCBP/l48Snw/6EhE21MeDdd5b3k1vXL1vp4QKBgQD/MP0g2luzbRIKuH7/i4ilX571w6OTx68eNhDPzIl17Z9pCDPlVybFgX8YtTzgmXwe5DfdcrjowD6PDeFmBz4qYKcvsB/HivqdHnsL9Zbv65eu/NbuHn1qzcGoeL8U+kG904NqvFD0FfFYkvdkdcJdb25PP9Sh2BdoqvYboFdleQKBgQDCUycDUhLH+raCYtuttwSxdudyKyDdK14J4O6ZKZttT+Udi5s3ydCIAzymq9iGbuRgrNCebJdY41G76FF7VGRGwvZWaAt4qYcw6dRKx4SvJg6W5aF6FijGRh4JhZd0lNnmSplX/xY4qq5P7ZqPb/xqfc72LLlGXlxC+QAovtL45QKBgQCWC7HqDTn559nB3/UcQGjaA70teBjIHZMDynpcFEGGy279jMsmv/WWD6wxVO80iEqIx1tpo7jBcbfW2Sx8lymgNTBYQ9CLvVXZ6EAlKgPTkcygIR4m6FqJ3+1MJ6MCrJrXCHSu34ch3X0IvXEk6nCcC5bqlfdqKeqpqkwGtmJs0QKBgQCDStkmBjLISJ09FPvlr60NFhAj8/29UMq1k/LBKvpnbb6YKrXRiJpK+xzkY2Dk21GT+87r6uSK2vNjKQC8efVSVfFHyf5OJQRBSQr1Bz6fc/ARhc6HBSfBZqhuuILu8o8SaD6Y1LE30iH5wMhXt0rszFmju+3mHGTrnv67iMpT/QKBgQDUju3lqJshD1FYN5OZa6l6Dq1jZL8eID7OPDM+hbxXeIwbBSOFmlvAwO8KNVgeMP/rMCAOoaW9BMZAmzye3zWc1dnVBV3vybUIwMZtKqjvjGLNqNtGiBFSGlZdPTRxoYwxd2DtBpouZ04MReqCUAeJGIN1lMotWGPqVzlW8Hwg9Q==";
// ---------------------------------------------------------------------------------------------------------------------

// To send reply web-hook of request from Anyonepay
$merchantPrivateKey = "-----BEGIN PRIVATE KEY-----"."\n".$yourPrivateKey1."\n"."-----END PRIVATE KEY-----";

// ---------------------------------------------------------------------------------------------------------------------
$data = file_get_contents('php://input');
$pm = json_decode($data);
// ---------------------------------------------------------------------------------------------------------------------
/* 
-- incoming with original data (for debug)
{
  "type": "COMPLETION",
  "publicKeySha1": "735a4f12c08062791526eb7d77ce304a9c7e4efb",
  "data": "{\"webhookSeq\":\"2010151634399080988\",\"paymentSeq\":\"2010151634399080917\",\"storeId\":\"2009230959474681499\",\"amountInitial\":20.00,\"amount\":20.00,\"referenceNo\":\"123456789\",\"createdTime\":\"2020-10-15T08:34:39Z\",\"finishedTime\":\"2020-10-20T18:00:02Z\"}",
  "signature": "zGmhVg4jcnlI/nm/T2B9axSAuK38PE5YGm4yqFhJ8uG7P3pByWp0yGWyRGM4WLURea2AfJmRDyJQyjH+d6UwvuzRM0tNTwlqQmk6rxyhDkySf94wFZpDKLpj4wI20YvRFekfGw3Fp9/Q8HOOIwvFMADMDzPV+zfLtKTthxcVptv4rvb4uyzzm8oA7PwsW2pmJmWIxPP0leeYC8YgN7knAwU4fb80Xtx+3gJuQ1o/B4+I7lGZIwp8wqAY6hqP7T/sllO/FjpGk6nfHMZfeuE0gh1phhReJJACyJmRCwSEqaSumQVz5hPINbbUOMHvttaeG4ZevH6Iu0qvHgwmK6roxw=="
}
*/

/* 
-- outgoing with original data (for debug)
{
  "data": "{\"result\":\"SUCCESS\",\"webhookSeq\":\"2010151634399080988\"}",
  "publicKeySha1": "b27b82f593158837eb2d20c789e3db031204f4f8",
  "signature": "SmdP8MFU7RN6jFu61r5Jtd+Zi9IGmZKSilKMq/MDXsUELFl+UHckiDv+DxlHkLpjFtlj9gQSsnpJalLa+2MhS8fj6mNi13q9E4Uy/w+/aGE87DONcKIgeGw4BTv9xlb99UQZvOBf+ynAk/x/4RYLErFllUndPFm2/Aw/pyTFPlYq+0Aahsh7+UGgJ0VVPluxHo2BmPmXPIHI0kQEk6kdDY9MxXzdq3yfgTBezOuhJWsIlf8x1W4gCd7QyJB5RHESWd02GAJzm0JXa3k7JshPksrviYV4yzdgruI/Y4eLKzaF0qi1O4DQFVgO/rh9VWL5L7Sud7ljFq6NvefpHfDoKw=="
}
*/

// ---------------------------------------------------------------------------------------------------------------------
$requestSignature = $pm->signature;
$encData = $pm->data; // String type
try {
  // 1. Verify sign of request is valid or not by Anyonpay's public key
  $valid = verifyByPublicKey($encData, base64_decode($requestSignature), $anyonepayPublicKey);
  
  if( ! $valid ){
    throw new Exception('Not valid signature.');
  }

  // ---------------------------------------------------------------------------------------------------------------------
  $dt=array('result' => "SUCCESS");  // If you want to stop continueing this payment, write another value, such as "REJECT"
  $dt['webhookSeq'] = json_decode($encData)->webhookSeq; // This parameter should be same with request.

  // Let AnyonePay know THAT you are used what public key of Anyonepay provided.
  $output=array('publicKeySha1' => sha1($yourPublicKey1,FALSE));
  $replySignature = '';
  $output['data'] = json_encode($dt);
  signByPrivateKey($output['data'], $replySignature, $merchantPrivateKey);
  $output['signature'] = base64_encode($replySignature);

  // ---------------------------------------------------------------------------------------------------------------------
  header("Content-type: application/json");
  echo "". json_encode($output);
} catch (Exception $e) {
  header('HTTP/1.0 409 Conflict'); 
  header('content-type: application/json'); 
  $o=array(
    "message" => "We can not verify your message, or sign to send reply. please check your public key registered. ".$e
  );
  echo json_encode($o);
  exit; 
}
// ---------------------------------------------------------------------------------------------------------------------
?>