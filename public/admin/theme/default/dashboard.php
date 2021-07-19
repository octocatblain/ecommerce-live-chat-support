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

            <div class="row">
            <div class="col-lg-12">
                <?php if(file_exists(ROOT_DIR.'reset.php')) echo warnMsgAdmin($lang['CH136'],false); ?>
            </div>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $onlineNow; ?></h3>
                  <p><?php trans('Users Online', $lang['CH137']); ?></p>
                </div>
                <div class="icon">
                  <i class="fa fa-smile-o"></i>
                </div>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $today_users_count; ?></h3>
                        <p><?php trans('Today Chats', $lang['CH138']); ?></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-chat"></i>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $today_page; ?><sup style="font-size: 20px"></sup></h3>
                  <p><?php trans('Today Pageviews', $lang['CH139']); ?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $today_visit; ?></h3>
                  <p><?php trans('Today Unique Visitors', $lang['CH140']); ?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
          
          <!-- Main row -->
          <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
          
          
            <!-- Custom tabs (Charts with tabs)-->
              <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right">
                  <li class="pull-left header"><i class="fa fa-signal"></i> <?php trans('Analytics', $lang['CH141']); ?></li>
                </ul>
                <div class="tab-content no-padding">
                 <?php
                 if(count($pageViewHistory) < 2){
                    echo '<div class="text-center"><br><br><br><br><br><br><?php trans(\'Not enough Data\', $lang[\'CH142\']); ?><br><br><br><br><br><br><br><br></div>';
                    }else{
                 ?>
                  <div class="chart tab-pane active" id="pageviews-chart" style="position: relative; height: 300px;"></div>
                <?php } ?>
                </div>
              </div><!-- /.nav-tabs-custom -->

             <div class="box box-primary customBox">
                        <div class="box-header">
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-primary btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-primary btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                            </div><!-- /. tools -->
                           <i class="fa fa-comments"></i>

                            <h3 class="box-title"><?php trans('Recent Chats', $lang['CH143']); ?></h3>
                        </div><!-- /.box-header -->

                       
                        <div class="box-body">
                            <table class="table table-hover table-bordered">
                                <tbody><tr>
                                    <th><?php trans('Username',$lang['RF66']); ?></th>
                                    <th><?php trans('Chat Started', $lang['CH144']); ?></th>
                                    <th><?php trans('Department', $lang['CH145']); ?></th>
                                    <th><?php trans('Status', $lang['CH146']); ?></th>
                                </tr>
                                <?php echo $userHistoryData; ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
               
         
                        <div class="box-footer">
                 
                        </div><!-- /.box-footer -->
                    </div>
            <div class="box box-success customBox">
                <div class="box-header">
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-success btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                        <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-success btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                    </div><!-- /. tools -->
                   <i class="fa fa-th-list"></i>

                    <h3 class="box-title"><?php trans('Admin Login History', $lang['CH147']); ?></h3>
                </div><!-- /.box-header -->

               
                <div class="box-body">
                    <table class="table table-hover table-bordered">
                        <tbody><tr>
                            <th><?php trans('Username',$lang['RF66']); ?></th>
                            <th><?php trans('Login Date', $lang['CH148']); ?></th>
                            <th><?php trans('IP',$lang['CH92']); ?></th>
                            <th><?php trans('Country',$lang['RF127']); ?></th>
                            <th><?php trans('Browser',$lang['CH90']); ?></th>
                        </tr>
                        <?php echo $adminHistoryData; ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
       
 
                <div class="box-footer">
         
                </div><!-- /.box-footer -->
            </div>
                            
          
          </section><!-- /.Left col -->
          
      <section class="col-lg-5 connectedSortable">

             <div id="server-box" class="box box-info customBox">
            <div class="box-header">
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-info btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-info btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div><!-- /. tools -->
               <i class="fa fa-server"></i>

        <h3 class="box-title"><?php trans('Server Information', $lang['CH149']); ?></h3>
            </div><!-- /.box-header -->

           
            <div class="box-body">
             <table class="table table-striped table-bordered">
  
                <tbody> 
                
                  <tr>
                  <td><?php trans('Server IP', $lang['CH150']); ?></td>
                  <td><strong><?php echo $_SERVER['SERVER_ADDR']; ?></strong></td>
                  </tr>
                  
                  <tr>
                  <td><?php trans('Server Disk Space', $lang['CH151']); ?></td>
                  <td><strong><?php echo roundsize($ds); ?></strong></td>
                  </tr> 
                  
                  <tr>
                  <td><?php trans('Free Disk Space', $lang['CH152']); ?></td>
                  <td><strong><?php echo roundsize($df); ?></strong></td>
                  </tr>               
                  
                  <tr>
                  <td><?php trans('Disk Space used by Script', $lang['CH153']); ?></td>
                  <td><strong><?php echo roundsize(GetDirectorySize(ROOT_DIR)); ?></strong></td>
                  </tr>
                  
                  <tr>
                  <td><?php trans('Memory Used', $lang['CH154']); ?></td>
                  <td><strong><?php echo getServerMemoryUsage(); ?></strong></td>
                  </tr>               
                  
                  <tr>
                  <td><?php trans('Current CPU Load', $lang['CH155']); ?></td>
                  <td><strong><?php echo getServerCpuUsage(); ?></strong></td>
                  </tr>               
                  
                  <tr>
                  <td><?php trans('PHP Version', $lang['CH156']); ?></td>
                  <td><strong><?php echo phpversion(); ?></strong></td>
                  </tr>
                  
                  <tr>
                  <td><?php trans('MySQL Version', $lang['CH157']); ?></td>
                  <td><strong><?php echo mysqli_get_server_info($con); ?></strong></td>
                  </tr>
                  
                  <tr>
                  <td><?php trans('Database Size', $lang['CH158']); ?></td>
                  <td><strong><?php echo $database_size; ?> MB</strong></td>
                  </tr>
                  
                </tbody>
              </table>
            </div><!-- /.box-body -->
   

            <div class="box-footer">
     
            </div><!-- /.box-footer -->
        </div>
        
        <div class="box box-danger customBox">
            <div class="box-header">
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-danger btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-danger btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div><!-- /. tools -->
               <i class="fa fa-user"></i>

                <h3 class="box-title"><?php trans('Latest New Users', $lang['CH159']); ?></h3>
            </div><!-- /.box-header -->

           
            <div class="box-body">
                <table class="table table-hover table-bordered">
                    <tbody><tr>
                        <th><?php trans('Username',$lang['RF66']); ?></th>
                        <th><?php trans('Registered', $lang['CH160']); ?> On</th>
                        <th><?php trans('Country',$lang['RF127']); ?></th>
                    </tr>
                    <?php echo $newUsersData; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
   

            <div class="box-footer">
     
            </div><!-- /.box-footer -->
        </div>
        
        
        <div class="box box-warning customBox">
            <div class="box-header">
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button title="" data-toggle="tooltip" data-widget="collapse" class="btn btn-warning btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-warning btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div><!-- /. tools -->
               <i class="fa fa-paper-plane"></i>

                <h3 class="box-title"><?php trans('Script Update', $lang['CH161']); ?></h3>
            </div><!-- /.box-header -->

           
            <div class="box-body">

                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr>
                        <td><?php trans('Your Version', $lang['CH162']); ?></td>
                        <td>v<?php echo VER_NO; ?></td>
                    </tr>
                    
                    <tr>
                        <td><?php trans('Latest Version', $lang['CH163']); ?></td>
                        <td>v<?php echo $latestData['version']; ?></td>
                    </tr>
                    <tr>
                        <td><?php trans('Update', $lang['CH164']); ?></td>
                        <?php 
                        if($updater)
                            echo '<td><a href="https://codecanyon.net/downloads" target="_blank" class="btn btn-success">'.$lang['CH164'].'</a></td>';
                        else
                            echo '<td style="color: #c0392b;">'.$lang['CH165'].'</td>';
                        ?>                                           
                    </tr>
                    
                </tbody></table>
                <br />
                
                <table class="table table-hover table-bordered">
                    <tbody>
                    <tr><th class="text-center"><?php trans('Latest News', $lang['CH166']); ?></th></tr>
                    
                    <tr>
                        <td>- <?php echo $latestData['news1']; ?></td>
                    </tr>
                    <tr>
                        <td>- <?php echo $latestData['news2']; ?></td>
                    </tr>
                    
                </tbody></table>
            </div><!-- /.box-body -->
   

            <div class="box-footer">
     
            </div><!-- /.box-footer -->
        </div>

                            
      </section>
      
      </div><!-- /.Main row -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
<?php
$uVtxt = makeJavascriptStr($lang['CH167']);
$pVtxt = makeJavascriptStr($lang['CH168']);
$footerAddArr[] = <<<EOD
  <script>
  var CountX = -1;
  var area = new Morris.Area({
    element: 'pageviews-chart',
    resize: true,
    data: [
        $pageViewData
    ],
    xkey: 'y',
    ykeys: ['item1', 'item2'],
    labels: ['$uVtxt', '$pVtxt'],
    lineColors: ['#c5eea1', '#b7d5f0'],
    hideHover: 'auto',
    parseTime: false,

  });
 </script> 
EOD;
?>