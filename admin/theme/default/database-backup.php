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
          
            <?php if(isset($msg)) echo $msg.'<br />'; ?>
            
            <div class="row"> <div class="col-md-6">
			<div class="form-group">
	            <div class="checkbox">
		           <label class="checkbox inline">
			          <input <?php isSelected($other['other']['dbbackup']['gzip'],true,'2') ?> type="checkbox" name="other[other][dbbackup][gzip]"  /> <?php trans('Compress database backup file using Gzip', $lang['CH430']); ?>
                   </label>
	            </div>
            </div>
            
			<div class="form-group">
	            <div class="checkbox">
		           <label class="checkbox inline">
			          <input <?php isSelected($other['other']['dbbackup']['cron'],true,'2') ?> type="checkbox" name="other[other][dbbackup][cron]" id="cron"  /> <?php trans('Automatically backup database using Cron Job', $lang['CH431']); ?>
                   </label>
	            </div>
            </div>
            
            <div class="form-group">
				<select id="cronopt" class="form-control" name="other[other][dbbackup][cronopt]">
					<option <?php echo isSelected($other['other']['dbbackup']['cronopt'], true, 1, 'daily'); ?> value="daily"><?php trans('Backup Daily', $lang['CH432']); ?></option>
                    <option <?php echo isSelected($other['other']['dbbackup']['cronopt'], true, 1, 'weekly'); ?> value="weekly"><?php trans('Backup Weekly', $lang['CH433']); ?></option>
                    <option <?php echo isSelected($other['other']['dbbackup']['cronopt'], true, 1, 'monthly'); ?> value="monthly"><?php trans('Backup Monthly', $lang['CH434']); ?></option>
                </select>
			</div>
            
            <div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings',$lang['CH187']); ?></button>
                <a class="btn btn-danger" href="<?php adminLink($controller.'/backup-now'); ?>"><i class="fa fa-file-archive-o" aria-hidden="true"></i> <?php trans('Backup Now', $lang['CH435']); ?></a>
                <a class="btn btn-warning" href="<?php adminLink($controller.'/backup-download'); ?>"><i class="fa fa-download" aria-hidden="true"></i> <?php trans('Backup & Download', $lang['CH436']); ?></a>
            </div>
             
            </div>  </div> 
            
            <br />
            
            </div><!-- /.box-body -->
            </form>
          </div><!-- /.box -->
          
         <div class="box box-danger">
            <div class="box-header with-border">
                <!-- tools box -->

                <h3 class="box-title">
                    <?php trans('DB Backup List', $lang['CH437']); ?>
                </h3>
            </div>

            <div class="box-body">
                  <table id="seoToolTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?php trans('Filename', $lang['CH438']); ?></th>
                        <th><?php trans('Backup Date', $lang['CH439']); ?></th>
                        <th><?php trans('Download',$lang['CH58']); ?></th>
                        <th><?php trans('Delete',$lang['CH232']); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php echo $tableData; ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
            </div>
            
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
        
        if($('#cron').prop('checked') == true) {
           $('#cronopt').removeAttr('disabled');
        } else {
           $('#cronopt').attr('disabled', 'disabled');
        }
                
       $('#cron').on('ifChecked', function(){
            $('#cronopt').removeAttr('disabled');
       });
       
       $('#cron').on('ifUnchecked', function(){
           $('#cronopt').attr('disabled', 'disabled'); 
       });
       });   
             
  </script>  
EOD;
?>