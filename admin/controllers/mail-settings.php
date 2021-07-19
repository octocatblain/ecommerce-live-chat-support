<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

$pageTitle = trans('Mail Settings', $lang['CH587'], true);
$subTitle = trans('General Settings', $lang['CH588'], true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $smtp_host =   escapeTrim($con,$_POST['smtp_host']);
    $smtp_port =  escapeTrim($con,$_POST['smtp_port']);
    $smtp_username =  escapeTrim($con,$_POST['smtp_user']);
    $smtp_password =  escapeTrim($con,$_POST['smtp_pass']);
    $socket =  escapeTrim($con,$_POST['socket']);
    $auth =  escapeTrim($con,$_POST['auth']);
    $protocol =  escapeTrim($con,$_POST['protocol']);

    $result = updateToDbPrepared($con, 'mail', array( 'smtp_host' => $smtp_host,  'smtp_port' => $smtp_port,  'smtp_username' => $smtp_username,  'smtp_password' => $smtp_password,  'smtp_socket' => $socket,  'protocol' => $protocol,  'smtp_auth' => $auth), array('id' => 1));

    if ($result)
        $msg = errorMsgAdmin(mysqli_error($con));
    else
        $msg = successMsgAdmin($lang['CH589']);
}

$mailInfo =  mysqli_query($con, "SELECT * FROM ".DB_PREFIX."mail WHERE id=1");
$row = mysqli_fetch_array($mailInfo);

$smtp_host = Trim($row['smtp_host']);
$smtp_username = Trim($row['smtp_username']);
$smtp_password = Trim($row['smtp_password']);
$smtp_port = Trim($row['smtp_port']);
$protocol = Trim($row['protocol']);
$auth = Trim($row['smtp_auth']);
$socket = Trim($row['smtp_socket']);