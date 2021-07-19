<?php
/*
* @author Balaji
* @name Rainbow PHP Framework
* @copyright 2019 ProThemes.Biz
*
*/

function getAdminName($con, $id){
    $data = mysqliPreparedQuery($con, 'SELECT name FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($id));
    if ($data !== false)
        return ucfirst($data['name']);
    return 'Unknown';
}

function getAdminDetails($con, $id){

    $row = mysqliPreparedQuery($con, 'SELECT user,name,logo,role FROM '.DB_PREFIX.'admin WHERE id=?','i',array($id));
    if($row !== false)
        return array('email' => $row['user'], 'name' => $row['name'], 'logo' => createLink($row['logo'],true,true), 'role' => $row['role']);
    return false;
}

function getAdminDetailsFull($con, $id){
    $row = mysqliPreparedQuery($con, 'SELECT user,name,logo,last_visit,last_visit_page,active_session_id,role FROM '.DB_PREFIX.'admin WHERE id=?','i',array($id));
    if($row !== false) {
        $data = mysqliPreparedQuery($con, 'SELECT ip,browser FROM '.DB_PREFIX.'admin_history WHERE id=?','i',array($row['active_session_id']));
        if($data !== false) {

            $data2 = mysqliPreparedQuery($con, 'SELECT name,data FROM ' . DB_PREFIX . 'admin_roles WHERE id=?', 'i', array($row['role']));
            if ($data2 !== false) {
                $data2['data'] =  dbStrToArr($data2['data']);

                if($data2['data'][0] == 'all')
                    $data2['access'] = '<span class="label label-success">'.$GLOBALS['lang']['CH370'].'</span>';
                else
                    $data2['access'] = '<span class="label label-danger">'.$GLOBALS['lang']['CH369'].'</span>';

                return array('email' => $row['user'], 'name' => $row['name'], 'logo' => createLink($row['logo'], true, true),
                    'role' => $data2['name'], 'access' => $data2['access'], 'ip' => $data['ip'], 'ua' => $data['browser'], 'last_visit' => $row['last_visit'], 'last_visit_page' => $row['last_visit_page']);

            }
        }
    }
    return false;
}

function getAdminActiveSessionid($con, $adminID){
    $data = mysqliPreparedQuery($con, 'SELECT active_session_id FROM ' . DB_PREFIX . 'admin WHERE id=?', 'i', array($adminID));
    if ($data !== false)
        return intval($data['active_session_id']);
    return 0;
}

function GetDirectorySize($path){
    $bytestotal = 0;
    if ($path !== false){
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path,FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

function successMsgAdmin($msg, $dismissable = true){        
    return '
    <div class="alert alert-success '.($dismissable ? 'alert-dismissable' : '').'">
        <i class="fa fa-check"></i>
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        <b>'.$GLOBALS['lang']['RF20'].'</b> '.$msg.'
    </div>';
}

function errorMsgAdmin($msg, $dismissable = true){    
    return '
    <div class="alert alert-danger '.($dismissable ? 'alert-dismissable' : '').'">
        <i class="fa fa-ban"></i>
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        <b>'.$GLOBALS['lang']['RF20'].'</b> ' . $msg . '
    </div>';
}

function warnMsgAdmin($msg, $dismissable = true){    
    return '
    <div class="alert alert-warning '.($dismissable ? 'alert-dismissable' : '').'">
        <i class="fa fa-ban"></i>
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        <b>'.$GLOBALS['lang']['RF20'].'</b> ' . $msg . '
    </div>';
}

function rndColor(){
    $bageColor = array(
        'blue',
        'red',
        'green',
        'purple',
        'light-blue',
        'yellow');
    $rndColor = $bageColor[array_rand($bageColor)];
    return $rndColor;
}

function rndFlatColor(){
    $bageColor = array(
        '#1ccdaa','#2ecc71','#3498db','#9b59b6','#34495e','#16a085','#27ae60','#2980b9','#8e44ad','#2c3e50','purple',
        '#f39c12','#e67e22','#e74c3c','#95a5a6','#7f8c8d','#d35400','#c0392b','#1E8BC3','#1BA39C','#DB0A5B',
        '#96281B');
    $rndColor = $bageColor[array_rand($bageColor)];
    return $rndColor;
}

function pickUpRandom($arr){
    return $arr[array_rand($arr)];
}
function getAdminMenuIcon($ba_laji, $array) {
    foreach ($array as $key => $val) {
        if(isset($val[4])){
            foreach ($val[4] as $arrKey => $arrVal) {
                if ($arrVal[1] === $ba_laji){
                    echo $array[$key][3];
                    return true;
                }
            }
        }else{
            if ($val[2] === $ba_laji){
                echo $array[$key][3];
                return true;
            }
        }
    }
    return null;
}
function getAdminPagesLinks($menuLinks, $onlyLinks=false){
    $arr = [];
    $donotStr = ['header-li','#','-',''];
    foreach($menuLinks as $menuLink) {
        if (isset($menuLink[4])) {
            foreach($menuLink[4] as $subLink){
                if(!in_array($subLink[1],$donotStr)) {
                    if($onlyLinks)
                        $arr[] =$subLink[1];
                    else
                        $arr[] = array($menuLink[1] . ' (' . $subLink[0] . ')', $subLink[1]);
                }
            }
        }else{
            if(!in_array($menuLink[2],$donotStr)) {
                if($onlyLinks)
                    $arr[] =$menuLink[2];
                else
                    $arr[] = array($menuLink[1], $menuLink[2]);
            }
        }
    }
    return $arr;
}

function rainbowLoader($txt, $return = false){
    $data = '<img alt="'.$txt.'" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjAiIHdpZHRoPSI2NHB4IiBoZWlnaHQ9IjY0cHgiIHZpZXdCb3g9IjAgMCAxMjggMTI4IiB4bWw6c3BhY2U9InByZXNlcnZlIj48Zz48cGF0aCBkPSJNNjQgMEw0MC4wOCAyMS45YTEwLjk4IDEwLjk4IDAgMCAwLTUuMDUgOC43NUMzNC4zNyA0NC44NSA2NCA2MC42MyA2NCA2MC42M1YweiIgZmlsbD0iI2ZmYjExOCIvPjxwYXRoIGQ9Ik0xMjggNjRsLTIxLjg4LTIzLjlhMTAuOTcgMTAuOTcgMCAwIDAtOC43NS01LjA1QzgzLjE3IDM0LjQgNjcuNCA2NCA2Ny40IDY0SDEyOHoiIGZpbGw9IiM4MGMxNDEiLz48cGF0aCBkPSJNNjMuNyA2OS43M2ExMTAuOTcgMTEwLjk3IDAgMCAxLTUuMDQtMjAuNTRjLTEuMTYtOC43LjY4LTE0LjE3LjY4LTE0LjE3aDM4LjAzcy00LjMtLjg2LTE0LjQ3IDEwLjFjLTMuMDYgMy4zLTE5LjIgMjQuNTgtMTkuMiAyNC41OHoiIGZpbGw9IiNjYWRjMjgiLz48cGF0aCBkPSJNNjQgMTI4bDIzLjktMjEuODhhMTAuOTcgMTAuOTcgMCAwIDAgNS4wNS04Ljc1QzkzLjYgODMuMTcgNjQgNjcuNCA2NCA2Ny40VjEyOHoiIGZpbGw9IiNjZjE3MWYiLz48cGF0aCBkPSJNNTguMjcgNjMuN2ExMTAuOTcgMTEwLjk3IDAgMCAxIDIwLjU0LTUuMDRjOC43LTEuMTYgMTQuMTcuNjggMTQuMTcuNjh2MzguMDNzLjg2LTQuMy0xMC4xLTE0LjQ3Yy0zLjMtMy4wNi0yNC41OC0xOS4yLTI0LjU4LTE5LjJ6IiBmaWxsPSIjZWMxYjIxIi8+PHBhdGggZD0iTTAgNjRsMjEuODggMjMuOWExMC45NyAxMC45NyAwIDAgMCA4Ljc1IDUuMDVDNDQuODMgOTMuNiA2MC42IDY0IDYwLjYgNjRIMHoiIGZpbGw9IiMwMThlZDUiLz48cGF0aCBkPSJNNjQuMyA1OC4yN2ExMTAuOTcgMTEwLjk3IDAgMCAxIDUuMDQgMjAuNTRjMS4xNiA4LjctLjY4IDE0LjE3LS42OCAxNC4xN0gzMC42M3M0LjMuODYgMTQuNDctMTAuMWMzLjA2LTMuMyAxOS4yLTI0LjU4IDE5LjItMjQuNTh6IiBmaWxsPSIjMDBiYmYyIi8+PHBhdGggZD0iTTY5LjczIDY0LjM0YTExMS4wMiAxMTEuMDIgMCAwIDEtMjAuNTUgNS4wNWMtOC43IDEuMTQtMTQuMTUtLjctMTQuMTUtLjdWMzAuNjVzLS44NiA0LjMgMTAuMSAxNC41YzMuMyAzLjA1IDI0LjYgMTkuMiAyNC42IDE5LjJ6IiBmaWxsPSIjZjhmNDAwIi8+PGNpcmNsZSBjeD0iNjQiIGN5PSI2NCIgcj0iMi4wMyIvPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBmcm9tPSIwIDY0IDY0IiB0bz0iLTM2MCA2NCA2NCIgZHVyPSIyNzAwbXMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIj48L2FuaW1hdGVUcmFuc2Zvcm0+PC9nPjwvc3ZnPg==">
    <br>'.$txt;

    if($return)
        return $data;
    else
        echo $data;
}