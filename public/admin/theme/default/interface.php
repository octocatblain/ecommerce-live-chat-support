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
                <div class="row" style="padding-left: 5px;">
                <div class="col-md-8">
                <br />
                <table class="table table-hover table-bordered">
                <tbody>
                <tr>
                    <td style="width: 200px;"><?php trans('Default Template', $lang['CH661']); ?></td>
                    <td><span class="badge bg-green"><?php echo $activeThemeName; ?></span></td>
                </tr> 
                    <tr>
                    <td style="width: 200px;"><?php trans('Default Language', $lang['CH662']); ?></td>
                    <td><span class="badge bg-blue"><?php echo $activeLangName . ' ('.str_replace(".PHP","",strtoupper($activeLang)).')'; ?></span></td>         
                </tr> 
                </tbody></table>
                    <br />                            
                <?php if(isset($msg)) echo $msg; ?>
                <div class="form-group">
                <label><?php trans('Change your default template', $lang['CH663']); ?>: </label>
                <select name="theme" class="form-control">
                    <?php echo $themeData; ?>
                </select>
                </div>
                                                    
                <div class="form-group">
                <label><?php trans('Change your default language', $lang['CH664']); ?>: </label>
                <select name="lang" class="form-control">
                    <?php echo $langdata; ?>
                </select>
                </div> 
                <br />
                </div></div>
                <button type="submit" class="btn btn-primary"> <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php trans('Save', $lang['CH39']); ?></button>
                <br />
                
                </div><!-- /.box-body -->
                </form>
              </div><!-- /.box -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
