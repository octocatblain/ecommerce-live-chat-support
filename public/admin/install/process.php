<?php

/*
 * @author Balaji
 */

error_reporting(1);

//License
$success = false;

//ROOT Path
define('ROOT_DIR', realpath(dirname(dirname(dirname(__FILE__)))) .DIRECTORY_SEPARATOR);

//Application Path
define('APP_DIR', ROOT_DIR .'core'.DIRECTORY_SEPARATOR);

//Configuration Path
define('CONFIG_DIR', APP_DIR .'config'.DIRECTORY_SEPARATOR);

//Installer Path
define('INSTALL_DIR', ROOT_DIR .'admin'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR);

function slashes($str){
    return str_replace("'","\'",$str);
}

$data_host = slashes(Trim($_POST['data_host']));
$data_name = slashes(Trim($_POST['data_name']));
$data_user = slashes(Trim($_POST['data_user']));
$data_pass = slashes(Trim($_POST['data_pass']));
$data_sec = slashes(Trim($_POST['data_sec']));
$data_prefix = slashes(Trim($_POST['data_prefix']));
$domain = urlencode($_POST['data_domain']);
$licPath = 'http://lic.prothemes.biz/pinky.php';

$con = mysqli_connect($data_host,$data_user,$data_pass,$data_name);

if (mysqli_connect_errno())
    die('Database Connection failed');


// Don't crack license checker. It will crash whole site and handle incorrectly!

// If you want to request new purchase code for "localhost" installation and for "development" site (or)
// Reset the old code for your new domain name than contact support! 

// For Support, mail to us: rainbowbalajib [at] gmail.com

$licData = file_get_contents($licPath."?code=$data_sec&domain=$domain");

if($licData == '')
    die('Unable connect to license server!');

$licArr = json_decode($licData, true);
$stats = intval($licArr['stats']);
$authCode = $licArr['authCode'];
$addtionalConfig = $licArr['config'];

if($stats === 1)
    $success = true;
elseif($stats === 0)
    die('Item purchase code not valid');
elseif($stats === 2)
    die('Already code used on another domain! Contact Support');
else
    die('Item purchase code not valid / banned');

if($authCode == '')
    $authCode = Md5($domain);
    
$domain = str_replace(array('http://','https://','www.'), '', urldecode($domain));
if(substr($domain, -1) != '/') $domain = $domain.'/';

$data = '<?php
defined(\'ROOT_DIR\') or die(header(\'HTTP/1.1 403 Forbidden\'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */

// --- Database Settings ---

//Database Hostname
define(\'DB_HOST\', \''.$data_host.'\');

//Database Username
define(\'DB_USER\', \''.$data_user.'\');

//Database Password
define(\'DB_PASS\', \''.$data_pass.'\');

//Database Name
define(\'DB_NAME\', \''.$data_name.'\');

//DB Tables Prefix (Avoid name conflicts)
define(\'DB_PREFIX\',\''.$data_prefix.'\');

//Base URL (Without http:// & https://)
$baseURL = \''.$domain.'\';

//Item Purchase Code
$item_purchase_code = \''.$data_sec.'\';

//Domain Security Code
$authCode = \''.$authCode.'\';

';

file_put_contents(CONFIG_DIR.'db.config.php', $data);
file_put_contents(CONFIG_DIR.'config.php', $addtionalConfig, FILE_APPEND);

die('1');