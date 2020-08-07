<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Accounts</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>accounts_admin_assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url(); ?>accounts_admin_assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>accounts_admin_assets/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url(); ?>accounts_admin_assets/vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>accounts_admin_assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>accounts_admin_assets/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>accounts_admin_assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    
   <!-- Date picker -->
   <link rel="stylesheet" href="<?php echo base_url(); ?>accounts_admin_assets/date-picker/bootstrap-datepicker.css" />
  <script src="<?php echo base_url(); ?>accounts_admin_assets/date-picker/bootstrap-datepicker.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script type="text/javascript">
      function checkDeletion(){
        var chk = confirm('Are you sure to delete this?');
        if (chk) {
          return true;
        }
        else{
          return false;
        }
      }
    </script>
    
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Accounts</a>
            </div>
            <!-- /.navbar-header -->


            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">                     
                        <li><a href="<?php echo base_url(); ?>super_admin"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>


                        <?php foreach ($sheets as $sheet) : ?>                          
                            <li><a href="<?php echo base_url(); ?>super_admin/get_amounts/<?php echo $sheet->id; ?>"><i class="fa fa-table"></i> <?php echo $sheet->name; ?></a></li>
                        <?php endforeach; ?>

                        <!-- manage sheets -->
                        <li><a href="<?php echo base_url(); ?>super_admin/sheets"><i class="fa fa-gears"></i> Manage sheets</a></li>
                        <!-- /- manage sheets -->

                        <li><a href="<?php echo base_url(); ?>super_admin/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>