<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

//Live Widget Test
if($pointOut == 'test'){
    $controller = 'test';
    $pageTitle = trans('Widget Test', $lang['CH10'], true);

    $inline = false;
    if(isset($args[0])) {
        if($args[0] === 'inline') {
            $pageTitle = trans('Widget Test Inline', $lang['CH11'], true);
            $inline = true;
        }
    }
}