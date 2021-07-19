<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));



$pageTitle = trans('Error Log File Viewer', $lang['CH449'], true);
$subTitle = trans('Error Log', $lang['CH450'], true);

//Clear Error Log File
if($pointOut == 'clear'){
    putMyData(LOG_DIR.ERR_R_FILE,'');
    $msg = successMsgAdmin($lang['CH451']);
}

$errData = getMyData(LOG_DIR.ERR_R_FILE);
if($errData == '')
    $errData = trans('Error log is empty!', $lang['CH452'], true);