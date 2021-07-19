<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
 
$remUserName = $remPassword = $remBox = $lock = ''; $fullLayout = 0;

if(isset($_SESSION[N_APP.'AdminToken'])){
    header('Location: '. $adminBaseURL);
    echo '<meta http-equiv="refresh" content="1;url='.$adminBaseURL.'">';
    exit();
}

if(isset($_COOKIE[N_APP.'_admin_remember']) && $_COOKIE[N_APP.'_admin_remember'] == 'on') {
    $remUserName = raino_trim($_COOKIE[N_APP.'_admin_email']);
    $remPassword = raino_trim($_COOKIE[N_APP.'_admin_password']);
    $remBox = ' checked="" ';
}

//Message Callback
if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

//Load Image Verifcation
extract(loadCapthca($con));
$admin_login_page = filBoolean($admin_login_page);

if($admin_login_page){
    $cap_type = strtolower($cap_type);
    $customCapPath = PLG_DIR.'captcha'.D_S.$cap_type.'_cap.php';
    define('CAP_GEN',1);
    define('CAP_VERIFY',1);
}

//Basic Settings
$basicSettings = getBasicSettings($con, 'app_name,html_app');
    
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(isset($_POST['email']) && isset($_POST['password'])){
        
        $emailBox = escapeTrim($con,$_POST['email']);
        $passwordBox = passwordHash(escapeTrim($con,$_POST['password']));
    
        if(isset($_POST['remember'])){
            setcookie(N_APP.'_admin_email', $_POST['email'], time() + (86400 * 300));
            setcookie(N_APP.'_admin_password', $_POST['password'], time() + (86400 * 300)); 
            setcookie(N_APP.'_admin_remember', 'on', time() + (86400 * 300));         
        }else{
             setcookie(N_APP.'_admin_remember', 'off', time() + (86400 * 300));    
        }
        
        if($admin_login_page)
            require LIB_DIR.'verify-verification.php';
        
        if(!isset($error)){ 
            $row = mysqliPreparedQuery($con, 'SELECT id,pass,role,name,logo FROM '.DB_PREFIX.'admin WHERE user=?','s',array($emailBox));
            if($row !== false) {
                $adminID = $row['id'];
                if ($row['pass'] == $passwordBox) {
                    //Valid User
                    $rowA = mysqliPreparedQuery($con, 'SELECT name,data FROM '.DB_PREFIX.'admin_roles WHERE id=?','i',array($row['role']));
                    if($rowA !== false) {
                        //Valid Admin Role
                        $admin_login_page = false;
                        $lock = 'disabled="" ';
                        $logged_time = date('m/d/Y h:i:sA');
                        $msg = successMsgAdmin('<meta http-equiv="refresh" content="1;url=' . $adminBaseURL . '">'.$lang['CH126']);
                        insertToDbPrepared($con, 'admin_history', array('admin_id' => $adminID, 'logged_time' => $logged_time, 'ip' => $ip, 'browser' => $browser, 'role' => $rowA['name']));
                        $activeSessionID = mysqli_insert_id($con);
                        $remUserName = $remPassword = $remBox = '';
                        $privilege = dbStrToArr($rowA['data']);
                        $defaultCon = 'dashboard';
                        if($privilege[0] != 'all'){
                            if(!in_array('dashboard', $privilege))
                                $defaultCon = $privilege[0];
                        }
                        if (defined('SESSION_LOCk')){
                            if(SESSION_LOCk) {
                                $activeSession = md5($ip.$browser.session_id());
                                updateToDbPrepared($con, 'admin', array('active_session' => $activeSession, 'active_session_id' => $activeSessionID, 'last_visit' => date("Y-m-d H:i:s")), array('id' => $adminID));
                            }else{
                                updateToDbPrepared($con, 'admin', array('active_session_id' => $activeSessionID, 'last_visit' => date("Y-m-d H:i:s")), array('id' => $adminID));
                            }
                        }
                        setSession(array('AdminToken' => true, 'AdminID' => $adminID, 'AdminName' => $row['name'], 'AdminLogo' => $row['logo'] , 'Role' => $rowA['name'], 'RoleData' => $rowA['data'], 'DefaultCon' => $defaultCon, 'activeSessionID' => $activeSessionID), null, 'admin');
                    }else{
                        $msg = errorMsgAdmin($lang['CH122']);
                    }
                } else {
                    $msg = errorMsgAdmin($lang['CH123']);
                }
           } else {
             $msg = errorMsgAdmin($lang['CH124']);
           }
       }else{
            $msg = errorMsgAdmin($error);
       }
   }else{
        $msg = errorMsgAdmin($lang['CH125']);
   }
}

if($admin_login_page)
    require LIB_DIR.'generate-verification.php';