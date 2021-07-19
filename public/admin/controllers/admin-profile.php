<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));



$pageTitle = trans('Administrators Accounts', $lang['CH519'], true);
$subTitle = trans('Admin Details', $lang['CH520'], true);
$avatarPage = $passPage = false;

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    if(isset($_FILES['logoUpload']) && $_FILES['logoUpload']['name'] !== ''){
        $isUploaded = secureImageUpload($_FILES['logoUpload']);
        if($isUploaded[0]) {
            if(updateToDbPrepared($con, 'admin', array('logo' => $isUploaded[1]), array('id' => $adminID))){
                $msg = successMsgAdmin($lang['CH522']);
            }else{
                setSession('AdminLogo', $isUploaded[1] , 'admin');
                setSession('msgCallBack', successMsgAdmin($lang['CH521']));
                redirectTo(adminLink($controller, true));
            }
        }else
            $msg = errorMsgAdmin($isUploaded[1]);
    }
        
    if(isset($_POST['passChange'])){
        $passPage = true;

        $infoRow = mysqliPreparedQuery($con, 'SELECT pass FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($adminID));
        $admin_oldPass = trim($infoRow['pass']);
       
        $admin_user = escapeTrim($con, $_POST['admin_user']);
        $admin_name = escapeTrim($con, $_POST['admin_name']);
        $new_pass = passwordHash(escapeTrim($con, $_POST['new_pass']));
        $retype_pass = passwordHash(escapeTrim($con, $_POST['retype_pass']));
        $old_pass = passwordHash(escapeTrim($con, $_POST['old_pass']));
    
        if($new_pass === $retype_pass){
            if($old_pass === $admin_oldPass){
                $result = updateToDbPrepared($con, 'admin', array('user' => $admin_user, 'pass' => $new_pass, 'name' => $admin_name), array('id' => $adminID));
                if ($result)
                    $msg = errorMsgAdmin($lang['CH524']);
                else {
                    setSession('AdminName', $admin_name, 'admin');
                    setSession('msgCallBack', successMsgAdmin($lang['CH523']));
                    redirectTo(adminLink($controller, true));
                }
            }else{
                $msg = errorMsgAdmin($lang['CH525']);
            }
        }else{
            $msg = errorMsgAdmin($lang['CH526']);
        }
  }
}

$adminData = mysqliPreparedQuery($con, 'SELECT user,reg_date,reg_ip FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($adminID));
$adminLog = mysqliPreparedQuery($con, 'SELECT logged_time,ip FROM ' . DB_PREFIX . 'admin_history WHERE admin_id=? ORDER BY ID DESC LIMIT 1', 'i', array($adminID),true);

if($privilege[0] ==='all')
    $privilegeBox = '<span class="label label-success">'.$lang['CH370'].'</span>';
else
    $privilegeBox = '<span class="label label-danger">'.$lang['CH369'].'</span>';

if ($passPage){
    $page1 = $page3 = '';
    $page2 = "active";
}elseif($avatarPage){
    $page1 = $page2 = '';
    $page3 = "active";
}else{
    $page1 = "active";
    $page2 = $page3 = '';
}