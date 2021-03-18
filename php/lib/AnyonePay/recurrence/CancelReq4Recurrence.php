<?php
namespace AnyonePay\recurrence;

use AnyonePay\recurrence\CheckoutService4Recurrence;
use AnyonePay\oneTime\RegisterReq;

class CancelReq4Recurrence extends RegisterReq
{
    /**
     * SubscriptionSeq to cancel
     * example Value : 2101281009580124539 
     *
     * @param string $subscriptionSeq
     * 
     * @return $this
     */
    public function setSubscriptionSeq($subscriptionSeq)
    {
        $this->subscriptionSeq = $subscriptionSeq;
        return $this;
    }

    /**
     * processByRedirectUrl
     * example Value : true, value
     *
     * @param boolean $processByRedirectUrl
     * 
     * @return $this
     */
    public function setProcessByRedirectUrl($processByRedirectUrl)
    {
        $this->processByRedirectUrl = $processByRedirectUrl;
        return $this;
    }

    public function send()
    {
        $checkoutService4Recurrence = new CheckoutService4Recurrence();
        $checkoutService4Recurrence->cancel($this);

        return $checkoutService4Recurrence;
    }
}