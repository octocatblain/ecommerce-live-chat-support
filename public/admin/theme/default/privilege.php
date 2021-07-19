<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $pageTitle; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php themeLink('bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php themeLink('dist/css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php themeLink('plugins/iCheck/square/blue.css'); ?>" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition lockscreen">
<br />
<style>
    .btnx{color: #777777 !important;}
</style>
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="<?php echo $adminBaseURL; ?>"><?php echo HTML_APP_NAME; ?></a>
    </div>

    <div class="alert alert-danger" style="background-color: #e8897d !important;">
        <?php trans('You don\'t have sufficient permissions to access this page!', $lang['CH816']); ?>
    </div>
    <br>    <br>

    <div class="lockscreen-item">
        <div class="lockscreen-image">
            <img src="<?php echo $admin_logo_path; ?>" alt="<?php echo $lang['CH818']; ?>">
        </div>

        <div class="lockscreen-credentials">
            <div class="input-group">
                <input value="<?php echo $adminName; ?>" type="text" class="form-control" placeholder="name">

                <div class="input-group-btn">
                    <a class="btn btnx" href="<?php echo $adminBaseURL; ?>"><i class="fa fa-arrow-right text-muted"></i> <?php echo $lang['CH266']; ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="<?php adminLink('logout'); ?>"><?php trans('Or sign in as a different user', $lang['CH817']); ?></a>
    </div>
    <div class="lockscreen-footer text-center">
        <br><br>
        <?php htmlPrint($basicSettings['copyright']); ?>
    </div>
</div>


<!-- jQuery 2.1.4 -->
<?php scriptLink('plugins/jQuery/jQuery-2.1.4.min.js'); ?>
<!-- Bootstrap 3.3.2 JS -->
<?php scriptLink('bootstrap/js/bootstrap.min.js',true); ?>
<!-- iCheck -->
<?php scriptLink('plugins/iCheck/icheck.min.js',true); ?>

</body>
</html>