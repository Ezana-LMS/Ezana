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
                                <h1 class="m-0 text-dark"><?php echo $mod->name; ?> Past Papers</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="std_dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="std_enrolled_modules.php">Enrolled Modules</a></li>
                                    <li class="breadcrumb-item active"><?php echo $mod->name; ?> Past Papers</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="">
                                <nav class="navbar navbar-light bg-light col-md-12">
                                    <form class="form-inline">
                                    </form>
                                    <div class="text-right">
                                        <a title="View <?php echo $mod->name; ?> Past Papers In List Formart" href="std_module_pastpapers.php?view=<?php echo $mod->id; ?>" class="btn btn-primary"><i class="fas fa-list"></i></a>
                                    </div>
                                </nav>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Module Side Menu -->
                                <?php require_once('public/partials/_std_modulemenu.php'); ?>
                                <!-- Module Side Menu -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card-box">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-12 text-right form-inline">
                                                            <div class="form-group mr-2" style="display: none;">
                                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                                    <option value="">Show all</option>
                                                                    <option value="active">Active</option>
                                                                    <option value="disabled">Disabled</option>
                                                                    <option value="suspended">Suspended</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="demo-foo-filtering" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th data-toggle="true">Paper</th>
                                                                <th data-toggle="true">Date Uploaded</th>
                                                                <th data-hide="all">Manage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $ret = "SELECT * FROM `ezanaLMS_PastPapers` WHERE module_name = '$mod->name' AND paper_visibility = 'Available'   ";
                                                            $stmt = $mysqli->prepare($ret);
                                                            $stmt->execute(); //ok
                                                            $res = $stmt->get_result();
                                                            $cnt = 1;
                                                            while ($pastExas = $res->fetch_object()) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $pastExas->paper_name; ?> </td>
                                                                    <td><?php echo date('d M Y - g:i', strtotime($pastExas->created_at)); ?></td>
                                                                    <td>
                                                                        <?php
                                                                        /* If It Lacks upload_solutionSolution Give Option to upload else Download solution */
                                                                        if ($pastExas->solution == '' || $pastExas->solution_visibility != 'Available') {
                                                                            echo
                                                                            "
                                                                                <span class='badge bg-danger'>Solution Unavailable</span>
                                                                            ";
                                                                        } else {
                                                                            echo
                                                                            "
                                                                                <a target='_blank' href= 'std_pdf_solution_viewer.php?id=$pastExas->id&view=$view' class='badge badge-success'>
                                                                                <i class='fas fa-eye'></i>
                                                                                    View Solution
                                                                                </a>
                                                                            ";
                                                                        } ?>
                                                                        <a target="_blank" href="std_pdf_viewer.php?id=<?php echo $pastExas->id; ?>&view=<?php echo $view; ?>" class="badge badge-secondary">
                                                                            <i class="fas fa-eye"></i>
                                                                            View Paper
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php $cnt = $cnt + 1;
                                                            } ?>
                                                        </tbody>
                                                    </table>
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