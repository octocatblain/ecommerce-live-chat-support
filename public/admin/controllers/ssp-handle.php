<?php
defined('SSP_HANDLE') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

//Handle Data Tables

//Load language data
if(isset($other['lang'])) {
    $lang = $other['lang'];
    unset($other['lang']);
}

if($other['action'] === 'users') {
    $geoData = loadIp2();
    $controller = 'manage-users';
    $dashDateFormat = 'j/M/Y h:i:sA';
    $arr['created_at'] = date($dashDateFormat, strtotime($arr['created_at']));
    $arr['last_active'] = date($dashDateFormat, strtotime($arr['last_active']));
    $result[$loop]['name'] = $arr['name'];
    $result[$loop]['email'] = $arr['email'];
    $result[$loop]['created_at'] = $arr['created_at'];
    $result[$loop]['last_active'] = $arr['last_active'];
    $result[$loop]['country'] = ip2Country($geoData,$arr['created_ip']);
    if( $arr['created_ip'] == '')
        $arr['created_ip'] = '-';
    $result[$loop]['created_ip'] = '<span class="label label-default">'.$arr['created_ip'].'</span>';
    $myid = $arr['id'];
    $result[$loop]['actions'] = '
                <a class="btn btn-danger btn-xs" onclick="return confirm(\''.$lang['CH234'].'\');" title="'.$lang['CH232'].'" href=' . adminLink($controller . '/delete/' . $myid, true) . '> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].' </a>';

}elseif($other['action'] === 'admin'){

    $controller = 'admin-accs';
    $result[$loop]['checkbox'] = $arr['id'];
    $result[$loop]['_id'] = $arr['id'];
    $result[$loop]['name'] = $arr['name'];
    $result[$loop]['user'] = $arr['user'];
    $result[$loop]['reg_date'] = $arr['reg_date'];
    $data = mysqliPreparedQuery($other['con'], 'SELECT name,data FROM ' . DB_PREFIX . 'admin_roles WHERE id=?', 'i', array($arr['role']));
    if ($data !== false) {
        $result[$loop]['role'] = $data['name'];
        $data = dbStrToArr($data['data']);
        if($data[0] == 'all')
            $result[$loop]['access'] = '<span class="label label-success">'.$lang['CH370'].'</span>';
        else
            $result[$loop]['access'] = '<span class="label label-danger">'.$lang['CH369'].'</span>';
    }else {
        $result[$loop]['role'] = '<span class="label label-warning">'.$lang['CH75'].'</span>';
        $result[$loop]['access'] =  '<span class="label label-warning">'.$lang['CH75'].'</span>';
    }
    $disabled = ''; if(intval($arr['id']) === 1){
        $disabled = 'disabled';
        $result[$loop]['access'] .= '&nbsp; <span class="label label-default">('.$lang['CH346'].')</span>';
    }

    $editLink = adminLink($controller.'/edit/'.$arr['id'],true);
    $result[$loop]['actions'] = "<a class='btn btn-info btn-xs ".$disabled."' href='".$editLink."'> <i class='fa fa-edit'></i> &nbsp; ".$lang['CH309']." </a> ".
        "<a onclick='deleteItem(\"".adminLink($controller.'/delete/'.$arr['id'],true)."\",\"myid_".$arr['id']."\")' class='btn btn-danger btn-xs ".$disabled."'> <i class=\"fa fa-trash-o\"></i> &nbsp; ".$lang['CH232']." </a> ";

}elseif($other['action'] === 'history'){

    $controller = 'chat-history';

    $date = date_create($arr['date']);
    $arr['rate'] = intval($arr['rate']);
    $arr['status'] = intval($arr['status']);
    $result[$loop]['checkbox'] = $arr['id'];
    $result[$loop]['_id'] = $arr['id'];
    $result[$loop]['_disabled'] = $disabled = 'disabled';

    if(isSelected($arr['admin'])){
        $userDetails = getAdminDetails($other['con'], $arr['user_id']);
        $userDetails['name'] = '<span class="label label-primary">'.$userDetails['name'].'</span>'. ' <i data-toggle="tooltip" data-placement="top" title="'.$lang['CH754'].'" class="fa fa-exclamation-circle"></i>';
    }else{
       $userDetails = getUserDetails($other['con'], $arr['user_id']);
    }

    $result[$loop]['name'] = $userDetails['name'];
    $result[$loop]['email'] = $userDetails['email'];
    $result[$loop]['date'] = date_format($date,"dS M Y");

    if($arr['rate'] === 0){
        $result[$loop]['rate'] = '<span style="color:#34495e;"><i class="fa fa-star-o" aria-hidden="true"></i> '.$lang['CH277'].'</span>';
    }elseif($arr['rate'] === 1){
        $result[$loop]['rate'] = '<span style="color:#e74c3c;"><i class="fa fa-thumbs-down" aria-hidden="true"></i> '.$lang['CH44'].'</span>';
    }elseif($arr['rate'] === 3) {
        $result[$loop]['rate'] = '<span style="color:#e67e22;"><i class="fa fa-check-circle " aria-hidden="true"></i> '.$lang['CH45'].'</span>';
    }elseif($arr['rate'] === 5){
        $result[$loop]['rate'] = '<span style="color:#2ecc71;"><i class="fa fa-thumbs-up" aria-hidden="true"></i> '.$lang['CH276'].'</span>';
    }

    if($arr['status'] === 0) {
        $result[$loop]['status'] = '<span class="label label-default">'.$lang['CH133'].'</span>';
        $disabled = '';
        $result[$loop]['_disabled'] = '';
    }elseif($arr['status'] === 1)
        $result[$loop]['status'] = '<span class="label label-warning">'.$lang['CH134'].'</span>';
    elseif($arr['status'] === 2)
        $result[$loop]['status'] = '<span class="label label-success">'.$lang['CH135'].'</span>';

    $result[$loop]['department'] = getDepartmentName($other['con'], $arr['dept']);

    $editLink = adminLink($controller.'/view/'.$arr['id'],true);
    $result[$loop]['actions'] = "<a class='btn btn-info btn-xs' href='".$editLink."'> <i class='fa fa-external-link-square'></i> &nbsp; ".$lang['CH755']." </a> ".
        "<a onclick='deleteItem(\"".adminLink($controller.'/delete/'.$arr['id'],true)."\",\"myid_".$arr['id']."\")' class='btn btn-danger btn-xs ".$disabled."'> <i class=\"fa fa-trash-o\"></i> &nbsp; ".$lang['CH232']." </a> ";

}elseif($other['action'] === 'canned'){

    $controller = 'canned-messages';

    $arr['status'] = intval($arr['status']);
    $result[$loop]['checkbox'] = $arr['id'];
    $result[$loop]['_id'] = $arr['id'];
    $result[$loop]['_disabled'] = '';

    $result[$loop]['code'] = $arr['code'];
    $result[$loop]['data'] = truncate($arr['data'], 20, 600);
    $result[$loop]['date'] = $arr['date'];

    $result[$loop]['admin'] = '<span class="label label-default">'.getAdminName($other['con'], $arr['admin']).'</span>';

    if($arr['status'] === 0)
        $result[$loop]['status'] = '<span class="label label-danger">'.$lang['CH290'].'</span>';
    else
        $result[$loop]['status'] = '<span class="label label-success">'.$lang['CH291'].'</span>';

    $editLink = adminLink($controller.'/edit/'.$arr['id'],true);
    $result[$loop]['actions'] = "<a class='btn btn-info btn-xs' href='".$editLink."'> <i class='fa fa-edit'></i> &nbsp; ".$lang['CH309']." </a> ".
        "<a onclick='deleteItem(\"".adminLink($controller.'/delete/'.$arr['id'],true)."\",\"myid_".$arr['id']."\")' class='btn btn-danger btn-xs'> <i class=\"fa fa-trash-o\"></i> &nbsp; ".$lang['CH232']." </a> ";

}elseif($other['action'] === 'departments') {

    $controller = 'departments';

    $arr['status'] = intval($arr['status']);
    $result[$loop]['checkbox'] = $arr['id'];
    $result[$loop]['_id'] = $arr['id'];
    $result[$loop]['_disabled'] = '';
    $result[$loop]['name'] = $arr['name'];
    $result[$loop]['des'] = truncate($arr['des'], 20, 600);

    if(intval($arr['id']) === 1)
        $result[$loop]['_disabled'] = 'disabled';

    $arr['data'] = json_decode($arr['data']);

    if ($arr['data'][0] === 'all') $result[$loop]['data'] = '<span class="label label-default">'.strtoupper($lang['CH370']).'</span>'; else {
        $result[$loop]['data'] = '';
        foreach ($arr['data'] as $adminID)
            $result[$loop]['data'] .= '<span class="label label-default">' . getAdminName($other['con'], $adminID) . '</span> &nbsp; ';
    }

    if ($arr['status'] === 0)
        $result[$loop]['status'] = '<span class="label label-danger">'.$lang['CH290'].'</span>';
    else
        $result[$loop]['status'] = '<span class="label label-success">'.$lang['CH291'].'</span>';

    $editLink = adminLink($controller . '/edit/' . $arr['id'], true);
    $result[$loop]['actions'] = "<a class='btn btn-info btn-xs' href='" . $editLink . "'> <i class='fa fa-edit'></i> &nbsp; ".$lang['CH309']." </a> " . "<a onclick='deleteItem(\"" . adminLink($controller . '/delete/' . $arr['id'], true) . "\",\"myid_" . $arr['id'] . "\")' class='btn btn-danger btn-xs ".$result[$loop]['_disabled']."'> <i class=\"fa fa-trash-o\"></i> &nbsp; ".$lang['CH232']." </a> ";

}