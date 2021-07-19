<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));



$activeTheme = getTheme($con);

$pageTitle = trans('Manage Addons', $lang['CH464'], true);
$subTitle = trans('Install Add-on', $lang['CH465'], true);

$addonDir = ADMIN_DIR.'addons';

//Check Htaccess File
//if(!file_exists($addonDir.D_S.'.htaccess')) copy(APP_DIR.'data'.D_S.'htaccessAddon.tdata', $addonDir.D_S.'.htaccess');

if($pointOut === 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delFileName = raino_trim($args[0]);
        $delPath = $addonDir.D_S.$delFileName.'.addonpk';

        if(file_exists($delPath)) {
            unlink($delPath);
            if (!file_exists($delPath)) die('1');
        }
    }
    die('0');
}

$minError = false;
if(!class_exists('ZipArchive')){
    $minError = true;
    $minMsg[] = array($lang['CH466'],'<span class="label label-danger">'.$lang['CH467'].'</span>');
}else{
    $minMsg[] = array($lang['CH466'],'<span class="label label-success">'.$lang['CH468'].'</span>');
}

if (is_writable($addonDir)) {
    $minMsg[] = array($lang['CH471'].' - "<b>/admin/addons</b>"','<span class="label label-success">'.$lang['CH469'].'</span>');
} else {
    $minError = true;
    $minMsg[] = array($lang['CH471'].' - "<b>/admin/addons</b>"','<span class="label label-danger">'.$lang['CH470'].'</span>');
}

$minMsg[] = array($lang['CH472'],'<span class="label label-info">'.formatBytes(file_upload_max_size()).'</span>');

//Install Addon
if (isset($_POST['addonID']))
{
    $target_dir = ADMIN_DIR . "addons/";
    $target_filename = basename($_FILES["addonUpload"]["name"]);
    $target_file = $target_dir . $target_filename;
    $uploadSs = 1;
    // Check if file already exists
    if (file_exists($target_file))
    {
        $target_filename = rand(1, 99999) . "_" . $target_filename;
        $target_file = $target_dir . $target_filename;
    }
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check file size
    if ($_FILES["addonUpload"]["size"] > 999500000){
        $msg = errorMsgAdmin($lang['RF97']);
        $uploadSs = 0;
    } else
    {
        // Allow certain file formats
        if ($imageFileType != "zip" && $imageFileType != "zipx" && $imageFileType != "addonpk")
        {
            $msg = errorMsgAdmin($lang['CH473']);
            $uploadSs = 0;
        }
    }

    // Check if $uploads is set to 0 by an error
    if (!$uploadSs == 0)
    {
        //No Error - Move the file to addon directory
        if (move_uploaded_file($_FILES["addonUpload"]["tmp_name"], $target_file))
        {
            $msg = successMsgAdmin($lang['CH474']);
            
            //Package File Path
            $file_path = $target_dir . $target_filename;
            
            //Temporarily extract Addons Data
            $addon_path = ADMIN_DIR . "addons/" . "ad_" . rand(1000, 999999);
            extractZip($file_path, $addon_path);
            
            //Check Addons Installer is exists 
            if (file_exists($addon_path . "/pinky.tdata")){
                if (file_exists($addon_path . "/install.php"))
                {
                    //Found - Process Installer
                    require_once ($addon_path . "/install.php");
                    
                    if($activeTheme != 'default'){
                        $addonRes.= $lang['CH475']." $activeTheme<br>";
                        recurse_copy($addon_path."/theme/default",ROOT_DIR."/theme/$activeTheme");
                    }
                }else{
                    //Not Found
                    $addonRes = $lang['CH476'];
                    $addonError = true;
                    $errType = 1;
                }
            }else{
                //Not Found
                $addonRes = $lang['CH477'];
                $addonError = true;
                $errType = 1; 
            }
            $addonRes = str_replace(array("<br>","<br/>","<br />"),PHP_EOL,$addonRes);
            //Delete the Addons Data
            delDir($addon_path);
            
            //Delete the package file
            delFile($file_path);
            $controller = "process-addon";

        } else{
            $msg = errorMsgAdmin($lang['RF99']);
        }
    }
}

$manualInstallFiles = array();
$manualInstall = false;

//Custom Cron Job Files
if(file_exists($addonDir)) {
    foreach (glob($addonDir . D_S . '*.addonpk', GLOB_BRACE) as $filename) {
        $manualInstallFiles[] = basename($filename);
        $manualInstall = true;
    }
}