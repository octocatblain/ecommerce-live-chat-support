<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = "Manage Addons";
$subTitle = "Install Add-on";

if(isset($_POST['addon'])) {

     //Enter addon filename
     $addonFileName = raino_trim($_POST['addon']);

     //Addon Uploaded Path
     $target_dir = ADMIN_DIR . 'addons' . D_S;

     //Package File Path
     $file_path = $target_dir . $addonFileName;

    if (file_exists($file_path)) {

        //Temporarily extract Addons Data
        $addon_path = ADMIN_DIR . "addons/" . "ad_" . rand(1000, 999999);
        extractZip($file_path, $addon_path);

        //Check Addons Installer is exists
        if (file_exists($addon_path . "/rainbow.tdata")) {
            if (file_exists($addon_path . "/install.php")) {
                //Found - Process Installer
                require_once($addon_path . "/install.php");

                if ($activeTheme != 'default' && $activeTheme != 'simpleX') {
                    $addonRes .= "Copying Theme Files to $activeTheme<br>";
                    recurse_copy($addon_path . "/theme/default", ROOT_DIR . "/theme/$activeTheme");
                }
            } else {
                //Not Found
                $addonRes = "Addons Installer is not detected!";
                $addonError = true;
                $errType = 1;
            }
        } else {
            //Not Found
            $addonRes = "Not compatible add-on!";
            $addonError = true;
            $errType = 1;
        }
        $addonRes = str_replace(array("<br>", "<br/>", "<br />"), PHP_EOL, $addonRes);
        //Delete the Addons Data
        delDir($addon_path);

        //Delete the package file
        delFile($file_path);
    } else {
        $addonRes = 'File Not Found!';
        $addonError = true;
        $errType = 1;
    }
} else {
    $addonRes = 'No Input!';
    $addonError = true;
    $errType = 1;
}