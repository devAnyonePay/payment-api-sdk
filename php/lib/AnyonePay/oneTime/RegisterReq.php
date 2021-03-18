<?php
namespace AnyonePay\oneTime;

use AnyonePay\oneTime\CheckoutService;
use AnyonePay\entity\JsonVO;

class RegisterReq extends JsonVO
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
     * StoreId being used. Business CMS is provide this value for use.
     * example Value : 1234567890123 
     *
     * @param string $storeId
     * 
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function setBillingMethod($billingMethod)
    {
        $this->billingMethod = $billingMethod;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setAdditional($additional)
    {
        $this->additional = $additional;
        return $this;
    }

    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    public function setProductDescription($productDescription)
    {
        $this->productDescription = $productDescription;
        return $this;
    }

    public function setProductItems($items)
    {
        $this->items = $items;
        return $this;
    }

    public function setReferenceNo($referenceNo)
    {
        $this->referenceNo = $referenceNo;
        return $this;
    }

    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    public function setWebhookUrl($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
        return $this;
    }

    public function setCancelUrl($cancelUrl)
    {
        $this->cancelUrl = $cancelUrl;
        return $this;
    }
    
    public function __construct()
    {
    }

    public function send()
    {
        $checkoutService = new CheckoutService();
        $checkoutService->regist($this);

        return $checkoutService;
    }
}