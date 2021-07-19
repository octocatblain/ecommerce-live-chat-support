<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Adblock Detection', $lang['CH235'], true);
$subTitle = trans('Detect & Ban - Ad Blocking Users', $lang['CH236'], true);

$htmlLibs = array('iCheck');

$taskData =  mysqli_query($con, "SELECT data FROM ".DB_PREFIX."rainbowphp where task='adblock'");
$taskRow = mysqli_fetch_array($taskData);
$adblock = dbStrToArr($taskRow['data']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $myValues = array_map_recursive(
        function($item) use ($con) { return escapeTrim($con,$item); },
        $_POST
    );

    if(!isset($myValues['adblock']['enable']))
        $myValues['adblock']['enable'] = 'off';

    $adblock = $myValues['adblock'];
    $strData = arrToDbStr($con, $adblock);
    $adblock = array_map_recursive('stripcslashes',$adblock);

    if(updateToDbPrepared($con, 'rainbowphp', array('data' => $strData), array('task' => 'adblock')))
        $msg = errorMsgAdmin($lang['CH237']);
    else
        $msg = successMsgAdmin($lang['CH238']);
}