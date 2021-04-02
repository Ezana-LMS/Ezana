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
                                        <a href="reports_faculties.php">
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
                                        <a href="reports_departments.php">
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
                                        <a href="reports_courses.php">
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
                                        <a href="reports_modules.php">
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
                                        <a href="reports_overall_school_calendar.php">

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
                                        <a href="reports_overall_school_timetable.php">
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
                                        <a href="reports_overall_school_enrollments.php">

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
                                        <a href="reports_lecturers.php">
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
                                        <a href="reports_students.php">
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