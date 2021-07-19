<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
 
$controller = $route = $pointOut = $pointOutA = null;
$args = $custom_route = array();

if(isset($_GET['route'])) {
    $route = escapeTrim($con,$_GET['route']); 
    $route = explode('/',$route);
    /// ------ Load Language Data START ------ 
    if(strlen($route[0]) == 2){
        #Remove Default Language Code on Link
        //if($route[0] === 'en') redirectTo(str_replace('/en','',$currentLink));
        if(isLangExists(strtolower($route[0]),$con)){
            define('LANG_SHORT_CODE',strtolower($route[0]));
            define('ACTIVE_LANG',strtolower($route[0]));
            $lang = getLangData(LANG_SHORT_CODE,$con);
            $route = array_slice($route, 1);
        }
    }else{
        if(isset($_SESSION[N_APP.'UserSelectedLang'])){
            //User Selected Language
            $loadLangCode = strtolower(raino_trim($_SESSION[N_APP.'UserSelectedLang']));
            define('ACTIVE_LANG',$loadLangCode);
            $lang = getLangData($loadLangCode,$con);
        }else{
            //Default Language
            $defaultLang = getLang($con); 
            define('ACTIVE_LANG',$defaultLang);
            $lang = getLangData($defaultLang,$con);
        }
    }
    isRouteEnabled($con);
    /// ------ Load Language Data END ------ 
    if(isset($route[0]) && $route[0] != ''){
        $controller = $route[0];
        if(isset($route[1])) {
            $pointOut = $route[1];
            $pointOutA = '/'.$route[1];
        }
        $args = array_slice($route, 2);
        $argWithPointOut = array_slice($route, 1);
        
        if(CUSTOM_ROUTE){
            foreach($links as $linkKey=>$linkVal){
                if($linkKey != $linkKey){
                    $custom_route[$linkVal] = $linkKey;
                    $custom_route[$linkKey] = CON_ERR;
                }
            }
            require ROU_DIR.'custom_router.php';
                foreach($custom_route as $customRouteKey=>$customRouteVal){
                    $customRouteKey = explode('/', $customRouteKey);
                    if($controller == Trim($customRouteKey[0])){
                        if(isset($customRouteKey[1])){
                            if($pointOut != null){
                                if($customRouteKey[1] == "[:any]"){
                                    $route = explode('/',$customRouteVal);
                                    $controller = $route[0];
                                    if(isset($route[1]))
                                        $pointOut = $route[1];
                                    $args = $argWithPointOut;
                                    break;
                                }else{
                                if($pointOut == $customRouteKey[1]){
                                    $route = explode('/',$customRouteVal);
                                    $controller = $route[0];
                                    if(isset($route[1]))
                                        $pointOut = $route[1];
                                    $args = array_merge(array_slice($route, 2),$args);
                                    break;
                                }
                                }
                            }
                        }else{
                            $route = explode('/',$customRouteVal);
                            $controller = $route[0];
                            if(isset($route[1])){
                                $pointOut = $route[1];
                                $args = array_merge(array_slice($route, 2),$argWithPointOut);
                            }else{
                                $args = array_merge(array_slice($route, 2),$args);
                            }
                            break;
                        }
                    }
                }
        }
    }else {
        if(defined('ADMIN_CON_DIR'))
            $controller = 'AdminDefaultCon';
        else
            $controller = CON_MAIN;
    }
}else{
    if(defined('ADMIN_CON_DIR'))
        $controller = 'AdminDefaultCon';
    else
        $controller = CON_MAIN;
}