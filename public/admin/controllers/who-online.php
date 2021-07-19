<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Who\'s Online', $lang['CH643'], true);
$subTitle = trans('Active Users', $lang['CH644'], true);
$onlineNow = 0; $rainbowTrackBalaji = '';
$refreshTime = 20;
$htmlLibs = array('dataTables');
$onlineData = getOnlineUsers($con);
$onlineNow = $onlineData[0];
$activeUsersInfo = $onlineData[1];

$geoData = loadIp2();

$flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'20'.D_S;
$iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
$flagLink = $baseURL.'resources/flags/default/20/';
$iconLink = $baseURL.'resources/icons/';
$screenLink = $iconLink.'screen.png';
$loadingBar = $iconLink.'load.gif';

if(count($activeUsersInfo) !== 0){
    foreach($activeUsersInfo as $data){

        $userCountryCode = ip2Code($geoData, $data['ip']);

        $userCountry = country_code_to_country($userCountryCode);
        $userCountry = ($userCountry == '') ? $lang['RF102'] : $userCountry;

        if(file_exists($flagPath.strtolower(trim($userCountry)).'.png'))
            $coLink = $flagLink.strtolower(trim($userCountry)).'.png';
        else
            $coLink = $flagLink.'unknown.png';

        $uaInfo = parse_user_agent($data['ua']);
        if(file_exists($iconPath.strtolower($uaInfo['platform']).'.png'))
            $osLink = $iconLink.strtolower($uaInfo['platform']).'.png';
        else
            $osLink = $iconLink.'unknown.png';
        if(file_exists($iconPath.strtolower($uaInfo['browser']).'.png'))
            $browserLink = $iconLink.strtolower($uaInfo['browser']).'.png';
        else
            $browserLink = $iconLink.'unknown.png';

        $pageData = '';
        foreach($data['pages'] as $pageV){
            if($pageV[2] === $data['last_visit_raw'])
            $pageData .= '<a target="_blank" href="'.$pageV[0].'">'.$pageV[0].'</a>';
        }

        $data['user_id'] = intval($data['user_id']);
        if($data['user_id'] === 0)
            $username = 'Guest';
        else
            $username = ucfirst(getUserName($con, $data['user_id']));

        if($data['ref'] !== 'Direct')
            $data['ref'] = '<a href="'.$data['ref'].'" target="_blank">'.$data['ref'].'</a>';

        $rainbowTrackBalaji .= '
        <tr>
            <td>'.$data['ip'].'</td>
            <td><img src="'.$coLink.'" alt="'.$userCountryCode.'" /> '.ucfirst($userCountry).'</td>
            <td>'.$username.'</td>
            <td><img data-toggle="tooltip" data-placement="top" title="'.$lang['CH90'].': '.$uaInfo['browser'].' '.$uaInfo['version'].'" src="'.$browserLink.'" alt="'.$uaInfo['browser'].'" /> '.$uaInfo['browser'].' '.$uaInfo['version'].'</td>
            <td>'.$pageData.'</td>
            <td>'.$data['ref'].'</td>
            <td>'.date('F jS Y h:i:s A',$data['last_visit_raw']).'</td>
        </tr>
        
        ';
    }
}else{
    $rainbowTrackBalaji .= '<tr><td class="hide"><td class="hide"><td class="hide"></td><td>'.$lang['CH645'].'</td><td class="hide"><td class="hide"><td class="hide"></tr>';
}
