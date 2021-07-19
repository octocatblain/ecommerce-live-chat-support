<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


$pageTitle = trans('Overview',$lang['CH527'],true);
$subTitle = trans('Analytics Overview', $lang['CH630'], true);

$htmlLibs = array('morris','dataTables','datePicker');

if($pointOut != '')
    $date = trim($pointOut);
else
    $date = date('Y-m-d');

//$date = date('Y-m-d', strtotime('-4 days'));
$countryCodes = $browsersList = $platformList = $refererList = $pageList = $valRes = array();
$totalHit = $totalBrowser = $totalPlatform = 0;
$table1 = $table2 = $table3 = $table4 = $table5 = '';

$geoData = loadIp2();

$flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'20'.D_S;
$iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
$flagLink = $baseURL.'resources/flags/default/20/';
$iconLink = $baseURL.'resources/icons/';
$screenLink = $iconLink.'screen.png';
$loadingBar = $iconLink.'load.gif';

$valRes = getTrackRecordsWithPageViews($date,$con);

$datas = array_reverse($valRes[1]);
foreach($datas as $data){
    $userCountryCode = ip2Code($geoData, $data['ip']);
    $countryCodes[] = $userCountryCode;
    $uaInfo = parse_user_agent($data['ua']);
    $platformList[] = $uaInfo['platform'];
    $browsersList[] = $uaInfo['browser'];
    $refererList[] = $data['ref'];

    foreach($data['pages'] as $pageV){
        if(array_key_exists($pageV[0],$pageList))
            $pageV[1] = $pageList[$pageV[0]] + $pageV[1];
        $pageList[$pageV[0]] = $pageV[1];
    }
}

$countryCodes = array_count_values($countryCodes);
arsort($countryCodes);
$totalHit = array_sum($countryCodes);

$browsersList  = array_count_values($browsersList);
arsort($browsersList);
$totalBrowser = array_sum($browsersList);

$platformList  = array_count_values($platformList);
arsort($platformList);
$totalPlatform = array_sum($platformList);

$refererList  = array_count_values($refererList);
arsort($refererList);
$totalReferer = array_sum($refererList);

arsort($pageList);
$totalPages = array_sum($pageList);

foreach($refererList as $referer=>$ses) {
    if($referer === 'Direct')
        $referer = '<td>('.$lang['CH631'].')</td>';
    else
        $referer = '<td><a title="'.$referer.'" target="_blank" href="'.$referer.'">'.getDomainName($referer).'</a></td>';
    $table5 .= '
    <tr>
    '.$referer.'
    <td>'.$ses.'</td>
    <td>'.round(($ses/$totalReferer)*100,2).'%</td>
    </tr>
    ';
}

foreach($pageList as $page=>$views) {
    $table4 .= '
    <tr>
    <td><a target="_blank" href="'.$page.'">'.$page.'</a></td>
    <td>'.$views.'</td>
    <td>'.round(($views/$totalPages)*100,2).'%</td>
    </tr>
    ';
}

foreach($platformList as $platform=>$hits) {
    if(file_exists($iconPath.strtolower($platform).'.png'))
        $osLink = $iconLink.strtolower($platform).'.png';
    else
        $osLink = $iconLink.'unknown.png';
    $table3 .= '
    <tr>
    <td><img src="'.$osLink.'" alt="'.$platform.'" />  '.ucfirst($platform).'</td>
    <td>'.$hits.'</td>
    <td>'.round(($hits/$totalPlatform)*100,2).'%</td>
    </tr>
    ';
}

foreach($browsersList as $browser=>$hits) {
    if(file_exists($iconPath.strtolower($browser).'.png'))
        $browserLink = $iconLink.strtolower($browser).'.png';
    else
        $browserLink = $iconLink.'unknown.png';
    $table2 .= '
    <tr>
    <td><img src="'.$browserLink.'" alt="'.$browser.'" />  '.ucfirst($browser).'</td>
    <td>'.$hits.'</td>
    <td>'.round(($hits/$totalBrowser)*100,2).'%</td>
    </tr>
    ';
}

foreach($countryCodes as $userCountryCode=>$hits) {
    $userCountry = country_code_to_country($userCountryCode);
    $userCountry = ($userCountry == '') ? $lang['RF102'] : $userCountry;
    if(file_exists($flagPath.strtolower(Trim($userCountry)).'.png'))
        $coLink = $flagLink.strtolower(Trim($userCountry)).'.png';
    else
        $coLink = $flagLink.'unknown.png';
    $table1 .= '
    <tr>
    <td><img src="'.$coLink.'" alt="'.$userCountryCode.'" />  '.ucfirst($userCountry).'</td>
    <td>'.$hits.'</td>
    <td>'.round(($hits/$totalHit)*100,2).'%</td>
    </tr>
    ';
}

$valRes[0]['h00']['unique'] = $valRes[0]['h00']['unique'] + $valRes[0]['h01']['unique'];
$valRes[0]['h00']['views'] = $valRes[0]['h00']['views'] + $valRes[0]['h01']['views'];

$valRes[0]['h02']['unique'] = $valRes[0]['h02']['unique'] + $valRes[0]['h03']['unique'];
$valRes[0]['h02']['views'] = $valRes[0]['h02']['views'] + $valRes[0]['h03']['views'];

$valRes[0]['h04']['unique'] = $valRes[0]['h04']['unique'] + $valRes[0]['h05']['unique'];
$valRes[0]['h04']['views'] = $valRes[0]['h04']['views'] + $valRes[0]['h05']['views'];

$valRes[0]['h06']['unique'] = $valRes[0]['h06']['unique'] + $valRes[0]['h07']['unique'];
$valRes[0]['h06']['views'] = $valRes[0]['h06']['views'] + $valRes[0]['h07']['views'];

$valRes[0]['h08']['unique'] = $valRes[0]['h08']['unique'] + $valRes[0]['h09']['unique'];
$valRes[0]['h08']['views'] = $valRes[0]['h08']['views'] + $valRes[0]['h09']['views'];

$valRes[0]['h10']['unique'] = $valRes[0]['h10']['unique'] + $valRes[0]['h11']['unique'];
$valRes[0]['h10']['views'] = $valRes[0]['h10']['views'] + $valRes[0]['h11']['views'];

$valRes[0]['h12']['unique'] = $valRes[0]['h12']['unique'] + $valRes[0]['h13']['unique'];
$valRes[0]['h12']['views'] = $valRes[0]['h12']['views'] + $valRes[0]['h13']['views'];

$valRes[0]['h14']['unique'] = $valRes[0]['h14']['unique'] + $valRes[0]['h15']['unique'];
$valRes[0]['h14']['views'] = $valRes[0]['h14']['views'] + $valRes[0]['h15']['views'];

$valRes[0]['h16']['unique'] = $valRes[0]['h16']['unique'] + $valRes[0]['h17']['unique'];
$valRes[0]['h16']['views'] = $valRes[0]['h16']['views'] + $valRes[0]['h17']['views'];

$valRes[0]['h18']['unique'] = $valRes[0]['h18']['unique'] + $valRes[0]['h19']['unique'];
$valRes[0]['h18']['views'] = $valRes[0]['h18']['views'] + $valRes[0]['h19']['views'];

$valRes[0]['h20']['unique'] = $valRes[0]['h20']['unique'] + $valRes[0]['h21']['unique'];
$valRes[0]['h20']['views'] = $valRes[0]['h20']['views'] + $valRes[0]['h21']['views'];

$valRes[0]['h22']['unique'] = $valRes[0]['h22']['unique'] + $valRes[0]['h23']['unique'];
$valRes[0]['h22']['views'] = $valRes[0]['h22']['views'] + $valRes[0]['h23']['views'];