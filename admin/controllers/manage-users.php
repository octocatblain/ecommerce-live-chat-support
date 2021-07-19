<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Manage Users', $lang['CH513'], true);
$subTitle = trans('User List', $lang['CH514'], true);

$htmlLibs = array('dataTables');
//Success Action
if($pointOut == 'new-user-success'){
    $msg = successMsgAdmin($lang['CH515']);
}

//Delete Action
if($pointOut == 'delete'){
    $user_id = $args[0];
    if($args[0] != ''){
        $query = "DELETE FROM ".DB_PREFIX."users WHERE id=$user_id";
        $result = mysqli_query($con, $query);
        if (mysqli_errno($con)){
            $msg = errorMsgAdmin(mysqli_error($con));
        } else {
            header('Location:'.adminLink($controller,true));
            die();
        }
    }
}

//Export Action
if($pointOut == 'export'){
    
    function sendHeaders($filename) {
        //Disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        //Force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        //Disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
    
    sendHeaders("data_export_" . date("Y-m-d") . ".csv");
    
    $idsList = array();
    $out = fopen('php://output', 'w');
    $query = "SELECT email_id FROM ".DB_PREFIX."users";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($result)) {
        $idsList = array($row['email_id']);
        fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        fputcsv($out, $idsList);
    }
    fclose($out);
    die();
}