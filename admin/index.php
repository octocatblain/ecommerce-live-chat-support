<?php
session_start();

//Application Admin Path 
define('ADMIN_DIR', realpath(dirname(__FILE__)) .DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(dirname(dirname(__FILE__))) .DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR .'core'.DIRECTORY_SEPARATOR);
define('CONFIG_DIR', APP_DIR .'config'.DIRECTORY_SEPARATOR);
define('ADMIN_CON_DIR', ADMIN_DIR.'controllers'.DIRECTORY_SEPARATOR);

//Load Configuration & Functions
require CONFIG_DIR.'config.php';
require APP_DIR.'functions.php';

//Check Installation
detectInstaller();

//Set Admin Base URL
$adminBaseURL = $baseURL.ADMIN_PATH;

//Admin Panel Theme
$admin_theme = 'default';

//Set Admin Theme Path
define('ADMIN_THEME_DIR', ADMIN_DIR. 'theme' . D_S . $admin_theme . D_S);

//Database Connection
$con = dbConnect();

//Start the Application
require ADMIN_DIR.'app.php';

//Output
if($fullLayout){
    require ADMIN_THEME_DIR.'header.php';
    require ADMIN_THEME_DIR.VIEW.'.php';
    require ADMIN_THEME_DIR.'footer.php';
}else{
    require ADMIN_THEME_DIR.VIEW.'.php';
}

//Close the database conncetion
mysqli_close($con);