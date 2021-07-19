<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Privilege Management', $lang['CH565'], true);
$subTitle = trans('Admin Groups', $lang['CH566'], true);

$htmlLibs = array('select2');

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

$menuLinks = getAdminPagesLinks($menuBarLinks);
$data = array(); $data['data'][0] = 'all'; $data['name'] = '';

if($pointOut === 'add' || $pointOut === 'edit'){
    $pageTitle = trans('Add New Privileges', $lang['CH567'], true);
    $subTitle = trans('Role Details', $lang['CH568'], true);
    if(isset($_POST['sel_access'])) {
        $myValues = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);

        if ($myValues['sel_access'] == 'all')
            $myValues['data'] = arrToDbStr($con, array('all'));
        else
            $myValues['data'] = arrToDbStr($con, $myValues['data']);

        unset($myValues['sel_access']);
        unset($myValues['save']);
        $myValues['added_by'] = getSession('AdminID');
        $myValues['added_date'] = date('m/d/Y h:i:sA');

        if($pointOut == 'edit'){
            $editID = intval($myValues['editID']);
            unset($myValues['editID']);
            if($editID !== 1) {
                if (updateToDbPrepared($con, 'admin_roles', $myValues, array('id' => $editID)))
                    setSession('msgCallBack', errorMsgAdmin($lang['CH569']));
                else
                    setSession('msgCallBack', successMsgAdmin($lang['CH570']));
            }
        }else {
            if (insertToDbPrepared($con, 'admin_roles', $myValues))
                setSession('msgCallBack', errorMsgAdmin($lang['CH571']));
            else
                setSession('msgCallBack', successMsgAdmin($lang['CH572']));
        }
        redirectTo(adminLink($controller, true));
    }
}

if($pointOut === 'edit'){
    $pageTitle = trans('Edit Privileges', $lang['CH573'], true);
    $subTitle = trans('Update Roles', $lang['CH574'], true);

    if(isset($args[0]) && $args[0] != ''){
        $data = mysqliPreparedQuery($con, 'SELECT name,data FROM '.DB_PREFIX.'admin_roles WHERE id=?', 'i', array($args[0]));
        if ($data !== false) {
            $data['data'] = dbStrToArr($data['data']);
            $editID = $args[0];
        }
    }
    if(!isset($editID))
        redirectTo(adminLink($controller, true));
}

if($pointOut === 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        if($delID !== 1) {
            $sql = 'DELETE FROM ' . DB_PREFIX . 'admin_roles WHERE id=' . $args[0];
            if (mysqli_query($con, $sql))
                die('1');
        }
    }
    die('0');
}

$result = mysqli_query($con, 'SELECT id,name,data,added_date FROM ' . DB_PREFIX . 'admin_roles');