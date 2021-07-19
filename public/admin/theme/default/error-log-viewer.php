<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<style>
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fdfdfd;
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
                <div style="position:absolute; top:4px; right:15px;">
                  <a class="btn btn-primary btn-sm" href="<?php adminLink($controller.'/clear'); ?>"><i class="fa fa-trash"></i> &nbsp; <?php trans('Clear Error Log', $lang['CH453']); ?></a>
                </div>
            </div><!-- /.box-header ba-la-ji -->
            <form action="#" method="POST">
            <div class="box-body">
            <br />
                <div class="alert alert-danger">
                    <strong><?php trans('Note', $lang['CH455']); ?>: </strong> <?php trans('Error Log includes warnings, fatal errors and notice messages.', $lang['CH454']); ?>
                </div>
                    
            <?php if(isset($msg)) echo $msg; ?>

             <textarea readonly="" class="form-control" rows="18"><?php echo $errData; ?></textarea>
            <br />
            <br />
            </div><!-- /.box-body -->
            </form>
          </div><!-- /.box -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->