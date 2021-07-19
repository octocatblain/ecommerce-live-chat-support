<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


?>
<style>
    table.dataTable {
        margin-top: 18px !important;
    }
    .dataTables_filter{
        display: inline-block;
        float: right;
    }
    .dataTables_length{
        display: inline-block;
        float: left;
    }
    .toolbar {
        display: inline-block;
        margin-left: 20px;
    }
</style>

<script src="<?php themeLink('dist/js/validator.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">$(function () { $('#my-form').validator(); });</script>

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
                        <a class="btn btn-success btn-sm" href="<?php adminLink($controller.'/add'); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Add New Admin', $lang['CH546']); ?></a>
                    </div>
                <?php } ?>
            </div><!-- /.box-header ba-la-ji -->
            <form onsubmit="return passCheck();" action="#" method="POST" id="my-form" enctype="multipart/form-data">
            <div class="box-body">
                <?php if(isset($msg)) echo $msg; ?><br />

                <?php if($pointOut === 'add' || $pointOut === 'edit'){ ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php trans('Name',$lang['CH268']); ?></label>
                                <input value="<?php echo $myValues['name']; ?>" required="required" type="text" placeholder="<?php trans('Type admin name', $lang['CH552']); ?>" name="name" id="name" class="form-control" />
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label><?php trans('Username (Email ID)', $lang['CH547']); ?></label>
                                <input value="<?php echo $myValues['user']; ?>" required="required" type="email" placeholder="<?php trans('Type admin email id', $lang['CH553']); ?>" name="user" id="user" class="form-control" />
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label> <?php trans('Admin Group (Privilege)', $lang['CH548']); ?> </label>
                                <select name="role" class="form-control">
                                    <?php foreach ($roles as $role){
                                        echo'<option '.isSelected($myValues['role'], true, 1, $role['id'],true).' value="'.$role['id'].'">'.$role['name'].'</option>';
                                    } ?>
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php trans('Password',$lang['RF67']); ?></label>
                                <input value="<?php echo $myValues['pass'] ; ?>" required="required" type="password" placeholder="<?php trans('Type your password', $lang['CH551']); ?>" name="pass" id="pass" class="form-control" />
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label><?php trans('Retype Password',$lang['RF136']); ?></label>
                                <input value="<?php echo $myValues['repass'] ; ?>" required="required" type="password" placeholder="<?php trans('Retype your password for confirmation', $lang['CH550']); ?>" name="repass" id="repass" class="form-control" />
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="logoID"><?php trans('Select logo to upload',$lang['CH544']); ?>:</label>
                                        <div class="controls">
                                            <input type="file" name="userLogo" id="userLogo" class="btn btn-default" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <img id="userLogoBox" src="<?php createLink($myValues['logo'],false,true); ?>" width="100px" height="100px" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php if($pointOut === 'add'){ ?>
                            <input type="submit" name="save" value="<?php trans('Create Account', $lang['CH549']); ?>" class="btn btn-primary"/>
                            <?php } else { ?>
                                <input type="submit" name="save" value="<?php trans('Update Account',$lang['CH528']); ?>" class="btn btn-primary"/>
                                <input type="hidden" value="<?php echo $args[0]; ?>" name="edit" id="edit">
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="mySitesTable">
                        <thead>
                        <tr>
                            <th><input name="select-all" value="1" id="select-all" type="checkbox" /></th>
                            <th><?php trans('Name',$lang['CH268']); ?></th>
                            <th><?php trans('Username',$lang['RF66']); ?></th>
                            <th><?php trans('Joined Date',$lang['CH517']); ?></th>
                            <th><?php trans('Role',$lang['CH86']); ?></th>
                            <th><?php trans('Access',$lang['CH87']); ?></th>
                            <th><?php trans('Actions',$lang['CH272']); ?></th>
                        </tr>
                        </thead>
                        <tbody>
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
$ajaxLink = adminLink('?route='.ACTIVE_LANG.'/ajax/manageAdmins',true,true);
$groupDel = adminLink($controller.'/groupdel',true);
$passwordConf = makeJavascriptStr($lang['CH526']);
$oopsTxt = makeJavascriptStr($lang['RF82']);
$delSelTxt = makeJavascriptStr($lang['CH273']);
$footerAddArr[] = <<<EOD
        <script type="text/javascript">
        
        function passCheck(){
            var myPass = $('#pass').val();
            var rePass = $('#repass').val();
            if(myPass !== rePass){
                sweetAlert('$oopsTxt','$passwordConf','error');
                return false;
            }
            return true;
        }
        </script>
EOD;

$footerAddArr[] = <<<EOD
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {

        $("#userLogo").change(function(){
            readURL(this,'#userLogoBox');
        });
        
        function showDeleteBtn(){
            if($('input[name="id[]"]:checked').length > 0){
                $('#delBtn').removeClass("hide");
                $('#delBtn').fadeIn();
            }else{
                $('#delBtn').fadeOut();
                $('#select-all').prop('checked', false);
            }
        }
        
    	var myTable = $('#mySitesTable').DataTable({
    		processing: true,
    		serverSide: true,
    		ajax: "$ajaxLink",
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0,
                searchable: false,
                render: function (data, type, full, meta){
                    if(full.id === '1')
                        return '<input disabled="" type="checkbox" name="id[]" value="' + full.id + '">';
                    else
                        return '<input type="checkbox" name="id[]" value="' + full.id + '">';
                 }
            },  { targets: '_id', visible: false } ],
            select: {
                style: 'multi'
            },
            order: [[ 1, 'asc' ]],
           dom: '<"toolbar">lfrtip',
           initComplete: function(){
              $("div.toolbar")
                 .html('<button id="delBtn" class="btn btn-danger btn-sm hide"><i class="fa fa-trash-o"></i> &nbsp; $delSelTxt</button>');           
           }   
    	});
        
        $(document).on('change', 'input[type="checkbox"]', function () {
            showDeleteBtn();
        });
   
    	$('#select-all').on('click', function(){
           var rows = myTable.rows({ 'search': 'applied' }).nodes();
           $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    	
    	$(document).on("click","#delBtn",function(e){
    	    var checkData = myTable.$('input[type="checkbox"]').serialize();
    	    groupDelItem('$groupDel', checkData);
    	    return false;
    	});
    	
    } );
    </script>    
EOD;
?>