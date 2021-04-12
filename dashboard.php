<?php
/*
 * Created on Thu Apr 01 2021
 *
 * The MIT License (MIT)
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
session_start();
require_once('configs/config.php');
require_once('configs/checklogin.php');
check_login();
require_once('configs/codeGen.php');
require_once('public/partials/_analytics.php');
/* Mark All Notications As Read */
if (isset($_GET['notification'])) {
    $notification = $_GET['notification'];
    $adn = "UPDATE   ezanaLMS_Notifications SET  status = 'Read' ";
    $stmt = $mysqli->prepare($adn);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=dashboard.php");
    } else {
        $info = "Please Try Again Or Try Later";
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
            <?php require_once('public/partials/_brand.php'); ?>
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
                            <a href="non_teaching_staff.php" class="nav-link">
                                <i class="nav-icon fas fa-user-secret"></i>
                                <p>
                                    Non Teaching Staff
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
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    System Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="reports.php" class="nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="system_settings.php" class="nav-link">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>System Settings</p>
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
                            <h1 class="m-0 text-dark">Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <!-- 
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline" action="faculty_search_result.php" method="GET">
                                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Faculty Name Or Code">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add New Faculty</button>
                                <div class="modal fade" id="modal-default">
                                    <div class="modal-dialog  modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Fill All Required Values </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data" role="form">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Name</label>
                                                                <input type="text" required name="name" class="form-control" id="exampleInputEmail1">
                                                                <input type="hidden" required name="id" value="<?php echo $ID; ?>" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="">Faculty Number / Code</label>
                                                                <input type="text" required name="code" value="<?php echo $a; ?><?php echo $b; ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <label for="exampleInputPassword1">Faculty Description</label>
                                                                <textarea id="textarea" name="details" rows="10" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="add_faculty" class=" btn btn-primary">Add Faculty</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </nav> -->
                        </div>
                        <hr>
                        <div class="row">
                            <!-- <div class="col-md-3">
                                <?php
                                $ret = "SELECT * FROM `ezanaLMS_Faculties` ORDER BY `name` ASC ";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->execute(); //ok
                                $res = $stmt->get_result();
                                $cnt = 1;
                                while ($faculty = $res->fetch_object()) {
                                ?>
                                    <div class="col-md-12">
                                        <div class="card  collapsed-card">
                                            <div class="card-header">
                                                <a href="faculty_dashboard.php?view=<?php echo $faculty->id; ?>">
                                                    <h3 class="card-title text-primary"><?php echo $cnt; ?>. <?php echo $faculty->name; ?></h3>
                                                    <div class="card-tools text-right">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="card-body">
                                                <ul class="list-group">

                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="faculty_departments.php?view=<?php echo $faculty->id; ?>">
                                                            Departments
                                                        </a>
                                                    </li>

                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="faculty_courses.php?view=<?php echo $faculty->id; ?>">
                                                            Courses
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="faculty_modules.php?view=<?php echo $faculty->id; ?>">
                                                            Modules
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="school_calendar.php?view=<?php echo $faculty->id; ?>">
                                                            Important Dates
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="faculty_lects.php?view=<?php echo $faculty->id; ?>">
                                                            Lecturers
                                                        </a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="faculty_students.php?view=<?php echo $faculty->id; ?>">
                                                            Students
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    $cnt = $cnt + 1;
                                } ?>
                            </div>
                             -->
                            <div class="col-md-12">
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
                                                            <h3>Important Dates</h3>
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
                                                <a href="non_teaching_staff.php">
                                                    <div class="small-box bg-info">
                                                        <div class="inner">
                                                            <h3>Non Teaching Staff</h3>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="fas fa-user-secret"></i>
                                                        </div>
                                                        <div class="small-box-footer">
                                                            <i class="fas fa-arrow-circle-right"></i>
                                                            <?php echo $admins; ?>
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
                                        <hr>
                                        <!-- Login Activity -->
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="card col-md-12">
                                                    <div class="card-head text-center">
                                                        <br>
                                                        <h4>User Login Activity <a href="user_login_activity.php" class="pull-right badge badge-success">View Logs</a></h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="chartContainer" style="height: 370px; max-width: auto; margin: 0px auto;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Student Requests -->
                                        <hr>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="card col-md-6">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            Users Requests
                                                        </h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <ul class="todo-list" data-widget="todo-list">
                                                            <?php
                                                            /* Load User Requests */
                                                            $ret = "SELECT * FROM `ezanaLMS_UserRequests` ORDER BY `created_at` ASC   ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($req = $res->fetch_object()) {
                                                            ?>
                                                                <li>
                                                                    <a href="user_requests.php?view=<?php echo $req->id; ?>">
                                                                        <span class="text"><?php echo $req->request; ?></span>
                                                                        <small class="badge badge-success"><i class="far fa-clock"></i> <?php echo date('d M Y - g:ia', strtotime($req->created_at)); ?></small>
                                                                        <div class="progress">
                                                                            <div class="progress-bar" style="width: <?php echo $req->progress; ?>%"></div>
                                                                        </div>
                                                                        <span class="progress-description">
                                                                            Progress : <?php echo $req->progress; ?> %
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                </a>
                                                            <?php
                                                            } ?>
                                                        </ul>
                                                    </div>

                                                </div>

                                                <div class="card col-md-6">
                                                    <div class="card-header text-center">
                                                        <h3 class="card-title">
                                                            System And Database Server Status
                                                        </h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <?php
                                                        $server_info = mysqli_get_server_info($mysqli);
                                                        echo "System / Database Server: " . $server_info . "<br>";
                                                        $array = explode("  ", mysqli_stat($mysqli));
                                                        foreach ($array as $value) {
                                                            echo "Server " . $value . "<br />";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="card col-md-12">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            Recent Bugs /System Errors Reports
                                                        </h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="todo-list" data-widget="todo-list">
                                                            <?php
                                                            /* Load Crashlytics */
                                                            $ret = "SELECT * FROM `ezanaLMS_BugReports` WHERE status  = 'Pending Fix'   ORDER BY `date_reported` ASC  ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            while ($bugs = $res->fetch_object()) {
                                                            ?>
                                                                <li>
                                                                    <a href="bugs_reports.php?view=<?php echo $bugs->id; ?>">
                                                                        <span class="text"> <?php echo $bugs->bug_title; ?> - Bug Status: <?php echo $bugs->status; ?> </span>
                                                                        <div class="pull-right">
                                                                            <small class="badge badge-success"><i class="far fa-clock"></i> Reported On: <?php echo date('d M Y - g:ia', strtotime($bugs->date_reported)); ?></small>
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            <?php
                                                            } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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