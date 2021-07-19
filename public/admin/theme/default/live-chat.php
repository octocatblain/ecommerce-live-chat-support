<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));


?>
<link href="<?php themeLink('plugins/jQueryUI/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php themeLink('plugins/slick/scrolltabs.min.css'); ?>" rel="stylesheet" type="text/css" />
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $pageTitle; ?>
            <small><?php trans('Control panel', $lang['CH77']); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php adminLink(); ?>"><i class="<?php getAdminMenuIcon($controller,$menuBarLinks); ?>"></i> <?php trans('Admin', $lang['CH78']); ?></a></li>
            <li class="active"><a href="<?php adminLink($controller); ?>"><?php echo $pageTitle; ?></a> </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-3half">

                <div class="box box-solid" id="onlineDataBox">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php trans('Online Users', $lang['CH79']); ?> <span id="onlineCount"></span></h3>

                        <div class="box-tools minTools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding" id="onlineData">
                        <div class="noOnline"><?php echo $lang['CH13']; ?></div>
                    </div>
                    <!-- /.box-body -->
                </div>

                <!-- /. box -->
                <div class="box box-solid" id="onlineDataOpBox">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php trans('Online Operators', $lang['CH80']); ?> <span id="adminCount"></span> </h3>

                        <div class="box-tools minTools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding" id="adminData">
                        <div class="noOnline"><?php echo $lang['CH13']; ?></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->

            <div class="col-sm-6" id="chatDataBox">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom chat-tab" id="tabs">

                    <ul class="nav nav-tabs chatTabs" id="chatTabs">

                    </ul>

                    <div class="tab-content">
                        <div id="masterChatdata" class="hide">

                        <div class="rainbowChat">
                        <div class="chat" id="{{userID}}">

                            <input type="hidden" class="ownerID" value="{{ownerID}}" />

                            <div class="chat-page">
                                <div class="page chat-history">

                                </div>

                                <div id="cannedMsg" class="canned-msg">
                                    <div class="canned-msg-content">
                                        <div onclick="setCannedMsg(1, this);" class="cannedWord1">
                                            <div data-temp="" class="cannedWord word1"></div>
                                            <div class="cannedData wordData1"></div>
                                        </div>
                                        <div onclick="setCannedMsg(2, this);" class="cannedWord2">
                                            <div data-temp="" class="cannedWord word2"></div>
                                            <div class="cannedData wordData2"></div>
                                        </div>
                                        <div onclick="setCannedMsg(3, this);" class="cannedWord3">
                                            <div data-temp="" class="cannedWord word3"></div>
                                            <div class="cannedData wordData3"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="chat-smiles" class="chat-smiles">
                                    <div class="chat-smiles-content">
                                        <?php foreach($emoticons[2] as $emoticon){
                                            if($emoticon[0])
                                                echo '<a onclick="selSmile(\''.$emoticon[3].'\', \'{{userID}}\')">'.$emoticon[2].'</a>';
                                        }?>
                                    </div>
                                </div>

                                <form class="chatForm" action="#" method="post">
                                    <div class="input-group">
                                        <div class="input-group-area"><input autocomplete="off" class="chatTxt" type="text" placeholder="<?php echo $lang['CH28']; ?>" autofocus></div>
                                        <div class="input-group-right">
                                            <img onclick="showSmiles('{{userID}}');" title="<?php echo $lang['CH29']; ?>" class="chatTxtIcon" alt="<?php echo $lang['CH29']; ?>" src="data:image/png;base64,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">
                                        </div>
                                        <?php if(isSelected($chatSettings['file_share'])){ ?>
                                        <div class="input-group-right">
                                            <img title="<?php echo $lang['CH30']; ?>" class="send-file-btn chatTxtIcon" alt="<?php echo $lang['CH30']; ?>" src="data:image/png;base64,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" />
                                            <input class="send-file hide" type="file">
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden">
                                </form>
                            </div>
                        </div>
                        </div>

                        </div>
                    </div>

                </div>

            </div>
            <!-- /.col -->


            <div class="col-sm-4half" id="userDetails">

                <div class="hide" id="masterAdminDetails">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php trans('Details', $lang['CH81']); ?></h3>
                            <div class="box-tools boxControls">
                                <?php if($eTone) { ?>
                                    <button title="<?php trans('Notification Sounds', $lang['CH82']); ?>" disabled class="btn btn-sm bg-blue2 btnDis" onclick="muteSound('{{chatID}}')"><i id="toneK{{chatID}}" class="fa fa-volume-up" aria-hidden="true"></i></button>
                                    <input type="hidden" value="1" id="tone{{chatID}}">
                                <?php } ?>
                                <?php if($cannedMsgBol) { ?>
                                    <button title="<?php trans('Canned Messages', $lang['CH83']); ?>" disabled class="btn btn-sm btn-warning btnDis" onclick="cannedBox('{{chatID}}')"><i id="cannedK{{chatID}}" class="glyphicon glyphicon-ok-sign" aria-hidden="true"></i></button>
                                    <input type="hidden" value="1" id="canned{{chatID}}">
                                <?php } ?>
                                <button disabled class="btn btn-sm btn-danger btnDis" onclick="closeTab('{{tabID}}')" > <i class="fa fa-user-times" aria-hidden="true"></i> &nbsp; <?php trans('End Chat',$lang['CH36']); ?></button>
                            </div>
                        </div>
                        <div class="box-body no-padding adminDetailsBody">

                            <div class="text-center userLoad">
                                <?php rainbowLoader($lang['CH13']); ?>
                            </div>

                            <div class="myprofile hide">
                                <br>
                                <img class="profile-user-img img-responsive img-circle" src="{{logo}}" alt="<?php trans('User profile picture', $lang['CH84']); ?>">
                                <h3 class="profile-username text-center">{{username}}</h3>
                            </div>
                            <table class="table hide">

                                <tr>
                                    <td><?php trans('Email', $lang['CH85']); ?></td>
                                    <td>{{email}}</td>
                                </tr>
                                <tr>
                                    <td><?php trans('Role', $lang['CH86']); ?></td>
                                    <td>{{role}}</td>
                                </tr>
                                <tr>
                                    <td><?php trans('Access', $lang['CH87']); ?></td>
                                    <td>{{access}}</td>
                                </tr>
                                <tr>
                                    <td><?php trans('Chat ID', $lang['CH88']); ?></td>
                                    <td>{{chatID}}</td>
                                </tr>
                                <tr>
                                    <td><?php trans('Location', $lang['CH89']); ?></td>
                                    <td>{{location}}</td>

                                </tr>
                                <tr>
                                    <td><?php trans('Browser', $lang['CH90']); ?></td>
                                    <td>{{browser}}</td>
                                </tr>

                                <tr>
                                    <td><?php trans('Platform', $lang['CH91']); ?></td>
                                    <td>{{platform}}</td>
                                </tr>

                                <tr>
                                    <td><?php trans('IP', $lang['CH92']); ?></td>
                                    <td>{{ip}}</td>
                                </tr>
                                <tr>
                                    <td><?php trans('User Agent', $lang['CH93']); ?></td>
                                    <td>{{ua}}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>

                <div class="hide" id="masterUserDetails">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php trans('Details',$lang['CH81']); ?></h3>
                        <div class="box-tools boxControls">
                            <?php if($eTone) { ?>
                            <button title="<?php trans('Notification Sounds',$lang['CH82']); ?>" disabled class="btn btn-sm bg-blue2 btnDis" onclick="muteSound('{{chatID}}')"><i id="toneK{{chatID}}" class="fa fa-volume-up" aria-hidden="true"></i></button>
                            <input type="hidden" value="1" id="tone{{chatID}}">
                            <?php } ?>
                            <?php if($cannedMsgBol) { ?>
                                <button title="<?php trans('Canned Messages',$lang['CH83']); ?>" disabled class="btn btn-sm btn-warning btnDis" onclick="cannedBox('{{chatID}}')"><i id="cannedK{{chatID}}" class="glyphicon glyphicon-ok-sign" aria-hidden="true"></i></button>
                                <input type="hidden" value="1" id="canned{{chatID}}">
                            <?php } ?>
                            <button disabled class="btn btn-sm btn-success btnDis" onclick="inviteUser()"> <i class="fa fa-user-times" aria-hidden="true"></i> &nbsp; <?php trans('Invite Operator', $lang['CH94']); ?></button>
                            <button disabled class="btn btn-sm btn-danger btnDis" onclick="closeTab('{{tabID}}')" > <i class="fa fa-user-times" aria-hidden="true"></i> &nbsp; <?php trans('End Chat',$lang['CH36']); ?></button>
                        </div>
                    </div>
                    <div class="box-body no-padding userDetailsBody">

                        <div class="text-center userLoad">
                            <?php rainbowLoader($lang['CH13']); ?>
                        </div>

                         <table class="table hide">
                            <tr>
                                <td><?php trans('Username',$lang['RF66']); ?></td>
                                <td>{{username}}</td>
                            </tr>

                             <tr>
                                 <td><?php trans('Email',$lang['CH85']); ?></td>
                                 <td>{{email}}</td>
                             </tr>
                             <tr>
                                 <td><?php trans('Chat ID',$lang['CH88']); ?></td>
                                 <td>{{chatID}}</td>
                             </tr>
                            <tr>
                                <td><?php trans('Location',$lang['CH89']); ?></td>
                                <td>{{location}}</td>

                            </tr>
                            <tr>
                                <td><?php echo $lang['CH90']; ?></td>
                                <td>{{browser}}</td>
                            </tr>

                            <tr>
                                <td><?php echo $lang['CH91']; ?></td>
                                <td>{{platform}}</td>
                            </tr>

                            <tr>
                                <td><?php trans('IP',$lang['CH92']); ?></td>
                                <td>{{ip}}</td>
                            </tr>
                             <tr>
                                 <td><?php trans('Screen', $lang['CH95']); ?></td>
                                 <td>{{screen}}</td>
                             </tr>
                            <tr>
                                <td><?php trans('User Agent',$lang['CH93']); ?></td>
                                <td>{{ua}}</td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-sm-4half" id="userPages">
                <div class="hide" id="masterUserPages">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php trans('Visitors Path', $lang['CH96']); ?></h3>

                        <div class="box-tools">

                        </div>
                    </div>
                    <div class="box-body no-padding pagesBody">

                        <div class="text-center userLoad">
                            <?php rainbowLoader($lang['CH13']); ?>
                        </div>
                        <div class="hide" id="tableData">{{tableData}}</div>
                    </div>
                    <!-- /.box-body -->
                </div>
                </div>
            </div>
            <!-- /.col -->

            <div class="clearfix"></div>

            <nav id="scroll-menu">
                <ul id="scroll-nav" class="nav nav-pills nav-inverse">
                    <li class="active">
                        <a onclick="loadMobilePage('chat', this);" class="scroll-link">
                            <span class="scroll-text"><?php trans('Chat', $lang['CH119']); ?></span>
                        </a>
                    </li>
                    <li class="">
                        <a onclick="loadMobilePage('details', this);" class="scroll-link">
                            <span class="scroll-text"><?php trans('Details',$lang['CH81']); ?></span>
                        </a>
                    </li>
                    <li class="">
                        <a onclick="loadMobilePage('users', this);" class="scroll-link">
                            <span class="scroll-text"><?php trans('Users', $lang['CH120']); ?></span>
                        </a>
                    </li>
                    <li class="">
                        <a onclick="loadMobilePage('operators', this);" class="scroll-link">
                            <span class="scroll-text"><?php trans('Operators', $lang['CH121']); ?></span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="col-md-12">
                <div class="box box-solid">
                    <div id="inviteData" class="hide"></div>
                </div>
            </div>

        </div>
        <!-- /.row -->
        <div>
        </div>

        <?php if($eTone) { ?>
        <audio id="myTone">
            <source src="#" type="audio/mpeg">
        </audio>
        <?php } ?>

    </section>
</div><!-- /.content-wrapper -->
<?php ob_start();
scriptLink('plugins/dynamic-tabs/dynamic-tabs.min.js',true);
scriptLink('plugins/jQueryUI/jquery-ui.min.js',true);
scriptLink('plugins/timeago/timeago.js',true);
scriptLink('plugins/slick/scrolltabs.min.js',true);
?>
<script type="text/javascript">

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

    <?php defineJs(array('template' => $chatTemplate['template'],
        'subTemplate' => $chatTemplate['subTemplate'],
        'emoticonsData' => json_encode($emoticons[3]),
        'defaultAvatar' => fixLink($chatSettings['default_avatar'], true),
        'tone' => $eTone,
        'tonePath' => $tonePath,
        'previewImgLoader' => themeLink('dist/img/small-load.gif',true),
        'axPath' => adminLink('chat-ajax', true),
        'users' => '{staff'.$adminInfo['AdminID'].': {id: "'.$adminInfo['AdminID'].'",name: "'.$adminInfo['AdminName'].'",avatarLink: "'.createLink($adminInfo['AdminLogo'], true, true).'"}}',
        'adminID' => $adminInfo['AdminID'],
        'chatRefreshTime' => intval($chatSettings['refresh']),
        'refreshAnalyticsTime' => intval($chatSettings['analytics']),
        'cannedMsgBol' => $cannedMsgBol,
        'cannedMsgArr' => json_encode($cannedMsgArr['result']),
        'cannedMsgType' => $cannedMsgType,
        'oopsTxt' => $lang['RF82'],
        'noDepTxt' => trans('It\'s not your department!', $lang['CH97'], true),
        'errSomTxt' => trans('Error: Something went wrong! Try to refresh the page!', $lang['CH98'], true),
        'unknownTxt' => trans('Unknown',$lang['CH75'],true),
        'alreadyCTxt' => trans('Already chat request sent!', $lang['CH99'], true),
        'inTxt' => trans('Invitation sent!', $lang['CH100'], true),
        'intTxt' => trans('Sending invitation failed!', $lang['CH101'], true),
        'selOpTxt' => trans('Select Operator!', $lang['CH102'], true),
        'inOpTxt' => trans('Invite Operator?', $lang['CH103'], true),
        'inUsTxt' => trans('Invite user?', $lang['CH104'], true),
        'areInUsTxt' => trans('Are you sure that you want to invite user to talk?', $lang['CH105'], true),
        'cancelTxt' => trans('Cancel',$lang['CH40'],true),
        'inviteTxt' => trans('Invite', $lang['CH106'], true),
        'unStartTxt' => trans('Unable to start chat!', $lang['CH107'], true),
        'joinTxt' => trans('Join chat?', $lang['CH108'], true),
        'useChatTxt' => trans('User chatting with another operator, are you want to join?', $lang['CH109'], true),
        'joinTxt1' => trans('Join', $lang['CH110'], true),
        'cantTxt' => trans('You can\'t chat yourself!', $lang['CH111'], true),
        'sureTxt' => trans('Are you sure?', $lang['CH112'], true),
        'sureTxt1' => trans('Are you sure that you want to end chat?',$lang['CH41'],true),
        'leaveTxt' => trans('Leave Talk?', $lang['CH113'], true),
        'leaveTxt1' => trans('Are you sure that you want to leave from talk?', $lang['CH114'], true),
        'failTxt' => trans('Failed to close!', $lang['CH115'], true),
        'fileTxt' => trans('File:', $lang['CH116'], true),
        'sizeTxt' => trans('Size:', $lang['CH117'], true),
        'errLoadTxt' => trans('Error Loading',$lang['CH56'],true),
        'previewTxt' => trans('Preview', $lang['CH118'], true),
        'loadingTxt' => trans('Loading...',$lang['CH13'],true),
        'downloadTxt' => trans('Download',$lang['CH58'],true),
        'upFailTxt' => trans('Upload Failed!',$lang['CH57'],true),
                   ));
    ?>
</script>
<?php
scriptLink('dist/js/chat.min.js',true);
$contents = ob_get_contents(); ob_end_clean(); $footerAddArr[] = $contents;
?>