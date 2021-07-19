<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Captcha Protection', $lang['CH195'], true);
$subTitle = trans('Captcha Settings', $lang['CH196'], true);
$htmlLibs = array('select2','colorpicker');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $myValues = array_map_recursive(
        function($item) use ($con) { return escapeTrim($con,$item); },
        $_POST
    );

    $capthcaData = arrToDbStr($con, $myValues['cap']);
    $capthcaPagesArr = array();
    
    foreach(capPages() as $capName=>$capRaw){
        if(in_array($capName,$myValues['cap_pages']))
            $capthcaPagesArr[$capName] = true;
        else
            $capthcaPagesArr[$capName] = false;
    }
    $capthcaPages = arrToDbStr($con, $capthcaPagesArr);
    $cap_type = strtolower($myValues['sel_cap']);
    $result = updateToDbPrepared($con, 'capthca', array( 'cap_options' => $capthcaPages,  'cap_data' => $capthcaData,  'cap_type' => $cap_type), array('id' => '1'));

    if ($result)
        $msg = errorMsgAdmin($lang['CH197']);
    else
        $msg = successMsgAdmin($lang['CH198']);
}

extract(loadAllCapthca($con));

$cap_options = dbStrToArr($cap_options);
$cap_data = dbStrToArr($cap_data);
$capList = capPages();

foreach($cap_data as $capEx)
    extract($capEx);