<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));



$pageTitle = trans('Maintenance Settings', $lang['CH188'], true);
$subTitle = trans('Chat Online / Offline', $lang['CH189'], true);

$htmlLibs = array('iCheck');
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $siteInfo =  mysqli_query($con, "SELECT other_settings FROM ".DB_PREFIX."site_info where id='1'");
    $siteInfoRow = mysqli_fetch_array($siteInfo);
    $other = dbStrToArr($siteInfoRow['other_settings']);
        
    $other['other']['maintenance'] = escapeTrim($con, $_POST['maintenance_mode']);
    $other['other']['maintenance_mes'] = escapeTrim($con, $_POST['maintenance_mes']);
    $other_settings = arrToDbStr($con,$other);
            
    $query = "UPDATE ".DB_PREFIX."site_info SET other_settings='$other_settings' WHERE id='1'";
    mysqli_query($con, $query);

    if (mysqli_errno($con))
        $msg = errorMsgAdmin(mysqli_error($con));
    else
        $msg = successMsgAdmin($lang['CH190']);

}

//Load Maintenance Settings
$siteInfo =  mysqli_query($con, "SELECT other_settings FROM ".DB_PREFIX."site_info where id='1'");
$siteInfoRow = mysqli_fetch_array($siteInfo);
$other = dbStrToArr($siteInfoRow['other_settings']);
     
$maintenance_mode = isSelected($other['other']['maintenance']);
$maintenance_mes =  $other['other']['maintenance_mes'];