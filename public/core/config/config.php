<?php
defined('ROOT_DIR') or die(header('HTTP/1.1 403 Forbidden'));

/**
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2020 ProThemes.Biz
 *
 */
 
//System Default Error Reporting
error_reporting(1);
//error_reporting(E_ALL);
//ini_set("display_errors", "1");

//Database Configuration
require CONFIG_DIR.'db.config.php';

//Application Configuration
require CONFIG_DIR.'app.config.php';

//OS Directory Separator
define('D_S',DIRECTORY_SEPARATOR);

//Define Directories
define('CON_DIR',APP_DIR.'controllers'.D_S);
define('HEL_DIR',APP_DIR.'helpers'.D_S);
define('LIB_DIR',APP_DIR.'library'.D_S);
define('MOD_DIR',APP_DIR.'models'.D_S);
define('PLG_DIR',APP_DIR.'plugins'.D_S);
define('LOG_DIR',APP_DIR.'logs'.D_S);
define('ROU_DIR',APP_DIR.'routes'.D_S);
define('TMP_DIR',APP_DIR.'temp'.D_S);

//Secure Hash Code #61.92.103.92.101.100.103
define('HASH_CODE', md5($item_purchase_code));

//mod_rewrite
define("MOD_REWRITE", true);

//Language Translation
define("LANG_TRANS", true);

//Application Error Reporting
define("ERR_R", true);

//Error Reporting File
define("ERR_R_FILE", 'error.tdata');

//Set System Default Error Reporting File
ini_set("error_log", LOG_DIR.ERR_R_FILE);

//Set Default Time Zone #List of Supported Timezones - http://php.net/manual/en/timezones.php
date_default_timezone_set('Asia/Calcutta');

//Custom Router
define("CUSTOM_ROUTE", true);

//Enbale Session Lock (Prevents multiple logins from same account, hijacking etc..)
define("SESSION_LOCk", true);

//Enable Plugin System
define('PLUG_SYS', false);

//Admin Path
define('ADMIN_DIR_NAME','admin');
define('ADMIN_PATH',ADMIN_DIR_NAME.'/');

//Cloudflare Fix
if(isset($_SERVER['HTTP_CF_VISITOR'])){
    $visitor = json_decode($_SERVER['HTTP_CF_VISITOR']);
    if($visitor->scheme == 'https')
        $_SERVER["HTTPS"] = 'on';
}
if(isset($_SERVER['HTTP_CF_CONNECTING_IP']))
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

//HTTP Proxy Fix
if(isset($_SERVER['HTTP_HTTPS']))
    $_SERVER["HTTPS"] = $_SERVER['HTTP_HTTPS'];

//Domain Host
$parseHost = explode('/',$baseURL);
$serverHost = $parseHost[0];
$subPath = str_replace($serverHost.'/','',$baseURL);

//Base URL
$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
$www = (strpos($_SERVER['HTTP_HOST'], 'www.') === false) ? '' : 'www.';
$baseURL = $protocol.$www.$baseURL;
define('BASEURL',$baseURL);

//Active Link
$currentLink = $protocol.$www.$serverHost.$_SERVER["REQUEST_URI"];

//Hide PHP Version
header('X-Powered-By: Rainbow Framework');

//Rainbow Analytics v2.0
define('ANALYTICS', true);

//Capture Bot and Web spider traffics?
define('ANALYTICS_BOT', false);

//Links
$links = array();
$links['test'] = 'test';

//Chat Settings

//Remember user details using cookies
define("REM_USER_DETAILS", true);
define("PINKY_CHAT", true);