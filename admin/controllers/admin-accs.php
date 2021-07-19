<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Administrator Accounts', $lang['CH554'], true);
$subTitle = trans('Admin List', $lang['CH555'], true);

$htmlLibs = array('dataTables');

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

if($pointOut == 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        if($delID !== 1) {
            $sql = 'DELETE FROM ' . DB_PREFIX . 'admin WHERE id=' . $delID;
            if (mysqli_query($con, $sql))
                die('1');
        }
    }
    die('0');
}

if($pointOut === 'groupdel'){

    $myValues = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    $delValues = implode(', ', $myValues['id']);

    $sql = 'DELETE FROM ' . DB_PREFIX . 'admin WHERE id IN ('.$delValues.')';
    if (mysqli_query($con, $sql))
        die('1');
    die('0');
}

if($pointOut === 'add' || $pointOut === 'edit'){
    $pageTitle = trans('Create Admin Account', $lang['CH564'], true);
    $subTitle = trans('New Admin',$lang['CH563'],true);
    $editError = $success = true;
    $myValues = array();
    $myValues['name'] = $myValues['user'] = '';
    $myValues['role'] = 1;
    $dLogo = $myValues['logo'] = 'admin/theme/default/dist/img/user-default.png';
    $roles = mysqliPreparedQuery($con, 'SELECT id,name FROM ' . DB_PREFIX . 'admin_roles');

    if(isset($_POST['name'])) {

        $myValues = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);

        if ($pointOut === 'edit') {
            $editError = false;
            $editID = $myValues['edit'];
            $myValuesZ = mysqliPreparedQuery($con, 'SELECT pass,logo FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($editID));
            unset($myValues['edit']);
        }

        if (isset($_FILES['userLogo']) && $_FILES['userLogo']['name'] != '') {
            $isUploaded = secureImageUpload($_FILES['userLogo']);
            if ($isUploaded[0]){
                $myValues['logo'] = $isUploaded[1];
                if ($pointOut === 'edit')
                    updateToDbPrepared($con, 'admin', array('logo' => $myValues['logo']), array('id' => $editID));
            }else {
                    $msg = errorMsgAdmin($isUploaded[1]);
                    $success = false;
                    unset($myValues['pass'], $myValues['repass']);
                }
        } else {
            if ($pointOut === 'edit')
                $myValues['logo'] = $myValuesZ['logo'];
            else
                $myValues['logo'] = $dLogo;
        }

        if ($success) {
            if ($myValues['pass'] === $myValues['repass']) {
                unset($myValues['repass'], $myValues['save'], $myValues['userLogo']);
                $nss = true;
                if ($pointOut === 'edit') {
                    if ($myValuesZ['pass'] === $myValues['pass']) $nss = false;
                }
                if ($nss) $myValues['pass'] = passwordHash($myValues['pass']);

                $myValues['reg_date'] = date('m/d/Y h:i:sA');
                $myValues['reg_ip'] = $ip;
                if($pointOut === 'add'){
                    $data = mysqliPreparedQuery($con, 'SELECT id FROM ' . DB_PREFIX . 'admin WHERE user=?', 's', array($myValues['user']));
                    if ($data !== false) {
                        $msg = errorMsgAdmin($lang['CH560']);
                    } else {
                        if (insertToDbPrepared($con, 'admin', $myValues)) {
                            $msg = errorMsgAdmin($lang['CH562']);
                            unset($myValues['pass'], $myValues['repass']);
                        } else {
                            setSession('msgCallBack', successMsgAdmin($lang['CH561']));
                            redirectTo(adminLink($controller, true));
                        }
                    }
                }elseif($pointOut === 'edit') {
                    unset($myValues['reg_date']);
                    $data = mysqliPreparedQuery($con, 'SELECT id FROM ' . DB_PREFIX . 'admin WHERE user=?', 's', array($myValues['user']));
                    $nss = true;
                    if ($data !== false) {
                        if($data['id'] !== intval($editID)){
                            $nss = false;
                            $msg = errorMsgAdmin($lang['CH560']);
                            unset($myValues['pass'], $myValues['repass']);
                        }
                    }
                    if ($nss){
                        if (updateToDbPrepared($con, 'admin', $myValues, array('id' => $editID))) {
                            $msg = errorMsgAdmin($lang['CH559']);
                            unset($myValues['pass'], $myValues['repass']);
                        } else {
                            setSession('msgCallBack', successMsgAdmin($lang['CH558']));
                            redirectTo(adminLink($controller, true));
                        }
                    }
                }
            } else {
                $msg = errorMsgAdmin($lang['CH526']);
            }
         }
    }
}

if($pointOut === 'edit'){
    $pageTitle = trans('Edit Admin Account', $lang['CH557'], true);
    $subTitle = trans('Update Admin', $lang['CH556'], true);
    if($editError) {
        if (isset($args[0]) && $args[0] != '') {
            $args[0] = intval($args[0]);
            if ($args[0] !== 1) {
                $myValues = mysqliPreparedQuery($con, 'SELECT name,user,pass,role,logo FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($args[0]));
                if ($myValues !== false) {
                    $myValues['repass'] = $myValues['pass'];
                    $editError = false;
                }
            }
        }
        if ($editError) redirectTo(adminLink($controller, true));
    }
}