<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));



$pageTitle = $subTitle = trans('Dashboard',$lang['RF103'],true);
$todayArr = $pageViewHistory = $pageViewDate = array(); $userHistoryData = '';
$today_page = $today_users_count = $today_visit = $onlineNow = 0; $updater = true;
$newUsersData = $newsLink = $jsonData = $latestData = $pageViewData = $adminHistoryData = '';
$dashDateFormat = 'j/M/Y h:i:sA';
$htmlLibs = array('morris');

//Load GEO Library
$geoData = loadIp2();

//Icons
$flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'24'.D_S;
$iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
$flagLink = $baseURL.'resources/flags/default/24/';
$iconLink = $baseURL.'resources/icons/';

//Database Size
$query = "SELECT table_schema '".DB_PREFIX."$dbName', SUM(data_length + index_length) / 1024 / 1024 'db_size_in_mb' FROM information_schema.TABLES WHERE table_schema='".DB_PREFIX."$dbName' GROUP BY table_schema";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
$database_size = round(floatval($row['db_size_in_mb']), 1);

//Disk Size
$ds = disk_total_space("/");
$df = disk_free_space("/");

//Online Now
$onlineData = getOnlineUsers($con);
$onlineNow = $onlineData[0];
$onlineNow = ($onlineNow == '') ? 0 : $onlineNow;

//Today Page / Unique View
$todayArr = getTodayViews($con);
$today_page = $todayArr['views'];
$today_visit = $todayArr['unique'];


//Today Chats
$result = mysqli_query($con, "SELECT id FROM ".DB_PREFIX."chat where DATE(nowtime)='".date('Y-m-d')."'");
$today_users_count = mysqli_num_rows($result);

//Admin History
$result = mysqli_query($con, 'SELECT admin_id,logged_time,ip,browser FROM '.DB_PREFIX.'admin_history ORDER BY id DESC LIMIT 7');
while ($row = mysqli_fetch_assoc($result)){
    $adminCountryCode = $adminBrowser = $adminCountry = $version = '';

    $adminCountryCode = ip2Code($geoData, $row['ip']);
    $adminCountry = country_code_to_country($adminCountryCode);
    $adminCountry = (!empty($adminCountry)) ? $adminCountry : 'Unknown';
    $adminBrowser = parse_user_agent($row['browser']);
    extract($adminBrowser);
    $adminBrowser = (!empty($browser)) ? $browser : 'Unknown';
    
    if(file_exists($flagPath.strtolower(Trim($adminCountry)).'.png'))
        $coLink = $flagLink.strtolower(Trim($adminCountry)).'.png';
    else
        $coLink = $flagLink.'unknown.png';

    if(file_exists($iconPath.strtolower($browser).'.png'))
        $browserLink = $iconLink.strtolower($browser).'.png';
    else
        $browserLink = $iconLink.'unknown.png';
    $row['logged_time'] = date($dashDateFormat, strtotime($row['logged_time']));
    $adminHistoryData .= '<tr>
        <td>'.getAdminName($con, $row['admin_id']).'</td>
        <td>'.$row['logged_time'].'</td>
        <td><span class=\'badge bg-'.rndColor().'\'>'.$row['ip'].'</span></td>
        <td><img src="'.$coLink.'" alt="'.$adminCountryCode.'" /> '.ucfirst($adminCountry).'</td>
        <td><img data-toggle="tooltip" data-placement="top" title="Browser: '.$browser.' '.$version.'" src="'.$browserLink.'" alt="'.$browser.'" /> '.$browser.'</td>
    </tr>';
}

//Pageview History
$pageViewHistory = array_reverse(getTrackViews($con,7));
foreach($pageViewHistory as $dbDate => $dbArr){
    $dbDate = date('jS M', strtotime($dbDate));
    $pageViewData.= '{y: \''.$dbDate.'\', item1: '.$dbArr['unique'].', item2: '.$dbArr['views'].'},'.PHP_EOL;
    $pageViewDate[] = $dbDate;
}
$pageViewDate = array_reverse($pageViewDate);
$dateStr = makeJavascriptArray($pageViewDate).'[CountX]';

//Update Check & News Panel
$newsLink = 'http://api.prothemes.biz/pinky/latest_news.php';
if(isset($item_purchase_code))
    $jsonData = simpleCurlGET($newsLink.'?link='.createLink('',true).'&code='.$item_purchase_code);
else
    die();
$latestData = json_decode($jsonData,true);

if($latestData['version'] == VER_NO)
    $updater = false;

//User History
$result = mysqli_query($con, 'SELECT name,created_ip,created_at FROM '.DB_PREFIX.'users ORDER BY id DESC LIMIT 7');
while ($row = mysqli_fetch_assoc($result)){
    $userCountry =  $userCountryCode = '';  

    $userCountryCode = ip2Code($geoData, $row['created_ip']);
    $userCountry = country_code_to_country($userCountryCode);
    $userCountry = (!empty($userCountry)) ? $userCountry : 'Unknown';

    if(file_exists($flagPath.strtolower(Trim($userCountry)).'.png'))
        $coLink = $flagLink.strtolower(Trim($userCountry)).'.png';
    else
        $coLink = $flagLink.'unknown.png';
    
    $row['created_at'] = date($dashDateFormat, strtotime($row['created_at']));
    $newUsersData .= '<tr>
        <td>'.$row['name'].'</td>
        <td>'.$row['created_at'].'</td>
        <td><img src="'.$coLink.'" alt="'.$userCountryCode.'" /> '.ucfirst($userCountry).'</td>
    </tr>';
}

//Recent Chats
$result = mysqli_query($con, 'SELECT date,user_id,dept,status FROM '.DB_PREFIX.'chat ORDER BY id DESC LIMIT 7');
while ($row = mysqli_fetch_assoc($result)){
    $row['status'] = intval($row['status']);
    if($row['status'] === 0)
        $row['status'] = '<span class="label label-default">'.$lang['CH133'].'</span>';
    elseif($row['status'] === 1)
        $row['status'] = '<span class="label label-warning">'.$lang['CH134'].'</span>';
    elseif($row['status'] === 2)
        $row['status'] = '<span class="label label-success">'.$lang['CH135'].'</span>';

    $row['date'] = date($dashDateFormat, strtotime($row['date']));
    $userHistoryData .= '<tr>
        <td>'.getUserName($con,$row['user_id']).'</td>
        <td>'.$row['date'].'</td>
        <td>'.getDepartmentName($con,$row['dept']).'</td>
        <td>'.$row['status'].'</td>
    </tr>'; 
}