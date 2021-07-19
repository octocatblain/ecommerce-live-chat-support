<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
 
$pageTitle = trans('Database Backup', $lang['CH440'], true);
$subTitle = trans('DB Backup Settings', $lang['CH441'], true);

$htmlLibs = array('iCheck');
$dbBackupPath = ADMIN_DIR.'db-backups'.D_S;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $siteInfo =  mysqli_query($con, "SELECT other_settings FROM ".DB_PREFIX."site_info where id='1'");
    $siteInfoRow = mysqli_fetch_array($siteInfo);
    $other = dbStrToArr($siteInfoRow['other_settings']);

    $myValues = array_map_recursive(
        function($item) use ($con) { return escapeTrim($con,$item); },
        $_POST['other']
    );
    
    unset($other['other']['dbbackup']);

    if(!isset($myValues['other']['dbbackup']['gzip']))
        $myValues['other']['sitemap']['gzip'] = false;
    if(!isset($myValues['other']['dbbackup']['cron']))
        $myValues['other']['sitemap']['cron'] = false;
    if(!isset($myValues['other']['dbbackup']['cronopt']))
        $myValues['other']['dbbackup']['cronopt'] = 'daily';
          
    $other = array_merge_recursive($other,$myValues);
    
    $other_settings = arrToDbStr($con,$other);
    $result = updateToDbPrepared($con, 'site_info', array('other_settings' => $other_settings), array('id' => '1'));

    if ($result)
        $msg = errorMsgAdmin(mysqli_error($con));
    else
        $msg = successMsgAdmin($lang['CH442']);
}

//Load Database Settings
$siteInfo =  mysqli_query($con, "SELECT other_settings FROM ".DB_PREFIX."site_info where id='1'");
$siteInfoRow = mysqli_fetch_array($siteInfo);
$other = dbStrToArr($siteInfoRow['other_settings']);

if($pointOut == 'backup-now'){
    $filePath = backupMySQLdb($con, $dbName, $dbBackupPath, isSelected($other['other']['dbbackup']['gzip']));
    
    if(file_exists($filePath))
        $msg = successMsgAdmin($lang['CH443']);
    else
        $msg = errorMsgAdmin($lang['CH444']);
}

if($pointOut == 'backup-download'){
    $filePath = backupMySQLdb($con, $dbName, $dbBackupPath, isSelected($other['other']['dbbackup']['gzip']));
    
    if(file_exists($filePath)){
        $onlyFilename = end(explode(D_S,$filePath));
        header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$onlyFilename."\"");  
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit();
    }else{
        $msg = errorMsgAdmin($lang['CH444']);
    }
}

if($pointOut == 'download'){
    if(isset($args[0]) && $args != ''){
        $filePath = $dbBackupPath.trim($args[0]);
        if(file_exists($filePath)){
            header('Content-Type: application/octet-stream');   
            header("Content-Transfer-Encoding: Binary"); 
            header("Content-disposition: attachment; filename=\"".trim($args[0])."\"");  
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit();
        }
    }
}

if($pointOut == 'delete'){
    if(isset($args[0]) && $args != ''){
        $filePath = $dbBackupPath.trim($args[0]);
        if(file_exists($filePath)) unlink($filePath);
        
        if(!file_exists($filePath))
            $msg = successMsgAdmin($lang['CH445']);
        else
            $msg = errorMsgAdmin($lang['CH446']);
    }
}

$tableData = '';
function doTableData($filename,$controller,$lang){
    $onlyFilename = explode(D_S,$filename);
    $onlyFilename = end($onlyFilename);
    return '<tr>
        <td>'.$onlyFilename.'</td>
        <td>'.date ("F d Y h:i:s A", filemtime($filename)).'</td>
        <td><a class="btn btn-primary btn-xs" href="'.adminLink($controller.'/download/'.$onlyFilename.'/now',true).'"> <i class="fa fa-download"></i> &nbsp; '.$lang['CH58'].' </a></td>
        <td><a class="btn btn-danger btn-xs" onclick="return confirm(\''.$lang['CH234'].'\');" href="'.adminLink($controller.'/delete/'.$onlyFilename.'/now',true).'"> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].' </a></td>
    </tr>';
}

foreach(glob($dbBackupPath.'{'.$dbName.'}*.sql',GLOB_BRACE) as $filename)
    $tableData .= doTableData($filename,$controller,$lang);
    
foreach(glob($dbBackupPath.'{'.$dbName.'}*.sql.gz',GLOB_BRACE) as $filename)
    $tableData .= doTableData($filename,$controller,$lang);