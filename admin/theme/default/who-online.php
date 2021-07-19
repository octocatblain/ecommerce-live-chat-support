<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<style>
table {
    table-layout: fixed; width: 100%;
}
td {
  word-wrap: break-word;
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
            </div><!-- /.box-header ba-la-ji -->
            <form action="#" method="POST">
            <div class="box-body">
          
            <?php if(isset($msg)) echo $msg; ?><br />
            
            
          <table id="visitorsTable" class="table table-bordered table-striped visitorsTable">
            <thead>
              <tr>
                <th><?php trans('IP',$lang['CH92']); ?></th>
                <th><?php trans('Country',$lang['RF127']); ?></th>
                <th><?php trans('Customer',$lang['CH646']); ?></th>
                <th><?php trans('Browser',$lang['CH90']); ?></th>
                <th><?php trans('Last Page Visited',$lang['CH647']); ?></th>
                <th><?php trans('Referer',$lang['CH641']); ?></th>
                <th><?php trans('Last Click',$lang['CH648']); ?></th>
              </tr>
            </thead>
            <tbody id="visitorsTableBody">
                <?php echo $rainbowTrackBalaji; ?>
            </tbody>
          </table> 
            
            <br />
            
            </div><!-- /.box-body -->
            </form>
          </div><!-- /.box -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
<?php 
metaRefresh(null,$refreshTime);
$footerAddArr[] = <<<EOD
<script type="text/javascript">
var visitTab;
$(function () {
    visitTab = $('#visitorsTable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false
    });
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
jQuery(document).ready(function(){
    $(document).on("click",".paginate_button", function(){
        setTimeout(function(){
        var pos = $('#contentBox').offset();
        $('body,html').animate({ scrollTop: pos.top });
        }, 1);
    });
});
</script>
EOD;
?>