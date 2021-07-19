<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Manage Departments', $lang['CH360'], true);
$subTitle = trans('Departments', $lang['CH361'], true);

$htmlLibs = array('dataTables','select2');
if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

if($pointOut == 'delete'){
    if(isset($args[0]) && $args[0] !== ''){
        $delID = intval($args[0]);
        if($delID !== 1) {
            $sql = 'DELETE FROM ' . DB_PREFIX . 'departments WHERE id=' . $delID;
            if (mysqli_query($con, $sql))
                die('1');
        }
    }
    die('0');
}

if($pointOut === 'groupdel'){

    $myValues = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    $delValues = implode(', ', $myValues['id']);

    $sql = 'DELETE FROM ' . DB_PREFIX . 'departments WHERE id IN ('.$delValues.')';
    if (mysqli_query($con, $sql))
        die('1');
    die('0');
}


if($pointOut === 'add' || $pointOut === 'edit') {
    $d = array();
    $d['status'] = $d['res'] = 1;
    $d['data'] = array();
    $restrictedData = '';

    if (isset($_POST['d'])) {
        $myValues = array_map_recursive(function ($item) use ($con) {
            return escapeTrim($con, $item);
        }, $_POST);
    }

    if($pointOut === 'add'){
        $subTitle = trans('Add Department', $lang['CH362'], true);

        if(isset($_POST['d'])) {
            $insert = $myValues['d'];
            $insert['date'] = $date;

            if(isSelected($insert['res']))
                $insert['data'] = json_encode(array('all'));
            else
                $insert['data']= json_encode($insert['data']);
            unset($insert['res']);

            if(insertToDbPrepared($con , 'departments', $insert)){
                $d = $insert;
                $d['data'] = json_decode($insert['data'],true);
                $d['res'] = 0;
                if(isset($d['data'][0])){
                    if($d['data'][0] === 'all')
                        $d['res'] = 1;
                }
                $msg = errorMsgAdmin($lang['CH281']);
            }else{
                setSession('msgCallBack', successMsgAdmin($lang['CH363']));
                redirectTo(adminLink($controller,true));
            }
        }
    }

    if($pointOut === 'edit') {
        $subTitle = trans('Update Department', $lang['CH364'], true);
        if(isset($args[0]) && $args[0] !== '') {
            $editID = intval($args[0]);

            $data = mysqliPreparedQuery($con, 'SELECT name,data,des,status FROM ' . DB_PREFIX . 'departments WHERE id=?', 'i', array($editID));
            if($data !== false)
                $d = $data;
            else
                redirectTo(adminLink($controller, true));

            if(isset($_POST['d'])) {
                $update = $myValues['d'];
                $update['date'] = $date;

                if(isSelected($update['res']))
                    $update['data'] = json_encode(array('all'));
                else
                    $update['data']= json_encode($update['data']);
                unset($update['res']);

                if(updateToDbPrepared($con , 'departments', $update, array('id' => $editID))){
                    $d = $update;
                    $msg = errorMsgAdmin($lang['CH283']);
                }else{
                    setSession('msgCallBack', successMsgAdmin($lang['CH365']));
                    redirectTo(adminLink($controller,true));
                }
                }

            $d['data'] = json_decode($d['data'],true);
            $d['res'] = 0;
            if(isset($d['data'][0])){
                if($d['data'][0] === 'all')
                    $d['res'] = 1;
            }
        }
    }

    $result = mysqli_query($con, 'SELECT id,name,user FROM ' . DB_PREFIX . 'admin');
    while($row = mysqli_fetch_assoc($result)) {
        if(in_array($row['id'],$d['data']))
            $restrictedData .= '<option selected="" value="'.$row['id'].'">'.$row['name'].'('. $row['user'] .')</option>';
        else
            $restrictedData .= '<option value="'.$row['id'].'">'.$row['name'].'('. $row['user'] .')</option>';
    }
}