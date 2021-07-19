<?php
/*
* @author Balaji
* @name Rainbow PHP Framework
* @copyright 2020 ProThemes.Biz
*
*/

function createChat($con,$userID,$dept,$date,$admin=false,$status=1,$isAdminChat=0,$analyticsID=0){

    $data=array();
    if($admin) {
        $data['operators'] = json_encode(array($userID));
        $data['staff_id'] = $userID;
        $data['user_id'] = -1;
    }else {
        $data['user_id'] = $userID;
        $data['staff_id'] = -1;
    }
    $data['dept'] = $dept;
    $data['date'] = $date;
    $data['status'] = $status;
    $data['admin'] = $isAdminChat;
    $data['analytics'] = $analyticsID;
    $data['nowtime'] = date("Y-m-d H:i:s");

    if(insertToDbPrepared($con, 'chat', $data)){
        return array(false);
    }else{
        $chatId = mysqli_insert_id($con);
        return array(true, $chatId);
    }
}

function updateChat($con, $chatID, $status, $userID, $admin = false){
    $data=array();
    $data['status'] = $status;

    if($userID !== NULL) {
        if ($admin) {
            $data['staff_id'] = $userID;
            $data['operators'] = json_encode(array($userID));
        }else
            $data['user_id'] = $userID;
    }

    if(updateToDbPrepared($con, 'chat', $data, array('id' => $chatID)))
        return false;
    else
        return true;
}

function updateAnalyticsId($con,$analyticsID=0,$chatID){
    $data = array();
    $data['analytics'] = $analyticsID;

    if(updateToDbPrepared($con, 'chat', $data, array('id' => $chatID)))
        return false;
    else
        return true;
}

function chatDetails($con, $chatID){
    $data = mysqliPreparedQuery($con, 'SELECT  user_id,status,dept,staff_id,operators,admin,rate,analytics FROM ' . DB_PREFIX . 'chat WHERE id=?', 'i', array($chatID));
    if ($data !== false) {
        if($data['operators'] != '') {
            $data['operators'] = json_decode($data['operators'], true);
            if ($data['operators'] === NULL)
                $data['operators'] = array();
        }else{
            $data['operators'] = array();
        }
        $data['rate'] = intval($data['rate']);
        $data['status'] = intval($data['status']);
        return $data;
    }
    return false;
}
function getCannedMsgAll($con){
    $arr = array();
    $count = 0;
    $result = mysqli_query($con, "SELECT code,data FROM " . DB_PREFIX . "canned_msg WHERE status=1");
    while($row = mysqli_fetch_array($result)) {
        $arr[] = array('code' => $row['code'], 'data' => $row['data']);
        $count++;
    }
    return array('result' => $arr, 'count' => $count);
}
function getCannedMsg($con, $string, $string2){
    $arr = array();
    $count = 0;
    $result = mysqli_query($con, "SELECT code,data FROM " . DB_PREFIX . "canned_msg WHERE status=1 AND code LIKE '$string%' LIMIT 3");
    while($row = mysqli_fetch_array($result)) {
        $arr[$row['code']] = $row['data'];
        $count++;
    }
    if($count !== 3){
        if($string2 != ''){
            $string = $string2 . ' ' . $string;
            $result = mysqli_query($con, "SELECT code,data FROM " . DB_PREFIX . "canned_msg WHERE status=1 AND code LIKE '$string%' LIMIT 3");
            while($row = mysqli_fetch_array($result)) {
                if($count === 3)
                    break;
                $arr[$row['code']] = $row['data'];
                $count++;
            }
        }
    }
    return array('result' => $arr, 'count' => $count);
}

function addOperator($con, $chatID, $adminID){
    $operators = getOperatorsList($con, $chatID);

    if(!in_array($adminID, $operators)){
        $operators[] = $adminID;
        $json = json_encode($operators);

        if(updateToDbPrepared($con, 'chat', array('operators' => $json), array('id' => $chatID)))
            return false;
        else
            return true;
    }
    return false;
}

function removeOperator($con, $chatID, $adminID){
    $operators = getOperatorsList($con, $chatID);

    if (($key = array_search($adminID, $operators)) !== false) {
        unset($operators[$key]);

        $json = json_encode($operators);

        if (updateToDbPrepared($con, 'chat', array('operators' => $json), array('id' => $chatID)))
            return false;
        else
            return true;
    }
    return false;
}

function getOperatorsList($con, $chatID){
    $operators = array();
    $data = mysqliPreparedQuery($con, 'SELECT operators FROM ' . DB_PREFIX . 'chat WHERE id=?', 'i', array($chatID));
    if ($data !== false) {
        if($data['operators'] != '') {
            $operators = json_decode($data['operators'], true);
            if($operators === NULL)
                return array();
        }
    }
    return $operators;
}

function isOperatorPresent($con, $chatID, $adminID){
    $operators = getOperatorsList($con, $chatID);

    if(in_array($adminID, $operators))
        return true;
    else
        return false;
}

function processAdminDetails($con, $adminID, $chatID, $baseURL){
    $output = array('success' => false);

    $flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'20'.D_S;
    $iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
    $flagLink = $baseURL.'resources/flags/default/20/';
    $iconLink = $baseURL.'resources/icons/';
    $coLink = $bLink = $pLink = $iconLink.'unknown.png';

    $data = getAdminDetailsFull($con, $adminID);
    if(is_array($data)){
        $output['success'] = true;
        $geoInfo = getMyGeoInfo($data['ip']);
        $deviceInfo = parse_user_agent($data['ua']);
        $deviceInfo['platform'] = ucfirst($deviceInfo['platform'] == '' ? '-' : $deviceInfo['platform']);
        $deviceInfo['browser'] = ucfirst($deviceInfo['browser'] == '' ? '-' : $deviceInfo['browser']);

        $ccData = country_code_to_country($geoInfo['country_code']);

        if(file_exists($flagPath.strtolower($ccData).'.png'))
            $coLink = $flagLink.strtolower($ccData).'.png';

        if(file_exists($iconPath.strtolower($deviceInfo['browser']).'.png'))
            $bLink = $iconLink.strtolower($deviceInfo['browser']).'.png';

        if(file_exists($iconPath.strtolower($deviceInfo['platform']).'.png'))
            $pLink = $iconLink.strtolower($deviceInfo['platform']).'.png';

        $output['ip'] = $data['ip'];
        $output['name'] = $data['name'];
        $output['email'] = $data['email'];
        $output['role'] = $data['role'];
        $output['access'] = $data['access'];
        $output['chatID'] = $chatID;
        $output['logo'] = $data['logo'];
        $output['country_code'] = $geoInfo['country_code'];
        $output['location'] = '<img src="'.$coLink.'" alt="'.$geoInfo['country'].'" /> '. (($geoInfo['region'] == '' || $geoInfo['region'] == '-') ? '' : $geoInfo['region'].',').$geoInfo['country'];
        $output['longitude'] = $geoInfo['longitude'];
        $output['latitude'] = $geoInfo['latitude'];
        $output['isp'] = $geoInfo['isp'];
        $output['ua'] = $data['ua'];

        $output['browser'] = '<img src="'.$bLink.'" alt="'.$deviceInfo['browser'].'" />'
            . ' ' . $deviceInfo['browser'].' '.$deviceInfo['version'];

        $output['platform'] = '<img src="'.$pLink.'" alt="'.$deviceInfo['platform'].'" />'
            . ' ' . $deviceInfo['platform'];
    }
    return $output;
}

function processUserDetails($con, $chatID, $baseURL){
    $output = array('success' => false);

    $flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'20'.D_S;
    $iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
    $flagLink = $baseURL.'resources/flags/default/20/';
    $iconLink = $baseURL.'resources/icons/';
    $coLink = $bLink = $pLink = $iconLink.'unknown.png';

    $query = mysqli_query($con, 'SELECT id,ip,ua,screen,user_id,ref FROM '. DB_PREFIX . 'rainbow_analytics WHERE chatID='.$chatID. ' ORDER BY id DESC');
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $output['success'] = true;

        $userInfo = getUserDetails($con, $data['user_id']);
        $geoInfo = getMyGeoInfo($data['ip']);
        $deviceInfo = parse_user_agent($data['ua']);
        $deviceInfo['platform'] = ucfirst($deviceInfo['platform'] == '' ? '-' : $deviceInfo['platform']);
        $deviceInfo['browser'] = ucfirst($deviceInfo['browser'] == '' ? '-' : $deviceInfo['browser']);

        $ccData = country_code_to_country($geoInfo['country_code']);

        if(file_exists($flagPath.strtolower($ccData).'.png'))
            $coLink = $flagLink.strtolower($ccData).'.png';

        if(file_exists($iconPath.strtolower($deviceInfo['browser']).'.png'))
            $bLink = $iconLink.strtolower($deviceInfo['browser']).'.png';

        if(file_exists($iconPath.strtolower($deviceInfo['platform']).'.png'))
            $pLink = $iconLink.strtolower($deviceInfo['platform']).'.png';

        $output['ip'] = $data['ip'];
        $output['name'] = $userInfo['name'];
        $output['email'] = $userInfo['email'];
        $output['image'] = fixLink($userInfo['image'],true);
        $output['country_code'] = $geoInfo['country_code'];
        $output['location'] = '<img src="'.$coLink.'" alt="'.$geoInfo['country'].'" /> '. (($geoInfo['region'] == '' || $geoInfo['region'] == '-') ? '' : $geoInfo['region'].',').$geoInfo['country'];
        $output['longitude'] = $geoInfo['longitude'];
        $output['latitude'] = $geoInfo['latitude'];
        $output['isp'] = $geoInfo['isp'];
        $output['screen'] = $data['screen'];
        $output['ua'] = $data['ua'];
        $output['traffic'] = ($data['ref'] === 'direct' || $data['ref'] == '') ? 'Direct' : $data['ref'];

        $output['browser'] = '<img src="'.$bLink.'" alt="'.$deviceInfo['browser'].'" />'
            . ' ' . $deviceInfo['browser'].' '.$deviceInfo['version'];

        $output['platform'] = '<img src="'.$pLink.'" alt="'.$deviceInfo['platform'].'" />'
            . ' ' . $deviceInfo['platform'];
    }
    return $output;
}

function getChatData($con, $chatID=-1){

    if($chatID === -1)
        $chatID = getSession('chatID');

    $data = mysqliPreparedQuery($con, 'SELECT id,date,msg,sub_msg_id,user_id,staff_id FROM ' . DB_PREFIX . 'chat_history WHERE chat_id=?', 'i', array($chatID),false);
    if ($data !== false)
        return $data;
    return array();
}

function notificationUpdate($con, $chatID, $userID, $staffID, $msg, $date, $ip){

    $insert = array();
    $insert['notify'] = 1;
    $insert['chat_id'] = $chatID;
    $insert['user_id'] = $userID;
    $insert['staff_id'] = $staffID;
    $insert['sub_msg_id'] = -1;
    $insert['msg'] = $msg;
    $insert['date'] = $date;
    $insert['ip'] = $ip;

    if(insertToDbPrepared($con, 'chat_history', $insert)){
        return false;
    }else{
        return true;
    }
}

function genTranscript($con, $chatID){
    $outData = '';
    $users = array();

    $result = mysqli_query($con, 'SELECT date FROM ' . DB_PREFIX . 'chat WHERE id='.$chatID);
    $row = mysqli_fetch_assoc($result);
    $outData = '<p><h3>Chat started on ' . $row['date'] . '</h3><br><br> <table>';

    $result = mysqli_query($con, 'SELECT id,date,msg,sub_msg_id,user_id,staff_id,notify,data FROM ' . DB_PREFIX . 'chat_history WHERE chat_id='.$chatID.' ORDER BY id ASC');
    while ($row = mysqli_fetch_assoc($result)){
        $row['msg'] = stripcslashes($row['msg']);
        $row['user_id'] = intval($row['user_id']);
        $row['staff_id'] = intval($row['staff_id']);

        if($row['msg'] === 'BALAJI_UPLOAD') {
            $resultU = mysqli_query($con, 'SELECT o_name,uploaded FROM ' . DB_PREFIX . 'chat_uploads WHERE id='.$row['data']);
            $rowU = mysqli_fetch_assoc($resultU);
            if($rowU['uploaded'] == '1')
                $upMsg = '<span style="color:#20bf6b">'.$GLOBALS['lang']['CH264'].'</span>';
            else
                $upMsg = '<span style="color:#eb3b5a">'.$GLOBALS['lang']['CH802'].'</span>';
            $row['msg'] = $GLOBALS['lang']['CH813'].' ('.$rowU['o_name'].') | '.$GLOBALS['lang']['CH814'].': '.$upMsg;
        }
        if($row['user_id'] == '-1'){
            $activeUserId = $row['staff_id'];
            $activeUser = 'staff'.$row['staff_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name']);
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $GLOBALS['lang']['RF102']);
            }
        }else{
            $activeUserId = $row['user_id'];
            $activeUser = 'user' . $row['user_id'];
            if (!isset($users[$activeUser])) {
                $data = mysqliPreparedQuery($con, 'SELECT name FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($activeUserId));
                if ($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name']);
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $GLOBALS['lang']['RF102']);
            }
        }

        $row['date'] = date_format(date_create($row['date']), 'M dS Y h:i:s A');
        $outData .= '<tr>';
        if(isSelected($row['notify'])) {
            $outData .= '<td>' . $row['date'] . '</td><td> &nbsp;&nbsp;***** ' . $row['msg'] . ' *****</td>';
        }else {
            $outData .= '<td>' . $row['date'] . '</td><td> &nbsp;&nbsp;<b>' . $users[$activeUser]['name'] . ':</b> ' . $row['msg'] . '</td>';
        }
        $outData .= '</tr>';
    }
    $outData .= '</table></p>';
    return $outData;
}

function genTranscriptFile($con, $chatID){
    $outData = '';
    $users = array();

    $result = mysqli_query($con, 'SELECT date FROM ' . DB_PREFIX . 'chat WHERE id='.$chatID);
    $row = mysqli_fetch_assoc($result);
    $outData = 'Chat started on ' . $row['date'] . PHP_EOL.PHP_EOL;

    $result = mysqli_query($con, 'SELECT id,date,msg,sub_msg_id,user_id,staff_id,notify,data FROM ' . DB_PREFIX . 'chat_history WHERE chat_id='.$chatID.' ORDER BY id ASC');
    while ($row = mysqli_fetch_assoc($result)){
        $row['msg'] = stripcslashes($row['msg']);
        $row['user_id'] = intval($row['user_id']);
        $row['staff_id'] = intval($row['staff_id']);

        if($row['msg'] === 'BALAJI_UPLOAD') {
            $resultU = mysqli_query($con, 'SELECT o_name,uploaded FROM ' . DB_PREFIX . 'chat_uploads WHERE id='.$row['data']);
            $rowU = mysqli_fetch_assoc($resultU);
            if($rowU['uploaded'] == '1')
                $upMsg = 'Success';
            else
                $upMsg = 'Failed';
            $row['msg'] = $GLOBALS['lang']['CH813'].' ('.$rowU['o_name'].') | '.$GLOBALS['lang']['CH814'].': '.$upMsg . PHP_EOL;
        }
        if($row['user_id'] == '-1'){
            $activeUserId = $row['staff_id'];
            $activeUser = 'staff'.$row['staff_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name']);
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $GLOBALS['lang']['RF102']);
            }
        }else{
            $activeUserId = $row['user_id'];
            $activeUser = 'user' . $row['user_id'];
            if (!isset($users[$activeUser])) {
                $data = mysqliPreparedQuery($con, 'SELECT name FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($activeUserId));
                if ($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name']);
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $GLOBALS['lang']['RF102']);
            }
        }

        $row['date'] = date_format(date_create($row['date']), 'M dS Y h:i:s A');
        if(isSelected($row['notify']))
            $outData .= $row['date'] . ' ***** ' . $row['msg'] . ' ***** ';
        else
            $outData .= $row['date'] .'   '  . $users[$activeUser]['name'] . ':  ' . $row['msg'];
        $outData .= PHP_EOL;
    }
    $outData .= PHP_EOL;
    ob_end_clean();
    dlSendHeaders('export-chat-id-'.$chatID.'.txt');
    echo $outData;
    die();
}

function loadNewMessages($con, $chatID, $msgID, $userID, $admin=false, $aUser='user'){
    $lastMsgID = $adminID = $chatStatus = 0;
    $newMsg = $users = array();
    $newMessages = false;
    if($admin)
        $adminID = intval(getSession('AdminID'));
    $chatStatus = chatStatus($con, $chatID);
    $result = mysqli_query($con, 'SELECT id,date,msg,sub_msg_id,user_id,staff_id,notify,data FROM ' . DB_PREFIX . 'chat_history WHERE chat_id='.$chatID.' AND id > '.$msgID.' ORDER BY id ASC');
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $row['user_id'] = intval($row['user_id']);
        $row['staff_id'] = intval($row['staff_id']);
        $row['upload'] = false;
        if(!isSelected($row['notify'])) {
            if ($admin) {
                if ($row['staff_id'] === $adminID) continue;
            } else {
                if ($row['user_id'] === intval($userID)) continue;
            }
        }
        $newMessages = true;

        $row['msg'] = stripcslashes($row['msg']);

        if($row['msg'] === 'BALAJI_UPLOAD') {
            $row['msg'] = genUploadBox($con, $row['data'], $admin, $row['staff_id'], $row['date']);
            $row['upload'] = true;
        }else
            $row['msg'] = stripcslashes($row['msg']);

        $newMsg[] = $row;
        $lastMsgID = $row['id'];
        if($row['user_id'] == '-1'){
            $activeUserId = $row['staff_id'];
            $activeUser = 'staff'.$row['staff_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name,logo FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name'], 'avatarLink' => createLink($data['logo'],true,true));
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => 'Unkown', 'avatarLink' => createLink('resources/avatars/unknown.png', true, true));
            }
        }else{
            if($userID != $row['user_id']) {
                $activeUserId = $row['user_id'];
                $activeUser = 'user' . $row['user_id'];
                if (!isset($users[$activeUser])) {
                    $data = mysqliPreparedQuery($con, 'SELECT name,image FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($activeUserId));
                    if ($data !== false)
                        $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name'], 'avatarLink' => fixLink($data['image'], true));
                    else
                        $users[$activeUser] = array('id' => $activeUserId, 'name' => 'Unkown', 'avatarLink' => createLink('resources/avatars/unknown.png', true, true));
                }
            }
        }
    }
    if($lastMsgID !== 0) {
        if($admin)
            setSession($chatID . '_lastMsgID', $lastMsgID, 'admin');
        else
            setSession('lastMsgID', $lastMsgID);
    }
    return array('newMessages' => $newMessages, 'chatHTML' => $newMsg, 'chatID' => $chatID,  'aUser' => $aUser, 'users' => $users, 'callBackID' => $lastMsgID, 'chatStatus' => $chatStatus,  'success' => true);
}

function chatStatus($con,$chatID){
    $data = mysqliPreparedQuery($con, 'SELECT status FROM ' . DB_PREFIX . 'chat WHERE id=?', 'i', array($chatID));
    if ($data !== false)
        return intval($data['status']);
    else
        return 0;
}

function chatTemplate($themePath){
    define('CHAT_TEM',true);
    $template = $subTemplate = '';
    $templateFile = $themePath . 'template.php';
    if(file_exists($templateFile))
        require $templateFile;
    return array('template' => $template, 'subTemplate' => $subTemplate);
}

function generateMsg($chatTemplate, $msgID, $msg, $msgTime, $activeUser, $userName, $userAvatarLink, $subMsg=false, $rowId){
    if($subMsg)
        $html = $chatTemplate['subTemplate'];
    else
        $html = $chatTemplate['template'];

    $replacementCode = array(
        '{{messageId}}' => 'msg_'. $msgID,
        '{{contentId}}' => 'con_'. $msgID,
        '{{nowTime}}' => $msgTime,
        '{{message}}' => $msg,
        '{{msgAdd}}' => '{{|'.$rowId.'|}}',
        '{{userName}}' => $userName,
        '{{avatarLink}}' => $userAvatarLink,
    );
    return str_replace(array_keys($replacementCode),array_values($replacementCode),$html);
}

function fixLink($link, $return=false){
    if($link === NULL || $link == '') {
        $link = '';
    }elseif(substr($link,0,4) !== 'http') {
        $link = createLink($link, true, true);
    }
    if($return)
        return $link;
    else
        echo $link;
}

function getAvatars($con, $addtional=false){
    $avatars = $avatarsJson = array();
    $jsonData = '';
    $result = mysqli_query($con, 'SELECT * FROM ' . DB_PREFIX . 'avatars WHERE status=1');
    while ($row = mysqli_fetch_array($result)){
        $row['path'] = fixLink($row['path'], true);
        $avatars[$row['id']] = $row['path'];
        if($addtional)
            $avatarsJson[] = json_encode(array('id' => $row['id'], 'path' => $row['path']));
    }
    if($addtional)
        $jsonData = '[' . implode(',', $avatarsJson) . ']';

    return array($avatars, $jsonData);
}

function searchAvatar($array=array(), $id){
    $arrowCount = 0;
    foreach($array as $avatarId => $avatarPath){
        if($avatarId === $id)
            return array('id' => $avatarId, 'path' => $avatarPath, 'count' => $arrowCount);
       $arrowCount++;
    }
    return false;
}

function getUserName($con, $userID){
    $username = $GLOBALS['lang']['RF102'];
    $result = mysqli_query($con, 'SELECT name FROM ' . DB_PREFIX . 'users WHERE id='.$userID);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }
    return $username;
}

function getUserDetails($con, $userID){
    $userID = intval($userID);

    if($userID !== 0) {
        $result = mysqli_query($con, 'SELECT name,email,image FROM ' . DB_PREFIX . 'users WHERE id=' . $userID);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        }
    }
    return array('name'=> 'Guest', 'email' => '-', 'image' => createLink('resources/avatars/unknown.png', true, true));
}

function getDepartments($con){
    $data = array();
    $query = mysqli_query($con, "SELECT id, name FROM " . DB_PREFIX . "departments WHERE status=1");
    while($row = mysqli_fetch_assoc($query)){
        $data[$row['id']] = shortCodeFilter($row['name']);
    }
    return $data;
}

function getDepartmentName($con, $id){

    $query = mysqli_query($con, "SELECT name FROM " . DB_PREFIX . "departments WHERE id=".$id);
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        return shortCodeFilter($data['name']);
    }
    return '-';
}

function getDepartmentDetails($con,$id){

    $query = mysqli_query($con, "SELECT name,data FROM " . DB_PREFIX . "departments WHERE id=".$id);
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $data['data'] = json_decode($data['data'], true);
        return array('name' => shortCodeFilter($data['name']), 'data' => $data['data']);
    }
    return false;
}

function adminChatSettings($con, $sep=null, $id=1){
    $sqlQ = 'beep_sound, default_avatar, file_share, upload_size, tone, default_tone, refresh, analytics, canned, canned_type, emoticons';
    if(is_array($sep))
        $sqlQ = implode($sep,', ');
    elseif($sep !== NULL)
        $sqlQ = $sep;

    $query = mysqli_query($con, "SELECT $sqlQ FROM " . DB_PREFIX . "admin_chat_settings WHERE id=".$id);
    $data = mysqli_fetch_assoc($query);
    return $data;
}

function chatSettings($con, $sep=null, $id=1){
    $sqlQ = 'chat_title, default_avatar, file_share, upload_size, upload_approve, tone, default_tone, refresh, side, width, height, mobile_detect, stats, msg, analytics, blacklist, dmsg, dname, dcontent, dlogo, emoticons';
    if(is_array($sep))
        $sqlQ = implode($sep,', ');
    elseif($sep !== NULL)
        $sqlQ = $sep;

    $query = mysqli_query($con, "SELECT $sqlQ FROM " . DB_PREFIX . "chat_settings WHERE id=".$id);
    $data = mysqli_fetch_assoc($query);
    return $data;
}

function updateAnalyticsChatID($con, $analyticsID, $chatID, $userID){
    if(updateToDbPrepared($con, 'rainbow_analytics', array('chatID' => $chatID, 'user_id' => $userID), array('id' => $analyticsID)))
        return false;
    else
        return true;
}

function chatStatusAdmin($con, $chatID, $userID, $return=false){
    $htmlData = $class = '';
    $status = $staffID = $dept = 0;
    $html = array();
    $html['no'] = array('chat-no', 'fa fa-question-circle');
    $html['clicked'] = array('chat-clicked', 'fa fa-window-maximize');
    $html['chatting'] = array('chat-started', 'fa fa-check-circle');
    $html['wait'] = array('chat-wait', 'fa fa-clock-o');
    $html['end'] = array('chat-ended', 'fa fa-check');

    if(intval($userID) === 0) {
        $class = 'no';
        $status = 3;
    } else if(intval($chatID) === 0) {
            $class = 'no';
            $status = 3;
    }else{
        $data = mysqliPreparedQuery($con, 'SELECT staff_id,status,dept FROM ' . DB_PREFIX . 'chat WHERE id=?', 'i', array($chatID));
        $st = intval($data['status']);
        $staffID = $data['staff_id'];
        $dept = intval($data['dept']);

        //$st = chatStatus($con, $chatID);
        $status = $st;
        if($st === 0)
            $class = 'end';
        elseif($st === 1) {
            $class = 'wait';
            if(!issetSession('sound'.$chatID)) {
                setSession('sound'.$chatID,true);
                $toneData = adminChatSettings($con, 'beep_sound');
                $tonePath = getTone($con, $toneData['beep_sound']);
                $htmlData .= '<audio autoplay src="'.$tonePath.'"><source src="#" type="audio/mpeg"></audio>';
            }
        }
        elseif ($st === 2)
            $class = 'chatting';
    }
    $htmlData .= '<span class="chat-stats '.$html[$class][0].' pull-right"><i class="'.$html[$class][1].'" aria-hidden="true"></i></span>';

    return array($status, $htmlData, $staffID, $dept);
}


function loadChatDataHTML($con, $chatID){
    $output = false;
    $token = $chatStatus = 0;
    $users = $mainMsg = $lastMsg = $emoticons = $notifyData = array();
    $startDate = $endDate = $activeUser = $msgID = $outputHTML = $insertID = '';
    $startDateBol = true;

    $chatStatus = chatStatus($con, $chatID);
    $chatTemplate = chatTemplate(ROOT_DIR . 'theme' . D_S . 'default' . D_S);

    $result = mysqli_query($con, 'SELECT id,date,msg,sub_msg_id,user_id,staff_id,notify,data FROM ' . DB_PREFIX . 'chat_history WHERE chat_id='.$chatID. ' ORDER BY id ASC');
    while ($row = mysqli_fetch_array($result)) {
        if(!($output)) {
            $chatSettings = adminChatSettings($con, 'emoticons');
            $emoticons = loadEmoticonPack($con,false,$chatSettings['emoticons']);
        }
        if($startDateBol){
            $startDateBol = false;
            $startDate = $row['date'];
        }
        $endDate = $row['date'];
        $row['user_id'] = intval($row['user_id']);
        $row['sub_msg_id'] = intval($row['sub_msg_id']);
        $row['staff_id'] = intval($row['staff_id']);

        if($row['msg'] === 'BALAJI_UPLOAD'){
            $row['msg'] = genUploadBox($con, $row['data'], true, $row['staff_id'], $row['date']);
        }else {
            $row['msg'] = chatLinkFix($row['msg']);
            if ($emoticons[0])
                $row['msg'] = stripcslashes(emoticons($row['msg'], $emoticons[1]));
        }
        $unknownUser = false; $output = true;

        if($row['user_id'] === -1){
            $activeUserId = $row['staff_id'];
            $activeUser = 'staff'.$row['staff_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name,logo FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name'], 'avatarLink' => fixLink($data['logo'],true));
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $GLOBALS['lang']['RF102'], 'avatarLink' => createLink('resources/avatars/unknown.png', true));
            }
        }else{
            $activeUserId = $row['user_id'];
            $activeUser = 'user'.$row['user_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name,image FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name'], 'avatarLink' => fixLink($data['image'], true));
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $GLOBALS['lang']['RF102'], 'avatarLink' => createLink('resources/avatars/unknown.png', true));
            }
        }

        if(isSelected($row['notify'])){
            $outputHTML .= '<div class="notify">' . $row['msg'] . '....</div>';
            $lastMsg = array('id' => '', 'msgid' => '', 'conid' => '', 'time' => '', 'user' => '');
        }else {
            if ($row['sub_msg_id'] === -1) {
                $mainMsg[] = '{{|' . $row['id'] . '|}}';
                $msgID = $chatID . '_' . $token++;
                $outputHTML .= generateMsg($chatTemplate, $msgID, $row['msg'], $row['date'], $activeUser, $users[$activeUser]['name'], $users[$activeUser]['avatarLink'], false, $row['id']);
            } else {
                $insertID = '{{|' . $row['sub_msg_id'] . '|}}';
                $diff = strtotime($row['date']) - strtotime($lastMsg['time']);
                if ($diff > 30) {
                    $msgID = $chatID . '_' . $token++;
                    $html = generateMsg($chatTemplate, $msgID, $row['msg'], $row['date'], $activeUser, $users[$activeUser]['name'], $users[$activeUser]['avatarLink'], true, $row['sub_msg_id']);
                    $outputHTML = str_replace($insertID, $html, $outputHTML);
                } else {
                    $msgID = $lastMsg['id'];
                    $html = '<div>' . $row['msg'] . '</div>';
                    $outputHTML = str_replace($insertID, $html . $insertID, $outputHTML);
                }
            }
            $lastMsg = array('id' => $msgID, 'msgid' => 'msg_' . $msgID, 'conid' => 'con_' . $msgID, 'time' => $row['date'], 'user' => $activeUser);
        }
    }

    if($output){
        $outputHTML = str_replace($mainMsg, '', $outputHTML);
        $chatData = array('chatHTML' => $outputHTML, 'chatID' => $chatID, 'chatStatus' => $chatStatus, 'users' => $users, 'startDate'=> $startDate, 'endDate' => $endDate, 'success' => true);
    }else
        $chatData = array('success' => false);
    return $chatData;
}

function loadChatMessagesHTML($con, $chatID, $activeChat='0', $admin=false){
    $output = false;
    $token = $callBackID = $chatStatus = 0;
    $users = $mainMsg = $lastMsg = $emoticons = $notifyData = $chatSettings = array();
    $activeUserId = $activeUser = $msgID = $outputHTML = $insertID = '';

    $chatStatus = chatStatus($con, $chatID);

    if(is_array($admin)){
        $notifyData = $admin;
        unset($admin);
        $admin = true;
    }

    if($admin){
        $chatSettings = adminChatSettings($con, 'emoticons');
        $activeChats = array($chatID => $activeChat);
        $notify = true;
        if (issetSession('activeChats')) {
            $activeChats = getSession('activeChats');
            if(isset($activeChats[$chatID]))
                $notify = false;
            $activeChats[$chatID] = $activeChat;
        }

        if($notify)
            notificationUpdate($con, $chatID, -1, $notifyData['id'], $notifyData['msg'], $notifyData['date'], $notifyData['ip']);

        setSession('activeChats', $activeChats, 'admin');
        $chatTemplate = chatTemplate(ROOT_DIR . 'theme' . D_S . 'default' . D_S);
    }else{
        $chatTemplate = chatTemplate(THEME_DIR);
        $chatSettings = chatSettings($con, 'emoticons');
    }

    $result = mysqli_query($con, 'SELECT id,date,msg,sub_msg_id,user_id,staff_id,notify,data FROM ' . DB_PREFIX . 'chat_history WHERE chat_id='.$chatID. ' ORDER BY id ASC');
    while ($row = mysqli_fetch_array($result)) {
        $callBackID = $row['id'];
        if(!($output)){
            $emoticons = loadEmoticonPack($con,false,$chatSettings['emoticons']);
        }
        $row['user_id'] = intval($row['user_id']);
        $row['sub_msg_id'] = intval($row['sub_msg_id']);
        $row['staff_id'] = intval($row['staff_id']);

        if($row['msg'] === 'BALAJI_UPLOAD'){
            $row['msg'] = genUploadBox($con, $row['data'], $admin, $row['staff_id'], $row['date']);
        }else {
            $row['msg'] = chatLinkFix($row['msg']);
            if ($emoticons[0])
                $row['msg'] = stripcslashes(emoticons($row['msg'], $emoticons[1]));
        }
        $unknownUser = false; $output = true;

        if($row['user_id'] === -1){
            $activeUserId = $row['staff_id'];
            $activeUser = 'staff'.$row['staff_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name,logo FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name'], 'avatarLink' => fixLink($data['logo'],true));
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => 'Unkown', 'avatarLink' => createLink('resources/avatars/unknown.png', true));
            }
        }else{
            $activeUserId = $row['user_id'];
            $activeUser = 'user'.$row['user_id'];
            if(!isset($users[$activeUser])){
                $data = mysqliPreparedQuery($con, 'SELECT name,image FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($activeUserId));
                if($data !== false)
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => $data['name'], 'avatarLink' => fixLink($data['image'], true));
                else
                    $users[$activeUser] = array('id' => $activeUserId, 'name' => 'Unkown', 'avatarLink' => createLink('resources/avatars/unknown.png', true));
            }
        }

        if(isSelected($row['notify'])){
            $outputHTML .= '<div class="notify">' . $row['msg'] . '....</div>';
            $lastMsg = array('id' => '', 'msgid' => '', 'conid' => '', 'time' => '', 'user' => '');
        }else {
            if ($row['sub_msg_id'] === -1) {
                $mainMsg[] = '{{|' . $row['id'] . '|}}';
                $msgID = $chatID . '_' . $token++;
                $outputHTML .= generateMsg($chatTemplate, $msgID, $row['msg'], $row['date'], $activeUser, $users[$activeUser]['name'], $users[$activeUser]['avatarLink'], false, $row['id']);
            } else {
                $insertID = '{{|' . $row['sub_msg_id'] . '|}}';
                $diff = strtotime($row['date']) - strtotime($lastMsg['time']);
                if ($diff > 30) {
                    $msgID = $chatID . '_' . $token++;
                    $html = generateMsg($chatTemplate, $msgID, $row['msg'], $row['date'], $activeUser, $users[$activeUser]['name'], $users[$activeUser]['avatarLink'], true, $row['sub_msg_id']);
                    $outputHTML = str_replace($insertID, $html, $outputHTML);
                } else {
                    $msgID = $lastMsg['id'];
                    $html = '<div>' . $row['msg'] . '</div>';
                    $outputHTML = str_replace($insertID, $html . $insertID, $outputHTML);
                }
            }
            $lastMsg = array('id' => $msgID, 'msgid' => 'msg_' . $msgID, 'conid' => 'con_' . $msgID, 'time' => $row['date'], 'user' => $activeUser);
        }
    }

    if($output){
        if($admin)
            setSession($chatID . '_lastMsgID', $callBackID, 'admin');
        $outputHTML = str_replace($mainMsg, '', $outputHTML);
        $chatData = array('chatHTML' => $outputHTML, 'chatID' => $chatID, 'chatStatus' => $chatStatus, 'aUser' => $activeChat, 'users' => $users, 'lastMsg' => $lastMsg, 'token' => $token, 'callBackID' => $callBackID, 'success' => true);
    }else
        $chatData = array('success' => false);
    return $chatData;
}

function getAdminChats($con, $activeSessionID){
    $query = mysqli_query($con, 'SELECT chat_data FROM ' . DB_PREFIX . 'admin_history WHERE id=' . $activeSessionID);
    $data = mysqli_fetch_assoc($query);
    $chatDetails = json_decode($data['chat_data'],true);
    if(is_array($chatDetails))
        return $chatDetails;
    else
        return array();
}

function removeAdminChat($con, $chatAdminID, $adminID){
    $activeSessionID = getAdminActiveSessionid($con, $adminID);
    $chatDetails = getAdminChats($con, $activeSessionID);
    if(is_array($chatDetails)){
        if(isset($chatDetails[$chatAdminID])){
            unset($chatDetails[$chatAdminID]);
            updateToDbPrepared($con, 'admin_history', array('chat_data' => json_encode($chatDetails)), array('id' => $activeSessionID));
        }
    }

    $activeSessionID = getAdminActiveSessionid($con, $chatAdminID);
    $chatDetails = getAdminChats($con, $activeSessionID);
    if(is_array($chatDetails)){
        if(isset($chatDetails[$adminID])){
            unset($chatDetails[$adminID]);
            updateToDbPrepared($con, 'admin_history', array('chat_data' => json_encode($chatDetails)), array('id' => $activeSessionID));
        }
    }
    return true;
}

function getOnlineAdminCount($con,$time='30'){
    $trackQuery = mysqli_query($con, "SELECT id FROM " . DB_PREFIX . "admin WHERE last_visit > NOW() - INTERVAL $time MINUTE");
    return mysqli_num_rows($trackQuery);
}

function getOnlineAdminsData($con,$adminID,$time='30'){
    $geoData = loadIp2();
    $onlineData = $onlineData2 = '';
    $onlineAdminCount = $onlineData2Count = 0;
    $inviteChat = array();
    $inviteChatBol = false;
    $trackQuery = mysqli_query($con, "SELECT id,name,user,logo,last_visit_page,active_session_id FROM " . DB_PREFIX . "admin WHERE last_visit > NOW() - INTERVAL $time MINUTE");
    $onlineAdminCount = mysqli_num_rows($trackQuery);
    if($onlineAdminCount > 0){

        $result = mysqli_query($con, 'SELECT id,chat_id,type,admin FROM ' . DB_PREFIX . 'invite_chat WHERE staff_id='.$adminID);
        while ($rowInvite = mysqli_fetch_array($result)) {

            $chatData = mysqliPreparedQuery($con, 'SELECT user_id,status,staff_id FROM ' . DB_PREFIX . 'chat WHERE id=?', 'i', array($rowInvite['chat_id']));
            if ($chatData !== false) {
                if (intval($chatData['status']) === 2) {
                    deleteRowID($con, 'invite_chat', $rowInvite['id']);
                    if(isSelected($rowInvite['admin'])){
                        $userIsAdmin = true;
                        $rowUser = array();
                        $rowUser['name'] = getAdminName($con, $chatData['staff_id']);
                        $chatData['user_id'] = $chatData['staff_id'];
                        $chatData['staff_id'] = $adminID;
                    }else{
                        $userIsAdmin = false;
                        $result = mysqli_query($con, 'SELECT name FROM ' . DB_PREFIX . 'users WHERE id=' . $chatData['user_id']);
                        if (mysqli_num_rows($result) > 0)
                            $rowUser = mysqli_fetch_assoc($result);
                    }
                    $inviteChatBol = true;
                    $inviteChat[] = array('chatID' => $rowInvite['chat_id'], 'userID' => $chatData['user_id'], 'ownerID' => $chatData['staff_id'], 'userName' => $rowUser['name'], 'type' => $rowInvite['type'], 'admin' => $userIsAdmin);
                }
            }
        }

        $onlineData2 .= $onlineData .= '<ul class="nav nav-pills nav-stacked adminscroll">';
        $chatDetails = array();
        while ($row = mysqli_fetch_assoc($trackQuery)) {
            $query = mysqli_query($con, 'SELECT ip,browser,role,chat_data FROM ' . DB_PREFIX . 'admin_history WHERE id=' . $row['active_session_id']);
            $data = array(); $data['ip'] = $data['browser'] = $data['role'] = '-';
            $row['chatID'] = 0;
            if (mysqli_num_rows($query) > 0) {
                $data = mysqli_fetch_assoc($query);
                $chatDetails = json_decode($data['chat_data'],true);
                if(is_array($chatDetails)){
                    if(array_key_exists($adminID, $chatDetails))
                        $row['chatID'] = $chatDetails[$adminID];
                }
            }
            $fnName = 'adminChatWindow';

            $onlineData .= '<li onclick="'.$fnName.'('.$row['id'].', \''.$row['name'].'\', \''. $row['chatID'].'\', true, \''. $row['id'].'\');" id="onlineStaff' . $row['id'] . '" data-content=\'<div class="pop-details"><span class="bold">'.$GLOBALS['lang']['CH268'].':</span> ' . $row['name'] . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['CH85'].':</b> ' . $row['user'] . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['RF127'].':</b> ' . ip2Country($geoData, $data['ip']) . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['CH92'].':</b> ' . $data['ip'] . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['CH803'].':</b> ' . linkencode($row['last_visit_page']) . '</div>\' data-title="'.$GLOBALS['lang']['CH812'].'" data-html="true" data-toggle="popover" data-trigger="hover"><a href="#"><img src="' . fixLink($row['logo'],true) . '" alt="' . $row['name'] . '" width="30" height="30"> <span class="username">' . $row['name'] .'</span></a></li>';
            if($row['id'] != $adminID) {
                $onlineData2Count++;
                $onlineData2 .= '<li onclick="selInviteStaff(\'' . $row['id'] . '\')" id="inviteStaff' . $row['id'] . '"><a href="#"><img src="' . fixLink($row['logo'], true) . '" alt="' . $row['name'] . '" width="30" height="30"> <span class="username">' . $row['name'] . '</span></a></li>';
            }
        }
        $onlineData2 .= '</ul>';
        $onlineData .= '</ul>';
    }
    return array($onlineAdminCount, $onlineData, $onlineData2, $onlineData2Count, $inviteChatBol, $inviteChat);
}

function getOnlineUsersData($con,$time='30'){
    $geoData = loadIp2();
    $onlineData = '';
    $onlineUsersCount = 0;
    $userList = $pathDetails = array();
    $trackQuery = mysqli_query($con, "SELECT id,date,created,ip,user_id,ses_id,pageviews,pages,ref,last_visit,last_visit_raw,ua,screen,keywords,user_path,is_bot,chatID FROM " . DB_PREFIX . "rainbow_analytics WHERE last_visit > NOW() - INTERVAL $time MINUTE ORDER BY id DESC");
    $onlineUsersCount = mysqli_num_rows($trackQuery);
    if ($onlineUsersCount > 0) {
        $adminData = getSession(array('AdminID', 'activeChats', 'guestChats'));

        if(is_array($adminData['guestChats'])){
            $delGuestChat = true;
            foreach($adminData['guestChats'] as $userID => $id){
                $data = mysqliPreparedQuery($con, 'SELECT chat_id FROM ' . DB_PREFIX . 'guest_chat WHERE id=?', 'i', array($id));
                if($data !== false)
                    $delGuestChat = false;
                else
                    unset($adminData['guestChats'][$userID]);
            }
            if($delGuestChat)
                clearSession('guestChats');
            else
                setSession('guestChats', $adminData['guestChats'], 'chat');
        }else{
            $adminData['guestChats'] = array();
        }

        $onlineData .= '<ul class="nav nav-pills nav-stacked ulscroll">';
        while ($row = mysqli_fetch_assoc($trackQuery)) {
            if($row['user_id'] != 0) {
                if (isset($userList['user' . $row['user_id']])) {
                    $onlineUsersCount--;
                    continue;
                }
            }
            $row['pages'] = dbStrToArr($row['pages']);
            $row['user_path'] = array_reverse(dbStrToArr($row['user_path']));
            $activePage = '-';
            if(isset($row['user_path'][0])){
                if(isset($row['pages'][intval($row['user_path'][0])][0]))
                    $activePage = $row['pages'][intval($row['user_path'][0])][0];
            }
            $pathDetails['user'.$row['user_id']] = array('user_path' => $row['user_path'], 'pages' => $row['pages'], 'chatID' => $row['chatID']);
            $userDetails = getUserDetails($con, $row['user_id']);
            $chatStats = chatStatusAdmin($con, $row['chatID'], $row['user_id']);
            $userList['user'.$row['user_id']] = array('id' => $row['user_id'], 'name' => $userDetails['name'], 'chatID' => $row['chatID'], 'ownerID' => $chatStats[2]);
            if($row['user_id'] == 0 || $row['chatID'] == 0) $fnName = 'guestChatWindow'; else $fnName = 'genChatWindow';
            if($chatStats[0] === 0) $fnName = 'guestChatWindow';

            $joinFn = true;
            if($chatStats[0] === 2) {
                if ($adminData['activeChats'] !== NULL) {
                    $activeChats = $adminData['activeChats'];
                    if (isset($activeChats[$row['chatID']]))
                        $joinFn = false;
                }else{
                    if ($chatStats[2] == $adminData['AdminID'])
                        $joinFn = false;
                }
            }else
                $joinFn = false;

            if($joinFn)
                $fnName = 'joinChatWindow';

            $deptData = '';
            $nameData = '<span class="username">' . $userDetails['name'] .'</span>';
            $deptDis = '';

            if($row['user_id'] != 0 && $chatStats[0] === 0){
                if(array_key_exists($row['user_id'], $adminData['guestChats'])){
                    $fnName = 'waitGuestChat';
                    $chatStats[1] = '<span class="chat-stats chat-wait2 pull-right"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>';
                }
            }

            if($chatStats[3] !== 0){
                $deptName = 'Invalid';
                $departmentDetails = getDepartmentDetails($con, $chatStats[3]);
                if(is_array($departmentDetails)){
                    $deptName = $departmentDetails['name'];
                    if(isset($departmentDetails['data'][0])){
                        if($departmentDetails['data'][0] !== 'all'){
                            if(!in_array($adminData['AdminID'], $departmentDetails['data'])){
                            //    if($fnName !== 'genChatWindow') {
                                    $nameData = '<span class="username disabledDeptUser">' . $userDetails['name'] . '</span>';
                                    $deptDis = 'disabled';
                                    $fnName = 'disabledDept';
                             //   }
                            }
                        }
                    }
                }
                $deptData = '<div class="pop-details"><b>'.$GLOBALS['lang']['CH145'].':</b> <span class="deptColor">' . $deptName . '</span></div>';
            }

            $onlineData .= '<li class="'.$deptDis.'" onclick="'.$fnName.'('.$row['user_id'].', \''.$userDetails['name'].'\', \''. $row['chatID'].'\', true, \''. $row['id'].'\',\''.$chatStats[0].'\',\''.$chatStats[2].'\');" id="online' . $row['id'] . '" data-content=\'<div class="pop-details"><span class="bold">'.$GLOBALS['lang']['CH268'].':</span> ' . $userDetails['name'] . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['CH85'].':</b> ' . $userDetails['email'] . '</div>'.$deptData.'<div class="pop-details"><b>'.$GLOBALS['lang']['RF127'].':</b> ' . ip2Country($geoData, $row['ip']) . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['CH92'].':</b> ' . $row['ip'] . '</div><div class="pop-details"><b>'.$GLOBALS['lang']['CH803'].':</b> ' . linkencode($activePage) . '</div>\' data-title="'.$GLOBALS['lang']['CH804'].'" data-html="true" data-toggle="popover" data-trigger="hover"><a href="#"><img src="' . fixLink($userDetails['image'],true) . '" alt="' . $userDetails['name'] . '" width="30" height="30"> '. $nameData . $chatStats[1] . ' </a></li>';
        }
        $onlineData .= '</ul>';
    }
    return array($onlineUsersCount, $onlineData, $userList, $pathDetails);
}

function linkTruncate($str, $chars = 25) {
    if (strlen($str) <= $chars)
        return $str;
    $str = substr($str, 0, $chars);
    $str = $str."...";
    return $str;
}

function chatLinkFix($data){

    $urlPattern = '/\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/im';

    $pseudoUrlPattern = '/(^|[^\/])(www\.[\S]+(\b|$))/im';

    $emailAddressPattern = '/[\w.]+@[a-zA-Z_-]+?(?:\.[a-zA-Z]{2,6})+/im';

    $data = preg_replace_callback($urlPattern,   function($m) {
        $match = $m[0];
        $extensionArr = explode('.',$match);
        $extension = $extensionArr[count($extensionArr) - 1];
        if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'bmp' || $extension === 'png' || $extension === 'bmp'){
            return '<a target="_blank" href="'.$match.'"><div> <div class="previewBox"><img class="previewImg" src="'.$match.'" alt="Preview"></div> </div> '.$match.'</a> ';
        }else {
            return '<a target="_blank" href="' . $match . '">' . $match . '</a>';
        }
    }, $data);

    $data = preg_replace($pseudoUrlPattern, '${1}<a target="_blank" href="http://${2}">${2}</a>', $data);

    $data = preg_replace($emailAddressPattern, '<a target="_blank" href="mailto:${0}">${0}</a>', $data);

    return $data;

}


function secureFileUpload($fileData,$maxFileSize=500000,$fileTypes=false,$allowedTypes = array('jpeg','pdf','txt')){
    $uploadMeBalaji = true;
    $targetDir = $targetFileName = $msg = '';

    if(isset($fileData["name"]) && $fileData["name"] != ''){

        $targetDir = ROOT_DIR.'uploads'.D_S.'temp'.D_S;

        if(!is_writable($targetDir))
            mkdir($targetDir);

        $targetFileName = basename($fileData["name"]);

        //Check if file already exists
        $targetFileName = unqFile($targetDir,$targetFileName);
        $targetFile = $targetDir . $targetFileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //Check file size
        if ($fileData["size"] > $maxFileSize){
            $msg = $GLOBALS['lang']['RF97'];
            $uploadMeBalaji = false;
        } else {
            if($fileTypes) {
                //Allow only certain file formats
                if (!in_array($fileType, $allowedTypes)) {
                    $msg = $GLOBALS['lang']['CH807'].' ' . implode(', ', $allowedTypes) . ' '.$GLOBALS['lang']['CH806'];
                    $uploadMeBalaji = false;
                }
            }
        }

        //Start Upload
        if ($uploadMeBalaji){
            if (move_uploaded_file($fileData["tmp_name"], $targetFile)){
                //Uploaded
                $msg = 'uploads/temp/'.$targetFileName;
            } else{
                $msg = $GLOBALS['lang']['RF99'];
                $uploadMeBalaji = false;
            }
        }

    }else{
        $msg = $GLOBALS['lang']['CH805'];
        $uploadMeBalaji = false;
    }

    return array($uploadMeBalaji, $msg, $targetFileName);
}

function convertToBytes($from) {
    $aUnits = array('B'=>0, 'KB'=>1, 'MB'=>2, 'GB'=>3, 'TB'=>4, 'PB'=>5, 'EB'=>6, 'ZB'=>7, 'YB'=>8);
    $sUnit = strtoupper(trim(substr($from, -2)));

    if (intval($sUnit) !== 0)
        $sUnit = 'B';

    if (!in_array($sUnit, array_keys($aUnits)))
        return false;

    $iUnits = trim(substr($from, 0, strlen($from) - 2));
    if (!intval($iUnits) == $iUnits)
        return false;

    return $iUnits * pow(1024, $aUnits[$sUnit]);
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function formatBytesWithUnit($bytes, $unit = "", $decimals = 2, $returnNoUnit=false) {
    $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

    $value = 0;
    if ($bytes > 0) {
        if (!array_key_exists($unit, $units)) {
            $pow = floor(log($bytes)/log(1024));
            $unit = array_search($pow, $units);
        }
        $value = ($bytes/pow(1024,floor($units[$unit])));
    }
    if (!is_numeric($decimals) || $decimals < 0) {
        $decimals = 2;
    }
    if($returnNoUnit)
        return sprintf('%.' . $decimals . 'f', $value);
   return sprintf('%.' . $decimals . 'f '.$unit, $value);
}

function genUploadBox($con, $uploadID, $isAdmin = false, $uploadedBy, $date){

    $nowDate = date('M d Y H:i:s ') . 'GMT' . date('O');
    $userUploaded = true;
    if(intval($uploadedBy) === -1)
        $userUploaded = false;
    $diff = round(abs($nowDate - $date) / 60);

    $query = mysqli_query($con, 'SELECT chat_id,o_name,size,uploaded,approved FROM ' . DB_PREFIX . 'chat_uploads WHERE id='.$uploadID);
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_array($query);
        $type = genFileNameType($data['o_name']);
        $size = formatBytes($data['size']);
        $uploaded = intval($data['uploaded']);
        $approved = intval($data['approved']);

        if(issetSession('AdminToken'))
            $uploadLink = adminLink('chat-ajax/upload/view/'.$uploadID, true);
        else
            $uploadLink = createLink('upload/view/'.$uploadID, true);

        if($uploaded === 1 && $approved === 1)
            $status = '<a class="uploadBoxHref" target="_blank" href="'.$uploadLink.'"><span class="icon-download2"></span> '.$GLOBALS['lang']['CH58'].'</a>';
        else if($uploaded === 0 && $approved === -1)
            $status = '<span class="upFail"> <span class="icon-cross"></span> '.$GLOBALS['lang']['CH3'].'</span>';
        else if($uploaded === 0 && $approved === 1){
            $status = '<span class="upFail"> <span class="icon-cross"></span> '.$GLOBALS['lang']['CH57'].'</span>';
            if($userUploaded) {
                if ($isAdmin) {
                    if($diff <= 15)
                        $status = '<span class="upApp"> <span class="icon-cross"></span> '.$GLOBALS['lang']['CH757'].'</span>';
                }
            }
        }else if($uploaded === 0 && $approved === 0){
            if ($isAdmin) {
                $status = '
                <button class="btn btn-success btn-xs" onclick="uploadApproval(\''.$uploadID.'\', 1)"><span class="icon-checkmark"></span> '.$GLOBALS['lang']['CH808'].'</button>
                <button class="btn btn-danger btn-xs" onclick="uploadApproval(\''.$uploadID.'\', 0)"><span class="icon-cross"></span> '.$GLOBALS['lang']['CH809'].'</button>';
            } else {
                $status = '<span class="upFail"> <span class="icon-cross"></span> '.$GLOBALS['lang']['CH57'].'</span>';
            }
        }

        $cssClass = '';
        if($type === 'audio')
            $cssClass = 'uploadAudio';
        else if($type === 'video')
            $cssClass = 'uploadVideo';
        else if($type === 'image')
            $cssClass = 'uploadImg';
        else if($type === 'zip')
            $cssClass = 'uploadZip';

        return '<div class="uploadBox '.$cssClass.'" id="upload'.$uploadID.'">' . PHP_EOL.
            '    <div class="upInfo">'.$GLOBALS['lang']['CH116'].' '.$data['o_name'].'</div>' . PHP_EOL.
            '    <div class="upInfo">'.$GLOBALS['lang']['CH117'].' '.$size.'</div>'. PHP_EOL.
            '    <hr>' . PHP_EOL.
            '    <div class="uploadBoxRes" id="uploadRes'.$uploadID.'">' . PHP_EOL.
            '      '.$status. PHP_EOL.
            '    </div>' . PHP_EOL.
            '    </div>';
    }
}

function genFileNameType($filename){
    $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $types = array();
    $types['video'] = array('mp4','mkv','avi','flv','mov','wmv');
    $types['audio'] = array('mp3','aac','ogg','m4a','ac3','flac','ape','wma','wav','pcm','aiff');
    $types['image'] = array('jpg','jpeg','gif','png','bmp','tiff','tif','psd','eps','exif','bpg');
    $types['zip'] = array('zip','zipx','rar','7z','bzip','arc','bz','bz2','lzma','tar','gz');
    $types['document'] = array('doc','docx','txt','text','rtf','pdf','odt');

    if(in_array($fileType,$types['video']))
        return 'video';
    elseif(in_array($fileType,$types['audio']))
        return 'audio';
    elseif(in_array($fileType,$types['image']))
        return 'image';
    elseif(in_array($fileType,$types['zip']))
        return 'zip';
    elseif(in_array($fileType,$types['document']))
        return 'document';
    return 'other';
}

function formatTimetoAgo($time){
    $now = time();
    $diff = $now - intval($time);

    if($diff < 60)
        $diff = $diff . ' Sec';
    elseif($diff > 60 && $diff < 3600)
        $diff = round($diff / 60) . ' Min';
    else
        $diff = round($diff / 3600) . ' Hour';

    return $diff;
}

function getTone($con, $id){
    $result = mysqli_query($con, 'SELECT path,status FROM ' . DB_PREFIX . 'notifications WHERE id='.$id);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if(isSelected($row['status']))
            return fixLink($row['path'],true);
    }
    return false;
}

function getAvailableTones($con){
    $list = array();
    $result = mysqli_query($con, 'SELECT id,status,name FROM ' . DB_PREFIX . 'notifications');

    while($row = mysqli_fetch_assoc($result)) {
        if (isSelected($row['status']))
            $list[$row['id']] = $row['name'];
    }

    return $list;
}

function getAvailableEmoticonsPack($con){
    $list = array();
    $result = mysqli_query($con, 'SELECT id,status,name FROM ' . DB_PREFIX . 'emoticon');

    while($row = mysqli_fetch_assoc($result)) {
        if (isSelected($row['status']))
            $list[$row['id']] = $row['name'];
    }

    return $list;
}

function nlFix($str){
    return str_replace(array('\r\n','\n','\r'), PHP_EOL, $str);
}

function capPages(){
    $arr = array(
        'contact_page' => $GLOBALS['lang']['CH810'],
        'admin_login_page' => $GLOBALS['lang']['CH811']
    );
    return $arr;
}

function getBasicSettings($con, $sep=null){
    if($sep === NULL)
        $sep = 'site_name,email,doForce,copyright,other_settings,app_name,html_app';
    $siteInfo =  mysqli_query($con, "SELECT $sep FROM ".DB_PREFIX."site_info where id=1");
    return mysqli_fetch_assoc($siteInfo);
}

function cleanMyLink($url,$baseURL){
    $baseURL = clean_with_www($baseURL);
    $url = clean_with_www($url);
    return str_replace($baseURL, '', $url);
}

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'ogg' => 'audio/ogg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}