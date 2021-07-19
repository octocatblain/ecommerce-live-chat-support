<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Manage Site', $lang['CH169'], true);
$subTitle = trans('Basic Settings', $lang['CH170'], true);
$htmlLibs = array('bCheckbox');

if(isset($_POST['b'])){
    $b = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    $b['b']['doForce'] = arrToDbStr($con,array(raino_trim($_POST['https']),raino_trim($_POST['www'])));
    $result = updateToDbPrepared($con, 'site_info', $b['b'] , array('id' => 1));
    if ($result)
        $msg = errorMsgAdmin($lang['CH171']);
    else
        $msg = successMsgAdmin($lang['CH172']);
}


//Get site Info
$b =  getBasicSettings($con);
$doForce = dbStrToArr($b['doForce']);
$b['copyright'] = htmlspecialchars_decode($b['copyright']);
$b['html_app'] = htmlspecialchars_decode($b['html_app']);
$forceHttps = filter_var($doForce[0], FILTER_VALIDATE_BOOLEAN);
$forceWww = filter_var($doForce[1], FILTER_VALIDATE_BOOLEAN);