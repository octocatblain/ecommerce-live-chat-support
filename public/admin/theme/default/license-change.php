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
            	<label><?php trans('Item Purchase Code', $lang['CH425']); ?>: <small>(<?php trans('License Key', $lang['CH428']); ?>)</small></label>
            	<input value="<?php echo $licArr['code']; ?>" disabled="" type="text" name="domain" class="form-control" />
            </div>      

            <div class="form-group">
            	<label><?php trans('Registered Domain Name', $lang['CH426']); ?>:</label>
            	<input value="<?php echo $licArr['domain']; ?>" disabled="" type="text" name="domain" class="form-control" />
            </div>
            
            <div class="form-group">
            	<label><?php trans('Registered Link', $lang['CH427']); ?>:</label>
            	<input value="<?php echo ($licArr['path'] == '') ? $licArr['domain'].'/' : $licArr['path']; ?>" disabled="" type="text" name="domain" class="form-control" />
            </div>
            
            <a target="_blank" href="http://api.prothemes.biz/pinky/reset.php?code=<?php echo $item_purchase_code; ?>" class="btn btn-danger"> <i class="fa fa-refresh" aria-hidden="true"></i> <?php trans('Reset Domain Name', $lang['CH429']); ?></a>
            
            <br />
            
            </div><!-- /.box-body -->
            </form>
          </div><!-- /.box -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->