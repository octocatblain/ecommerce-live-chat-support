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
                    <?php if($pointOut == ''){ ?>
                    <div style="position:absolute; top:4px; right:15px;">
                        <a class="btn btn-success btn-sm" href="<?php adminLink($controller.'/add'); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Create New Role', $lang['CH575']); ?></a>
                    </div>
                    <?php } ?>
            </div><!-- /.box-header ba-la-ji -->
            <form action="#" method="POST">
                <div class="box-body">

                    <?php if(isset($msg)) echo $msg; ?><br />

                    <?php if($pointOut == 'add' || $pointOut == 'edit'){ ?>

                        <div class="col-md-offset-1 col-md-9">
                        <div class="form-group">
                            <label><?php trans('Role Name', $lang['CH576']); ?></label>
                            <input value="<?php echo $data['name']; ?>" required="required" type="text" placeholder="<?php trans('Type role name', $lang['CH579']); ?>" name="name" id="name" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label> <?php trans('Access Type', $lang['CH577']); ?> </label>
                            <select name="sel_access" class="form-control">
                                <option <?php isSelected($data['data'][0], true, 1, 'all'); ?> value="all"><?php trans('Global (All)', $lang['CH580']); ?></option>
                                <option <?php isSelected($data['data'][0], false, 1, 'all'); ?> value="restricted"><?php trans('Restricted',$lang['CH369']); ?></option>
                            </select>
                        </div>

                        <div class="form-group hide" id="restricted">
                            <label><?php trans('Provide access for following pages', $lang['CH578']); ?>:</label>
                            <select name="data[]" class="form-control select2" multiple="multiple" data-placeholder="<?php trans('Which pages are allowed to access?',$lang['CH581']); ?>" style="width: 100%;">
                                <?php
                                foreach($menuLinks as $link){
                                    if(in_array($link[1],$data['data']))
                                        echo '<option selected="" value="'.$link[1].'">'.$link[0].'</option>';
                                    else
                                        echo '<option value="'.$link[1].'">'.$link[0].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <br />
                        <?php if($pointOut == 'edit') { ?>
                            <input type="hidden" value="<?php echo $editID; ?>" name="editID" />
                            <input type="submit" name="save" value="<?php trans('Update Admin Role', $lang['CH582']); ?>" class="btn btn-primary"/>
                        <?php } else { ?>
                            <input type="submit" name="save" value="<?php trans('Create Admin Role', $lang['CH583']); ?>" class="btn btn-primary"/>
                        <?php } ?>
                        <a class="btn btn-danger" href="<?php adminLink($controller); ?>">Cancel</a>
                        <br />
                        <br />
                        </div>
                    <?php } else { ?>

                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <th>#</th>
                            <th><?php trans('Role',$lang['CH86']); ?></th>
                            <th><?php trans('Page Access',$lang['CH534']); ?></th>
                            <th><?php trans('Created on', $lang['CH584']); ?></th>
                            <th><?php trans('Actions',$lang['CH272']); ?></th>
                            </thead>
                            <tbody>
                            <?php $loop=1; while ($row = mysqli_fetch_assoc($result)) {
                                $data = dbStrToArr($row['data']);
                                $disabled = $accessStr = ''; if($loop == 1) $disabled = 'disabled="" ';
                                if($data[0] == 'all') $accessStr = '<span class="label label-success">'.$lang['CH370'].'</span>'; else $accessStr = '<span class="label label-danger">'.$lang['CH369'].'</span>';
                                echo '<tr id="myid'.$loop.'">
                                    <td style="width: 100px;">'.$loop.'</td>
                                    <td>'.strtoupper($row['name']).'</td>
                                    <td>'.$accessStr.'</td>
                                    <td>'.$row['added_date'].'</td>
                                    <td style="width: 200px;">
                                    <a '.$disabled.' class="btn btn-info btn-xs" href="'.adminLink($controller.'/edit/'.$row['id'],true).'" title="'.$lang['CH585'].' "><i class="fa fa-edit"></i> '.$lang['CH309'].'</a>
                                    <a '.$disabled.' onclick="deleteItem(\''.adminLink($controller.'/delete/'.$row['id'],true).'\',\'myid'.$loop.'\')" class="btn btn-danger btn-xs" title="'.$lang['CH586'].'"><i class="fa fa-trash-o"></i> '.$lang['CH232'].'</a>
                                    </td>
                                    </tr>';
                                    $loop++;
                            }?>
                            </tbody>
                        </table>
                    <?php } ?>

                    <br />

                </div><!-- /.box-body -->
            </form>
        </div><!-- /.box -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php
$footerAddArr[] = <<<EOD
    <script> 
        var selVal;
        
        function disableRestricted(){
            selVal = jQuery('select[name="sel_access"]').val();
            if(selVal === 'restricted'){
                $('#restricted').removeClass("hide");
                $('#restricted').fadeIn();
            }else{
            $('#restricted').fadeOut();
            }  
        }
        
       $(function () {
        $(".select2").select2();
        disableRestricted();
       });
        
       $('select[name="sel_access"]').on('change', function() { disableRestricted(); });
    </script>
EOD;
?>