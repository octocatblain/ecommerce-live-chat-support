<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Default Theme Settings',$lang['CH771'],true);
if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }
$htmlLibs = array('colorpicker');

if(isset($args[2]) && $args[2] != '')
    $pointOut = $args[2];

//Load theme settings
$to = getThemeOptionsDev($con,$themePathName);

if ($pointOut === 'reset') {
    $to = $to['reset'];
    $to['reset'] = $to;

    if (updateToDbPrepared($con, 'themes_data ', array($args[0].'_theme' => arrToDbStr($con, $to)), array('id' => 1))) {
        $msg = errorMsgAdmin($lang['CH376']);
    } else {
        setSession('msgCallBack', successMsgAdmin($lang['CH375']));
        redirectTo(str_replace('/reset','',$currentLink));
    }
}

function removeRGB($val){
    return str_replace(array('rgb(',')'), '', $val);
}

if ($pointOut === 'save') {
    $myValues = array_map_recursive(function ($item) use ($con) {
        return removeRGB(escapeTrim($con, $item));
    }, $_POST);

    $myValues['to']['reset'] = $to['reset'];
    $to = $myValues['to'];


    if (updateToDbPrepared($con, 'themes_data ', array($args[0].'_theme' => arrToDbStr($con, $to)), array('id' => 1))) {
        $msg = errorMsgAdmin($lang['CH218']);
    } else {
        setSession('msgCallBack', successMsgAdmin($lang['CH377']));
        redirectTo(str_replace('/save','',$currentLink));
    }
}