<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = $subTitle = trans('Visitor Log', $lang['CH649'], true);
$rainbowTrackBalaji = '';

$htmlLibs = array('dataTables','dateRangePicker');
$geoData = loadIp2();
$flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'24'.D_S;
$iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
$flagLink = $baseURL.'resources/flags/default/24/';
$iconLink = $baseURL.'resources/icons/';
$screenLink = $iconLink.'screen.png';
$loadingBar = $iconLink.'load.gif';

$date = date('Y-m-d');
$datas = array_reverse(getTrackRecords($date,$con));

foreach($datas as $data){
    $userCountryCode = ip2Code($geoData, $data['ip']);
    $userCountry = country_code_to_country($userCountryCode);
    $userCountry = ($userCountry == '') ? $lang['CH75'] : $userCountry;

    if(file_exists($flagPath.strtolower(Trim($userCountry)).'.png'))
        $coLink = $flagLink.strtolower(Trim($userCountry)).'.png';
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
        $pageData .= '<div class="pagesWell"><a target="_blank" href="'.$pageV[0].'">'.$pageV[0].'</a><br>
        '.$lang['CH650'].': '.$pageV[1].' <br>
        '.$lang['CH651'].': '.date('h:i:s A',$pageV[2]).'</div>
        ';
    }

    if($data['ref'] !== 'Direct')
        $data['ref'] = '<a href="'.$data['ref'].'" target="_blank">'.getDomainName($data['ref']).'</a>';

    $data['user_id]'] = intval($data['user_id]']);
    if($data['user_id]'] === 0)
        $username = 'Guest';
    else
        $username = ucfirst(getUserName($con, $data['user_id']));

    $rainbowTrackBalaji .= '
    <tr>
        <td>
        <img src="'.$coLink.'" alt="'.$userCountryCode.'" />  <strong class="b16">'.ucfirst($userCountry).'</strong><br><br>
        <strong>'.date('F jS Y h:i:s A',strtotime($data['created'])).'</strong> <br>
        '.$lang['RF66'].': '.$username.'<br>
        '.$lang['CH652'].': '.$data['pageviews'].'<br>
        '.$lang['CH92'].': <span class="badge" style="background-color: '.rndFlatColor().' !important;">'.$data['ip'].'</span><br><br>
        '.$lang['CH653'].': '.$data['ref'].'<br>
        </td>
        <td><img data-toggle="tooltip" data-placement="top" title="'.$lang['CH640'].': '.$uaInfo['platform'].'" src="'.$osLink.'" alt="'.$uaInfo['platform'].'" />
        <img data-toggle="tooltip" data-placement="top" title="'.$lang['CH90'].': '.$uaInfo['browser'].' '.$uaInfo['version'].'" src="'.$browserLink.'" alt="'.$uaInfo['browser'].'" />
        <img data-toggle="tooltip" data-placement="top" title="'.$lang['CH654'].': '.$data['screen'].'" src="'.$screenLink.'" />
        </td>
        <td>'.$pageData.'</td>
    </tr>
    
    ';
}