<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Cron Job Viewer', $lang['CH460'], true);
$subTitle = trans('Cron Job', $lang['CH461'], true);

//Clear Cron Log File
if($pointOut == 'clear'){
    putMyData(LOG_DIR.'cron.tdata','');
    $msg = successMsgAdmin($lang['CH462']);
}

$errData = getMyData(LOG_DIR.'cron.tdata');
if($errData == '')
    $errData = $lang['CH463'];
    
$cronPath = APP_DIR.'cron.php';