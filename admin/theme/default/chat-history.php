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
    .rainbowChat .chat-history {
        height: 532px;
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
        <?php if($pointOut == 'view'){ ?>
            <div style="position:absolute; top:4px; right:15px;">
                <a class="btn btn-success btn-sm" href="<?php adminLink($controller); ?>"><i class="fa fa-chevron-left"></i> &nbsp; <?php trans('Go Back', $lang['CH266']); ?></a>
            </div>
        <?php } ?>
    </div><!-- /.box-header ba-la-ji -->
    <form onsubmit="return passCheck();" action="#" method="POST" id="my-form" enctype="multipart/form-data">
        <div class="box-body <?php echo ($pointOut === 'view') ? 'pinkback' : ''; ?>">
            <?php if(isset($msg)) echo $msg; ?><br />

            <?php if($pointOut === 'view'){ ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <div class="text-center"> <?php echo $htmlData['startDate'] .' - '. $htmlData['endDate']; ?> </div>
                                <div class="box-tools">
                                    <a id="exportChat" href="<?php adminLink('chat-ajax/export/'.$viewID); ?>" class="btn btn-sm btn-danger"> <span class="fa fa-file-text-o"></span> <?php trans('Export Chat', $lang['CH267']); ?></a>
                                    <a id="printChat" class="btn btn-sm btn-info"> <span class="fa fa-print"></span> <?php trans('Print Chat',$lang['CH5']); ?></a>
                                </div>
                            </div>
                            <div class="box-body no-padding" id="onlineData">
                             <div class="rainbowChat">
                                <div class="chat">
                                    <div class="chat-page">
                                        <div class="page chat-history"><?php echo $htmlData['chatHTML']; ?></div>
                                    </div>
                                </div>
                             </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">

                        <?php if(isSelected($chatDetails['admin'])) { ?>
                        <div id="masterAdminDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php trans('Details',$lang['CH81']); ?></h3>
                                </div>
                                <div class="box-body no-padding adminDetailsBody">

                                    <div class="myprofile">
                                        <br>
                                        <img class="profile-user-img img-responsive img-circle" src="<?php echo $adminDetails['logo']; ?>" alt="<?php trans('User profile picture',$lang['CH84']); ?>">
                                        <h3 class="profile-username text-center"><?php echo $adminDetails['name']; ?></h3>
                                    </div>
                                    <table class="table">
                                        <tr>
                                            <td><?php trans('Email',$lang['CH85']); ?></td>
                                            <td><?php echo $adminDetails['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Role',$lang['CH86']); ?></td>
                                            <td><?php echo $adminDetails['role']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Access',$lang['CH87']); ?></td>
                                            <td><?php echo $adminDetails['access']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Chat ID',$lang['CH88']); ?></td>
                                            <td><?php echo $adminDetails['chatID']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Location',$lang['CH89']); ?></td>
                                            <td><?php echo $adminDetails['location']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Browser',$lang['CH90']); ?></td>
                                            <td><?php echo $adminDetails['browser']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Platform',$lang['CH91']); ?></td>
                                            <td><?php echo $adminDetails['platform']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('IP',$lang['CH92']); ?></td>
                                            <td><?php echo $adminDetails['ip']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('User Agent',$lang['CH93']); ?></td>
                                            <td><?php echo $adminDetails['ua']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <?php } else { ?>
                        <div id="masterUserDetails">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php trans('Details',$lang['CH81']); ?></h3>
                                </div>
                                <div class="box-body no-padding userDetailsBody">

                                    <table class="table">
                                        <tr>
                                            <td><?php trans('Name', $lang['CH268']); ?></td>
                                            <td><?php echo $userDetails['name']; ?></td>
                                        </tr>

                                        <tr>
                                            <td><?php trans('Email',$lang['CH85']); ?></td>
                                            <td><?php echo $userDetails['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Chat ID',$lang['CH88']); ?></td>
                                            <td><?php echo $viewID; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Location',$lang['CH89']); ?></td>
                                            <td><?php echo $userDetails['location']; ?></td>
                                        </tr>

                                        <tr>
                                            <td><?php trans('Browser',$lang['CH90']); ?></td>
                                            <td><?php echo $userDetails['browser']; ?></td>
                                        </tr>

                                        <tr>
                                            <td><?php trans('Platform',$lang['CH91']); ?></td>
                                            <td><?php echo $userDetails['platform']; ?></td>
                                        </tr>

                                        <tr>
                                            <td><?php trans('IP',$lang['CH92']); ?></td>
                                            <td><?php echo $userDetails['ip']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('Screen',$lang['CH95']); ?></td>
                                            <td><?php echo $userDetails['screen']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php trans('User Agent',$lang['CH93']); ?></td>
                                            <td><?php echo $userDetails['ua']; ?></td>
                                        </tr>

                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div id="masterUserPages">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php trans('Visitors Path',$lang['CH96']); ?></h3>
                                </div>
                                <div class="box-body no-padding pagesBody">
                                    <div id="tableData"><?php echo $userPathData; ?></div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

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
                            <th><?php trans('Email',$lang['CH85']); ?></th>
                            <th><?php trans('Date', $lang['CH269']); ?></th>
                            <th><?php trans('Stats', $lang['CH270']); ?></th>
                            <th><?php trans('Department',$lang['CH145']); ?></th>
                            <th><?php trans('Rating', $lang['CH271']); ?></th>
                            <th><?php trans('Actions', $lang['CH272']); ?></th>
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
scriptLink('plugins/timeago/timeago.js',true);
$ajaxLink = adminLink('?route='.ACTIVE_LANG.'/ajax/chatHistory',true, true);
$groupDel = adminLink($controller.'/groupdel',true);
$printLink = '';
$delSelTxt = makeJavascriptStr($lang['CH273']);
if($pointOut === 'view')
    $printLink = adminLink('chat-ajax/print-chat/'.$viewID,true);

$footerAddArr[] = <<<EOD
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {

        //Print Chat
        $("#printChat").on("click", function (e) {
            window.open('$printLink', "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600,top="+(screen.height-400)+",left="+(screen.width-840));
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

if($pointOut === 'view') { ob_start(); ?>
<script type="text/javascript">
    var locale = function(number, index, total_sec) {
        return [
            ['<?php makeJavascriptStr($lang['CH61'],true); ?>', 'right now'],
            ['%s <?php makeJavascriptStr($lang['CH62'], true); ?>', 'in %s seconds'],
            ['1 <?php makeJavascriptStr($lang['CH63'], true); ?>', 'in 1 minute'],
            ['%s <?php makeJavascriptStr($lang['CH64'], true); ?>', 'in %s minutes'],
            ['1 <?php makeJavascriptStr($lang['CH65'], true); ?>', 'in 1 hour'],
            ['%s <?php trans('hours ago', $lang['CH66']); ?>', 'in %s hours'],
            ['1 <?php trans('day ago', $lang['CH67']); ?>', 'in 1 day'],
            ['%s <?php trans('days ago', $lang['CH68']); ?>', 'in %s days'],
            ['1 <?php trans('week ago', $lang['CH69']); ?>', 'in 1 week'],
            ['%s <?php trans('weeks ago', $lang['CH70']); ?>', 'in %s weeks'],
            ['1 <?php trans('month ago', $lang['CH71']); ?>', 'in 1 month'],
            ['%s <?php trans('months ago', $lang['CH72']); ?>', 'in %s months'],
            ['1 <?php trans('year ago', $lang['CH73']); ?>', 'in 1 year'],
            ['%s <?php trans('years ago', $lang['CH74']); ?>', 'in %s years']
        ][index];
    };


    $(document).ready(function () {
        timeago.register('en', locale);
        timeago().render($('.caltime'));
    });
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="/__/firebase/8.7.1/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="/__/firebase/8.7.1/firebase-analytics.js"></script>

<!-- Initialize Firebase -->
<script src="/__/firebase/init.js"></script>
<?php $contents = ob_get_contents(); ob_end_clean(); $footerAddArr[] = $contents; }  ?>