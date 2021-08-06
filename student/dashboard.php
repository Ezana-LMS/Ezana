<?php
/*
 * Created on Thu Jul 01 2021
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
require_once('../config/std_checklogin.php');
std_checklogin();
require_once('../config/codeGen.php');
require_once('partials/analytics.php');
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php');
        $id  = $_SESSION['id'];
        $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($std = $res->fetch_object()) {
            /* Load System Academic Settings */
            $ret = "SELECT * FROM `ezanaLMS_AcademicSettings` WHERE status = 'Current'  ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($sys = $res->fetch_object()) {
        ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0 text-bold">Hello, <?php echo $std->admno . " " . $std->name; ?></h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right small">
                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-lg-4 col-6">
                                                        <div class="small-box bg-info">
                                                            <div class="inner">
                                                                <h3>Enrolled Course</h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-chalkboard"></i>
                                                            </div>
                                                            <div class="small-box-footer">
                                                                <i class="fas fa-arrow-circle-right"></i>
                                                                <?php echo $std->course; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-6">
                                                        <a href="important_dates">
                                                            <div class="small-box bg-info">
                                                                <div class="inner">
                                                                    <h3>Academic Yr & Semester</h3>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="fas fa-calendar"></i>
                                                                </div>
                                                                <div class="small-box-footer">
                                                                    <i class="fas fa-arrow-circle-right"></i>
                                                                    <?php echo $sys->current_academic_year . " /  " . $sys->current_semester; ?> Semester
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-lg-4 col-6">
                                                        <a href="modules">
                                                            <div class="small-box bg-info">
                                                                <div class="inner">
                                                                    <h3>Enrolled Modules</h3>
                                                                </div>
                                                                <div class="icon">
                                                                    <i class="fas fa-table"></i>
                                                                </div>
                                                                <div class="small-box-footer">
                                                                    <i class="fas fa-arrow-circle-right"></i>
                                                                    <?php echo $enrolled_modules; ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
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
        <?php
            }
        }
        require_once('partials/scripts.php');
        ?>
</body>

</html>