<?php
namespace AnyonePay\core;

use AnyonePay\AnyonePaySdk;

class Configuration
{

    const API_ORIGIN_SANDBOX    = "https://api-sandbox.anyonepay.ph";
    // const API_ORIGIN_SANDBOX    = "http://172.23.144.1:3020";
    const API_ORIGIN_STAGE      = "http://stgapi.anyonepay.ph";
    const API_ORIGIN_PRODUCTION = "https://api.anyonepay.ph";
    const API_ORIGIN_PRODUCTION_INTERNAL = "https://10.10.6.12";

    private static $instance;

    private $activeProfile;

    protected function __construct()
    {
        $this->activeProfile = AnyonePaySdk::getInstance()->getActiveProfile();
    }

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getProperty($key)
    {
        return constant('self::' . strtoupper($key) . '_' . $this->activeProfile);
    }
}
