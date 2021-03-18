<?php
namespace AnyonePay\recurrence;

require_once dirname(__FILE__) . "/../core/Unirest.php";

use AnyonePay\core\Configuration;
use AnyonePay\recurrence\RegisterRes4Recurrence;
use AnyonePay\oneTime\RetrieveRes;
use Unirest\Request;

class CheckoutService4Recurrence
{
    private $hasError = false;
    private $lastError;

    private $apiServerOrigin;
    
    public function __construct()
    {
        $this->apiServerOrigin = Configuration::getInstance()->getProperty('API_ORIGIN');
    }

    // -------------------------------------------------------------------------
    
    public function regist($registerReq)
    {
        try {
            $headers = array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Anyonepay-Client-Id' => $registerReq->clientId,
                'X-Anyonepay-Client-Secret' => $registerReq->clientSecret,
            );
    
            $body = $registerReq->serialize();
    
            $url = $this->apiServerOrigin . '/payments/v1/subscriptions';
    
            // Disable SSL and Certificate verification
            Request::verifyPeer(false);
            Request::verifyHost(false);
            
            $response = Request::post($url, $headers, $body);
    
            // echo "-------------- [register] ---------------- <br/> \n";
            // echo json_encode($response) . "\n";
            // echo "---------- [On the Debug Mode] ----------- <br/> \n";
    
            if ($response->code == 200) {
                /*
                * {
                *   "result_code": 200,
                *   "result_message": "AOP_PAY_REGISTER_PAYMENT_200_SUCCESS",
                *   "data": {
                *       "subscriptionSeq": "2009090540286680376",
                *       "referenceNo": "REFERENCE-NO-FROM-MERCHANT-00001",
                *       "redirectUrl": "http://api-sandbox.anyonepay.ph/checkout..."
                *    }
                * }
                */
            
                $result = $response->body;
    
                $subscriptionSeq = $result->data->subscriptionSeq;
                $referenceNo = $result->data->referenceNo;
                $checkoutUrl = $result->data->redirectUrl;
    
                $this->apiResponse = new RegisterRes4Recurrence($subscriptionSeq, $referenceNo, $checkoutUrl);

                $this->hasError = false;
                $this->lastError = null;
                return;
            }
            
            $this->hasError = true;
            $this->lastError = $response->body;
        } catch (Exception $ex) {
            $this->hasError = true;
            $this->lastError = $ex;
        }
    }

    public function hasError(){
        return $this->hasError;
    }

    public function getLastError(){
        return $this->lastError;
    }

    public function getResponse(){
        return $this->apiResponse;
    }

    // -------------------------------------------------------------------------

    public function retrieve($retrieveReq)
    {
        try {
            $headers = array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Anyonepay-Client-Id' => $retrieveReq->clientId,
                'X-Anyonepay-Client-Secret' => $retrieveReq->clientSecret,
            );

            $url = $this->apiServerOrigin . '/payments/v1/payments/seq/' . $retrieveReq->paymentSeq;

            // Disable SSL and Certificate verification
            Request::verifyPeer(false);
            Request::verifyHost(false);

            $response = Request::get($url, $headers, array(
            ));

            // echo "--------------- [verify] ----------------- <br/> \n";
            // echo json_encode($response) . "\n";
            // echo "---------- [On the Debug Mode] ----------- <br/> \n";

            if ($response->code == 200) {
                /*
                {
                    "result_code": 200,
                    "result_message": "AOP_GET_PAYMENT_RESULT_200_SUCCESS",
                    "data": {
                        "paymentSeq": "2010151634399080917",
                        "payDate": "2020-10-20T18:00:02Z",
                        "amount": 20.00,
                        "product": "Pay Test",
                        "items": [
                        {
                            "name": "item-1",
                            "count": 1,
                            "price": 20
                        }
                        ],
                        "status": "SUCCESS",
                        "referenceNo": "123456789"
                    }
                }
                */

                $result = $response->body;

                $this->apiResponse = new RetrieveRes($result);

                $this->hasError = false;
                $this->lastError = null;
                return;
            }
            
            $this->hasError = true;
            $this->lastError = $response->body;
        } catch (Exception $ex) {
            $this->hasError = true;
            $this->lastError = $ex;
        }
    }

    // -------------------------------------------------------------------------

    public function cancel($cancelReq)
    {
        try {
            $headers = array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Anyonepay-Client-Id' => $cancelReq->clientId,
                'X-Anyonepay-Client-Secret' => $cancelReq->clientSecret,
            );
    
            $body = $cancelReq->serialize();
    
            $url = $this->apiServerOrigin . '/payments/v1/subscriptions/'. $cancelReq->subscriptionSeq .'/cancel';
    
            // Disable SSL and Certificate verification
            Request::verifyPeer(false);
            Request::verifyHost(false);
            
            $response = Request::post($url, $headers, $body);
    
            // echo "-------------- [register] ---------------- <br/> \n";
            // echo json_encode($response) . "\n";
            // echo "---------- [On the Debug Mode] ----------- <br/> \n";
    
            if ($response->code == 200) {
                /*
                * {
                *   "result_code": 200,
                *   "result_message": "AOP_PAY_CANCEL_SUBSCRIPTION_REQUEST_200_DONE",
                *   "data": {
                *       "subscriptionSeq": "2009090540286680376",
                *       "referenceNo": "REFERENCE-NO-FROM-MERCHANT-00001",
                *       "redirectUrl": "http://api-sandbox.anyonepay.ph/sandbox/web/un-subscription.html?entry=un-subscription&subscriptionSeq=2101281005284655756&cancelUrl=http%3A%2F%2Ftestshop.anyonepay.ph%2Ftest%2FpayResult.php%3Fv%3Dpayment_canceled&otr=EGeAUlPhvKAiUD2XZAnb1k%2B%2B9NGAqXBFMn%2Bk0yySGqwt%2F4MYXjkgjszzFcB2ngb7H1hbTkmL3Ij%2FObg8hm97EtKYJE5vl7yHB2tanbhcnGkpYZ1hV8yKmZAtx3ddnyad"
                *    }
                * }
                */
            
                $result = $response->body;
    
                $subscriptionSeq = $result->data->subscriptionSeq;
                $referenceNo = $result->data->referenceNo;
                $checkoutUrl = $result->data->redirectUrl;
    
                $this->apiResponse = new CancelRes4Recurrence($subscriptionSeq, $referenceNo, $checkoutUrl);

                $this->hasError = false;
                $this->lastError = null;
                return;
            }
            
            $this->hasError = true;
            $this->lastError = $response->body;
        } catch (Exception $ex) {
            $this->hasError = true;
            $this->lastError = $ex;
        }
    }
}