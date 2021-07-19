<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
     <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
          Your Version <?php echo VER_NO; ?>
        </div>
        <!-- Copyright -->
        <?php echo $basicSettings['copyright']; ?>
      </footer>

      <div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- Bootstrap 3.3.2 JS -->
    <?php scriptLink('bootstrap/js/bootstrap.min.js',true); ?>

    <!-- Sweet Alert -->
    <?php scriptLink('dist/js/sweetalert.min.js',true); ?>

    <!-- App -->
    <?php scriptLink('dist/js/app.min.js',true); ?>
    <?php scriptLink('dist/js/custom.js',true); ?>

    <!-- Morris.js charts -->
    <?php if(in_array('morris', $htmlLibs)) { scriptLink('https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',true,true); ?>
    <?php scriptLink('plugins/morris/morris.min.js',true); } ?>
    
    <!-- iCheck -->
    <?php if(in_array('iCheck', $htmlLibs)) scriptLink('plugins/iCheck/icheck.min.js',true); ?>
    
    <!-- DATA TABES SCRIPT -->
    <?php if(in_array('dataTables', $htmlLibs)) { scriptLink('plugins/datatables/jquery.dataTables.min.js',true); ?>
    <?php scriptLink('plugins/datatables/dataTables.bootstrap.min.js',true); } ?>

    <!-- date-picker -->
    <?php if(in_array('datePicker', $htmlLibs)) scriptLink('plugins/datepicker/bootstrap-datepicker.js',true);  ?>

    <!-- date-range-picker -->
    <?php if(in_array('dateRangePicker', $htmlLibs)) { scriptLink('plugins/daterangepicker/moment.min.js',true); ?>
    <?php scriptLink('plugins/daterangepicker/daterangepicker.js',true); } ?>

    <!-- CK Editor -->
    <?php if(in_array('ckeditor', $htmlLibs)) scriptLink('plugins/ckeditor/ckeditor.js',true); ?>
    
    <!-- Select2 -->
    <?php if(in_array('select2', $htmlLibs)) scriptLink('plugins/select2/select2.full.min.js',true); ?>
    
    <!-- Checkbox -->
    <?php if(in_array('bCheckbox', $htmlLibs)) scriptLink('plugins/checkbox/bootstrap-checkbox.min.js',true); ?>

    <!-- colorpicker -->
    <?php if(in_array('colorpicker', $htmlLibs)) scriptLink('plugins/colorpicker/bootstrap-colorpicker.min.js',true); ?>

    <?php if(isset($footerAddArr)){
        foreach($footerAddArr as $footerCodes)
            echo $footerCodes;
    } ?>

  </body>
</html>