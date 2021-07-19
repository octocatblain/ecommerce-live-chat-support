<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));


$pageTitle = trans('Notifications Tones', $lang['CH347'], true);
$subTitle = trans('Manage Notification Tones', $lang['CH348'], true);
$mainPage = true;

$htmlLibs = array('dataTables');
if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

//Status Change
if($pointOut === 'status'){
    $status = 0;
    if($args[0] === 'disable')  $status = 0;  else $status = 1;
    $id = intval($args[1]);

    mysqli_query($con, "UPDATE ".DB_PREFIX."notifications SET status=$status WHERE id=$id");
    setSession('msgCallBack', successMsgAdmin($lang['CH295']));
    redirectTo(adminLink($controller, true));
}


//Delete Notification Tone
if($pointOut === 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        $sql = 'DELETE FROM ' . DB_PREFIX . 'notifications WHERE id=' . $args[0];
        if (mysqli_query($con, $sql))
            die('1');
    }
    die('0');
}

//Add / Edit Tones
if($pointOut === 'add' || $pointOut === 'edit'){
    $pageTitle = trans('Add Notification Tone', $lang['CH349'], true);
    $subTitle = trans('Notification Tone Details', $lang['CH350'], true);
    $mainPage = false;
    $pack = array('name' => '', 'path' => '', 'status' => 1);

    if($pointOut === 'edit'){
        $subTitle = 'Edit Notification Tone';
        $editID = intval($args[0]);
        if($editID == '' || $editID === NULL || $editID === 0)
            redirectTo(adminLink($controller, true));
        $pack = mysqliPreparedQuery($con, 'SELECT name,path,status FROM ' . DB_PREFIX . 'notifications WHERE id=?', 'i', array($editID));
    }

    if(isset($_POST['addTone'])) {
        $pack = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);
        unset($pack['addTone']);
        $pack['added_by'] = getSession('AdminID');
        $pack['date'] = date('m/d/Y h:i:sA');

        if($pointOut === 'edit'){
            $res = updateToDbPrepared($con, 'notifications', $pack, array('id' => $editID));
            $suMsg = trans('Notification tone settings updated successfully', $lang['CH351'], true);
        }else{
            $res = insertToDbPrepared($con, 'notifications', $pack);
            $suMsg = trans('Notification tone added successfully', $lang['CH352'], true);
        }
        if ($res)
            setSession('msgCallBack', errorMsgAdmin($lang['CH353']));
        else
            setSession('msgCallBack', successMsgAdmin($suMsg));
        redirectTo(adminLink($controller, true));
    }
}

if($mainPage)
    $data = mysqliPreparedQuery($con, 'SELECT id,name,path,added_by,date,status FROM ' . DB_PREFIX . 'notifications', '', array(),false);
