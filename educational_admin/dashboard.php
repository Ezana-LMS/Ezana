<?php
/*
 * Created on Tue Jun 29 2021
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
require_once('../config/edu_admn_checklogin.php');
edu_admn_checklogin();
require_once('../config/codeGen.php');
require_once('partials/analytics.php');

/* Delete User Requests */
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $adn = "DELETE FROM ezanaLMS_UserRequests WHERE id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $delete);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=dashboard");
    } else {
        $info = "Please Try Again Or Try Later";
    }
}
require_once('partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('partials/aside.php');
        /* Load This Page With Logged In User Session */
        $id  = $_SESSION['id'];
        $ret = "SELECT * FROM `ezanaLMS_Admins` WHERE id ='$id' ";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($admin = $res->fetch_object()) {
        ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-bold">Educational Administrator Dashboard</h1>
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
                                                    <a href="faculties?view=<?php echo $admin->school_id; ?>">
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
                                                    <a href="departments?view=<?php echo $admin->school_id; ?>">
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
                                                    <a href="courses?view=<?php echo $admin->school_id; ?>">
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
                                                    <a href="modules?view=<?php echo $admin->school_id; ?>">
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
                                                    <a href="overall_school_calendar?view=<?php echo $admin->school_id; ?>">

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
                                                    <a href="lecturers?view=<?php echo $admin->school_id; ?>">

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
                                                    <a href="students?view=<?php echo $admin->school_id; ?>">
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
                                            <hr>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="card col-md-12">
                                                        <div class="card-header">
                                                            <h3 class="card-title">
                                                                Users Requests
                                                            </h3>
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <ul class="todo-list" data-widget="todo-list">
                                                                <?php
                                                                /* Load User Requests */
                                                                $ret = "SELECT * FROM `ezanaLMS_UserRequests` ORDER BY `created_at` ASC   ";
                                                                $stmt = $mysqli->prepare($ret);
                                                                $stmt->execute(); //ok
                                                                $res = $stmt->get_result();
                                                                while ($req = $res->fetch_object()) {
                                                                ?>
                                                                    <li>
                                                                        <a href="requests?view=<?php echo $req->id; ?>">
                                                                            <span class="text"><?php echo substr($req->request, 0, 50); ?>...</span>
                                                                            <small class="badge badge-success"><i class="far fa-clock"></i> <?php echo date('d M Y - g:ia', strtotime($req->created_at)); ?></small>
                                                                            <div class="progress">
                                                                                <div class="progress-bar" style="width: <?php echo $req->progress; ?>%"></div>
                                                                            </div>
                                                                            <span class="progress-description">
                                                                                Progress : <?php echo $req->progress; ?> %
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <br>
                                                                <?php
                                                                } ?>
                                                            </ul>
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
                    <?php require_once('partials/footer.php'); ?>
                </div>
            </div>
            <!-- ./wrapper -->
        <?php
        }
        require_once('partials/scripts.php');
        ?>
</body>

</html>