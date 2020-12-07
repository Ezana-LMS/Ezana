<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('public/partials/_analytics.php');
require_once('public/partials/_head.php');
?>

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
                            <a href="dashboard.php" class="active nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="faculties.php" class="nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Faculties
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="departments.php" class="nav-link">
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
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">System Administrator Dashboard </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Info boxes -->
                    <div class="row">

                        <!-- Faculties -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="faculties.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-university"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Faculties</span>
                                        <span class="info-box-number"><?php echo $faculties; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>


                        <!-- Departments -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="departments.php">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fa fa-building" aria-hidden="true"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Departments</span>
                                        <span class="info-box-number">
                                            <?php echo $departments; ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Courses  -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="courses.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"> Courses</span>
                                        <span class="info-box-number"><?php echo $courses; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Modules -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="modules.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chalkboard"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Modules</span>
                                        <span class="info-box-number"><?php echo $modules; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Lecturers -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="lecturers.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-tie"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Lecturers</span>
                                        <span class="info-box-number"><?php echo $lecs; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Students -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="total_students.php">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Students</span>
                                        <span class="info-box-number"><?php echo $students; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?php require_once('public/partials/_footer.php'); ?>
    </div>
    <!-- ./wrapper -->
    <?php require_once('public/partials/_scripts.php'); ?>
</body>

</html>