<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework - PHP Script
 * @copyright 2019 ProThemes.Biz
 *
 */

$activePage = 'login';
$chatID = $userID = $arrowCount = $cap_contact = 0;
$nowDate = date('M d Y H:i:s ') . 'GMT' . date('O');
$avatars = getAvatars($con,true);
$availableAvatars = $avatars[0];
$defaultAvatar = current($availableAvatars);
$chatTemplate = chatTemplate(THEME_DIR);
$chatStartTime = $nowDate;
$disabledClass = 'disabled';
$departments = getDepartments($con);
$adminCount = intval(getOnlineAdminCount($con));
$chatSettings = chatSettings($con);
$emoticons = loadEmoticonPack($con,true, $chatSettings['emoticons']);
$userData = array('userName' => '', 'updateUserEmail' => '', 'userEmail' => '');
$remName = $remEmail = $remAvatar = '';
$previewImgLoader = themeLink('img/small-load.gif',true);

$tonePath = ''; $eTone = false;
if(isSelected($chatSettings['tone'])){
    $tonePath = getTone($con, $chatSettings['default_tone']);
    $eTone = true;
}

if($adminCount === 0) {
    $activePage = 'contact';
    $chatSettings['chat_title'] = trans('Contact US',$lang['RF2'],true);

    //Load Image Verifcation
    extract(loadCapthca($con));
    $cap_contact = filter_var($contact_page, FILTER_VALIDATE_BOOLEAN);

    if($cap_contact){
        $cap_type = strtolower($cap_type);
        $customCapPath = PLG_DIR.'captcha'.D_S.$cap_type.'_cap.php';
        define('CAP_GEN',1);
        require LIB_DIR.'generate-verification.php';
    }
}

//Remember user details
if (REM_USER_DETAILS) {
    $remName = raino_trim($_COOKIE[N_APP . '_user']);
    $remEmail = raino_trim($_COOKIE[N_APP . '_email']);
    $remAvatar = intval($_COOKIE[N_APP . '_avatar']);

    $remAvatarRes = searchAvatar($availableAvatars, $remAvatar);
    if ($remAvatarRes !== false) {
        $defaultAvatar = $remAvatarRes['path'];
        $arrowCount = $remAvatarRes['count'];
    }
}

if(!issetSession('chatID')) {
    if (REM_USER_DETAILS) {
        $remData = mysqliPreparedQuery($con, 'SELECT id FROM ' . DB_PREFIX . 'users WHERE email=?', 's', array($remEmail));
        if ($remData !== false) {
            $userID = $remData['id'];
            setSession(array('userID' => $userID, 'userName' => $remName, 'userImage' => $avatars[0][$remAvatar]),null,'chat');
        }
    }
} else {
    $disabledClass = '';
    $userData = getSession(array('chatID', 'userID', 'userName', 'userImage', 'userEmail'));

    $userData['updateUserEmail'] = 'disabled';
    if($userData['userEmail'] === 'no-email@guest.com' || $userData['userEmail'] === '' || $userData['userEmail'] === '-')
        $userData['updateUserEmail'] = '';

    $data = mysqliPreparedQuery($con, 'SELECT status,date FROM ' . DB_PREFIX . 'chat WHERE id=?', 'i', array($userData['chatID']));
    if ($data !== false) {
        $chatStatus = intval($data['status']);
        $chatStartTime = $data['date'];
        $userID = $userData['userID'];
        if ($chatStatus === 1 || $chatStatus === 2) {
            $chatID = $userData['chatID'];
            $activePage = 'chat';
        }
    }

}

if(issetSession('chatWin')){
    $chatWinStats = getSession('chatWin');
}else{
    $chatWinStats = 0;
    if(intval($chatSettings['stats']) === 2)
        $chatWinStats = 1;
}
