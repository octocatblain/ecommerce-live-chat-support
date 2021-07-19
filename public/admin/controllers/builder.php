<?php

defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));
define('BUILDER_CON', ADMIN_CON_DIR.'builder'.D_S);
define('BUILDER_THEME_DIR', ADMIN_THEME_DIR.'builder'.D_S);

$pageBuilderTitle = $subTitle = 'Theme Builder';
$controllerPath = $themeBaseName = $themePathName = '';

if(isset($args[0]) && $args[0] != '' && isThemeExists($args[0])){
    $themePathName = $args[0];
    if(isset($args[1]) && $args[1] != ''){
        $themeBaseName = str_replace(' ','-',$args[1]);
        $controllerPath = BUILDER_CON.$themeBaseName.'.php';
        if(file_exists($controllerPath)){
            $controller = 'builder'.D_S.$themeBaseName;
            require $controllerPath;
        }else{
            die($lang['CH770']);
        }
    }
}else{
    die($lang['CH769']);
}