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
          
            <?php if(isset($msg)) echo $msg; ?>
            <div class="col-md-8">
			<div class="form-group">
	            <div class="checkbox">
		           <label class="checkbox inline">
			          <input <?php isSelected($adblock['enable'],true,'2') ?> type="checkbox" name="adblock[enable]"  /> <?php trans('Enable ad-blocker detection and protection system', $lang['CH240']); ?>
                   </label>
	            </div>
            </div>
            <div class="form-group">
				<label><?php trans('Adblock notification type', $lang['CH239']); ?></label>
				<select class="form-control" name="adblock[options]" id="opt">
					<option <?php echo isSelected($adblock['options'], true, 1, 'link'); ?> value="link"><?php trans('Redirect to custom adblock notification page', $lang['CH241']); ?></option>
                    <option <?php echo isSelected($adblock['options'], true, 1, 'close'); ?> value="close"><?php trans('Dialog box with close button (User can continue to access website)', $lang['CH242']); ?></option>
                    <option <?php echo isSelected($adblock['options'], true, 1, 'force'); ?> value="force"><?php trans('Dialog box without close button (User can\'t continue to access website)', $lang['CH243']); ?></option>
                </select>
			</div>
            
            <div class="form-group hide" id="opt-link">
                <label><?php trans('Redirect Link', $lang['CH244']); ?></label>
                <input name="adblock[link]" type="text" placeholder="<?php trans('Type link', $lang['CH245']); ?>" value="<?php echo $adblock['link']; ?>" class="form-control" />
            </div>
            
            <div class="hide" id="opt-close">
            <div class="form-group">
                <label><?php trans('Dialog Box - Title', $lang['CH248']); ?></label>
                <input name="adblock[close][title]" type="text" placeholder="<?php trans('Type title', $lang['CH246']); ?>" value="<?php echo $adblock['close']['title']; ?>" class="form-control" />
            </div>
            
            <div class="form-group">
                <label><?php trans('Dialog Box - Message', $lang['CH249']); ?></label>
                <textarea rows="6" name="adblock[close][msg]" placeholder="<?php trans('Type description', $lang['CH247']); ?>" class="form-control"><?php echo nlfix(htmlspecialchars_decode($adblock['close']['msg'])); ?></textarea>
            </div>
            </div>
            
            <div class="hide" id="opt-force">
            <div class="form-group">
                <label><?php trans('Dialog Box - Title', $lang['CH248']); ?></label>
                <input name="adblock[force][title]" type="text" placeholder="<?php trans('Type title',$lang['CH246']); ?>" value="<?php echo $adblock['force']['title']; ?>" class="form-control" />
            </div>
            
            <div class="form-group">
                <label><?php trans('Dialog Box - Message', $lang['CH249']); ?></label>
                <textarea rows="6" name="adblock[force][msg]" placeholder="<?php trans('Type description', $lang['CH247']); ?>" class="form-control"><?php echo nlfix(htmlspecialchars_decode($adblock['force']['msg'])); ?></textarea>
            </div>
            </div>

            <button type="submit" class="btn btn-danger"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>

           <br /><br />
               
            </div>
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
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '100%'
        });
        var selVal = jQuery('select[id="opt"]').val();
        oldSel = selVal;
        $('#opt-'+selVal).removeClass("hide");
        $('#opt-'+selVal).fadeIn();
      });      
      $('select[id="opt"]').on('change', function() {
            var selVal = jQuery('select[id="opt"]').val();
            $('#opt-'+oldSel).fadeOut();
            $('#opt-'+selVal).removeClass("hide");
            $('#opt-'+selVal).fadeIn();
            oldSel = selVal;
      });
        
  </script>  
EOD;
?>  