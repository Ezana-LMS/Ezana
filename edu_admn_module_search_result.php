<?php
/*
 * Created on Wed Apr 21 2021
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
check_login();
require_once('public/partials/_head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php
        require_once('public/partials/_edu_admn_nav.php');
        ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <?php require_once('public/partials/_brand.php'); ?>
            <!-- Sidebar -->
            <?php require_once('public/partials/_sidebar.php');
            /* Limit Search Querry To Logged In User Faculty Scope */
            $id  = $_SESSION['id'];
            $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
            $stmt = $mysqli->prepare($ret);
            $stmt->execute(); //ok
            $res = $stmt->get_result();
            while ($admin = $res->fetch_object()) {
            ?>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Module Search Results</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="edu_admn_faculties.php?view=<?php echo $admin->school_id; ?>"><?php echo $admin->school; ?></a></li>
                                <li class="breadcrumb-item active">Module Search Results</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        <div class="text-left">
                            <nav class="navbar navbar-light bg-light col-md-12">
                                <form class="form-inline" action="edu_admn_module_search_result.php" method="GET">
                                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Module Name Or Code">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                </form>
                            </nav>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $query = $_GET['query'];
                                $min_length = 5;
                                if (strlen($query) >= $min_length) {
                                    $query = htmlspecialchars($query);
                                    $query = mysqli_real_escape_string($mysqli, $query);
                                    $raw_results = mysqli_query($mysqli, "SELECT * FROM ezanaLMS_Modules WHERE (`name` LIKE '%" . $query . "%') OR (`code` LIKE '%" . $query . "%') AND faculty_id = '$admin->school_id' ");
                                    if (mysqli_num_rows($raw_results) > 0) {
                                        while ($results = mysqli_fetch_array($raw_results)) {
                                ?>
                                            <div class="col-md-12">
                                                <div class="card card-primary ">
                                                    <div class="card-header">
                                                        <a href="edu_admn_module.php?view=<?php echo $results['id']; ?>">
                                                            <h3 class="card-title"><?php echo $results['name']; ?></h3>
                                                            <div class="card-tools text-right">
                                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="module_notices.php?view=<?php echo $results['id']; ?>">
                                                                    Notices & Memos
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="pastpapers.php?view=<?php echo $results['id']; ?>">
                                                                    Past Papers
                                                                </a>
                                                            </li>

                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="course_materials.php?view=<?php echo $results['id']; ?>">
                                                                    Reading Materials
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="class_recordings.php?view=<?php echo $results['id']; ?>">
                                                                    Class Recordings
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="module_assignments.php?view=<?php echo $results['id']; ?>">
                                                                    Assignments
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="student_groups.php?view=<?php echo $results['id']; ?>">
                                                                    Student Groups
                                                                </a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <a href="module_enrollments.php?view=<?php echo $results['id']; ?>">
                                                                    Module Enrollments
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    } else {
                                        echo "<span class ='text-danger'>No Search Results</span>";
                                    }
                                } else {
                                    echo "<span class ='text-danger'> Minimum Search Querry  Length Is " . $min_length . " Characters </span> ";
                                } ?>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Main Footer -->
            <?php require_once('public/partials/_footer.php');
            } ?>
            </div>
        </div>
        <!-- ./wrapper -->
        <?php require_once('public/partials/_scripts.php');
        ?>
</body>

</html>