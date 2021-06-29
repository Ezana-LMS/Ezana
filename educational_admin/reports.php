<?php
/*
 * Created on Sat Jun 26 2021
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
require_once('../config/config.php');
require_once('../config/checklogin.php');
admin_checklogin();
require_once('partials/analytics.php');
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php'); ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Reports</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="reports">System</a></li>
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
                                        <a href="reports_faculties">
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
                                        <a href="reports_departments">
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
                                        <a href="reports_courses">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Courses</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-chalkboard"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $courses; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="reports_modules">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Modules</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-cubes"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $modules; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="reports_overall_school_calendar">

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
                                        <a href="reports_overall_school_timetable">
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
                                        <a href="reports_overall_school_enrollments">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Enrollments</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="reports_module_allocations">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Teaching Allocations</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <a href="reports_overall_module_grades">

                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Student Perfomances</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-file-signature"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-6">
                                        <a href="reports_lecturers">
                                            <div class="small-box bg-info">
                                                <div class="inner">
                                                    <h3>Lecturers</h3>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                </div>
                                                <div class="small-box-footer">
                                                    <i class="fas fa-arrow-circle-right"></i>
                                                    <?php echo $lecs; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-4 col-6">
                                        <a href="reports_students">
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
                <?php require_once('partials/footer.php'); ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('partials/scripts.php'); ?>
</body>

</html>