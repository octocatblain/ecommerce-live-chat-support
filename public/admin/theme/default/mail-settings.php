<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

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
            <form onsubmit="return protocolCheck('0');" action="#" method="POST">
            <div class="box-body">
          
            <?php if(isset($msg)) echo $msg; ?><br />
                <div class="row" style="padding-left: 5px;">
                    <div class="col-md-8">
                    <div class="form-group">
                    <label><?php trans('Select your Mail Protocol', $lang['CH590']); ?>: </label>
                    <select name="protocol" class="form-control"> 
       					<option <?php echo isSelected($protocol, true, 1, '1'); ?> value="1"><?php trans('PHP Mail', $lang['CH591']); ?></option>
     					<option <?php echo isSelected($protocol, true, 1, '2'); ?> value="2"><?php trans('SMTP', $lang['CH592']); ?></option>
                    </select>                     
                </div>                                              
                <br />
                   <div class="box-header with-border">
                    <h3 class="box-title"><?php trans('SMTP Information', $lang['CH593']); ?> </h3>
                   </div><!-- /.box-header -->
                    <br />
                       
                    <div class="form-group">
                        <label for="smtp_host"><?php trans('SMTP Host', $lang['CH594']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter smtp host', $lang['CH605']); ?>" name="smtp_host" value="<?php echo $smtp_host; ?>" class="form-control">
                    </div>
                <div class="form-group">											
                  <label for="smtp_auth"><?php trans('SMTP Auth', $lang['CH595']); ?></label>
			      <select name="auth" class="form-control">  
                       <option <?php echo isSelected($auth, true, 1); ?> value="true"><?php trans('True',$lang['RF6']); ?></option>
                       <option <?php echo isSelected($auth, false, 1); ?> value="false"><?php trans('False', $lang['CH606']); ?></option>
                   </select>				
					</div> <!-- /form-group -->
                    
                   <div class="form-group">
                        <label for="smtp_port"><?php trans('SMTP Port', $lang['CH596']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter smtp port', $lang['CH604']); ?>" name="smtp_port" value="<?php echo $smtp_port; ?>" class="form-control">
                    </div>
                       <div class="form-group">
                        <label for="smtp_user"><?php trans('SMTP Username', $lang['CH597']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter smtp username', $lang['CH603']); ?>" name="smtp_user" value="<?php echo $smtp_username; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="smtp_pass"><?php trans('SMTP Password', $lang['CH598']); ?></label>
                        <input type="password" placeholder="<?php trans('Enter smtp password', $lang['CH602']); ?>" name="smtp_pass" value="<?php echo $smtp_password; ?>" class="form-control">
                    </div>       
                    
                 <div class="form-group">											
                <label for="smtp_socket"><?php trans('SMTP Secure Socket', $lang['CH599']); ?></label>
			    <select name="socket" class="form-control">
                    <option <?php echo isSelected($socket, true, 1, 'tls'); ?> value="tls"><?php trans('TLS', $lang['CH600']); ?></option>
                    <option <?php echo isSelected($socket, true, 1, 'ssl'); ?> value="ssl"><?php trans('SSL', $lang['CH601']); ?></option>
                </select>				
					</div> <!-- /form-group -->      
                    </div> </div>
                <button type="submit" class="btn btn-primary"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save', $lang['CH39']); ?></button>
                <br />
                
                </div><!-- /.box-body -->
                </form>
              </div><!-- /.box -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php
$footerAddArr[] = <<<EOD
  <script>
      function protocolCheck(val){
        if(val == '1'){
            $('input[name=smtp_port]').attr('disabled', 'disabled');
            $('input[name=smtp_user]').attr('disabled', 'disabled');
            $('input[name=smtp_pass]').attr('disabled', 'disabled');
            $('select[name=socket]').attr('disabled', 'disabled');
            $('select[name=auth]').attr('disabled', 'disabled');
            $('input[name=smtp_host]').attr('disabled', 'disabled');
        }else{
            $('input[name=smtp_port]').removeAttr('disabled');
            $('input[name=smtp_user]').removeAttr('disabled');
            $('input[name=smtp_pass]').removeAttr('disabled');
            $('select[name=socket]').removeAttr('disabled');
            $('select[name=auth]').removeAttr('disabled');
            $('input[name=smtp_host]').removeAttr('disabled');
        }
        return true;
      }
      var selVal;  
      $(function () {
        selVal = jQuery('select[name="protocol"]').val();
        protocolCheck(selVal);
      });      
      $('select[name="protocol"]').on('change', function() {
        selVal = jQuery('select[name="protocol"]').val();
        protocolCheck(selVal);
      });
  </script>  
EOD;
?>  