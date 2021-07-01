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
$time = time(); /* Timestamp Everything */

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
                                <h1 class="m-0 text-bold"><?php echo $mod->name; ?> Past Papers</h1>
                                <br>
                                <a title="View <?php echo $mod->name; ?> Past Papers In Tabular Formart" href="module_pastpapers_tabular?view=<?php echo $mod->id; ?>" class="btn btn-primary"><i class="fas fa-table"></i> List View</a>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('partials/module_menu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="row">
                                                <?php
                                                $now =  date('m/d/Y g:ia', strtotime("now"));
                                                $ret = "SELECT * FROM `ezanaLMS_PastPapers` WHERE module_name = '$mod->name' AND paper_visibility <= '$now' AND solution_visibility <= '$now'  ";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($pastExas = $res->fetch_object()) {
                                                ?>
                                                    <div class="col-md-6">
                                                        <div class="card card-primary card-outline">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><?php echo $pastExas->paper_name; ?></h5>
                                                                <br>
                                                                <hr>
                                                                <div class="text-center">
                                                                    <a target="_blank" href="../Data/PastPapers/<?php echo $pastExas->pastpaper; ?>" class="btn btn-outline-success">
                                                                        View Paper
                                                                    </a>
                                                                    <?php
                                                                    if ($pastExas->solution == '') {
                                                                        /* Nothing */
                                                                    } else {
                                                                        echo
                                                                        "
                                                                        <a target='_blank' href= '../Data/PastPapers/$pastExas->solution' class='btn btn-outline-success'>
                                                                            View Solution
                                                                        </a>
                                                                        ";
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <small class="text-muted">Uploaded: <?php echo date('d-M-Y g:ia', strtotime($pastExas->created_at)); ?></small>
                                                                <br>
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
        <?php require_once('partials/scripts.php');
        }  ?>
</body>

</html>