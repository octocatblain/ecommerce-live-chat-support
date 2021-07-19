<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Chat Settings',$lang['CH256'],true);
$subTitle = trans('Chat Settings (Clients)', $lang['CH373'], true);
$chat = array();

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

$uploadLimit = ' - [<small>'.$lang['CH374'].': <span class="backLabel label-primary">'.formatBytes(file_upload_max_size()).'</span></small>]';

if($pointOut !== 'admin') {

    if ($pointOut === 'reset') {
        $chat = chatSettings($con, null, 2);
        if (updateToDbPrepared($con, 'chat_settings', $chat, array('id' => 1))) {
            $msg = errorMsgAdmin($lang['CH376']);
        } else {
            setSession('msgCallBack', successMsgAdmin($lang['CH375']));
            redirectTo(adminLink($controller, true));
        }
    }

    $chat = chatSettings($con);

    if ($pointOut === 'save') {
        $myValues = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);

        $myValues['chat']['upload_size'] = convertToBytes($myValues['chat']['upload_size'].'MB');

        if (updateToDbPrepared($con, 'chat_settings', $myValues['chat'], array('id' => 1))) {
            $msg = errorMsgAdmin($lang['CH218']);
        } else {
            setSession('msgCallBack', successMsgAdmin($lang['CH377']));
            redirectTo(adminLink($controller, true));
        }
        $chat = $myValues['chat'];
    }

    $defaultTheme = getTheme($con);
    $themeDetails = getThemeDetails($defaultTheme);
    $builderLink = adminLink($themeDetails[1]['builder'], true);

}else{
    $subTitle = trans('Chat Settings (Admin)', $lang['CH378'], true);
    $lController = $controller;
    $controller = 'chat-settings-admin';

    if(isset($args[0])){
        if($args[0] != '')
            $pointOut = trim($args[0]);
    }

    if ($pointOut === 'reset') {
        $chat = adminChatSettings($con, null, 2);
        if (updateToDbPrepared($con, 'admin_chat_settings', $chat, array('id' => 1))) {
            $msg = errorMsgAdmin($lang['CH376']);
        } else {
            setSession('msgCallBack', successMsgAdmin($lang['CH375']));
            redirectTo(adminLink($lController.'/admin', true));
        }
    }

    $chat = adminChatSettings($con);

    if ($pointOut === 'save') {
        $myValues = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);

        $myValues['chat']['upload_size'] = convertToBytes($myValues['chat']['upload_size'].'MB');

        if (updateToDbPrepared($con, 'admin_chat_settings', $myValues['chat'], array('id' => 1))) {
            $msg = errorMsgAdmin($lang['CH218']);
        } else {
            setSession('msgCallBack', successMsgAdmin($lang['CH377']));
            redirectTo(adminLink($lController.'/admin', true));
        }
        $chat = $myValues['chat'];
    }
}