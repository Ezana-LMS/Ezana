<?php
/*
 * Created on Wed Jun 30 2021
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
require_once('../config/lec_checklogin.php');
lec_check_login();
require_once('../config/codeGen.php');
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
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
            while ($cr = $res->fetch_object()) {
        ?>
                <!-- /.navbar -->
                <!-- Main Sidebar Container -->
                <?php require_once('partials/aside.php'); ?>

                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right small">
                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="modules">Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12 text-center">
                                    <h1 class="m-0 text-bold"><?php echo $cr->class_name; ?> Class Recording</h1>
                                    <br>
                                    <span class="btn btn-primary"><i class="fas fa-arrow-left"></i><a href="module_class_recordings?view=<?php echo $mod->id; ?>" class="text-white">Back</a></span>
                                </div>
                                <hr>
                                <div class="row">
                                    <!-- Module Side Menu -->
                                    <?php require_once('partials/module_menu.php'); ?>
                                    <!-- Module Side Menu -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <?php
                                            if ($cr->clip_type == 'Clip') {
                                                echo "
                                                <div class='row'>
                                                    <div class='card card-primary col-md-12 card-outline'>
                                                        <div class='card-body'>
                                                            <div class='embed-responsive embed-responsive-16by9'>
                                                                <video controls width='300px' height='200px' class='embed-responsive-item' id='uploaded_video' src='../Data/Class_Recordings/$cr->video' allowfullscreen>
                                                            </div>
                                                            <br>
                                                            <h5 class=text-center>Class Recording Transcription</h5>
                                                            <p>
                                                                $cr->details
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            ";
                                            } else {
                                                echo
                                                "
                                                <div class='row'>
                                                    <div class='card card-primary col-md-12 card-outline'>
                                                        <div class='card-body'>
                                                            <div class='text-center'>
                                                                <a target='_blank' href= '$cr->external_link' class='btn btn-outline-success'>
                                                                    Open  Attached Class Recording Link
                                                                </a>
                                                            </div>
                                                            <br>
                                                            <h5 class=text-center>Class Recording Transcription</h5>
                                                            <p>
                                                                $cr->details
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                ";
                                            }
                                            ?>

                                            <h5 class="text-center card-header">Module Reading Materials</h5>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="row">

                                                    <?php
                                                    $ret = "SELECT * FROM `ezanaLMS_ModuleRecommended` WHERE module_code ='$mod->code'  ORDER BY `created_at` ASC  ";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->execute(); //ok
                                                    $res = $stmt->get_result();
                                                    while ($rm = $res->fetch_object()) {
                                                    ?>
                                                        <div class="col-md-4">
                                                            <div class="card card-primary card-outline">
                                                                <div class="card-body">
                                                                    <p class="card-title">
                                                                        <?php
                                                                        /* Trim Topic */
                                                                        if (strlen($rm->topic) > 30) {
                                                                            $trimstring = substr($rm->topic, 0, 30) . '...';
                                                                        } else {
                                                                            $trimstring = $rm->topic;;
                                                                        }
                                                                        echo $trimstring
                                                                        ?>
                                                                    </p>
                                                                    <br>
                                                                    <hr>
                                                                    <div class="text-center">
                                                                        <a target='_blank' href='../Data/Reading_Materials/<?php echo $rm->readingMaterials; ?>' class='btn btn-outline-success'>
                                                                            View
                                                                        </a>
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
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Main Footer -->
                        <?php require_once('partials/footer.php');
                        ?>
                    </div>
                </div>
                <!-- ./wrapper -->
        <?php }
        }
        require_once('partials/scripts.php'); ?>
</body>

</html>