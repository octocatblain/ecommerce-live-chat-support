<?php
defined('ROOT_DIR') or die(header('HTTP/1.1 403 Forbidden'));

/**
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */

// --- Application Settings ---

//Define Application Name
define('APP_NAME','Pinky Live Chat');
define('HTML_APP_NAME','<span class="livechat icon-bubbles3"></span> Pinky <b>Chat</b>');

//Define Version Number of Application
define('VER_NO','1.2');

//Define App Prefix (Used for sessions, cookies etc..)
define('N_APP','pinky');

//Define Native Sign
define('NATIVE_SIGN','');

//Define Native Application Sign
define('NATIVE_APP_SIGN','');

//Set Default Controller
define('CON_MAIN','main');

//Set Default Error Controller
define('CON_ERR','error');

//Set Development Mode
define('DEVMODE', false);