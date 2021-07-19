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
        <div style="position:absolute; top:4px; right:15px;">
        <?php if($pointOut === 'add' || $pointOut === 'edit'){ ?>
            <a class="btn btn-success btn-sm" href="<?php adminLink($controller); ?>"><i class="fa fa-chevron-left"></i> &nbsp; <?php trans('Go Back',$lang['CH266']); ?></a>
        <?php } else{ ?>
            <a class="btn btn-success btn-sm" href="<?php adminLink($controller . '/add'); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Add Canned Message',$lang['CH280']); ?></a>
        <?php } ?>
        </div>
    </div><!-- /.box-header ba-la-ji -->
    <form action="#" method="POST" id="my-form" enctype="multipart/form-data">
        <div class="box-body">
            <?php if(isset($msg)) echo $msg; ?><br />

            <?php if($pointOut === 'add' || $pointOut === 'edit'){ ?>

                <div class="row">
                    <div class="col-md-8">
                        <?php
                            adminInputTxt('a[code]', 'code', $lang['CH286'], $lang['CH288'], $a['code']);
                            adminTextArea('a[data]', 'data', $lang['CH287'], $lang['CH289'], $a['data'], 'rows="8"');
                            adminSelect('a[status]','status',$lang['CH146'], array($lang['CH290'], $lang['CH291']), $a['status'], true);
                        ?>
                        <input type="submit" name="save" value="<?php trans('Save Settings',$lang['CH187']); ?>" class="btn btn-primary"/>
                        <a class="btn btn-warning" href="<?php adminLink($controller); ?>">Cancel</a>
                    </div>
                </div>

            <?php } else { ?>
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="mySitesTable">
                        <thead>
                        <tr>
                            <th><input name="select-all" value="1" id="select-all" type="checkbox" /></th>
                            <th><?php trans('Code', $lang['CH292']); ?></th>
                            <th><?php trans('Message',$lang['CH287']); ?></th>
                            <th><?php trans('Date',$lang['CH269']); ?></th>
                            <th><?php trans('Admin',$lang['CH78']); ?></th>
                            <th><?php trans('Stats',$lang['CH270']); ?></th>
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
$ajaxLink = adminLink('?route='.ACTIVE_LANG.'/ajax/cannedMsg',true,true);
$groupDel = adminLink($controller.'/groupdel',true);
$delSelTxt = makeJavascriptStr($lang['CH273']);

$footerAddArr[] = <<<EOD
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {

        
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
                    return '<input '+ full.disabled +' type="checkbox" name="id[]" value="' + full.id + '">';
                 }
            },  { targets: '_id', visible: false } ],
            select: {
                style: 'multi'
            },
            order: [[ 6, 'desc' ]],
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