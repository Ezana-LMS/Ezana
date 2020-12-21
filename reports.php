<?php
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');
require_once('public/partials/_analytics.php');
check_login();
if (isset($_POST['add_faculty'])) {
    //Error Handling and prevention of posting double entries
    $error = 0;
    if (isset($_POST['code']) && !empty($_POST['code'])) {
        $code = mysqli_real_escape_string($mysqli, trim($_POST['code']));
    } else {
        $error = 1;
        $err = "Faculty Code Cannot Be Empty";
    }
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
    } else {
        $error = 1;
        $err = "Faculty Name Cannot Be Empty";
    }
    if (!$error) {
        //prevent Double entries
        $sql = "SELECT * FROM  ezanaLMS_Faculties WHERE  code='$code' || name ='$name' ";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if ($code == $row['code']) {
                $err =  "Faculty With This Code Already Exists";
            } else {
                $err = "Faculty Name Already Exists";
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $details = $_POST['details'];

            $query = "INSERT INTO ezanaLMS_Faculties (id, code, name, details) VALUES(?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ssss', $id, $code, $name, $details);
            $stmt->execute();
            if ($stmt) {
                $success = "Faculty Added";
            } else {
                //inject alert that profile update task failed
                $info = "Please Try Again Or Try Later";
            }
        }
    }
}
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
                        <li class="nav-item has-treeview">
                            <a href="#" class="active nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav  nav-treeview">
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
                            <h1 class="m-0 text-dark">Reports</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports.php">System</a></li>
                                <li class="breadcrumb-item active">Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <hr>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-4 col-6">
                                        <a href="faculties.php">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Faculties</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-university"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $faculties; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <a href="departments.php">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Departments</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $departments; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="courses.php">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Courses</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $courses; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="modules.php">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Modules</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-chalkboard"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $modules; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="overall_school_calendar.php">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Calendar</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-calendar"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <a href="overall_school_calendar.php">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Time Table</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-table"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <a href="overall_school_calendar.php">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Student Enrollments</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-user-tag"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <a href="lecturers.php">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Lecturers</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $lecs; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="students.php">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Students</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $students; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
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