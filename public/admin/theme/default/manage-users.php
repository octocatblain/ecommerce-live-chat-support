<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<style>
    @media only screen and (min-width : 1000px) {
        .table-responsive {
            overflow-x: hidden;
        }
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
                <?php if($pointOut == ''){ ?>
                <a href="<?php adminLink($controller.'/export'); ?>" class="btn btn-primary"><i class="fa fa-fw fa-share-square"></i> <?php trans('Export', $lang['CH516']); ?></a>
                <?php } ?>
              </div>
            </div><!-- /.box-header ba-la-ji -->
            <div class="box-body">
            <?php if(isset($msg)) echo $msg; ?><br />
            

             <div class="table-responsive">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="mySitesTable">
                    <thead>
                        <tr>
                              <th><?php trans('Name',$lang['CH268']); ?></th>
                              <th><?php trans('Email ID',$lang['RF114']); ?></th>
                              <th><?php trans('IP',$lang['CH92']); ?></th>
                              <th><?php trans('Country',$lang['RF127']); ?></th>
                              <th><?php trans('Joined Date', $lang['CH517']); ?></th>
                              <th><?php trans('Last Active', $lang['CH518']); ?></th>
                              <th><?php trans('Actions',$lang['CH272']); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
             </div>

            <br />
            
            </div><!-- /.box-body -->
          </div><!-- /.box -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
<?php 
$ajaxLink = adminLink('?route='.ACTIVE_LANG.'/ajax/manageUsers',true,true);
$footerAddArr[] = <<<EOD
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
    	$('#mySitesTable').dataTable( {
    		"processing": true,
    		"serverSide": true,
    		"ajax": "$ajaxLink"
    	} );
    } );
    </script>    
EOD;
?>