<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Language Editor',$lang['CH693'],true);
$subTitle = trans('Available Languages',$lang['CH694'],true);
$defaultLang = getLang($con);

$htmlLibs = array('dataTables');
if(issetSession('msgCallBack')){ $msg = getSession('msgCallBack'); clearSession('msgCallBack'); }

if($pointOut === 'save'){
    ob_end_clean();
    header('Content-Type: application/json');
    $output = array('success' => false);

    $myValues = array_map_recursive(function ($item) use ($con) {
        return escapeTrim($con, $item);
    }, $_POST);

    if(isset($args[0])){
        if($args[0] === 'settings'){

            $updateLangArr = array($myValues['sort_order'],strtolower($myValues['language_code']),$myValues['language_name'],$myValues['status'],$myValues['direction']);

            //Update General Settings
            langUpdateAll(strtolower($myValues['oldLangCode']),$updateLangArr,$con);
            $output['success'] = true;

        } elseif($args[0] === 'data'){
            $language_code = raino_trim($myValues['langCode']);
            unset($myValues['langCode']);
            foreach($myValues as $langCode=>$langVal){
                $langBalaji = $language_code;
                $query = "UPDATE ".DB_PREFIX."lang SET lang_$langBalaji='$langVal' WHERE id=$langCode";
                mysqli_query($con,$query);
            }
            $output['success'] = true;
        } elseif($args[0] === 'success'){
            $output['success'] = true;
            $output['link'] = adminLink($controller,true,true);
            setSession('msgCallBack', successMsgAdmin($lang['CH705']));
        }
    }
    echo json_encode($output);
    die();
}

if($pointOut === 'backup'){
    
    if(isset($args[0]))
        $code = $args[0];
    else
        die($lang['CH695']);
        
    $langInfo = getSelectedLang($code,$con);
    $langDataRainbow = gzcompress(base64_encode(serialize(array($langInfo,getLangData($code,$con)))));   
    header('Content-Description: File Transfer');
    header('Content-Type: text/html; charset=UTF-8');
    header('Content-Length: ' . strlen($langDataRainbow));
    header('Content-disposition: attachment; filename='.$code.'.lbak');
    ob_clean();
    flush();
    echo $langDataRainbow;
    die();
}

if($pointOut === 'import'){
    $subTitle = trans('Import Language File', $lang['CH696'], true);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $customStr = raino_trim($_POST['customStr']);
        $customStr = filter_var($customStr, FILTER_VALIDATE_BOOLEAN);
        
        $target_dir = ADMIN_DIR . "addons".D_S;
        $target_filename = basename($_FILES["langUpload"]["name"]);
        $target_file = $target_dir . $target_filename;
        
        $uploadSs = 1;
        // Check if file already exists
        if (file_exists($target_file))
        {
            $target_filename = rand(1, 99999) . "_" . $target_filename;
            $target_file = $target_dir . $target_filename;
        }
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check file size
        if ($_FILES["langUpload"]["size"] > 999500000)
        {
            $msg = errorMsgAdmin($lang['RF97']);
            $uploadSs = 0;
        } else {
            // Allow certain file formats
            if ($imageFileType != "lbak" && $imageFileType != "ldata"){
                $msg = errorMsgAdmin($lang['CH697']);
                $uploadSs = 0;
            }
        }
    
        // Check if $uploads is set to 0 by an error
        if (!$uploadSs == 0)
        {
            //No Error - Move the file to addon directory
            if (move_uploaded_file($_FILES["langUpload"]["tmp_name"], $target_file))
            {
                
                //Language File Path
                $file_path = $target_dir . $target_filename;
                
                $importData = unserialize(base64_decode(gzuncompress(getMyData($file_path))));
                
                $importLangInfo = $importData[0];
                $importLangData = $importData[1];
                $importLangInfo[2] = strtolower($importLangInfo[2]);
                
                if(is_array($importLangInfo) && is_array($importLangData)){
                    if(!isLangExists($importLangInfo[2],$con)){
                        addLang(array('2','',$importLangInfo[2],$importLangInfo[3],$importLangInfo[4],$importLangInfo[5],$importLangInfo[6]),$con);
                        
                        $langBalaji = $importLangInfo[2];
                        
                        //Update Language Data
                        foreach($importLangData as $langCode=>$langVal){
                            if($customStr){
                                $langVal = escapeTrim($con,$langVal);
                                $query = "UPDATE ".DB_PREFIX."lang SET $langBalaji='$langVal' WHERE code='lang_$langCode'";
                                mysqli_query($con,$query);
                            }else{
                                if(!check_str_contains($langCode,'CS')){
                                    $langVal = escapeTrim($con,$langVal);
                                    $query = "UPDATE ".DB_PREFIX."lang SET $langBalaji='$langVal' WHERE code='lang_$langCode'";
                                    mysqli_query($con,$query);
                                }
                            }
                        }

                        $msg = successMsgAdmin($lang['CH698']);
                    }else{
                        $msg = errorMsgAdmin($lang['CH699']);
                    }
                }else{
                    $msg = errorMsgAdmin($lang['CH700']);
                }
                
                //Delete the language file
                delFile($file_path);  

            } else {
                $msg = errorMsgAdmin($lang['RF99']);
            }
        }
    }
    
}

if($pointOut === 'status'){
    $status = false;
    if($args[0] == 'disable')
        $status = false;
    else
        $status = true;
    $code = $args[1];
    if($code != $defaultLang){
        langStatusChange($code,$status,$con);
        header('Location:'.adminLink($controller,true));
        die();
    }else{
        $msg = errorMsgAdmin($lang['CH701']);
    }
}

if($pointOut === 'delete'){
    if(isset($args[0]) && $args[0] != ''){
        $code = $args[0];
        if($code != $defaultLang){
            removeLang($code,$con);
            header('Location:'.adminLink($controller,true));
            die();
        }else{
            $msg = errorMsgAdmin($lang['CH702']);
        }
    }
}

if($pointOut === 'add'){
    $subTitle = trans('Create New Language', $lang['CH703'], true);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $language_name = raino_trim($_POST['language_name']);
        $language_code = strtolower(raino_trim($_POST['language_code']));
        
        if(!isLangExists($language_code,$con)){
            $language_author = raino_trim($_POST['language_author']);
           // $sort_order = raino_trim($_POST['sort_order']);
            $status = raino_trim($_POST['status']);
            $text_direction = raino_trim($_POST['direction']);
            $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);
            addLang(array('2','',$language_code,$language_name,$language_author,$status,$text_direction),$con);
            header('Location:'.adminLink($controller.'/edit/'.$language_code,true));
            die();
        }else{
            $msg = errorMsgAdmin($lang['CH699']);
        }
    }
}

if($pointOut === 'add-custom-text'){
    $subTitle = trans('Add New Custom String', $lang['CH704'], true);
    $customNumber = getLastID($con,DB_PREFIX.'lang') + 1;
    $customNumber = 'CS'.$customNumber;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $csnumber = raino_trim($_POST['csnumber']);
        $default_string = raino_trim($_POST['default_string']);
        $query = "INSERT INTO ".DB_PREFIX."lang (code,default_text) VALUES ('$csnumber','$default_string')";
        mysqli_query($con, $query);
        if($args[0] != '')
            header('Location:'.adminLink($controller.'/edit/'.$args[0],true));
        else
            header('Location:'.adminLink($controller,true));
        die();
        
    }
}

if($pointOut === 'edit'){
    
    if(isset($_SESSION['callBackMsg'])){
        $msg = $_SESSION['callBackMsg'];
        unset($_SESSION['callBackMsg']);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $language_name = escapeTrim($con,$_POST['language_name']);
        $language_code = strtolower(escapeTrim($con,$_POST['language_code']));
        $sort_order = escapeTrim($con,$_POST['sort_order']);
        $direction = escapeTrim($con,$_POST['direction']);
        $status = escapeTrim($con,$_POST['status']);
        $updateLangArr = array($sort_order,$language_code,$language_name,$status,$direction);
        $postCount = 1;

        //Update General Settings
        langUpdateAll($args[0],$updateLangArr,$con);

        //Update Language Data
        foreach($_POST as $langCode=>$langVal){
            if($postCount > 5){
                $langBalaji = $language_code;
                $langVal = escapeTrim($con,$langVal);
                $query = "UPDATE ".DB_PREFIX."lang SET lang_$langBalaji='$langVal' WHERE id='$langCode'";
                mysqli_query($con,$query);
            }
            $postCount++;
        }
        $_SESSION['callBackMsg'] = successMsgAdmin($lang['CH705']);
        redirectTo(adminLink($controller.'/edit/'.$language_code,true));
    }
    
    if($args[0] != ''){
       $subTitle = strtoupper($args[0]).' - '.$lang['CH693'];
       $langCodes = $langDataArr = array();
       $langBalajiCheck = false; 
       $code = 0;
       $langTable =  mysqli_query($con, "SELECT * FROM ".DB_PREFIX."lang where id='1'");
       $langTableRow = mysqli_fetch_array($langTable,MYSQLI_ASSOC);
       
       foreach($langTableRow as $langTableCode => $langTableVal){
           if($code == 3)
                $langBalajiCheck = true;
           if($langBalajiCheck)
                $langCodes[] = substr($langTableCode, -2);
           $code++;
       }
       if(in_array($args[0],$langCodes)){
           $langCodeData = mysqli_query($con, "SELECT id, code, default_text, lang_$args[0] FROM ".DB_PREFIX."lang");
           while($langCodeDataRow = mysqli_fetch_array($langCodeData,MYSQLI_NUM)) {
           $langDataArr[] = array($langCodeDataRow[0],$langCodeDataRow[1],$langCodeDataRow[2],$langCodeDataRow[3]);
           }
           $generalLangSet = getSelectedLang($args[0],$con);
       }else
        die($lang['CH706']);
       
    }else
        header('Location: '.adminLink($controller,true));
    
}else{
    $allLangs = getAllLang($con);
}