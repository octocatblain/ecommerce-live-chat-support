<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php trans('Live Chat', $lang['CH76']); ?></title>
    <link rel="stylesheet" href="<?php themeLink('css/glyphicons.min.css'); ?>">
    <link rel="stylesheet" href="<?php themeLink('css/style.min.css'); ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">
    <style type="text/css">
       <?php
       defineCss(array(
        '#rainbow-chat' => array('font-size: '. $tO['chat']['size'].'px'),
        '#rainbow-chat h4' => array('font-size: '. $tO['chat']['hsize'].'px'),
        '#rainbow-chat h5' => array('font-size: '. $tO['chat']['usize'].'px'),
        '#rainbow-chat header' => array(cssBackground($tO['chat']['gradient'], $tO['chat']['hcolor1'],$tO['chat']['hcolor2']), 'color:'. $tO['chat']['label']),
        '#rainbow-mobile-chat header' => array(cssBackground($tO['chat']['gradient'], $tO['chat']['hcolor1'],$tO['chat']['hcolor2']), 'color:'. $tO['chat']['label']),
        '#rainbow-chat .chat' => array(cssBackground(0, $tO['chat']['background']), 'border-color: '.$tO['chat']['border']),
        '#rainbow-chat .login-btn' => array(cssBackground($tO['pBtn']['gradient'], $tO['pBtn']['hcolor1'],$tO['pBtn']['hcolor2']), 'color:'. $tO['pBtn']['color'], 'border-color: '.$tO['pBtn']['border']),
        '#rainbow-chat .login-btn:hover' => array(cssBackground($tO['pBtnh']['gradient'], $tO['pBtnh']['hcolor1'], $tO['pBtnh']['hcolor2']), 'color:'. $tO['pBtnh']['color'], 'border-color: '.$tO['pBtnh']['border']),
        '.btn-blue' => array('color: '. $tO['bBtn']['color'], 'background-color:rgb('.$tO['bBtn']['back'].')', 'border-color:rgb('.$tO['bBtn']['border'].')'),
        '.btn-blue:hover' => array('background-color:rgba('.$tO['bBtn']['back'].',0.6)', 'border-color:rgba('.$tO['bBtn']['border'].',0.6)'),
        '.btn-green' => array('color: '. $tO['gBtn']['color'], 'background-color:rgb('.$tO['gBtn']['back'].')', 'border-color:rgb('.$tO['gBtn']['border'].')'),
        '.btn-green:hover' => array('background-color:rgba('.$tO['gBtn']['back'].',0.6)', 'border-color:rgba('.$tO['gBtn']['border'].',0.6)'),
        '.btn-red' => array('color: '. $tO['rBtn']['color'], 'background-color:rgb('.$tO['rBtn']['back'].')', 'border-color:rgb('.$tO['rBtn']['border'].')'),
        '.btn-red:hover' => array('background-color:rgba('.$tO['rBtn']['back'].',0.6)', 'border-color:rgba('.$tO['rBtn']['border'].',0.6)'),
        '.btn-orange' => array('color: '. $tO['oBtn']['color'], 'background-color:rgb('.$tO['oBtn']['back'].')', 'border-color:rgb('.$tO['oBtn']['border'].')'),
        '.btn-orange:hover' => array('background-color:rgba('.$tO['oBtn']['back'].',0.6)', 'border-color:rgba('.$tO['oBtn']['border'].',0.6)'),
        '.btn-gray' => array('color: '. $tO['grayBtn']['color'], 'background-color:rgb('.$tO['grayBtn']['back'].')', 'border-color:rgb('.$tO['grayBtn']['border'].')'),
        '.btn-gray:hover' => array('background-color:rgba('.$tO['grayBtn']['back'].',0.6)', 'border-color:rgba('.$tO['grayBtn']['border'].',0.6)'),
        ));
       if($emoticons[4] != '') echo $emoticons[4];
       if(isset($themeOptions['custom']['css'])) { if($themeOptions['custom']['css'] != '') htmlPrint($themeOptions['custom']['css']); } ?>
    </style>
</head>
<body>

<div id="rainbow-mobile-chat" class="hide">
    <header class="clearfix">
        <span class="icon-bubbles hicon"></span>
    </header>
</div>

<div id="rainbow-chat">

    <header class="clearfix">
        <div class="chatControls">
            <h4><span class="glyphicons glyphicons-chat hicon"></span> <span id="pageTitle"> </span> </h4>
            <div id="chatOptBtn" title="Options" class="chatBtn chatSetBtn">
                <span class="glyphicons glyphicons-settings myicons"></span>
            </div>
            <div title="<?php trans('Minimize', $lang['CH17']); ?>" class="chatBtn chatCloseBtn">
                <span class="glyphicons glyphicons-chevron-down myicons"></span>
            </div>
            <div id="chatMobileBtn" title="<?php trans('Chat Window Close', $lang['CH18']); ?>" class="chatBtn chatMinBtn">
                <span class="icon-cross myicons"></span>
            </div>
            <div id="chatMinBtn" title="<?php trans('Full Screen Minimize', $lang['CH19']); ?>" class="chatBtn chatMinBtn">
                <span class="glyphicons glyphicons-resize-small myicons"></span>
            </div>
        </div>
    </header>

    <div class="chat">

        <div class="page login-page">

            <form id="chatLogin" action="#" method="post">

                <fieldset>
                    <p class="login-title"><?php trans('Introduce yourself to start chat', $lang['CH20']); ?></p>

                    <div class="avatarBox">
                        <a class="login-btn arrow" id="avatarLeft"><span class="glyphicons glyphicons-chevron-left"></span></a>
                        <img width="40" height="40" class="avatar" id="userAvatar" src="<?php echo $defaultAvatar; ?>">
                        <a class="login-btn arrow" id="avatarRight"><span class="glyphicons glyphicons-chevron-right"></span></a>
                    </div>

                    <input value="<?php echo $remName; ?>" class="login-input" id="lname" type="text" placeholder="<?php echo $lang['CH21']; ?>">
                    <div class="errTxt"> <span id="lname-err"><?php echo $lang['CH23']; ?></span> </div>

                    <input value="<?php echo $remEmail; ?>" class="login-input" id="lemail" type="text" placeholder="<?php echo $lang['CH22']; ?>">
                    <div class="errTxt"> <span id="lemail-err"><?php echo $lang['CH24']; ?></span> </div>

                    <select class="login-input" id="lhelp">
                        <option value="0" selected disabled><?php echo $lang['CH25']; ?></option>
                        <?php foreach($departments as $key => $val)
                            echo '<option value="'.$key.'">'.$val.'</option>';
                        ?>
                    </select>

                    <div class="errTxt"> <span id="lhelp-err"><?php echo $lang['CH26']; ?></span> </div>

                    <div class="login-btnbox">
                        <input id="loginBtn" class="login-btn disabled" type="submit" value="<?php trans('Start Chatting', $lang['CH27']); ?>">
                    </div>
                </fieldset>

            </form>

        </div>

        <div class="chat-page">
            <div class="page chat-history" id="chat-history">

            </div>

            <div class="hide" id="default-messages">
                <?php if(isSelected($chatSettings['dmsg'])){ ?>
                <div class="chat-message clearfix" data-user="staff1">

                    <img src="<?php fixLink($chatSettings['dlogo']); ?>" alt="" width="40" height="40">
                    <div class="chat-message-content clearfix">
                        <span class="chat-time caltime" id="dMsgContent" data-timeago="<?php echo $chatStartTime; ?>"></span>
                        <h5><?php echo $chatSettings['dname']; ?></h5>
                        <p><?php echo $chatSettings['dcontent']; ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div id="chat-smiles" class="chat-smiles">
                <div class="chat-smiles-content">
                    <?php foreach($emoticons[2] as $emoticon){
                        if($emoticon[0])
                            echo '<a onclick="selSmile(\''.$emoticon[3].'\')">'.$emoticon[2].'</a>';
                    }?>
                </div>
            </div>

            <form id="chaForm" action="#" method="post">

                <fieldset>

                    <div class="input-group">

                        <div class="input-group-area"><input autocomplete="off" id="chatTxt" type="text" placeholder="<?php trans('Type your message...',$lang['CH28']); ?>" autofocus></div>
                        <div class="input-group-right">
                            <img id="smile-btn" onclick="showSmiles();" title="<?php trans('Insert an emoji', $lang['CH29']); ?>" class="chatTxtIcon" alt="<?php trans('Insert an emoji', $lang['CH29']); ?>" src="data:image/png;base64,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">
                        </div>
                        <?php if(isSelected($chatSettings['file_share'])){ ?>
                        <div class="input-group-right">
                            <img id="send-file-btn" title="<?php trans('Send a File', $lang['CH30']); ?>" class="chatTxtIcon" alt="<?php trans('Send a File', $lang['CH30']); ?>" src="data:image/png;base64,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" />
                            <input class="hideImp" id="send-file" type="file">
                        </div>
                        <?php } ?>
                    </div>
                    <input type="hidden">

                </fieldset>

            </form>
        </div>

        <div class="page settings-page">
            <div class="triangle"></div>

            <div class="card" id="options">
                <div class="card-header text-center"><span class="card-title"> <?php trans('Options', $lang['CH31']); ?></span></div>

                <div class="card-body text-center">
                    <a id="fullScreenBtn" class="btn btn-sm btn-blue opBtn" onclick="return fullScreen();"> <span class="glyphicons glyphicons-fullscreen"></span> <?php trans('Toggle fullscreen', $lang['CH32']); ?></a>
                    <?php if($eTone){ ?>
                    <a class="btn btn-sm btn-orange opBtn" onclick="return muteSound();"> <span id="toneK" class="glyphicons glyphicons-volume-up"></span> <span id="toneTxt"><?php trans('Mute Sound', $lang['CH33']); ?></span></a>
                    <?php } ?>

                    <a id="printChat" class="btn btn-sm btn-green opBtn <?php echo $disabledClass; ?>"> <span class="glyphicons glyphicons-print"></span> <?php trans('Print Chat', $lang['CH34']); ?></a>

                    <a id="updateUserBtn" class="btn btn-sm btn-gray opBtn <?php echo $disabledClass; ?>" onclick="return updateUserInfo(3);"> <span class="glyphicons glyphicons-user"></span> <?php trans('Update Details', $lang['CH35']); ?></a>

                    <a id="closeChatBtn" class="btn btn-sm btn-red opBtn <?php echo $disabledClass; ?>" onclick="return userEndChat(2);"> <span class="glyphicons glyphicons-log-out"></span> <?php trans('End Chat', $lang['CH36']); ?></a>
                </div>
            </div>

            <div class="card hide" id="updateDetails">
                <div class="card-header text-center"><span class="card-title"> <?php trans('Update Details', $lang['CH35']); ?> </span></div>
                <div class="card-body text-center">
                    <p class="pad3">

                        <b class="text-left"><?php echo $lang['CH37']; ?> </b>
                        <input value="<?php echo $userData['userName']; ?>" class="login-input" id="uname" type="text" placeholder="<?php trans('Your Name',$lang['CH21']); ?>">
                        <div class="errTxt"> <span id="uname-err"><?php trans('Please enter a valid name.',$lang['CH23']); ?></span> </div>

                        <b class="text-left"><?php echo $lang['CH38']; ?> </b>
                        <input <?php echo $userData['updateUserEmail']; ?> value="<?php echo $userData['userEmail']; ?>" class="login-input" id="uemail" type="text" placeholder="<?php trans('Your Email ID',$lang['CH22']); ?>">
                        <div class="errTxt"> <span id="uemail-err"><?php trans('Please enter a valid email.',$lang['CH24']); ?></span> </div>

                    </p>
                    <div>
                        <button onclick="return updateUserInfo(1);" class="btn btn-sm btn-red"> <span class="glyphicons glyphicons-ok"></span> <?php echo $lang['CH39']; ?> </button>
                        <button onclick="return updateUserInfo(0);" class="btn btn-sm btn-green"> <span class="glyphicons glyphicons-remove"></span> <?php echo $lang['CH40']; ?> </button>
                    </div>
                </div>
            </div>

            <div class="card hide" id="endChat">
                <div class="card-header text-center"><span class="card-title"> <?php echo $lang['CH36']; ?></span></div>
                <div class="card-body text-center">
                    <p class="pad3"><?php trans('Are you sure that you want to end chat?', $lang['CH41']); ?></p>
                    <div>
                        <button onclick="return userEndChat(1);" class="btn btn-sm btn-red"> <span class="glyphicons glyphicons-ok"></span> <?php echo $lang['CH36']; ?></button>
                        <button onclick="return userEndChat(0);" class="btn btn-sm btn-green"> <span class="glyphicons glyphicons-remove"></span> <?php echo $lang['CH40']; ?></button>
                    </div>
                </div>
            </div>

        </div>



        <div id="chatRateBox">
            <div class="card">
                <div class="card-header text-center"><span class="card-title"> <?php trans('Chat Ended', $lang['CH42']); ?></span></div>
                <div class="card-body text-center">
                    <p class="pad3"><?php trans('How do you rate your customer service experience?', $lang['CH43']); ?></p>

                    <div class="chatRate">
                        <div class="chatCol" onclick="return chatRate(this, 0);">
                            <span class="chaticons icon-sad2"></span>
                            <div class="txFix"><?php echo $lang['CH44']; ?></div>
                        </div>
                        <div class="chatCol" onclick="return chatRate(this, 1);">
                            <span class="chaticons icon-neutral2"></span>
                            <div><?php echo $lang['CH45']; ?></div>
                        </div>
                        <div class="chatCol" onclick="return chatRate(this, 2);">
                            <span class="chaticons icon-smile2"></span>
                            <div class="txFix"><?php echo $lang['CH46']; ?></div>
                        </div>
                    </div>

                    <div class="chatRateMsg"> <span class="icon-checkmark"></span> <?php echo $lang['CH47']; ?></div>

                    <div>
                        <button onclick="return activatePage('login');" class="btn btn-sm btn-green"> <span class="glyphicons glyphicons-chat"></span> <?php echo $lang['CH48']; ?></button>
                        <button id="transcriptBtn" onclick="return sendTranscript();" class="btn btn-sm btn-blue"> <span id="transcriptIcon" class="glyphicons glyphicons-envelope"></span> <?php echo $lang['CH49']; ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="page contact-page">

            <fieldset>

                <div id="contactStep1">
                    <p class="login-title"><?php echo $lang['CH50']; ?></p>
                    <input class="login-input" id="cname" type="text" placeholder="<?php echo $lang['CH21']; ?>">
                    <div class="errTxt"> <span id="cname-err"><?php echo $lang['CH23']; ?></span> </div>

                    <input class="login-input" id="cemail" type="text" placeholder="<?php echo $lang['CH22']; ?>">
                    <div class="errTxt"> <span id="cemail-err"><?php echo $lang['CH24']; ?></span> </div>

                    <textarea onresize="false" class="login-input" id="cmsg" placeholder="<?php echo $lang['CH51']; ?>"></textarea>
                    <div class="errTxt"> <span id="cmsg-err"><?php echo $lang['CH52']; ?></span> </div>

                    <div class="login-btnbox">
                        <input id="contactBtn" onclick="return contactFn(true);" class="login-btn" type="submit" value="<?php echo $lang['CH53']; ?>">
                    </div>

                </div>

                <?php
                if ($cap_contact)
                    echo '<div id="contactStep2"><br><br>' . $captchaCode . '<div class="login-btnbox"><input id="contactBtn" onclick="return contactFn(false);" class="login-btn" type="submit" value="'.$lang['CH53'].'"></div> </div>';
                ?>

                <div id="contactStep3">
                    <br><br>
                    <div class="cLoad">
                        <img alt="<?php echo $lang['CH13']; ?>" title="<?php echo $lang['CH13']; ?>" src="<?php echo $previewImgLoader; ?>" />
                        <span><?php echo $lang['CH13']; ?></span>
                    </div>

                    <div class="cSuccess hide">
                        <div class="contactMsg contactSuccess">
                           <span class="glyphicons glyphicons-ok"></span>  &nbsp; <?php echo $lang['RF27']; ?>
                        </div>
                        <br>
                        <p>
                            <?php echo $lang['CH54']; ?>
                        </p>
                    </div>

                    <div class="cFailed hide">
                        <div class="contactMsg contactFailed">
                            <span class="glyphicons glyphicons-remove"></span>  &nbsp; <?php echo $lang['RF28']; ?>
                        </div>

                        <div class="login-btnbox">
                            <input id="tryContactBtn" class="login-btn" type="submit" value="<?php echo $lang['CH55']; ?>">
                        </div>
                    </div>

                </div>

            </fieldset>

        </div>
        <?php if($eTone) { ?>
            <audio id="myTone">
                <source src="#" type="audio/mpeg">
            </audio>
            <?php $myTone = 1; if(issetSession('tone')) $myTone = getSession('tone'); ?>
            <input type="hidden" value="<?php echo $myTone; ?>" id="tone">
        <?php } ?>
    </div>

</div>

<script type="text/javascript">
        <?php defineJs(array(
                'chatOpen' => $chatWinStats,
                'template' => $chatTemplate['template'],
                'subTemplate' => $chatTemplate['subTemplate'],
                'activePage'  => $activePage,
                'pageTitle'  =>  shortCodeFilter($chatSettings['chat_title']),
                'emoticonsData' => json_encode($emoticons[3]),
                'availableAvatars'  => $avatars[1],
                'arrowCount' => $arrowCount,
                'axPath' => createLink('chat-ajax',true),
                'frAxPath' => createLink('ajax',true),
                'baseUrl' => $baseURL,
                'chatID' => $chatID,
                'userID' => $userID,
                'tone' => $eTone,
                'tonePath' => $tonePath,
                'chatRefreshTime' => $chatSettings['refresh'],
                'trackLink' => createLink('track',true,true),
                'analyticsUpdateTime' => intval($chatSettings['analytics']),
                'analyticsUpdateLink' => createLink('chat-ajax/analytics/update',true,true),
                'previewImgLoader' => $previewImgLoader,
                'defaultAvatar' => fixLink($chatSettings['default_avatar'], true),
                'imageVr' => trans('Please verify your image verification',$lang['RF29'],true),
                'capCodeWrg' => trans('Your image verification code is wrong!',$lang['RF4'],true),
                'capRefresh' => trans('Loading...', $lang['CH13'], true),
                'endTxt' => trans('Chat closed by user', $lang['CH14'], true),
                'transSus' => trans('Chat transcript sent successfully', $lang['CH15'], true),
                'transFail' => trans('Failed to sent chat transcript', $lang['CH16'], true),
                'loadTxt' => trans('Loading...',$lang['CH13'],true),
                'errLoadTxt' => trans('Error Loading', $lang['CH56'], true),
                'upErrTxt' => trans('Upload Failed!', $lang['CH57'], true),
                'downloadTxt' => trans('Download', $lang['CH58'], true),
                'someErrTxt' => trans('Something Went Wrong! Contact Support!',$lang['RF91'],true),
                'waitApTxt' => trans('Waiting for approval...', $lang['CH59'], true),
                'uploadingTxt' => trans('Uploading...', $lang['CH60'], true),
                'unknownTxt' => trans('Unknown', $lang['CH75'], true),
                'users' => ($activePage === 'chat' ? '{user'.$userData['userID'].': {id: "'.$userData['userID'].'",name: "'.$userData['userName'].'",avatarLink: "'.$userData['userImage'].'"}}' : '{}'),
            )
        );
        ?>
        var locale = function(number, index, total_sec) {
            return [
                ['<?php makeJavascriptStr($lang['CH61'],true); ?>', 'right now'],
                ['%s <?php makeJavascriptStr($lang['CH62'], true); ?>', 'in %s seconds'],
                ['1 <?php makeJavascriptStr($lang['CH63'], true); ?>', 'in 1 minute'],
                ['%s <?php makeJavascriptStr($lang['CH64'], true); ?>', 'in %s minutes'],
                ['1 <?php makeJavascriptStr($lang['CH65'], true); ?>', 'in 1 hour'],
                ['%s <?php trans('hours ago', $lang['CH66']); ?>', 'in %s hours'],
                ['1 <?php trans('day ago', $lang['CH67']); ?>', 'in 1 day'],
                ['%s <?php trans('days ago', $lang['CH68']); ?>', 'in %s days'],
                ['1 <?php trans('week ago', $lang['CH69']); ?>', 'in 1 week'],
                ['%s <?php trans('weeks ago', $lang['CH70']); ?>', 'in %s weeks'],
                ['1 <?php trans('month ago', $lang['CH71']); ?>', 'in 1 month'],
                ['%s <?php trans('months ago', $lang['CH72']); ?>', 'in %s months'],
                ['1 <?php trans('year ago', $lang['CH73']); ?>', 'in 1 year'],
                ['%s <?php trans('years ago', $lang['CH74']); ?>', 'in %s years']
            ][index];
        };
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="/__/firebase/8.7.1/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="/__/firebase/8.7.1/firebase-analytics.js"></script>

<!-- Initialize Firebase -->
<script src="/__/firebase/init.js"></script>

<script src="<?php themeLink('js/jquery-2.2.4.min.js'); ?>"></script>
<script src="<?php themeLink('js/chat.min.js'); ?>"></script>

</body>
</html>