<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Email Templates', $lang['CH618'], true);
$subTitle = trans('Templates', $lang['CH619'], true);
$mailTemplates = true;

$htmlLibs = array('ckeditor');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['activation']))
    {
        $activationMail = base64_encode(raino_trim($_POST['activation']));
        $activationSub = base64_encode(raino_trim($_POST['activationSub']));
        
        $query = "UPDATE ".DB_PREFIX."mail_templates SET subject='$activationSub', body='$activationMail' WHERE code='account_activation'";
        
        if (!mysqli_query($con, $query))
            $msg = errorMsgAdmin($lang['RF91']);
        else 
            $msg = successMsgAdmin($lang['CH620']);
    }
    
    if (isset($_POST['password']))
    {
        $passwordMail = base64_encode(raino_trim($_POST['password']));
        $passwordSub = base64_encode(raino_trim($_POST['passwordSub']));
        
        $query = "UPDATE ".DB_PREFIX."mail_templates SET subject='$passwordSub', body='$passwordMail' WHERE code='password_reset'";
        
        if (!mysqli_query($con, $query))
            $msg = errorMsgAdmin($lang['RF91']);
        else 
            $msg = successMsgAdmin($lang['CH620']);
    }
}

$query =  'SELECT code,subject,body FROM '.DB_PREFIX.'mail_templates';
$result = mysqli_query($con,$query);
        
while($row = mysqli_fetch_array($result)) {
    $code =  Trim($row['code']);
    
    if($code == 'account_activation'){
      $activationSub = html_entity_decode(base64_decode($row['subject']));
      $activationMail = html_entity_decode(base64_decode($row['body']));
    }
    
    if($code == 'password_reset'){
      $passwordSub = html_entity_decode(base64_decode($row['subject']));
      $passwordMail = html_entity_decode(base64_decode($row['body']));
    }
}