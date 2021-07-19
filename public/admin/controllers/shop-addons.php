<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
 
$pageTitle = trans('Shop Addons', $lang['CH478'], true);
$subTitle = trans('Pinky Chat Addons', $lang['CH479'], true);

$htmlLibs = array('dataTables');
if($pointOut === 'ajax'){
    $query = http_build_query($_GET) . "\n";
    echo getMyData('http://api.prothemes.biz/pinky/shop_addon.php?'.$query);
    die();
}