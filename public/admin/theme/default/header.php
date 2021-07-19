<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="<?php themeLink('dist/img/favicon.png'); ?>" />
    <title><?php echo $pageTitle .' | '. $basicSettings['app_name']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport' />
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php themeLink('bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <?php if(in_array('morris', $htmlLibs)) { ?>
    <!-- Morris chart -->
    <link href="<?php themeLink('plugins/morris/morris.css'); ?>" rel="stylesheet" type="text/css" />
    <?php } if(in_array('dataTables', $htmlLibs)) { ?>
    <!-- DATA TABLES -->
    <link href="<?php themeLink('plugins/datatables/dataTables.bootstrap.css'); ?>" rel="stylesheet" type="text/css" />
    <?php } ?>
    <!-- Font Awesome Icons -->
    <link href="<?php themeLink('dist/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="<?php themeLink('dist/css/ionicons.min.css'); ?>" rel="stylesheet" type="text/css" />
    <?php if(in_array('datePicker', $htmlLibs)) { ?>
    <!-- date picker -->
    <link href="<?php themeLink('plugins/datepicker/datepicker.css'); ?>" rel="stylesheet" type="text/css" />
    <?php } if(in_array('dateRangePicker', $htmlLibs)) { ?>
    <!-- daterange picker -->
    <link href="<?php themeLink('plugins/daterangepicker/daterangepicker.css'); ?>" rel="stylesheet" type="text/css" />
    <?php } if(in_array('colorpicker', $htmlLibs)) { ?>
    <!-- colorpicker -->
    <link href="<?php themeLink('plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet" type="text/css" />
    <?php } ?>
    <!-- Theme style -->
    <link href="<?php themeLink('dist/css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php themeLink('dist/css/custom.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php themeLink('dist/css/skins/skin-pink.min.css'); ?>" rel="stylesheet" type="text/css" />
    <?php if(in_array('iCheck', $htmlLibs)) { ?>
    <!-- iCheck -->
    <link href="<?php themeLink('plugins/iCheck/square/blue.css'); ?>" rel="stylesheet" type="text/css" />
    <?php } if(in_array('select2', $htmlLibs)) { ?>
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php themeLink('plugins/select2/select2.min.css'); ?>" />
    <?php } ?>
    <!-- jQuery 2.1.4 -->
    <?php scriptLink('plugins/jQuery/jQuery-2.1.4.min.js'); ?>
    
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body class="skin-blue sidebar-mini <?php if(isset($_COOKIE['toggleState'])) { if($_COOKIE['toggleState'] === 'closed') { echo 'sidebar-collapse hold-transition'; } } else{ echo 'sidebar-collapse hold-transition'; } ?>">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="<?php adminLink(); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><span class="livechat icon-bubbles3"></span></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><?php echo $basicSettings['html_app']; ?></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

            <li class="dropdown">
                <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false"><i class="fa fa-globe fa-lg"></i> &nbsp; <?php echo strtoupper(ACTIVE_LANG); ?></a>
                <ul class="dropdown-menu">
                    <?php foreach($loadedLanguages as $language){
                        echo '<li><a href="'.customAdminLangLink($_SERVER["REQUEST_URI"], $language[2], $subPath, true).'">'.$language[3].'</a></li>';
                    }?>
                </ul>
            </li>

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="<?php adminLink('admin-profile'); ?>" >
                  <!-- The user image in the navbar-->
                  <img src="<?php echo $admin_logo_path; ?>" class="user-image" alt="User Image"/>
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs"><?php echo $adminName; ?></span>
                </a>

              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="<?php createLink('widget-test'); ?>" title="Preview Chat Widget" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
              </li>
              <li>
                <a href="<?php adminLink('logout'); ?>" title="Logout"><i class="glyphicon glyphicon-off"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
            <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $admin_logo_path; ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p>Welcome Back </p>
              <!-- Status -->
              <p style="font-size:15px;"><a href="#"><?php echo $adminName; ?>!</a> </p>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <!-- Optionally, you can add icons to the links -->
            <?php
            ksort($menuBarLinks);
            foreach($menuBarLinks as $menuBarLink){
                $isActive = $subMenuLinkData = '';
                $subMenuConNames = array();
                if($menuBarLink[0]){
                    if(isset($menuBarLink[4])){
                        foreach($menuBarLink[4] as $subMenuLink){
                            if(!isset($subMenuLink[3]))
                                $subMenuLink[3] = true;
                            if($subMenuLink[3]) {
                                if($privilege[0] == 'all' || in_array($subMenuLink[1], $privilege)) {
                                    $subMenuLinkData .= '<li><a href="' . $adminBaseLink . $subMenuLink[1] . '"><i class="' . $subMenuLink[2] . '"></i>' . $subMenuLink[0] . '</a></li>';
                                    $subMenuConNames[] = $subMenuLink[1];
                                }
                            }
                        }
                        if(count($subMenuConNames) != 0) {
                            if (in_array($controller, $subMenuConNames)) $isActive = 'active';
                            echo '<li class="treeview ' . $isActive . '">
                          <a href="#"><i class="round ' . $menuBarLink[3] . '"></i> <span> ' . $menuBarLink[1] . '</span> <i class="fa fa-angle-left pull-right"></i></a>
                          <ul class="treeview-menu">
                              ' . $subMenuLinkData . '
                          </ul>
                        </li>';
                        }
                    } else {
                        if($controller == $menuBarLink[2]) $isActive = 'active';
                        if($menuBarLink[2] == 'header-li')
                            echo '<li class="header">'.$menuBarLink[1].'</li>';
                        else {
                            if($privilege[0] == 'all' || in_array($menuBarLink[2], $privilege)) {
                                if($menuBarLink[2] == $adminInfo['DefaultCon']) $menuBarLink[2] = '';
                                echo '<li class="' . $isActive . '"><a href="' . $adminBaseLink . $menuBarLink[2] . '"><i class="round ' . $menuBarLink[3] . '"></i> <span> ' . $menuBarLink[1] . '</span></a></li>';
                            }
                        }
                    }
                }
            }
            ?>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>