<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Pinky Live Chat
 * @copyright 2020 ProThemes.Biz
 *
 */

//AJAX ONLY
$nowDate = date('M d Y H:i:s ') . 'GMT' . date('O');

ob_end_clean();
header('Content-Type: application/json');

if($pointOut === 'load-messages'){
    $chatData = getChatData($con);
    echo json_encode($chatData);
}

if($pointOut === 'guest'){

    $output = array('success' => false);

    if(issetSession('analyticsID')){
        $id = intval(getSession('analyticsID'));

        $query = mysqli_query($con, 'SELECT id, chat_id, user_id FROM ' . DB_PREFIX . 'guest_chat WHERE analytics='.$id. ' ORDER by id DESC');
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_array($query);

            mysqli_query($con, 'DELETE FROM ' . DB_PREFIX . 'guest_chat WHERE id='. $data['id']);

            $userData = mysqliPreparedQuery($con, 'SELECT name,image,email FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($data['user_id']));
            $output = array('success' => true, 'chatID' => $data['chat_id'], 'userID' => $data['user_id'], 'name' => $userData['name'], 'email' => $userData['email'], 'avatarLink' => $userData['image']);

            setSession(array('userID' => $data['user_id'], 'userName' => $userData['name'], 'userEmail' => $userData['email'], 'userImage' => $userData['image'], 'chatID' => $data['chat_id']),null,'chat');

            updateAnalyticsChatID($con, $id, $data['chat_id'], $data['user_id']);
            updateAnalyticsId($con, $id, $data['chat_id']);
        }
    }
    echo json_encode($output);
    die();
}

if($pointOut === 'analytics'){
    if(isset($args[0]) && $args[0] === 'update') {
        if(issetSession('analyticsID')){
            $update = array();
            $created = date("Y-m-d H:i:s");
            $nowTime = time();
            $id = intval(getSession('analyticsID'));

            //Update Last Visit
            $update['last_visit'] = $created;
            $update['last_visit_raw'] = $nowTime;

            //Write to DB
            if (updateToDbPrepared($con, 'rainbow_analytics', $update, array('id' => $id))) die($lang['RF91']);
        }
    }
}

if($pointOut === 'load-messages-html'){
    $chatID = getSession('chatID');
    $chatData = loadChatMessagesHTML($con, $chatID);
    echo json_encode($chatData);
    die();
}

if($pointOut === 'check'){
    $chatData = array();
    $callBackID = 0;
    if($args[0] === 'new-messages'){
        if(isset($args[1]) && $args[1] != ''){
            $callBackID = intval($args[1]);
        }else {
            if (issetSession('lastMsgID'))
                $callBackID = getSession('lastMsgID');
        }
        $chatInfo = getSession(array('chatID', 'userID'));
        $chatData = loadNewMessages($con, $chatInfo['chatID'], $callBackID, $chatInfo['userID']);

        if($chatData['chatStatus'] == 0)
            clearSession(array('chatID','lastMsgID','mainMsgID'));
    }
    echo json_encode($chatData);
}

if($pointOut === 'user'){

    $inputUsers = explode(',', $_POST['users']);
    $outputUsers = array();

    foreach ($inputUsers as $user){
        $check = substr($user, 0, 4);
        if($check === 'user'){
            $inputID = intval(str_replace('user','',$user));
            $data = mysqliPreparedQuery($con, 'SELECT name,image FROM ' . DB_PREFIX . 'users WHERE id=?', 'i', array($inputID));
            if($data !== false)
                $outputUsers['user'.$inputID] = array('id' => $inputID, 'name' => $data['name'], 'avatarLink' => fixLink($data['image'],true));
        }else{
            $inputID = intval(str_replace('staff','',$user));
            $data = mysqliPreparedQuery($con, 'SELECT name,logo FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($inputID));
            if($data !== false)
                $outputUsers['staff'.$inputID] = array('id' => $inputID, 'name' => $data['name'], 'avatarLink' => fixLink($data['logo'],true));
        }
    }
    echo json_encode($outputUsers);
}

if($pointOut === 'new'){
    $jsonData = '';
    $chatFail = true; $createChat = false;
    $avatars = getAvatars($con);
    $myValues = array_map_recursive(
        function($item) use ($con) { return escapeTrim($con,$item); },
        $_POST
    );
    $analyticsID = getSession('analyticsID');

    $data = mysqliPreparedQuery($con, 'SELECT id FROM ' . DB_PREFIX . 'users WHERE email=?', 's', array($myValues['email']));
    if ($data !== false) {
        $userID = $data['id'];
        $update = array();
        $update['name'] = $myValues['name'];
        $update['last_active'] = $nowDate;
        $update['image'] = cleanMyLink($avatars[0][$myValues['image']], $baseURL);

        if(!(updateToDbPrepared($con, 'users', $update , array('id' => $userID))))
            $createChat = true;

    }else{
        $insert = array();
        $insert['name'] = $myValues['name'];
        $insert['email'] = $myValues['email'];
        $insert['created_ip'] = $ip;
        $insert['created_at'] = $insert['last_active'] = $nowDate;
        $insert['image'] = cleanMyLink($avatars[0][$myValues['image']], $baseURL);

        if(!(insertToDbPrepared($con, 'users', $insert))){
            $userID = mysqli_insert_id($con);
            $createChat = true;
        }
    }

    if($createChat){
        $result = createChat($con, $userID, $myValues['help'], $nowDate,false,1,0,$analyticsID);
        if($result[0]){
            $chatFail = false;
            setSession(array('userID' => $userID, 'userName' => $myValues['name'], 'userEmail' => $myValues['email'], 'userImage' => $avatars[0][$myValues['image']], 'chatID' => $result[1]),null,'chat');
            $jsonData = array('success' => '1', 'chatID' => $result[1], 'userID' => $userID);
            if(REM_USER_DETAILS){
                setcookie(N_APP.'_user', $myValues['name'], time() + (86400 * 300),'/'.$subPath);
                setcookie(N_APP.'_email', $myValues['email'], time() + (86400 * 300),'/'.$subPath);
                setcookie(N_APP.'_avatar', $myValues['image'] , time() + (86400 * 300),'/'.$subPath);
            }
            if(!(updateAnalyticsChatID($con, $analyticsID, $result[1], $userID)))
                $chatFail = true;
        }
    }

    if($chatFail)
        $jsonData = array('error' => trans('Failed to start chat!', $lang['CH8'], true));

    echo json_encode($jsonData);
}

if($pointOut === 'add'){

    $jsonData = array('error' => trans('Unable to send chat message!', $lang['CH7'], true));

    if(issetSession('chatID')){
        $myValues = array_map_recursive(
            function($item) use ($con) { return escapeTrim($con,$item); },
            $_POST
        );

        $mainMsgID = 0;
        $subMsgID = '-1';
        if(isset($myValues['subMsg'])) {
            $userData = getSession(array('chatID', 'userID', 'mainMsgID'));
            $subMsgID = $userData['mainMsgID'];
        }else {
            $userData = getSession(array('chatID', 'userID'));
        }

        $insert = array();
        $insert['date'] = $nowDate;
        $insert['ip'] = $ip;
        $insert['sub_msg_id'] = $subMsgID;
        $insert['msg'] = $myValues['msg'];
        $insert['chat_id'] = $userData['chatID'];
        $insert['user_id'] = $userData['userID'];
        $insert['staff_id'] = -1;

        if(!(insertToDbPrepared($con, 'chat_history', $insert))){
            $insID = mysqli_insert_id($con);

            if(isset($myValues['subMsg']))
                $mainMsgID = $subMsgID;
            else
                $mainMsgID = $insID;

            setSession('mainMsgID', $mainMsgID, 'chat');

            $jsonData = array('success' => '1', 'msgID' => $insID);
        }
    }
    echo json_encode($jsonData);
}

if($pointOut === 'contact'){
    $jsonData = array('success' => 0);
    $myValues = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    $mailOut = sendMail($con, $lang['CH6'].' '.$myValues['name'], $myValues['msg'], $myValues['email'], $myValues['name'], $adminEmail, $site_name);
    if ($mailOut === 1)
        $jsonData['success'] = 1;
    echo json_encode($jsonData);
}

if($pointOut === 'upload'){

    $jsonData = array('success' => 0, 'upload' => 0);

    if(issetSession('chatID')) {
        $ids = getSession(array('chatID', 'userID', 'mainMsgID'));
        $chatSettings = chatSettings($con, array('file_share', 'upload_size', 'upload_approve'));

        if(isSelected($chatSettings['file_share'])) {
            if ($args[0] === 'new') {

                $myValues = array_map_recursive(function ($item) use ($con) {
                    return escapeTrim($con, $item);
                }, $_POST);

                if (round($myValues['size']) <= round($chatSettings['upload_size'])) {

                    $myValues['uploaded'] = 0;
                    $myValues['o_name'] = $myValues['name'];
                    $myValues['name'] = '';
                    $myValues['chat_id'] = $ids['chatID'];

                    if (isSelected($chatSettings['upload_approve'])) {
                        $myValues['approved'] = 1;
                        $jsonData['upload'] = 1;
                    } else
                        $myValues['approved'] = 0;

                    $subMsg = isSelected($myValues['subMsg']);
                    unset($myValues['subMsg']);

                    if (insertToDbPrepared($con, 'chat_uploads', $myValues)) {
                        $jsonData['error'] = 'Database error!';
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
                            $subMsgID = $ids['mainMsgID'];

                        $insert = array();
                        $insert['date'] = $nowDate;
                        $insert['ip'] = $ip;
                        $insert['sub_msg_id'] = $subMsgID;
                        $insert['msg'] = 'BALAJI_UPLOAD';
                        $insert['chat_id'] = $ids['chatID'];
                        $insert['user_id'] = $ids['userID'];
                        $insert['staff_id'] = -1;
                        $insert['data'] = $uploadID;

                        if (!(insertToDbPrepared($con, 'chat_history', $insert))) {
                            $insID = mysqli_insert_id($con);

                            if($subMsg)
                                $mainMsgID = $subMsgID;
                            else
                                $mainMsgID = $insID;

                            setSession('mainMsgID', $mainMsgID, 'chat');

                            updateToDbPrepared($con, 'chat_uploads', array('data_id' => $insID), array('id' => $uploadID));
                        }
                    }
                } else {
                    $jsonData['error'] = trans('Uploaded file size is big', $lang['CH1'], true);
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
            } elseif ($args[0] === 'view') {
                $uploadID = intval($args[1]);
                $chatID = getSession('chatID');
                $query = mysqli_query($con, 'SELECT chat_id,o_name,name,uploaded,approved FROM ' . DB_PREFIX . 'chat_uploads WHERE id=' . $uploadID);
                if (mysqli_num_rows($query) > 0) {
                    $data = mysqli_fetch_array($query);
                    if ($chatID == $data['chat_id']) {
                        if (isSelected($data['approved']) && isSelected($data['uploaded'])) {
                            ob_end_clean();
                            dlSendHeaders($data['o_name']);
                            readfile(ROOT_DIR . 'uploads' . D_S . 'temp' . D_S . $data['name']);
                            die();
                        }
                    }
                }

                die(trans('You are not authorized to access this page!', $lang['CH2'], true));

            } elseif ($args[0] === 'pending') {
                $pendingUploads = false;
                $pendingArr = json_decode($_POST['data'],true);
                $uploads = array();
                if(count($pendingArr) !== 0){
                    $result = mysqli_query($con, 'SELECT id,approved FROM ' . DB_PREFIX . 'chat_uploads WHERE id IN ('.implode(',',$pendingArr).')');
                    while ($row = mysqli_fetch_array($result)) {
                        $row['approved'] = intval($row['approved']);
                        if($row['approved'] === 1) {
                            $uploadAuth = randomChar(10);
                            setSession('uploadAuth'.$row['id'], $uploadAuth);
                            $uploads[$row['id']] = array('stats' => true, 'uploadAuth' => $uploadAuth);
                        }elseif($row['approved'] === -1)
                            $uploads[$row['id']] = array('stats' => false, 'error' => trans('Operator denied the file!', $lang['CH3'], true));
                        elseif($row['approved'] === 0)
                            $pendingUploads = true;
                    }
                }
                $jsonData['success'] = true;
                $jsonData['pendingUploads'] = $pendingUploads;
                $jsonData['upload'] = $uploads;
            }
        }
    }
    echo json_encode($jsonData);
}

if($pointOut === 'tone'){
    $jsonData = array('success' => 1);
    $tone = intval($args[0]);
    setSession('tone', $tone);
    echo json_encode($jsonData);
}

if($pointOut === 'close'){
    $jsonData = array('success' => 0);

    $action = intval($args[0]);
    if($action === 1) {
        if (issetSession('chatID')) {
            $chatID = getSession('chatID');
            updateChat($con, $chatID, 0, null);
            $jsonData['success'] = 1;
        }
    }

    clearSession('chatID');
    echo json_encode($jsonData);
}

if($pointOut === 'transcript'){
    $jsonData = array('success' => 0);
    $chatID = intval($args[0]);
    if(issetSession('userID')) {
        $userID = getSession('userID');
        $userDetails = getUserDetails($con, $userID);
        $data = mysqliPreparedQuery($con, 'SELECT status,dept FROM ' . DB_PREFIX . 'chat WHERE id=? AND user_id=?', 'ii', array($chatID,$userID));
        if ($data !== false) {
            if ($userDetails['email'] !== 'no-email@guest.com' && $userDetails['email'] !== '-') {
                $content = genTranscript($con, $chatID);
                if(!issetSession('transcript_'.$chatID)) {
                    $mailOut = sendMail($con, $lang['CH4'], $content, $adminEmail, $site_name, $userDetails['email'], $userDetails['name']);
                    if ($mailOut === 1) {
                        setSession('transcript_' . $chatID, 1);
                        $jsonData['success'] = 1;
                    }
                }
            }
        }
    }
    echo json_encode($jsonData);
}

if($pointOut === 'print-chat'){
    ob_end_clean();
    header('Content-Type: text/html');
    $chatID = intval(getSession('chatID'));
    $html = '';
    if($chatID !== 0){
        $printTitle = trans('Print Chat', $lang['CH5'], true);
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

if($pointOut === 'rating'){
    $jsonData = array('success' => 0);

    $chatID = intval($args[0]);
    $score = intval($args[1]);

    if($score === 2)
        $score = 5;
    elseif($score === 1)
        $score = 3;
    elseif($score === 0)
        $score = 1;
    else
        $score = 0;

    if(issetSession('userID')) {
        $userID = getSession('userID');
        $data = mysqliPreparedQuery($con, 'SELECT status FROM ' . DB_PREFIX . 'chat WHERE id=? AND user_id=?', 'ii', array($chatID,$userID));
        if ($data !== false) {
            if(!updateToDbPrepared($con, 'chat', array('rate' => $score), array('id' => $chatID)))
                $jsonData['success'] = 1;
        }
    }
    echo json_encode($jsonData);
}


if($pointOut === 'add-notify-msg'){
    $jsonData = array('success' => 0);

    if(issetSession('chatID')) {
        $userData = getSession(array('chatID', 'userID'));
        $msg = raino_trim($_POST['msg']);
        notificationUpdate($con, $userData['chatID'], $userData['userID'], -1, $msg, $nowDate, $ip);
        $jsonData['success'] = 1;
    }
    echo json_encode($jsonData);
}

if($pointOut === 'update-user'){
    $jsonData = array('success' => 0);

    if(issetSession('userID')) {
        $updateData = array();
        $mailUpdate = false;
        $inEmail = raino_trim($_POST['email']);
        $updateData['name'] = raino_trim($_POST['name']);

        $userID = getSession('userID');
        $userDetails = getUserDetails($con, $userID);

        if($userDetails['email'] === 'no-email@guest.com' || $userDetails['userEmail'] === '' || $userDetails['userEmail'] === '-') {
            $updateData['email'] = $inEmail;
            $mailUpdate = true;
        }

        if(!updateToDbPrepared($con, 'users', $updateData, array('id' => $userID))) {
            $jsonData['success'] = 1;
            if($mailUpdate)
                setSession(array('userName' => $updateData['name'], 'userEmail' => $updateData['email']),null,'chat');
            else
                setSession('userName', $updateData['name'], 'chat');
        }
    }
    echo json_encode($jsonData);
}

//AJAX END
die();