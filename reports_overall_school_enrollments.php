<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');
require_once('public/partials/_analytics.php');
?>

<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ezana Learning Management System - Overall School Enrollments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- SEO META TAGS -->
    <meta name="title" content="Ezana LMS">
    <meta name="description" content="Ezana Learning Management System">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://ezana.org">
    <meta property="og:title" content="Ezana LMS">
    <meta property="og:description" content="Ezana Learning Management System">
    <meta property="og:image" content="public/dist/img/logo.jpeg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://ezana.org">
    <meta property="twitter:title" content="Ezana LMS">
    <meta property="twitter:description" content="Ezana Learning Management System">
    <meta property="twitter:image" content="public/dist/img/logo.jpeg">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="public/dist/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="public/dist/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/dist/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="public/dist/img/favicons/site.webmanifest">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="public/plugins/fontawesome-free/css/all.min.css">
    <!-- Data Tables -->
    <link rel="stylesheet" href="public/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- <link rel="stylesheet" type="text/css" href="plugins/datatable/datatables.css"> -->
    <link rel="stylesheet" type="text/css" href="public/plugins/datatable/custom_dt_html5.css">
    <!-- <link rel="stylesheet" type="text/css" href="plugins/datatable/dt-global_style.css">  -->
    <!-- CK Editor CDN -->
    <!-- <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script> -->
    <!-- Scroll Bars -->
    <link rel="stylesheet" href="public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="public/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- SWAL ALERTS INJECTION-->
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="public/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('public/partials/_nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="dashboard.php" class="brand-link">
                <img src="public/dist/img/logo.png" alt="Ezana LMS Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">Ezana LMS</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="dashboard.php" class=" nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="faculties.php" class=" nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Faculties
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="departments.php" class=" nav-link">
                                <i class="nav-icon fas fa-building"></i>
                                <p>
                                    Departments
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="courses.php" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Courses
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="modules.php" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                <p>
                                    Modules
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="lecturers.php" class="nav-link">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>
                                    Lecturers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="students.php" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Students
                                </p>
                            </a>
                        </li>
                        <li class="nav-item   has-treeview">
                            <a href="#" class="active nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="reports.php" class="active nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="data_backup.php" class="nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Data Backup</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="system_settings.php" class="nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Settings</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Student Enrollments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports.php">System</a></li>
                                <li class="breadcrumb-item"><a href="reports.php">Reports</a></li>
                                <li class="breadcrumb-item active">Enrollments</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table id="export-dt" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Admission</th>
                                            <th>Name</th>
                                            <th>Course</th>
                                            <th>Module</th>
                                            <th>Academic Yr</th>
                                            <th>Sem Enrolled</th>
                                            <th>Sem Start</th>
                                            <th>Sem End </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM `ezanaLMS_Enrollments`  ";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute(); //ok
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($en = $res->fetch_object()) {
                                        ?>

                                            <tr>
                                                <td><?php echo $en->student_adm; ?></td>
                                                <td><?php echo $en->student_name; ?></td>
                                                <td><?php echo $en->course_name; ?></td>
                                                <td><?php echo $en->module_name; ?></td>
                                                <td><?php echo $en->academic_year_enrolled; ?></td>
                                                <td><?php echo $en->semester_enrolled; ?></td>
                                                <td><?php echo date('d M Y', strtotime($en->semester_start)); ?></td>
                                                <td><?php echo date('d M Y', strtotime($en->semester_end)); ?></td>
                                            </tr>
                                        <?php $cnt = $cnt + 1;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Main Footer -->
                <?php require_once('public/partials/_footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>