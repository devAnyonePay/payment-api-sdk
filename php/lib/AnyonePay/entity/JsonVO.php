<?php
namespace AnyonePay\entity;

class JsonVO
{

    public function __construct()
    {}

    public function serialize()
    {
        return json_encode($this);
    }

    public function deserialize($json)
    {
        return json_decode($json);
    }
}