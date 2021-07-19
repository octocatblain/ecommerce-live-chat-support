<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));
if(!(ANALYTICS)) die();

/*
 * @author Balaji
 * @name: Rainbow Analytics v2.0
 * @copyright 2019 ProThemes.Biz
 *
 */

$storeTitles = true;
$date = date("Y-m-d");
$created = date("Y-m-d H:i:s");
$ses_id = session_id();
$nowTime = time();
$ua = escapeTrim($con, $_SERVER['HTTP_USER_AGENT']);
$isBot = isBot($ua);
$pageData = array();
$userID = $newPath = 0;
$pageCheck = false;
$ref = $screen = $page = '';
$username = 'Guest';

//Check bot traffic
if(!(ANALYTICS_BOT)){
    if(isSelected($isBot))
        die();
}

if(isset($_POST['page']) && trim($_POST['page']) !== NULL)
   $page = escapeTrim($con, $_POST['page']);
else
    die('No Active Link');

if(isset($_POST['ref']) && trim($_POST['ref']) !== NULL)
    $ref = escapeTrim($con, $_POST['ref']);

if(isset($_POST['pageTitle']) && trim($_POST['pageTitle']) !== NULL)
    $pageTitle = escapeTrim($con, $_POST['pageTitle']);

if(isset($_POST['screen']) && trim($_POST['screen']) !== NULL)
    $screen = escapeTrim($con, $_POST['screen']);

if(issetSession('userName')){
    $userData = getSession(array('userName','userID'));
    $username = $userData['userName'];
    $userID = $userData['userID'];
}

//Analytics
$trackQuery =  mysqli_query($con, "SELECT id,created,ip,user_id,ses_id,pageviews,pages,ref,last_visit,last_visit_raw,ua,screen,keywords,user_path,is_bot FROM ".DB_PREFIX."rainbow_analytics where date='$date' AND ip='$ip' AND ses_id='$ses_id'");
if(mysqli_num_rows($trackQuery) > 0) {

    //Already session exist
    $row = mysqli_fetch_array($trackQuery);
    $update = array();

    $row['pages'] = dbStrToArr($row['pages']);
    $row['user_path'] = dbStrToArr($row['user_path']);
    $pageCheck = isPageExistOnArray($page,$row['pages']);

    //Update Pages
    if($pageCheck[0]){
        $row['pages'][$pageCheck[1]][1] = $row['pages'][$pageCheck[1]][1] + 1;
        $row['pages'][$pageCheck[1]][2] = $nowTime;
        if($storeTitles)
            $row['pages'][$pageCheck[1]][3] = $pageTitle;
        $newPath = $pageCheck[1];
        $update['pages'] = arrToDbStr($con, $row['pages']);
    }else{
        $newPath = count($row['pages']);
        if($storeTitles)
            $row['pages'][] = array($page, 1 ,$nowTime, $pageTitle);
        else
            $row['pages'][] = array($page, 1 ,$nowTime);
        $update['pages'] = arrToDbStr($con, $row['pages']);
    }

    //Update User Path
    $row['user_path'][] = $newPath;
    $update['user_path'] = arrToDbStr($con, $row['user_path']);

    //Update Last Visit
    $update['last_visit'] = $created;
    $update['last_visit_raw'] = $nowTime;

    //Update Page View
    $update['pageviews'] = intval($row['pageviews'])+1;

    //Update Username (If Applicable!)
    if($row['user_id'] != $userID)
        $update['user_id'] = $userID;

    setSession('analyticsID', $row['id']);

    if(updateToDbPrepared($con, 'rainbow_analytics', $update , array('id' => $row['id'])))
        die('Something Went Wrong!');

}else{
    //Insert New Date & Create New Session
    $insert = array();
    if($storeTitles)
        $pageData[] = array($page, 1, $nowTime, $pageTitle);
    else
        $pageData[] = array($page, 1, $nowTime);
    $userPath = array(0);
    $keywords = array(
                'google' => '',
                'yahoo' => '',
                'bing' => '',
                'ask' => ''
        );
    $insert['date'] = $date;
    $insert['created'] = $created;
    $insert['ip'] = $ip;
    $insert['user_id'] = $userID;
    $insert['ses_id'] = $ses_id;
    $insert['pageviews'] = 1;
    $insert['pages'] = arrToDbStr($con,$pageData);
    $insert['ref'] = $ref;
    $insert['last_visit'] = $created;
    $insert['last_visit_raw'] = $nowTime;
    $insert['ua'] = $ua;
    $insert['screen'] = $screen;
    $insert['keywords'] = arrToDbStr($con,$keywords);
    $insert['user_path'] = arrToDbStr($con,$userPath);
    $insert['is_bot'] = $isBot;

    if(issetSession('chatID'))
        $insert['chatID'] = getSession('chatID');
    else
        $insert['chatID'] = 0;

    if(insertToDbPrepared($con, 'rainbow_analytics', $insert))
            die('Something Went Wrong!');
    else{
        $inserID = mysqli_insert_id($con);
        setSession('analyticsID', $inserID);
    }

}
die();