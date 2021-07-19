<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

$pageTitle = trans('Canned Messages',$lang['CH83'],true);
$subTitle = trans('Message List',$lang['CH279'],true);
$htmlLibs = array('dataTables');

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

if($pointOut == 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        $sql = 'DELETE FROM ' . DB_PREFIX . 'canned_msg WHERE id=' . $delID;
        if (mysqli_query($con, $sql))
            die('1');
    }
    die('0');
}

if($pointOut === 'groupdel'){

    $myValues = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    $delValues = implode(', ', $myValues['id']);

    $sql = 'DELETE FROM ' . DB_PREFIX . 'canned_msg WHERE id IN ('.$delValues.')';
    if (mysqli_query($con, $sql))
        die('1');
    die('0');
}

if($pointOut === 'add' || $pointOut === 'edit'){

    if(isset($_POST['a'])) {
        $myValues = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);
    }

    if($pointOut === 'add'){
        $subTitle = trans('Add Canned Message', $lang['CH280'], true);

        if(isset($_POST['a'])) {
            $insert = $myValues['a'];
            $insert['admin'] = $adminID;
            $insert['date'] = $date;

            if(insertToDbPrepared($con , 'canned_msg', $insert)){
                $a = $insert;
                $msg = errorMsgAdmin($lang['CH281']);
            }else{
                setSession('msgCallBack', successMsgAdmin($lang['CH282']));
                redirectTo(adminLink($controller,true));
            }
        }
    }

    if($pointOut === 'edit'){
        $subTitle = trans('Edit Canned Message', $lang['CH285'], true);
        if(isset($args[0]) && $args[0] !== ''){
            $editID = intval($args[0]);

        $data = mysqliPreparedQuery($con, 'SELECT code,data,status FROM ' . DB_PREFIX . 'canned_msg WHERE id=?', 'i', array($editID));
        if ($data !== false)
            $a = $data;
        else
            redirectTo(adminLink($controller,true));

        if(isset($_POST['a'])) {
            $update = $myValues['a'];
            $update['admin'] = $adminID;
            $update['date'] = $date;

            if(updateToDbPrepared($con, 'canned_msg', $update, array('id' => $editID))){
                $a = $update;
                $msg = errorMsgAdmin($lang['CH283']);
            }else{
                setSession('msgCallBack', successMsgAdmin($lang['CH284']));
                redirectTo(adminLink($controller,true));
            }
        }

        }else{
            redirectTo(adminLink($controller,true));
        }
    }
}