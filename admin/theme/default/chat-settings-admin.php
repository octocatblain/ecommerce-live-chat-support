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
            <form action="<?php adminLink($lController.'/admin/save'); ?>" method="POST">
                <div class="box-body">

                    <?php if(isset($msg)) echo $msg; ?><br />
                    <div class="row" style="padding: 5px;">

                        <div class="col-md-6">
                            <?php

                            adminInputFile('chat[default_avatar]', 'default_avatar', $lang['CH381'], $lang['CH382'], $chat['default_avatar']);

                            adminSelect('chat[tone]','tone',$lang['CH82'], array($lang['CH383'], $lang['CH384']), $chat['tone'], true);

                            adminSelect('chat[default_tone]', 'default_tone', $lang['CH385'], getAvailableTones($con), $chat['default_tone'], true);

                            adminSelect('chat[canned]','canned',$lang['CH83'], array($lang['CH383'], $lang['CH384']), $chat['canned'], true);

                            adminSelect('chat[canned_type]','canned_type',$lang['CH420'], array('1' => $lang['CH421'] , $lang['CH422']), $chat['canned_type'], true);

                            ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-info-circle"></i>
                                <b><?php echo $lang['CH455']; ?>: </b> <?php echo $lang['CH815']; ?></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php

                            adminSelect('chat[file_share]','file_share',$lang['CH395'], array($lang['CH383'], $lang['CH396']) , $chat['file_share'], true);

                            adminInputNum('chat[upload_size]', 'upload_size', $lang['CH397'] . $uploadLimit, $lang['CH398'], formatBytesWithUnit($chat['upload_size'], 'MB', 0,true));

                            adminInputNum('chat[refresh]', 'refresh', $lang['CH400'], $lang['CH401'], $chat['refresh']);

                            adminInputNum('chat[analytics]', 'analytics', $lang['CH402'], $lang['CH403'], $chat['analytics']);

                            adminSelect('chat[emoticons]', 'emoticons', $lang['CH825'], getAvailableEmoticonsPack($con), $chat['emoticons'], true);

                            adminSelect('chat[beep_sound]', 'default_tone', 'Beep sound when chat starts', getAvailableTones($con), $chat['beep_sound'], true);

                            ?>

                        </div>

                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
                            <a onclick="return confirm('<?php makeJavascriptStr($lang['CH417'], true); ?>');" href="<?php adminLink($lController.'/admin/reset'); ?>" class="btn btn-warning"> <i class="fa fa-refresh" aria-hidden="true"></i> <?php trans('Reset to default',$lang['CH418']); ?></a>
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

        if($('#file_share').val() == 0)
            $('#upload_size').attr("disabled","disabled");
        else
            $('#upload_size').removeAttr("disabled");

        if($('#canned').val() == 0)
            $('#canned_type').attr("disabled","disabled");
        else
            $('#canned_type').removeAttr('disabled');
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