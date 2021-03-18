<?php
// -----------------------------------------------------------------------------
require_once dirname(__FILE__) . "/AnyonePaySdk.php";
require_once dirname(__FILE__) . "/core/Configuration.php";
require_once dirname(__FILE__) . '/entity/JsonVO.php';
// -----------------------------------------------------------------------------
require_once dirname(__FILE__) . "/oneTime/CheckoutService.php";
require_once dirname(__FILE__) . '/oneTime/RegisterReq.php';
require_once dirname(__FILE__) . '/oneTime/RegisterRes.php';
require_once dirname(__FILE__) . '/oneTime/RetrieveReq.php';
require_once dirname(__FILE__) . '/oneTime/RetrieveRes.php';
// -----------------------------------------------------------------------------
require_once dirname(__FILE__) . "/recurrence/CheckoutService4Recurrence.php";
require_once dirname(__FILE__) . '/recurrence/RegisterReq4Recurrence.php';
require_once dirname(__FILE__) . '/recurrence/RegisterRes4Recurrence.php';
require_once dirname(__FILE__) . '/recurrence/CancelReq4Recurrence.php';
require_once dirname(__FILE__) . '/recurrence/CancelRes4Recurrence.php';
// -----------------------------------------------------------------------------
?>
