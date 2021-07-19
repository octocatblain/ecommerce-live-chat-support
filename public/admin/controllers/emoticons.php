<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Emoticons', $lang['CH310'], true);
$subTitle = trans('Manage Emoticon Packs', $lang['CH311'], true);
$mainPage = true;

$htmlLibs = array('dataTables');
if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

//Status Change
if($pointOut === 'status'){
    $status = 0;
    if($args[0] === 'disable')  $status = 0;  else $status = 1;
    $id = intval($args[1]);

    mysqli_query($con, "UPDATE ".DB_PREFIX."emoticon SET status=$status WHERE id=$id");
    setSession('msgCallBack', successMsgAdmin($lang['CH295']));
    redirectTo(adminLink($controller, true));
}

if($pointOut === 'status-icon'){
    $status = 0;
    if($args[0] === 'disable')  $status = 0;  else $status = 1;
    $id = intval($args[1]);
    $pageid = intval($args[2]);
    mysqli_query($con, "UPDATE ".DB_PREFIX."emoticon_data SET status=$status WHERE id=$id");
    setSession('msgCallBack', successMsgAdmin($lang['CH312']));
    redirectTo(adminLink($controller.'/view-icons/'.$pageid, true));
}

if($pointOut === 'status-display'){
    $status = 0;
    if($args[0] === 'no')  $status = 0;  else $status = 1;
    $id = intval($args[1]);
    $pageid = intval($args[2]);

    mysqli_query($con, "UPDATE ".DB_PREFIX."emoticon_data SET display=$status WHERE id=$id");
    setSession('msgCallBack', successMsgAdmin($lang['CH313']));
    redirectTo(adminLink($controller.'/view-icons/'.$pageid, true));
}

//Delete Emoticon Packs
if($pointOut === 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        if($delID !== 1) {
            $sql = 'DELETE FROM ' . DB_PREFIX . 'emoticon WHERE id=' . $args[0];
            if (mysqli_query($con, $sql)) {
                $sql = 'DELETE FROM ' . DB_PREFIX . 'emoticon_data WHERE emoticon=' . $args[0];
                if (mysqli_query($con, $sql))
                    die('1');
            }
        }
    }
    die('0');
}

//Delete Emoticon
if($pointOut === 'delete-icon'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        $sql = 'DELETE FROM ' . DB_PREFIX . 'emoticon_data WHERE id=' . $args[0];
        if (mysqli_query($con, $sql))
            die('1');
    }
    die('0');
}

if($pointOut === 'create' || $pointOut === 'settings'){
    $pageTitle = trans('Create New Emoticon Pack',$lang['CH314'],true);
    $subTitle = trans('Emoticon Pack Details', $lang['CH315'], true);
    $mainPage = false;
    $pack = array('name' => '', 'type' => 'image', 'data' => '', 'status' => 1);

    if($pointOut === 'settings'){
        $subTitle = trans('Edit Emoticon Pack', $lang['CH316'], true);
        $editID = intval($args[0]);
        if($editID == '' || $editID === NULL || $editID === 0)
            redirectTo(adminLink($controller, true));
        $pack = mysqliPreparedQuery($con, 'SELECT name,type,data,status FROM ' . DB_PREFIX . 'emoticon WHERE id=?', 'i', array($editID));
    }

    if(isset($_POST['createEmoticon'])) {
        $pack = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);
        unset($pack['createEmoticon']);
        $pack['added_by'] = getSession('AdminID');
        $pack['date'] = date('m/d/Y h:i:sA');

        if($pointOut === 'settings'){
            $res = updateToDbPrepared($con, 'emoticon', $pack, array('id' => $editID));
            $suMsg = trans('Emoticon Pack settings updated successfully', $lang['CH317'], true);
        }else{
            $res = insertToDbPrepared($con, 'emoticon', $pack);
            $suMsg = trans('Emoticon Pack created successfully', $lang['CH318'], true);
        }
        if ($res)
            setSession('msgCallBack', errorMsgAdmin($lang['CH319']));
        else
            setSession('msgCallBack', successMsgAdmin($suMsg));
        redirectTo(adminLink($controller, true));
    }
}

//Add & Edit Icon
if($pointOut === 'add-icon' || $pointOut === 'edit-icon'){
    $mainPage = false;
    $eData = array('name' => '', 'code' => '', 'data' => '', 'display' => 1, 'status' => 1);
    $id = intval($args[0]);

    $addData = mysqliPreparedQuery($con, 'SELECT name,type FROM ' . DB_PREFIX . 'emoticon WHERE id=?', 'i', array($id));
    if ($addData !== false) {
        $pageTitle = $addData['name'];
        $subTitle = trans('Add Emoticon', $lang['CH320'], true);
        if($pointOut === 'edit-icon'){
            $subTitle = trans('Edit Emoticon', $lang['CH321'], true);
            $editID = intval($args[1]);
            $eData = mysqliPreparedQuery($con, 'SELECT id,name,code,data,display,status FROM ' . DB_PREFIX . 'emoticon_data WHERE id=?', 'i', array($editID));
        }
        if(isset($_POST['addEmoticon'])) {
            $myValues = array_map_recursive(function ($item) use ($con) {
                return escapeTrim($con, $item);
            }, $_POST);
            unset($myValues['addEmoticon']);
            $myValues['emoticon'] = $id;
            if($pointOut === 'edit-icon'){
                $res = updateToDbPrepared($con, 'emoticon_data', $myValues, array('id' => $editID));
                $suMsg = trans('Emoticon updated successfully', $lang['CH322'], true);
            }else{
                $res = insertToDbPrepared($con, 'emoticon_data', $myValues);
                $suMsg = trans('Emoticon added successfully', $lang['CH323'], true);
            }
            if ($res)
                setSession('msgCallBack', errorMsgAdmin($lang['CH319']));
            else
                setSession('msgCallBack', successMsgAdmin($suMsg));
            redirectTo(adminLink($controller.'/view-icons/'.$id, true));
        }
    }else {
        redirectTo(adminLink($controller, true));
    }
}

//View Icons
if($pointOut === 'view-icons'){
    $mainPage = false;
    $id = intval($args[0]);
    $viewData = mysqliPreparedQuery($con, 'SELECT name,type FROM ' . DB_PREFIX . 'emoticon WHERE id=?', 'i', array($id));
    if ($viewData !== false) {
        $pageTitle = $viewData['name'];
        $subTitle = ' Manage Emoticons';
        $emoticons = mysqliPreparedQuery($con, 'SELECT id,name,code,data,display,status FROM ' . DB_PREFIX . 'emoticon_data WHERE emoticon=?', 'i', array($id),false);
    }
}

//Manage Emoticon Packs
if($mainPage)
    $data = mysqliPreparedQuery($con, 'SELECT id,name,added_by,date,status FROM ' . DB_PREFIX . 'emoticon', '', array(),false);
