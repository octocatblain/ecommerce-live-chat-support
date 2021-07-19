<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<style>
    .textIn{
        width: 100% !important;
    }
    .alert a {
        color: #fff;
        text-decoration: none;
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
              <?php if($pointOut == 'edit') { ?>
                <div style="position:absolute; top:4px; right:15px;">
                  <a class="btn btn-primary btn-sm" href="<?php adminLink($controller.'/add-custom-text/'.$args[0]); ?>"><i class="fa fa-plus-square-o"></i> &nbsp; <?php trans('Add Custom Text', $lang['CH707']); ?></a>
                  <a class="btn btn-danger btn-sm" href="<?php adminLink($controller.'/backup/'.$args[0]); ?>"><i class="fa fa-download"></i> &nbsp; <?php trans('Backup Language', $lang['CH709']); ?></a>
                </div>
              <?php } elseif($pointOut == '') { ?>
                <div style="position:absolute; top:4px; right:15px;">
                  <a class="btn btn-success btn-sm" href="<?php adminLink($controller.'/add'); ?>"><i class="fa fa-plus"></i> &nbsp; <?php trans('Create New Language',$lang['CH703']); ?></a>
                  <a class="btn btn-primary btn-sm" href="<?php adminLink($controller.'/add-custom-text'); ?>"><i class="fa fa-plus-square-o"></i> &nbsp; <?php trans('Add Custom Text', $lang['CH707']); ?></a>
                  <a class="btn btn-warning btn-sm" href="<?php adminLink($controller.'/import'); ?>"><i class="fa fa-upload"></i> &nbsp; <?php trans('Import Language', $lang['CH708']); ?></a>
              </div>
              <?php } ?>
            </div><!-- /.box-header ba-la-ji -->
            <form id="editForm" action="#" method="POST" <?php if($pointOut == 'import') { echo 'enctype="multipart/form-data"';} ?>>
            <div class="box-body">
          
            <?php if(isset($msg)) echo $msg; ?><br />
            
            <?php if($pointOut == 'edit'){ ?>
            
           <div class="box-header with-border">
            <h3 class="box-title"><?php trans('General Settings',$lang['CH588']); ?></h3>
           </div>
           
           <br />
           <input type="hidden" name="oldLangCode" id="oldLangCode" value="<?php echo $args[0];?>">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td><label for="language_name" class="newCenter"><?php trans('Language Name',$lang['CH710']); ?>:</label></td>
                        <td><input required="required" value="<?php echo $generalLangSet[3]; ?>" type="text" placeholder="<?php trans('Type language name', $lang['CH711']); ?>" name="language_name" id="language_name" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td><label for="language_code" class="newCenter"><?php trans('Language Code', $lang['CH712']); ?>: </label></td>
                        <td><input <?php echo ($args[0] == $defaultLang) ? 'disabled' : ''; ?> required="required" value="<?php echo $generalLangSet[2]; ?>" maxlength="2" type="text" placeholder="<?php trans('Type language code', $lang['CH713']); ?>" id="language_code" name="language_code" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td><label for="sort_order" class="newCenter"><?php trans('Sort Order', $lang['CH714']); ?>:</label></td>
                        <td><input type="text" name="sort_order" id="sort_order" value="<?php echo $generalLangSet[0]; ?>" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td><label for="direction" class="newCenter"><?php trans('Text Direction', $lang['CH715']); ?>:</label></td>
                        <td><select id="direction" name="direction" class="form-control">
                        <option <?php isSelected($generalLangSet[6], true, 1, 'ltr'); ?> value="ltr"><?php trans('Left to Right', $lang['CH716']); ?></option>
                        <option <?php isSelected($generalLangSet[6], true, 1, 'rtl'); ?> value="rtl"><?php trans('Right to Left', $lang['CH717']); ?></option>
                    </select></td>
                    </tr>
                    <tr>
                        <td><label for="status" class="newCenter"><?php trans('Status',$lang['CH146']); ?>:</label></td>
                        <td><select <?php echo ($args[0] == $defaultLang) ? 'disabled' : ''; ?> name="status" id="status" class="form-control">
                        <option <?php isSelected($generalLangSet[5], true, 1); ?> value="1"><?php trans('Enabled',$lang['CH291']); ?></option>
                        <option <?php isSelected($generalLangSet[5], false, 1); ?> value="0"><?php trans('Disabled',$lang['CH290']); ?></option>
                    </select>
                        <?php echo ($args[0] == $defaultLang) ? ' 
                                <br>
                                <div class="alert alert-warning">
                                    <i class="fa fa-info-circle"></i>
                                    <b>'.$lang['CH455'].': </b> '.$lang['CH718'].': <a class="btn btn-xs btn-info" href="'.adminLink('interface',true).'">'.$lang['CH719'].'</a>
                                </div>' : ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
               <div class="box-header with-border">
                <h3 class="box-title"><?php trans('Language Data', $lang['CH720']); ?></h3>
               </div>
            <br />
            
            <table class="table table-hover table-bordered" id="mySitesTable">
                <thead>
                    <th class="hide"><?php trans('ID',$lang['CH307']); ?></th>
                    <th><?php trans('UID', $lang['CH723']); ?></th>
                    <th><?php trans('Default String', $lang['CH722']); ?></th>
                    <th><?php echo strtoupper($args[0]); ?> <?php trans('Language String', $lang['CH721']); ?></th>
                </thead>
                <tbody>
                    <?php foreach($langDataArr as $langVal){
                    if(strlen($langVal[2]) < 150) $row = 2; 
                    elseif(strlen($langVal[2]) < 250) $row = 4;    
                    elseif(strlen($langVal[2]) < 450) $row = 6; 
                    elseif(strlen($langVal[2]) < 650) $row = 8;   
                    else $row = 10;
                    echo '<tr><td style="display: none; width: 10px;">'.$langVal[0].'</td><td style="width: 50px;">'.$langVal[1].'</td><td style="width: 300px;">'.$langVal[2].'</td>
                    <td><textarea name="'.$langVal[0].'" id="'.$langVal[0].'" class="form-control textIn" cols="2000" rows="'.$row.'">'.$langVal[3].'</textarea></td></tr>';
                    }?>
                </tbody>
                </table>
                
                <br />
                
                <div class="text-center">
                    <input id="saveBtn" type="submit" value="<?php trans('Save',$lang['CH39']); ?>" class="btn btn-primary btn-lg"/>
                    <a class="btn btn-danger btn-lg" href="<?php adminLink($controller); ?>"><?php trans('Cancel',$lang['CH40']); ?></a>
                </div>
                
                <br />
                <br />
            <?php } elseif($pointOut == 'add'){ ?>
                
                <div class="form-group">
                    <label for="language_name"><?php trans('Language Name',$lang['CH710']); ?></label>
                    <input required="required" type="text" placeholder="<?php trans('Type language name',$lang['CH711']); ?>" name="language_name" id="language_name" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="language_code"><?php trans('Language Code',$lang['CH712']); ?> <small> ( <?php trans('2 Letter', $lang['CH726']); ?> <span style="color: #e74c3c;">ISO 639-1</span> <?php trans('Language Code',$lang['CH712']); ?> - <a target="_blank" rel="nofollow" href="http://www.w3schools.com/tags/ref_language_codes.asp"><?php trans('Reference', $lang['CH725']); ?></a> )</small></label>
                    <input required="required" maxlength="2" type="text" placeholder="<?php trans('Type language code',$lang['CH713']); ?>" id="language_code" name="language_code" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="language_author"><?php trans('Your Name',$lang['CH21']); ?></label>
                    <input type="text" placeholder="<?php trans('Type your name', $lang['CH724']); ?>" id="language_author" name="language_author" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="direction"><?php trans('Text Direction',$lang['CH715']); ?></label>
                    <select name="direction" class="form-control">
                        <option selected="" value="ltr"><?php trans('Left to Right',$lang['CH716']); ?></option>
                        <option value="rtl"><?php trans('Right to Left',$lang['CH717']); ?></option>
                    </select>
                </div>
                                
                <div class="form-group">
                    <label for="status"><?php trans('Status',$lang['CH146']); ?></label>
                    <select name="status" class="form-control">
                        <option selected="" value="1"><?php trans('Enabled',$lang['CH291']); ?></option>
                        <option value="0"><?php trans('Disabled',$lang['CH290']); ?></option>
                    </select>
                </div>
                <br />
                <input type="submit" name="save" value="<?php trans('Create Language File', $lang['CH727']); ?>" class="btn btn-primary"/>
                <a class="btn btn-danger" href="<?php adminLink($controller); ?>"><?php trans('Cancel',$lang['CH40']); ?></a>
                <br />
                <br />
                
            
            <?php } elseif($pointOut == 'add-custom-text'){ ?>
                <div class="form-group">
                    <label for="csnumber"><?php trans('UID',$lang['CH723']); ?></label>
                    <input type="text" readonly="" id="csnumber" name="csnumber" value="<?php echo $customNumber; ?>" class="form-control" />
                </div>
                
                <div class="form-group">
                    <label for="default_string"><?php trans('Default String',$lang['CH722']); ?></label>
                    <input required="required" type="text" placeholder="<?php trans('Type your text',$lang['CH728']); ?>" id="default_string" name="default_string" class="form-control" />
                </div>
                
                <br />
                <input type="submit" name="save" value="<?php trans('Add',$lang['CH227']); ?>" class="btn btn-primary"/>
                <a class="btn btn-danger" href="<?php adminLink($controller); ?>"><?php trans('Cancel',$lang['CH40']); ?></a>
                <br />
                <br />   
            <?php } elseif($pointOut == 'import'){ ?>
            
                <div class="form-group text-center">																	
                    <label for="langID"><?php trans('Select language file to upload', $lang['CH729']); ?>:</label>
                        <div class="controls">		   
                         <input type="file" name="langUpload" id="langUpload" class="btn btn-default" style=" display: inline-block;" />
                         <input type="hidden" name="langID" id="langID" value="1" /> <br />
                         <br />
                            <label class="checkbox-inline"><input type="checkbox" name="customStr" /><?php trans('Include Custom Language Strings ?', $lang['CH731']); ?></label>
                            <br />   <br />                 
                         <button type="submit" class="btn btn-warning"><i class="fa fa-upload"></i> <?php trans('Import', $lang['CH730']); ?></button>
                        </div> <!-- /controls -->	
				</div> <!-- /control-group -->
            <?php } else { ?>

                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <th><?php trans('Sort Order',$lang['CH714']); ?></th>
                        <th><?php trans('Language Code',$lang['CH712']); ?></th>
                        <th><?php trans('Language Name',$lang['CH710']); ?></th>
                        <th><?php trans('Author', $lang['CH732']); ?></th>
                        <th><?php trans('Status',$lang['CH146']); ?></th>
                        <th><?php trans('Actions',$lang['CH272']); ?></th>
                    </thead>
                    <tbody>
                        <?php foreach($allLangs as $langVal){  
                        echo '<tr>
                        <td style="width: 100px;">'.$langVal[0].'</td>
                        <td>'.strtoupper($langVal[2]).'</td>
                        <td>'.$langVal[3].'</td>
                        <td>'.$langVal[4].'</td>
                        <td>'.($langVal[5] ? '<a href="'.adminLink($controller.'/status/disable/'.$langVal[2],true).'" class="label label-success">'.$lang['CH291'].'</a>' : '<a href="'.adminLink($controller.'/status/enable/'.$langVal[2],true).'" class="label label-danger">'.$lang['CH290'].'</a>').'</td>
                        <td style="width: 200px;">
                        <a class="btn btn-info btn-xs" href="'.adminLink($controller.'/edit/'.$langVal[2],true).'" title="'.$lang['CH585'].' "><i class="fa fa-edit"></i> '.$lang['CH309'].'</a>
                        <a class="btn btn-success btn-xs" href="'.createLink($langVal[2],true).'" target="_blank" title="'.$lang['CH733'].'"><i class="fa fa-external-link"></i> '.$lang['CH118'].'</a>
                        <a class="delete btn btn-danger btn-xs" data-confirm="'.$lang['CH234'].'" href="'.adminLink($controller.'/delete/'.$langVal[2],true).'" title="'.$lang['CH586'].'"><i class="fa fa-trash-o"></i> '.$lang['CH232'].'</a>
                        </td>
                        </tr>';
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

<?php if($pointOut === 'edit') { ob_start(); ?>
<script>
    var langDataCount = <?php echo count($langDataArr); ?>;
    var langPath = '<?php adminLink('language-editor'); ?>';
    var maxPHPLimit = 200;
    $('#mySitesTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "lengthMenu": [[-1, 100, 250, 500], ["<?php trans('All',$lang['CH370']); ?>", 100, 250, 500]],
    });

    function saveLangData(start,langCode){
        var mycount = 1;
        var nxtStart,nxtCount = false;
        var myPostData = {};
        myPostData['langCode'] = langCode;
        for(var i=start; i<=langDataCount; i++){
            nxtCount = i;
            myPostData[i] = $('#'+i).val();
            if(mycount === maxPHPLimit) {
                nxtStart = true;
                break;
            }
            mycount++;
        }
        $.post(langPath+'/save/data',myPostData,function(output){
            if(output.success){
                mycount = 1;
                myPostData = {};
                if(nxtStart) {
                    nxtStart = false;
                    saveLangData(nxtCount,langCode);
                }
            }
        });

        if(i === (langDataCount) +1) {
            $.post(langPath+'/save/success',{language_code:langCode},function(output) {
                if(output.success) {
                    $('#saveBtn').val('<?php makeJavascriptStr($lang['CH735'], true); ?>');
                    $('#saveBtn').removeAttr('disabled');
                    window.location.href = output.link;
                }
            });
        }
    }

    $("#editForm").submit(function(e) {
        e.preventDefault();

        $('#saveBtn').val('<?php makeJavascriptStr($lang['CH734'], true); ?>');
        $('#saveBtn').attr('disabled','disabled');

        //Save Language settings
        var oldLangCode = $('#oldLangCode').val();
        var langCode = $('#language_code').val();
        var langName = $('#language_name').val();
        var langSort = $('#sort_order').val();
        var langDirection = $('#direction').val();
        var langStatus = $('#status').val();

        $.post(langPath+'/save/settings',{oldLangCode: oldLangCode, sort_order:langSort, language_code:langCode, language_name: langName, status:langStatus, direction:langDirection},function(output){
            if(output.success){
                //Save Language Data
                saveLangData(1,langCode);
            }
        });
    });
</script>
<?php $contents = ob_get_contents(); ob_end_clean(); $footerAddArr[] = $contents; }  ?>