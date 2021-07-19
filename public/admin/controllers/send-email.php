<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Send Email', $lang['CH621'], true);
$subTitle = trans('Send Email to Customers', $lang['CH622'], true);
$from = $replyTo = $message = $to = $sub = '';

$htmlLibs = array('ckeditor');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $to = raino_trim($_POST['to']);
    $sub = raino_trim($_POST['sub']);
    $message = raino_trim($_POST['mailcontent']);
        
    //Load Site Info
    $siteInfo =  mysqli_query($con, "SELECT * FROM ".DB_PREFIX."site_info where id='1'");
    $siteInfoRow = mysqli_fetch_array($siteInfo);
    $site_name = shortCodeFilter($siteInfoRow['site_name']);
    $adminEmail = trim($siteInfoRow['email']);
    $from = $replyTo = $adminEmail;
    
    if ($to != null && $sub != null && $message != null && $adminEmail != null){
        
        $htmlMessage = '<html><body><p>'.nl2br($message).'</p></body></html>';
    
        //Load Mail Settings
        extract(loadMailSettings($con));
        
        if($protocol == '1'){
            //PHP Mail
            if(default_mail($from, $site_name, $replyTo, $site_name, $to, $sub, $htmlMessage)){
                $msg = successMsgAdmin($lang['RF27']);
                $success = $lang['RF27'];
                $message = $to = $sub = '';
            }else{
                $msg = errorMsgAdmin($lang['RF28']);
            }
        }else{
            //SMTP Mail
            if(smtp_mail($smtp_host, $smtp_port, isSelected($smtp_auth), $smtp_username, $smtp_password, $smtp_socket,
                    $from, $site_name, $replyTo, $site_name, $to, $sub, $htmlMessage)){
                //Your message has been sent successfully   
                $msg = successMsgAdmin($lang['RF27']);
                $message = $to = $sub = '';
            }else{
                $msg = errorMsgAdmin($lang['RF28']);
            }
        }
    }else{
        $msg = errorMsgAdmin($lang['RF28']);
    }
}