<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Chat History', $lang['CH274'], true);
$subTitle = trans('Chat List', $lang['CH275'], true);
$htmlLibs = array('dataTables');

if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

if($pointOut == 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        $sql = 'DELETE FROM ' . DB_PREFIX . 'chat WHERE id=' . $delID;
        if (mysqli_query($con, $sql))
            die('1');
    }
    die('0');
}

if($pointOut === 'groupdel'){

    $myValues = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    $delValues = implode(', ', $myValues['id']);

    $sql = 'DELETE FROM ' . DB_PREFIX . 'chat WHERE id IN ('.$delValues.')';
    if (mysqli_query($con, $sql))
        die('1');
    die('0');
}

if($pointOut === 'view'){
    if(isset($args[0]) && $args[0] !== '') {
        $viewID = intval($args[0]);
        $chatDetails = chatDetails($con, $viewID);
        $htmlData = loadChatDataHTML($con, $viewID);
        $htmlData['startDate'] = date_create($htmlData['startDate']);
        $htmlData['endDate'] = date_create($htmlData['endDate']);
        $htmlData['startDate'] = date_format($htmlData['startDate'], 'dS M Y h:i:s A');
        $htmlData['endDate'] = date_format($htmlData['endDate'], 'dS M Y h:i:s A');
        $owner = 'None';
        $operators = false;
        $operatorsData = array();
        $userPathData = '';

        if($chatDetails['staff_id'] != -1)
            $owner = getAdminName($con, $chatDetails['staff_id']);

        if(isSelected($chatDetails['admin']))
            $adminDetails = processAdminDetails($con, $chatDetails['user_id'], $viewID, $baseURL);
        else
            $userDetails = processUserDetails($con, $viewID, $baseURL);

        if($chatDetails['analytics'] != '0'){
            $trackQuery = mysqli_query($con, "SELECT pages,user_path FROM " . DB_PREFIX . "rainbow_analytics WHERE id=".$chatDetails['analytics']);
            $onlineUsersCount = mysqli_num_rows($trackQuery);
            if ($onlineUsersCount > 0) {
                $pathDetail = mysqli_fetch_assoc($trackQuery);
                $userPathData = '<table class="table">';
                $pageCount = 0;
                $pathDetail['pages'] = dbStrToArr($pathDetail['pages']);
                $pathDetail['user_path'] = array_reverse(dbStrToArr($pathDetail['user_path']));
                foreach($pathDetail['user_path'] as $k){
                    $pageCount++;
                    if(isset($pathDetail['pages'][$k])){
                        $pTitle = $pathDetail['pages'][$k][0];
                        if(isset($pathDetail['pages'][$k][3]))
                            $pTitle = $pathDetail['pages'][$k][3];
                        $pageTime = $pathDetail['pages'][$k][2];

                        $userPathData .= '<tr><td class="vPath">  <i class="fa fa-external-link-square" aria-hidden="true"></i> &nbsp; <a target="_blank" title="'.$pathDetail['pages'][$k][0].'" href="'.$pathDetail['pages'][$k][0].'">'.linkTruncate($pTitle, 30).'</a></td><td>'.formatTimetoAgo($pageTime).'</td></tr>';
                    }else{
                        break;
                    }
                    if($pageCount === 5)
                        break;
                }
                $userPathData .= '</table>';
            }
        }

        if($chatDetails['rate'] === 0)
            $chatDetails['rate'] = '<span style="color:#34495e;"><i class="fa fa-star-o" aria-hidden="true"></i> '.$lang['CH277'].'</span>';
        elseif($chatDetails['rate'] === 1)
            $chatDetails['rate'] = '<span style="color:#e74c3c;"><i class="fa fa-thumbs-down" aria-hidden="true"></i> '.$lang['CH44'].'</span>';
        elseif($chatDetails['rate'] === 3)
            $chatDetails['rate'] = '<span style="color:#e67e22;"><i class="fa fa-check-circle " aria-hidden="true"></i> '.$lang['CH45'].'</span>';
        elseif($chatDetails['rate'] === 5)
            $chatDetails['rate'] = '<span style="color:#2ecc71;"><i class="fa fa-thumbs-up" aria-hidden="true"></i> '.$lang['CH276'].'</span>';

        if($chatDetails['status'] === 0) {
            $chatDetails['status'] = '<span class="label label-default">'.$lang['CH133'].'</span>';
        }elseif($chatDetails['status'] === 1)
            $chatDetails['status'] = '<span class="label label-warning">'.$lang['CH134'].'</span>';
        elseif($chatDetails['status'] === 2)
            $chatDetails['status'] = '<span class="label label-success">'.$lang['CH135'].'</span>';

        foreach($chatDetails['operators'] as $val){
            if($val == $chatDetails['staff_id'])
                continue;
           $operators = true;
           $operatorsData[] = getAdminName($con, $val);
        }

        $subTitle = '<span class="sepCH">'.$lang['CH88'].': '. $viewID . ' </span>';
        $subTitle .= '<span class="sepCH">'.$lang['CH278'].': '. $owner . ' </span>';
        $subTitle .= '<span class="sepCH">'.$lang['CH271'].': '. $chatDetails['rate'] . ' </span>';
        $subTitle .= '<span class="sepCH">'.$lang['CH146'].': '. $chatDetails['status'] . ' </span>';
        if($operators)
            $subTitle .= '<span class="sepCH">'.$lang['CH121'].': '. implode(',',$operatorsData) . ' </span>';
    }else{
        redirectTo(adminLink($controller,true));
    }
}