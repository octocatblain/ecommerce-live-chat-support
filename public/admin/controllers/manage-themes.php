<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

$pageTitle = trans('Manage Themes', $lang['CH665'], true);
$subTitle = trans('All Themes', $lang['CH666'], true);
$defaultTheme = getTheme($con);

if($pointOut == 'success')
    $msg = successMsgAdmin($lang['CH667']);

if($pointOut == 'failed')
    $msg = errorMsgAdmin($lang['RF91']);

if($pointOut === 'clone') {

    $subTitle = trans('Clone Theme', $lang['CH668'], true);

    if (isset($args[0]) && $args[0] != '') {

        $themeDir = ROOT_DIR.'theme'.D_S.$args[0];

        $themeDetails = array();

        if (is_dir($themeDir)) {
            $themeDetailsFile = $themeDir . D_S . 'themeDetails.xml';
            if (file_exists($themeDetailsFile)) {
                $themeDetailsXML = simplexml_load_file($themeDetailsFile, "SimpleXMLElement", LIBXML_NOCDATA);
                $themeDetails = json_decode(json_encode($themeDetailsXML), true);
                if (isset($themeDetails['@attributes']['compatibility'])) {
                    if ($themeDetails['@attributes']['compatibility'] == '1.0') {
                        if (isset($themeDetails['themeDetails']))
                            $themeDetails = $themeDetails['themeDetails'];
                    }
                }
            }
        }

        $srcTheme = explode('/',$themeDetails['builder']);
        $srcTheme = $srcTheme[count($srcTheme) - 1];

        $builderControllers = ADMIN_CON_DIR.'builder';
        $builderTheme = ADMIN_THEME_DIR.'builder';
        $themePath = ROOT_DIR.'theme';

        $minMsg = array();
        $minError = false;
        if (is_writable($builderControllers)) {
            $minMsg[] = array('"/admin/controllers/builder/"','<span class="label label-success">'.$lang['CH469'].'</span>');
        } else {
            $minError = true;
            $minMsg[] = array('"/admin/controllers/builder/"','<span class="label label-danger">'.$lang['CH470'].'</span>');
        }
        if (is_writable($builderTheme)) {
            $minMsg[] = array('"/admin/theme/default/builder/"','<span class="label label-success">'.$lang['CH469'].'</span>');
        } else {
            $minError = true;
            $minMsg[] = array('"/admin/theme/default/builder/"','<span class="label label-danger">'.$lang['CH470'].'</span>');
        }
        if (is_writable($themePath)) {
            $minMsg[] = array('"/theme/"','<span class="label label-success">'.$lang['CH469'].'</span>');
        } else {
            $minError = true;
            $minMsg[] = array('"/theme/"','<span class="label label-danger">'.$lang['CH470'].'</span>');
        }


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $log = array();
            $myValues = array_map_recursive(function ($item) use ($con) {
                return escapeTrim($con, $item);
            }, $_POST['theme']);

            $myValues['dir'] = mb_strtolower($myValues['dir']);

            $cloneThemePath = $themePath. D_S . $myValues['dir'];
            $cloneThemeBaseName = themeBaseName($myValues['name']);

            if (is_writable($cloneThemePath) || mkdir($cloneThemePath, 0755)) {
                chmod($cloneThemePath, 0755);
                //Copy Theme Files
                themeCopyFiles($themeDir, $cloneThemePath);

                //Copy Theme Builder Files
                if(!copy($builderControllers.D_S.$srcTheme.'.php',$builderControllers.D_S.$cloneThemeBaseName.'.php'))
                    $log[] = $lang['CH674'].": ".$builderControllers.D_S.$cloneThemeBaseName.'.php'.PHP_EOL;

                if(!copy($builderTheme.D_S.$srcTheme.'.php',$builderTheme.D_S.$cloneThemeBaseName.'.php'))
                    $log[] = $lang['CH674'].": ".$builderTheme.D_S.$cloneThemeBaseName.'.php'.PHP_EOL;

                //Update Theme Details
                $themeDetailsXMLFile = $cloneThemePath .D_S.'themeDetails.xml';
                $themeDetailsXML = simplexml_load_file($themeDetailsXMLFile, "SimpleXMLElement", LIBXML_NOCDATA);
                $themeDetailsXML->themeDetails->name = $myValues['name'];
                $themeDetailsXML->themeDetails->themeBaseName = $cloneThemeBaseName;
                $themeDetailsXML->themeDetails->description = $myValues['des'];
                $themeDetailsXML->themeDetails->author = $myValues['author'];
                $themeDetailsXML->themeDetails->authorEmail = $myValues['email'];
                $themeDetailsXML->themeDetails->authorWebsite = $myValues['link'];
                $themeDetailsXML->themeDetails->copyright = $myValues['copy'];
                $themeDetailsXML->themeDetails->builder = 'builder/client/'.$myValues['dir'].'/'.$cloneThemeBaseName;
                $themeDetailsXML->asXML($themeDetailsXMLFile);

                //Create theme database
                $colName = $myValues['dir'].'_theme';
                $oldColName = $args[0].'_theme';
                if (mysqli_query($con, "ALTER TABLE ".DB_PREFIX."themes_data ADD $colName MEDIUMBLOB")) {
                    if(mysqli_query($con, "UPDATE ".DB_PREFIX."themes_data SET $colName = CAST($oldColName AS CHAR)")){
                        $log[] = $lang['CH671'].PHP_EOL;
                    }else{
                        $log[] = $lang['CH670']. PHP_EOL;
                    }
                }else{
                    $log[] = $lang['CH669'].PHP_EOL;
                }

            }else{
                $log[] = $lang['CH672'].' "' . $cloneThemePath . '" '.$lang['CH673'];
            }

            $logData = '';
            foreach($log as $line)
                $logData .= $line;
        }

    }else{
        header('Location:'.adminLink($controller,true));
        die();
    }
}

function themeBaseName($name){
    $name = mb_strtolower($name);
    return str_replace(array('~','!','@','#','$','%','^','&','.','"','*','(',')','_', ' ', '|', '{', '}', '<', '>', '?', ';', ':', "'", '=','+'),'-',$name);
}

function themeCopyFiles($src,$dst) {
    $dir = opendir($src);
    if(!is_writable($dst))
        @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                themeCopyFiles($src . '/' . $file,$dst . '/' . $file);
            } else {
                if(!copy($src . '/' . $file,$dst . '/' . $file)){
                    $GLOBALS['log'][] = $GLOBALS['lang']['CH674'].": ".$dst . '/' . $file.PHP_EOL;
                }
            }
        }
    }
    closedir($dir);
}