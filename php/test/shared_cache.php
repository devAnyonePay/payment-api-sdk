<?php

namespace AnyonePayTest;
use DateTime;

/*
    This class using for Test only.
    
    !!!!! !!!!! !!!!! !!!!! !!!!! !!!!! !!!!! !!!!! !!!!!!
    !!!!! DO NOT USE this for saving important data. !!!!!
    !!!!! !!!!! !!!!! !!!!! !!!!! !!!!! !!!!! !!!!! !!!!!!

    How to use :

    $test="abcdefg";
    TestCache::getInstance()->save_cache($test, 'webhook');
    $test2 = TestCache::getInstance()->get_cache('webhook');
    var_dump($test2);

    To save/get cache that easily just save this as cache.php or whatever you see fit:
*/

class TestCache
{
    private static $instance;
    // Create a temporary file in the temporary 
    // files directory using sys_get_temp_dir()
    private static $temp_file;

    protected function __construct()
    {    
    }

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
            self::$temp_file = sys_get_temp_dir();
        }
        return self::$instance;
    }

    static function get_cache_id($name) {
         // If no set these SYSTEM ID : default is 879, for security
         $testshop_share_id_webhook=$_SERVER['anyonepay_testshop_share_system_id_webhook'];
         if( $testshop_share_id_webhook == '' ) $testshop_share_id_webhook = 879;

        // maintain list of caches here
        $id=array(
            'webhook' => $testshop_share_id_webhook,
        );

        return $id[$name];
    }

    function save_cache($data, $name) {
        $fname = self::$temp_file."/".self::get_cache_id($name);
        $fp = fopen($fname, "r+");
        ftruncate($fp, 0);
        fclose($fp);

        $myfile = fopen($fname, "w") or die("Unable to open file to write!");
        fwrite($myfile, json_encode($data));
        fclose($myfile);

        return $fname;
    }

    function get_cache($name) {
        $fname = self::$temp_file."/".self::get_cache_id($name);
        if( filesize($fname) == 0 ) return "[]";

        $myfile = fopen($fname, "r") or die("Unable to open cache file!");
        $data = fread($myfile,filesize($fname));
        fclose($myfile);
        return $data;
    }

}
?>