<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

//Current Date & User IP
$date = date('jS F Y');
$year = date('Y');
$ip = getUserIP();
$browser = escapeTrim($con,getUA());

//Basic Settings
$basicSettings = getBasicSettings($con, 'app_name,html_app,copyright');
$basicSettings['app_name'] = shortCodeFilter($basicSettings['app_name']);
$basicSettings['html_app'] = htmlspecialchars_decode(shortCodeFilter($basicSettings['html_app']));
$basicSettings['copyright'] = htmlspecialchars_decode(shortCodeFilter($basicSettings['copyright']));

//Theme Settings
$theme_path = $adminBaseURL.'theme' . '/' . $admin_theme . '/';
define('THEMEURL', $theme_path);
$fullLayout = 1; $htmlLibs = array();

//Load Language
$isRTL = false;
$loadedLanguages = getAvailableLanguages($con);
if(!isset($_GET['route'])){

    if(isset($_SESSION[N_APP.'UserSelectedLang']))
        $loadLangCode = strtolower(raino_trim($_SESSION[N_APP.'UserSelectedLang'])); //User Selected Language
    else
        $loadLangCode = getLang($con);  //Default Language

    define('ACTIVE_LANG',$loadLangCode);
    $lang = getLangData($loadLangCode,$con);
}

//Load Router System
require ROU_DIR.'router.php';

//Loaded Language is RTL
$isRTL = isRTLlang($loadedLanguages);

if(issetSession('AdminToken')){
    $adminInfo = getSession(array('AdminName', 'AdminLogo', 'AdminID', 'Role', 'RoleData', 'DefaultCon'));

    $adminID = $adminInfo['AdminID'];
    $adminName = $adminInfo['AdminName'];
    $adminLogo = $adminInfo['AdminLogo'];
    $admin_logo_path = createLink($adminLogo,true,true);
    $privilege = dbStrToArr($adminInfo['RoleData']);

    //Check for multiple logins from same account
    if (defined('SESSION_LOCk')) {
        if(SESSION_LOCk) {
            $multipleLogin = true;
            $actSes = mysqliPreparedQuery($con, 'SELECT active_session FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($adminID));
            if ($actSes !== false) {
                $nowSession = md5($ip.$browser.session_id());
                if($actSes['active_session'] === $nowSession)
                    $multipleLogin = false;
            }
            if($multipleLogin){
                clearSession(null,'admin');
                setSession('msgCallBack', errorMsgAdmin($lang['CH824']));
                redirectTo($adminBaseURL);
            }
        }
    }

    if($controller === 'AdminDefaultCon')
        $controller = $adminInfo['DefaultCon'];

    if($privilege[0] !== 'all'){
        if(!in_array($controller.$pointOutA, $privilege) && !in_array($controller.'?::all', $privilege)) {
            if($controller !== 'ajax' && $pointOut !== 'logout')
                $controller = 'privilege';
        }
    }
}else{
    $controller = 'login';  
}

//Load Admin Links
define('ADMIN_LINKS',true);
require ADMIN_CON_DIR.'links.php';

//Create Link
$baseLink = createLink('',true);
$adminBaseLink = adminLink('',true);

$path = ADMIN_CON_DIR . $controller . '.php';
$csPath = ADMIN_CON_DIR. $admin_theme . D_S. $controller . '.php';
if(file_exists($csPath)) {
    require($csPath);
} elseif(file_exists($path)){
    require($path);
} else {
    $controller = "error";
    require ADMIN_CON_DIR. $controller . '.php';
}

updateToDbPrepared($con, 'admin', array('last_visit_page' => $currentLink, 'last_visit' => date("Y-m-d H:i:s")), array('id' => $adminID));

define('VIEW', $controller);