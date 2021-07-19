<?php
defined('APP_NAME') or die(header('HTTP/1.0 403 Forbidden'));

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
            <div class="subRightBar">
            <?php if($pointOut === 'view-icons') { ?>
                 <a class="btn btn-success btn-sm" href="<?php adminLink($controller.'/add-icon/'.$id); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Add Icon', $lang['CH324']); ?></a>
            <?php } elseif($pointOut === 'add-icon' || $pointOut === 'edit-icon') { ?>
                <a class="btn btn-warning btn-sm" href="<?php adminLink($controller.'/view-icons/'.$id); ?>"><i class="fa fa-chevron-left"></i> &nbsp; <?php trans('Go Back',$lang['CH266']); ?></a>
            <?php } elseif($pointOut === NULL) { ?>
                  <a class="btn btn-success btn-sm" href="<?php adminLink($controller.'/create'); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Create New Emoticon Pack',$lang['CH314']); ?></a>
            <?php } ?>
            </div>
        </div><!-- /.box-header ba-la-ji -->
        <form action="#" method="POST">
            <div class="box-body">

                <?php if(isset($msg)) echo $msg; ?><br />

                <?php if($pointOut === 'add-icon' || $pointOut === 'edit-icon'){ ?>

                    <div class="form-group">
                        <label><?php trans('Emoticon Name', $lang['CH325']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter emoticon name', $lang['CH328']); ?>" required="" name="name" value="<?php echo $eData['name']; ?>" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label><?php trans('Emoticon Code', $lang['CH326']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter emoticon code', $lang['CH327']); ?>" required="" name="code" value="<?php echo $eData['code']; ?>" class="form-control"/>
                    </div>

                    <?php if($addData['type'] === 'image'){ ?>

                    <div class="form-group">
                        <label><?php trans('Emoticon URL', $lang['CH329']); ?></label>
                        <div class="input-group">
                            <input placeholder="<?php trans('Enter emoticon URL', $lang['CH330']); ?>" type="text" id="thumbnail" value="<?php echo $eData['data']; ?>" class="form-control" name="data" />
                            <span class="input-group-addon">
                            <a class="fa fa-picture-o iframe-btn" href="<?php echo $baseURL; ?>core/library/filemanager/dialog.php?type=1&field_id=thumbnail"></a>
                        </span>
                        </div>
                    </div>

                    <?php } else { ?>
                        <div class="form-group">
                            <label><?php trans('Emoticon HTML Code', $lang['CH331']); ?></label>
                            <input type="text" placeholder="<?php trans('Enter emoticon icon HTML code', $lang['CH332']); ?>" required="" name="data" value="<?php echo $eData['data']; ?>" class="form-control"/>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label><?php trans('Display emoticon to users', $lang['CH333']); ?></label>
                        <select class="form-control" name="display">
                            <option <?php echo isSelected($eData['display'], true, 1); ?> value="1"><?php trans('Yes',$lang['CH213']); ?></option>
                            <option <?php echo isSelected($eData['display'], false, 1); ?> value="0"><?php trans('No',$lang['CH214']); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php trans('Status',$lang['CH146']); ?></label>
                        <select class="form-control" name="status">
                            <option <?php echo isSelected($eData['status'], true, 1); ?> value="1"><?php trans('Enabled',$lang['CH291']); ?></option>
                            <option <?php echo isSelected($eData['status'], false, 1); ?> value="0"><?php trans('Disabled',$lang['CH290']); ?></option>
                        </select>
                    </div>
                    <?php
                    if($pointOut === 'edit-icon')
                        echo '<input class="btn btn-success" type="submit" value="'.$lang['CH39'].'">';
                    else
                        echo '<input class="btn btn-success" type="submit" value="'.$lang['CH227'].'">';
                    ?>
                    <input type="hidden" name="addEmoticon" value="1">
                <?php } elseif($pointOut === 'view-icons') { ?>
                <div class="table-fix">
                <table id="emoticons" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?php trans('ID',$lang['CH307']); ?></th>
                            <th><?php trans('Name',$lang['CH268']); ?></th>
                            <th><?php trans('Code',$lang['CH292']); ?></th>
                            <th><?php trans('Display', $lang['CH334']); ?></th>
                            <th><?php trans('Status',$lang['CH146']); ?></th>
                            <th><?php trans('Actions',$lang['CH272']); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($emoticons as $emoticon){
                            echo '<tr id="myid_'.$emoticon['id'].'">
                            <td>'.$emoticon['id'].'</td>
                            <td>'.ucfirst($emoticon['name']).'</td>
                            <td>'.$emoticon['code'].'</td>
                            <td>'.(isSelected($emoticon['display']) ? '<a href="'.adminLink($controller.'/status-display/no/'.$emoticon["id"].'/'.$id,true).'" class="label label-success">'.$lang['CH213'].'</a>' : '<a href="'.adminLink($controller.'/status-display/yes/'.$emoticon["id"].'/'.$id,true).'" class="label label-danger">'.$lang['CH214'].'</a>').'</td>
                            <td>'.(isSelected($emoticon['status']) ? '<a href="'.adminLink($controller.'/status-icon/disable/'.$emoticon["id"].'/'.$id,true).'" class="label label-success">'.$lang['CH291'].'</a>' : '<a href="'.adminLink($controller.'/status-icon/enable/'.$emoticon["id"].'/'.$id,true).'" class="label label-danger">'.$lang['CH290'].'</a>').'</td>
                            
                            <td>
                            <a class="btn btn-primary btn-xs" href="'.adminLink($controller.'/edit-icon/'.$id.'/'.$emoticon['id'],true).'"><i class="fa fa-external-link"></i> &nbsp; '.$lang['CH309'].' </a>
                            <a onclick=\'deleteItem("'.adminLink($controller.'/delete-icon/'.$emoticon['id'],true).'","myid_'.$emoticon['id'].'")\' class="btn btn-danger btn-xs"> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].' </a></td>
                            </tr>';
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
                <?php }elseif ($pointOut === 'create' || $pointOut === 'settings'){ ?>

                    <div class="form-group">
                        <label><?php trans('Pack Name', $lang['CH335']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter your emoticons pack name', $lang['CH336']); ?>" name="name" value="<?php echo $pack['name']; ?>" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label><?php trans('Emoticons Type', $lang['CH337']); ?></label>
                        <select class="form-control" name="type">
                            <option <?php isSelected($pack['type'], true, '1', 'image'); ?> value="image"><?php trans('Separate Images', $lang['CH338']); ?></option>
                            <option <?php isSelected($pack['type'], true, '1', 'sprite'); ?> value="sprite"><?php trans('CSS Image Sprites (Group)', $lang['CH339']); ?></option>
                            <option <?php isSelected($pack['type'], true, '1', 'font'); ?> value="font"><?php trans('Font Icons', $lang['CH340']); ?></option>
                        </select>
                    </div>

                    <div class="form-group hide" id="css-data">
                        <label><?php trans('CSS Data', $lang['CH341']); ?></label>
                        <textarea rows="7" placeholder="<?php trans('Enter any addtional CSS codes here', $lang['CH342']); ?>" name="" class="form-control"><?php echo $pack['data']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php trans('Status',$lang['CH146']); ?></label>
                        <select class="form-control" name="status">
                            <option <?php isSelected($pack['status'], true, '1'); ?> value="1"><?php trans('Enabled',$lang['CH291']); ?></option>
                            <option <?php isSelected($pack['status'], false, '1'); ?> value="0"><?php trans('Disabled',$lang['CH290']); ?></option>
                        </select>
                    </div>

                    <input type="hidden" name="createEmoticon" value="1">

                    <?php
                    if($pointOut === 'settings')
                        echo '<input class="btn btn-success" type="submit" value="'.$lang['CH39'].'">';
                    else
                        echo '<input class="btn btn-success" type="submit" value="'.$lang['CH343'].'">';
                    ?>

                <?php } else { ?>
                <div class="table-fix">
                <table id="emoticons" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><?php trans('ID',$lang['CH307']); ?></th>
                        <th><?php trans('Name',$lang['CH268']); ?></th>
                        <th><?php trans('Added By',$lang['CH308']); ?></th>
                        <th><?php trans('Status',$lang['CH146']); ?></th>
                        <th><?php trans('Actions',$lang['CH272']); ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data as $emoticonSet){
                    echo '<tr id="myid_'.$emoticonSet['id'].'">
                    <td>'.$emoticonSet['id'].'</td>
                    <td>'.ucfirst($emoticonSet['name']).($emoticonSet['id'] == '1' ? ' &nbsp; <span class="label label-default">'.$lang['CH346'].'</span>' : '').'</td>
                    <td>'.getAdminName($con, $emoticonSet['added_by']).'</td>
                    <td>'.(isSelected($emoticonSet['status']) ? '<a href="'.adminLink($controller.'/status/disable/'.$emoticonSet["id"],true).'" class="label label-success">'.$lang['CH291'].'</a>' : '<a href="'.adminLink($controller.'/status/enable/'.$emoticonSet["id"],true).'" class="label label-danger">'.$lang['CH290'].'</a>').'</td>
                    
                    <td>
                    <a class="btn btn-warning btn-xs" href="'.adminLink($controller.'/add-icon/'.$emoticonSet['id'],true).'"><i class="fa fa-plus"></i> &nbsp; '.$lang['CH324'].' </a>
                    <a class="btn btn-success btn-xs" href="'.adminLink($controller.'/view-icons/'.$emoticonSet['id'],true).'"><i class="fa fa-external-link"></i> &nbsp; '.$lang['CH345'].' </a>
                    <a class="btn btn-info btn-xs" href="'.adminLink($controller.'/settings/'.$emoticonSet['id'],true).'"><i class="fa fa-cog"></i> &nbsp; '.$lang['CH344'].' </a>
                    <a onclick=\'deleteItem("'.adminLink($controller.'/delete/'.$emoticonSet['id'],true).'","myid_'.$emoticonSet['id'].'")\' class="btn btn-danger btn-xs '.$disabled.'"> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].' </a></td>
                    </tr>';
                    }
                    ?>

                    </tbody>
                </table>
                </div>
                <?php } ?>

                <br />

            </div><!-- /.box-body -->
        </form>
    </div><!-- /.box -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php
if($pointOut === NULL || $pointOut === 'view-icons') {
    $footerAddArr[] = <<<EOD
    <script type="text/javascript">
      $(function () {
        $('#emoticons').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "order": [[ 0 , 'asc' ]],
          "columnDefs": [{ "targets": [0], "visible": false, "searchable": false }],
        });
      });
    </script>
EOD;
}elseif($pointOut === 'add-icon' || $pointOut === 'edit-icon'){
    if($addData['type'] === 'image'){
        $linkCss = themeLink('plugins/fancybox/jquery.fancybox.min.css',true);
        $linkjs = themeLink('plugins/fancybox/jquery.fancybox.min.js',true);
        $footerAddArr[] = <<<EOD
        <link rel="stylesheet" href="$linkCss" />
        <script src="$linkjs"></script>
        <script type="text/javascript">
         jQuery(document).ready(function ($) {         
              $('.iframe-btn').fancybox({
                'width': '75%',
                'height': '90%',
                'autoScale': false,
                'autoDimensions': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'type': 'iframe'
            });
        });
        </script>
<style>.fancybox-content{ height: 500px !important; }</style>
EOD;

    }
}elseif($pointOut === 'create' || $pointOut === 'settings'){

    $footerAddArr[] = <<<EOD
    <script> 
       var oldSel;
       $(function () {
        var selVal = $('select[name="type"]').val();
        if(selVal !== 'image'){
            $('#css-data').removeClass("hide");
            $('#css-data').fadeIn();
        }
       });
        
       $('select[name="type"]').on('change', function() {
            var selVal = $('select[name="type"]').val();
            if(selVal !== 'image'){
                $('#css-data').removeClass("hide");
                $('#css-data').fadeIn();
            }else{
                $('#css-data').fadeOut();
            }
        });
    </script>
EOD;
    
}

?>