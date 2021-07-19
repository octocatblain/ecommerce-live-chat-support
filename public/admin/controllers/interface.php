<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
* @author Balaji
* @name: Rainbow PHP Framework
* @copyright 2019 ProThemes.Biz
*
*/

$pageTitle = trans('Manage Interface', $lang['CH658'], true);
$subTitle = trans('Default Interface Settings', $lang['CH659'], true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
   if(isset($_POST['theme']) || isset($_POST['lang'])){
       $stats = false;
       $selTheme = raino_trim($_POST['theme']);
       $selLang = raino_trim($_POST['lang']);
       
       if(setTheme($con,$selTheme))
            $stats = true;                      
       else
            $stats = false;
            
       if(setLang($con,$selLang))
            $stats = true;
       else
            $stats = false;
       
       if($stats)
            $msg = successMsgAdmin($lang['CH660']);
       else
            $msg = errorMsgAdmin($lang['CH218']);
   }
}

$themeList = getThemeList();
$langList = getAvailableLanguages($con);

$activeTheme = getTheme($con);
$activeLang = getLang($con);

$themeData = $langdata  = $activeLangName = $activeThemeName = '';
foreach($themeList as $cTheme){
    $themeData .= '<option '.isSelected($cTheme[0],true,'1',$activeTheme,true).' value="'.$cTheme[0].'">'.ucfirst($cTheme[2]['name']).'</option>';
    
    if(isSelected($cTheme[0],true,null,$activeTheme,true)) 
        $activeThemeName = $cTheme[2]['name'];
}

foreach($langList as $cLang){
    $langdata .= '<option '.isSelected($cLang[2],true,'1',$activeLang,true).' value="'.$cLang[2].'">'.$cLang[3].'</option>';
    
    if(isSelected($cLang[2],true,null,$activeLang,true)) 
        $activeLangName = $cLang[3];
}