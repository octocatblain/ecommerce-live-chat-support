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
            <?php if($pointOut === NULL) { ?>
                  <a class="btn btn-info btn-sm" href="<?php adminLink($controller.'/add'); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Add New Notification Tone', $lang['CH354']); ?></a>
            <?php } ?>
            </div>
        </div><!-- /.box-header ba-la-ji -->
        <form action="#" method="POST">
            <div class="box-body">

                <?php if(isset($msg)) echo $msg; ?><br />

                <?php if ($pointOut === 'add' || $pointOut === 'edit'){ ?>

                    <div class="form-group">
                        <label><?php trans('Tone Name', $lang['CH355']); ?></label>
                        <input type="text" placeholder="<?php trans('Enter your tone name', $lang['CH356']); ?>" name="name" value="<?php echo $pack['name']; ?>" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label><?php trans('Tone URL', $lang['CH357']); ?></label>
                        <div class="input-group">
                            <input placeholder="<?php trans('Enter tone URL', $lang['CH358']); ?>" type="text" id="path" value="<?php echo $pack['path']; ?>" class="form-control" name="path" />
                            <span class="input-group-addon">
                            <a class="fa fa-file-audio-o iframe-btn" href="<?php echo $baseURL; ?>core/library/filemanager/dialog.php?type=3&field_id=path"></a>
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?php trans('Status',$lang['CH146']); ?></label>
                        <select class="form-control" name="status">
                            <option <?php isSelected($pack['status'], true, '1'); ?> value="1"><?php trans('Enabled',$lang['CH291']); ?></option>
                            <option <?php isSelected($pack['status'], false, '1'); ?> value="0"><?php trans('Disabled',$lang['CH290']); ?></option>
                        </select>
                    </div>

                    <input type="hidden" name="addTone" value="1">

                    <?php
                    if($pointOut === 'edit')
                        echo '<input class="btn btn-success" type="submit" value="'.$lang['CH39'].'">';
                    else
                        echo '<input class="btn btn-success" type="submit" value="'.$lang['CH227'].'">';
                    ?>
                    <a class="btn btn-danger" href="<?php adminLink($controller); ?>"><?php trans('Cancel',$lang['CH40']); ?></a>
                <?php } else { ?>
                <div class="table-fix">
                <table id="notifications" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="hide"><?php trans('ID',$lang['CH307']); ?></th>
                        <th><?php trans('Name',$lang['CH268']); ?></th>
                        <th><?php trans('Playback', $lang['CH359']); ?></th>
                        <th><?php trans('Added By',$lang['CH308']); ?></th>
                        <th><?php trans('Status',$lang['CH146']); ?></th>
                        <th><?php trans('Actions',$lang['CH272']); ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data as $toneSet){
                    echo '<tr id="myid_'.$toneSet['id'].'">
                    <td class="hide">'.$toneSet['id'].'</td>
                    <td>'.ucfirst($toneSet['name']).'</td>
                    <td><audio class="tone" controls preload="none"><source src="'.fixLink($toneSet['path'], true).'" type="audio/mpeg"></audio></td>
                    <td>'.getAdminName($con, $toneSet['added_by']).'</td>
                    <td>'.(isSelected($toneSet['status']) ? '<a href="'.adminLink($controller.'/status/disable/'.$toneSet["id"],true).'" class="label label-success">'.$lang['CH291'].'</a>' : '<a href="'.adminLink($controller.'/status/enable/'.$toneSet["id"],true).'" class="label label-danger">'.$lang['CH290'].'</a>').'</td>
                    
                    <td>
                    <a class="btn btn-info btn-xs" href="'.adminLink($controller.'/edit/'.$toneSet['id'],true).'"><i class="fa fa-external-link"></i> &nbsp; '.$lang['CH309'].' </a>
                    <a onclick=\'deleteItem("'.adminLink($controller.'/delete/'.$toneSet['id'],true).'","myid_'.$toneSet['id'].'")\' class="btn btn-danger btn-xs '.$disabled.'"> <i class="fa fa-trash-o"></i> &nbsp; '.$lang['CH232'].' </a></td>
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
if($pointOut === NULL) {
    $footerAddArr[] = <<<EOD
    <script type="text/javascript">
      $(function () {
        $('#notifications').on( 'page.dt', function () {
            $('html, body').animate({
                scrollTop: 0
            }, 200);        
        });
        $('#notifications').DataTable({
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
}elseif($pointOut === 'add' || $pointOut === 'edit'){
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
?>