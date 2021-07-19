<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<style>
.alert-success {
    background-color: #dff0d8 !important;
    border-color: #d6e9c6 !important;
    color: #3c763d !important;
}
.lineCode{
    padding: 5px;
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
          
            <?php if(isset($msg)) echo $msg; ?>
                
                <div class="row">

                    <div class="col-md-12">
                    
                    <div class="alert alert-info">
                        <strong><?php trans('Note',$lang['CH455']); ?>: </strong> <?php trans('Short Codes also supported inside mail templates!', $lang['CH608']); ?>
                    </div>
                        <h4><?php trans('Password Reset - Mail Template', $lang['CH609']); ?></h4><br />
                    </div>
                                        
                    <div class="col-md-8">
                        <div class="form-group">
                          <label for="passwordSub"><?php trans('Subject', $lang['CH610']); ?></label>
                          <input class="form-control" type="text" name="passwordSub" value="<?php echo $passwordSub; ?>" />
                        </div>
                        
                        <div class="form-group">
                          <label for="password"><?php trans('Mail Content', $lang['CH611']); ?></label>
                          <textarea id="password" name="password" class="form-control"><?php echo $passwordMail; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label><?php trans('Replacement Codes', $lang['CH612']); ?></label>
                        
                        <div class="well alert-success">
                            <div class="lineCode"><b>{SiteName}</b> - <?php trans('Returns your site name', $lang['CH613']); ?><br /></div>
                            <div class="lineCode"><b>{FullName}</b> - <?php trans('Returns customers name', $lang['CH614']); ?><br /></div>
                            <div class="lineCode"><b>{UserName}</b> - <?php trans('Returns customers username', $lang['CH615']); ?><br /></div>
                            <div class="lineCode"><b>{NewPassword}</b> - <?php trans('Returns new password', $lang['CH616']); ?><br /></div>
                            <div class="lineCode"><b>{UserEmailId}</b> - <?php trans('Returns customers mail id', $lang['CH617']); ?><br /></div>
                        </div>
                        
                    </div>
                
                    <div class="col-md-12">
                        <br /><br />
                        <input type="submit" name="save" value="<?php trans('Update Templates', $lang['CH607']); ?>" class="btn btn-primary"/>
                        <br /><br />
                    </div>
                </div>
                </div><!-- /.box-body -->
                </form>

              </div><!-- /.box -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<?php
$filebrowserBrowseUrl = createLink('core/library/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',true);
$filebrowserUploadUrl = createLink('core/library/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',true);
$filebrowserImageBrowseUrl = createLink('core/library/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',true);
$footerAddArr[] = <<<EOD
<script type="text/javascript">
    $(function () {
    CKEDITOR.replace('password',{ filebrowserBrowseUrl : '$filebrowserBrowseUrl', filebrowserUploadUrl : '$filebrowserUploadUrl', filebrowserImageBrowseUrl : '$filebrowserImageBrowseUrl', toolbar : 'Basic' });
    CKEDITOR.on( 'dialogDefinition', function( ev ) {
      var dialogName = ev.data.name;
      var dialogDefinition = ev.data.definition;
      if ( dialogName == 'link' || dialogName == 'image' ){
         dialogDefinition.removeContents( 'Upload' );
      }
   });
   });
</script>
EOD;
?>