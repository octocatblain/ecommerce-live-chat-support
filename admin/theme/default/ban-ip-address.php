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
                    <div class="form-group">
                        <label for="ban_ip"><?php trans('IP Address to Ban:', $lang['CH221']); ?></label>
                        <input required="" type="ip" class="form-control" id="ban_ip" name="ban_ip" placeholder="<?php trans('Enter user ip to ban', $lang['CH222']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="reason"><?php trans('Reason:', $lang['CH223']); ?> <small><?php trans('(Optional)', $lang['CH224']); ?></small></label>
                        <textarea class="form-control" id="reason" name="reason" placeholder="<?php trans('Reason to ban?', $lang['CH225']); ?>"></textarea>
                    </div>
                    <p> <?php trans('Note: Banned IP\'s can\'t able to access your chat!', $lang['CH226']); ?></p>
                </div>
                <button type="submit" class="btn btn-success"> <i class="fa fa-plus" aria-hidden="true"></i> <?php trans('Add', $lang['CH227']); ?></button>
                 </div><!-- /.box-body -->
            </form>
        </div>
                            
         <div class="box box-danger">
            <div class="box-header with-border">
                <!-- tools box -->

                <h3 class="box-title">
                    <?php trans('Recently banned IP\'s', $lang['CH228']); ?>
                </h3>
            </div>

            <div class="box-body">
                  <table id="seoToolTable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?php trans('Banned IP', $lang['CH229']); ?></th>
                        <th><?php trans('Banned Reason', $lang['CH230']); ?></th>
                        <th><?php trans('Added Date', $lang['CH231']); ?></th>
                        <th><?php trans('Delete', $lang['CH232']); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if(count($bannedList) == 0){
                        echo '<tr><td colspan="4" class="text-center">'.$lang['CH233'].'</td></tr>';
                    }else{
                        foreach($bannedList as $bannedIp){                
                            echo '<tr>
                            <td>'.$bannedIp["ip"].'</td>
                            <td>'.$bannedIp["reason"].'</td>
                            <td>'.$bannedIp["added_at"].'</td>
                            <td><a class="btn btn-danger btn-xs" onclick="return confirm(\''.$lang['CH234'].'\');" title="'.$lang['CH232'].'" href='.adminLink('ban-ip-address/delete/'.$bannedIp['id'],true).'> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].' </a></td>
                          </tr>';
                        }
                    }
                    ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
            </div>
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->