<?php
namespace AnyonePay\oneTime;

use AnyonePay\entity\JsonVO;

class RetrieveRes extends JsonVO
{
    public function getResult(){
        return $this->result;
    }

    public function getPaymentSeq(){
        return $this->paymentSeq;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getReferenceNo(){
        return $this->referenceNo;
    }

    public function getCreatedTime(){
        return $this->createdTime;
    }

    public function getFinishedTime(){
        return $this->finishedTime;
    }

    public function getProduct(){
        return $this->product;
    }

    public function getItems(){
        return $this->items;
    }


    public function __construct($r)
    {
        $this->result = $r;
        $result = $r;

        // if (!isset($result->result)) {
        //     echo "no-set result:";
        //     return;
        // }
        
        if (!isset($result->data)) {
            return;
        }

        $_data = $result->data;

        $this->amount = $_data->amount;
        $this->paymentSeq = $_data->paymentSeq;
        $this->status = $_data->status;
        $this->referenceNo = $_data->referenceNo;
        $this->createdTime = $_data->createdTime;
        $this->finishedTime = $_data->finishedTime;
        $this->product = $_data->product;
        $this->items = $_data->items;
    }
}