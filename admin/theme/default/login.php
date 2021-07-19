<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="<?php themeLink('dist/img/favicon.png'); ?>" />
    <title><?php echo $basicSettings['app_name']; ?> | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php themeLink('bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?php themeLink('dist/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php themeLink('dist/css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php themeLink('dist/css/login.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php themeLink('plugins/iCheck/square/green.css'); ?>" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <br />
    <div class="login-box">
      <div class="login-logo">
        <a href="<?php echo $adminBaseURL; ?>"><?php echo htmlspecialchars_decode($basicSettings['html_app']); ?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <?php
        if(isset($_GET['forget'])){
        ?>
        <p class="login-box-msg"><strong><?php trans('Forget Password', $lang['CH132']); ?></strong></p>
        <p>1. In your downloaded ZIP package, there is folder called "Don't Upload"</p>
        <p>2. Goto the folder and find  "reset.php"</p>
        <p>3. Upload "reset.php" file into script root directory</p>
        <p>4. Execute the file by visiting URL "<?php createLink('reset.php',false,true); ?>"</p>
        <p>5. Now try default credentials <br />

        <div class="callout callout-warning">
        <p><?php trans('After reset, delete the "reset.php" file!', $lang['CH131']); ?></p>
        </div>
        </p>
        <?php    
        }else{
        ?>
        <p class="login-box-msg"><strong><?php trans('Admin Section', $lang['CH127']); ?></strong> <br/> <?php trans('Sign in to start your session', $lang['CH128']); ?></p>
        <?php if(isset($msg)) echo $msg; ?>
        <form action="<?php echo $adminBaseURL; ?>" method="POST" onsubmit="return checkLogin();">
          <div class="form-group has-feedback">
            <input <?php echo $lock; ?>value="<?php echo $remUserName; ?>" required="" type="email" name="email" class="form-control" placeholder="<?php echo $lang['CH85']; ?>" />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input <?php echo $lock; ?>value="<?php echo $remPassword; ?>" required="" type="password" name="password" class="form-control" placeholder="<?php echo $lang['RF67']; ?>" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <?php if ($admin_login_page) echo '<div class="col-xs-12">'.$captchaCode.'</div>'; ?>    
            <div class="col-xs-8"> 
              <div class="checkbox icheck">
                <label>
                  <input <?php echo $remBox; ?> name="remember"  <?php echo $lock; ?>type="checkbox"/> <?php trans('Remember Me', $lang['CH129']); ?>
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button <?php echo $lock; ?>type="submit" class="btn btn-success btn-block btn-flat"><?php echo $lang['RF57']; ?></button>
            </div><!-- /.col -->
          </div>
        </form>
        <br />
        <a href="<?php echo $adminBaseURL; ?>?forget"><?php trans('I forgot my password', $lang['CH130']); ?></a><br />
       <?php  }?>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <?php scriptLink('plugins/jQuery/jQuery-2.1.4.min.js'); ?>
    <!-- Bootstrap 3.3.2 JS -->
    <?php scriptLink('bootstrap/js/bootstrap.min.js',true); ?>
    <!-- iCheck -->
    <?php scriptLink('plugins/iCheck/icheck.min.js',true); ?>
    <script>
    function reloadCap(){
         $('input[name="scode"]').val('');
         $('input[name="scode"]').attr("placeholder", 'Loading...');
         $('input[name="scode"]').prop('disabled', true);
         $('#capImg').css("opacity","0.5");
         $.get('<?php createLink('phpcap/reload',false,true); ?>',function(data){
            var newCap = $.trim(data).split(':::'); 
            $('#pageUID').val(newCap[1]);
            $('#capImg').attr('src', newCap[0]);
            $('input[name="scode"]').attr("placeholder", "");
            $('input[name="scode"]').prop('disabled', false);
            $('#capImg').css("opacity","1");
         });    
    }
    function checkLogin(){
        var lEmail= jQuery.trim($('input[name=email]').val());
        if (lEmail==null || lEmail=="") {
            alert("Email field can't be empty!");
            return false;
        }
        var lPass = jQuery.trim($('input[name=password]').val());
        if (lPass==null || lPass=="") {
            alert("Password field can't be empty!");
            return false;
        }
        return true;
      }
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-green',
          radioClass: 'iradio_square-green',
          increaseArea: '20%'
        });
      });
    </script>
  </body>
</html>