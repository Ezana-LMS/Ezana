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
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
            /* Class Recoding */
            $clip = $_GET['clip'];
            $ret = "SELECT * FROM `ezanaLMS_ClassRecordings` WHERE id= '$clip'";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            $cnt = 1;
            while ($cr = $res->fetch_object()) {
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
                                    <h1 class="m-0 text-dark"><?php echo $cr->class_name; ?> Clip </h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="std_dashboard.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="std_enrolled_modules.php">Enrolled Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?> Play Class Recording</li>
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
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class='embed-responsive embed-responsive-16by9'>
                                                                    <video controls width='320px' height='200px' class='embed-responsive-item' id='uploaded_video' src='public/uploads/EzanaLMSData/ClassVideos/<?php echo $cr->video; ?>' allowfullscreen>
                                                                </div>
                                                                <hr>
                                                                <p>
                                                                    <?php echo $cr->details; ?>
                                                                </p>
                                                                <br>
                                                                <h3 class="text-center">Course Reading Materials</h3>
                                                                <?php
                                                                $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE module_code ='$mod->code' ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                $cnt = 1;
                                                                while ($rm = $res->fetch_object()) {
                                                                ?>
                                                                    <div class="col-md-4">
                                                                        <div class="card">
                                                                            <a href="public/uploads/EzanaLMSData/Reading_Materials/<?php echo $rm->readingMaterials; ?>" target="_blank">
                                                                                <div class="card-body">
                                                                                    <p class="card-title"><?php echo $rm->readingMaterials; ?></p>
                                                                                    <br>
                                                                                    <hr>
                                                                                    <div class="text-center">
                                                                                        <?php
                                                                                        /* Show External Link */
                                                                                        if ($rm->external_link == '') {
                                                                                            /* Yall Know Silence Is Best Answer */
                                                                                        } else {
                                                                                            echo
                                                                                            "
                                                                                                    <a target='_blank' href= '$rm->external_link' class='btn btn-outline-success'>
                                                                                                        Open Link
                                                                                                    </a>
                                                                                                ";
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="text-center">
                                                                    <?php
                                                                    /* If Class Has External Link */
                                                                    if ($cr->external_link == '') {
                                                                    } else {
                                                                        echo
                                                                        "
                                                                        <a target='_blank' href= '$cr->external_link' class='btn btn-outline-success'>
                                                                            Clip External Link
                                                                        </a>
                                                                        ";
                                                                    }
                                                                    ?>
                                                                </div>
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
        <?php }
        }
        require_once('public/partials/_scripts.php'); ?>
</body>

</html>