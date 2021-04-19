<?php
/*
 * Created on Mon Apr 19 2021
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
        $success = "Deleted" && header("refresh:1; url=edu_admn_dashboard.php");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('public/partials/_edu_admn_nav.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <?php require_once('public/partials/_brand.php'); ?>
            <!-- Sidebar -->
            <?php require_once('public/partials/_sidebar.php'); ?>

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
                                <li class="breadcrumb-item"><a href="edu_admn_dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">

                        </div>
                        <hr>
                        <div class="row">

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-6">
                                                <a href="edu_admn_faculties.php">
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
                                                <a href="edu_admn_departments.php">
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
                                                <a href="edu_admn_courses.php">
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
                                                <a href="edu_admn_modules.php">
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
                                                <a href="edu_admn_overall_school_calendar.php">

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
                                                <a href="edu_admn_lecturers.php">

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
                                                <a href="edu_admn_students.php">
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
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="card col-md-12">
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
                                                                    <a href="edu_admn_user_requests.php?view=<?php echo $req->id; ?>">
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