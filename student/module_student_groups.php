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
require_once('../config/checklogin.php');
std_checklogin();
require_once('../config/codeGen.php');
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('partials/header.php');
        require_once('partials/aside.php');
        $view = $_GET['view'];
        $ret = "SELECT * FROM `ezanaLMS_Modules` WHERE id ='$view'  ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($mod = $res->fetch_object()) {
            /* Load Student Details */
            $id = $_SESSION['id'];
            $ret = "SELECT * FROM `ezanaLMS_Students` WHERE id ='$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($std = $res->fetch_object()) {
        ?>
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right small">
                                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                        <li class="breadcrumb-item"><a href="modules">Enrolled Modules</a></li>
                                        <li class="breadcrumb-item active"><?php echo $mod->name; ?> Groups</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="col-md-12 text-center">
                                    <h1 class="m-0 text-bold"><?php echo $mod->name; ?> Student Groups</h1>
                                    <br>
                                </div>
                                <hr>
                                <div class="row">
                                    <!-- Module Side Menu -->
                                    <?php require_once('partials/module_menu.php'); ?>
                                    <!-- Module Side Menu -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Group Code & Name</th>
                                                            <th>Added On</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ret = "SELECT * FROM `ezanaLMS_StudentsGroups` WHERE student_admn = '$std->admno'  ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        while ($g = $res->fetch_object()) {
                                                        ?>

                                                            <tr>
                                                                <td><?php echo $g->code . "<br>" . $g->name; ?></td>
                                                                <td><?php echo date('d M Y, g:ia', strtotime($g->created_at)); ?></td>
                                                                <td>
                                                                    <a class="badge badge-success" href="std_module_group_details.php?group=<?php echo $g->id; ?>&view=<?php echo $mod->id; ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                        View Group Details
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
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
            }
        } ?>
</body>

</html>