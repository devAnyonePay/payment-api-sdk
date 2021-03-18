<?php
namespace AnyonePay\recurrence;

use AnyonePay\recurrence\CheckoutService4Recurrence;
use AnyonePay\oneTime\RegisterReq;

class RegisterReq4Recurrence extends RegisterReq
{
    /**
     * Which Payment gateway channel to subscribe
     * example Value : PGC09 
     *
     * @param string $pgChannel
     * 
     * @return $this
     */
    public function setPgChannel($pgChannel)
    {
        $this->pgChannel = $pgChannel;
        return $this;
    }

    /**
     * Subscription cycle
     * example Value : DAY,
     * available enum : DAY, MONTH, YEAR
     *
     * @param string $interval
     * 
     * @return $this
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * How many days or months or years of cycle.
     * example Value : 1,
     *
     * @param string $intervalCount
     * 
     * @return $this
     */
    public function setIntervalCount($intervalCount)
    {
        $this->intervalCount = $intervalCount;
        return $this;
    }

    /**
     * When is start billing (yyyy-MM-dd)
     * example Value : 2021-02-03
     *
     * @param string $startDate
     * 
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function send()
    {
        $checkoutService4Recurrence = new CheckoutService4Recurrence();
        $checkoutService4Recurrence->regist($this);

        return $checkoutService4Recurrence;
    }
}