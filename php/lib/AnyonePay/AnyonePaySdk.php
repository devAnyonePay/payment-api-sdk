<?php
namespace AnyonePay;
use Exception;

class AnyonePaySdk
{

    private static $instance;

    private $activeProfile;

    protected function __construct()
    {}

    /**
     *
     * @return mixed
     */
    public function getActiveProfile()
    {
        return $this->activeProfile;
    }

    /**
     *
     * @param mixed $activeProfile
     */
    public function setActiveProfile($activeProfile)
    {
        $this->activeProfile = $activeProfile;
    }

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function initConfig($profile = "SANDBOX")
    {
        $this->activeProfile = $profile;
    }

    public function verifyByPublicKey($data, $signature, $pubkey)
    {
        //verify signature
        $ok = openssl_verify($data, $signature, $pubkey, OPENSSL_ALGO_SHA1);
        if ($ok == 1) {
            return true;
        } elseif ($ok == 0) {
            return false;
        } else {
            throw new Exception('Unable to verify data. Please use valid public key. '.openssl_error_string());
        }
    }

    public function signByPrivateKey($data, &$signature, $privkey)
    {
        //create signature
        openssl_sign($data, $signature, $privkey, "sha1WithRSAEncryption");
    }
}

