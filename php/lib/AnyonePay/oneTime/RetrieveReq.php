<?php
namespace AnyonePay\oneTime;

use AnyonePay\oneTime\CheckoutService;
use AnyonePay\entity\JsonVO;

class RetrieveReq extends JsonVO
{

    /**
     * Credential ClientId being used. Business CMS is provide this value for use.
     * example Value : abcdeghijklm 
     *
     * @param string $clientId
     * 
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * Credential ClientSecret being used. Business CMS is provide this value for use.
     * example Value: abcdeghijklmaA12=sdf
     *
     * @param string $clientSecret
     * 
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * paymentSeq being used. It gets after charged.
     * example Value : 1234567890123 
     *
     * @param string $paymentSeq
     * 
     * @return $this
     */
    public function setPaymentSeq($paymentSeq)
    {
        $this->paymentSeq = $paymentSeq;
        return $this;
    }

    public function __construct()
    {
    }

    public function send()
    {
        $checkoutService = new CheckoutService();
        $checkoutService->retrieve($this);

        return $checkoutService;
    }
}