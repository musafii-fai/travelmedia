<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TravelMedia | <?php echo ucfirst($title); ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo base_url(); ?>assets/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <!-- <link href="<?php //echo base_url('assets/datatables/css/jquery.dataTables.min.css')?>" rel="stylesheet"> -->
        <link href="<?php echo base_url('assets/datatables.net-bs/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- Datepicker -->
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <!-- Bootstrap time Picker -->
        <link href="<?php echo base_url(); ?>assets/css/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
        <!-- Select2 -->
        <link href="<?php echo base_url(); ?>assets/select2/css/select2.min.css" rel="stylesheet"/>

        <!-- jQuery 2.0.2 -->
        <script src="<?php echo base_url(); ?>assets/js/jquery/jquery-2.1.0.min.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            .treeview-menu .active {
                background-color: #dddddd;
            }
        </style>
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?php echo base_url(); ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                TravelMedia
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <!-- Menu head -->
                	<?php $this->load->view("templates/menu_head"); ?>
                <!-- End Menu head -->
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Menu side -->
            	<?php $this->load->view("templates/menu_side"); ?>
            <!-- End menu side -->

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php 
                        	if (isset($content_title)) {
                        		echo $content_title;
                        		if (isset($small_title)) {
                        			echo "<small>".$small_title."</small>";
                        		}
                        	}
                        ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <?php 
                            if(isset($breadcrumbs)) {
                                $class = $this->router->class;
                                $method = $this->router->method;

                                foreach ($breadcrumbs as $key => $val) {
                                    $active = $val == ''?'active':'';
                                    echo '<li class="'.$active.'">';
                                        if ($val == "") {
                                            echo $key;
                                        } else {
                                            $anchor = '<a href="'.$val.'">'.$key.'</a>';
                                            echo $anchor;
                                        }
                                    echo '</li>';
                                }
                            }
                        ?>
                        <!-- <li class="active">Blank page</li> -->
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">