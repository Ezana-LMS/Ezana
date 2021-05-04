<?php
/*
 * Created on Tue May 04 2021
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
std_check_login();
require_once('configs/codeGen.php');
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_std_nav.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE code ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
        ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <?php require_once('public/partials/_std_sidebar.php'); ?>
            </aside>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="std_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="std_enrolled_modules.php">Enrolled Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('public/partials/_std_modulemenu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card card-widget widget-user-2">
                                                <div class="widget-user-header text-center bg-primary">
                                                    <h3 class=""><?php echo $mod->name; ?></h3>
                                                </div>
                                                <div class="card-footer p-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Module Name : <span class="float-right "><?php echo $mod->name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Module Code : <span class="float-right "><?php echo $mod->code; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Course Name : <span class="float-right "><?php echo $mod->course_name; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Teaching Duration : <span class="float-right "><?php echo $mod->course_duration; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        No Of Lecturers Per Week : <span class="float-right "><?php echo $mod->lectures_number; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Exam Weight Percentage : <span class="float-right "><?php echo $mod->exam_weight_percentage; ?></span>
                                                                    </span>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <span class="nav-link text-primary">
                                                                        Cat Weight Percentage : <span class="float-right "><?php echo $mod->cat_weight_percentage; ?></span>
                                                                    </span>
                                                                </li>
                                                                <!-- Course And Department  Module Registered To -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_Courses` WHERE id = '$mod->course_id'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($course = $res->fetch_object()) {
                                                                    $department_id = $course->department_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Departments` WHERE id = '$department_id'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($department = $res->fetch_object()) {
                                                                        /* Number Of Students Registered To This Module */
                                                                        $query = "SELECT COUNT(*)  FROM `ezanaLMS_Enrollments` WHERE module_code = '$mod->code' ";
                                                                        $stmt = $mysqli->prepare($query);
                                                                        $stmt->execute();
                                                                        $stmt->bind_result($enrolled_students);
                                                                        $stmt->fetch();
                                                                        $stmt->close();
                                                                ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Department Code : <span class="float-right "><?php echo $department->code; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Department Name : <span class="float-right "><?php echo $department->name; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Code : <span class="float-right "><?php echo $course->code; ?></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Course Name : <span class="float-right "><?php echo $course->name; ?></span>
                                                                            </span>
                                                                        </li>
                                                                <?php

                                                                    }
                                                                } ?>
                                                            </ul>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <ul class="nav flex-column">
                                                                <!-- Assigned Lec Details -->
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($ass = $res->fetch_object()) {
                                                                    /* Lec Details */
                                                                    $lec = $ass->lec_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$lec'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($lecturer = $res->fetch_object()) {

                                                                ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Name : <span class="float-right "><?php echo $lecturer->name; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Email : <span class="float-right "><a href="mailto:<?php echo $lecturer->email; ?>"><?php echo $lecturer->email; ?></a></span>
                                                                            </span>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-primary">
                                                                                Assigned Lecturer Phone Number : <span class="float-right "><a href="tel:<?php echo $lecturer->phone; ?>"><?php echo $lecturer->phone; ?></a></span>
                                                                            </span>
                                                                        </li>
                                                                    <?php }
                                                                }
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleAssigns` WHERE module_code = '$mod->code' AND status= 'Guest Lecturer'  ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($GuestLec = $res->fetch_object()) {
                                                                    $Guestlecture = $GuestLec->lec_id;
                                                                    $ret = "SELECT * FROM `ezanaLMS_Lecturers` WHERE id = '$Guestlecture'  ";
                                                                    $stmt = $mysqli->prepare($ret);
                                                                    $stmt->execute(); //ok
                                                                    $res = $stmt->get_result();
                                                                    while ($Glecturer = $res->fetch_object()) {
                                                                    ?>
                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-success">
                                                                                Guest Lecturer Name : <span class="float-right "><?php echo $Glecturer->name; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-success">
                                                                                Guest Lecturer Email : <span class="float-right "><?php echo $Glecturer->email; ?></span>
                                                                            </span>
                                                                        </li>

                                                                        <li class="nav-item">
                                                                            <span class="nav-link text-success">
                                                                                Guest Lecturer Phone : <span class="float-right "><?php echo $Glecturer->phone; ?></span>
                                                                            </span>
                                                                        </li>
                                                                <?php
                                                                    }
                                                                } ?>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item">
                                                                <span class="nav-link text-center text-primary">
                                                                    Module Details
                                                                </span>
                                                            </li>
                                                            <li class="nav-item">
                                                                <?php echo $mod->details; ?>
                                                            </li>
                                                        </ul>
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
        <?php require_once('public/partials/_scripts.php');
        } ?>
</body>

</html>