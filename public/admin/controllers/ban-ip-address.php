<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

$pageTitle = trans('Ban IP Address', $lang['CH216'], true);
$subTitle = trans('Add User IP to Ban', $lang['CH217'], true);

if($pointOut == 'delete'){
    $code = $args[0];
    if($args[0] != ''){
        $result = mysqli_query($con, "DELETE FROM ".DB_PREFIX."banned_ip WHERE id='$args[0]'");
    
        if (mysqli_errno($con)) {
            $msg = errorMsgAdmin(mysqli_error($con));
        } else {
            header('Location:'.adminLink($controller,true));
            die();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $ban_ip = escapeTrim($con, $_POST['ban_ip']);
    $banReason = escapeTrim($con, $_POST['reason']);
    
    if (!filter_var($ban_ip, FILTER_VALIDATE_IP) === false) {
        $result = insertToDbPrepared($con, 'banned_ip', array('added_at' => $date,'ip' => $ban_ip,'reason' => $banReason));

        if ($result)
            $msg = errorMsgAdmin($lang['CH218']);
        else
            $msg =  successMsgAdmin($lang['CH219']);
    } else {
        $msg = errorMsgAdmin($lang['CH220']);
    }
}

$bannedList = array();
$result = mysqli_query($con,"SELECT id,ip,added_at,reason FROM ".DB_PREFIX."banned_ip");

while($row = mysqli_fetch_assoc($result))
  $bannedList[] = $row;