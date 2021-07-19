<?php

/*
* @author Balaji
* @name Rainbow PHP Framework
* @copyright 2021 ProThemes.Biz
*
*/

function helper(){
    return 'I am Helper!';
}

function detectInstaller(){
    if(!(DEVMODE)) {
        $filename = ROOT_DIR . 'admin' . D_S . 'install' . D_S . 'install.php';
        if (file_exists($filename)) {
            if(check_str_contains($GLOBALS['currentLink'],'/admin'))
                $insLink = 'install/install.php';
            else
                $insLink = 'admin/install/install.php';
            echo "Install.php file exists! <br /> <br />  Redirecting to installer panel...";
            echo '<meta http-equiv="refresh" content="1;url='.$insLink.'">';
            exit();
        }
    }
    return false;
}

function getTheme($con){
    $result = mysqli_query($con, "SELECT theme FROM ".DB_PREFIX."interface where id=1");
    $row = mysqli_fetch_assoc($result);
    return trim($row['theme']);
}

function getThemeOptions($con,$themeName,$baseURL){
    $themeOptions = array();
    $result = mysqli_query($con, "SELECT * FROM ".DB_PREFIX."themes_data where id=1");
    $row = mysqli_fetch_assoc($result);
    if(isset($row[$themeName.'_theme'])){
        $themeOptions = dbStrToArr($row[$themeName.'_theme']);
        if(isset($themeOptions['general']['imgLogo'])) {
            if (isSelected($themeOptions['general']['imgLogo']))
                $themeOptions['general']['themeLogo'] = '<img class="themeLogoImg" src="' . $baseURL . $themeOptions['general']['logo'] . '" />';
            else
                $themeOptions['general']['themeLogo'] = '<span class="themeLogoText">' . htmlspecialchars_decode(shortCodeFilter($themeOptions['general']['htmlLogo'])) . '</span>';
            $themeOptions['general']['favicon'] = $baseURL . $themeOptions['general']['favicon'];
        }
    }
    return $themeOptions;
}

function getThemeOptionsDev($con,$themeName){
    $themeOptions = array();
    $result = mysqli_query($con, "SELECT * FROM ".DB_PREFIX."themes_data where id=1");
    $row = mysqli_fetch_assoc($result);
    if(isset($row[$themeName.'_theme']))
        $themeOptions = dbStrToArr($row[$themeName.'_theme']);
    return $themeOptions;
}

function getMaintenanceMode($con){

    $siteInfo =  mysqli_query($con, "SELECT other_settings FROM ".DB_PREFIX."site_info where id=1");
    $siteInfoRow = mysqli_fetch_assoc($siteInfo);
    $other = dbStrToArr($siteInfoRow['other_settings']);
    
    return array($other['other']['maintenance'],$other['other']['maintenance_mes']);
}

function getMenuBarLinks($con,$userPageUrl=''){
    
    $result = mysqli_query($con, "SELECT * FROM ".DB_PREFIX."pages");
    $rel = $target = $classActive = $relActive = $targetActive = '';
    $headerLinks = $footerLinks = array();

    while($row = mysqli_fetch_array($result)) {
        $header_show = filter_var($row['header_show'], FILTER_VALIDATE_BOOLEAN);
        $footer_show = filter_var($row['footer_show'], FILTER_VALIDATE_BOOLEAN);
        $linkShow = filter_var($row['status'], FILTER_VALIDATE_BOOLEAN);
        $langCheck = $row['lang'] == '' ? 'all' :  $row['lang'];

        if($linkShow){
            if($header_show || $footer_show){
                if($langCheck == 'all' || $langCheck == ACTIVE_LANG){
                    $classActive = $relActive = $targetActive = '';
                    $sort_order = $row['sort_order'];
                    $page_name = shortCodeFilter($row['page_name']);
                    /*if($row['page_name'] == '{{lang[1]}}'){
                        if(isset($_SESSION[N_APP.'Username'])){
                            $page_name = shortCodeFilter('{{lang[217]}}');
                            $row['page_url'] = createLink('dashboard',true);
                        }
                    }*/
                    if($row['type'] != 'page'){
                        $page_content = decSerBase($row['page_content']);
                        $rel = $page_content[0]; $target = $page_content[1];
                    }
                    
                    if($row['type'] == 'page')
                        $page_url = createLink('page/'.$row['page_url'],true);
                    elseif($row['type'] == 'internal')
                        $page_url = shortCodeFilter($row['page_url']);
                    elseif($row['type'] == 'external'){
                        $page_url = $row['page_url'];
                        if($rel != 'none' && $rel != '')
                            $relActive = ' rel="'.$rel.'"';
                        if($target != 'none' && $target != '')
                            $targetActive = ' target="'.$target.'"';
                    }
        
                    if(rtrim($page_url,'/') == rtrim($userPageUrl,'/'))
                        $classActive = ' class="active"';
                    
                    //Fix - Not needed     
                    $classActive = '';
                    if($header_show)
                        $headerLinks[] = array($sort_order,'<li'.$classActive.'><a'.$relActive.$targetActive.' href="'.$page_url.'">'.$page_name.'</a></li>');
                    sort($headerLinks);
                    
                    if($footer_show)
                        $footerLinks[] = array($sort_order,'<li'.$classActive.'><a'.$relActive.$targetActive.' href="'.$page_url.'">'.$page_name.'</a></li>');
                    sort($footerLinks);
                }
            }
        }
    }
    return array($headerLinks,$footerLinks);
}

function getSidebarWidgets($con){
    $leftWidgets = $rightWidgets = $footerWidgets = array();
    $result = mysqli_query($con,"SELECT * FROM ".DB_PREFIX."widget ORDER BY CAST(sort_order AS UNSIGNED) ASC");
    while ($row = mysqli_fetch_array($result))
    {
        $widgetType = strtolower(Trim($row['widget_type']));
        $showWidget = filter_var($row['widget_enable'], FILTER_VALIDATE_BOOLEAN);
        if($showWidget) {
            
            $widgetCode = htmlspecialchars_decode($row['widget_code']);
            $widgetName = htmlspecialchars_decode($row['widget_name']);
            
            if(check_str_contains($widgetCode,"shortCode")){
                $shortCode = explode("shortCode(",$widgetCode);
                $shortCode = explode(")",$shortCode[1]);
                $shortCode = Trim($shortCode[0]);
                if(defined($shortCode))
                    $widgetCode = str_replace("shortCode(".$shortCode.")",constant($shortCode),$widgetCode);
                else
                    $widgetCode = "SHORT CODE NOT FOUND!"; 
            }
            
            if($widgetType=="left")
                $leftWidgets[] = array($widgetName,$widgetCode);  
            elseif($widgetType=="right")
                $rightWidgets[] = array($widgetName,$widgetCode);  
            else
                $footerWidgets[] = array($widgetName,$widgetCode);  
        }
    }
    return array($leftWidgets,$rightWidgets,$footerWidgets);
}

function trans($str,$customStr=null,$returnStr=false){
    $noNullCheck = false;  //Enable for testing!
    if($noNullCheck)
        $nullData = 'NoNullCheck-Ba-la-ji';
    else
        $nullData = null;
    if(LANG_TRANS){
        if($customStr != $nullData){
            if($returnStr)
                return $customStr;
            else
                echo $customStr;
        }
        else{
            if($returnStr)
                return $str;
            else
                echo $str;
        }
    }else{
            if($returnStr)
                return $str;
            else
                echo $str; 
    }
    return true;
}

function getLang($con){
    $result = mysqli_query($con, "SELECT lang FROM ".DB_PREFIX."interface where id=1");
    $row = mysqli_fetch_assoc($result);
    return trim($row['lang']);
}

function getThemeDetails($themeDir){
    $themeDir = ROOT_DIR . 'theme' . D_S . $themeDir;
    if (is_dir($themeDir)){
        $themeDetailsFile = $themeDir.D_S.'themeDetails.xml';
        if(file_exists($themeDetailsFile)){

            $themeDetailsXML = simplexml_load_file($themeDetailsFile, "SimpleXMLElement", LIBXML_NOCDATA);
            $themeDetails = json_decode(json_encode($themeDetailsXML),true);
            if(isset($themeDetails['@attributes']['compatibility'])){
                if($themeDetails['@attributes']['compatibility'] == '1.0'){
                    if(isset($themeDetails['themeDetails']))
                        return array($themeDir,$themeDetails['themeDetails']);
                }
            }
        }
    }
    return false;
}

function getThemeList(){
    $dir = ROOT_DIR.'theme';
    $themelist = array();
    $filesBalajiArr = scandir($dir);
     foreach($filesBalajiArr as $file){
        $themeDir = $dir.D_S.$file;
        if($file != '.' && $file != '..'){
            if (is_dir($themeDir)){
               $themeDetailsFile = $themeDir.D_S.'themeDetails.xml';
               if(file_exists($themeDetailsFile)){
                    $themeDetailsXML = simplexml_load_file($themeDetailsFile, "SimpleXMLElement", LIBXML_NOCDATA);
                    $themeDetails = json_decode(json_encode($themeDetailsXML),true);
                    if(isset($themeDetails['@attributes']['compatibility'])){
                        if($themeDetails['@attributes']['compatibility'] == '1.0'){
                            if(isset($themeDetails['themeDetails']))
                                $themelist[] = array($file,$themeDir,$themeDetails['themeDetails']);
                        }
                    }
               }
            }
        }
     }
    return $themelist;
}

function isThemeExists($themeDirName){
    $themeDir = ROOT_DIR.'theme'.D_S.$themeDirName;
    $themeDetailsFile = $themeDir.D_S.'themeDetails.xml';
    if(file_exists($themeDir) && is_dir($themeDir)){
       if(file_exists($themeDetailsFile)){
            $themeDetailsXML = simplexml_load_file($themeDetailsFile, "SimpleXMLElement", LIBXML_NOCDATA);
            $themeDetails = json_decode(json_encode($themeDetailsXML),true);
            if(isset($themeDetails['@attributes']['compatibility'])){
                if($themeDetails['@attributes']['compatibility'] == '1.0'){
                    if(isset($themeDetails['themeDetails']))
                        return true;
                }
            }
       }
    }
    return false;
}

function setTheme($con,$themeName){
    $themeName = escapeTrim($con,$themeName);
    $themeArr = getThemeList();
    if (in_multiarray($themeName,$themeArr)) {        
        if (updateToDbPrepared($con, 'interface', array('theme' => $themeName), array('id' => 1)))
            return false;
        else
            return true;
    }else{
        return false;
    }
}

function setLang($con,$lang){
    $lang = escapeTrim($con,$lang);
    $langArr = getAvailableLanguages($con);

    if (in_multiarray($lang,$langArr)) {            
        if (updateToDbPrepared($con, 'interface', array('lang' => $lang), array('id' => 1)))
            return false;
        else
            return true;
    }else{
        return false;
    }
}

function ipBanCheck($con,$ip,$site_name) {
    $query = mysqli_query($con, "SELECT reason FROM ".DB_PREFIX."banned_ip WHERE ip='$ip'");
    if (mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);
        if($row['reason'] != '')
            die("You have been banned from ".$site_name." <br>"."Reason: ".$row['reason']); 
        else
            die("You have been banned from ".$site_name); 
    }
}

function errStop() {
    echo 'S'.'o'.'m'.'e'.'t'.'h'.'i'.'n'.'g'.' '.'W'.'e'.'n'.'t'.' '.'W'.'r'.'o'.'n'.'g'.'!';
    die();
}

if (isset($item_purchase_code)) {
    if($item_purchase_code == '')
        errStop();
    if(!check_str_contains($item_purchase_code,'-'))
        errStop();
} else {
    errStop();
}

function secureImageUpload($fileData,$maxFileSize=500000,$allowedTypes = array('jpg','png','jpeg','gif')){
    $itIsImage = false; $uploadMeBalaji = true;
    $targetDir = $targetFileName = $msg = '';

    if(isset($fileData["name"]) && $fileData["name"] != ''){
     
        $targetDir = ROOT_DIR.'uploads'.D_S;
        $targetFileName = basename($fileData["name"]);
        $itIsImage = getimagesize($fileData["tmp_name"]);
        
        //Check it is a image
        if ($itIsImage !== false) {
           
            //Check if file already exists
            $targetFileName = unqFile($targetDir,$targetFileName);
            $targetFile = $targetDir . $targetFileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            
            //Check file size
            if ($fileData["size"] > $maxFileSize){
                $msg = 'Sorry, your file is too large.';
                $uploadMeBalaji = false;
            } else {
                //Allow only certain file formats
                if(!in_array($imageFileType, $allowedTypes)){
                    $msg = 'Sorry, only '.implode(', ',$allowedTypes).' file types are allowed.';
                    $uploadMeBalaji = false;
                }
            }
    
            //Start Upload
            if ($uploadMeBalaji){
                if (move_uploaded_file($fileData["tmp_name"], $targetFile)){
                    //Uploaded
                    $msg = 'uploads/'.$targetFileName;
                } else{
                    $msg = 'Sorry, there was an error uploading your file.';
                    $uploadMeBalaji = false;
                }
            }
        } else {
            $msg = 'File is not an image.';
            $uploadMeBalaji = false;
        }
    }else{
        $msg = 'Unknown File';
        $uploadMeBalaji = false;
    }
    
    return array($uploadMeBalaji, $msg);
}

function serBase($arr=array()){
    return base64_encode(serialize($arr));
}

function decSerBase($str){
    return unserialize(base64_decode($str));
}

function fixJSON($json) {
    $regex = <<<'REGEX'
~
    "[^"\\]*(?:\\.|[^"\\]*)*"
    (*SKIP)(*F)
  | '([^'\\]*(?:\\.|[^'\\]*)*)'
~x
REGEX;

    return preg_replace_callback($regex, function($matches) {
        return '"' . preg_replace('~\\\\.(*SKIP)(*F)|"~', '\\"', $matches[1]) . '"';
    }, $json);
}

function arrToDbStr($con,$dataBala_ji){
    return escapeMe($con, json_encode($dataBala_ji));
}

function dbStrToArr($dataBal_aji){
    $jsonDecoded = array();
    $dataBal_aji = Trim($dataBal_aji);
    if($dataBal_aji == '')
        return $jsonDecoded;
    else{
        $jsonDecoded = json_decode(str_replace('\"','"',$dataBal_aji),true);

        if($jsonDecoded === NULL)
            $jsonDecoded = json_decode(stripcslashes($dataBal_aji), true);

        return array_map_recursive('stripcslashes',$jsonDecoded);
    }
}

function linkencode($url) {
    $ta = parse_url($url);
    if (!empty($ta['scheme'])) { $ta['scheme'].='://'; }

    if (isset($ta['user']) && isset($ta['pass'])) {
        if (!empty($ta['pass']) and !empty($ta['user'])) {
            $ta['user'] .= ':';
            $ta['pass'] = rawurlencode($ta['pass']) . '@';
        } elseif (!empty($ta['user'])) {
            $ta['user'] .= '@';
        }
    }else{
        $ta['user'] = $ta['pass'] = '';
    }

    if(isset($ta['port'])) {
        if (!empty($ta['port']) and !empty($ta['host']))
            $ta['host'] = '' . $ta['host'] . ':';
    }else{
        $ta['port'] = '';
    }

    if (!empty($ta['path'])) {
        $tu='';
        $tok=strtok($ta['path'], "\\/");
        while (strlen($tok)) {
            $tu.=rawurlencode($tok).'/';
            $tok=strtok("\\/");
        }
        $ta['path']='/'.trim($tu, '/');
    }
    if(isset($ta['query']) && !empty($ta['query']))
            $ta['query'] = '?' . $ta['query'];
    else
        $ta['query'] = '';

    if(isset($ta['fragment']) && !empty($ta['fragment']))
        $ta['fragment']='#'.$ta['fragment'];
    else
        $ta['fragment'] = '';

    return implode('', array($ta['scheme'], $ta['user'], $ta['pass'], $ta['host'], $ta['port'], $ta['path'], $ta['query'], $ta['fragment']));
}

function filBoolean($val){
    return filter_var($val, FILTER_VALIDATE_BOOLEAN);
}

function dlSendHeaders($filename) {
    $filename = str_replace(' ','-',$filename);
    //Disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    //Force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    //Disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function size_as_kb($yoursize) {
    $size_kb = round($yoursize/1024);
    return $size_kb;
}
function loadCapthca($con){
    $query = mysqli_query($con, "SELECT cap_options,cap_data,cap_type FROM ".DB_PREFIX."capthca where id=1");
    $row = mysqli_fetch_assoc($query);
    $cap_options = dbStrToArr($row['cap_options']);
    $cap_data = dbStrToArr($row['cap_data']);
    $cap_type = Trim($row['cap_type']);
    return array_merge($cap_options,$cap_data[$cap_type],array('cap_type'=>$cap_type));
}

function loadAllCapthca($con){
    $query = mysqli_query($con, "SELECT * FROM ".DB_PREFIX."capthca where id=1");
    return mysqli_fetch_assoc($query);
}

function loadMailSettings($con){
    $query = mysqli_query($con, "SELECT * FROM ".DB_PREFIX."mail WHERE id=1");
    return mysqli_fetch_assoc($query);
}

function getMailTemplates($con,$code){
    $query = mysqli_query($con, "SELECT * FROM ".DB_PREFIX."mail_templates WHERE code='$code'");
    return mysqli_fetch_assoc($query);
}

function in_multiarray($elem, $array) {
    $top = sizeof($array) - 1;
    $bottom = 0;
    while($bottom <= $top)
    {
        if($array[$bottom] == $elem)
            return true;
        else
            if(is_array($array[$bottom]))
                if(in_multiarray($elem, ($array[$bottom])))
                    return true;
               
        $bottom++;
    }       
    return false;
}

function createLink($link='',$return=false, $noLang=false){
    $langShortCode = '';
    if(!defined('BASEURL'))
        die('Base URL not set!');
    if(!$noLang){
        if(defined('LANG_SHORT_CODE'))
               $langShortCode = LANG_SHORT_CODE.'/';
    }
    if($return)
        return BASEURL.$langShortCode.$link;
    else
        echo BASEURL.$langShortCode.$link;
}

function adminLink($link='',$return=false, $noLang=false){
    $langShortCode = '';
    if(!defined('BASEURL'))
        die('Base URL not set!');
    if(!defined('ADMIN_PATH'))
        die('Admin Path not set!');
    if(!$noLang){
        if(defined('LANG_SHORT_CODE'))
            $langShortCode = LANG_SHORT_CODE.'/';
    }
    if($return)
        return BASEURL.ADMIN_PATH.$langShortCode.$link;
    else
        echo BASEURL.ADMIN_PATH.$langShortCode.$link;
}

function themeLink($link='',$return=false){
    if(!defined('THEMEURL'))
        die('Theme URL not set!');
    if($return)
        return THEMEURL.$link;
    else
        echo THEMEURL.$link;
}

function scriptLink($link='',$type=false,$isExternal=false,$typeStr=null,$return=false){
    $finalStr = $finalTypeStr = '';
    if(!defined('THEMEURL'))
        die('Theme URL not set!');
    if($type){
        if($typeStr == null)
            $finalTypeStr = 'type="text/javascript"';
        else
            $finalTypeStr = 'type="'.$typeStr.'"';
    }
    if($isExternal)
        $finalStr = '<script src="'.$link.'" '.$finalTypeStr.'></script>';
    else
        $finalStr = '<script src="'.THEMEURL.$link.'" '.$finalTypeStr.'></script>';
    if($return)
        return $finalStr;
    else
        echo $finalStr;
}

function genCanonicalData($baseURL, $currentLink, $loadedLanguages=array(), $return = false){
    $data = $activeLang = $activeLangSlash = '';
    if(defined('ACTIVE_LANG')){
        $activeLangSlash = ACTIVE_LANG.'/';
        $activeLang = ACTIVE_LANG;
    }

    $activeLink = str_replace(array($baseURL.$activeLangSlash, $baseURL.$activeLang, $baseURL), '', $currentLink);

    $data .= '<link rel="canonical" href="'.$currentLink.'" />'.PHP_EOL;
    
    foreach($loadedLanguages as $language){
      $data .= '        <link rel="alternate" hreflang="'.$language[2].'" href="'.$baseURL.$language[2].'/'.$activeLink.'" />'.PHP_EOL;
    }
    if($return)
        return $data;
    else
        echo $data;
}

function htmlPrint($htmlCode,$return=false){
    if($return)
        return htmlspecialchars_decode($htmlCode);
    else
        echo htmlspecialchars_decode($htmlCode);
}

function shortCodeFilter($string){
    //Bala-ji $regex = "/\{{\((.*?)\)\}}/";
    $regex = "/\{{(.*?)\}}/";
    $arrRegex = "/\[(.*?)\]/";
    preg_match_all($regex, $string, $matches);
    
    for($i = 0; $i < count($matches[1]); $i++) {
        $match = $matches[1][$i];
        preg_match($arrRegex, $match, $arrMatches);
        if(isset($arrMatches[1])){
            $newMatch =  str_replace("[".$arrMatches[1]."]",'',$match);
            if(isset($GLOBALS[$newMatch][$arrMatches[1]]))
                $string = str_replace("{{".$match."}}",$GLOBALS[$newMatch][$arrMatches[1]],$string);
            else
                stop('SHORT CODE ERROR - "'. $match.'" NOT FOUND');
        }else{
            if(isset($GLOBALS[$match]) && $match != '')
                $string = str_replace("{{".$match."}}",$GLOBALS[$match],$string);
            else
                stop('SHORT CODE ERROR - "'. $match.'" NOT FOUND');
        }
    }
    return $string;
}

function removeShortCodes($string){
    $regex = "/\{{(.*?)\}}/";
    $arrRegex = "/\[(.*?)\]/";
    return preg_replace($regex, '', $string);
}

function isSelected($val,$bol=true,$model=null,$matchString=null,$returnVal=false){
    
    $checkBalajiVal = null;
    
    if($matchString == null){
        $checkBalajiVal = filter_var($val, FILTER_VALIDATE_BOOLEAN);
    } else{
        if($matchString == $val)
            $checkBalajiVal = true;
        else
            $checkBalajiVal = false;
    }
    
    if($checkBalajiVal){
        if($bol){
            if($model == null)
                return true;
            elseif($model == '1'){
                if($returnVal)
                    return 'selected=""';
                else
                    echo 'selected=""';
            }elseif($model == '2'){
                if($returnVal)
                    return 'checked=""';
                else
                    echo 'checked=""';
            }
        }else{
            if($model == null)
                return false;
            elseif($model == '1'){
                if($returnVal)
                    return '';
                else
                    echo '';
            }elseif($model == '2'){
                if($returnVal)
                    return '';
                else
                    echo '';
            }
        }
     }else{
        if($bol){
            if($model == null)
                return false;
            elseif($model == '1'){
                if($returnVal)
                    return '';
                else
                    echo '';
            }elseif($model == '2'){
                if($returnVal)
                    return '';
                else
                    echo '';
            }
        }else{
            if($model == null)
                return true;
            elseif($model == '1'){
                if($returnVal)
                    return 'selected=""';
                else
                    echo 'selected=""';
            }elseif($model == '2'){
                if($returnVal)
                    return 'checked=""';
                else
                    echo 'checked=""';
            }
        }
    }
}

function quickLoginCheck($con,$ip){
    
    $date = date('Y-m-d');
    $taskData =  mysqli_query($con, "SELECT * FROM ".DB_PREFIX."rainbowphp where task='quick_login'");
    $taskRow = mysqli_fetch_array($taskData);
    $taskData = dbStrToArr($taskRow['data']);
    
    if(isset($taskData[$date])){
        if(isset($taskData[$date][$ip]))
            return false;
    }
    return true;
}

function quickLoginDisable($con,$ip){
    
    $date = date('Y-m-d');
    $taskData =  mysqli_query($con, "SELECT * FROM ".DB_PREFIX."rainbowphp where task='quick_login'");
    $taskRow = mysqli_fetch_array($taskData);
    $taskData = dbStrToArr($taskRow['data']);
    
    if(isset($taskData[$date])){
        if(isset($taskData[$date][$ip])){
            return false;
        }else{
            //New IP Record
            $taskData[$date][$ip] = array('time' => time());
        }
    }else{
        //Clear old date and insert new!
        $prevDate = date('Y-m-d', strtotime($date .' -1 day'));
        if(isset($taskData[$prevDate]))
            unset($taskData[$prevDate]);
        $taskData[$date][$ip] = array('time' => time());
    }
    updateToDb($con,'rainbowphp', array(
        'data' => arrToDbStr($con,$taskData)), array('task' => 'quick_login'));
    return true;
}

function setSession($data, $value=null, $group=null){
    $groupVal = 'default';
    $groupVar = 'SesVal';
    if($group != NULL)
        $groupVal = $group;
    if (!isset($_SESSION[N_APP . $groupVar])) $_SESSION[N_APP . $groupVar] = array();
    if (!isset($_SESSION[N_APP . $groupVar][$groupVal])) $_SESSION[N_APP . $groupVar][$groupVal] = array();

    if (is_array($data)) {
        foreach ($data as $key => &$value) {
            $_SESSION[N_APP . $key] = $value;
            if(!in_array($key, $_SESSION[N_APP.$groupVar][$groupVal]))
                $_SESSION[N_APP.$groupVar][$groupVal][] = $key;
        }
    }else{
        $_SESSION[N_APP . $data] = $value;
        if(!in_array($data, $_SESSION[N_APP.$groupVar][$groupVal]))
            $_SESSION[N_APP.$groupVar][$groupVal][] = $data;
    }
}

function getSession($values){
    if (is_array($values)) {
        $returnValues = [];
        foreach($values as $value){
            if(isset($_SESSION[N_APP.$value]))
                $returnValues[$value] = $_SESSION[N_APP.$value];
            else
                $returnValues[$value] = null;
        }
        return $returnValues;
    }else{
        if(isset($_SESSION[N_APP.$values]))
            return $_SESSION[N_APP.$values];
        else
            return null;
    }
}

function issetSession($val=NULL){
    if(isset($_SESSION[N_APP.$val]))
        return true;
    else
        return false;
}
function clearSession($values=NULL, $group='default'){
    if ($values != NULL) {
        if(is_array($values)){
            foreach($values as $val){
                if(isset($_SESSION[N_APP.$val])) unset($_SESSION[N_APP.$val]);
            }
        }else{
            if(isset($_SESSION[N_APP.$values]))  unset($_SESSION[N_APP.$values]);
        }
    }else{
        if(isset($_SESSION[N_APP.'SesVal'][$group])){
            foreach($_SESSION[N_APP.'SesVal'][$group] as $val){
                if(isset($_SESSION[N_APP.$val])) unset($_SESSION[N_APP.$val]);
            }
            unset($_SESSION[N_APP.'SesVal'][$group]);
        }
    }
}

function file_upload_max_size() {
    static $max_size = -1;
    if ($max_size < 0) {
        $post_max_size = parse_size(ini_get('post_max_size'));
        if ($post_max_size > 0)
            $max_size = $post_max_size;

        $upload_max = parse_size(ini_get('upload_max_filesize'));
        if ($upload_max > 0 && $upload_max < $max_size)
            $max_size = $upload_max;
    }
    return $max_size;
}

function parse_size($size) {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);
    if ($unit)
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    else
        return round($size);
}

function br2nl($string){
    return  preg_replace('/<br\\s*?\/??>/i', PHP_EOL, $string);
}