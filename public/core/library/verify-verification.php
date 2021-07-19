<?php
defined('CAP_VERIFY') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

if($cap_type == 'phpcap'){
    $capVerifyCode = $userVerifycode = $userPageID = '';
    $userVerifycode = strtolower(raino_trim($_POST['scode']));
    $userPageID = raino_trim($_POST['pageUID']);

    if(isset($_SESSION[N_APP.'Cap'.$userPageID]['code'])){
        $capVerifyCode = strtolower($_SESSION[N_APP.'Cap'.$userPageID]['code']);
        unset($_SESSION[N_APP.'Cap'.$userPageID]['code']);
    }

    if(!empty($userVerifycode)){
        if ($userVerifycode != $capVerifyCode)
            $error = $lang['RF4']; //Your image verification code is wrong!
    }else{
        //Please verify your image verification.
        $error = $lang['RF29'];
    }

}elseif($cap_type == 'recap'){
    $userVerifycode = raino_trim($_POST['g-recaptcha-response']);
        if(!empty($userVerifycode)){
        $capResult = get_recaptcha_response($userVerifycode,$recap_seckey,$ip);
            if($capResult['success']){
                //reCaptcha Verified.
            }else{
                //Your image verification code is wrong!
                $error = $lang['RF4'];
            }
        }else{
            //Please verify your image verification.
            $error = $lang['RF29'];
        }
}elseif(file_exists($customCapPath)){
    define('CAP_VERIFY_PLG',1);
    require($customCapPath);
}else{
    stop('Unknown Image Verification System!');
}  