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

          
    <?php if(isset($msg)) echo $msg; ?><br />
    
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="<?php trans('Collapse', $lang['CH633']); ?>"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="<?php trans('Remove', $lang['CH632']); ?>"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-line-chart"></i>
                    <h3 class="box-title"> <?php trans('Hourly Traffic', $lang['CH634']); ?> </h3>
                    <div style="position:absolute; top:4px; right:15px;">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input data-date="<?php echo $date; ?>" data-date-format="yyyy-mm-dd" value="<?php echo $date; ?>" class="form-control pull-right" id="datepicker" type="text">
                        </div>
                    </div>
                </div><!-- /.box-header -->
    
                <div class="box-body">
                    <div class="chart tab-pane active" id="pageviews-chart" style="position: relative; height: 300px;"></div>
                </div><!-- /.box-body -->
      
            </div>
        </div>
      </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="<?php trans('Collapse', $lang['CH633']); ?>"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="<?php trans('Remove', $lang['CH632']); ?>"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-file-o"></i>
                    <h3 class="box-title"> <?php trans('Pages', $lang['CH635']); ?> </h3>
                </div><!-- /.box-header -->
    
                <div class="box-body">
                 <table id="platform" class="table table-striped table-bordered">
                    <thead>
                        <th><?php trans('Link',$lang['CH488']); ?></th>
                        <th><?php trans('Pageviews', $lang['CH636']); ?></th>
                        <th><?php trans('Percentage', $lang['CH637']); ?></th>
                    </thead>
                    <tbody> 
                        <?php echo $table4; ?>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
      
            </div>
        </div>
      </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="<?php trans('Collapse', $lang['CH633']); ?>"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="<?php trans('Remove', $lang['CH632']); ?>"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-globe"></i>
                    <h3 class="box-title"> <?php trans('Countries', $lang['CH638']); ?> </h3>
                </div><!-- /.box-header -->
    
                <div class="box-body">
                 <table id="countries" class="table table-striped table-bordered">
                    <thead>
                        <th><?php trans('Country',$lang['RF127']); ?></th>
                        <th><?php trans('Sessions', $lang['CH639']); ?></th>
                        <th><?php trans('Percentage',$lang['CH637']); ?></th>
                    </thead>
                    <tbody> 
                        <?php echo $table1; ?>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
      
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-info customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="<?php trans('Collapse', $lang['CH633']); ?>"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="<?php trans('Remove', $lang['CH632']); ?>"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-list-alt"></i>
                    <h3 class="box-title">  Browsers </h3>
                </div><!-- /.box-header -->
    
                <div class="box-body">
                 <table id="browsers" class="table table-striped table-bordered">
                    <thead>
                        <th><?php trans('Browser',$lang['CH90']); ?></th>
                        <th><?php trans('Sessions',$lang['CH639']); ?></th>
                        <th><?php trans('Percentage',$lang['CH637']); ?></th>
                    </thead>
                    <tbody> 
                        <?php echo $table2; ?>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
      
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="<?php trans('Collapse', $lang['CH633']); ?>"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="<?php trans('Remove', $lang['CH632']); ?>"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-desktop"></i>
                    <h3 class="box-title"> <?php trans('Operating Systems', $lang['CH640']); ?> </h3>
                </div><!-- /.box-header -->
    
                <div class="box-body">
                 <table id="platform" class="table table-striped table-bordered">
                    <thead>
                        <th><?php trans('Platform',$lang['CH91']); ?></th>
                        <th><?php trans('Sessions',$lang['CH639']); ?></th>
                        <th><?php trans('Percentage',$lang['CH637']); ?></th>
                    </thead>
                    <tbody> 
                        <?php echo $table3; ?>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
      
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-info customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="<?php trans('Collapse', $lang['CH633']); ?>"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="<?php trans('Remove', $lang['CH632']); ?>"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-list-alt"></i>
                    <h3 class="box-title">  <?php trans('Referer', $lang['CH641']); ?> </h3>
                </div><!-- /.box-header -->
    
                <div class="box-body">
                 <table id="referer" class="table table-striped table-bordered">
                    <thead>
                        <th><?php trans('Referral', $lang['CH642']); ?></th>
                        <th><?php trans('Sessions',$lang['CH639']); ?></th>
                        <th><?php trans('Percentage',$lang['CH637']); ?></th>
                    </thead>
                    <tbody> 
                        <?php echo $table5; ?>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
      
            </div>
        </div>
    </div>
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
<?php
$uVtxt = makeJavascriptStr($lang['CH167']);
$pVtxt = makeJavascriptStr($lang['CH168']);
$myLink = adminLink($controller,true);
$footerAddArr[] = <<<EOD
<script type="text/javascript">
    $(function () {
        $('#countries').DataTable({
          "paging": true, "pagingType":"full", "lengthChange": false, "searching": false,
          "ordering": false, "info": true, "autoWidth": false, "pageLength": 6
        });
        $('#browsers').DataTable({
          "paging": true, "pagingType":"full", "lengthChange": false, "searching": false,
          "ordering": false, "info": true, "autoWidth": false, "pageLength": 6
        });
        $('#platform').DataTable({
          "paging": true, "pagingType":"full", "lengthChange": false, "searching": false,
          "ordering": false, "info": true, "autoWidth": false, "pageLength": 6
        });
        $('#referer').DataTable({
          "paging": true, "pagingType":"full", "lengthChange": false, "searching": false,
          "ordering": false, "info": true, "autoWidth": false, "pageLength": 6
        });
    });
</script>    
EOD;

$chartData = "
    data: [
      {y: '12AM - 2AM', item1: ".$valRes[0]['h00']['unique'].", item2: ".$valRes[0]['h00']['views']."},
      {y: '2AM - 4AM', item1: ".$valRes[0]['h02']['unique'].", item2: ".$valRes[0]['h02']['views']."},
      {y: '4AM - 6AM', item1: ".$valRes[0]['h04']['unique'].", item2: ".$valRes[0]['h04']['views']."},
      {y: '6AM - 8AM', item1: ".$valRes[0]['h06']['unique'].", item2: ".$valRes[0]['h06']['views']."},
      {y: '8AM - 10AM', item1: ".$valRes[0]['h08']['unique'].", item2: ".$valRes[0]['h08']['views']."},
      {y: '10AM - 12PM', item1: ".$valRes[0]['h10']['unique'].", item2: ".$valRes[0]['h10']['views']."},
      {y: '12PM - 2PM', item1: ".$valRes[0]['h12']['unique'].", item2: ".$valRes[0]['h12']['views']."},
      {y: '2PM - 4PM', item1: ".$valRes[0]['h14']['unique'].", item2: ".$valRes[0]['h14']['views']."},
      {y: '4PM - 6PM', item1: ".$valRes[0]['h16']['unique'].", item2: ".$valRes[0]['h16']['views']."},
      {y: '6PM - 8PM', item1: ".$valRes[0]['h18']['unique'].", item2: ".$valRes[0]['h18']['views']."},
      {y: '8PM - 10PM', item1: ".$valRes[0]['h20']['unique'].", item2: ".$valRes[0]['h20']['views']."},
      {y: '10PM - 12AM', item1: ".$valRes[0]['h22']['unique'].", item2: ".$valRes[0]['h22']['views']."}
    ],";

$footerAddArr[] = <<<EOD
  <script>
   /* Morris.js Charts */
  // Sales chart
  var CountX = -1;
  var area = new Morris.Area({
    element: 'pageviews-chart',
    resize: true,
    $chartData
    xkey: 'y',
    ykeys: ['item1', 'item2'],
    labels: ['$uVtxt', '$pVtxt'],
    lineColors: ['#c5eea1', '#b7d5f0'],
    hideHover: 'auto',
    parseTime: false,
    xLabelMargin: 10,
    padding: 40,
    xLabelAngle: 30,
    xLabelFormat: function(d) {
    CountX = CountX+1;
    return ['12AM - 2AM','2AM - 4AM','4AM - 6AM','6AM - 8AM','8AM - 10AM','10AM - 12PM','12PM - 2PM','2PM - 4PM','4PM - 6PM','6PM - 8PM','8PM - 10PM','10PM - 12AM'][CountX];
    }
  });

      $('#datepicker').datepicker()
      .on('changeDate', function(ev){
            $(this).datepicker('hide');
            window.location.href = '$myLink/' + $('#datepicker').val();
      });
 </script> 

EOD;
?>