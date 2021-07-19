<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2018 ProThemes.Biz
 *
 */

$pageTitle = trans('Live Chat',$lang['CH76'],true);
$subTitle = trans('Chat',$lang['CH119'],true);
$onlineUsersCount = 0;
$chatTemplate = chatTemplate(ROOT_DIR.'theme'.D_S.'default'.D_S);

if(issetSession('activeChats'))
    $adminInfo = getSession(array('AdminID','AdminName', 'AdminLogo' ,'activeChats'));
else
    $adminInfo = getSession(array('AdminID','AdminName', 'AdminLogo'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $myValues = array_map_recursive(
        function($item) use ($con) { return escapeTrim($con,$item); },
        $_POST
    );
}

$chatSettings = adminChatSettings($con);
$emoticons = loadEmoticonPack($con,true,$chatSettings['emoticons']);

$tonePath = ''; $eTone = $cannedMsgBol = false;
$cannedMsgArr = array(); $cannedMsgType = 1;
if(isSelected($chatSettings['tone'])){
   $tonePath = getTone($con, $chatSettings['default_tone']);
   $eTone = true;
}

if(isSelected($chatSettings['canned'])){
    $cannedMsgBol = true;
    if(intval($chatSettings['canned_type']) === 2) {
        $cannedMsgArr = getCannedMsgAll($con);
        $cannedMsgType = 2;
    }
}