<?php
namespace AnyonePay\recurrence;

use AnyonePay\entity\JsonVO;

class RegisterRes4Recurrence extends JsonVO
{

    public $subscriptionSeq;
    public $referenceNo;
    public $checkoutUrl;

    public function __construct($subscriptionSeq, $referenceNo, $checkoutUrl)
    {
        $this->subscriptionSeq = $subscriptionSeq;
        $this->referenceNo = $referenceNo;
        $this->checkoutUrl = $checkoutUrl;
    }
}