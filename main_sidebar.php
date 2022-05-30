
<?php 
$date = date("Y-m-d");
include './libs/database.php';
session_start();
if ($_SESSION['user']==null || $_SESSION['userId']==null) {
  header('Location: ./index.php');
}
$url.= substr($_SERVER['REQUEST_URI'], 8); 
$urlfinal= substr($url, 0, -4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WFM</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- datatable -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/sig.png" alt="iHelpBDLogo" height="60" width="120">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="./exchange-report.php">
            <i class="far fa-bell"></i>
            <?php 
            $countNoti=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'total' FROM `roster`.`roster_chanage` WHERE `sender_user_name`='".$_SESSION['user']."' OR `receiver_name`='".$_SESSION['user']."' AND 'status'=0"));
            $countOff=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as 'total' FROM `off_day_request` WHERE `sender_user_name`='".$_SESSION['user']."' AND `receiver_name`='".$_SESSION['user']."' AND 'status'=0"));
            $total=$countNoti['total']+$countOff['total'];
            ?>
            <span class="badge badge-warning navbar-badge"><?php echo $total; ?></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="./homepage.php" class="brand-link">
        <img src="dist/img/sig.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">WFM</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo $_SESSION['full_name']; ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" id="mydiv" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
           <?php 
           if ($_SESSION['user_level']!=1) 
           {
            ?>
            <li class="nav-item menu-open " >
              <a href="./homepage.php" class="nav-link <?php if($urlfinal=="homepage"){ echo 'active'; } ?>" id="home">
                <p>
                  Dashboard
                  <!-- <i class="right fas fa-angle-left"></i> -->
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./add-campaign.php" class="nav-link  <?php if($urlfinal=="add-campaign"){ echo 'active'; } ?>">
                <i class="nav-icon fas fa-campground"></i>
                <p>
                  Campaign
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./schedule.php" class="nav-link <?php if($urlfinal=="schedule"){ echo 'active'; } ?>">
                <i class="nav-icon fas fa-calendar-plus"></i>
                <p>
                  Schedule
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./add-shift.php" class="nav-link <?php if($urlfinal=="add-shift"){ echo 'active'; } ?>">
                <i class="nav-icon fab fa-shirtsinbulk"></i>
                <p>
                  Shift
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./erlang.php" class="nav-link <?php if($urlfinal=="erlang"){ echo 'active'; } ?>">
                <i class="nav-icon fas fa-hourglass-half"></i>
                <p>
                  Forecasting
                </p>
              </a>
            </li>
            <li class="nav-item <?php if($urlfinal=="roster" || $urlfinal=="roster-manage" || $urlfinal=="roster-weekly-auto"){ echo 'menu-open'; } ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon nav-icon fas fa-map"></i>
                <p>
                  Roster
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./roster.php" class="nav-link <?php if($urlfinal=="roster"){ echo 'active'; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                     Day Wise Roster
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./roster-weekly-auto.php" class="nav-link <?php if($urlfinal=="roster-weekly-auto"){ echo 'active'; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                     Weekly Roster
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./roster-manage.php" class="nav-link <?php if($urlfinal=="roster-manage"){ echo 'active'; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Roster Manage
                    </p>
                  </a>
                </li>
              </ul>
            </li>


            <li class="nav-item">
              <a href="./users.php" class="nav-link <?php if($urlfinal=="users"){ echo 'active'; } ?>">
                <i class="nav-icon fas fa-user-cog"></i>
                <p>
                  Users
                </p>
              </a>
            </li>
            <li class="nav-item <?php if($urlfinal=="weekly-erlang-report" || $urlfinal=="required-report" || $urlfinal=="exchange-request-report" || $urlfinal=="shiftReport"){ echo 'menu-open'; } ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>
                  Reports
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./weekly-erlang-report.php" class="nav-link <?php if($urlfinal=="weekly-erlang-report"){ echo 'active'; } ?>" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Forecasting Report
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./required-report.php" class="nav-link  <?php if($urlfinal=="required-report"){ echo 'active'; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Slot Wise Report
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./exchange-request-report.php" class="nav-link  <?php if($urlfinal=="exchange-request-report"){ echo 'active'; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Exchange Report
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./shiftReport.php" class="nav-link  <?php if($urlfinal=="shiftReport"){ echo 'active'; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Off Day Report
                    </p>
                  </a>
                </li>
              </ul>
            </li>

            <?php
          } 
          if ($_SESSION['user_level']==1) 
          {
            ?>
            <li class="nav-item">
              <a href="./roster-report.php" class="nav-link <?php if($urlfinal=="roster-report"){ echo 'active'; } ?>">
                <i class="nav-icon far fa-calendar-plus"></i>
                <p>
                  Roster
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./exchange-report.php" class="nav-link <?php if($urlfinal=="exchange-report"){ echo 'active'; } ?>">
                <i class="nav-icon far fa-calendar-plus"></i>
                <p>
                  Exchange Report
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./weekly-report.php" class="nav-link <?php if($urlfinal=="weekly-report"){ echo 'active'; } ?>">
                <i class="nav-icon far fa-calendar-plus"></i>
                <p>
                  Weekly Schedule
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./shiftReport.php" class="nav-link <?php if($urlfinal=="shiftReport"){ echo 'active'; } ?>">
                <i class="nav-icon far fa-calendar-plus"></i>
                <p>
                  Off Day Report
                </p>
              </a>
            </li>

            <?php
          }
          ?>

          <li class="nav-item">
            <a href="./logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/homepage.php">Home</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

