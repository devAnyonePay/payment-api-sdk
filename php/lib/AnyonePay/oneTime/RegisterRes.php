<?php
namespace AnyonePay\oneTime;

use AnyonePay\entity\JsonVO;

class RegisterRes extends JsonVO
{

    public $paymentSeq;
    public $referenceNo;
    public $checkoutUrl;

    public function __construct($paymentSeq, $referenceNo, $checkoutUrl)
    {
        $this->paymentSeq = $paymentSeq;
        $this->referenceNo = $referenceNo;
        $this->checkoutUrl = $checkoutUrl;
    }
}