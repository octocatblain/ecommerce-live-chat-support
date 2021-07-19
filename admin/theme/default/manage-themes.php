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
                <?php if($pointOut === 'clone'){ if(isset($log)) {  ?>
                <textarea readonly="" id="tableRes" rows="12" class="form-control"><?php echo $logData; ?></textarea>
                <br>
                <a class="btn btn-danger" href="<?php adminLink($controller); ?>"><?php trans('Go to Manage Themes', $lang['CH675']); ?></a>
                <br>
                <br>
                <?php } else { ?>
                <div class="form-group">
                    <label><?php trans('Theme Name', $lang['CH676']); ?>:</label>
                    <input value="<?php echo $themeDetails['name']; ?> Clone" required="required" type="text" placeholder="<?php trans('Type theme name', $lang['CH682']); ?>" name="theme[name]" class="form-control" />
                </div>

                <div class="form-group">
                    <label><?php trans('Theme Directory Name', $lang['CH677']); ?>: <small><?php trans('(Only Alphanumeric characters)', $lang['CH679']); ?></small></label>
                    <input value="<?php echo $args[0]; ?>clone" pattern="[a-zA-Z0-9 ]+" required="required" type="text" placeholder="<?php trans('Type directory name', $lang['CH683']); ?>" name="theme[dir]" class="form-control" />
                </div>

                <div class="form-group">
                    <label><?php trans('Description',$lang['CH368']); ?>:</label>
                    <input value="<?php echo $themeDetails['description']; ?>" required="required" type="text" placeholder="<?php trans('Type theme description', $lang['CH684']); ?>" name="theme[des]" class="form-control" />
                </div>

                <div class="form-group">
                    <label><?php trans('Your Name',$lang['CH21']); ?>:</label>
                    <input value="<?php echo $themeDetails['author']; ?>" required="required" type="text" placeholder="<?php trans('Type author name', $lang['CH685']); ?>" name="theme[author]" class="form-control" />
                </div>

                <div class="form-group">
                    <label><?php trans('Your Email ID',$lang['CH22']); ?>:</label>
                    <input value="<?php echo $themeDetails['authorEmail']; ?>" required="required" type="text" placeholder="<?php trans('Type author email', $lang['CH686']); ?>" name="theme[email]" class="form-control" />
                </div>

                <div class="form-group">
                    <label><?php trans('Your Website Link', $lang['CH678']); ?>:</label>
                    <input value="<?php echo $themeDetails['authorWebsite']; ?>" required="required" type="text" placeholder="<?php trans('Type author website link', $lang['CH687']); ?>" name="theme[link]" class="form-control" />
                </div>

                <div class="form-group">
                    <label><?php trans('Copyright', $lang['CH680']); ?>:</label>
                    <input value="<?php echo $themeDetails['copyright']; ?>" required="required" type="text" placeholder="<?php trans('Type author website link', $lang['CH687']); ?>" name="theme[copy]" class="form-control" />
                </div>
                <table class="table table-bordered">
                    <tbody><tr>
                        <th>#</th>
                        <th><?php trans('Required directory permissions', $lang['CH681']); ?></th>
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
                <br />
                <input <?php if($minError) echo ' disabled="" '; ?> type="submit" name="save" value="<?php trans('Clone Theme',$lang['CH668']); ?>" class="btn btn-success"/>
                <a class="btn btn-danger" href="<?php adminLink($controller); ?>"><?php trans('Cancel',$lang['CH40']); ?></a>
                <br />
                <br />

                <?php } } else { ?>
<div class="row">
<?php 
    foreach(getThemeList() as $themes){ 
    $themeDirRaw = $themes[0]; $themeDir = $themes[1]; $themeDetails = $themes[2]; $previewLink = '';

    if(file_exists($themeDir.D_S.$themeDetails['preview']))
        $previewLink = createLink('theme/'.$themeDirRaw.'/'.$themeDetails['preview'],true,true);
    else
        $previewLink  = createLink('core/library/img/no-preview.png',true);;
?>
  
    <div class="col-sm-5 col-md-3">
        <div class="panel panel-white themePanel">
            <div class="panel-body themePanelBody">
                <img src="<?php echo $previewLink; ?>" alt="<?php echo $themeDetails['description']; ?>" class="screenPreview img-responsive" />
            </div>
            <div class="panel-footer">
                <h4 class="remove-margin-bottom">
                    <a href="" class="theme-title"><?php echo $themeDetails['name']; ?></a> <span class="font-small author">(<?php trans('By', $lang['CH692']); ?> <?php echo $themeDetails['author']; ?>)</span>
                </h4>

                <div class="clearfix font-small purchases-col">
                    <div class="pull-right">
                        <?php if($defaultTheme == $themes[0]){ ?>
                        <a class="btn btn-primary btn-xs disabled" href=""> <i class="fa fa-paint-brush"></i> &nbsp; <?php trans('Active', $lang['CH691']); ?> </a>
                        <?php } else{ ?>
                        <a class="btn btn-primary btn-xs" onclick="return confirm('<?php makeJavascriptStr($lang['CH689'], true); ?>');" href="<?php adminLink('ajax/theme/set/frontend/'.$themes[0]); ?>"> <i class="fa fa-paint-brush"></i> &nbsp; <?php trans('Apply', $lang['CH688']); ?> </a>
                        <?php } ?>
                        <a href="<?php adminLink($controller.'/clone/'.$themes[0]); ?>" class="btn btn-warning btn-xs"> <i class="fa fa-copy"></i>&nbsp; <?php trans('Clone', $lang['CH690']); ?></a>
                        <a href="<?php createLink('templates/preview/'.$themes[0]); ?>" target="_blank" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i> &nbsp; <?php trans('Preview',$lang['CH118']); ?> </a>
                        <?php if(!nullCheck($themeDetails['builder'])){ ?>
                        <a target="_blank" class="btn btn-danger btn-xs" href="<?php adminLink($themeDetails['builder']); ?>"> <i class="fa fa-edit"></i> &nbsp; <?php trans('Edit',$lang['CH309']); ?> </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php } ?>

</div>
                <?php } ?>
             <br />
            </div><!-- /.box-body -->
            </form>
          </div><!-- /.box -->
  
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->