<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
?>
<style>
    .nav-tabs-custom  .tab-content {
        padding: 25px !important;
    }
    .input-group .input-group-addon {
        background-color: #eee;
    }
    .input-group {
        margin-bottom: 15px;
    }
    .row{
        padding: 25px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $pageTitle; ?>
            <small><?php trans('Control panel',$lang['CH77']); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php adminLink(); ?>"> <?php trans('Admin',$lang['CH78']); ?></a></li>
            <li class="active"><a href="<?php adminLink($controller); ?>"><?php echo $pageBuilderTitle; ?></a></li>
            <li class="active"><a href="<?php adminLink($controller); ?>"><?php echo $pageTitle; ?></a> </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp; <?php trans('General', $lang['CH772']); ?></a></li>
                <li class=""><a href="#addCss" data-toggle="tab"><i class="fa fa-plus-square-o" aria-hidden="true"></i>&nbsp; <?php trans('Add Custom Stylesheet', $lang['CH773']); ?></a></li>
            </ul>
            <form onsubmit="return removeDisabled();" action="<?php echo $currentLink . '/save' ?>" method="POST">
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <br />
                    <?php if(isset($msg)) echo $msg; ?>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="well">
                                <h4 class="text-center"><?php trans('Chat Widget',$lang['CH774']); ?></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            adminColorPicker('to[chat][background]', null, $lang['CH775'], $lang['CH776'], $to['chat']['background']);
                            adminInputNum('to[chat][size]', null, $lang['CH777'], $lang['CH778'], $to['chat']['size']);

                            adminColorPicker('to[chat][label]', null, $lang['CH779'], $lang['CH780'], $to['chat']['label']);
                            adminColorPicker('to[chat][border]', null, $lang['CH781'], $lang['CH782'], $to['chat']['border']);
                            adminInputNum('to[chat][usize]', null, $lang['CH783'], $lang['CH784'], $to['chat']['usize']);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php

                            adminSelect('to[chat][gradient]', 'cGradient', $lang['CH785'], array($lang['CH214'],$lang['CH213']), $to['chat']['gradient'], true);

                            adminColorPicker('to[chat][hcolor1]', null, $lang['CH786'], $lang['CH788'], $to['chat']['hcolor1']);
                            adminColorPicker('to[chat][hcolor2]', null, $lang['CH786'].' 2 ('.$lang['CH787'].')', $lang['CH788'], $to['chat']['hcolor2']);

                            adminInputNum('to[chat][hsize]', null, $lang['CH790'], $lang['CH789'], $to['chat']['hsize']);
                            ?>
                        </div>
                    </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="well">
                                <h4 class="text-center"><?php trans('Primary Button', $lang['CH791']); ?></h4>
                                <?php
                                adminSelect('to[pBtn][gradient]', 'pGradient', $lang['CH785'], array($lang['CH214'],$lang['CH213']), $to['pBtn']['gradient'], true);

                                adminColorPicker('to[pBtn][hcolor1]', null, $lang['CH786'], $lang['CH788'], $to['pBtn']['hcolor1']);
                                adminColorPicker('to[pBtn][hcolor2]', null, $lang['CH786'].' 2 ('.$lang['CH787'].')', $lang['CH788'], $to['pBtn']['hcolor2']);
                                adminColorPicker('to[pBtn][border]', null, $lang['CH792'], $lang['CH788'], $to['pBtn']['border']);
                                adminColorPicker('to[pBtn][color]', null, $lang['CH779'], $lang['CH788'], $to['pBtn']['color']);
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="well">
                                <h4 class="text-center"><?php trans('Primary Button (Hover)', $lang['CH793']); ?></h4>
                                <?php
                                adminSelect('to[pBtnh][gradient]', 'phGradient', $lang['CH785'], array($lang['CH214'],$lang['CH213']), $to['pBtnh']['gradient'], true);

                                adminColorPicker('to[pBtnh][hcolor1]', null, $lang['CH786'], $lang['CH788'], $to['pBtnh']['hcolor1']);
                                adminColorPicker('to[pBtnh][hcolor2]', null, $lang['CH786'].' 2 ('.$lang['CH787'].')', $lang['CH788'], $to['pBtnh']['hcolor2']);
                                adminColorPicker('to[pBtnh][border]', null, $lang['CH792'], $lang['CH788'], $to['pBtnh']['border']);
                                adminColorPicker('to[pBtnh][color]', null, $lang['CH779'], $lang['CH788'], $to['pBtnh']['color']);
                                ?>
                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="well">
                                <h4 class="text-center"><?php trans('Blue Button', $lang['CH795']); ?></h4>
                                <?php
                                adminColorPicker('to[bBtn][back]', null, $lang['CH794'], $lang['CH776'], 'rgb('.$to['bBtn']['back'].')');
                                adminColorPicker('to[bBtn][border]', null, $lang['CH792'], $lang['CH782'], 'rgb('.$to['bBtn']['border'].')');
                                adminColorPicker('to[bBtn][color]', null, $lang['CH779'], $lang['CH780'], $to['bBtn']['color']);
                                ?>
                            </div>

                            <div class="well">
                                <h4 class="text-center"><?php trans('Grey Button', $lang['CH796']); ?></h4>
                                <?php
                                adminColorPicker('to[grayBtn][back]', null, $lang['CH794'], $lang['CH776'], 'rgb('.$to['grayBtn']['back'].')');
                                adminColorPicker('to[grayBtn][border]', null, $lang['CH792'], $lang['CH782'], 'rgb('.$to['grayBtn']['border'].')');
                                adminColorPicker('to[grayBtn][color]', null, $lang['CH779'], $lang['CH780'], $to['grayBtn']['color']);
                                ?>
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="well">
                                <h4 class="text-center"><?php trans('Green Button', $lang['CH797']); ?></h4>
                                <?php
                                adminColorPicker('to[gBtn][back]', null, $lang['CH794'], $lang['CH776'], 'rgb('.$to['gBtn']['back'].')');
                                adminColorPicker('to[gBtn][border]', null, $lang['CH792'], $lang['CH782'], 'rgb('.$to['gBtn']['border'].')');
                                adminColorPicker('to[gBtn][color]', null, $lang['CH779'], $lang['CH780'], $to['gBtn']['color']);
                                ?>
                            </div>

                            <div class="well">
                                <h4 class="text-center"><?php trans('Orange Button', $lang['CH798']); ?></h4>
                                <?php
                                adminColorPicker('to[oBtn][back]', null, $lang['CH794'], $lang['CH776'], 'rgb('.$to['oBtn']['back'].')');
                                adminColorPicker('to[oBtn][border]', null, $lang['CH792'], $lang['CH782'], 'rgb('.$to['oBtn']['border'].')');
                                adminColorPicker('to[oBtn][color]', null, $lang['CH779'], $lang['CH780'], $to['oBtn']['color']);
                                ?>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="well">
                                <h4 class="text-center"><?php trans('Red Button', $lang['CH799']); ?></h4>
                                <?php
                                adminColorPicker('to[rBtn][back]', null, $lang['CH794'], $lang['CH776'], 'rgb('.$to['rBtn']['back'].')');
                                adminColorPicker('to[rBtn][border]', null, $lang['CH792'], $lang['CH782'], 'rgb('.$to['rBtn']['border'].')');
                                adminColorPicker('to[rBtn][color]', null, $lang['CH779'], $lang['CH780'], $to['rBtn']['color']);
                                ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
                                <a onclick="return confirm('<?php echo $lang['CH417']; ?>');" href="<?php echo $currentLink . '/reset' ?>" class="btn btn-warning"> <i class="fa fa-refresh" aria-hidden="true"></i> <?php trans('Reset to default',$lang['CH418']); ?></a>
                            </div>
                        </div>

                    </div>

                    <br />
                </div>

                <div class="tab-pane <?php echo $page2; ?>" id="addCss">
                    <br />
                    <?php adminTextArea('to[custom][css]', 'css', $lang['CH800'].':', '.test{ width: 20px; }', htmlspecialchars_decode($to['custom']['css']), ' rows="15" '); ?>
                    <br />

                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
                            <a onclick="return confirm('<?php echo $lang['CH417']; ?>');" href="<?php echo $currentLink . '/reset' ?>" class="btn btn-warning"> <i class="fa fa-refresh" aria-hidden="true"></i> <?php trans('Reset to default',$lang['CH418']); ?></a>
                        </div>
                    </div>
                    <br /> <br /> <br />
                </div>
            </div>
            </form>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php ob_start(); ?>

    <script>

        $('.colorpicker-component').colorpicker();

        $('.colorpicker').on('click', function() {
            $(this).parent().colorpicker('show');
        });

        function removeDisabled(){
            $('select').removeAttr('disabled');
            $('input').removeAttr('disabled');
        }

        function selPicker(name, opt){
            if(opt == 0)
                $("input[name='"+name+"']").attr('disabled','disabled').parent().addClass('disabled');
            else
                $("input[name='"+name+"']").removeAttr('disabled').parent().removeClass('disabled');
        }

        function selChange(){
            selPicker('to[chat][hcolor2]', $('#cGradient').val());
            selPicker('to[pBtn][hcolor2]', $('#pGradient').val());
            selPicker('to[pBtnh][hcolor2]', $('#phGradient').val());
        }
        selChange();
        $('select').on('change', function() {selChange();});

    </script>
<?php $contents = ob_get_contents(); ob_end_clean(); $footerAddArr[] = $contents; ?>