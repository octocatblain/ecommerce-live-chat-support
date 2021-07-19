<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

$pageTitle = trans('Avatars', $lang['CH293'], true);
$subTitle = trans('Manage Avatars', $lang['CH294'], true);
$mainPage = true;
$htmlLibs = array('dataTables');

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

//Status Change
if($pointOut === 'status'){
    $status = 0;
    if($args[0] === 'disable')  $status = 0;  else $status = 1;
    $id = intval($args[1]);

    mysqli_query($con, "UPDATE ".DB_PREFIX."avatars SET status=$status WHERE id=$id");
    setSession('msgCallBack', successMsgAdmin($lang['CH295']));
    redirectTo(adminLink($controller, true));
}


//Delete Notification Tone
if($pointOut === 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        $sql = 'DELETE FROM ' . DB_PREFIX . 'avatars WHERE id=' . $args[0];
        if (mysqli_query($con, $sql))
            die('1');
    }
    die('0');
}

//Add / Edit Tones
if($pointOut === 'add' || $pointOut === 'edit'){
    $pageTitle = $lang['CH296'];
    $subTitle = $lang['CH297'];
    $mainPage = false;
    $pack = array('name' => '', 'path' => '', 'status' => 1);

    if($pointOut === 'edit'){
        $subTitle = $lang['CH298'];
        $editID = intval($args[0]);
        if($editID == '' || $editID === NULL || $editID === 0)
            redirectTo(adminLink($controller, true));
        $pack = mysqliPreparedQuery($con, 'SELECT name,path,status FROM ' . DB_PREFIX . 'avatars WHERE id=?', 'i', array($editID));
    }

    if(isset($_POST['addAvatar'])) {
        $pack = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);
        unset($pack['addAvatar']);
        $pack['added_by'] = getSession('AdminID');
        $pack['date'] = date('m/d/Y h:i:sA');

        if($pointOut === 'edit'){
            $res = updateToDbPrepared($con, 'avatars', $pack, array('id' => $editID));
            $suMsg = $lang['CH299'];
        }else{
            $res = insertToDbPrepared($con, 'avatars', $pack);
            $suMsg = $lang['CH300'];
        }
        if ($res)
            setSession('msgCallBack', errorMsgAdmin($lang['CH301']));
        else
            setSession('msgCallBack', successMsgAdmin($suMsg));
        redirectTo(adminLink($controller, true));
    }
}

if($mainPage)
    $data = mysqliPreparedQuery($con, 'SELECT id,name,path,added_by,date,status FROM ' . DB_PREFIX . 'avatars', '', array(),false);
