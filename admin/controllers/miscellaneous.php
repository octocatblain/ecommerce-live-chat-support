<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Miscellaneous', $lang['CH503'], true);
$subTitle = trans('Miscellaneous Task', $lang['CH504'], true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(isset($_POST['action'])){
    
    $action = raino_trim($_POST['action']);
    
    //Clean up all temporary directories
    if($action === 'temp'){
        $folders = array(APP_DIR.'temp', ROOT_DIR.'uploads'.D_S.'temp'.D_S);

        foreach($folders as $delDir){
            $files = array_diff(scandir($delDir), array('.', '..','.htaccess','index.php'));
            foreach ($files as $file){
                (is_dir("$delDir/$file")) ? delDir("$delDir/$file") : unlink("$delDir/$file");
            }
        }
        
        $msg = successMsgAdmin($lang['CH505']);
    }

    //Clear all analytics data
    if($action === 'analytics'){
        mysqli_query($con,'DELETE FROM '.DB_PREFIX.'rainbow_analytics');
        if (mysqli_errno($con))
            $msg = errorMsgAdmin(mysqli_error($con));
        else
            $msg = successMsgAdmin($lang['CH506']);
    }
    
    //Clear all admin login history data
    if($action === 'admin'){
        mysqli_query($con,'DELETE FROM '.DB_PREFIX.'admin_history');
        if (mysqli_errno($con))
            $msg = errorMsgAdmin(mysqli_error($con));
        else
            $msg = successMsgAdmin($lang['CH507']);
    }

    //Clear all users accounts
    if($action === 'users'){
        mysqli_query($con,'DELETE FROM '.DB_PREFIX.'users');
        if (mysqli_errno($con))
            $msg = errorMsgAdmin(mysqli_error($con));
        else
            $msg = successMsgAdmin($lang['CH508']);
    }

    //Clear all chats
    if($action === 'chats'){
        mysqli_query($con,'DELETE FROM '.DB_PREFIX.'chat');
        if (mysqli_errno($con))
            $msg = errorMsgAdmin(mysqli_error($con));
        else{
            mysqli_query($con,'DELETE FROM '.DB_PREFIX.'chat_history');
            if (mysqli_errno($con))
                $msg = errorMsgAdmin(mysqli_error($con));
            else
                $msg = successMsgAdmin($lang['CH509']);
        }
    }

    //Clear all chats older than 2 months
    if($action === 'chats2'){
        $trackQuery = mysqli_query($con, "SELECT id FROM " . DB_PREFIX . "chat WHERE nowtime < NOW() - INTERVAL 60 DAY");
        while($row = mysqli_fetch_assoc($trackQuery)) {
            deleteRowID($con, 'chat', $row['id']);
            deleteRow($con, 'chat_history', ' chat_id='.$row['id']);
        }
        $msg = successMsgAdmin($lang['CH510']);
    }

    //Clear all chats older than 6 months
    if($action === 'chats6'){
        $trackQuery = mysqli_query($con, "SELECT id FROM " . DB_PREFIX . "chat WHERE nowtime < NOW() - INTERVAL 180 DAY");
        while($row = mysqli_fetch_assoc($trackQuery)) {
            deleteRowID($con, 'chat', $row['id']);
            deleteRow($con, 'chat_history', ' chat_id='.$row['id']);
        }
        $msg = successMsgAdmin($lang['CH511']);
    }

    //Clear all chats older than 1 year
    if($action === 'chats1y'){
        $trackQuery = mysqli_query($con, "SELECT id FROM " . DB_PREFIX . "chat WHERE nowtime < NOW() - INTERVAL 365 DAY");
        while($row = mysqli_fetch_assoc($trackQuery)) {
            deleteRowID($con, 'chat', $row['id']);
            deleteRow($con, 'chat_history', ' chat_id='.$row['id']);
        }
        $msg = successMsgAdmin($lang['CH512']);
    }

    }
}