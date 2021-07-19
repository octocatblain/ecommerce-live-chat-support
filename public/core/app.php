<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */

//Current Date & User IP
$date = date('jS F Y');
$year = date('Y');
$ip = getUserIP(); 

//Higher Level Plugin Execution
if(PLUG_SYS)
    require LIB_DIR.'user_levelx.php';

//Load Basic Settings
$siteInfoRow = getBasicSettings($con);

$site_name = shortCodeFilter($siteInfoRow['site_name']);
$app_name = shortCodeFilter($siteInfoRow['app_name']);
$html_app = shortCodeFilter($siteInfoRow['html_app']);
$adminEmail = trim($siteInfoRow['email']);
$doForce = dbStrToArr($siteInfoRow['doForce']);
$copyright = htmlspecialchars_decode(shortCodeFilter($siteInfoRow['copyright']));
$other = dbStrToArr($siteInfoRow['other_settings']);
$forceHttps = filter_var($doForce[0], FILTER_VALIDATE_BOOLEAN);
$forceWww = filter_var($doForce[1], FILTER_VALIDATE_BOOLEAN);

//WWW Redirect
if($forceWww){
    if ((strpos($_SERVER['HTTP_HOST'], 'www.') === false)) {
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        header('Location: '.$protocol.'www.'. $serverHost . $_SERVER["REQUEST_URI"],true,301);
        exit();
    }
}

//HTTPS Redirect
if($forceHttps) {
    if (!isset($_SERVER["HTTPS"])) {
        header('Location: '.'https://'. $serverHost . $_SERVER["REQUEST_URI"],true,301);
        exit();
    }
}

//Check User IP is banned
ipBanCheck($con,$ip,$site_name);

//Get the default theme
$default_theme = '';
if(isset($_SESSION[N_APP.'UserSelectedTheme']))
    $default_theme = raino_trim($_SESSION[N_APP.'UserSelectedTheme']); //User Selected Theme
elseif(isset($_SESSION[N_APP.'AdminSelectedTheme'])){
    $default_theme = raino_trim($_SESSION[N_APP.'AdminSelectedTheme']); //Admin Selected Theme
    $footerAddArr[] = previewBox();
}else
    $default_theme = getTheme($con); //Load Default Theme
    
$theme_path = $baseURL.'theme' . '/' . $default_theme . '/';
define('THEMEURL', $theme_path);
define('THEME_DIR', ROOT_DIR .'theme' . D_S . $default_theme . D_S);

//Load theme settings
$themeOptions = $tO = getThemeOptions($con,$default_theme,$baseURL);

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

//Maintenance Mode
if(isSelected($other['other']['maintenance'])){
    if(!isset($_SESSION[N_APP.'AdminToken']))
        die('Chat under maintenance mode!');
}

//Base Link with Language Code
$baseLink = createLink('',true);

//Controller - Higher Level Plugin Execution
if(PLUG_SYS)
    require LIB_DIR.'user_level1.php';

$path = CON_DIR . $controller . '.php';
$csPath = CON_DIR. $default_theme . D_S. $controller . '.php';
if(file_exists($csPath)) {
    require($csPath);
} elseif(file_exists($path)){
    require($path);
} else {
    writeLog('Controller File ("'.$controller.'.php") Not Found');
    $controller = CON_ERR;
    require(CON_DIR. $controller . '.php');
}

//Controller - Lower Level Plugin Execution
if(PLUG_SYS)
    require LIB_DIR.'user_level2.php';

//Last Callback Link
if(!isset($_SESSION[N_APP.'callbackLinks']))
    $_SESSION[N_APP.'callbackLinks'] = array();
array_unshift($_SESSION[N_APP.'callbackLinks'],$currentLink);
$_SESSION[N_APP.'callbackLinks'] = array_slice($_SESSION[N_APP.'callbackLinks'], 0,4);
$_SESSION[N_APP.'LastCallbackLink'] = $currentLink;

//Generate Page Title
if(!isset($metaTitle)){
    $metaTitle = ''; 
    if(isset($pageTitle))
        $metaTitle = $pageTitle.' | '. $site_name; 
     else
        $metaTitle = $title;
}

//Lower Level Plugin Execution
if(PLUG_SYS)
    require LIB_DIR.'user_levely.php';

define('VIEW', $controller);