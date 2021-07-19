<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

?>
<style>
    .widgetCode {
        border: 1px solid #E5E5E5!important;
        background: #fefcfb !important;
        color: #636e72 !important;
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

                <div class="box-body">

                    <?php if(isset($msg)) echo $msg; ?><br />

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab" aria-expanded="true"><?php trans('Bottom Widget', $lang['CH252']); ?></a></li>
                            <li class=""><a href="#tab2" data-toggle="tab" aria-expanded="false"><?php trans('Inline Widget', $lang['CH253']); ?></a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="customTab wid50">
                                <?php  adminSelect('w[lang]', 'lang', $lang['CH254'], $widgetLang, 'default', true);  ?>
                            </div>

                            <div class="tab-pane customTab active" id="tab1">

                                <p class="noMar"><?php echo $lang['CH255']; ?></p>

                                <?php adminTextArea('w[code]', 'code', '', $lang['CH260'], $w['code'], 'rows="5" onclick="selectAll(this)" readonly="" ', 'widgetCode'); ?>

                            </div>
                            <div class="tab-pane customTab" id="tab2">

                                <p class="noMar"><?php echo $lang['CH255']; ?></p>

                                <?php adminTextArea('w[inline]', 'inline', '', $lang['CH259'], $w['inline'], 'rows="5" onclick="selectAll(this)" readonly="" ', 'widgetCode'); ?>

                            </div>
                            <div class="customTab">
                             <div class="text-center">
                                <button onclick="return copyMe();" class="btn btn-success"><?php trans('Copy to Clipboard', $lang['CH258']); ?></button>
                                <a href="<?php echo $builderLink; ?>" class="btn btn-warning"><?php trans('Customize Widget', $lang['CH257']); ?></a>
                                <a href="<?php adminLink('chat-settings'); ?>" class="btn btn-primary"><?php trans('Chat Settings', $lang['CH256']); ?></a>
                             </div>
                            <br>
                            <b><?php trans('How to use:', $lang['CH261']); ?></b>

                            <p><?php echo $lang['CH262']; ?></p>
                            </div>
                        </div>
                    </div>

                    <br />

                </div><!-- /.box-body -->

        </div><!-- /.box -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php ob_start(); ?>

<script type="text/javascript">
    <?php
        defineJs('widgetLink', str_replace(array('http://', 'https://'), '//', $baseURL.'{{lang}}widget.js'));
    ?>
    function selChange(){
        var value = $('#lang').val();
        var mylink;
        if(value === 'default')
            mylink = widgetLink.replace(/{{lang}}/g, '');
        else
            mylink = widgetLink.replace(/{{lang}}/g, value + '/');
        $('#code').val('<script type="text/javascript" src="'+mylink+'"><\/script>');
        $('#inline').val('<script type="text/javascript" src="'+mylink+'&inline"><\/script>');
    }

    $('select').on('change', function() {  selChange(); });

    function copyMe(){
        var copyBox;
        if($('.tab-content .active').attr('id') === 'tab1')
            copyBox = '#code';
        else
            copyBox = '#inline';

        var copyTextarea = document.querySelector(copyBox);
        copyTextarea.select();
        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            swal('<?php makeJavascriptStr($lang['CH264'], true); ?>', '<?php makeJavascriptStr($lang['CH263'], true); ?>', "success");
        } catch (err) {
            alert('Oops, unable to copy!');
            swal('<?php makeJavascriptStr($lang['RF82'], true); ?>', '<?php makeJavascriptStr($lang['CH265'], true); ?>', "error");
        }
    }
    function selectAll(text) {
        text.focus();
        text.select();
        document.execCommand("Copy");
    }
</script>
<?php $contents = ob_get_contents(); ob_end_clean(); $footerAddArr[] = $contents; ?>