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
            </div><!-- /.box-header -->

            <form action="#" method="POST">
            <div class="box-body">
             <?php if(isset($msg)) echo $msg;?>
            <br />

            <div class="text-center">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="alert alert-warning">
                            <strong><?php trans('Warning', $lang['CH491']); ?>!</strong> <?php trans('All actions are irreversible!', $lang['CH490']); ?>
                        </div>

                        <div class="form-group">
                        <label><?php trans('Select your action', $lang['CH492']); ?></label>
                        <select name="action" class="form-control">
                            <option value="temp"><?php trans('Clean up all temporary directories', $lang['CH493']); ?></option>
                            <option value="analytics"><?php trans('Clear all analytics data', $lang['CH494']); ?></option>
                            <option value="admin"><?php trans('Clear all admin login history data', $lang['CH495']); ?></option>
                            <option value="users"><?php trans('Clear all users accounts', $lang['CH496']); ?></option>
                            <option value="chats2"><?php trans('Clear older than 2 months chats', $lang['CH497']); ?></option>
                            <option value="chats6"><?php trans('Clear older than 6 months chats', $lang['CH498']); ?></option>
                            <option value="chats1y"><?php trans('Clear older than 1 year chats', $lang['CH499']); ?></option>
                            <option value="chats"><?php trans('Clear all chats & history data', $lang['CH500']); ?></option>
                        </select>
                        </div>
                       <button class="btn btn-danger" onclick="return confirm('<?php makeJavascriptStr($lang['CH501'], true); ?>');"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?php trans('Process', $lang['CH502']); ?></button>
                    </div>
                </div>

            </div>
            <br /> <br />
            </div><!-- /.box-body -->
            </form>
          </div><!-- /.box -->

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
