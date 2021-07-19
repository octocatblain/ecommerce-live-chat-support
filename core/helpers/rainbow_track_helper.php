<?php

/*
 * @author Balaji
 * @name: Rainbow Analytics v2.0
 * @copyright 2019 ProThemes.Biz
 *
 */

function isBot($ua){
    return preg_match('/bot|crawl|slurp|spider|mediapartners/i', $ua);
}

function isPageExistOnArray($elem, $array) {
    foreach($array as $key => $pages){
        if($pages[0] == Trim($elem))
            return array(true,$key);
    }
    return array(false);
}

function getTrackRecords($date,$con){
    $finalRecord = array();
    $result = mysqli_query($con, "SELECT created,ip,user_id,ses_id,pageviews,pages,ref,last_visit,last_visit_raw,ua,screen,keywords,user_path,is_bot,chatID FROM " . DB_PREFIX . "rainbow_analytics WHERE date='$date' ORDER BY id DESC");
    while($row = mysqli_fetch_assoc($result)) {
        $row['pages'] = dbStrToArr($row['pages']);
        $finalRecord[] = $row;
    }
    return $finalRecord;
}

function getTrackRecordsRange($startDate,$endDate,$con){
    $finalRecord = array();
    $result = mysqli_query($con, "SELECT created,ip,user_id,ses_id,pageviews,pages,ref,last_visit,last_visit_raw,ua,screen,keywords,user_path,is_bot,chatID FROM " . DB_PREFIX . "rainbow_analytics WHERE date between '$startDate' and '$endDate' ORDER BY id DESC");
    while($row = mysqli_fetch_assoc($result)) {
        $row['pages'] = dbStrToArr($row['pages']);
        $finalRecord[] = $row;
    }
    return $finalRecord;
}

function getTrackRecordsWithPageViews($date,$con){
    $hours = array('h00' => array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h01'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h02'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h03'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h04'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h05'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h06'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h07'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h08'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h09'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h10'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h11'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h12'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h13'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h14'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h15'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h16'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h17'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h18'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h19'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h20'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h21'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h22'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'h23'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()),
        'total'=> array('views' => 0, 'unique' => 0, 'ses' => 0, 'ips'=> array()));

    $finalRecord = $viewRecord = array();
    $result = mysqli_query($con, "SELECT created,ip,user_id,ses_id,pageviews,pages,ref,last_visit,last_visit_raw,ua,screen,keywords,user_path,is_bot,chatID FROM " . DB_PREFIX . "rainbow_analytics WHERE date='$date' ORDER BY id DESC");
    while($row = mysqli_fetch_assoc($result)){

        $activeHour = date('H', strtotime($row['last_visit_raw']));

        $hours['h'.$activeHour]['views'] = $hours['h'.$activeHour]['views'] + intval($row['pageviews']);
        $hours['h'.$activeHour]['ses'] = $hours['h'.$activeHour]['ses'] + 1;

        $hours['total']['views'] = $hours['total']['views'] + intval($row['pageviews']);
        $hours['total']['ses'] = $hours['total']['ses'] + 1;

        if(!in_array($row['ip'], $hours['total']['ips'])){
            $hours['total']['unique'] = $hours['total']['unique'] + 1;
            $hours['total']['ips'][] = $row['ip'];
        }

        if(!in_array($row['ip'], $hours['h'.$activeHour]['ips'])){
            $hours['h'.$activeHour]['unique'] = $hours['h'.$activeHour]['unique'] + 1;
            $hours['h'.$activeHour]['ips'][] = $row['ip'];
        }

        $row['pages'] = dbStrToArr($row['pages']);
        $finalRecord[] = $row;
    }

    return array($hours, $finalRecord);
}

function getTrackViews($con,$reportLimit = 9){
    $viewRecord = array();
    for($i=0; $i<=$reportLimit; $i++){
        if($i === 0)
            $date = date('Y-m-d');
        else
            $date = date('Y-m-d',strtotime("-$i days"));

        $temp = array();
        $pageView = $uniqueView = $sesCount = 0;

        $result = mysqli_query($con, "SELECT ip,pageviews FROM " . DB_PREFIX . "rainbow_analytics WHERE date='$date'");
        while($row = mysqli_fetch_assoc($result)){
            $sesCount++;
            $pageView = intval($row['pageviews']) + $pageView;
            if(!in_array($row['ip'],$temp)) {
                $temp[] = $row['ip'];
                $uniqueView++;
            }
        }
        $viewRecord[$date] = array('views' => $pageView, 'unique' => $uniqueView, 'ses' => $sesCount);
    }
    return $viewRecord;
}

function getTodayViews($con){
    $date = date('Y-m-d');
    $temp = array();
    $pageView = $uniqueView = $sesCount = 0;

    $result = mysqli_query($con, "SELECT ip,pageviews FROM " . DB_PREFIX . "rainbow_analytics WHERE date='$date'");
    while($row = mysqli_fetch_assoc($result)){
        $sesCount++;
        $pageView = intval($row['pageviews']) + $pageView;
        if(!in_array($row['ip'],$temp)) {
            $temp[] = $row['ip'];
            $uniqueView++;
        }
    }
    return array('views' => $pageView, 'unique' => $uniqueView, 'ses' => $sesCount);
}

function getOnlineUsers($con,$time='30'){
    $onlineUsersCount = 0;
    $onlineUsers = array();
    $trackQuery = mysqli_query($con, "SELECT id,date,created,ip,user_id,ses_id,pageviews,pages,ref,last_visit,last_visit_raw,ua,screen,keywords,user_path,is_bot,chatID FROM " . DB_PREFIX . "rainbow_analytics WHERE last_visit > NOW() - INTERVAL $time MINUTE");
    $onlineUsersCount = mysqli_num_rows($trackQuery);
    if ($onlineUsersCount > 0) {
        while ($row = mysqli_fetch_assoc($trackQuery)) {
            $row['pages'] = dbStrToArr($row['pages']);
            $onlineUsers[] = $row;
        }
    }
    return array($onlineUsersCount, $onlineUsers);
}