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

        <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="<?php echo $page1; ?>"><a href="#adminPages" data-toggle="tab"><?php trans('Overview', $lang['CH527']); ?></a></li>
                  <li class="<?php echo $page2; ?>"><a href="#pass-change" data-toggle="tab"><?php trans('Update Account', $lang['CH528']); ?></a></li>
                  <li class="<?php echo $page3; ?>"><a href="#avatar" data-toggle="tab"><?php trans('Change Avatar', $lang['CH529']); ?></a></li>
                </ul>
                <div class="tab-content">
                
                <div class="tab-pane <?php echo $page1; ?>" id="adminPages" >
                <br />
                <?php if(isset($msg)) echo $msg; ?>
                <div class="row">
                <div class="col-md-2">
                <table class="table table-hover table-bordered">
                <tbody>
                    <tr>
                        <td><img width="180px" height="180px" src="<?php createLink($adminLogo,false,true); ?>" alt="<?php trans('Admin Logo', $lang['CH530']); ?>" /></td>
                    </tr> 
                </tbody>
                </table>
                </div>
                <div class="col-md-6" style="margin-left: 30px;">
                <table class="table table-hover table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 200px;"><?php trans('Admin Name', $lang['CH531']); ?></td>
                        <td><span><?php echo $adminName; ?></span></td>
                    </tr> 
                    <tr>
                        <td style="width: 200px;"><?php trans('Admin User ID', $lang['CH532']); ?></td>
                        <td><span><?php echo $adminData['user']; ?></span></td>
                    </tr>
                    <tr>
                        <td style="width: 200px;"><?php trans('Admin Group', $lang['CH533']); ?></td>
                        <td><span><?php echo $adminInfo['Role']; ?></span></td>
                    </tr>
                    <tr>
                        <td style="width: 200px;"><?php trans('Page Access', $lang['CH534']); ?></td>
                        <td><span><?php echo $privilegeBox; ?></span></td>
                    </tr>
                    <tr>
                        <td style="width: 200px;"><?php trans('Registration Date', $lang['CH535']); ?></td>
                        <td><span><?php echo $adminData['reg_date']; ?></span></td>
                    </tr> 
                    <tr>
                        <td style="width: 200px;"><?php trans('Registration IP', $lang['CH536']); ?></td>
                        <td><span><?php echo $adminData['reg_ip']; ?></span></td>
                    </tr> 
                    <tr>
                        <td style="width: 200px;"><?php trans('Last Login Date', $lang['CH537']); ?></td>
                        <td><span><?php echo $adminLog['logged_time']; ?></span></td>
                    </tr> 
                    <tr>
                        <td style="width: 200px;"><?php trans('Last Active IP', $lang['CH538']); ?></td>
                        <td><span><?php echo $adminLog['ip']; ?></span></td>
                    </tr> 
                </tbody>
                </table>
                </div>
                </div>

                <br />
                </div>
                
                <div class="tab-pane <?php echo $page2; ?>" id="pass-change" >
                
                <form action="#" method="POST">
                <div class="box-body">
                <?php if(isset($msg)) echo $msg; ?>
                
                <div class="form-group">
                    <label for="admin_user"><?php trans('Admin ID', $lang['CH539']); ?></label>
                    <input type="email" placeholder="<?php trans('Enter your email id',$lang['RF151']); ?>" value="<?php echo $adminData['user']; ?>" name="admin_user" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="admin_name"><?php trans('Admin Name',$lang['CH531']); ?></label>
                    <input type="text" placeholder="<?php trans('Enter your admin name', $lang['CH540']); ?>" value="<?php echo $adminName; ?>" name="admin_name" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="new_pass"><?php trans('New Password',$lang['RF135']); ?></label>
                    <input type="password" placeholder="<?php trans('Enter your new password',$lang['RF139']); ?>" name="new_pass" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="retype_pass"><?php trans('Retype Password',$lang['RF136']); ?></label>
                    <input type="password" placeholder="<?php trans('Retype the new password', $lang['CH543']); ?>" name="retype_pass" class="form-control" />
                </div>
                
                <hr />
                
                <div class="form-group">
                    <label for="old_pass"><?php trans('Old Password', $lang['CH541']); ?></label>
                    <input type="password" placeholder="<?php trans('Enter your old admin panel password', $lang['CH542']); ?>" name="old_pass" class="form-control" />
                </div>
                <input type="hidden" name="passChange" value="1" />
                <input type="submit" name="save" value="<?php trans('Save',$lang['CH39']); ?>" class="btn btn-primary"/>
                <br />
                
                </div><!-- /.box-body -->
            </form>
                </div>
                
                <div class="tab-pane <?php echo $page3; ?>" id="avatar" >
                <br />
                <div class="box-body">
                <?php if(isset($msg)) echo $msg; ?>
                <form id="theme_id" method="POST" action="<?php adminLink($controller); ?>" enctype="multipart/form-data">
                    <div class="form-group">											
    					<label for="logoID"><?php trans('Select logo to upload', $lang['CH544']); ?>:</label>
    					<div class="controls">			   
                         <img id="userLogoBox" src="<?php createLink($adminLogo,false,true); ?>" style="text-align:center;"/> <br /><br />
                         <input type="file" name="logoUpload" id="logoUpload" class="btn btn-default" />
                         <input type="hidden" name="logoID" id="logoID" value="1" /> <br />
                         <input type="submit" value="<?php trans('Upload Image', $lang['CH545']); ?>" name="submit" class="btn btn-primary" />
                      </div> <!-- /controls -->	
    
    				</div> <!-- /control-group -->
                </form>  
                
              </div>
              
                </div>
            
                </div>
            </div>
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php
$footerAddArr[] = <<<EOD
<script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {

        $("#logoUpload").change(function(){
            readURL(this,'#userLogoBox');
        });
    } );
</script>
EOD;
?>