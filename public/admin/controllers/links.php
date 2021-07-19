<?php
defined('ADMIN_LINKS') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */
 
$menuBarLinks = array();

//Link Format     -> 0) Show Or Hide 1) Link Name  2) Link Path  3) Link Icon 4) Sub Link Array
//Sub Link Format -> 0) Link Name    1) Link Path  2) Link Icon  3) Show Or Hide

$menuBarLinks['0'] = array(true, trans('MAIN NAVIGATION', $lang['CH736'], true),'header-li','fa fa-lock');
$menuBarLinks['1'] = array(true, trans('Dashboard',$lang['RF103'],true),'dashboard','fa fa-dashboard');
$menuBarLinks['2'] = array(true, trans('Basic Settings',$lang['CH170'],true),'#','fa fa-cogs', array(
                        array(trans('Site Settings',$lang['CH737'],true),'basic-settings','fa fa-paw'),
                        array(trans('Maintenance',$lang['CH738'],true),'maintenance','fa fa-paw'),
                        array(trans('Captcha Protection',$lang['CH195'],true),'captcha-protection','fa fa-paw'),
                        array(trans('Ban IP Address',$lang['CH216'],true),'ban-ip-address','fa fa-paw'),
                        array(trans('Adblock Detection',$lang['CH235'],true),'adblock-detection','fa fa-paw'),
                  ));

$menuBarLinks['3'] = array(true, trans('Chat App',$lang['CH739'],true),'#','fa fa-comments', array(
                        array(trans('Live Chat',$lang['CH76'],true),'live-chat','fa fa-paw'),
                        array(trans('Chat AJAX Calls',$lang['CH740'],true),'chat-ajax?::all','fa fa-paw',false),
                        array(trans('Get Widget Code',$lang['CH250'],true),'get-widget-code','fa fa-paw'),
                        array(trans('Chat History',$lang['CH274'],true),'chat-history?::all','fa fa-paw'),
                        array(trans('Canned Messages',$lang['CH83'],true),'canned-messages','fa fa-paw'),
                        array(trans('Avatars',$lang['CH293'],true),'avatars','fa fa-paw'),
                        array(trans('Emoticons',$lang['CH310'],true),'emoticons','fa fa-paw'),
                        array(trans('Notification Tunes',$lang['CH741'],true),'notifications','fa fa-paw'),
                        array(trans('Departments',$lang['CH361'],true),'departments','fa fa-paw'),
                        array(trans('Settings (Front End)',$lang['CH742'],true),'chat-settings','fa fa-paw'),
                        array(trans('Settings (Back End)',$lang['CH743'],true),'chat-settings/admin','fa fa-paw'),
));

$menuBarLinks['4'] = array(true, trans('Analytics',$lang['CH141'],true),'#','fa fa-bar-chart', array(
                        array(trans('Overview',$lang['CH527'],true),'analytics-overview','fa fa-paw'),
                        array(trans('Visitors Log',$lang['CH744'],true),'visitors-log','fa fa-paw'),
                        array(trans('Who\'s Online',$lang['CH643'],true),'who-online','fa fa-paw'),
                ));


$menuBarLinks['6'] = array(true, trans('Email Setup',$lang['CH745'],true),'#','fa fa-envelope-o', array(
                        array(trans('Send Email',$lang['CH621'],true),'send-email','fa fa-paw'),
                        array(trans('Email Templates',$lang['CH618'],true),'mail-templates','fa fa-paw'),
                        array(trans('Mail Settings',$lang['CH587'],true),'mail-settings','fa fa-paw'),
                        
                  ));
$menuBarLinks['7'] = array(true, trans('Administrators',$lang['CH746'],true),'#','fa fa-server', array(
                        array(trans('My Profile',$lang['RF83'],true),'admin-profile','fa fa-paw'),
                        array(trans('Add New Admin',$lang['CH546'],true),'admin-accs/add','fa fa-paw'),
                        array(trans('Admin Accounts',$lang['CH747'],true),'admin-accs','fa fa-paw'),
                        array(trans('Admin Groups',$lang['CH566'],true),'admin-groups','fa fa-paw'),
                ));
$menuBarLinks['8'] = array(true, trans('Users',$lang['CH120'],true),'manage-users','fa fa-group');

$menuBarLinks['10'] = array(true, trans('Interface',$lang['CH748'],true),'#','fa fa-desktop', array(
                        array(trans('Create New Language',$lang['CH703'],true),'language-editor/add','fa fa-paw'),
                        array(trans('Language Editor',$lang['CH693'],true),'language-editor','fa fa-paw'),
                        array(trans('Manage Themes',$lang['CH665'],true),'manage-themes','fa fa-paw'),
                        array(trans('Interface Settings',$lang['CH719'],true),'interface','fa fa-paw'),
                  ));

$menuBarLinks['12'] = array(true, trans('Miscellaneous',$lang['CH503'],true),'miscellaneous','fa fa-bolt');

$menuBarLinks['13'] = array(true, trans('Addons',$lang['CH749'],true),'#','fa fa-plus-circle', array(
                        array(trans('Install Addons',$lang['CH480'],true),'manage-addons','fa fa-paw'),
                        array(trans('Shop Addons',$lang['CH478'],true),'shop-addons','fa fa-paw'),
                        array(trans('Process Installation',$lang['CH750'],true),'process-addon','fa fa-paw',false),
                  ));
$menuBarLinks['14'] = array(true, trans('Cron Job',$lang['CH461'],true),'cron-job','fa fa-cogs');
$menuBarLinks['15'] = array(true, trans('Error Log Viewer',$lang['CH751'],true),'error-log-viewer','fa fa-exclamation-triangle');
$menuBarLinks['16'] = array(true, trans('PHP Information',$lang['CH448'],true),'php-info-viewer','fa fa-info-circle');
  
$menuBarLinks['17'] = array(true, trans('ADVANCED FEATURES (BETA)',$lang['CH752'],true),'header-li','fa fa-lock');
$menuBarLinks['18'] = array(true, trans('File Manager',$lang['CH753'],true),'file-manager','fa fa-file');
$menuBarLinks['18A'] = array(true, 'Database Editor','exploder/db-editor.php','fa fa-database');
$menuBarLinks['19'] = array(true, trans('Database Backup',$lang['CH440'],true),'database-backup','fa fa-hdd-o');
$menuBarLinks['20'] = array(true, trans('License Change',$lang['CH424'],true),'license-change','fa fa-key');