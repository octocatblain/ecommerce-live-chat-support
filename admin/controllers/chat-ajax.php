<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

//AJAX ONLY
$nowDate = date('M d Y H:i:s ') . 'GMT' . date('O');

ob_end_clean();
header('Content-Type: application/json');

if($pointOut === 'userDetailsArr'){

    $chats = $_POST['chats'];
    $output = array('success' => false);
    $output['userDetails'] = array();
    $output['remove'] = true;

    foreach($chats as $activeChat => $chatID){
        $chatID = intval($chatID);
        $outputData = processUserDetails($con, $chatID, $baseURL);
        if($outputData['success']){
            $output['success'] = true;
            $output['userDetails'][$chatID] = $outputData;
        }else{
            $output['remove'] = false;
        }
    }

    echo json_encode($output);
    die();
}

if($pointOut === 'userDetails'){
    $chatID = intval($args[0]);
    $output = processUserDetails($con, $chatID, $baseURL);
    echo json_encode($output);
    die();
}

if($pointOut === 'adminDetails'){
    $chatID = intval($args[0]);
    $adminID = intval($args[1]);
    $output = processAdminDetails($con, $adminID, $chatID, $baseURL);
    echo json_encode($output);
    die();
}

if($pointOut === 'userUpload'){

    $output = array('success' => false);

    if($args[0] === 'approval') {
        $uploadID = intval($args[1]);
        $stats = intval($args[2]);

        if($stats === 0)
            $stats = -1;

        if (!updateToDbPrepared($con, 'chat_uploads', array('approved' => $stats), array('id' => $uploadID))) {
            $output['success'] = true;

            if ($stats === 1)
                $output['msg'] = $lang['CH757'];
            else
                $output['msg'] = $lang['CH3'];
        }
    }elseif($args[0] === 'pending'){
        $pendingUploads = false;
        $pendingArr = json_decode($_POST['data'],true);
        $uploads = array();
        if(count($pendingArr) !== 0){
            $result = mysqli_query($con, 'SELECT id,uploaded FROM ' . DB_PREFIX . 'chat_uploads WHERE id IN ('.implode(',',$pendingArr).')');
            while ($row = mysqli_fetch_array($result)) {
                $row['uploaded'] = intval($row['uploaded']);
                if($row['uploaded'] === 1) {
                    $uploads[$row['id']] = array('stats' => true);
                }else
                    $pendingUploads = true;
            }
        }
        $output['success'] = true;
        $output['pendingUploads'] = $pendingUploads;
        $output['upload'] = $uploads;
    }
    echo json_encode($output);
    die();
}

if($pointOut === 'upload'){
    $jsonData = array('success' => 0, 'upload' => 0);
    $chatSettings = adminChatSettings($con, array('file_share', 'upload_size'));
    if(isSelected($chatSettings['file_share'])) {
        if ($args[0] === 'view') {
            $uploadID = intval($args[1]);
            $query = mysqli_query($con, 'SELECT chat_id,o_name,name,uploaded,approved FROM ' . DB_PREFIX . 'chat_uploads WHERE id=' . $uploadID);
            if (mysqli_num_rows($query) > 0) {
                $data = mysqli_fetch_array($query);
                if (isSelected($data['approved']) && isSelected($data['uploaded'])) {
                    ob_end_clean();
                    dlSendHeaders($data['o_name']);
                    readfile(ROOT_DIR . 'uploads' . D_S . 'temp' . D_S . $data['name']);
                    die();
                }
            }
            die($lang['CH758']);
        } elseif ($args[0] === 'new') {

            $myValues = array_map_recursive(function ($item) use ($con) {
                return escapeTrim($con, $item);
            }, $_POST);

            if (round($myValues['size']) <= round($chatSettings['upload_size'])) {

                $myValues['uploaded'] = 0;
                $myValues['o_name'] = $myValues['name'];
                $myValues['name'] = '';
                $myValues['approved'] = 1;
                $jsonData['upload'] = 1;
                $subMsg = isSelected($myValues['subMsg']);
                unset($myValues['subMsg']);

                if (insertToDbPrepared($con, 'chat_uploads', $myValues)) {
                    $jsonData['error'] = $lang['RF40'];
                } else {
                    $uploadID = mysqli_insert_id($con);
                    $jsonData['success'] = true;
                    $jsonData['uploadID'] = $uploadID;
                    $jsonData['type'] = genFileNameType($myValues['o_name']);

                    if (isSelected($jsonData['upload'])) {
                        $jsonData['uploadAuth'] = randomChar(10);
                        setSession('uploadAuth'.$uploadID, $jsonData['uploadAuth']);
                    }

                    $mainMsgID = 0;
                    $subMsgID = -1;
                    if($subMsg)
                        $subMsgID = getSession($myValues['chat_id'].'_mainMsgID');

                    $insert = array();
                    $insert['date'] = $nowDate;
                    $insert['ip'] = $ip;
                    $insert['sub_msg_id'] = $subMsgID;
                    $insert['msg'] = 'BALAJI_UPLOAD';
                    $insert['chat_id'] = $myValues['chat_id'];
                    $insert['user_id'] = -1;
                    $insert['staff_id'] = $adminID;
                    $insert['data'] = $uploadID;

                    if (!(insertToDbPrepared($con, 'chat_history', $insert))) {
                        $insID = mysqli_insert_id($con);

                        if($subMsg)
                            $mainMsgID = $subMsgID;
                        else
                            $mainMsgID = $insID;

                        setSession($myValues['chat_id'] . '_mainMsgID', $mainMsgID, 'admin');

                        updateToDbPrepared($con, 'chat_uploads', array('data_id' => $insID), array('id' => $uploadID));
                    }
                }
            }else {
                $jsonData['error'] = $lang['CH759'];
            }
        } elseif ($args[0] === 'process') {
            if (isset($args[2])) {
                $args[1] = intval($args[1]);
                if (getSession('uploadAuth'.$args[1]) === $args[2]) {
                    clearSession('uploadAuth'.$args[1]);
                    $output = secureFileUpload($_FILES['file'], round($chatSettings['upload_size']));
                    if ($output[0]) {
                        $jsonData['success'] = $jsonData['upload'] = true;
                        updateToDbPrepared($con, 'chat_uploads', array('uploaded' => true, 'name' => $output[2]), array('id' => $args[1]));
                    } else {
                        $jsonData['success'] = true;
                        $jsonData['upload'] = false;
                        $jsonData['error'] = $output[1];
                    }
                }
            }
        }
    }
    echo json_encode($jsonData);
    die();
}

if($pointOut === 'operator'){

    $output = array('success' => false);

    if(isset($args[0])){

        if($args[0] === 'create'){
            $userID = intval($args[1]);
            $ownerID = intval($args[2]);

            //Create Chat
            if (!insertToDbPrepared($con, 'chat', array('user_id' => 'a'.$userID, 'status' => 2, 'dept' => '', 'staff_id' => $ownerID, 'operators' => json_encode(array($adminID)), 'date' => $nowDate))) {
                $chatID = mysqli_insert_id($con);
                $adminData = getAdminDetails($con, $userID);
                $output = array('success' => true, 'userID' => 'a'.$userID, 'chatID' => $chatID, 'ownerID' => $adminID, 'userName' => $adminData['name'], 'avatarLink' => $adminData['logo']);
            }
        }
    }

    echo json_encode($output);
    die();
}

if($pointOut === 'close'){
    $output = array('success' => false);

    $chatID = intval($args[0]);
    $notify = intval($args[1]);
    $chatAdminID = intval($args[2]);

    if($chatAdminID !== 0)
        removeAdminChat($con, $chatAdminID, $adminID);

    if(isSelected($notify))
        notificationUpdate($con, $chatID, -1, $adminID, $lang['CH760'], $nowDate, $ip);

    if(!updateToDbPrepared($con, 'chat', array('status' => 0), array('id' => $chatID))){
        clearSession(array($chatID.'_lastMsgID', $chatID.'_mainMsgID'));
        unset($_SESSION[N_APP.'activeChats'][$chatID]);
        $output = array('success' => true);
    }

    echo json_encode($output);
    die();
}

if($pointOut === 'leave'){
    $output = array('success' => false);

    $chatID = intval($args[0]);
    $notify = intval($args[1]);

    removeOperator($con, $chatID, $adminID);

    if(isSelected($notify))
        notificationUpdate($con, $chatID, -1, $adminID, $lang['CH761'].' '. $adminName .' '.$lang['CH762'], $nowDate , $ip);

    clearSession(array($chatID.'_lastMsgID', $chatID.'_mainMsgID'));
    unset($_SESSION[N_APP.'activeChats'][$chatID]);
    $output['success'] = true;

    echo json_encode($output);
    die();
}

if($pointOut === 'invite'){

    $userID = intval($args[0]);
    $analyticsID = intval($args[1]);
    $output = array('success' => false);
    $userInfo = false;

    //Create User Account
    if($userID === 0 ){

        $query = mysqli_query($con, 'SELECT id FROM ' . DB_PREFIX . 'users ORDER BY id DESC limit 1');
        $getLastID = mysqli_fetch_array($query);
        $userID = intval($getLastID['id']) + 1;

        $avatars = getAvatars($con,true);
        $availableAvatars = $avatars[0];
        $defaultAvatar = current($availableAvatars);

        $userData['name'] = 'Guest'.$userID;
        $userData['email'] = 'no-email@guest.com';
        $userData['created_ip'] = '';
        $userData['created_at'] = $userData['last_active'] = $nowDate;
        $userData['image'] = $defaultAvatar;

        if(!insertToDbPrepared($con, 'users', $userData)) {
            $userID = mysqli_insert_id($con);
            $userInfo = true;
        }

    }else{
        $userInfo = true;
        $userData = mysqliPreparedQuery($con, 'SELECT name,image FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($userID));
    }

    if($userInfo) {

        //Create Chat
        if (!insertToDbPrepared($con, 'chat', array('user_id' => $userID, 'status' => 2, 'dept' => '', 'staff_id' => $adminID, 'operators' => json_encode(array($adminID)), 'date' => $nowDate))) {
            $chatID = mysqli_insert_id($con);

            if (!insertToDbPrepared($con, 'guest_chat', array('date' => date("Y-m-d H:i:s"), 'user_id' => $userID, 'chat_id' => $chatID, 'analytics' => $analyticsID))) {

                if(issetSession('guestChats'))
                    $guestChats = getSession('guestChats');
                else
                    $guestChats = array();

                $guestChats[$userID] = mysqli_insert_id($con);
                setSession('guestChats', $guestChats, 'chat');

                $output = array('success' => true, 'userID' => $userID, 'chatID' => $chatID, 'ownerID' => $adminID, 'userName' => $userData['name'], 'avatarLink' => $userData['image']);
            }
        }
    }

    echo json_encode($output);
    die();
}

if($pointOut === 'inviteStaff'){
    $staffID = intval($_POST['staffID']);
    $chatID = intval($_POST['chatID']);
    $output = array('success' => false);
    $type = 4;

    if(!isOperatorPresent($con, $chatID, $staffID)){
        if (!insertToDbPrepared($con, 'invite_chat', array('date' => date("Y-m-d H:i:s"), 'staff_id' => $staffID, 'chat_id' => $chatID, 'type' => $type, 'admin' => 0)))
            $output = array('success' => true);
    }else{
        $output['error'] = $lang['CH763'];
    }

    echo json_encode($output);
    die();
}

if($pointOut === 'chats'){
    $onlineData = '';
    $pathDetails = $chatHTML = $userList = $userDetailsArr = array();
    $updateChatHTML = false;
    $chatData = array('success' => false);

    $data = getOnlineUsersData($con);
    $adminData = getOnlineAdminsData($con, $adminID);
    $onlineUsersCount = $data[0];
    $onlineData = $data[1];
    $userList = $data[2];
    $pathDetails = $data[3];

    if($adminData[3] === 0)
        $adminData[2] = '<div class="noOnline">'.$lang['CH764'].'</div>';

    if($onlineUsersCount === 0){
        $onlineData = '<div class="noOnline">'.$lang['CH765'].'</div>';
    }else {
        if(issetSession('activeChats')){
            $activeChats = getSession('activeChats');
            foreach ($activeChats as $chatID => $user){

                //<-Admin Chat Fix->
                if(substr($user, 0, 5) === 'staff'){
                    $userAdminID = intval(substr($user, 5));
                    if(!isset($userList[$user])) {
                        $userList[$user] = array('id' => $userAdminID, 'name' => getAdminName($con, $userAdminID), $chatID, null);
                    }
                }
                //<-Admin Chat Fix->

                if(isset($userList[$user])) {
                    $pathDetail = $pathDetails[$user];
                    $userPathData = '<table class="table">';
                    $pageCount =0;
                    foreach($pathDetail['user_path'] as $k){
                        $pageCount++;
                        if(isset($pathDetail['pages'][$k])){
                            $pageTitle = $pathDetail['pages'][$k][0];
                            if(isset($pathDetail['pages'][$k][3]))
                                $pageTitle = $pathDetail['pages'][$k][3];
                            $pageTime = $pathDetail['pages'][$k][2];

                            $userPathData .= '<tr><td class="vPath">  <i class="fa fa-external-link-square" aria-hidden="true"></i> &nbsp; <a target="_blank" title="'.$pathDetail['pages'][$k][0].'" href="'.$pathDetail['pages'][$k][0].'">'.linkTruncate($pageTitle, 30).'</a></td><td>'.formatTimetoAgo($pageTime).'</td></tr>';
                        }else{
                            break;
                        }
                        if($pageCount === 5)
                            break;
                    }
                    $userPathData .= '</table';
                    $updateChatHTML = true;
                    if ($args[0] === 'refresh') {
                        $lastMsgID = 0;
                        if (issetSession($chatID . '_lastMsgID'))
                            $lastMsgID = getSession($chatID . '_lastMsgID');
                        $chatHTML['chat'.$chatID] = loadNewMessages($con, $chatID, $lastMsgID, 0, true, $user);
                    } elseif ($args[0] === 'load-all') {
                        $chatHTML['chat'.$chatID] = loadChatMessagesHTML($con, $chatID, $user, true);
                    }
                    $chatHTML['chat'.$chatID]['pagesData'] = $userPathData;
                    $chatHTML['chat'.$chatID]['ownerID'] = $userList[$user]['ownerID'];
                }
            }
        }
    }
    $chatData = array('success' => true, 'onlineUsersCount' => $onlineUsersCount, 'onlineData' => $onlineData, 'onlineAdminCount' => $adminData[0], 'adminData' => $adminData[1], 'inviteData' => $adminData[2], 'inviteChatBol' => $adminData[4], 'inviteChat' => $adminData[5], 'userList' => $userList, 'updateChatHTML' => $updateChatHTML, 'chatHTML' => $chatHTML);
    echo json_encode($chatData);
    die();
}

if($pointOut === 'load-messages-html'){
    $defaultMsg = $lang['CH767'].' '.$adminInfo['AdminName'];
    $chatID = intval($args[0]);
    $activeChat = escapeTrim($con, $args[1]);
    if(isset($args[2])){
        $status = intval($args[2]);
        if($status === 1){
            updateChat($con, $chatID, 2, $adminID, true);
        }elseif($status === 4){
            addOperator($con, $chatID, $adminID);
            $defaultMsg = $lang['CH761'].' '. $adminInfo['AdminName']. ' '.$lang['CH766'];
        }
    }
    $chatData = loadChatMessagesHTML($con, $chatID, $activeChat, array('id' => $adminID, 'ip' => $ip, 'msg' => $defaultMsg, 'date' => $nowDate));
    echo json_encode($chatData);
    die();
}

if($pointOut === 'add'){

    $jsonData = array('error' => $lang['CH7']);
    $adminData = array();

    $myValues = array_map_recursive(
        function($item) use ($con) { return escapeTrim($con,$item); },
        $_POST
    );

    $mainMsgID = 0;
    $subMsgID = -1;

    if(isset($myValues['subMsg'])) {
        $adminData = getSession(array('AdminID', $myValues['chatID'] . '_mainMsgID', 'activeChats'));
        $subMsgID = $adminData[$myValues['chatID'] . '_mainMsgID'];
    }else {
        $adminData = getSession(array('AdminID','activeChats'));
    }

    if(array_key_exists($myValues['chatID'],$adminData['activeChats'])) {
        $insert = array();
        $insert['date'] = $nowDate;
        $insert['ip'] = $ip;
        $insert['sub_msg_id'] = $subMsgID;
        $insert['msg'] = $myValues['msg'];
        $insert['chat_id'] = $myValues['chatID'];
        $insert['user_id'] = -1;
        $insert['staff_id'] = $adminData['AdminID'];

        if (!(insertToDbPrepared($con, 'chat_history', $insert))) {
            $insID = mysqli_insert_id($con);

            if (isset($myValues['subMsg']))
                $mainMsgID = $subMsgID;
            else
                $mainMsgID = $insID;

            setSession($myValues['chatID'] . '_mainMsgID', $mainMsgID, 'admin');
            $jsonData = array('success' => '1', 'msgID' => $insID);
        }
        echo json_encode($jsonData);
    }
}

if($pointOut === 'ping'){
    updateToDbPrepared($con, 'admin', array('last_visit' => date("Y-m-d H:i:s")), array('id' => $adminID));
    echo json_encode(array('success' => true));
}

if($pointOut === 'search-canned'){
    $jsonData = array('success' => true);
    $word = escapeTrim($con, $_POST['word']);
    $word2 = escapeTrim($con, $_POST['word2']);
    $out = getCannedMsg($con, $word, $word2);
    $jsonData['canned'] = $out['result'];
    $jsonData['count'] = $out['count'];
    echo json_encode($jsonData);
}

if($pointOut === 'createAdminChat'){
    $jsonData = array('success' => false);
    $adminChatIDArr = createChat($con, $adminID, '', $nowDate, true, 2, 1);
    if($adminChatIDArr[0]) {
        $adminChatID = $adminChatIDArr[1];
        $chattingAdminID = intval($args[0]);
        $activeSessionID = getSession('activeSessionID');
        $chats = getAdminChats($con, $activeSessionID);
        $chats[$chattingAdminID] = $adminChatID;

        $jsonData['chatID'] = $adminChatID;
        $jsonData['activeSessionID'] = $activeSessionID;
        updateToDbPrepared($con, 'admin_history', array('chat_data' => json_encode($chats)), array('id' => $activeSessionID));
        updateToDbPrepared($con, 'chat', array('user_id' => $chattingAdminID), array('id' => $adminChatID));

        $chattingSesID = getAdminActiveSessionid($con, $chattingAdminID);
        $chats = getAdminChats($con, $chattingSesID);
        $chats[$adminID] = $adminChatID;
        updateToDbPrepared($con, 'admin_history', array('chat_data' => json_encode($chats)), array('id' => $chattingSesID));

        $type = 4;
        if (!insertToDbPrepared($con, 'invite_chat', array('date' => date("Y-m-d H:i:s"), 'staff_id' => $chattingAdminID, 'chat_id' => $adminChatID, 'type' => $type, 'admin' => 1)))
            $jsonData['success'] = true;
    }
    echo json_encode($jsonData);
}

if($pointOut === 'print-chat'){
    ob_end_clean();
    header('Content-Type: text/html');
    $chatID = intval($args[0]);
    $html = '';
    if($chatID !== 0){
        $printTitle = $lang['CH5'];
        $html =
            '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="content-type" content="text/html" />
                <meta name="author" content="Balaji" />
            
                <title>'.$printTitle.'</title>
            </head>
            <body>
                '.genTranscript($con, $chatID).'
            <script type="text/javascript">
                setTimeout(function () { window.print(); }, 500);
                window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
            </script>
            </body>
            </html>';
    }
    die($html);
}

if($pointOut === 'export'){
    $chatID = intval($args[0]);
    if($chatID !== 0)
        genTranscriptFile($con, $chatID);
}

die();