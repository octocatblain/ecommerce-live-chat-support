<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2020 ProThemes.Biz
 *
 */
 
if(defined('CUSTOM_ROUTE')){
    if(!CUSTOM_ROUTE){
        stop("No Custom Router Enabled");
    }
}

//$custom_route['Route Path Name'] = "Controller Name";

//Basic Controller Routing
//$custom_route['contact'] = "contactus";

//Specific PointOut into Controller Routing
//$custom_route['product/update'] = "update";

//Specific Route Path into Specific PointOut Routing
//$custom_route['product'] = "product/sub";

//Dynamic PointOut Routing
//$custom_route['blog/[:any]'] = "product";

//Hide Real Controller Routing
//$custom_route['product'] = "error";

//Will block the page "example.com/page/privacy" and allow only "example.com/privacy"
//$custom_route['privacy'] = "page/privacy-policy";
//$custom_route['page/privacy'] = "error";

if(!defined('ADMIN_CON_DIR')) {

    //Front-End Panel
    $custom_route['lang/set'] = "ajax/lang";
    $custom_route['theme/set'] = "ajax/theme";
    $custom_route['templates/preview'] = "ajax/templates";
    $custom_route['theme/unset'] = "ajax/theme/unset";
    $custom_route['rainbow/track'] = "track";
    $custom_route['rainbow/master-js'] = "ajax/master-js";
    $custom_route['phpcap/reload'] = "ajax/phpcap/reload";
    $custom_route['phpcap/image'] = "ajax/phpcap/image";
    $custom_route['verify'] = "ajax/account-verify";
    $custom_route['widget-test'] = "other/test";
    $custom_route['upload/view'] = "chat-ajax/upload/view";
    $custom_route['ajax/contact'] = "chat-ajax/contact";

}else{

    //Admin Panel
    $custom_route['hello'] = "ajax/hello";
    $custom_route['file-manager'] = "ajax/exploder";
    $custom_route['logout'] = "ajax/logout";


}
