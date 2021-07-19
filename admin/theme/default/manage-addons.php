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
                  <h3 class="box-title"><?php trans('Install Addons', $lang['CH480']); ?></h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                 <?php if(isset($msg)) echo $msg. '<br>'; ?>
                <br>
                <table class="table table-bordered table-hover">
                <tbody><tr>
                    <th>#</th>
                    <th><?php trans('Name',$lang['CH268']); ?></th>
                    <th><?php trans('Status',$lang['CH146']); ?></th>
                </tr>
                <?php
                $loopC = 1;
                foreach($minMsg as $msg){
                  echo '
                  <tr>
                    <td>'.$loopC.'</td>
                    <td>'.$msg[0].'</td>
                    <td>'.$msg[1].'</td>
                  ';  
                  $loopC++;
                } 
                ?>
                 </tbody></table>
                <?php if($manualInstall) { ?>
                <hr />
                <div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3" class="text-center"><?php trans('Manually Uploaded Files', $lang['CH481']); ?></th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th><?php trans('Filename',$lang['CH438']); ?></th>
                            <th><?php trans('Actions',$lang['CH272']); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach($manualInstallFiles as $file){
                            echo '<tr id="myid_'.$i.'"><td>'.$i.'</td><td>'.$file.'</td><td><form method="POST" action="'.adminLink('process-addon',true).'"><input value="'.$file.'" type="hidden" name="addon"><button type="submit" class="btn btn-success btn-xs"> <i class="fa fa-cog"></i> &nbsp; '.$lang['CH482'].'</button>
                            <a onclick="deleteItem(\''.adminLink($controller.'/delete/'.str_replace(array('.addonpk','.zip','.zipx'), '', $file),true).'\',\'myid_'.$i.'\');" class="btn btn-danger btn-xs"> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].'</a></form>
                            </td></tr>';
                            $i++;
                        } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>

                <hr />
                <form action="#" method="POST" enctype="multipart/form-data">

                <br />
                <div class="form-group">											
					<label for="addonID"><?php trans('Select a addon package to install', $lang['CH483']); ?>:</label>
					<div class="controls">			   
                 <input type="file" name="addonUpload" id="addonUpload" class="btn btn-default" />
                 <input type="hidden" name="addonID" id="addonID" value="1" /> <br />
                 <?php if($minError){ ?>
                     <button disabled="" type="submit" class="btn btn-primary"> <i class="fa fa-cog" aria-hidden="true"></i> <?php trans('Upload & Install', $lang['CH484']); ?></button>
                 <?php } else { ?>
                     <button type="submit" class="btn btn-primary"> <i class="fa fa-cog" aria-hidden="true"></i> <?php trans('Upload & Install', $lang['CH484']); ?></button>
                 <?php } ?>
                  </div> <!-- /controls -->	

				</div> <!-- /control-group -->
                </form>
                
                <div class="row">
                <div class="col-md-6">
                <br />
                <div class="callout callout-danger">
                    <h4><?php trans('Note',$lang['CH455']); ?>!</h4>
                    <p>1) <?php echo $lang['CH485']; ?></p>
                    <p>2) <?php echo $lang['CH486']; ?></p>
                  </div>
                </div>
                </div>

                </div><!-- /.box-body -->
      
              </div><!-- /.box -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->