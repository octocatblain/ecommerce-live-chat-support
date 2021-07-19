<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

if(!isset($addonRes))
    die("Not Allowed");
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $pageTitle; ?>  
            <small><?php echo $lang['CH77']; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php adminLink(); ?>"><i class="fa fa-cogs"></i> <?php echo $lang['CH78']; ?></a></li>
            <li class="active"><?php echo $pageTitle; ?> </li>
          </ol>
        </section>

    <!-- Main content -->
    <section class="content">

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $lang['CH480']; ?></h3>
            </div><!-- /.box-header -->

            <div class="box-body">

            <?php if(isset($msg)) echo $msg;?>

            <br />
            <p><?php trans('Addon Installation Log File', $lang['CH819']); ?>:</p>
            <textarea readonly="" id="tableRes" rows="12" class="form-control"><?php echo $addonRes; ?></textarea>
            <br />
            <?php if(isset($addonError)){
            if($errType == '2') { ?>
            <p style="color: #d35400;"><?php echo $lang['CH820']; ?></p>
            <?php } elseif($errType == '1'){  ?>
            <p style="color: #c0392b;"><?php echo $lang['CH821']; ?></p>
            <?php } } else{ ?>
            <p style="color: #27ae60;"><?php echo $lang['CH822']; ?></p>
            <?php } ?>
            <br />
            <?php if($customLink){ ?>
            <p><?php trans('Goto', $lang['CH823']); ?>:</p>
            <?php foreach($customLinks as $links){
            echo $links. '  ';
            } } ?>
            <br />

            </div><!-- /.box-body -->

          </div><!-- /.box -->

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->