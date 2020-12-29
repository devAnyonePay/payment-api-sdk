<?php
/*
    Control access for this, allows in whitelisted ip ranges.
*/
function getRealIpAddr(){
    if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
     // Check IP from internet.
     $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
     // Check IP is passed from proxy.
     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
     // Get IP address from remote address.
     $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Validates subnet specified by CIDR notation.of the form IP address followed by 
 * a '/' character and a decimal number specifying the length, in bits, of the subnet
 * mask or routing prefix (number from 0 to 32).
 *
 * @param $ip - IP address to check
 * @param $cidr - IP address range in CIDR notation for check
 * @return bool - true match found otherwise false
 */
function cidr_match($ip, $cidr) {
    $outcome = false;
    $pattern = '/^(([01]?\d?\d|2[0-4]\d|25[0-5])\.){3}([01]?\d?\d|2[0-4]\d|25[0-5])\/(\d{1}|[0-2]{1}\d{1}|3[0-2])$/';
    if (preg_match($pattern, $cidr)){
        list($subnet, $mask) = explode('/', $cidr);
        if (ip2long($ip) >> (32 - $mask) == ip2long($subnet) >> (32 - $mask)) {
            $outcome = true;
        }
    }
    return $outcome;
}

$ipWhiteRangesStr = $_SERVER['anyonepay_testshop_ip_ranges'];

if( $ipWhiteRangesStr == '' ) {
    // Allows private network debug
    $ipWhiteRanges=array(
        "10.0.0.0/8",
        "172.16.0.0/12",
        "192.168.0.0/16",
        "127.0.0.1/32",
    );
}else{
    // $values  = "1.1.1.1/32 2.2.2.2/16";
    $ipWhiteRanges=explode(" ", $ipWhiteRangesStr);
}


function ip_validate($list) { 
    $nowIp = getRealIpAddr();
    for($i = 0; $i < count($list); ++$i) {
        if( cidr_match($nowIp,$list[$i]) ){
            return true;
        }
    }
    return false;
}

if (! ip_validate( $ipWhiteRanges ) ) { 
    $msg = "You are not allowed to access this site. your IP:".getRealIpAddr();

    header('WWW-Authenticate: Basic realm="AnyonePay Test"'); 
    header('HTTP/1.0 401 Unauthorized'); 
    header('content-type: application/json'); 
    echo "{ \"message\":\"".$msg."\"}"; 
    exit; 
}
?>