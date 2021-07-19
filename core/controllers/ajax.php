<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */

//AJAX ONLY

//POST Request Handler 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    //AJAX Image Verification
    if($pointOut === 'verification') {
        //Load Image Verifcation
        extract(loadCapthca($con));
        
        $cap_type = strtolower($cap_type);
        $customCapPath = PLG_DIR.'captcha'.DIRECTORY_SEPARATOR.$cap_type.'_cap.php';
        define('CAP_VERIFY',1);
        
        //Verify image verification.
        require LIB_DIR.'verify-verification.php';  
        
        if(isset($args[0]) && $args[0] == 'get-auth'){
            if(!isset($error)){
                $secKey = randomChar(9);
                $_SESSION[N_APP.'sec'.$secKey] = array(1,strtotime("+5 minutes"));
                echo '1:::'.$secKey;
            }else
                echo '0:::0';
        }else{
            if(!isset($error))
                echo '1';  //Verified
            else
                echo $error; //Failed Verification
        }
        die();
    }
}

//PHP Image Verification
if($pointOut === 'phpcap'){
    $phpCap = ''; $captcha_config = array();

    if(isset($args[0]) && $args[0] != ''){
        if($args[0] == 'reload'){
            extract(loadCapthca($con));
            $phpCap = elite_captcha($color,$mode,$mul,$allowed);  
            $_SESSION[N_APP.'Cap'.$phpCap['page']] = $phpCap;
            echo $phpCap['image_src'] .':::'. $phpCap['page'];
        }elseif($args[0] == 'image'){
       	    $captcha_config = unserialize($_SESSION[N_APP.'_CAPTCHA']['config']);
	        if( !$captcha_config ) exit();
	        unset($_SESSION[N_APP.'_CAPTCHA']);
            drawPHPCap($captcha_config);
        }
        die();
    }
}

//Set Language
if($pointOut === 'lang') {
    $langCode = raino_trim($args[0]);
    if($langCode != ''){
        $_SESSION[N_APP.'UserSelectedLang'] = $langCode;
        if(isset($_SESSION[N_APP.'LastCallbackLink']))
            $goToLink = $_SESSION[N_APP.'LastCallbackLink'];
        else
            $goToLink = createLink('',true);
        header('Location:'.$goToLink,true,301);
    }else{
        echo 'Language code missing!';
    }
    die();
}

//Set Theme
if($pointOut === 'theme'){
    $themeCode = raino_trim($args[0]);
    if($themeCode == 'unset'){
        unset($_SESSION[N_APP.'UserSelectedTheme']);
        unset($_SESSION[N_APP.'AdminSelectedTheme']);
        header('Location:'. createLink('widget-test',true));
        die();
    }
    if($themeCode != ''){
        if(isThemeExists($themeCode)){
            $_SESSION[N_APP.'UserSelectedTheme'] = $themeCode;
            header('Location:'. createLink('widget-test',true));
        }else{
            stop('Theme fails to load!');
        }
    }else{
         stop('Theme name missing!');
    }

}

//Say Hello
if($pointOut === 'hello'){
    echo 'Hello';
    die();
}

//Geo IP Information
if($pointOut === 'ip-info'){
    header('Content-Type: application/json');  
    echo getMyGeoInfo($ip, true);
    die();
}

//Only Authenticated Users

//Admin Ajax Controller
if(isset($_SESSION[N_APP.'AdminToken'])){
    
    //Themes Preview 
    if($pointOut === 'templates'){
        $themeDir = raino_trim($args[0]);
        if(isThemeExists($themeDir)){
            unset($_SESSION[N_APP.'UserSelectedTheme']);
            $_SESSION[N_APP.'AdminSelectedTheme'] = $themeDir;
            header('Location:'. createLink('widget-test',true));
        }else{
            stop('Theme fails to load!');
        }
        die();
    }

}

//Troubleshooting
if($pointOut === 'troubleshoot') {

    if (trim($args[0]) != $item_purchase_code)
        die();

    if(isset($args[1]) && $args[1] != '') {
        $pointOut = $args[1];

        if($pointOut === 'phpinfo')
            phpinfo();

        if($pointOut === 'appinfo'){
            echo '
            <table>
                <tbody>
                    <tr><td>Script Name: </td><td>'. APP_NAME .'</td></tr>
                    <tr><td>Script Version: </td><td>'. VER_NO .'</td></tr>
                    <tr><td>Framework Version: </td><td>'. getFrameworkVersion() .'</td></tr>
                    <tr><td>PHP Version: </td><td>'. phpversion() .' <a href="'.createLink($controller.'/troubleshoot/'.$item_purchase_code.'/phpinfo',true).'" target="_blank">(View PHP Info)</a></td></tr>
                    <tr><td>MySQL Version: </td><td>'. mysqli_get_server_info($con) .'</td></tr>
                    <tr><td>Script Root Dir: </td><td>'. ROOT_DIR .'</td></tr>
                    <tr><td>Base URL: </td><td>'. $baseURL .'</td></tr>
                    <tr><td>Admin Base URL: </td><td>'. adminLink('',true) .'</td></tr>
                    <tr><td>Server IP: </td><td>'. $_SERVER['SERVER_ADDR'] .'</td></tr>
                    <tr><td>Server CPU Usage: </td><td>'. getServerCpuUsage() .'</td></tr>
                    <tr><td>Server Memory Usage: </td><td>'. round(getServerMemoryUsage(),2) .'</td></tr>
                </tbody>
            </table>';
        }

        if($pointOut === 'htaccess'){
            $htData = getMyData(LIB_DIR.'htaccess.backup');
            putMyData(ROOT_DIR.'.htaccess', $htData);
            $adminBaseURL = $baseURL . ADMIN_DIR_NAME . '/';
            redirectTo($adminBaseURL);
        }

        if($pointOut === 'login'){
            setSession(array('AdminToken' => true, 'AdminID' => 1, 'AdminName' => 'Troubleshoot', 'AdminLogo' => '' , 'Role' => 'Troubleshoot', 'RoleData' => '[\"all\"]', 'DefaultCon' => 'dashboard', 'activeSessionID' => 1), null, 'admin');
            $adminBaseURL = $baseURL . ADMIN_DIR_NAME . '/';
            redirectTo($adminBaseURL);
        }

    }
    die();
}

//AJAX END
die();