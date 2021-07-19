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
            <form action="#" method="POST">
            <div class="box-body">
          
            <?php if(isset($msg)) echo $msg; ?><br />
            
            <div class="form-group">
            <label><?php trans('Captcha protection for following pages:', $lang['CH199']); ?></label>
                <select name="cap_pages[]" class="form-control select2" multiple="multiple" data-placeholder="<?php trans('Which pages need image verifications?', $lang['CH200']); ?>" style="width: 100%;">
                  <?php 
                    foreach($capList as $capName=>$capRaw)
                        echo '<option '.isSelected($cap_options[$capName], true, 1,null,true).' value="'.$capName.'">'.$capRaw.'</option>';
                  ?>
                </select>
            </div>
            
            <div class="form-group">
                <label> <?php trans('Select Capthca Service', $lang['CH201']); ?> </label>
                <select name="sel_cap" class="form-control">
                <?php foreach($cap_data as $capbasename => $cap){
                    echo '<option '. isSelected($cap_type, true, 1, $capbasename,true).' value="'.$capbasename.'" >'.$cap['cap_name'].'</option>';
                }?>
                </select>
            </div>
            
            <div class="hide" id="recap"> 
                <input type="hidden" value="Google reCAPTCHA" name="cap[recap][cap_name]" />            
                <div class="form-group">
                    <label>reCAPTCHA <?php trans('Secret Key', $lang['CH202']); ?></label>
                    <input type="text" placeholder="<?php trans('Enter your', $lang['CH204']); ?> reCAPTCHA <?php trans('Secret Key', $lang['CH202']); ?>" name="cap[recap][recap_seckey]" value="<?php echo $recap_seckey; ?>" class="form-control" />
                </div>
                <div class="form-group">
                    <label>reCAPTCHA <?php trans('Site Key', $lang['CH203']); ?></label>
                    <input type="text" placeholder="<?php trans('Enter your', $lang['CH204']); ?> reCAPTCHA <?php trans('Site Key', $lang['CH203']); ?>" name="cap[recap][recap_sitekey]" value="<?php echo $recap_sitekey; ?>" class="form-control" />
                </div>
            </div> 
            
            <div class="hide" id="phpcap">  
                <input type="hidden" value="<?php trans('Built-in PHP Image Verification', $lang['CH205']); ?>" name="cap[phpcap][cap_name]" />
				<div class="form-group">
					<label><?php trans('Difficulty type', $lang['CH206']); ?></label>
					<select class="form-control" name="cap[phpcap][mode]">
    					<option <?php echo isSelected($mode, true, 1, 'Easy'); ?> value="Easy"><?php trans('Easy', $lang['CH207']); ?></option>
                        <option <?php echo isSelected($mode, true, 1, 'Normal'); ?> value="Normal"><?php trans('Normal', $lang['CH208']); ?></option>
                        <option <?php echo isSelected($mode, true, 1, 'Tough'); ?> value="Tough"><?php trans('Tough', $lang['CH209']); ?></option>
                    </select>
				</div>         
                <div class="form-group">
                    <label><?php trans('Allowed characters', $lang['CH210']); ?></label>
                    <input type="text" placeholder="<?php trans('Enter your characters', $lang['CH215']); ?>" name="cap[phpcap][allowed]" value="<?php echo $allowed; ?>" required="" class="form-control" />
                </div>
                <div class="form-group">
                    <label><?php trans('Captcha text color', $lang['CH211']); ?></label>
                    <input type="text" value="<?php echo $color; ?>" class="form-control  my-colorpicker1 colorpicker-element" name="cap[phpcap][color]" />
                </div>
				<div class="form-group">
					<label><?php trans('Multiple background images', $lang['CH212']); ?></label>
					<select class="form-control" name="cap[phpcap][mul]">
    					<option <?php echo isSelected($mul, true, 1); ?> value="yes"><?php trans('Yes', $lang['CH213']); ?></option>
                        <option <?php echo isSelected($mul, false, 1); ?> value="no"><?php trans('No', $lang['CH214']); ?></option>
                    </select>
				</div>   
            </div>

                <button type="submit" class="btn btn-success"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
            <br /><br />
        </div><!-- /.box-body -->
        </form>
          </div><!-- /.box -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  
<?php
$footerAddArr[] = <<<EOD
    <script> 
       var oldSel;
       $(function () {
        $(".select2").select2();
        $(".my-colorpicker1").colorpicker();
        var selVal = jQuery('select[name="sel_cap"]').val();
        oldSel = selVal;
        $('#'+selVal).removeClass("hide");
        $('#'+selVal).fadeIn();
       });
        
       $('select[name="sel_cap"]').on('change', function() {
            var selVal = jQuery('select[name="sel_cap"]').val();
            $('#'+oldSel).fadeOut();
            $('#'+selVal).removeClass("hide");
            $('#'+selVal).fadeIn();
            oldSel = selVal;
        });
    </script>
EOD;
?>