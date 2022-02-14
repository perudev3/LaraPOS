<?php

include_once 'recursos/componentes/validador.php';
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>LaraPOS - <?php echo $titulo_pagina; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="recursos/adminLTE/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="recursos/adminLTE/dist/css/select2.min.css">
        
        <!-- Font Awesome -->
        <!--<link href="recursos/js/plugins/datatables/jquery-datatables.css" rel="stylesheet">-->
        <link rel="stylesheet" type="text/css" href="recursos/js/plugins/datatables/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="recursos/js/plugins/datatables/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="recursos/js/plugins/datatables/responsive.dataTables.min.css">
        <!--<link href="recursos/js/plugins/datatables/dataTables.tableTools.css" rel="stylesheet">-->
        <link href="recursos/css/bootstrap-overrides.css" rel="stylesheet">
        <link href="recursos/css/jquery-ui.css" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon" href="usqay-icon.svg">
        <meta name="mobile-web-app-capable" content="yes"/>
        <!-- Ionicons -->
        <link rel="stylesheet" href="recursos/ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="recursos/fa/css/font-awesome.min.css">
        
        <link rel="stylesheet" href="recursos/css/select2.css">
        <link rel="stylesheet" href="recursos/css/checkmark.css">
        <link rel="stylesheet" href="recursos/css/select2-bootstrap.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="recursos/adminLTE/dist/css/AdminLTE.css">
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect.
        -->
        <link rel="stylesheet" href="recursos/adminLTE/dist/css/skins/skin-blue.min.css">
        
        <link rel="stylesheet" href="recursos/btable/dist/bootstrap-table.min.css">
        <!--Token Input-->
        <link rel="stylesheet" href="recursos/token-input/styles/token-input-facebook.css">

        <link rel="stylesheet" type="text/css" href="recursos/js/plugins/chartjs/Chart.min.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    </head>
    <!--
    BODY TAG OPTIONS:
    =================
    Apply one or more of the following classes to get the
    desired effect
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-blue                              |
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
    -->
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <!-- Main Header -->
            <header class="main-header">

                  <!-- Logo -->
                <a href="index.php" class="logo"  style="background: #00395e !important;">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span  class="logo-mini"><img src='recursos/img/usqay-circle-icon.svg' width="80%"></span>
                    <!-- logo for regular state and mobile devices -->
                    <span  class="logo-lg"><img src='recursos/img/usqay_logo.png'  height="60px"></span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation"  style="background: #00395e !important;">
                     <!-- Sidebar toggle button-->
                   <!--  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a> -->
                    <!-- Navbar Right Menu -->
                    <!-- <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                        </ul>
                    </div> -->
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <!-- <aside class="main-sidebar" style="background: #2d2d2d !important">                
                <section class="sidebar">
                </section>            
            </aside> -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper"  style=" background-color: #FFF !important;">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    
                    <h1>
                        <?php echo $titulo_pagina; ?>
                    </h1>
                </section>
                <section class="content">               