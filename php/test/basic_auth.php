<?php
// If no set these ID/PW : default is admin / lucky
$testshop_userId=$_SERVER['anyonepay_testshop_userId'];
$testshop_userPw=$_SERVER['anyonepay_testshop_userPw'];

function loadAccount($_id = "admin", $_pw = "lucky") {
    if( $_id == '' ) $_id = "admin";
    if( $_pw == '' ) $_pw = "lucky";
    return array($_id=>$_pw);
}
$acc = loadAccount($testshop_userId, $testshop_userPw);

function pc_validate($user,$pass,$users) { 
    /* replace with appropriate username and password checking, such as checking a database */ 
    if (isset($users[$user]) && ($users[$user] == $pass)) { 
        return true; 
    } else 
    { return false; } 
}

if (! pc_validate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $acc)) { 
    header('WWW-Authenticate: Basic realm="My Website"'); 
    header('HTTP/1.0 401 Unauthorized'); 
    header('content-type: application/json'); 
    echo "{ \"message\":\"You need to enter a valid username and password.\"}"; 
    exit; 
}
?>