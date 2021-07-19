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

                    <div class="row" style="padding-left: 5px;">
                        <div class="col-md-8">
                        <br />
						<div class="form-group">
				            <div class="checkbox">
					           <label class="checkbox inline">
						          <input <?php if ($maintenance_mode) echo 'checked="true"'; ?>
						          type="checkbox" name="maintenance_mode"  /> <?php trans('Enable maintenance mode (Users can\'t able to access the chat!).', $lang['CH191']); ?>
	                           </label>
				            </div>
		  	           </div>
                       
                        <div class="form-group">
                            <label for="maintenance_mes"><?php trans('Maintenance Reason', $lang['CH192']); ?></label>
                            <textarea class="form-control" id="maintenance_mes" name="maintenance_mes" placeholder="<?php trans('Enter your reason', $lang['CH194']); ?>"><?php echo $maintenance_mes; ?></textarea>
                        </div>
                       
                       <div class="callout callout-info">
		                  <p><?php trans('Note: Administrators still have access the full chat functionality!', $lang['CH193']); ?></p>
                       </div>
                        
                       <br />
                    </div>
                    </div>
                <button type="submit" class="btn btn-primary"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
                <br /> <br />
                
                </div><!-- /.box-body -->
                </form>
              </div><!-- /.box -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<?php
$footerAddArr[] = <<<EOD
  <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%'
        });
      });
  </script>  
EOD;
?> 