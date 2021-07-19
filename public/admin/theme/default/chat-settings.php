<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $pageTitle; ?>
            <small><?php trans('Control panel',$lang['CH77']); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php adminLink(); ?>"><i class="<?php getAdminMenuIcon($controller,$menuBarLinks); ?>"></i> <?php trans('Admin',$lang['CH78']); ?></a></li>
            <li class="active"><a href="<?php adminLink($controller); ?>"><?php echo $pageTitle; ?></a> </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $subTitle; ?></h3>
            </div><!-- /.box-header ba-la-ji -->
            <form action="<?php adminLink($controller.'/save'); ?>" method="POST">
                <div class="box-body">

                    <?php if(isset($msg)) echo $msg; ?><br />
                    <div class="row" style="padding: 5px;">

                        <div class="col-md-6">
                            <?php

                            adminInputTxt('chat[chat_title]','chat_title', $lang['CH379'], $lang['CH380'], $chat['chat_title']);

                            adminInputFile('chat[default_avatar]', 'default_avatar', $lang['CH381'], $lang['CH382'], $chat['default_avatar']);

                            adminSelect('chat[tone]','tone',$lang['CH82'], array($lang['CH383'], $lang['CH384']), $chat['tone'], true);

                            adminSelect('chat[default_tone]', 'default_tone', $lang['CH385'], getAvailableTones($con), $chat['default_tone'], true);

                            adminInputNum('chat[width]', 'width', $lang['CH386'], $lang['CH387'], $chat['width']);

                            adminInputNum('chat[height]', 'height', $lang['CH388'], $lang['CH389'], $chat['height']);

                            adminInputNum('chat[mobile_detect]', 'mobile_detect', $lang['CH390'], $lang['CH391'], $chat['mobile_detect']);

                            adminSelect('chat[emoticons]', 'emoticons', $lang['CH825'], getAvailableEmoticonsPack($con), $chat['emoticons'], true);

                            ?>

                        </div>
                        <div class="col-md-6">
                            <?php

                            adminSelect('chat[side]','side',$lang['CH392'], array($lang['CH393'], $lang['CH394']), $chat['side'], true);

                            adminSelect('chat[file_share]','file_share',$lang['CH395'], array($lang['CH383'], $lang['CH396']) , $chat['file_share'], true);

                            adminInputNum('chat[upload_size]', 'upload_size', $lang['CH397'] . $uploadLimit, $lang['CH398'], formatBytesWithUnit($chat['upload_size'], 'MB', 0,true));

                            adminSelect('chat[upload_approve]','upload_approve',$lang['CH399'], array($lang['CH214'], $lang['CH213']), $chat['upload_approve'], true);

                            adminInputNum('chat[refresh]', 'refresh', $lang['CH400'], $lang['CH401'], $chat['refresh']);

                            adminInputNum('chat[analytics]', 'analytics', $lang['CH402'], $lang['CH403'], $chat['analytics']);

                           // adminSelect('chat[msg]','msg','Custom message boxes', array('Turn Off', 'Turn On') , $chat['msg'], true);

                            $statsOpt = array($lang['CH383'], $lang['CH404'], $lang['CH405']);
                            adminSelect('chat[stats]', 'stats', $lang['CH406'], $statsOpt, $chat['stats'], true);

                            ?>
                        </div>

                        <div class="col-md-12">
                            <br>
                            <div class="row myWell">

                                <h4 class="text-center"><?php trans('Default Message', $lang['CH407']); ?></h4>
                                <br>
                                <div class="col-md-6">
                                    <?php
                                    adminSelect('chat[dmsg]','dmsg',$lang['CH408'], array($lang['CH383'], $lang['CH384']) , $chat['dmsg'], true);
                                    adminInputTxt('chat[dname]','dname', $lang['CH409'], $lang['CH410'], $chat['dname']);
                                    ?>
                                </div>

                                <div class="col-md-6">
                                    <?php
                                    adminInputFile('chat[dlogo]', 'dlogo', $lang['CH411'], $lang['CH412'], $chat['dlogo']);
                                    adminTextArea('chat[dcontent]','dcontent', $lang['CH413'], $lang['CH414'], nlFix($chat['dcontent']), 'rows="1"');
                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="row myWell">
                                <h4 class="text-center"><?php trans('Pages where the widget should not be displayed?', $lang['CH415']); ?></h4>
                                <br>
                                <?php adminTextArea('chat[blacklist]','blacklist', $lang['CH416'], $lang['CH415'], nlFix($chat['blacklist']), 'rows="8"'); ?>
                            </div>
                            <br>
                                <div class="text-center">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
                                <a onclick="return confirm('<?php makeJavascriptStr($lang['CH417'], true); ?>');" href="<?php adminLink($controller.'/reset'); ?>" class="btn btn-warning"> <i class="fa fa-refresh" aria-hidden="true"></i> <?php trans('Reset to default', $lang['CH418']); ?></a>
                                <a href="<?php echo $builderLink; ?>" class="btn btn-info"> <i class="fa fa-cog" aria-hidden="true"></i> <?php trans('Customize Widget (Colors, Text Size etc..)', $lang['CH419']); ?></a>
                            </div>
                        </div>

                    </div>

                    <br />

                </div><!-- /.box-body -->
            </form>
        </div><!-- /.box -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php ob_start(); ?>

<link rel="stylesheet" href="<?php themeLink('plugins/fancybox/jquery.fancybox.min.css'); ?>" />
<script src="<?php themeLink('plugins/fancybox/jquery.fancybox.min.js'); ?>"></script>

<script>

    function selChange(){
        if($('#tone').val() == 0)
            $('#default_tone').attr("disabled","disabled");
        else
            $('#default_tone').removeAttr('disabled');

        if($('#dmsg').val() == 0){
            $('#dname').attr("disabled","disabled");
            $('#dlogo').attr("disabled","disabled");
            $('#dcontent').attr("disabled","disabled");
        }else{
            $('#dname').removeAttr("disabled");
            $('#dlogo').removeAttr("disabled");
            $('#dcontent').removeAttr("disabled");
        }

        if($('#file_share').val() == 0){
            $('#upload_size').attr("disabled","disabled");
            $('#upload_approve').attr("disabled","disabled");
        }else{
            $('#upload_size').removeAttr("disabled");
            $('#upload_approve').removeAttr("disabled");
        }
    }
    selChange();
    $('select').on('change', function() {selChange();});

    $('.iframe-btn').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'autoDimensions': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe'
    });

    function readURL(input,box){
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(box).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<style>.fancybox-content{ height: 500px !important; }</style>
<?php $contents = ob_get_contents(); ob_end_clean(); $footerAddArr[] = $contents; ?>