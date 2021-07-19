<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));



$pageTitle = trans('Get Widget Code', $lang['CH250'], true);
$subTitle = trans('Embed Chat Widget', $lang['CH251'], true);

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

$w = array();

$w['code'] = '<script type="text/javascript" src="'. str_replace(array('http://', 'https://'), '//', $baseURL) .'widget.js"></script>';
$w['inline'] = '<script type="text/javascript" src="'. str_replace(array('http://', 'https://'), '//', $baseURL) .'widget.js&inline"></script>';

$widgetLang = array();

$widgetLang['default'] = 'Default Language';
foreach(getAvailableLanguages($con) as $langBalaji)
    $widgetLang[$langBalaji[2]] = $langBalaji[3];

$defaultTheme = getTheme($con);
$themeDetails = getThemeDetails($defaultTheme);
$builderLink = adminLink($themeDetails[1]['builder'], true);