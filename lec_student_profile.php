<?php
/*
 * Created on Mon Apr 26 2021
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
require_once('configs/codeGen.php');
lec_check_login();
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_lec_nav.php');
        $view  = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE admno ='$view' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($std = $res->fetch_object()) {
            //Get Default Profile Picture
            if ($std->profile_pic == '') {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/dist/img/no-profile.png' alt='User profile picture'>";
            } else {
                $dpic = "<img class='profile-user-img img-fluid img-circle' src='public/uploads/UserImages/students/$std->profile_pic' alt='User profile picture'>";
            } ?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <?php require_once('public/partials/_brand.php'); ?>
                <!-- Sidebar -->
                <?php require_once('public/partials/_lec_sidebar.php'); ?>
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $std->name; ?> Profile</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="lec_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="lec_allocated_modules.php">Allocated Modules</a></li>
                                    <li class="breadcrumb-item active">Student Profile</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <?php echo $dpic; ?>
                                        </div>

                                        <h3 class="profile-username text-center"></h3>

                                        <p class="text-muted text-center"></p>

                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Student Name: </b> <a class="float-right"><?php echo $std->name; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student Gender: </b> <a class="float-right"><?php echo $std->gender; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Admission Number: </b> <a class="float-right"><?php echo $std->admno; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student ID: </b> <a class="float-right"><?php echo $std->idno; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student Email : </b> <a class="float-right" href="mailto:<?php echo $std->email; ?>"><?php echo $std->email; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Student Phone No: </b> <a class="float-right"><?php echo $std->phone; ?></a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <p class="text-muted text-center"></p>
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Date Enrolled: </b> <a class="float-right"><?php echo $std->day_enrolled; ?></a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>School / Faculty: </b> <a class="float-right"><?php echo $std->school; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Department: </b> <a class="float-right"><?php echo $std->department; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Course: </b> <a class="float-right"><?php echo $std->course; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Current Year: </b> <a class="float-right"><?php echo $std->current_year; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>No Of Enrolled / Attempted Modules: </b> <a class="float-right">
                                                    <?php

                                                    $query = "SELECT COUNT(module_name)  FROM `ezanaLMS_Enrollments` WHERE student_adm = '$std->admno' ";
                                                    $stmt = $mysqli->prepare($query);
                                                    $stmt->execute();
                                                    $stmt->bind_result($enrolled_modules);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    echo $enrolled_modules;
                                                    ?>
                                                </a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>Account Status: </b> <a class="float-right"><?php echo $std->acc_status; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Current Address: </b> <a class="float-right"><?php echo $std->adr; ?></a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>

        <?php
            require_once("public/partials/_footer.php");
            require_once("public/partials/_scripts.php");
        }
        ?>
</body>

</html>