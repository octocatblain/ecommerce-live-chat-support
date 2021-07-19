<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

$pageTitle = trans('Domain License', $lang['CH423'], true);
$subTitle = trans('License Change', $lang['CH424'], true);

//Domain License Info
$jsonData = simpleCurlGET('http://api.prothemes.biz/pinky/info.php?link='.createLink('',true).'&code='.$item_purchase_code);
$licArr = json_decode($jsonData,true);