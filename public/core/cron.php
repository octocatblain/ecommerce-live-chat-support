<?php

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */

//-------------------------------------------------
//----------------- CRON JOB FILE -----------------
//-------------------------------------------------


//Define CRON 
define('CRON','_1');

//ROOT Path
define('ROOT_DIR', realpath(dirname(dirname(__FILE__))) .DIRECTORY_SEPARATOR);

//Application Path
define('APP_DIR', ROOT_DIR .'core'.DIRECTORY_SEPARATOR);

//Configuration Path
define('CONFIG_DIR', APP_DIR .'config'.DIRECTORY_SEPARATOR);

//Load Configuration & Functions
require CONFIG_DIR.'config.php';
require APP_DIR.'functions.php';

//Log File
$logMyCronFile = LOG_DIR.'cron.tdata';
$msgWithDate = '';
$msgWithDate .= '['. date('d-M-Y H:i:s') . ' ' . getTimeZone() .']' . " Cron job started \r\n\n";
            
//Clear Temp Folder Files
$folderName = APP_DIR.'temp'.D_S;
if (file_exists($folderName)) {
    foreach (new DirectoryIterator($folderName) as $fileInfo) {
        if ($fileInfo->isDot())
            continue;
        if (time() - $fileInfo->getCTime() >= 60 * 60) {
            $fileName = $fileInfo->getFilename();
            if($fileName != '.htaccess' && $fileName != 'index.php'){
                if(is_dir($fileInfo->getRealPath()))
                    delDir($fileInfo->getRealPath());
                else
                    delFile($fileInfo->getRealPath());
            }
        }
    }
}

//Database Connection
$con = dbConnect();

//Custom Cron Job Files
if(file_exists(APP_DIR.'cron')) {
    foreach (glob(APP_DIR . 'cron' . D_S . '*{_cron}.php', GLOB_BRACE) as $filename) {
        if (file_exists($filename)) require $filename;
    }
}

//Close inactive chats more than 1 hour
$trackQuery = mysqli_query($con, "SELECT id,analytics FROM " . DB_PREFIX . "chat WHERE status != 0");
$chatNotClosed = mysqli_num_rows($trackQuery);
if($chatNotClosed > 0){
    while($row = mysqli_fetch_assoc($trackQuery)) {
        $trackQuery2 = mysqli_query($con, "SELECT id FROM " . DB_PREFIX . "rainbow_analytics WHERE id=".$row['analytics']." AND last_visit < NOW() - INTERVAL 60 MINUTE");
        $chatNotClosed2 = mysqli_num_rows($trackQuery2);
        if($chatNotClosed2 > 0){
            notificationUpdate($con, $row['id'], -1, -1, 'Chat closed by cron job', date('M d Y H:i:s ') . 'GMT' . date('O'), $ip);
            updateChat($con, $row['id'], 0, null);
        }
    }
}

//Load Settings
$siteInfo =  mysqli_query($con, "SELECT other_settings FROM ".DB_PREFIX."site_info where id='1'");
$siteInfoRow = mysqli_fetch_array($siteInfo);
$other = dbStrToArr($siteInfoRow['other_settings']);

$dbBackup = false;
if(isSelected($other['other']['dbbackup']['cron'])){
    if($other['other']['dbbackup']['cronopt'] == 'daily'){
        if((time() - $other['other']['dbbackup']['cronlog']) > 86400)
            $dbBackup = true;
    }
   if($other['other']['dbbackup']['cronopt'] == 'weekly'){
        if((time() - $other['other']['dbbackup']['cronlog']) > 604800)
            $dbBackup = true;
    }
   if($other['other']['dbbackup']['cronopt'] == 'monthly'){
        if((time() - $other['other']['dbbackup']['cronlog']) > 2592000)
            $dbBackup = true;
    }
}

//Backup Database
if($dbBackup){
    $dbBackupPath = ROOT_DIR.ADMIN_DIR_NAME.D_S.'db-backups'.D_S;
    $filePath = backupMySQLdb($con, $dbName, $dbBackupPath, isSelected($other['other']['dbbackup']['gzip']));
    
    if(file_exists($filePath))
        $msgWithDate .= '['. date('d-M-Y H:i:s') . ' ' . getTimeZone() .']' . " Database backup generated successfully \r\n\n";
    else
        $msgWithDate .= '['. date('d-M-Y H:i:s') . ' ' . getTimeZone() .']' . " Database backup failed \r\n\n";
    
    //Update sitemap build time on database
    $other['other']['dbbackup']['cronlog'] = time();
    $other_settings = arrToDbStr($con,$other);
    $query = "UPDATE ".DB_PREFIX."site_info SET other_settings='$other_settings' WHERE id='1'";
    mysqli_query($con, $query);
}

//Close the database conncetion
mysqli_close($con);

//Log Ending Time
$msgWithDate .= '['. date('d-M-Y H:i:s') . ' ' . getTimeZone() .']' . " Cron job successfully completed! \r\n\n";
putMyData($logMyCronFile,$msgWithDate,FILE_APPEND);

//-------------------------------------------------
//------------------- B-ALAJ-I --------------------
//-------------------------------------------------

//END
die();